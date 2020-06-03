const mix = require('laravel-mix');

mix
  .js('resources/js/cookie-consent.js', 'public/js')
  .sass('resources/sass/cookie-consent.scss', 'public/css');