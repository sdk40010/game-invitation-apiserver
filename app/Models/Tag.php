<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'count'
    ];

    public function invitations()
    {
        return $this->belongsToMany(Invitation::class, 'tagmaps')
            ->withTimestamps();
    }
}
