<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Invitation\IndexRequest;
use App\Http\Requests\Invitation\UpsertRequest;

use App\Models\Invitation;
use App\Http\Resources\InvitationCollection;
use App\Models\Tag;

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
    public function store(UpsertRequest $request)
    {
        $invitationData = $request->getInvitationData();

        // 募集の保存
        $invitation = new Invitation($invitationData);
        $invitation->user()->associate(Auth::user())->save();

        // タグ、タグマップへの保存
        $tagsData = $request->getTagsData();
        foreach ($tagsData['existing'] as $tagData) {
            $tag = Tag::find($tagData['id']);
            $tag->fill($tagData)->save();
            $invitation->tags()->attach($tag->id);
        }
        foreach ($tagsData['new'] as $tagData) {
            $tag = Tag::create($tagData);
            $invitation->tags()->attach($tag->id);
        }

        return response()->json(['redirectTo' => '/invitations'.'/'.$invitation->id]);
    }

    /**
     * 募集を更新する
     */
    public function update(UpsertRequest $request, Invitation $invitation)
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
