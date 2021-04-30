<?php

namespace Tests;

use App\Models\Invitation;
use Illuminate\Support\Carbon;

abstract class InvitationTestCase extends TestCase
{
    /**
     * テスト用の募集を作成する
     * 
     * @param array $tags 
     * @return array
     */
    protected function makeInvitation($tags = [])
    {
        $invitation = factory(Invitation::class)->make()->toArray();

        foreach(['id', 'user_id', 'start_in', 'interval'] as $key) {
            unset($invitation[$key]);
        }
        $invitation['start_time'] = Carbon::parse($invitation['start_time'])->format('Y-m-d H:i:s');
        $invitation['end_time'] = Carbon::parse($invitation['end_time'])->format('Y-m-d H:i:s');
        $invitation['tags'] = $tags;

        return $invitation;
    }

    /**
     * @return array
     */
    protected function prepareForTestUpdate()
    {
        $invitation = $this->getRandomInvitation();
        $updated = $this->makeInvitation($invitation->tags->toArray());
        $updated['capacity'] = $invitation->capacity; // 参加者の人数を下回るとエラーになるので定員はそのままにしておく

        return [$invitation, $updated];
    }
}
