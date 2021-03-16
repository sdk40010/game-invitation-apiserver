<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Resources\InvitationCollection;

use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * ユーザーの投稿履歴を取得する
     */
    public function showInvitations(Request $request, $id)
    {
        $user = (new User)->withProfile($id)->find($id);

        $invitations = $user->invitationsPosted()
            ->orderBy('created_at', 'desc') // 投稿日時の新しい順
            ->paginate(20);

        $array = $user->toArray();
        $array['posted'] // ページネーション用のメタ情報がついた投稿履歴
            = (new InvitationCollection($invitations))
                ->toResponse($request)
                ->getData(true);

        return response()->json($array);
    }

    /**
     * ユーザーの参加履歴を取得する
     */
    public function showParticipations(Request $request, $id)
    {
        // $user->loadCount(static::$count);
        $user = (new User)->withProfile($id)->find($id);

        $array = $user->toArray();

        $invitations = $user->invitationsParticipatedIn()
            ->orderBy('participations.created_at', 'desc') // 参加日時の新しい順
            ->paginate(20);

        // ページネーション用のメタ情報がついた参加履歴
        $array['participatedIn']
            = (new InvitationCollection($invitations))
                ->toResponse($request)
                ->getData(true);

        return response()->json($array);
    }
}
