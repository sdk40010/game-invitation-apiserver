<?php

namespace App\Providers;

use App\Repositories\InvitationRepository;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // データラップを行わないようにするための設定
        Resource::withoutWrapping();
    }

    /**
     * 登録する必要のある全コンテナシングルトン
     *
     * @var array
     */
    public $singletons = [
        InvitationRepository::class => InvitationRepository::class
    ];
}
