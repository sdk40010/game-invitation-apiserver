<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\ServiceAccount;
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
        $this->app->singleton(\Kreait\Firebase::class, function () {
            return (new Factory())->withServiceAccount('firebase-adminsdk');
        });
    }

    /**
     * Bootstrap services.
     *
     * @return array
     */
    public function provides()
    {
        return [\Kreait\Firebase::class];
    }
}
