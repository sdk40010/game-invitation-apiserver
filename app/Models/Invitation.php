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
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function participatingUsers()
    {
        return $this->belongsToMany(User::class, 'participations');
    }

    public function tags()
    {
        return $this->belongsTo(Tag::class, 'tagmaps');
    }
}
