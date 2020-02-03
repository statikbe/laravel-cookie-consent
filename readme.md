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
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookieConsent"
```

Publish the config-file:

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="config"
```

This is the contents of the published config-file:
This will read the policy urls from your env. 
```php
return [
    'policyUrl_nl' => env('COOKIE_POLICY_URL_NL', null),
    'policyUrl_en' => env('COOKIE_POLICY_URL_EN', null),
    'policyUrl_fr' => env('COOKIE_POLICY_URL_FR', null)
];
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
     'title_cookiebanner' => 'This website uses cookies to improve your browsing experience. By clicking on ‘Agree’, you accept the use of these cookies.',
     'active_gdpr' => 'Active',
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
<a href="#" class="js-cookie-settings">{{ trans('cookieConsent::texts.settings_notice_gdpr') }}</a>
```

This gives your visitor the opportunity to change the settings again.

## Security

If you discover any security related issues, please email [info@statik.be](mailto:info@statik.be) instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
