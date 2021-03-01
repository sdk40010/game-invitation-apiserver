<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TimeStampFormat;

class Reply extends Model
{
    use TimeStampFormat;

    /**
     * 常にロードするリレーション
     */
    protected $with = ['user'];

    /**
     * 返信先のコメント
     */
    public function comment() {
        return $this->belongsTo(Comment::class);
    }

    /**
     * 返信の投稿者
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
