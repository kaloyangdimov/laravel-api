<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warcraft extends Model
{
    use HasFactory;

    const WOW_API_BASE_ENDPOINT = 'https://eu.api.blizzard.com/';
    const WOW_PROFILE_BASE = 'profile/user/wow';
    const WOW_PROFILE_NAMESPACE = 'profile-eu';
    const WOW_CHARACTER_BASE_URL = 'profile/wow/character/';
}
