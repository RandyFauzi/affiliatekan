(function (window, document) {
    'use strict';

    var TRACKING_COOKIE_NAME = 'affiliatekan_ref';
    var TRACKING_QUERY_PARAMETER = 'ref';
    var TRACKING_COOKIE_EXPIRY_DAYS = 30;
    var DEFAULT_API_ENDPOINT = 'https://api.affiliatekan.com/v1/conversions';
    var INITIAL_SCRIPT_ELEMENT = document.currentScript;

    function resolveTrackerConfiguration() {
        var globalConfiguration = window.AffiliatekanTrackerConfig || {};
        var parsedCookieDuration = parseInt(globalConfiguration.cookieDuration || (INITIAL_SCRIPT_ELEMENT && INITIAL_SCRIPT_ELEMENT.getAttribute('data-cookie-duration')), 10);

        return {
            apiEndpoint: globalConfiguration.apiEndpoint || (INITIAL_SCRIPT_ELEMENT && INITIAL_SCRIPT_ELEMENT.getAttribute('data-api-endpoint')) || DEFAULT_API_ENDPOINT,
            apiKey: globalConfiguration.apiKey || (INITIAL_SCRIPT_ELEMENT && INITIAL_SCRIPT_ELEMENT.getAttribute('data-api-key')) || '',
            cookieDuration: isNaN(parsedCookieDuration) ? TRACKING_COOKIE_EXPIRY_DAYS : parsedCookieDuration,
        };
    }

    function readQueryParameter(parameterName) {
        try {
            var currentUrl = new URL(window.location.href);

            return currentUrl.searchParams.get(parameterName);
        } catch (error) {
            return null;
        }
    }

    function writeTrackingCookie(cookieValue, expiryDays) {
        if (!cookieValue) {
            return;
        }

        var expiryDate = new Date();
        expiryDate.setTime(expiryDate.getTime() + (expiryDays * 24 * 60 * 60 * 1000));
        var cookieSegments = [
            TRACKING_COOKIE_NAME + '=' + encodeURIComponent(cookieValue),
            'expires=' + expiryDate.toUTCString(),
            'path=/'
        ];

        if (window.location.protocol === 'https:') {
            cookieSegments.push('SameSite=None');
            cookieSegments.push('Secure');
        }

        document.cookie = cookieSegments.join('; ');
    }

    function readTrackingCookie() {
        var cookiePrefix = TRACKING_COOKIE_NAME + '=';
        var availableCookies = document.cookie ? document.cookie.split(';') : [];

        for (var cookieIndex = 0; cookieIndex < availableCookies.length; cookieIndex += 1) {
            var normalizedCookie = availableCookies[cookieIndex].trim();

            if (normalizedCookie.indexOf(cookiePrefix) === 0) {
                return decodeURIComponent(normalizedCookie.substring(cookiePrefix.length));
            }
        }

        return null;
    }

    function resolveClickEndpoint(conversionEndpoint) {
        if (!conversionEndpoint) {
            return 'https://api.affiliatekan.com/v1/clicks';
        }

        return conversionEndpoint.replace(/\/conversions(?:\/)?$/, '/clicks');
    }

    function sendClickEvent(affiliateCode, trackerConfiguration) {
        if (!affiliateCode) {
            return;
        }

        fetch(resolveClickEndpoint(trackerConfiguration.apiEndpoint), {
            method: 'POST',
            body: new URLSearchParams({
                affiliate_code: affiliateCode,
            }),
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data && data.success && typeof data.cookie_duration_days === 'number') {
                writeTrackingCookie(affiliateCode, data.cookie_duration_days);
            }
        })
        .catch(function (error) {
            console.error('Affiliatekan click tracking failed.', error);
        });
    }

    function captureClick() {
        var trackerConfiguration = resolveTrackerConfiguration();
        var affiliateReferenceCode = readQueryParameter(TRACKING_QUERY_PARAMETER);

        if (!affiliateReferenceCode) {
            return null;
        }

        writeTrackingCookie(affiliateReferenceCode, trackerConfiguration.cookieDuration);
        sendClickEvent(affiliateReferenceCode, trackerConfiguration);

        return affiliateReferenceCode;
    }

    function trackConversion(orderId, amount) {
        var trackerConfiguration = resolveTrackerConfiguration();
        var storedAffiliateCode = readTrackingCookie();

        if (!storedAffiliateCode || !trackerConfiguration.apiKey) {
            return Promise.resolve(false);
        }

        return fetch(trackerConfiguration.apiEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-API-KEY': trackerConfiguration.apiKey,
            },
            body: JSON.stringify({
                affiliate_code: storedAffiliateCode,
                vendor_order_id: String(orderId),
                sale_amount: amount,
            }),
        }).catch(function (error) {
            console.error('Affiliatekan conversion tracking failed.', error);

            return false;
        });
    }

    captureClick();

    window.AffiliatekanTracker = {
        captureClick: captureClick,
        trackConversion: trackConversion,
    };
})(window, document);
