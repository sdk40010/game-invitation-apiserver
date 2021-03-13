<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Kreait\Firebase\Auth::class, function () {
            return (new Factory())->withServiceAccount(base_path('storage/firebase-adminsdk.json'))->createAuth();
        });
    }

    /**
     * 
     *
     * @return array
     */
    public function provides()
    {
        return [\Kreait\Firebase\Auth::class];
    }
}
