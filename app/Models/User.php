<?php

namespace App\Models;

use App\Models\Invitation;
use App\Models\Notification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'firebase_uid', 'name', 'email', 'icon_url'
    ];

    /**
     * ユーザーが投稿した募集一覧
     */
    public function invitationsPosted()
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * ユーザーが参加した募集一覧
     */
    public function invitationsParticipatedIn()
    {
        return $this->belongsToMany(Invitation::class, 'participations')
            ->as('participation')
            ->withTimestamps()
            ->using('App\Models\Participation');;
    }

    /**
     * ユーザー宛の通知一覧
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * ユーザーがフレンド申請した人（自分->相手）
     *  */ 
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->withTimestamps();
    }

    /**
     * ユーザーにフレンド申請した人（相手->自分）
     */
    public function inverseFriends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * 
     */
    public function isfollowing()
    {
        // return $this->
    }
}
