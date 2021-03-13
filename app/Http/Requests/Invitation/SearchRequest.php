<?php

namespace App\Http\Requests\Invitation;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * クエリパラメータの変数名一覧（省略形 -> 元の形）
     */
    private static $originalNames = [
        'tags' => 'tags',
        'title' => 'title',
        'minst' => 'minStartTime',
        'maxst' => 'maxStartTime',
        'minc' => 'minCapacity',
        'maxc' => 'maxCapacity',
        'page' => 'page',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * 検索パラメータを取得する
     */
    public function searchParams() {
        $array = [];
        foreach (static::$originalNames as $key => $name) {
            $default = $key === 'page' ? 1 : null;
            $array[$name] = $this->query($key, $default);
        }
        return $array;
    }
}
