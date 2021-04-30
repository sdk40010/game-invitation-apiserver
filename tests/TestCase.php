<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Invitation;

use Illuminate\Support\Facades\Log;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected static $urlPrefix = '/api/v1/';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        
        // 認証済みのユーザーを指定する
        $this->actingAs(User::first());

        // ajax通信の設定
        $this->withHeaders([
            "Content-Type" => "application/json",
            "X-Requested-With" => "XMLHttpRequest",
        ]);
    }

    /**
     * 無作為に募集を１件取得する
     * 
     * @return Eloquent
     * 
     */
    protected function getRandomInvitation() {
        return Invitation::all()->random();
    }

    protected function debug($message)
    {
        Log::channel('stderr')->debug($message);
    }
}
