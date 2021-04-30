<?php

namespace App\Models;

use App\Models\UUIDModel;
use App\Models\TimeStampFormat;
use Illuminate\Support\Carbon;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Invitation extends UUIDModel
{
    use TimeStampFormat;

    /**
     * 常にロードするリレーション
     */
    protected $with = [
        'user',
        'tags',
    ];

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
     * キャストする属性
     */
    protected $casts = [
        'user_id' => 'int',
        'capacity' => 'int'
    ];
    
    /**
     * 募集の作成者
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * プロフィール情報を付与するためのクロージャを取得する
     */
    public function userWithProfile()
    {
        return function ($query) {
            return User::withProfile($query);
        };
    }

    /**
     * 募集の参加者一覧
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'participations')
            ->as('participation')
            ->using('App\Models\Participation')
            ->withTimestamps();
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
