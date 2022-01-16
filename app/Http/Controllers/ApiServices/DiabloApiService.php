<?php

namespace App\Http\Controllers\ApiServices;

use App\Models\Diablo;
use Illuminate\Support\Facades\Http;

class DiabloApiService extends BlizzardApiService
{
    public function getApiAccount(string $accessToken)
    {
        $target = $this->apiBaseEndpoint . Diablo::PROFILE_BASE;
        $userInfo = $this->userInfo($accessToken);

        $response = HTTP::get($target.$userInfo->battletag.'/', [
            'access_token' => $accessToken,
        ]);

        return $response->throw()->json();
    }

    public function getCharacter(string $accessToken, int $heroId)
    {
        $target = $this->apiBaseEndpoint . Diablo::PROFILE_BASE;
        $response = HTTP::get($target.$this->userInfo($accessToken)->battletag.'/hero/'.$heroId, [
            'access_token' => $accessToken
        ]);

        return $response->throw()->json();
    }
}
