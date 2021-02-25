<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Comment\StoreRequest;

use App\Models\Comment;
use App\Models\Invitation;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * 募集のコメント一覧の取得
     */
    public function index(Request $request, Invitation $invitation)
    {
        $comments = $invitation->comments()->with('user')->latest()->get();
        return response()->json($comments);
    }

    /**
     * コメントの保存
     */
    public function store(StoreRequest $request, Invitation $invitation)
    {
        $comment = $invitation->comments()->save(
            (new Comment($request->getCommentData()))
                ->user()
                ->associate(Auth::user())
        );

        return response()->json($comment);
    }
}
