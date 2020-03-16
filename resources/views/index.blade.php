<script type="text/javascript" src="{{ asset('vendor/cookieConsent/js/gdpr.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/cookieConsent/css/gdpr.css') }}">
@php($policyVar = 'policyUrl_' . app()->getLocale())

<div class="cookiebanner" id="cookiebanner">
    <div class="section section--light section--sml">
        <div class="container">
            <div class="cookie__wrapper">
                <h2 class="cookie__title">{{ trans('cookieConsent::texts.title_gdpr') }}</h2>
                <div class="cookie__body">
                    {{ trans('cookieConsent::texts.title_cookiebanner') }}
                </div>
                <div class="cookie__cta">
                    <a href="#" class="btn btn--brand cookiemonster__accept js-cookie-accept">{{ trans('cookieConsent::texts.accept_notice_gdpr') }}</a>
                    <a href="#" class="inline__item cookiemonster__settings js-cookie-settings">{{ trans('cookieConsent::texts.settings_notice_gdpr') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="cookiebanner-overlay" id="cookiebanner-overlay"></div>

<div class="cookie-modal" id="cookieModal">
    <div class="cookie-modal__content">
        <h2 class="text--center">{{ trans('cookieConsent::texts.settings_notice_gdpr') }}</h2>
        <p class="modal__description">
            {!! trans('cookieConsent::texts.body_notice_gdpr', ['policyUrl' => config("cookie-consent.$policyVar")]) !!}
        </p>
        <br>
        <form>
            <div class="cookie__section">
                <strong class="cookie__title">{{ trans('cookieConsent::texts.title_essential_modal_gdpr') }}</strong><br>
                <p class="block modal__description">
                    {!! trans('cookieConsent::texts.text_essential_modal_gdpr') !!}<br>
                </p>
                <input class="js-cookie-essential" type="checkbox" name="essential" id="essential"  disabled="disabled" checked="checked">

                <label>{{ trans('cookieConsent::texts.alwaysactive_gdpr') }}</label>
            </div>
            <div class="cookie__section">
                <strong class="cookie__title">{{ trans('cookieConsent::texts.title_performance_modal_gdpr') }}</strong><br>
                <p class="block modal__description">
                    {!! trans('cookieConsent::texts.text_performance_modal_gdpr') !!}<br>
                </p>
                <input class="js-cookie-performance" type="checkbox" name="CookiePerformance" id="performance" value="2">
                <label for="performance" id="performanceLabel" class="js-cookie-performance"> {{ trans('cookieConsent::texts.notactive_gdpr') }}</label>
            </div>
            <div class="cookie__section">
                <strong class="cookie__title">{{ trans('cookieConsent::texts.title_marketing_modal_gdpr') }}</strong><br>
                <p class="block modal__description">
                    {!! trans('cookieConsent::texts.text_marketing_modal_gdpr') !!}<br>
                </p>
                <input class="js-cookie-marketing" type="checkbox" name="CookieMarketing" id="marketing" value="3">
                <label for="marketing" id="marketingLabel" class="js-cookie-marketing">{{ trans('cookieConsent::texts.notactive_gdpr') }}</label>
            </div>
        </form>
        <p class="text--center spacer">
            <a href="#" class="btn btn--default js-modal-close">{{ trans('cookieConsent::texts.close_modal_gdpr') }}</a>
        </p>
    </div>
</div>
