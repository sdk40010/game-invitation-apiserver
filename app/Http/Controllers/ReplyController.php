<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Comment;

use Illuminate\Support\Facades\Log;

class ReplyController extends Controller
{
    /**
     * コメントの返信一覧を取得
     */
    public function index(Request $request, Comment $comment)
    {
        Log::debug($comment->id);
        $replies = $comment->replies()->oldest()->get();
        return response()->json($replies);
    }
}
