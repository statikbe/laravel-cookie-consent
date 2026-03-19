/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/util/cookies.js"
/*!**************************************!*\
  !*** ./resources/js/util/cookies.js ***!
  \**************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   getCookie: () => (/* binding */ getCookie),
/* harmony export */   setCookie: () => (/* binding */ setCookie)
/* harmony export */ });
function getCookie(key) {
  if (!key) return null;
  return decodeURIComponent(document.cookie.replace(new RegExp('(?:(?:^|.*;)\\s*' + encodeURIComponent(key).replace(/[\-\.\+\*]/g, '\\$&') + '\\s*\\=\\s*([^;]*).*$)|^.*$'), '$1')) || null;
}
function setCookie(key, value, expireDays, sessionDomain, secure) {
  var date = new Date();
  var expires = null;
  if (expireDays) {
    date.setTime(date.getTime() + expireDays * 24 * 60 * 60 * 1000);
    expires = date.toUTCString();
  }
  document.cookie = "".concat(encodeURIComponent(key), "=").concat(encodeURIComponent(value)).concat(expires ? "; expires=".concat(expires) : '', "; path=/").concat(sessionDomain ? "; domain=".concat(sessionDomain) : '').concat(secure ? '; secure' : '');
}

/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Check if module exists (development only)
/******/ 		if (__webpack_modules__[moduleId] === undefined) {
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!*************************************************!*\
  !*** ./resources/js/cookie-consent-filament.js ***!
  \*************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _util_cookies__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./util/cookies */ "./resources/js/util/cookies.js");

var onConsentChange;
window.cookieBannerConsentChange = function (callback) {
  onConsentChange = callback;
};
document.addEventListener('alpine:init', function () {
  Alpine.data('cookieConsentModal', function (props) {
    return {
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
      init: function init() {
        var _this = this;
        this.$nextTick(function () {
          _this.initialize();
        });
      },
      // Button listeners
      acceptAll: function acceptAll() {
        this.updateCookie(this.COOKIE_VALUE_BOTH);
        this.closeCookieConsentModal();
        this.closeCookieSettingsModal();
      },
      acceptEssentials: function acceptEssentials() {
        this.updateCookie(this.COOKIE_VALUE_NONE);
        this.closeCookieConsentModal();
        this.closeCookieSettingsModal();
      },
      openSettings: function openSettings() {
        this.openCookieSettingsModal();
      },
      saveSettings: function saveSettings() {
        var cookieValue;
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
      openCookieConsentModal: function openCookieConsentModal() {
        this.$dispatch('open-modal', {
          id: this.COOKIE_CONSENT_MODAL_ID
        });
      },
      closeCookieConsentModal: function closeCookieConsentModal() {
        this.$dispatch('close-modal', {
          id: this.COOKIE_CONSENT_MODAL_ID
        });
      },
      openCookieSettingsModal: function openCookieSettingsModal() {
        this.$dispatch('open-modal', {
          id: this.COOKIE_CONSENT_SETTINGS_MODAL_ID
        });
      },
      closeCookieSettingsModal: function closeCookieSettingsModal() {
        this.$dispatch('close-modal', {
          id: this.COOKIE_CONSENT_SETTINGS_MODAL_ID
        });
      },
      initialize: function initialize() {
        var ignoredPathsArray = this.IGNORED_PATHS ? this.IGNORED_PATHS.map(function (ignoredPath) {
          return ignoredPath.trim();
        }) : [];
        var isIgnoredPage = ignoredPathsArray.indexOf(location.pathname) > -1;
        var isRobot = /bot|google|baidu|bing|msn|duckduckbot|teoma|slurp|yandex/i.test(navigator.userAgent);
        if (isRobot) return;
        if (!(0,_util_cookies__WEBPACK_IMPORTED_MODULE_0__.getCookie)(this.COOKIE_KEY) && !isIgnoredPage) {
          this.openCookieConsentModal();
        }
        this.fillFormData();
      },
      fillFormData: function fillFormData() {
        var cookieValue = (0,_util_cookies__WEBPACK_IMPORTED_MODULE_0__.getCookie)(this.COOKIE_KEY);
        this.checkboxAnalytics = cookieValue === this.COOKIE_VALUE_BOTH || cookieValue === this.COOKIE_VALUE_ANALYTICS;
        this.checkboxMarketing = cookieValue === this.COOKIE_VALUE_BOTH || cookieValue === this.COOKIE_VALUE_MARKETING;
      },
      updateCookie: function updateCookie(cookieValue) {
        (0,_util_cookies__WEBPACK_IMPORTED_MODULE_0__.setCookie)(this.COOKIE_KEY, cookieValue, this.COOKIE_EXPIRATION_DAYS, this.SESSION_DOMAIN, this.COOKIE_SECURE);
        if (onConsentChange) {
          onConsentChange(cookieValue);
        }

        //  Fire GTM event if dataLayer is found
        if (window.dataLayer) {
          window.dataLayer.push({
            event: this.GTM_EVENT
          });
        }
      }
    };
  });
});
})();

/******/ })()
;