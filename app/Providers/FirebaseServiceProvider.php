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
            $serviceAccount = base64_decode(config('firebaseadminsdk.service_account'));
            return (new Factory())->withServiceAccount($serviceAccount)->createAuth();
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
