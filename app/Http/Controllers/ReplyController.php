<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Reply\StoreRequest;
use App\Http\Requests\Reply\UpdateRequest;
use App\Http\Requests\Reply\DeleteRequest;

use App\Models\Comment;
use App\Models\Reply;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReplyController extends Controller
{
    /**
     * コメントの返信一覧を取得する
     */
    public function index(Request $request, Comment $comment)
    {
        $replies = $comment->replies()->oldest()->get();
        return response()->json($replies);
    }

    /**
     * 返信を保存する
     */
    public function store(StoreRequest $request, Comment $comment)
    {
        $reply = $comment->replies()->save(
            (new Reply($request->getReplyData()))
                ->user()
                ->associate(Auth::user())
        );

        $replies = $comment->replies()->oldest()->get();
        return response()->json($replies);
    }

    /**
     * 返信を更新する
     */
    public function update(UpdateRequest $request, Comment $comment, Reply $reply)
    {
        $reply->fill($request->getReplyData())->save();
        return response()->json($reply);
    }

    /**
     * 返信を削除する
     */
    public function delete(DeleteRequest $request, Comment $comment, Reply $reply)
    {
        $reply->delete();
        return response()->json(['message' => '返信を削除しました']);
    }
}
