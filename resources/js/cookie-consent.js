import {getCookie, setCookie} from './util/cookies';

import {addEventListener, isHidden} from './util/dom';

import {hideModal, showModal} from './util/modals';

import 'wicg-inert';

const modalAlert = document.querySelector('.js-lcc-modal-alert');
const modalSettings = document.querySelector('.js-lcc-modal-settings');
const modalSettingsTrigger = document.querySelector('button.js-lcc-settings-toggle');
const backdrop = document.querySelector('.js-lcc-backdrop');
const checkboxAnalytics = document.getElementById('lcc-checkbox-analytics');
const checkboxMarketing = document.getElementById('lcc-checkbox-marketing');

const COOKIE_KEY = modalAlert.dataset.cookieKey || '__cookie_consent';
const COOKIE_VALUE_ANALYTICS = modalAlert.dataset.cookieValueAnalytics || '2';
const COOKIE_VALUE_MARKETING = modalAlert.dataset.cookieValueMarketing || '3';
const COOKIE_VALUE_NONE = modalAlert.dataset.cookieValueNone || 'false';
const COOKIE_VALUE_BOTH = modalAlert.dataset.cookieValueBoth || 'true';
const COOKIE_EXPIRATION_DAYS = modalAlert.dataset.cookieExpirationDays || '365';
const GTM_EVENT = modalAlert.dataset.gtmEvent || 'pageview';
const SESSION_DOMAIN = modalAlert.dataset.sessionDomain;
const COOKIE_SECURE = !!modalAlert.dataset.cookieSecure;

const ignoredPaths = modalAlert.dataset.ignoredPaths || null;

let onConsentChange;

initialize();

window.cookieBannerConsentChange = (callback) => {
    onConsentChange = callback;
};

function initialize() {

    const ignoredPathsArray = ignoredPaths ? ignoredPaths.split(',').map(ignoredPath => ignoredPath.trim()) : [];
    const isIgnoredPage = ignoredPathsArray.indexOf(location.pathname) > -1;

    const isRobot = /bot|google|baidu|bing|msn|duckduckbot|teoma|slurp|yandex/i.test(navigator.userAgent);

    if (isRobot || !modalAlert || !modalSettings) return;

    if (!getCookie(COOKIE_KEY) && !isIgnoredPage) {
        showModal(modalAlert);
    }

    initSettings();

    addEventListener('click', '.js-lcc-settings-toggle', function () {

        toggleModalSettings();
    });

    addEventListener('click', '.js-lcc-essentials', function () {

        updateCookie(COOKIE_VALUE_NONE);
        hideModal(modalSettings, true);
        hideModal(modalAlert);
    });

    addEventListener('click', '.js-lcc-accept', function () {

        updateCookie('true');
        hideModal(modalSettings, true);
        hideModal(modalAlert);
    });

    addEventListener('click', '.js-lcc-settings-save', function () {

        saveSettings();
        toggleModalSettings();
        hideModal(modalAlert);
    });
}

function toggleModalSettings() {

    if (isHidden(modalSettings)) {

        showModal(modalSettings, !isHidden(modalAlert));

        initSettings();

        backdrop.addEventListener('click', backdropListener);
        document.body.addEventListener('keydown', keyboardListener);

    } else {

        hideModalSettings();
    }

    function backdropListener() {

        hideModalSettings();
    }

    function keyboardListener(event) {

        //  Close modal on pressing Escape key
        if ((event.which || event.keyCode) === 27) {

            event.preventDefault();

            hideModalSettings();
        }
    }

    function hideModalSettings() {

        backdrop.removeEventListener('click', backdropListener);
        document.body.removeEventListener('keydown', keyboardListener);

        hideModal(modalSettings, !getCookie(COOKIE_KEY));

        if (!getCookie(COOKIE_KEY)) {
            showModal(modalAlert, true);
        }

        modalSettingsTrigger.focus();
    }
}

function initSettings() {

    const cookieValue = getCookie(COOKIE_KEY);

    checkboxAnalytics.checked = cookieValue === COOKIE_VALUE_BOTH || cookieValue === COOKIE_VALUE_ANALYTICS;
    checkboxMarketing.checked = cookieValue === COOKIE_VALUE_BOTH || cookieValue === COOKIE_VALUE_MARKETING;
}

function saveSettings() {

    let cookieValue;

    if (checkboxAnalytics.checked && checkboxMarketing.checked) {
        cookieValue = COOKIE_VALUE_BOTH;
    } else if (checkboxAnalytics.checked) {
        cookieValue = COOKIE_VALUE_ANALYTICS;
    } else if (checkboxMarketing.checked) {
        cookieValue = COOKIE_VALUE_MARKETING;
    } else {
        cookieValue = COOKIE_VALUE_NONE;
    }

    updateCookie(cookieValue);
}

function updateCookie(cookieValue) {

    setCookie(COOKIE_KEY, cookieValue, COOKIE_EXPIRATION_DAYS, SESSION_DOMAIN, COOKIE_SECURE);

    if (onConsentChange) {
        onConsentChange(cookieValue);
    }

    //  Fire GTM event if dataLayer is found
    if (window.dataLayer) {
        window.dataLayer.push({event: GTM_EVENT});
    }
}