<?php

namespace App\Http\Controllers\ApiServices;

use App\Http\Controllers\Controller;
use App\Models\Warcraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlizzardApiService extends Controller
{
    private $error;

    private function setError($error)
    {
        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }

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

        if ($response->failed()) {
            $this->setError(json_decode($response->body())->error_description);
            return;
        }

        return json_decode($response->body());
    }

    /**
     * Gets the user's profile data
     */
    public function getProfile(string $accessToken)
    {
        $target = Warcraft::WOW_API_BASE_ENDPOINT . Warcraft::WOW_PROFILE_BASE;
        $response = HTTP::get($target, [
            'namespace'    => Warcraft::WOW_PROFILE_NAMESPACE,
            'access_token' => $accessToken
        ]);

        if ($response->failed()) {
            $this->setError(json_decode($response->body())->detail);
            return;
        }

        return json_decode($response->body())->wow_accounts;
    }

    /**
     * Gets a character's details
     */
    public function getCharacterInfo(string $accessToken, int $realmId, int $characterId)
    {
        $target = Warcraft::WOW_API_BASE_ENDPOINT . Warcraft::WOW_PROFILE_BASE.'/protected-character/'.$realmId .'-'.$characterId;
        $response = HTTP::get($target, [
            'namespace'    => Warcraft::WOW_PROFILE_NAMESPACE,
            'access_token' => $accessToken
        ]);

        if ($response->failed()) {
            $this->setError(json_decode($response->body())->error_description);
            return;
        }

        return json_decode($response->body());
    }

    /**
     * Gets a character's achievments
     */
    public function getAchievments(string $accessToken, string $realm, string $characterName)
    {
        $target = Warcraft::WOW_API_BASE_ENDPOINT.Warcraft::WOW_CHARACTER_BASE_URL.$realm.'/'.$characterName.'/achievements';

        $response = HTTP::get($target, [
            'namespace'     => Warcraft::WOW_PROFILE_NAMESPACE,
            'access_token'  => $accessToken,
        ]);

        if ($response->failed()) {
            $this->setError(json_decode($response->body())->error_description);
            return;
        }

        return json_decode($response->body());
    }
}
