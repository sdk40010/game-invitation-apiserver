<?php

namespace App\Http\Requests\Reply;

use App\Http\Requests\Reply\StoreRequest;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $reply = $this->route('reply');
        return $reply && $this->user()->can('updateOrDelete', $reply);
    }

}
