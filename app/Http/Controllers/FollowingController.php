<?php

namespace App\Http\Controllers;

use App\Http\Requests\Following\StoreRequest;
use App\Http\Requests\Following\DeleteRequest;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class FollowingController extends Controller
{

    /**
     * ユーザーをフォローする
     */
    public function store(StoreRequest $request, User $user)
    {
        Auth::user()->followings()->attach($user);
        return response()->json(['message' => $user->name.'をフォローしました']);
    }

    /**
     * ユーザーのフォローを取り消す
     */
    public function delete(DeleteRequest $request, User $user)
    {
        Auth::user()->followings()->detach($user);
        return response()->json(['message' => $user->name.'のフォローを取り消しました']);
    }
}
