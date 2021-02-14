<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class UUIDModel extends Model
{
    protected $primaryKey ='id';

    protected $keyType = 'uuid';

    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // インスタンス生成時に自動でuuidを設定する
        $this->attributes['id'] = Uuid::uuid4()->toString();
    }
}
