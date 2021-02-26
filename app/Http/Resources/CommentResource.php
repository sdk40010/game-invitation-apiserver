<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $createdAt = $this->created_at->equalTo($this->updated_at)
            ? $this->created_at->diffForHumans()
            : $this->created_at->diffForHumans().' (編集済み)';

        return [
            'id' => $this->id,
            'content' => $this->content,
            'invitation_id' => $this->invitation_id,
            'user' => $this->whenLoaded('user'),
            'created_at' => $createdAt,
        ];
    }
}
