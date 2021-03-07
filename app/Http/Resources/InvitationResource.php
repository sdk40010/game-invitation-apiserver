<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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

        if ($resource['participants_count']) {
            $resource['canParticipateIn'] = // 募集に参加可能かどうか
                $resource['capacity'] > $resource['participants_count'] ||
                Carbon::parse($resource['start_time']) > Carbon::now();
        }

        return $resource;
    }
}
