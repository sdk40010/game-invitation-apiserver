<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Models\Comment;

class CommentTest extends TestCase
{
    /**
     * コメント一覧取得機能をテストする
     * 
     * @return void
     */
    public function testGetAll()
    {
        $invitation = $this->getRandomInvitation();

        $response = $this->getJson($this->getURLPrefix($invitation->id));

        $response->assertOk();
        $this->assertCount($invitation->comments()->count(), $response->json());
    }

    /**
     * コメント投稿機能をテストする
     *
     * @return void
     */
    public function testPost()
    {
        $invitation = $this->getRandomInvitation();
        [$commentForDB, $commentForReq] = $this->makeComment();

        $response = $this->postJson($this->getURLPrefix($invitation->id), $commentForReq);

        $commentForDB['id'] = $response->json('id');

        $response->assertOk();
        $this->assertDatabaseHas('comments', $commentForDB);
    }

    /**
     * コメント更新機能をテストする
     * 
     * @return void
     */
    public function testUpdate()
    {
        $invitation = $this->getRandomInvitation();
        $comment = $invitation->comments->random();

        [$commentForDB, $commentForReq] = $this->makeComment();

        $this->actingAs(User::find($comment->user_id));

        $response = $this->putJson(
            $this->getURLPrefix($invitation->id).$comment->id,
            $commentForReq
        );

        $commentForDB['id'] = $response->json('id');

        $response->assertOk();
        $this->assertDatabaseHas('comments', $commentForDB);
    }

    /**
     * 投稿者以外はコメントを更新できないことをテストする
     */
    public function theOthersCantUpdate()
    {
        $invitation = $this->getRandomInvitation();
        $comment = $invitation->comments->random();

        [$commentForDB, $commentForReq] = $this->makeComment();

        $this->actingAs(User::where('id', '!=', $comment->user_id)->first());

        $response = $this->putJson(
            $this->getURLPrefix($invitation->id).$comment->id,
            $commentForReq
        );

        $response->assertStatus(403);
    }

    /**
     * コメント削除機能をテストする
     */
    public function testDelete()
    {
        $invitation = $this->getRandomInvitation();
        $comment = $invitation->comments->random();

        $this->actingAs(User::find($comment->user_id));

        $response = $this->deleteJson($this->getURLPrefix($invitation->id).$comment->id);

        $response->assertOk();
        $this->assertDatabaseMissing('comments', $comment->toArray());
    }

    /**
     * 投稿者以外はコメントを削除できないことをテストする
     */
    function testOthersCantDelete()
    {
        $invitation = $this->getRandomInvitation();
        $comment = $invitation->comments->random();

        $this->actingAs(User::where('id', '!=', $comment->user_id)->first());

        $response = $this->deleteJson($this->getURLPrefix($invitation->id).$comment->id);

        $response->assertStatus(403);
    }

    /**
     * コメント機能テスト用のURLプレフィックスを取得する
     * 
     * @return string
     */
    private function getURLPrefix($invitationId)
    {
        return static::$urlPrefix.'invitations/'.$invitationId.'/comments'.'/';
    }

    /**
     * テスト用のコメントを作成する
     * 
     * @return array
     */
    private function makeComment()
    {
        $commentForDB = factory(Comment::class)->make()->toArray();
        $commentForReq = ['comment' => $commentForDB['content']];

        return [$commentForDB, $commentForReq];
    }
}
