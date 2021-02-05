<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function invitations()
    {
        return $this->belongsToMany(Invitation::class, 'tagmaps');
    }
}
