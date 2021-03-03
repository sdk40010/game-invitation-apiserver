<?php

namespace App\Http\Requests\Reply;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

use App\Models\User;

use Illuminate\Support\Facades\Log;

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
        $rules = ['reply' => 'required|min:1'];

        // 返信先のユーザーのIDが存在するユーザーのものであるか確認する
        if (array_key_exists('reply_to', $this->all())) {
            $ids = User::all()
                ->map(function ($user) { return $user->id; })
                ->toArray();
            
            $rules['reply_to'] = Rule::in([...$ids]);
        }

        return $rules;
    }

    /**
     * バリデーション済みのコメントデータを取得する
     */
    public function getReplyData()
    {
        $validated = $this->validated();
        $data = [];

        if (array_key_exists('reply_to', $validated)) {
            $data['to'] = intval($validated['reply_to']);
        }

        $data['content'] = $validated['reply'];

        return $data;
    }
}
