<?php

namespace App\Models;

use App\Models\Invitation;
use App\Models\Notification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firebase_uid', 'name', 'email', 'icon_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function participatingInvitations()
    {
        return $this->belongsTo(Invitation::class, 'participations');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships');
    }
}
