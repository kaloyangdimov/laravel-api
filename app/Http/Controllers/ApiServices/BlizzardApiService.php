<?php

namespace App\Http\Controllers\ApiServices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlizzardApiService extends Controller
{
    /**
     * API call for access token creation
     *
     * @return
     */
    public function createAccessToken(string $code)
    {
        $target = 'https://eu.battle.net/oauth/token/';
        $response = HTTP::asForm()->withBasicAuth(env('BLIZZ_CLIENT_ID'), env('BLIZZ_CLIENT_SECRET'))
        ->post($target, [
            'grant_type'   => 'authorization_code',
            'redirect_uri' => 'http://127.0.0.1:8000/createtoken',
            'code'         => $code,
            'client_id'    => env('BLIZZ_CLIENT_ID')
        ]);

        if ($response->ok()) {
            return json_decode($response->body());
        } else {
            return false;
        }
    }

    public function getProfile(string $accessToken)
    {
        $target = 'https://eu.api.blizzard.com/profile/user/wow';
        $response = HTTP::get($target, [
            'namespace'    => 'profile-eu',
            'access_token' => $accessToken
        ]);

        if ($response->ok()) {
            return json_decode($response->body())->wow_accounts;
        } else {
            return false;
        }
    }

    public function getCharacterInfo(string $accessToken, int $realmId, int $characterId)
    {
        $target = 'https://eu.api.blizzard.com/profile/user/wow/protected-character/'.$realmId .'-'.$characterId;
        $response = HTTP::get($target, [
            'namespace'    => 'profile-eu',
            'access_token' => $accessToken
        ]);

        if ($response->ok()) {
            return json_decode($response->body());
        } else {
            return false;
        }
    }
}
