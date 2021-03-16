<?php

namespace App\Http\Requests\Following;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\DB;

class DeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! $this->user()) {
            return false;
        }

        // ログインユーザーがユーザーをフォローしているか確かめる
        $following = DB::table('followings')
            ->where([
                ['user_id', $this->user()->id],
                ['following_id', $this->route('user')->id]
            ])
            ->first();

        return $following ? true : false;
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
}
