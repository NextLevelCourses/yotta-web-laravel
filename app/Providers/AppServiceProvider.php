<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Google\Cloud\Firestore\FirestoreClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton('firebase.firestore.wheater_station', function () {
            return new FirestoreClient([
                'keyFilePath' => config('firebase.credentials.wheater_station'),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') == "production" || config('app.env') == "staging") {
            URL::forceScheme('https');
        }
    }
}
