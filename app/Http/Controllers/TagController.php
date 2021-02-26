<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * タグ一覧を取得する
     */
    public function index(Request $request)
    {
        return response()->json(Tag::orderBy('count', 'desc')->get());
    }
}
