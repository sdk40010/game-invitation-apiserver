<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $casts = [
        'date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime'
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
