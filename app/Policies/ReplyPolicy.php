<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
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
     * ユーザーが返信を更新・削除可能かどうか判定する
     */
    public function updateOrDelete(User $user, Reply $reply)
    {
        return $user->id === $reply->user_id;
    }
}
