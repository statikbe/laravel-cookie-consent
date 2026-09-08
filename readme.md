<p align="center"><img src="docs/img/card.png" alt="Card of Laravel Cookie consent"></p>

# Laravel cookie consent modal

[![Latest Version on Packagist](https://img.shields.io/packagist/v/statikbe/laravel-cookie-consent.svg?style=flat-square)](https://packagist.org/packages/statikbe/laravel-cookie-consent)
[![Total Downloads](https://img.shields.io/packagist/dt/statikbe/laravel-cookie-consent.svg?style=flat-square)](https://packagist.org/packages/statikbe/laravel-cookie-consent)

![Modal cookie consent](docs/img/modal.png?raw=true 'Modal for Cookie consent')

![Preferences Modal](docs/img/preferences.png?raw=true 'Preferences for cookies')

Cookie banner and preferences modal for Laravel. Visitors choose which cookie categories they accept; Google Tag Manager reads the resulting cookie to decide which tags fire. Based on [spatie/laravel-cookie-consent](https://github.com/spatie/laravel-cookie-consent) with added per-category consent.

## Requirements

| Laravel | PHP  |
|---------|------|
| 10–13   | 8.0+ |

## Upgrading

See [upgrading.md](upgrading.md).

## Table of contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Filament Integration](#filament-integration)
- [Customisation](#customisation)
- [Google Tag Manager](#google-tag-manager)
- [Security](#security)
- [License](#license)

## Installation

### 1. Install via Composer

```bash
composer require statikbe/laravel-cookie-consent
```

The package registers itself automatically.

### 2. Register the middleware

**Laravel 11 and later** — in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \Statikbe\CookieConsent\CookieConsentMiddleware::class,
    ]);
})
```

Or as a named alias:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'cookie-consent' => \Statikbe\CookieConsent\CookieConsentMiddleware::class,
    ]);
})
```

**Laravel 10 and earlier** — in `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \Statikbe\CookieConsent\CookieConsentMiddleware::class,
    ],
];
```

Or as a named middleware applied to specific routes:

```php
// app/Http/Kernel.php
protected $routeMiddleware = [
    'cookie-consent' => \Statikbe\CookieConsent\CookieConsentMiddleware::class,
];

// routes/web.php
Route::middleware('cookie-consent')->group(function () {
    // ...
});
```

The middleware injects the cookie consent snippet into every HTML response before the closing `</body>` tag.

### 3. Publish the assets

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-public"
```

### 4. Include the stylesheet (default theme only)

Add this to your base template. Skip this step if you are using the Filament theme — see [Filament Integration](#filament-integration).

```html
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/cookie-consent/css/cookie-consent.css') }}">
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-config"
```

`config/cookie-consent.php`:

```php
return [
    /*
     * Theme for the cookie consent popup.
     * Options: 'default', 'filament'
     */
    'theme' => 'default',

    /*
     * Filament render hook used to register the cookie settings nav item.
     * Set to null to disable the nav item entirely.
     * See: Filament Integration > Nav item
     */
    'filament-nav-item-render-hook' => \Filament\View\PanelsRenderHook::USER_MENU_PROFILE_AFTER,

    /*
     * Name of the cookie written to the browser.
     * If you change this, update the GTM variable name to match.
     */
    'cookie_key' => '__cookie_consent',

    /*
     * Values written to the cookie for each consent choice.
     *
     *   analytics only  => '2'
     *   marketing only  => '3'
     *   both accepted   => 'true'
     *   none accepted   => 'false'
     *
     * GTM reads this value with regex triggers to decide which tags fire.
     * If you change these values, update your GTM triggers to match.
     */
    'cookie_value_analytics' => '2',
    'cookie_value_marketing' => '3',
    'cookie_value_both' => 'true',
    'cookie_value_none' => 'false',

    'cookie_expiration_days' => '365',

    /*
     * GTM custom event fired after the visitor saves their preferences.
     */
    'gtm_event' => 'cookie_refresh',

    /*
     * Relative paths where the cookie banner is suppressed.
     * Accepts wildcards via Str::is() — e.g. '/api/*', '/en/cookie-policy'.
     */
    'ignored_paths' => [],

    /*
     * Set to true to suppress the banner on 4xx/5xx error pages.
     */
    'skip_on_error_responses' => false,

    /*
     * Mark the consent cookie as Secure (HTTPS only).
     * Reads from the COOKIE_CONSENT_SECURE env variable.
     */
    'cookie_secure' => env('COOKIE_CONSENT_SECURE', false),

    /*
     * Cookie policy page URLs shown in the banner.
     * Read from env — add only the locales your site supports.
     */
    'policy_url_en' => env('COOKIE_POLICY_URL_EN', null),
    'policy_url_fr' => env('COOKIE_POLICY_URL_FR', null),
    'policy_url_nl' => env('COOKIE_POLICY_URL_NL', null),
];
```

### Hiding the banner on specific pages

```php
'ignored_paths' => ['/en/cookie-policy', '/api/documentation*'],
```

Wildcards use Laravel's [`Str::is()`](https://laravel.com/docs/helpers#method-str-is) matching.

### Hiding the banner on error pages

```php
'skip_on_error_responses' => true,
```

## Filament Integration

If your project uses [Filament](https://filamentphp.com), you can render the cookie banner using Filament components instead of the default styled theme.

### 1. Enable the Filament theme

```php
// config/cookie-consent.php
'theme' => 'filament',
```

This will render the cookie popup using Filament components:

![screenshot of filament theme](./assets/screenshot-filament.png)

The default theme looks like this for comparison:

![screenshot of default theme](./assets/screenshot-default.png)

### 2. Configure Tailwind to scan the package views

**Tailwind v4** — in your main CSS file:

```css
@source 'vendor/statikbe/laravel-cookie-consent/resources/**/*.blade.php';
```

**Tailwind v3** — in `tailwind.config.js`:

```js
export default {
    content: [
        'vendor/statikbe/laravel-cookie-consent/resources/**/*.blade.php',
    ]
}
```

### 3. Filament styles outside the panel

If you display the cookie banner on pages that are not inside a Filament panel, include Filament's CSS and JS in those page templates.

### 4. Cookie settings nav item

When the Filament theme is active, the package registers a "Cookie settings" link in the Filament user menu. The position is controlled by `filament-nav-item-render-hook`, which defaults to after the profile menu item.

To change the position, set a different render hook value:

```php
'filament-nav-item-render-hook' => \Filament\View\PanelsRenderHook::SIDEBAR_NAV_END,
```

To remove the nav item entirely:

```php
'filament-nav-item-render-hook' => null,
```

## Customisation

### Translations

Publish the language files:

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-lang"
```

Files land in `lang/vendor/cookie-consent/{locale}/texts.php`. To add a new locale, copy the `en` directory to the target locale and translate the strings.

### Views

Publish the view files:

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-views"
```

Files land in `resources/views/vendor/cookie-consent`.

To let visitors re-open the preferences modal (e.g. from your footer next to the cookie policy link):

```html
<button type="button" class="js-lcc-settings-toggle">
    @lang('cookie-consent::texts.alert_settings')
</button>
```

## Google Tag Manager

Set up GTM to read the consent cookie and control which tags fire. Full setup instructions: [docs/google-tag-manager.md](docs/google-tag-manager.md).

## Security

If you discover a security issue, please email [info@statik.be](mailto:info@statik.be) instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](license.md) for more information.
