<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiServices\WarcraftApiService;
use App\Http\Controllers\Services\CachingService;
use App\Http\Requests\BlizzardCharacterRequest;
use App\Http\Requests\CharacterAchievmentRequest;
use Exception;

class WarcraftController extends Controller
{
    private $warcraftApiService;

    public function __construct(WarcraftApiService $service)
    {
        $this->warcraftApiService = $service;
    }

    /**
     * Authorize link for the client to make requests on behalf of the user
     */
    public function authorizeAccess()
    {
        $url = $this->warcraftApiService->generateAuthorizationLink();

        return redirect($url);
    }

    /**
     * Creates a token for querying the API on behalf of the user
     */
    public function createToken(Request $request)
    {
        $cachingService = new CachingService();
        try {
            $tokenResponse = $this->warcraftApiService->createAccessToken($request->code);
        } catch (Exception $ex) {
            return redirect()->route('dashboard')->withErrors(['token' => $ex->getMessage()]);
        }

        $cachingService->storeUserToken($tokenResponse['access_token'], $tokenResponse['expires_in']);

        return redirect()->route('dashboard');
    }

    /**
     * Get account data via request or read from cache
     */
    public function getProfile()
    {
        $cachingService = new CachingService();
        $profileData = $cachingService->getRedisProfileData('wow-profile-'.auth()->user()->token);

        if (!$profileData) {
            try {
                $profileData = $this->warcraftApiService->getProfile(session()->get('blizzAccessToken'));
            } catch (Exception $ex) {
                return redirect()->route('dashboard')->withErrors(['error' => $ex->getMessage()]);
            }
        }

        $cachingService->storeRedisData('wow-profile-'.auth()->user()->token, json_encode($profileData));

        return view('wow.profile', ['accounts' => $profileData]);
    }

    /**
     * Get data for specific character
     */
    public function getCharacterData(BlizzardCharacterRequest $request)
    {
        try {
            $charactedData = $this->warcraftApiService->getCharacterInfo($request->userToken, $request->realmID, $request->charID);
        } catch (Exception $ex) {
            return back()->withErrors(['error' => $ex->getMessage()]);
        }

        return view('wow.character', ['characterData' => $charactedData]);
    }

    /**
     * Get achievments for specific character
     */
    public function getCharacterAchievments(CharacterAchievmentRequest $request)
    {
        try {
            $achievmentsData = $this->warcraftApiService->getAchievments($request->userToken, $request->realmSlug, $request->characterName);
        } catch (Exception $ex) {
            return back()->withErrors(['error' => $ex->getMessage()]);
        }

        return view('wow.character-achievments', ['achievmentsData' => $achievmentsData]);
    }
}
