<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiServices\DiabloApiService;
use App\Http\Controllers\Services\CachingService;
use App\Http\Requests\DiabloHeroRequest;
use Exception;
use Illuminate\Http\Request;

class DiabloController extends Controller
{
    private $diabloService;

    public function __construct(DiabloApiService $service)
    {
        $this->diabloService = $service;
    }

    /**
     * Get account data via request or read from cache
     */
    public function getApiAccount()
    {
        $cachingService = new CachingService();
        $profileData = $cachingService->getRedisProfileData('diablo-profile-'.auth()->user()->token);

        if (!$profileData) {
            try {
                $profileData = $this->diabloService->getApiAccount(session()->get('blizzAccessToken'));
            } catch (Exception $ex) {
                info($ex->getMessage());
                return redirect()->route('dashboard')->withErrors(['token' => $ex->getMessage()]);
            }
        }

        $cachingService->storeRedisData('diablo-profile-'.auth()->user()->token, json_encode($profileData));

        return view('diablo.profile', ['profile' => $profileData]);
    }

    /**
     * Get account data via request or read from cache
     */
    public function getCharacterInfo(DiabloHeroRequest $request)
    {
        try {
            $characterData = $this->diabloService->getCharacter(session()->get('blizzAccessToken'), $request->heroId);
        } catch (Exception $ex) {
            return redirect()->route('dashboard')->withErrors(['token' => $ex->getMessage()]);
        }

        return view('diablo.character', ['character' => $characterData]);
    }
}
