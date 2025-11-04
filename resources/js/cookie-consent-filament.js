import { getCookie, setCookie } from './util/cookies';

let onConsentChange;
window.cookieBannerConsentChange = (callback) => {
    onConsentChange = callback;
};

document.addEventListener('alpine:init', () => {
    Alpine.data('cookieConsentModal', (props) => ({
        COOKIE_KEY: props.COOKIE_KEY,
        IGNORED_PATHS: JSON.parse(props.IGNORED_PATHS),
        COOKIE_VALUE_ANALYTICS: props.COOKIE_VALUE_ANALYTICS,
        COOKIE_VALUE_MARKETING: props.COOKIE_VALUE_MARKETING,
        COOKIE_VALUE_BOTH: props.COOKIE_VALUE_BOTH,
        COOKIE_VALUE_NONE: props.COOKIE_VALUE_NONE,
        COOKIE_EXPIRATION_DAYS: props.COOKIE_EXPIRATION_DAYS,
        GTM_EVENT: props.GTM_EVENT,
        SESSION_DOMAIN: props.SESSION_DOMAIN,
        COOKIE_SECURE: props.COOKIE_SECURE,
        checkboxAnalytics: false,
        checkboxMarketing: false,
        init() {
            this.$nextTick(() => {
                this.initialize();
            });
        },
        // Button listeners
        acceptAll() {
            this.updateCookie(this.COOKIE_VALUE_BOTH);
            this.closeCookieConsentModal();
            this.closeCookieSettingsModal();
        },
        acceptEssentials() {
            this.updateCookie(this.COOKIE_VALUE_NONE);
            this.closeCookieConsentModal();
            this.closeCookieSettingsModal();
        },
        openSettings() {
            this.openCookieSettingsModal();
        },
        saveSettings() {
            let cookieValue;

            if (this.checkboxAnalytics && this.checkboxMarketing) {
                cookieValue = this.COOKIE_VALUE_BOTH;
            } else if (this.checkboxAnalytics) {
                cookieValue = this.COOKIE_VALUE_ANALYTICS;
            } else if (this.checkboxMarketing) {
                cookieValue = this.COOKIE_VALUE_MARKETING;
            } else {
                cookieValue = this.COOKIE_VALUE_NONE;
            }

            this.updateCookie(cookieValue);
            this.closeCookieConsentModal();
            this.closeCookieSettingsModal();
        },
        // Helper functions
        openCookieConsentModal() {
            this.$dispatch('open-modal', { id: 'cookie-consent-modal' });
        },
        closeCookieConsentModal() {
            this.$dispatch('close-modal', { id: 'cookie-consent-modal' });
        },
        openCookieSettingsModal() {
            this.$dispatch('open-modal', { id: 'cookie-consent-settings-modal' });
        },
        closeCookieSettingsModal() {
            this.$dispatch('close-modal', { id: 'cookie-consent-settings-modal' });
        },
        initialize() {
            const ignoredPathsArray = this.IGNORED_PATHS
                ? this.IGNORED_PATHS.map((ignoredPath) => ignoredPath.trim())
                : [];
            const isIgnoredPage = ignoredPathsArray.indexOf(location.pathname) > -1;

            const isRobot = /bot|google|baidu|bing|msn|duckduckbot|teoma|slurp|yandex/i.test(navigator.userAgent);

            if (isRobot) return;

            if (!getCookie(this.COOKIE_KEY) && !isIgnoredPage) {
                this.openCookieConsentModal();
            }

            this.fillFormData();
        },
        fillFormData() {
            const cookieValue = getCookie(this.COOKIE_KEY);

            this.checkboxAnalytics =
                cookieValue === this.COOKIE_VALUE_BOTH || cookieValue === this.COOKIE_VALUE_ANALYTICS;
            this.checkboxMarketing =
                cookieValue === this.COOKIE_VALUE_BOTH || cookieValue === this.COOKIE_VALUE_MARKETING;
        },
        updateCookie(cookieValue) {
            setCookie(
                this.COOKIE_KEY,
                cookieValue,
                this.COOKIE_EXPIRATION_DAYS,
                this.SESSION_DOMAIN,
                this.COOKIE_SECURE,
            );

            if (onConsentChange) {
                onConsentChange(cookieValue);
            }

            //  Fire GTM event if dataLayer is found
            if (window.dataLayer) {
                window.dataLayer.push({ event: this.GTM_EVENT });
            }
        },
    }));
});
