<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiServices\BlizzardApiService;
use Illuminate\Support\Facades\Redis;

class WarcraftController extends Controller
{
    public function __construct(BlizzardApiService $service)
    {
        $this->service = $service;
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
        $tokenResponse = $this->service->createAccessToken($request->code);

        $user = auth()->user();
        $user->token = $tokenResponse->access_token;
        $user->token_valid_to = \Carbon\Carbon::now()->add($tokenResponse->expires_in, 'seconds');
        $user->save();

        session()->put('blizzAccessToken', $tokenResponse->access_token);

        return redirect()->route('dashboard');
    }

    /**
     * Get account data via request or read from cache
     */
    public function getProfile()
    {
        $redis = false;

        if (isset(auth()->user()->token) && Redis::get('profile-'.auth()->user()->token)) {
            $response = json_decode(Redis::get('profile-'.auth()->user()->token));
            $redis = true;
        } else {
            $response = $this->service->getProfile(session()->get('blizzAccessToken'));
        }

        if ($response) {
            if (!$redis) {
                Redis::set('profile-'.auth()->user()->token, json_encode($response));
            }

            return view('wow.profile', ['accounts' => $response]);
        }

        return back()->withErrors(['character' => __('custom.no_char')]);
    }

    public function getCharacterData(Request $request)
    {
        $charactedData = $this->service->getCharacterInfo(auth()->user()->token, $request->realmID, $request->charID);

        return view('wow.character', ['characterData' => $charactedData]);
    }

}
