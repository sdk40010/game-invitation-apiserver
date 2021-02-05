<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    public function comment() {
        $this->belongsTo(Comment::class);
    }

    public function user() {
        $this->belongsTo(User::class);
    }
}
