<?php

namespace App\Http\Requests\Reply;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reply' => 'required|min:1'
        ];
    }

    /**
     * コメントデータを取得する
     */
    public function getReplyData()
    {
        $reply = $this->input('reply');
        return [ 'content' => $reply ];
    }
}
