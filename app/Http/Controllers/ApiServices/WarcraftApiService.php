<?php

namespace App\Http\Controllers\ApiServices;

use App\Models\Warcraft;
use Illuminate\Support\Facades\Http;

class WarcraftApiService extends BlizzardApiService
{
    /**
     * Gets the user's profile data
     */
    public function getProfile(string $accessToken)
    {
        $target = $this->apiBaseEndpoint . Warcraft::WOW_PROFILE_BASE;
        $response = HTTP::get($target, [
            'namespace'    => Warcraft::WOW_PROFILE_NAMESPACE,
            'access_token' => $accessToken
        ]);

        return $response->throw()->json();
    }

    /**
     * Gets a character's details
     */
    public function getCharacterInfo(string $accessToken, int $realmId, int $characterId)
    {
        $target = $this->apiBaseEndpoint . Warcraft::WOW_PROFILE_BASE.'/protected-character/'.$realmId .'-'.$characterId;
        $response = HTTP::get($target, [
            'namespace'    => Warcraft::WOW_PROFILE_NAMESPACE,
            'access_token' => $accessToken
        ]);

        return $response->throw()->json();
    }

    /**
     * Gets a character's achievments
     */
    public function getAchievments(string $accessToken, string $realm, string $characterName)
    {
        $target = $this->apiBaseEndpoint.Warcraft::WOW_CHARACTER_BASE_URL.$realm.'/'.$characterName.'/achievements';

        $response = HTTP::get($target, [
            'namespace'     => Warcraft::WOW_PROFILE_NAMESPACE,
            'access_token'  => $accessToken,
        ]);

        return $response->throw()->json();
    }
}
