<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiServices\BlizzardApiService;
use App\Http\Requests\BlizzardCharacterRequest;
use App\Http\Requests\CharacterAchievmentRequest;
use Illuminate\Support\Facades\Redis;
use Exception;

class WarcraftController extends Controller
{
    private $blizzApiService;

    public function __construct(BlizzardApiService $service)
    {
        $this->blizzApiService = $service;
    }

    /**
     * Stores the returned token in DB for future use
     */
    private function storeUserToken($token, $expires)
    {
        $user = auth()->user();
        $user->token = $token;
        $user->token_valid_to = \Carbon\Carbon::now()->add($expires, 'seconds');
        $user->save();

        session()->put('blizzAccessToken', $token);
    }

    /**
     * Store data in cache if no data exists for given key
     */
    private function storeRedisData($key, $data)
    {
        try {
            if (!Redis::exists($key)) {
                Redis::set($key, $data);
            }
        } catch (Exception $ex) {
            info($ex);
        }
    }

    /**
     * get cached data, if any
     */
    private function getRedisProfileData($key)
    {
        try {
            if (!is_null($key)) {
                return json_decode(Redis::get($key));
            }
        } catch (Exception $ex) {
            info($ex);
        }
    }

    /**
     * Authorize link for the client to make requests on behalf of the user
     */
    public function authorizeAccess()
    {
        $target = 'https://eu.battle.net/oauth/authorize';
        $queryString = http_build_query([
            'auth_flow'     => 'auth_code',
            'scope'         => 'wow.profile',
            'client_id'     => env('BLIZZ_CLIENT_ID'),
            'response_type' => 'code',
            'redirect_uri'  => 'http://127.0.0.1:8000/createtoken',
        ]);

        $url = $target.'?'.$queryString;

        return redirect($url);
    }

    /**
     * Creates a token for querying the API on behalf of the user
     */
    public function createToken(Request $request)
    {
        $tokenResponse = $this->blizzApiService->createAccessToken($request->code);

        if ($this->blizzApiService->getError()) {
            return redirect()->route('dashboard')->withErrors(['token' => $this->blizzApiService->getError()]);
        }

        $this->storeUserToken($tokenResponse->access_token, $tokenResponse->expires_in);

        return redirect()->route('dashboard');
    }

    /**
     * Get account data via request or read from cache
     */
    public function getProfile()
    {
        $profileData = $this->getRedisProfileData('profile-'.auth()->user()->token);

        if (!$profileData) {
            $profileData = $this->blizzApiService->getProfile(session()->get('blizzAccessToken'));

            if ($this->blizzApiService->getError()) {
                return redirect()->route('dashboard')->withErrors(['token' => $this->blizzApiService->getError()]);
            }
        }

        $this->storeRedisData('profile-'.auth()->user()->token, json_encode($profileData));

        return view('wow.profile', ['accounts' => $profileData]);
    }

    /**
     * Get data for specific character
     */
    public function getCharacterData(BlizzardCharacterRequest $request)
    {
        $charactedData = $this->blizzApiService->getCharacterInfo($request->userToken, $request->realmID, $request->charID);

        if ($this->blizzApiService->getError()) {
            return back()->withErrors(['token' => $this->blizzApiService->getError()]);
        }

        return view('wow.character', ['characterData' => $charactedData]);
    }

    /**
     * Get achievments for specific character
     */
    public function getCharacterAchievments(CharacterAchievmentRequest $request)
    {
        $achievmentsData = $this->blizzApiService->getAchievments($request->userToken, $request->realmSlug, $request->characterName);

        if ($this->blizzApiService->getError()) {
            return back()->withErrors(['token' => $this->blizzApiService->getError()]);
        }

        return view('wow.character-achievments', ['achievmentsData' => $achievmentsData]);
    }
}
