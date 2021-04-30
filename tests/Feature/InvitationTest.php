<?php

namespace Tests\Feature;

use Tests\InvitationTestCase;

use App\Models\Invitation;
use App\Models\User;

use App\Http\Middleware\ConvertResponseFieldsToCamelCase as CamelCaseConverter;
use Illuminate\Support\Carbon;

class InvitationTest extends InvitationTestCase
{
    /**
     * 募集取得機能をテストする
     */
    public function testGet()
    {
        $invitation = $this->getRandomInvitation();
        $response = $this->getJson(static::$urlPrefix.'invitations/'.$invitation->id);

        $response
            ->assertOk()
            ->assertJson(CamelCaseConverter::toCamelCase($invitation->toArray()));
    }

    /**
     * 募集一覧取得機能をテストする
     */
    public function testGetMany()
    {
        $response = $this->getJson(static::$urlPrefix.'invitations');
        $invitations = $response->json('invitations');

        foreach ($invitations as $i => $invitation) {
            $startTime = Carbon::parse($invitation['startTime']);

            // 開始時刻が現在時刻よりも後の時刻であることをテストする
            $this->assertTrue($startTime->greaterThanOrEqualTo(Carbon::now()));
            
            // 開始時刻の昇順で並んでいることをテストする
            if ($i < count($invitation) - 1) {
                $startTime->lessThanOrEqualTo(
                    Carbon::parse($invitations[$i + 1]['startTime'])
                );
            }

        }
    }

    /**
     * 募集投稿機能をテストする
     */
    public function testPost()
    {
        $invitation = $this->makeInvitation();

        $response = $this->postJson(static::$urlPrefix.'invitations', $invitation);

        $invitation['id'] = preg_split('/\/invitations\//' ,$response->json('redirectTo'))[1];
        unset($invitation['tags']);

        $response->assertOk();
        $this->assertDatabaseHas('invitations', $invitation);
    }

    /**
     * 募集更新機能をテストする
     */
    public function testUpdate()
    {
        [$invitation, $updated] = $this->prepareForTestUpdate();

        $this->actingAs(User::find($invitation->user_id)); // 募集の投稿者を認証済みユーザーとして指定する

        $response = $this->putJson(static::$urlPrefix.'invitations/'.$invitation->id, $updated);

        $updated['id'] = $invitation->id;
        unset($updated['tags']);

        $response->assertOk();
        $this->assertDatabaseHas('invitations', $updated);
    }

    /**
     * 投稿者以外は募集を更新できないことをテストする
     */
    public function testOthersCantUpdate()
    {
        [$invitation, $updated] = $this->prepareForTestUpdate();

        $this->actingAs(User::where('id', '!=', $invitation->user_id)->first());

        $response = $this->putJson(static::$urlPrefix.'invitations/'.$invitation->id, $updated);

        $response->assertStatus(403);
    }

    /**
     * 募集削除機能をテストする
     */
    public function testDelete()
    {
        $invitation = $this->getRandomInvitation();

        $this->actingAs(User::find($invitation->user_id));

        $response = $this->deleteJson(static::$urlPrefix.'invitations/'.$invitation->id);

        $response->assertOk();
        $this->assertDatabaseMissing('invitations', $invitation->toArray());
    }

    /**
     * 投稿者以外は募集を削除できないことをテストする
     */
    public function testOthersCantDelete()
    {
        $invitation = $this->getRandomInvitation();

        $this->actingAs(User::where('id', '!=', $invitation->user_id)->first());

        $response = $this->deleteJson(static::$urlPrefix.'invitations/'.$invitation->id);

        $response->assertStatus(403);
    }
}
