<?php

namespace App\Http\Requests\Following;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreRequest extends FormRequest
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

        // ログインユーザーがユーザーをフォローしていないこと、自分自身をフォローしようとしていないを確かめる
        $following = DB::table('followings')
            ->where([
                ['user_id', $this->user()->id],
                ['following_id', $this->route('user')->id]
            ])
            ->first();

        $notFollowingSelf = $this->user()->id !== $this->route('user')->id;
        
        return ! $following && $notFollowingSelf;
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
