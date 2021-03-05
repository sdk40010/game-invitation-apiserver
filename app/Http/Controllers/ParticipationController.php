<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Participation\StoreRequest;
use App\Http\Requests\Participation\DeleteRequest;

use App\Models\Invitation;

use Illuminate\Support\Facades\Auth;

class ParticipationController extends Controller
{
    /**
     * 募集への参加を保存する
     */
    public function store(StoreRequest $request, Invitation $invitation)
    {
        $invitation->participants()->attach(Auth::user());
        return response()->json(["message" => "募集に参加しました。"]);
    }

    /**
     * 募集への参加を削除する
     */
    public function delete(DeleteRequest $request, Invitation $invitation)
    {
        $invitation->participants()->detach(Auth::user());
        return response()->json(["message" => "募集への参加を取り消しました。"]);
    }
}
