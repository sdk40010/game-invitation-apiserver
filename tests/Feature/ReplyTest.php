<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\Comment;
use App\Models\Reply;
use App\Models\User;

class ReplyTest extends TestCase
{
    /**
     * 返信一覧取得機能をテストする
     * 
     * @return void
     */
    public function testGetAll()
    {
        $comment = $this->getRandomComment();

        $response = $this->getJson($this->getURLPrefix($comment->id));

        $response->assertOk();
        $this->assertCount($comment->replies->count(), $response->json());
    }

    /**
     * 返信投稿機能をテストする
     *
     * @return void
     */
    public function testPost()
    {
        $comment = $this->getRandomComment();
        [$replyForDB, $replyForReq] = $this->makeReply();

        $response = $this->postJson($this->getURLPrefix($comment->id), $replyForReq);

        $replyForDB['id'] = $response->json('id');

        $response->assertOk();
        $this->assertDatabaseHas('replies', $replyForDB);
    }

    /**
     * 返信更新機能をテストする
     * 
     * @return void
     */
    public function testUpdate()
    {
        $comment = $this->getRandomComment();
        $reply = $comment->replies->random();

        [$replyForDB, $replyForReq] = $this->makeReply();

        $this->actingAs(User::find($reply->user_id));

        $response = $this->putJson(
            $this->getURLPrefix($comment->id).$reply->id,
            $replyForReq
        );

        $replyForDB['id'] = $response->json('id');

        $response->assertOk();
        $this->assertDatabaseHas('replies', $replyForDB);
    }

    /**
     * 投稿者以外は返信を更新できないことをテストする
     * 
     * @return void
     */
    public function testOthersCantUpdate()
    {
        $comment = $this->getRandomComment();
        $reply = $comment->replies->random();

        [$replyForDB, $replyForReq] = $this->makeReply();

        $this->actingAs(User::where('id', '!=', $reply->user_id)->first());

        $response = $this->putJson(
            $this->getURLPrefix($comment->id).$reply->id,
            $replyForReq
        );

        $response->assertStatus(403);
    }

    /**
     * 無作為にコメントを1件取得する
     * 
     * @return Eloquent
     */
    private function getRandomComment()
    {
        return Comment::all()->random();
    }

    /**
     * テスト用の返信を作成する
     * 
     * @return array
     */
    private function makeReply()
    {
        $replyForDB = factory(Reply::class)->make()->toArray();
        $replyForReq = ['reply' => $replyForDB['content']];

        return [$replyForDB, $replyForReq];
    }

    /**
     * 返信機能テスト用のURLプレフィックスを取得する
     * 
     * @return string
     */
    private function getURLPrefix($commentId)
    {
        return static::$urlPrefix.'comments/'.$commentId.'/replies'.'/';
    }
}
