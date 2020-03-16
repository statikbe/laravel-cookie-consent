<?php

namespace Statikbe\CookieConsent;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;

class CookieConsentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'cookieConsent');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cookieConsent');

        $this->app['view']->composer('cookieConsent::index', function (View $view) {
        });

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        $this->publishes([
            __DIR__.'/../config/cookie-consent.php' => config_path('cookie-consent.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../public/' => public_path('vendor/cookieConsent'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/cookieConsent'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../resources/lang' => base_path('resources/lang/vendor/cookieConsent'),
        ], 'lang');
    }
}
