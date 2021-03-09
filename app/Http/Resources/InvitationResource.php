<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Log;

class InvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->resource->toArray();

        if ($request->user()) {
            // ユーザーが投稿者自身かどうか
            $resource['isPoster'] = $request->user()->id === $resource['user_id'];
        }

        if ($resource['participants_count']) {
            $resource['canParticipateIn'] = // 募集に参加可能かどうか
                $resource['capacity'] > $resource['participants_count'] &&
                Carbon::parse($resource['start_time']) > Carbon::now();
        }

        return $resource;
    }
}
