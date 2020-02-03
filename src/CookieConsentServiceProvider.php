<?php

namespace Statikbe\CookieConsent;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;

class CookieConsentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/cookie-consent.php' => config_path('cookie-consent.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/cookieConsent'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../resources/lang' => base_path('resources/lang/vendor/cookieConsent'),
        ], 'lang');

        $this->publishes([
            __DIR__.'/../resources/public/' => public_path('/'),
        ], 'cookieConsent');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'cookieConsent');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cookieConsent');

        $this->app['view']->composer('cookieConsent::index', function (View $view) {
        });
    }
}
