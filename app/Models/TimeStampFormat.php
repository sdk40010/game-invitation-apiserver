<?php

namespace App\Models;

use Illuminate\Support\Carbon;

trait TimeStampFormat
{
    /**
     * フォーマット済みの作成時刻を取得
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        $createdAt = Carbon::parse($value);
        $updatedAt = Carbon::parse($this->attributes['updated_at']);

        return $createdAt->equalTo($updatedAt)
            ? $createdAt->diffForHumans()
            : $createdAt->diffForHumans().' (編集済み)';
    }

}
