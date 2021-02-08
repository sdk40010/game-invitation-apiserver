<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Kreait\Firebase\Auth::class, function () {
            return (new Factory())->withServiceAccount(base_path('path/to/firebase-adminsdk.json'))->createAuth();
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
