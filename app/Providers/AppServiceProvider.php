<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Ufee\Amo\Base\Storage\Oauth\FileStorage;
use Ufee\Amo\Oauthapi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Oauthapi::class, function () {
            return Oauthapi::setInstance([
                'domain' => config('amocrm.domain'),
                'client_id' => config('amocrm.client_id'),
                'client_secret' => config('amocrm.client_secret'),
                'redirect_uri' => config('amocrm.redirect_uri'),
                'zone' => 'ru',
                'timezone' => 'Europe/Moscow',
                'lang' => 'ru'
            ]);
        });

        $this->app->singleton(FileStorage::class, function ($app) {
            return new FileStorage(['path' => storage_path(config('amocrm.path'))]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
