<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Reply\StoreRequest;

use App\Models\Comment;
use App\Models\Reply;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReplyController extends Controller
{
    /**
     * コメントの返信一覧の取得
     */
    public function index(Request $request, Comment $comment)
    {
        $replies = $comment->replies()->oldest()->get();
        return response()->json($replies);
    }

    /**
     * 返信の保存
     */
    public function store(StoreRequest $request, Comment $comment)
    {
        $comment->replies()->save(
            (new Reply($request->getReplyData()))
                ->user()
                ->associate(Auth::user())
        );

        $replies = $comment->replies()->oldest()->get();
        return response()->json($replies);
    }
}
