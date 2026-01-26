<?php

use Filament\View\PanelsRenderHook;

return [
    /**
     * Theme to use for the cookie consent popup.
     * Available options: 'default', 'filament'.
     */
    'theme' => 'default',
    'filament-nav-item-render-hook' => PanelsRenderHook::USER_MENU_PROFILE_AFTER,
    'cookie_key' => '__cookie_consent',
    'cookie_value_analytics' => '2',
    'cookie_value_marketing' => '3',
    'cookie_value_both' => 'true',
    'cookie_value_none' => 'false',
    'cookie_expiration_days' => '365',
    'gtm_event' => 'cookie_refresh',
    'ignored_paths' => [],
    /**
     * Skip cookie consent on error responses (4xx and 5xx status codes).
     * Set to true if you want to disable cookie banner on error pages.
     */
    'skip_on_error_responses' => false,
    'cookie_secure' => env('COOKIE_CONSENT_SECURE', false),
    'policy_url_en' => env('COOKIE_POLICY_URL_EN', null),
    'policy_url_fr' => env('COOKIE_POLICY_URL_FR', null),
    'policy_url_nl' => env('COOKIE_POLICY_URL_NL', null),
];
