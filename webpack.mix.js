const mix = require('laravel-mix');

mix
  .js('resources/js/cookie-consent-default.js', 'public/js')
  .js('resources/js/cookie-consent-filament.js', 'public/js')
  .sass('resources/sass/cookie-consent.scss', 'public/css');