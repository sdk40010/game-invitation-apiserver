<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Invitation\StoreRequest;
use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InvitationController extends Controller
{
    /**
     * 募集を保存する
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        Log::debug($validated);
        $invitation = new Invitation($validated);
        $invitation->user()->associate(Auth::user());
        $invitation->save();
        return response()->json(['redirectTo' => '/invitations'.'/'.$invitation->id]);
    }

    /**
     * 募集を取得する
     */
    public function show(Invitation $invitation)
    {
        $invitation->load('user');
        return response()->json($invitation);
    }

    /**
     * 募集を更新する
     */
    public function update(StoreRequest $request, Invitation $invitation)
    {
        if (Auth::user()->can('update', $invitation)) {
            $invitation->fill($request->all());
            $invitation->save();
            return response()->json(['message' => '募集が更新されました。']);
        } else {
            abort(403);
        }
    }

    /**
     * 募集を削除する
     */
    public function delete(Invitation $invitation)
    {
        if (Auth::user()->can('delete', $invitation)) {
            $invitation->delete();
            return response()->json(['message' => '募集が削除されました。']);
        } else {
            abort(403);
        }
    }
}
