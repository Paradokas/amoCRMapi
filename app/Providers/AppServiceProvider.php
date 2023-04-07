<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Ufee\Amo\Oauthapi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('AmoCRM', function () {
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
