# Show a Cookie consent modal on your Laravel application

The package includes a script & styling for a cookie banner and a modal where the visitor can select his/her cookie preferences.

This package is mainly based on the one from spatie: https://github.com/spatie/laravel-cookie-consent

With the only exception that you can choose which cookies you enable.
This only works when Google Tag Manager is correctly configured (some regex config based on the value set in the cookie).

## Installation

You can install the package via composer:

``` bash
composer require statikbe/laravel-cookie-consent
```

The package will automatically register itself.

First of all **you need to** publish the javascript and css files:
```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="public"
```

Include the css/cookie-consent.css & js/cookie-consent.js into your base.blade.php or any other base template you use.
```
<link rel="stylesheet" type="text/css" href="{{asset("vendor/cookie-consent/css/cookie-consent.css")}}">
```
## Usage

Instead of including a snippet in your view, we will automatically add it. You only need to add `Statikbe\CookieConsent\CookieConsentMiddleware` to your kernel:

```php
// app/Http/Kernel.php

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ...
        \Statikbe\CookieConsent\CookieConsentMiddleware::class,
    ];

    // ...
}
```

This will add `cookieConsent::index` to the content of your response right before the closing body tag.

## Customising the dialog texts

If you want to modify the text shown in the dialog you can publish the lang-files with this command:

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="lang"
```

This will publish this file to `resources/lang/vendor/cookieConsent/en/texts.php`.
 ```php
 
 return [
     'alert_title' => 'Deze website gebruikt cookies',
     'setting_analytics' => 'Analytische cookies',
 ];
 ```
 
 If you want to translate the values to, for example, English, just copy that file over to `resources/lang/vendor/cookieConsent/fr/texts.php` and fill in the English translations.
 
### Customising the dialog contents

If you need full control over the contents of the dialog. You can publish the views of the package:

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="views"
```

This will copy the `index`  view file over to `resources/views/vendor/cookieConsent`.

The `cookie-settings` view file is just a snippet you need to place somewhere onto your page. Most preferably in the footer next to the url of your cookie policy.

```html 
<a href="javascript:void(0)" class="js-lcc-settings-toggle">'cookie-consent::texts.alert_settings'|translate}</a>
```

This gives your visitor the opportunity to change the settings again.
### Publishing
#### Config

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="config"
```
This is the contents of the published config-file:
This will read the policy urls from your env. 
```php
return [
    'cookie_key' => '__cookie_consent',
    'cookie_value_analytics' => '2',
    'cookie_value_marketing' => '3',
    'cookie_value_both' => 'true',
    'cookie_value_none' => 'false',
    'cookie_expiration_days' => '365',
    'gtm_event' => 'pageview',
    'ignored_paths' => [],
    'policy_url_en' => env('COOKIE_POLICY_URL_EN', null),
    'policy_url_fr' => env('COOKIE_POLICY_URL_FR', null),
    'policy_url_nl' => env('COOKIE_POLICY_URL_NL', null),
];
```
You can customize some settings that work with your GTM.

#### Translations

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="lang"
```

#### Views

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="views"
```


## Security

If you discover any security related issues, please email [info@statik.be](mailto:info@statik.be) instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
