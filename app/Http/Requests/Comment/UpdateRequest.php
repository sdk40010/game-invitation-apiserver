<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\Comment\StoreRequest;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $comment = $this->route('comment');
        return $comment && $this->user()->can('updateOrDelete', $comment);
    }
}
