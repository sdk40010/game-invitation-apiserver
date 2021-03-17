<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Resources\InvitationCollection;
use App\Http\Resources\UserResource;

use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * ユーザーを取得する
     */
    public function show(Request $request, $id) {
        $user = (new User)->withProfile()->find($id);
        return response()->json($user);
    }

    /**
     * ユーザーの投稿履歴を取得する
     */
    public function showInvitations(Request $request, $id)
    {
        $invitations = User::find($id)->invitationsPosted()
            ->orderBy('created_at', 'desc') // 投稿日時の新しい順
            ->paginate(20);

        return new InvitationCollection($invitations);
    }

    /**
     * ユーザーの参加履歴を取得する
     */
    public function showParticipations(Request $request, $id)
    {
        $invitations = User::find($id)->invitationsParticipatedIn()
            ->orderBy('participations.created_at', 'desc') // 参加日時の新しい順
            ->paginate(20);

        return new InvitationCollection($invitations);
    }

    /**
     * ユーザーのフォロー一覧を取得する
     */
    public function showFollowings(Request $request, $id)
    {
        $followingsRelation = User::find($id)->followings();

        $followings = $followingsRelation->getRelated()
            ->withProfile($followingsRelation->getQuery())
            ->orderBy('followings.created_at', 'desc') // フォロー日時の新しい順
            ->paginate(20);

        // デバック
        // $array = UserResource::collection($followings)->toResponse($request)->getData(true);
        // foreach ($array['data'] as $user) {
        //     Log::debug($user['is_following']);
        //     Log::debug($user['is_follower']);
        // }

        UserResource::wrap('followings');
        return UserResource::collection($followings);
    }

    /**
     * ユーザーのフォロワー一覧を取得する
     */
    public function showFollowers(Request $request, $id)
    {
        $followersRelation = User::find($id)->followers();

        $followers = $followersRelation->getRelated()
            ->withProfile($followersRelation->getQuery())
            ->orderBy('followings.created_at', 'desc') // フォローされた日時の新しい順
            ->paginate(20);

            UserResource::wrap('followers');
            return UserResource::collection($followers);
    }
}
