---
name: cookie-consent
description: >
  Integrate and configure the statikbe/laravel-cookie-consent package in a Laravel
  application: register the middleware, publish the JS/CSS assets, detect whether the app is
  a Filament-only panel or has a public frontend and pick the matching banner theme,
  translate the dialog, exclude paths, and wire consent to Google Tag Manager.
license: MIT
metadata:
  author: statikbe
---

# Laravel Cookie Consent

Use this skill when a Laravel application needs a GDPR cookie banner + preferences modal
from `statikbe/laravel-cookie-consent`, or when changing its configuration, texts, theme,
or Google Tag Manager wiring.

## Primary Goal

- Get the banner rendering in the theme that matches the host app — Filament-only panel vs.
  public frontend — and the consent cookie driving GTM, with the smallest correct set of
  changes.

## Workflow

### 1. Install and publish the assets

```bash
composer require statikbe/laravel-cookie-consent
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-public"
```

The provider auto-registers. Publishing `cookie-public` is **not optional** — the rendered
snippet loads `asset('vendor/cookie-consent/js/cookie-consent.js')` (or
`cookie-consent-filament.js`), so the banner silently does nothing without it. Re-run the
publish command with `--force` after every package upgrade.

### 2. Register the middleware

The package injects nothing until `CookieConsentMiddleware` runs. Laravel 11+:

```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \Statikbe\CookieConsent\CookieConsentMiddleware::class,
    ]);
})
```

Laravel 10 and earlier: add it to `$middleware` in `app/Http/Kernel.php`. To scope it to
specific routes instead, alias it (`'cookie-consent' => CookieConsentMiddleware::class`)
and apply the alias to a route group.

The middleware appends `cookie-consent::index` before the last `</body>`. It is a no-op when
the response is not an `Illuminate\Http\Response` (JSON, streamed, redirect, file downloads)
or has no `</body>`, so it is safe on API routes.

### 3. Determine the app's shape before picking a theme

**Do this before writing any config.** `theme` is a single global value — the service
provider swaps the entire `cookie-consent::` view namespace at boot, so the app gets one
banner style on every page. The right value depends on whether the app has a public
frontend or is nothing but a Filament panel. Never infer it from "is Filament installed".

Inspect the host app:

```bash
composer show filament/filament >/dev/null 2>&1 && echo "filament installed"
php artisan route:list --method=GET          # any GET routes outside the panel path?
ls resources/views/layouts resources/views/components/layouts 2>/dev/null
grep -n "input\|resources/" vite.config.js   # app.css/app.js, or only a filament theme?
```

**Filament-only** — the panel *is* the app. Signals: `routes/web.php` has no page routes of
its own (empty, or just a redirect to the panel), no public layout under
`resources/views/layouts/`, `vite.config.js` builds only
`resources/css/filament/*/theme.css`, and the panel provider uses `->path('')` or is the
only entry point.

→ **`'theme' => 'filament'`** — renders with `x-filament::modal` / `x-filament::button` and
Alpine, and registers the settings link in the user menu.

**Has a public frontend** — a marketing site, storefront, or any Blade/Livewire/Inertia
pages served to visitors, *whether or not* a Filament panel sits alongside it. Signals: GET
routes returning app views or controllers, a base layout, `resources/js/app.js` +
`resources/css/app.css` as Vite inputs, page components outside `app/Filament/`.

→ **`'theme' => 'default'`** — the visitors who need the consent banner are on the frontend,
and the Filament theme's markup only renders correctly where Filament's CSS and Alpine are
loaded. A mixed app cannot have both; see the gotcha below.

If the app is genuinely ambiguous (e.g. a panel plus one public login page), ask which
audience the banner is for rather than guessing.

### 4. Configure the chosen theme

Publish the config when anything needs changing:

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-config"
```

**Default theme** — plain CSS, no framework. Link the stylesheet in the app's base layout:

```blade
<link rel="stylesheet" href="{{ asset('vendor/cookie-consent/css/cookie-consent.css') }}">
```

*Mixed-app gotcha:* the middleware injects into **every** HTML response, Filament panel
pages included — where that stylesheet is not loaded, so the banner renders unstyled in the
admin. Panel routes sit behind auth and normally set only essential session cookies, so the
usual fix is to exclude them:

```php
'ignored_paths' => ['/admin*'],
```

If the panel does load GTM or other tracking and the banner must appear there too, inject
the stylesheet into the panel instead:

```php
// app/Providers/Filament/AdminPanelProvider.php
->renderHook(
    PanelsRenderHook::HEAD_END,
    fn () => new HtmlString('<link rel="stylesheet" href="'.asset('vendor/cookie-consent/css/cookie-consent.css').'">'),
)
```

**Filament theme** — requires Filament to be installed. Add the package's Blade files to the
Tailwind sources so the classes are not purged:

```css
/* Tailwind v4 — app.css */
@source '../../vendor/statikbe/laravel-cookie-consent/resources/**/*.blade.php';
```

```js
// Tailwind v3 — tailwind.config.js
content: ['./vendor/statikbe/laravel-cookie-consent/resources/**/*.blade.php'],
```

To show the Filament-themed banner outside a Filament panel, that page must also render
Filament's styles and scripts.

### 5. Give visitors a way to reopen the preferences

Nothing reopens the modal after the first choice unless the app renders a trigger — put one
in the footer, next to the cookie policy link.

Default theme (any element with the class works):

```blade
<a href="javascript:void(0)" class="js-lcc-settings-toggle">@lang('cookie-consent::texts.alert_settings')</a>
```

Filament theme: a user-menu item is registered automatically via the render hook in
`cookie-consent.filament-nav-item-render-hook` — this only happens when
`theme => 'filament'`, so a mixed app on the default theme still needs its own trigger in
the frontend footer. (Default
`PanelsRenderHook::USER_MENU_PROFILE_AFTER`; set it to `null` to disable). For a custom
trigger, dispatch the Filament modal event:

```blade
<button x-on:click="$dispatch('open-modal', { id: '{{ \Statikbe\CookieConsent\CookieConsentServiceProvider::COOKIE_CONSENT_SETTINGS_MODAL_ID }}' })">
    @lang('cookie-consent::texts.alert_settings')
</button>
```

### 6. Configure the remaining keys

All keys live in `config/cookie-consent.php`:

| Key | Purpose |
| --- | --- |
| `theme` | `'default'` or `'filament'` |
| `filament-nav-item-render-hook` | Where to inject the Filament settings link; `null` disables it |
| `cookie_key` | Consent cookie name (default `__cookie_consent`) |
| `cookie_value_analytics` / `_marketing` / `_both` / `_none` | Values written for each choice (`2` / `3` / `true` / `false`) — these are what GTM matches on |
| `cookie_expiration_days` | Consent lifetime (default `365`) |
| `gtm_event` | Event pushed to `window.dataLayer` when consent changes |
| `ignored_paths` | Paths where the banner is not injected; `Str::is()` wildcards allowed |
| `skip_on_error_responses` | `true` hides the banner on 4xx/5xx responses |
| `cookie_secure` | `COOKIE_CONSENT_SECURE` — set `true` in production |
| `policy_url_en` / `_fr` / `_nl` | `COOKIE_POLICY_URL_*`, interpolated into the settings text |

`ignored_paths` is matched against `$request->getPathInfo()`, so include the leading slash
and any locale prefix — and add the cookie policy page itself:

```php
'ignored_paths' => ['/en/cookie-policy', '/nl/cookie-policy', '/api/documentation*'],
```

The cookie is written on the domain from `config('session.domain')`, so a shared
`SESSION_DOMAIN` gives one consent across subdomains.

### 7. Translate and customise the dialog

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-lang"
```

Ships with `en`, `nl`, `fr`, `es`, `oc` under the `cookie-consent::texts.*` namespace.
Published to `lang/vendor/cookie-consent/{locale}/texts.php`; add a locale directory to
support more. `settings_text` receives `:policyUrl` from the `policy_url_{locale}` config
key, so add a config entry for every locale used. The Filament theme reads
`texts.filament.settings_text` (rendered through Blade, so it may contain
`<x-filament::link>`) instead of `texts.settings_text`.

For full control over the markup:

```bash
php artisan vendor:publish --provider="Statikbe\CookieConsent\CookieConsentServiceProvider" --tag="cookie-views"
```

This copies the views to `resources/views/vendor/cookie-consent`. Note it always publishes
the **default-theme** views — under `theme => 'filament'`, copy from
`vendor/statikbe/laravel-cookie-consent/resources/views-filament` by hand instead. When
editing published views, keep the `js-lcc-*` classes and the `data-cookie-*` attributes on
`.js-lcc-modal-alert`: the bundled JS reads its whole configuration from them.

### 8. Wire up Google Tag Manager

The package only stores the choice; GTM decides what fires. Read the consent cookie in GTM
with a 1st-Party Cookie variable on `cookie_key`, then gate tags on it:

- `true` — analytics **and** marketing accepted
- `2` — analytics only
- `3` — marketing only
- `false` — essential only

Trigger re-evaluation on the custom event in `gtm_event` (default `cookie_refresh`), which
the script pushes to `window.dataLayer` on every consent change. Full walkthrough and an
importable container template:
<https://github.com/statikbe/laravel-cookie-consent/blob/main/docs/google-tag-manager.md>

### 9. React to consent in application JS

Both themes expose one global, registered before the banner initialises:

```js
window.cookieBannerConsentChange((value) => {
    // value is the raw cookie value: 'true' | '2' | '3' | 'false'
});
```

Only the latest callback is kept — register once and fan out from there.

## Rules, References, and Templates

Read before executing:

- `vendor/statikbe/laravel-cookie-consent/config/cookie-consent.php` — the full key list
- `vendor/statikbe/laravel-cookie-consent/src/CookieConsentMiddleware.php` — exactly when injection is skipped
- `vendor/statikbe/laravel-cookie-consent/resources/lang/en/texts.php` — every translation key
- <https://github.com/statikbe/laravel-cookie-consent/blob/main/docs/google-tag-manager.md> — GTM setup (not shipped in the Composer dist)

## Examples

- **Multilingual marketing site, no panel.** `route:list` shows public GET routes and there's
  a base layout → `theme => 'default'`. Publish `cookie-public` + `cookie-config`, append the
  middleware globally, link the CSS in the base layout, set `policy_url_nl` / `policy_url_fr`
  / `policy_url_en` from env, add each localised cookie-policy path to `ignored_paths`, and
  put a `.js-lcc-settings-toggle` link in the footer partial.
- **Filament-only app.** `routes/web.php` only redirects to the panel and Vite builds nothing
  but the panel theme → `theme => 'filament'`. Add the `@source` line to the panel theme's
  CSS, rebuild assets, and leave `filament-nav-item-render-hook` at its default so the
  settings link appears in the user menu.
- **Public site with a Filament admin alongside.** A frontend exists, so `theme => 'default'`
  wins. Add `'/admin*'` to `ignored_paths` so the unstyled default banner never reaches the
  panel, and add the footer trigger on the frontend — the Filament user-menu item is not
  registered under this theme.
- **API-heavy app.** Keep the middleware on the `web` group only; JSON responses are ignored
  by the middleware anyway, and set `skip_on_error_responses => true` to keep the banner off
  error pages.

## Anti-patterns

- Setting `theme => 'filament'` because Filament appears in `composer.json`. The question is
  whether the app has a public frontend, not whether Filament is installed — on a frontend
  page the Filament components render without their CSS or Alpine and the banner breaks.
- Leaving `theme => 'default'` on a mixed app without excluding the panel path, which drops
  an unstyled banner on top of the admin UI.
- Trying to serve the Filament banner in the panel and the default banner on the frontend.
  `theme` is one global value; pick the audience that matters and give the other one
  `ignored_paths`.
- Registering the middleware but skipping `--tag="cookie-public"` — the banner renders and
  the script 404s, so no consent is ever recorded.
- Adding the middleware to the `api` group or to a Livewire/Inertia partial-response path in
  the hope of injecting the banner there; injection needs a full HTML document.
- Hand-writing the consent cookie or reading it server-side to gate scripts. Consent is
  client-side; gate third-party tags in GTM using the documented cookie values.
- Renaming or removing `js-lcc-accept`, `js-lcc-essentials`, `js-lcc-settings-toggle`,
  `js-lcc-settings-save`, or the `lcc-checkbox-analytics` / `lcc-checkbox-marketing` ids in
  published views — the bundled JS binds to them by name.
- Changing `cookie_value_*` in config without updating the matching GTM triggers.
- Leaving the cookie-policy page out of `ignored_paths`, which traps visitors behind the
  banner on the page explaining it.
