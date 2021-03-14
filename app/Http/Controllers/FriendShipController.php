<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendShipController extends Controller
{
    /**
     * フレンド関係を取得する
     */
    public function show(Request $request, User $user)
    {
        DB::table('friendships')
            ->where(function ($query) use ($user) {
                $query->where([ // 自分->相手のフレンド関係の場合
                    ['user_id', Auth::user()->id],
                    ['friend_id', $user->id],
                ]);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where([ // 相手->自分のフレンド関係の場合
                    ['user_id', $user->id],
                    ['friend_id', Auth::user()->id],
                ]);
            });
    }

    /**
     * ユーザーにフレンド申請する
     */
    public function store(Request $request, User $user)
    {
        Auth::user()->friends()->attach($user);
        return response()->json(["message" => "フレンド申請しました。"]);
    }
}
