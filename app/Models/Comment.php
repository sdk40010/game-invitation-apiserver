<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\TimeStampFormat;

class Comment extends Model
{
    use TimeStampFormat;

    protected $fillable = ['content'];

    /**
     * コメント対象の募集
     */
    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }

    /**
     * コメントの投稿者
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * コメントへの返信一覧
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
