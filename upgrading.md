# Upgrading
## 1.7 to 1.8
In order to use the domain setting on cookies you need to delete
`public/vendor/cookie-consent` folder and publish the assets again.
```php
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="public"
```
## 1.5 to 1.6
In order to use the new Google Consent mode, you need to delete
`public/vendor/cookie-consent` folder and publish the assets again.
```php
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="public"
```
## 1.3 to 1.4
This is an **optional** upgrade. There are only some styling changes.

To get all the latest changes you will have to upgrade the published files under your `public/vendor/cookie-consent` folder.

The simplest way (if you have not made any changes yourself) is to delete this folder and run:
```php
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="public"
```
