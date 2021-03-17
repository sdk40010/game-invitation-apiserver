<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'count'
    ];

    protected $hidden = ['pivot'];

    /**
     * タグが付いた募集一覧
     */
    public function invitations()
    {
        return $this->belongsToMany(Invitation::class, 'tagmaps')
            ->withTimestamps();
    }
}
