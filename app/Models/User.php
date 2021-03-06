<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const ACTIVE_YES = 1;
    const ACTIVE_NO = 0;

    const IS_ADMIN_YES = 1;
    const IS_ADMIN_NO = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'active',
        'is_admin',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getActiveStates()
    {
        return [
            self::ACTIVE_YES => __('custom.yes'),
            self::ACTIVE_NO  => __('custom.no'),
        ];
    }

    public static function getAdminStates()
    {
        return [
            self::IS_ADMIN_YES => __('custom.yes'),
            self::IS_ADMIN_NO  => __('custom.no'),
        ];
    }

    public static function getAdminUsers()
    {
        return  self::where('is_admin', User::IS_ADMIN_YES)->where('active', User::ACTIVE_YES)->get();
    }
}
