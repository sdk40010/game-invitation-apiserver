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
        'firebase_uid',
        'name',
        'email',
        'icon_url'
    ];

    /**
     * 隠蔽する属性
     */
    protected $hidden = ['pivot'];

    /**
     * キャストする属性
     */
    protected $casts = [
        'id' => 'int'
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
    public static function withProfile($query = null)
    {
        $query = $query ?? static::query();

        // 投稿件数、参加件数、フォロー数、フォロワー数
        $query->withCount([
            'invitationsPosted', 
            'invitationsParticipatedIn',
            'followings',
            'followers',
        ]);

        if (Auth::user()) {
            $createClosure = function ($whereClauses) {
                return function ($query) use ($whereClauses) {
                    $query
                        ->selectRaw('case when count(*) = 1 then true else false end')
                        ->from('followings');
                    
                    foreach ($whereClauses as $clauses) {
                        $query->whereRaw(...$clauses);
                    }
                };
            };
    
            $query->addSelect([
                'is_following' => $createClosure([ // ログインユーザーがユーザーをフォローしている
                    ['user_id = ?', Auth::user()->id],
                    ['following_id = users.id']
                ]),
                'is_follower' => $createClosure([ // ユーザーがログインユーザーのフォロワーである
                    ['user_id = users.id'],
                    ['following_id = ?', Auth::user()->id]
                ])
            ]);
        }

        return $query;
    }

    


}
