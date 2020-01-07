/**
 * The data layer management script for GTM
 */

mojGtm = [{}];

(function (w, d, s, l, i) {
    w[l] = w[l] || [];
    w[l].push({
        'gtm.start':
            new Date().getTime(), event: 'gtm.js'
    });
    var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
    j.async = true;
    j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
    f.parentNode.insertBefore(j, f);
})(window, document, 'script', 'mojGtm', 'GTM-PWKHW94');


/**
 * This function wraps around the dataLayer.push function
 * @param event
 * @param object
 * @private
 */
function _trackEvent(event, object) {
    if (!object) {
        var message = 'MoJ: Custom function _trackEvent() should include an object of variable name-value pairs';
        if (!console && !console.warn) {
            alert(message);
        } else {
            console.warn(message);
        }
        mojGtm.push({'event': event});
        return;
    }

    mojGtm.push($.extend({}, {'event': event}, object));
}

function _jq_target(event){
    return $(event.target);
}

jQuery(function ($) {
    // when a top menu item gets clicked
    $('.jj-nav-primary').on('click', function () {
        _trackEvent('top-menu-click', {'topMenuItemLabel': $(this).text()});
    });

    // when a footer link is clicked
    $('.jj-nav-footer').on('click', function () {
        _trackEvent('footer-menu-click', {'footerMenuItemLabel': $(this).text()});
    });

    // when a search page link is clicked
    $('.search-page-link').on('click', function (event) {
        var tag = _jq_target(event),
            label = $(this).text(),
            keyword = $('#keyword').val();

        switch (true) {
            case tag.is('input'):
                label = tag.val();
                    break;
            case tag.hasClass('ga-nav-primary'):
                label = 'Primary nav - ' + label;
                break;
            case tag.hasClass('ga-mini-home-form-button'):
                label = 'Mini form homepage - ' + label;
                break;
            case tag.hasClass('ga-main-form-button'):
                label = 'Main form search - ' + label;
                break;
            case tag.hasClass('ga-nav-top-right'):
                label = 'Header right - ' + label;
                break;
        }

        label = label.replace(' & ', ' and ');

        //alert(tag.hasClass('ga-main-form-button') ? label : 'No class found on that tag!');

        _trackEvent('search-page-links', {'searchPageLabel': label, 'searchFormKeyword': keyword});
    });

    // when an about block is clicked
    $('.about__block').on('click', function(){
        var label = $(this).find('h3').text();

        if (!label) {
            label = null; // catch null in GTM
        }

        _trackEvent('about-block-click', {'aboutBlockLabel': label});
    });
});
