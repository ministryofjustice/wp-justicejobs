<?php
/*
Plugin Name: MoJ Switch Locale
Plugin URI: https://www.justice.gov.uk/
Description: Sets the locale from a post querystring value
Version: 1.0
Author: Damien Wilson
Author URI: https://www.justice.gov.uk/
Text Domain: brookhouse
*/


$moj_locale_storage = [
    'type' => '',
    'engine' => [],
    'set' => false
];


/**
 * Returns the locale found in our storage.
 *
 * First we check to see if we have a stored locale in the DB to use and, that the querystring
 * value passed (if any) is the same as the stored value. If so we return the stored value.
 *
 * If the stored locale was null or different to the new locale, we check if the new locale matches
 * any of the languages stored in our language directory. If we have a match the locale is returned.
 * Otherwise, we return the default WordPress locale or en_GB if for any reason the default was null.
 *
 * @param string $locale The default locale code used by WordPress
 * @return string A language code in the form a string
 */
function moj_switch_locale($locale)
{
    global $moj_locale_storage;

    if (in_array(
        $moj_locale_storage['engine']['moj_locale'],
        get_available_languages(get_template_directory() . '/languages')
    )) {
        return $moj_locale_storage['engine']['moj_locale'];
    }

    return $locale ?? 'en_GB';
}

function moj_switch_locale_start_storage()
{
    global $moj_locale_storage;

    if (isset($_COOKIE)) {
        $moj_locale_storage['type'] = 'cookie';
        $moj_locale_storage['engine'] = $_COOKIE;
    }

    moj_switch_get_locale();
}

function moj_switch_get_locale()
{
    global $moj_locale_storage;
    $get_global = $_GET;

    // is it already set?
    if (!isset($get_global['locale']) && isset($moj_locale_storage['engine']['moj_locale'])) {
        return true;
    }

    $locale = (
        isset($get_global['locale'])
            ? $get_global['locale']
            : $moj_locale_storage['engine']['moj_locale'] ?? 'en_GB'
    );

    $moj_locale_storage['engine']['moj_locale'] = $locale;

    setcookie(
        'moj_locale',
        $locale,
        time() + 7200,
        '/',
        COOKIE_DOMAIN
    );

    return true;
}

/**
 * Set the default locale
 */
add_action('registered_post_type', 'moj_switch_locale_start_storage', 99);

/**
 * Return the default locale
 */
add_filter('locale', 'moj_switch_locale', 1);
