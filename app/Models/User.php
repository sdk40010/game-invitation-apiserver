<?php

namespace App\Models;

use App\Models\Invitation;
use App\Models\Notification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            ->using('App\Models\Participation')
            ->withTimestamps();
    }

    /**
     * ユーザー宛の通知一覧
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * フォロー一覧
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'followings', 'user_id', 'following_id')
            ->withTimestamps();
    }

    /**
     * フォロワー一覧
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followings', 'following_id', 'user_id')
            ->withTimestamps();
    }


    /**
     * ユーザーのプロフィールをEagerロードする
     */
    public function withProfile($query = null, $userIdColumn = null)
    {
        $query = $query ?? $this->query();
        $userIdColumn = $userIdColumn ?? 'users.id';

        $query->withCount([
            'invitationsPosted', 
            'invitationsParticipatedIn',
            'followings',
            'followers',
        ]);

        if (Auth::user()) {
            $createClosure = function ($whereClouse) {
                return function ($query) use ($whereClouse) {
                    $query
                        ->selectRaw('case when count(*) = 1 then true else false end')
                        ->from('followings')
                        ->where($whereClouse);
                };
            };
    
            $query->addSelect([
                'is_following' => $createClosure([ // ログインユーザーがユーザーをフォローしている
                    ['user_id', Auth::user()->id],
                    ['following_id', $userIdColumn]
                ]),
                'is_follower' => $createClosure([ // ユーザーがログインユーザーのフォロワーである
                    ['user_id', $userIdColumn],
                    ['following_id', Auth::user()->id]
                ])
            ]);
        }

        return $query;
    }

    // public function withProfile($query = null, $userIdColumn = null)
    // {
    //     $query = $query ?? $this->query();
    //     $userIdColumn = $userIdColumn ?? 'users.id';

    //     $query->withCount([
    //         'invitationsPosted', 
    //         'invitationsParticipatedIn',
    //         'followings',
    //         'followers',
    //     ]);

    //     if (Auth::user()) {
    //         $createSubQuery = function ($column, $whereClouse) {
    //             return DB::table('followings')
    //                 ->select($column)
    //                 ->where($whereClouse)
    //                 ->toSql();
    //         };
    
            

    //         $query->selectRaw('if users.id in ('.$createSubQuery('').')');
            

    //     }

    //     return $query;
    // }


}
