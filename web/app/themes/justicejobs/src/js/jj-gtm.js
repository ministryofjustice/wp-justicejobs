/**
 * The data layer management script for GTM
 */
(function (w, d, s, l, i) {
    w[l] = w[l] || [];
    w[l].push({
        'gtm.start':
            new Date().getTime(), event: 'gtm.js'
    });
    var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s), dl = l != 'jjg' ? '&l=' + l : '';
    j.async = true;
    j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
    f.parentNode.insertBefore(j, f);
})(window, document, 'script', 'jjg', 'GTM-PWKHW94');

function trackEvent(event, object) {
    if (!object) {
        if (console && console.warn()) {
            console.warn('Custom function trackEvent() requires an object of name-value pairs: ')
        }
    }

    object = {};

    jjg.push({
        'event': event,
        object
    });
}

jQuery(function($) {
    trackEvent('clicked');
});
