<?php

namespace Statikbe\CookieConsent;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;

class CookieConsentServiceProvider extends ServiceProvider
{
    public const string COOKIE_CONSENT_MODAL_ID = 'cookie-consent-modal';
    public const string COOKIE_CONSENT_SETTINGS_MODAL_ID = 'cookie-consent-settings-modal';

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'cookie-consent');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cookie-consent');

        $this->app['view']->composer('cookie-consent::index', function (View $view) {});

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
        
        if (
            config('cookie-consent.theme') === 'filament'
            && ! config('cookie-consent.disable_filament_nav_hook', false)
            && class_exists(FilamentView::class)
            && class_exists(PanelsRenderHook::class)
        ) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::SIDEBAR_NAV_END,
                fn () => view('cookie-consent::filament-nav-item'),
            );
        }
    }

    /**
     * Console-specific booting.
     */
    protected function bootForConsole(): void
    {
        $this->publishes([
            __DIR__.'/../config/cookie-consent.php' => config_path('cookie-consent.php'),
        ], 'cookie-config');

        $this->publishes([
            __DIR__.'/../public/' => public_path('vendor/cookie-consent'),
        ], 'cookie-public');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/cookie-consent'),
        ], 'cookie-views');

        $langPath = 'vendor/cookie-consent';
        $langPath = (function_exists('lang_path'))
            ? lang_path($langPath)
            : resource_path('lang/'.$langPath);
        $this->publishes([
            __DIR__.'/../resources/lang' => $langPath,
        ], 'cookie-lang');
    }
}
