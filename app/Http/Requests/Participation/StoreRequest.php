<?php

namespace App\Http\Requests\Participation;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Carbon;

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
        $notParticipatedIn = false;
        $canParticipateIn = false;

        if ($invitation) {
            // ユーザーが募集に参加済みでないかどうか
            $notParticipatedIn = $invitation->participants->contains(function ($participant) {
                return $this->user()->id !== $participant->id;
            });

            // 募集に参加可能かどうか
            $canParticipateIn =
                $invitation->capacity > $invitation->participants_count ||
                Carbon::parse($invitation->start_time) > Carbon::now();
        }

        return $invitation && $notParticipatedIn && $canParticipateIn; 
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
