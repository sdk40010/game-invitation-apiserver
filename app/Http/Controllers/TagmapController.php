<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Http\Resources\Tag as TagResource;

class TagmapController extends Controller
{
    /**
     * 募集に付けられたタグ一覧を取得する
     */
    public function showTags(Invitation $invitation)
    {
        return TagResource::collection($invitation->tags);
    }
}
