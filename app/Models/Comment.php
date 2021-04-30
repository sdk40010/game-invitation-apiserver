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
     * 常にロードするリレーション
     */
    protected $with = ['user'];

    /**
     * 常にロードするリレーションの件数
     */
    protected $withCount = ['replies'];

    /**
     * キャストする属性
     */
    protected $casts = [
        'user_id' => 'int',
    ];

    /**
     * コメント先の募集
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
     * コメントの返信一覧
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
