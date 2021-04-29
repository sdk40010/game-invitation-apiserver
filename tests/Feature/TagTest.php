<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\InvitationTestCase;

use App\Models\User;
use App\Models\Invitation;
use App\Models\Tag;

class TagTest extends InvitationTestCase
{
    /**
     * タグ作成機能をテストする
     */
    public function testCreateTags()
    {
        $tags = [['name' => 'new tag']];
        $invitation = $this->makeInvitation($tags);

        $response = $this->postJson(static::$urlPrefix.'invitations', $invitation);

        $response->assertOk();
        foreach ($tags as $tag) {
            $this->assertDatabaseHas('tags', $tag);
        }
    }

    /**
     * タグ一覧取得機能をテストする
     */
    public function testGetAll()
    {
        $response = $this->getJson(static::$urlPrefix.'tags');

        $this->assertCount(Tag::count(), $response->json());
    }

    /**
     * 募集更新時のタグ追加機能をテストする
     */
    public function testAddTags()
    {
        $TAG_MAX = 10;

        [$invitation, $updated] = $this->prepareForTestUpdate();
        while ($invitation->tags->count() === $TAG_MAX) { // 最大個数未満のタグが付いた募集を取得する
            [$invitation, $updated] = $this->prepareForTestUpdate();
        }

        // 募集にタグを最大個数までつける
        $newTags = Tag::whereNotIn('id', 
            $invitation->tags->map(function ($tag) { return $tag->id; })
        )->take($TAG_MAX - $invitation->tags->count())->get();
        $updated['tags'] = [...$updated['tags'], ...$newTags];

        $this->actingAs(User::find($invitation->user_id));

        $response = $this->putJson(static::$urlPrefix.'invitations/'.$invitation->id, $updated);

        $response->assertOk();
        $this->assertCount($TAG_MAX, $invitation->tags()->get());
    }

    /**
     * 募集更新時のタグ取り外し機能をテストする
     */
    public function testDetachTags()
    {
        [$invitation, $updated] = $this->prepareForTestUpdate();
        while ($invitation->tags->count() === 0) { // タグが1個以上ついた募集を取得する
            [$invitation, $updated] = $this->prepareForTestUpdate();
        }

        $updated['tags'] = []; // タグをすべて外す

        $this->actingAs(User::find($invitation->user_id));

        $response = $this->deleteJson(static::$urlPrefix.'invitations/'.$invitation->id, $updated);

        $response->assertOk();
        $this->assertCount(0, $invitation->tags()->get());
    }
}
