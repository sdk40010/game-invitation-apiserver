<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class InvitationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * データラッパーのキー名
     */
    public static $wrap = 'invitations';

    /**
     * 収集するリソースクラス
     */
    public $collects = "App\Http\Resources\InvitationResource";
}
