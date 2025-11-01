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
        // firestore
        $this->app->singleton('firebase.firestore.wheater_station', function () {
            return new FirestoreClient([
                'keyFilePath' => config('firebase.credentials.wheater_station'),
            ]);
        });

        //realtime database
        $this->app->singleton('firebase.database.solar_dome', function () {
            return (new \Kreait\Firebase\Factory)
                ->withServiceAccount(config('firebase.credentials.solar_dome'))
                ->withDatabaseUri(config('firebase.database.solar_dome.host'))
                ->createDatabase();
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
