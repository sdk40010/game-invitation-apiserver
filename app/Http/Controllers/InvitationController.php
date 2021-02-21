<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Invitation\IndexRequest;
use App\Http\Requests\Invitation\StoreRequest;

use App\Models\Invitation;
use App\Http\Resources\InvitationCollection;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class InvitationController extends Controller
{
    /**
     * 募集一覧を取得する
     */
    public function index(IndexRequest $request)
    {
        $invitations = Invitation::with('user')
            ->where($request->getWhereClause())
            ->orderBy(...$request->getOrderByClause())
            ->paginate(30);
        
        return new InvitationCollection($invitations);
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
     * 募集を保存する
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        Log::debug($validated);
        $invitation = new Invitation($validated);
        $invitation->user()->associate(Auth::user())->save();
        // $invitation->save();
        return response()->json(['redirectTo' => '/invitations'.'/'.$invitation->id]);
    }

    /**
     * 募集を更新する
     */
    public function update(StoreRequest $request, Invitation $invitation)
    {
        if (Auth::user()->can('updateOrDelete', $invitation)) {
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
        if (Auth::user()->can('updateOrDelete', $invitation)) {
            $invitation->delete();
            return response()->json(['message' => '募集が削除されました。']);
        } else {
            abort(403);
        }
    }
}
