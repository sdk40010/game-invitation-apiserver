<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * ユーザーがコメントを更新・削除可能かどうか判定する
     */
    public function updateOrDelete(User $user, Comment $comment)
    {
        return $user->id === $comment->user_id;
    }
}
