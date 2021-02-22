<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Http\Resources\Tag as TagResource;

class TagController extends Controller
{
    /**
     * タグ一覧を取得する
     */
    public function index(Request $request)
    {
        return TagResource::collection(
            Tag::orderBy('count', 'desc')->get()
        );
    }
}
