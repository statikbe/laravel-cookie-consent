# Laravel cookie consent bottom window

[![Latest Version on Packagist](https://img.shields.io/packagist/v/infernalmedia/laravel-cookie-consent.svg?style=flat-square)](https://packagist.org/packages/infernalmedia/laravel-cookie-consent)
[![Total Downloads](https://img.shields.io/packagist/dt/infernalmedia/laravel-cookie-consent.svg?style=flat-square)](https://packagist.org/packages/infernalmedia/laravel-cookie-consent)


The package includes a script & styling for a cookie banner and a modal where the visitor can select his/her cookie preferences.

This package is mainly based on the one from spatie: https://github.com/spatie/laravel-cookie-consent

With the only exception that you can choose which cookies you enable.
This only works when Google Tag Manager is correctly configured (some regex config based on the value set in the cookie).

* [Upgrading](upgrading.md)
* [Installation](#installation)
* [Usage](#usage)
* [Customizing the dialog texts](#customizing-the-dialog-texts)
  * [Customizing the dialog contents](#customizing-the-dialog-contents)
    + [Publishing](#publishing)
      - [Config](#config)
      - [Translations](#translations)
      - [Views](#views)
* [Configure Google Tag Manager](#configure-google-tag-manager)
* [Security](#security)
* [License](#license)
   

## Installation

You can install the package via composer:

``` bash
composer require infernalmedia/laravel-cookie-consent
```

The package will automatically register itself.

First of all **you need to** publish the javascript and css files:
```bash
php artisan vendor:publish --provider="Infernalmedia\CookieConsent\CookieConsentServiceProvider" --tag="public"
```
## Usage

Instead of including a snippet in your view, we will automatically add it. This is done using middleware using two methods:

1. The first option: include it in your entire project using the kernel:

```php
// app/Http/Kernel.php

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ...
        \Infernalmedia\CookieConsent\CookieConsentMiddleware::class,
    ];

    // ...
}
```

2. The second option: include it as a route middleware and add this to any route you want.

```php
// app/Http/Kernel.php

class Kernel extends HttpKernel
{
    // ...
    
    protected $routeMiddleware = [
        // ...
        'cookie-consent' => \Infernalmedia\CookieConsent\CookieConsentMiddleware::class,
    ];
}


// routes/web.php
Route::group([
    'middleware' => ['cookie-consent']
], function(){
    // ...
});
```

This will add `cookieConsent::head` to the content of your response right before the closing head tag.
This will add `cookieConsent::index` to the content of your response right before the closing body tag.

## Customizing the dialog texts

If you want to modify the text shown in the dialog you can publish the lang-files with this command:

```bash
php artisan vendor:publish --provider="Infernalmedia\CookieConsent\CookieConsentServiceProvider" --tag="lang"
```

This will publish this file to `resources/lang/vendor/cookieConsent/en/texts.php`.
 ```php
 
 return [
     'alert_title' => 'Deze website gebruikt cookies',
     'setting_analytics' => 'Analytische cookies',
 ];
 ```
 
 If you want to translate the values to, for example, English, just copy that file over to `resources/lang/vendor/cookieConsent/fr/texts.php` and fill in the English translations.
 
### Customizing the dialog contents

If you need full control over the contents of the dialog. You can publish the views of the package:

```bash
php artisan vendor:publish --provider="Infernalmedia\CookieConsent\CookieConsentServiceProvider" --tag="views"
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
php artisan vendor:publish --provider="Infernalmedia\CookieConsent\CookieConsentServiceProvider" --tag="config"
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
    'facebook_pixel_code' => env('FACEBOOK_PIXEL_CODE', null),
];
```

You can customize some settings that work with your GTM and Facebook Pixel.

#### Don't show modal on cookie policy page or other pages

If you don't want the modal to be shown on certain pages you can add the relative url to the ignored paths setting. This also accepts wildcards (see the Laravel `Str::is()` [helper](https://laravel.com/docs/9.x/helpers#method-str-is)).

```php
'ignored_paths => ['/en/cookie-policy', '/api/documentation*'];
```

#### Translations

```bash
php artisan vendor:publish --provider="Infernalmedia\CookieConsent\CookieConsentServiceProvider" --tag="lang"
```

#### Views

```bash
php artisan vendor:publish --provider="Infernalmedia\CookieConsent\CookieConsentServiceProvider" --tag="views"
```

## Configure Google Tag Manager

All the steps to configure your Google Tag Manager can be found [here](docs/google-tag-manager.md).


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

* [Guillaume Ernst](https://github.com/infernalmedia)
* [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
