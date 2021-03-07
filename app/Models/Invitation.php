<?php

namespace App\Models;

use App\Models\UUIDModel;
use App\Models\TimeStampFormat;
use Illuminate\Support\Carbon;

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
     *
     * @return string
     */
    public function getStartTimeAttribute($value)
    {
        $startTime = Carbon::parse($value);
        return $startTime->format('Y/m/d H:i');
    }

    /**
     * 終了時刻の取得
     *
     * @return string
     */
    public function getEndTimeAttribute($value)
    {
        $endTime = Carbon::parse($value);
        return $endTime->format('Y/m/d H:i');
    }
}
