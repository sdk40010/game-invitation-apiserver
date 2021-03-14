<?php

namespace App\Models;

use App\Models\UUIDModel;
use App\Models\TimeStampFormat;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Invitation extends UUIDModel
{
    use TimeStampFormat;

    /**
     * 常にロードするリレーション
     */
    protected $with = ['user', 'tags'];

    /**
     * 常にロードするリレーションの件数
     */
    protected $withCount = ['participants'];

    /**
     * Carbonインスタンスへ変換するカラム
     */
    protected $dates = [
        'start_time',
        'end_time',
    ];

    /**
     * モデルの配列に追加するアクセサ
     */
    protected $appends = [
        'start_in',
        'interval',
    ];

    /**
     * 複数代入可能な属性
     */
    protected $fillable = [
        'use_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'capacity',
        'img_url'
    ];
    
    /**
     * 募集の作成者
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 募集の作成者（プロフィール用の情報付）
     */
    public function userWithProfileInfo()
    {
        return $this->load(['user' => function ($query) {
            $query->withCount([ // 投稿履歴、参加履歴、フレンドの件数
                'invitationsPosted', 
                'invitationsParticipatedIn',
                'friends',
                'inverseFriends'
            ])
            ->addSelect([ // ユーザーと募集の投稿者のフレンド関係
                'friendship_status' => function ($query) {
                    $query
                        ->selectRaw('case when count(*) = 1 then friendships.status else null end')
                        ->from('friendships')
                        ->where(function ($query) {
                            $query->where([ // 自分->相手のフレンド関係の場合
                                ['user_id', Auth::user()->id],
                                ['friend_id', $this->user->id]
                            ]);
                        })
                        ->orWhere(function ($query){
                            $query->where([ // 自分->相手のフレンド関係の場合
                                ['user_id', $this->user->id],
                                ['friend_id', Auth::user()->id]
                            ]);
                        })
                        ->groupBy('friendships.user_id', 'friendships.status');
                }
            ]);
        }]);
    }

    /**
     * 募集の参加者一覧
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'participations')
            ->as('participation')
            ->withTimestamps()
            ->using('App\Models\Participation');
    }

    /**
     * 募集に付けられたタグ一覧
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tagmaps')
            ->withTimestamps();
    }

    /**
     * コメント一覧
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 開始時刻の取得
     */
    public function getStartTimeAttribute($value)
    {
        $startTime = Carbon::parse($value);
        return $startTime->format('Y/m/d H:i');
    }

    /**
     * 終了時刻の取得
     */
    public function getEndTimeAttribute($value)
    {
        $endTime = Carbon::parse($value);
        return $endTime->format('Y/m/d H:i');
    }

    /**
     * 現在から開始時刻までの差分を取得する
     */
    public function getStartInAttribute()
    {
        return Carbon::parse($this->attributes['start_time'])->diffForHumans();
    }

    /**
     * 開始時刻と終了時刻の差分を取得する
     */
    public function getIntervalAttribute()
    {
        $startTime = Carbon::parse($this->attributes['start_time']);
        $endTime = Carbon::parse($this->attributes['end_time']);

        $interval = $endTime->diffAsCarbonInterval($startTime);
        $parts = $interval->totalHours < 1
            ? 1 // 1時間未満のときは分のみ表示する
            : 2; // 1時間以上のときは時間と分を表示する

        return $interval->forHumans(['parts' => $parts]);
        
    }
}
