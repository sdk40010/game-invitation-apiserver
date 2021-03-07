<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Resources\InvitationCollection;

use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    /**
     * ユーザーが投稿した募集一覧を取得する
     */
    public function showInvitations(Request $request, User $user)
    {
        $array = $user->toArray();

        $invitations = $user->invitationsPosted()
            ->orderBy('created_at', 'desc') // 投稿日時の新しい順
            ->paginate(30);

        // ページネーション用のメタ情報がついた募集一覧
        $array['invitations_posted']
            = (new InvitationCollection($invitations))
                ->toResponse($request)
                ->getData(true);

        return response()->json($array);
    }

    /**
     * ユーザーが参加した募集一覧を取得する
     */
    public function showPrticipations(Request $request, User $user)
    {
        $user->load(['invitationsParticipatedIn' => function ($query) {
            // 参加日時の新しい順
            $query->orderBy('participations.created_at', 'desc')
                ->paginate(30);
        }]);

        return response()->json($user);
    }
}
