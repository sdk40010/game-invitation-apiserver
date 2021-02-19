<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
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
     * ユーザーが指定された募集を更新可能かどうか判定する
     */
    public function update(User $user, Invitation $invitation)
    {
        return $user->id === $invitation->user_id;
    }

    /**
     * ユーザーが指定された募集を削除可能かどうか判定する
     */
    public function delete(User $user, Invitation $invitation)
    {
        return $user->id === $invitation->user_id;
    }
}
