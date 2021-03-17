<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Invitation\IndexRequest;
use App\Http\Requests\Invitation\StoreRequest;
use App\Http\Requests\Invitation\UpdateRequest;
use App\Http\Requests\Invitation\DeleteRequest;
use App\Http\Requests\Invitation\SearchRequest;

use App\Models\Invitation;
use App\Http\Resources\InvitationCollection;
use App\Http\Resources\InvitationResource;
use App\Models\Tag;

use App\Repositories\InvitationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class InvitationController extends Controller
{
    /**
     * 募集一覧を取得する
     */
    public function index(IndexRequest $request)
    {
        $invitations = Invitation::where($request->getWhereClause())
            ->orderBy(...$request->getOrderByClause())
            ->paginate(20);
        
        return new InvitationCollection($invitations);
    }

    /**
     * 募集を取得する
     */
    public function show(Invitation $invitation)
    {
        $invitation
            ->load(['participants' => function ($query) { // 募集の参加者
                // 参加日時の古い順
                $query->orderBy('participations.created_at', 'asc');
            }])
            ->load(['user' => $invitation->userWithProfile()]);

        return new InvitationResource($invitation);
    }

    /**
     * 募集を保存する
     */
    public function store(StoreRequest $request)
    {
        // 募集を保存する
        $invitation = new Invitation($request->validated());
        $invitation->user()->associate(Auth::user())->save();

        // タグとタグマップを保存する
        $this->upsertTags($request->getTagsData(), $invitation);

        // 募集の投稿者を最初の参加者として保存する
        $invitation->participants()->attach(Auth::user());

        return response()->json(['redirectTo' => '/invitations'.'/'.$invitation->id]);
    }

    /**
     * 募集を更新する
     */
    public function update(UpdateRequest $request, Invitation $invitation)
    {
        // 募集を更新する
        $invitation->fill($request->validated());
        $invitation->save();

        //タグとタグマップを更新する
        $this->upsertTags($request->getTagsData(), $invitation);
        foreach ($request->getTagsDataShouldDetached() as $tag) {
            $invitation->tags()->detach($tag['id']);
        }

        return response()->json(['message' => '募集を更新しました']);
    }

    /**
     * 募集を削除する
     */
    public function delete(DeleteRequest $request, Invitation $invitation)
    {
        $invitation->delete();
        return response()->json(['message' => '募集を削除しました']);
    }

    /**
     * 募集を検索する
     */
    public function search(SearchRequest $request, InvitationRepository $invitationRepository)
    {
        return $invitationRepository->search($request->searchParams());
    }

    /**
     * タグとタグマップへの追加・更新を行う
     * 
     * @param $tagsData - 既存のタグと新しいタグで分類されたタグデータ
     * @param $invitation - タグ付け対象の募集
     */
    private function upsertTags($tagsData, $invitation) {
        foreach ($tagsData['existing'] as $tagData) {
            $tag = Tag::find($tagData['id']);
            $tag->fill($tagData)->save();
            $invitation->tags()->attach($tag->id);
        }
        
        foreach ($tagsData['new'] as $tagData) {
            $tag = Tag::create($tagData);
            $invitation->tags()->attach($tag->id);
        }
    }
}
