<?php

namespace App\Models;

use App\Models\UUIDModel;

class Invitation extends UUIDModel
{
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

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
}
