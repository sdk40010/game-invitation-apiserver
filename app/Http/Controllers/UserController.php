<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Resources\InvitationCollection;

use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // リレーション件数の取得用
    private static $count = ['invitationsPosted', 'invitationsParticipatedIn'];

    /**
     * ユーザーの投稿履歴を取得する
     */
    public function showInvitations(Request $request, User $user)
    {
        // 投稿履歴と参加履歴の件数
        $user->loadCount(static::$count);

        $array = $user->toArray();

        $invitations = $user->invitationsPosted()
            ->orderBy('created_at', 'desc') // 投稿日時の新しい順
            ->paginate(20);

        // ページネーション用のメタ情報がついた投稿履歴
        $array['posted']
            = (new InvitationCollection($invitations))
                ->toResponse($request)
                ->getData(true);

        return response()->json($array);
    }

    /**
     * ユーザーの参加履歴を取得する
     */
    public function showParticipations(Request $request, User $user)
    {
        $user->loadCount(static::$count);

        $array =$user->toArray();

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
