<?php

namespace Statikbe\CookieConsent;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;

class CookieConsentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'cookie-consent');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cookie-consent');

        $this->app['view']->composer('cookie-consent::index', function (View $view) {
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
    protected function bootForConsole(): void
    {
        $this->publishes([
            __DIR__ . '/../config/cookie-consent.php' => config_path('cookie-consent.php'),
        ], 'cookie-config');

        $this->publishes([
            __DIR__ . '/../public/' => public_path('vendor/cookie-consent'),
        ], 'cookie-public');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/cookie-consent'),
        ], 'cookie-views');

        $langPath = 'vendor/cookie-consent';
        $langPath = (function_exists('lang_path'))
            ? lang_path($langPath)
            : resource_path('lang/' . $langPath);
        $this->publishes([
            __DIR__ . '/../resources/lang' => $langPath,
        ], 'cookie-lang');
    }
}