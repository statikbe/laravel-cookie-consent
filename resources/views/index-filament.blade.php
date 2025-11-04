@php
    $locale = $app->getLocale();
    $cookieModalHeading = __('cookie-consent::texts.alert_title');
    $cookieModalDescription = __('cookie-consent::texts.alert_text');
    $settingsModalHeading = __('cookie-consent::texts.settings_title');
    $settingsModalDescription = Blade::render(
        __('cookie-consent::texts.filament.settings_text', [
            'policyUrl' => config("cookie-consent.policy_url_$locale"),
        ]),
    );
@endphp

<div
    x-data="cookieConsentModal({
                COOKIE_KEY:
                    '{{ config('cookie-consent.cookie_key', '__cookie_consent') }}',
                COOKIE_VALUE_ANALYTICS:
                    '{{ config('cookie-consent.cookie_value_analytics', '2') }}',
                COOKIE_VALUE_MARKETING:
                    '{{ config('cookie-consent.cookie_value_marketing', '3') }}',
                COOKIE_VALUE_BOTH:
                    '{{ config('cookie-consent.cookie_value_both', 'true') }}',
                COOKIE_VALUE_NONE:
                    '{{ config('cookie-consent.cookie_value_none', 'false') }}',
                COOKIE_EXPIRATION_DAYS:
                    '{{ config('cookie-consent.cookie_expiration_days', '365') }}',
                GTM_EVENT: '{{ config('cookie-consent.gtm_event', 'pageview') }}',
                IGNORED_PATHS:
                    '{{ json_encode(config('cookie-consent.ignored_paths', [])) }}',
                SESSION_DOMAIN: '{{ config('session.domain') }}',
                COOKIE_SECURE: '{{ config('cookie-consent.cookie_secure', false) }}',
            })"
>
    {{-- COOKIE CONSENT MODAL --}}
    <x-filament::modal
        :close-button="false"
        :close-by-escaping="false"
        :close-by-clicking-away="false"
        id="cookie-consent-modal"
        width="lg"
        heading="{{ $cookieModalHeading }}"
        description="{!! $cookieModalDescription !!}"
    >
        <x-slot name="footer">
            <div class="flex flex-col gap-4">
                <x-filament::button @click="acceptAll">
                    {{ __('cookie-consent::texts.alert_accept') }}
                </x-filament::button>
                <x-filament::button @click="acceptEssentials">
                    {{ __('cookie-consent::texts.alert_essentials_only') }}
                </x-filament::button>
                <x-filament::button @click="openSettings" color="gray">
                    {{ __('cookie-consent::texts.alert_settings') }}
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::modal>

    {{-- COOKIE SETTINGS MODAL --}}
    <x-filament::modal id="cookie-consent-settings-modal" width="lg" heading="{{ $settingsModalHeading }}">
        <x-filament::modal.description class="-mt-4">
            {!! $settingsModalDescription !!}
        </x-filament::modal.description>

        <label class="text-sm">
            <div class="flex items-center gap-2 text-gray-500">
                <x-filament::input.checkbox disabled="disabled" checked="checked" />
                <span>
                    {{ __('cookie-consent::texts.setting_essential') }}
                </span>
            </div>

            <p class="pl-6 text-sm">
                {{ __('cookie-consent::texts.setting_essential_text') }}
            </p>
        </label>

        <label class="text-sm">
            <div class="flex items-center gap-2 text-gray-500">
                <x-filament::input.checkbox disabled="disabled" checked="checked" />
                <span>
                    {{ __('cookie-consent::texts.setting_functional') }}
                </span>
            </div>
            <p class="pl-6 text-sm">
                {{ __('cookie-consent::texts.setting_functional_text') }}
            </p>
        </label>

        <label class="text-sm">
            <div class="flex items-center gap-2 font-semibold">
                <x-filament::input.checkbox x-model="checkboxAnalytics" />
                <span>
                    {{ __('cookie-consent::texts.setting_analytics') }}
                </span>
            </div>
            <p class="pl-6 text-sm">
                {{ __('cookie-consent::texts.setting_analytics_text') }}
            </p>
        </label>

        <label class="text-sm">
            <div class="flex items-center gap-2 font-semibold">
                <x-filament::input.checkbox x-model="checkboxMarketing" />
                <span>
                    {{ __('cookie-consent::texts.setting_marketing') }}
                </span>
            </div>
            <p class="pl-6 text-sm">
                {{ __('cookie-consent::texts.setting_marketing_text') }}
            </p>
        </label>

        <x-slot name="footer">
            <div class="flex flex-col justify-end gap-2">
                <x-filament::button @click="saveSettings">
                    {{ __('cookie-consent::texts.settings_save') }}
                </x-filament::button>
                <x-filament::button @click="acceptAll" color="gray">
                    {{ __('cookie-consent::texts.alert_accept') }}
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::modal>
</div>

@vite('packages/laravel-cookie-consent/resources/js/cookie-consent-filament.js')
