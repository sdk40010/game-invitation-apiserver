<?php

namespace App\Http\Requests\Invitation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Log;

class IndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * リクエストのクエリ文字列を元に作成したwhere句の条件を取得する
     */
    public function getWhereClause()
    {
        $whereClause = [];
        $whereClause[] = ['start_time', '>', Carbon::now('Asia/Tokyo')];

        return $whereClause;
    }

    /**
     * リクエストのクエリ文字列を元に作成したorderBy句の条件を取得する
     */
    public function getOrderByClause()
    {
        $orderByClause = [];

        if (empty($this->query('sort')) || $this->query('sort') === 'near') {
            $orderByClause = ['start_time', 'asc'];
        }

        return $orderByClause;
    }
}
