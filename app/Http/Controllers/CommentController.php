<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Comment\StoreRequest;
use App\Http\Requests\Comment\UpdateRequest;

use App\Models\Comment;
use App\Models\Invitation;

use App\Http\Resources\CommentResource;

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
        return CommentResource::collection($comments);
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

        return new CommentResource($comment);
    }

    /**
     * コメントの更新
     */
    public function update(UpdateRequest $request, Invitation $invitation, Comment $comment)
    {
        $comment->fill($request->getCommentData())->save();
        return new CommentResource($comment->load('user'));
    }
}
