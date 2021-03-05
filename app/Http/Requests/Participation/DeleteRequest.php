<?php

namespace App\Http\Requests\Participation;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invitation = $this->route('invitation');
        return $invitation &&
            $invitation->participants->contains(function ($participant) {
                // ユーザーが募集に参加済みであることを判定する
                return $this->user()->id === $participant->id;
            });

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
