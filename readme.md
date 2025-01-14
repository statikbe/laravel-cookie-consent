<p align="center"><img src="docs/img/card.png" alt="Card of Laravel Cookie consent"></p>

# Laravel cookie consent modal
[![Latest Version on Packagist](https://img.shields.io/packagist/v/statikbe/laravel-cookie-consent.svg?style=flat-square)](https://packagist.org/packages/statikbe/llaravel-cookie-consent)
[![Total Downloads](https://img.shields.io/packagist/dt/statikbe/laravel-cookie-consent.svg?style=flat-square)](https://packagist.org/packages/statikbe/laravel-cookie-consent)


![Modal cookie consent](docs/img/modal.png?raw=true "Modal for Cookie consent")

![Preferences Modal](docs/img/preferences.png?raw=true "Preferences for cookies")


The package includes a script & styling for a cookie banner and a modal where the visitor can select his/her cookie preferences.

This package is mainly based on the one from spatie: https://github.com/spatie/laravel-cookie-consent

With the only exception that you can choose which cookies you enable.
This only works when Google Tag Manager is correctly configured (some regex config based on the value set in the cookie).

* [Upgrading](upgrading.md)
* [Installation](#installation)
* [Usage](#usage)
* [Customising the dialog texts](#customising-the-dialog-texts)
    + [Customising the dialog contents](#customising-the-dialog-contents)
    + [Publishing](#publishing)
      - [Config](#config)
      - [Translations](#translations)
      - [Views](#views)
* [Configure Google Tag Manager](#configure-google-tag-manager)
* [Security](#security)
* [License](#license)
   

## Upgrading
You can find our upgrading guides [here](upgrading.md).

## Installation

You can install the package via composer:

``` bash
composer require statikbe/laravel-cookie-consent
```

The package will automatically register itself.

First of all **you need to** publish the javascript and css files:
```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-public"
```

Include the css/cookie-consent.css into your base.blade.php or any other base template you use.
```
<link rel="stylesheet" type="text/css" href="{{asset("vendor/cookie-consent/css/cookie-consent.css")}}">
```

The javascript file is included in the cookie snippet and will be added at the end of your body.
## Usage

Instead of including a snippet in your view, we will automatically add it. This is done using middleware using two methods:

__For Laravel 11.x and newer__

```php
// bootstrap/app.php

->withMiddleware(function (Middleware $middleware) {
    ...
    $middleware->web(append: [
        ...
        \Statikbe\CookieConsent\CookieConsentMiddleware::class,
    ]);

// OR AS AN ALIAS

    $middleware->alias([
        ...
        'cookie-consent' => \Statikbe\CookieConsent\CookieConsentMiddleware::class,
    ]);
})

```
__For Laravel 10.x and earlier__

```php
// app/Http/Kernel.php

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ...
        \Statikbe\CookieConsent\CookieConsentMiddleware::class,
    ];

     protected $routeMiddleware = [
        // ...
        'cookie-consent' => \Statikbe\CookieConsent\CookieConsentMiddleware::class,
    ];
    
    
// routes/web.php
Route::group([
    'middleware' => ['cookie-consent']
], function(){
    // ...
});
}
```

This will add `cookieConsent::index` to the content of your response right before the closing body tag.

## Customising the dialog texts

If you want to modify the text shown in the dialog you can publish the lang-files with this command:

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-lang"
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
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-views"
```

This will copy the `index`  view file over to `resources/views/vendor/cookieConsent`.

The `cookie-settings` view file is just a snippet you need to place somewhere onto your page. Most preferably in the footer next to the url of your cookie policy.

```html 
<a href="javascript:void(0)" class="js-lcc-settings-toggle">@lang('cookie-consent::texts.alert_settings')</a>
```

This gives your visitor the opportunity to change the settings again.
### Publishing
#### Config

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-config"
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

#### Don't show modal on cookie policy page or other pages
If you don't want the modal to be shown on certain pages you can add the relative url to the ignored paths setting. This also accepts wildcards (see the Laravel `Str::is()` [helper](https://laravel.com/docs/9.x/helpers#method-str-is)).
```
'ignored_paths => ['/en/cookie-policy', '/api/documentation*'];
```

#### Translations

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-lang"
```

#### Views

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-views"
```

## Configure Google Tag Manager
All the steps to configure your Google Tag Manager can be found [here](docs/google-tag-manager.md).


## Security

If you discover any security related issues, please email [info@statik.be](mailto:info@statik.be) instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
