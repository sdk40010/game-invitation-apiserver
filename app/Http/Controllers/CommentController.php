<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Comment\StoreRequest;
use App\Http\Requests\Comment\UpdateRequest;
use App\Http\Requests\Comment\DeleteRequest;

use App\Models\Comment;
use App\Models\Invitation;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * 募集のコメント一覧の取得
     */
    public function index(Request $request, Invitation $invitation)
    {
        $comments = $invitation->comments()->latest()->get();
        return response()->json($comments);
    }

    /**
     * コメントの取得
     */
    public function show(Request $request, Invitation $invitation, Comment $comment)
    {
        return response()->json($comment);
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

    /**
     * コメントの更新
     */
    public function update(UpdateRequest $request, Invitation $invitation, Comment $comment)
    {
        $comment->fill($request->getCommentData())->save();
        return response()->json($comment->load('user'));
    }

    /**
     * コメントの削除
     */
    public function delete(DeleteRequest $request, Invitation $invitation, Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'コメントを削除しました。']);
    }
}
