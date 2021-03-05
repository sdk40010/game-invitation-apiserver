<?php

namespace App\Http\Requests\Participation;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Invitation;

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
        $invitation = $this->route('invitation');
        $notParticipated = false;
        $canParticipate = false;

        if ($invitation) {
            // ユーザーが募集に参加済みでない
            $notParticipated = $invitation->participants->contains(function ($participant) {
                return $this->user()->id !== $participant->id;
            });

            // 定員に空きがある
            $canParticipate = $invitation->capacity > $invitation->participants->count();
        }

        return $invitation && $notParticipated && $canParticipate; 
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
