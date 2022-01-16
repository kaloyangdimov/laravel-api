<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Exception;

class CachingService extends Controller
{
    /**
     * Stores the returned token in DB for future use
     */
    public function storeUserToken(string $token, int $expires)
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
    public function storeRedisData($key, $data)
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
    public function getRedisProfileData($key)
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
     * get cached data, if any
     */
    public function deleteRedisProfileData(array $keys)
    {
        try {
            foreach ($keys as $singleKey) {
                Redis::del($singleKey.auth()->user()->token);
            }

            return true;
        } catch (Exception $ex) {
            info($ex);
        }
    }
}
