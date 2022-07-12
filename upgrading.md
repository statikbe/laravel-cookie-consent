# Upgrading

## 1.3 to 1.4
This is an **optional** upgrade. There are only some styling changes.

To get all the latest changes you will have to upgrade the published files under your `public/vendor/cookie-consent` folder.

The simplest way (if you have not made any changes yourself) is to delete this folder and run:
```php
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="public"
```
