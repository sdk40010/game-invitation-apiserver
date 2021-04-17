<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $urlPrefix = '/api/v1/';

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
}
