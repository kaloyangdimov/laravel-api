<?php

namespace App\Http\Controllers\ApiServices;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Http;

class BlizzardApiService extends Controller
{
    protected $apiBaseEndpoint = 'https://eu.api.blizzard.com/';

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
            'redirect_uri' => env('APP_URL').'/createtoken',
            'code'         => $code,
            'client_id'    => env('BLIZZ_CLIENT_ID')
        ]);

        return $response->throw()->json();
    }

    public function generateAuthorizationLink()
    {
        $target = 'https://eu.battle.net/oauth/authorize';
        $queryString = http_build_query([
            'auth_flow'     => 'auth_code',
            'scope'         => 'wow.profile',
            'client_id'     => env('BLIZZ_CLIENT_ID'),
            'response_type' => 'code',
            'redirect_uri'  => env('APP_URL').'/createtoken',
        ]);

        $url = $target.'?'.$queryString;

        return $url;
    }

    public function userInfo(string $accessToken)
    {
        $target = 'https://eu.battle.net/oauth/userinfo';
        $response = HTTP::get($target, [
            'access_token'  => $accessToken,
        ]);

        if ($response->failed()) {
            throw new Exception(json_decode($response->body())->error_description);
            return;
        }

        $response = json_decode($response->body());
        $response->battletag = urlencode($response->battletag); // prepare data for direct url insertion

        return $response;
    }
}
