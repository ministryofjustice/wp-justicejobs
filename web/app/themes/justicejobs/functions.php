<?php
/**
 * Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ben_theme
 */

if (!function_exists('theme_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails..
     */
    function theme_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on RRE Theme, use a find and replace
         * to change 'rre-theme' to the name of your theme in all the template files.
         */
        load_theme_textdomain('ben-theme', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

// Enable custom menus
// =======================
        add_theme_support('menus');

// This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'header-main-menu' => esc_html__('Main Menu', 'ben-theme'),
            'footer-menu' => esc_html__('Footer Menu', 'ben-theme')
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

// Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');
    }
endif;
add_action('after_setup_theme', 'theme_setup');

// Adds CSS
// Adds JS
// ==========
function enqueue_justice_jobs_scripts()
{
    global $manifest;
    define('MOJ_ENQUEUE_PATH', get_template_directory_uri() . '/dist');
    $manifest = json_decode(
        file_get_contents('dist/mix-manifest.json', true),
        true
    );

    // CSS
    wp_enqueue_style('core-css', mix_asset('/css/main.min.css'), array(), null, 'all');

    // JS and jQuery
    wp_enqueue_script('core-js', mix_asset('/js/main.min.js'), array('jquery'), null, true);

    // Temporary workaround to comply with GDPR - tracking off by default
    if (isset($_COOKIE['ccfwCookiePolicy'])) {
        $cookieAccepted = htmlspecialchars($_COOKIE['ccfwCookiePolicy']);

        if ($cookieAccepted === 'true') {
            wp_enqueue_script('jj-gtm', mix_asset('/js/jj-gtm.min.js'), array('jquery'));
        }
    }

    // Third party vendor scripts
    wp_deregister_script('jquery'); // This removes jquery shipped with WP so that we can add our own.
    wp_enqueue_script('jquery', mix_asset('/js/jquery.min.js'));

    $local_attr = [
        'root_url' => get_template_directory_uri(),
        'ajaxurl' => admin_url('admin-ajax.php')
    ];

    wp_localize_script('core-js', 'justice', $local_attr);
}

add_action('wp_enqueue_scripts', 'enqueue_justice_jobs_scripts', 99);


/**
 * Add the class attribute with a value to anchor links generated by nav-menus
 *
 * @param $atts
 * @param $item
 * @param $args
 * @return array
 */
function add_specific_menu_location_atts($atts, $item, $args)
{
    // check if the item is in the header menu
    if ($args->theme_location == 'header-main-menu') {
        /*echo '<pre style="font-size: 0.8em">' . print_r($args, true) . '</pre>';
        echo '<pre style="font-size: 0.8em">' . print_r($item, true) . '</pre>';*/

        // add desired attributes:
        $atts['class'] = 'jj-nav-primary';

        // detect search page link by class in menu and drop classes
        if (in_array('search-page-link', $item->classes)) {
            $atts['class'] = 'search-page-link ga-nav-primary';
        }
    }

    // check if the item is in the footer menu
    if ($args->theme_location == 'footer-menu') {
        // add desired attributes:
        $atts['class'] = 'jj-nav-footer';
    }
    return $atts;
}

add_filter('nav_menu_link_attributes', 'add_specific_menu_location_atts', 10, 3);

/**
 * @param $filename
 * @return string
 */
function mix_asset($filename)
{
    global $manifest;
    if (!isset($manifest[$filename])) {
        error_log("Mix asset '$filename' does not exist in manifest.");
    }
    return MOJ_ENQUEUE_PATH . $manifest[$filename];
}


// Add style select button to the Wysiwyg editor
// ==========
function add_style_select_buttons($buttons)
{
    array_unshift($buttons, 'styleselect');
    return $buttons;
}

// Register our callback to the appropriate filter
add_filter('mce_buttons_2', 'add_style_select_buttons');


//Add custom styles to the Wysiwyg editor
function moj_custom_styles($init_array)
{

    $style_formats = array(
        // These are the custom styles
        array(
            'title' => 'Body Heading',
            'block' => 'p',
            'classes' => 'heading--body',
            'wrapper' => false,
        ),
    );
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode($style_formats);

    return $init_array;
}

// Attach callback to 'tiny_mce_before_init'
add_filter('tiny_mce_before_init', 'moj_custom_styles');

// ************ Cron Jobs for Jobs Feed ****************************************
// Create custom cron interval
add_filter('cron_schedules', 'jj_add_cron_interval');
function jj_add_cron_interval($schedules)
{
    $schedules['six_hours'] = [
        'interval' => 21600,
        'display' => esc_html__('Every Six Hours')
    ];

    $schedules['thirty_minutes'] = [
        'interval' => 1800,
        'display' => esc_html__('Every Thirty Minutes')
    ];

    $schedules['fifteen_minutes'] = [
        'interval' => 900,
        'display' => esc_html__('Every Fifteen Minutes')
    ];

    $schedules['five_minutes'] = [
        'interval' => 300,
        'display' => esc_html__('Every Five Minutes')
    ];

    $schedules['three_minutes'] = [
        'interval' => 180,
        'display' => esc_html__('Every Three Minutes')
    ];

    return $schedules;
}

// Add Options page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page();
}

/**
 * Load Jobs Handler file
 */
require 'inc/jobs-handler/save-jobs-xmlfile.php';
require 'inc/jobs-handler/add-edit-jobs.php';
require 'inc/jobs-handler/job-search.php';

// Create cron hook and schedule event
add_action('save_xml_cron_hook', 'save_jobs_xml_file');
if (!wp_next_scheduled('save_xml_cron_hook')) {
    wp_schedule_event(time(), 'hourly', 'save_xml_cron_hook');
}

add_action('update_jobs_cron_hook', 'import_jobs_from_xml');
if (!wp_next_scheduled('update_jobs_cron_hook')) {
    wp_schedule_event(time() + 900, 'hourly', 'update_jobs_cron_hook');
}

/**
 * Custom functions that act independently of the theme templates.
 */
require 'inc/extras.php';
require 'inc/maps-endpoint.php';

/**
 * Load Custom Posts file
 */
require 'inc/custom-posts.php';

/**
 * Load Custom Taxonomies file
 */
require 'inc/custom-taxonomies.php';
require 'inc/job-location-taxonomy-meta.php';

/**
 * Manually invoke a jobs import process. Available options are:
 * - Refresh feed and DB
 * - Pull from server
 * - Pull with force
 * - Import stored data
 * - Delete stored data
 * Requires the logged in capability of administrator.
 * When a singular process completes the function will either die() on completion or redirect to the home page
 * If a user who is not logged in attempts to fire a process, they are redirected to the home page
 *
 * @uses is_user_logged_in()
 * @uses current_user_can()
 * @example https://justicejobs.dev.wp.dsd.io/?import-override=refresh-feed
 */
function jobs_import_override()
{
    $query = get_query_var('jobs_process');
    if (is_user_logged_in() && current_user_can('administrator')) {
        switch ($query) {
            case 'pull-jobs':
                save_jobs_xml_file();
                jobs_import_override_complete();
                break;
            case 'import-jobs':
                import_jobs_from_xml();
                jobs_import_override_complete();
                break;
            case 'delete-jobs':
                deleteJobs();
                jobs_import_override_complete();
                break;
            case 'pull-jobs-force':
                save_jobs_xml_file(true);
                jobs_import_override_complete();
                break;
            case 'refresh-feed':
                save_jobs_xml_file(true);
                import_jobs_from_xml();
                jobs_import_override_complete();
                break;
        }
    }

    if (!empty($query)) {
        jobs_import_override_complete(true);
    }
}

add_action('wp', 'jobs_import_override', 1);

/**
 * When a manual import override process has completed
 * Handles white-page script output correctly in production
 * @param $redirect bool
 */
function jobs_import_override_complete($redirect = false)
{
    if (WP_ENV === 'production' || $redirect === true) {
        if (wp_redirect('/', 301)) {
            exit;
        }
    }
    die();
}

if (!function_exists('deleteJobs')) {
    function deleteJobs()
    {
        $all_posts = get_posts(array('post_type' => 'job', 'numberposts' => -1));

        foreach ($all_posts as $eachpost) {
            wp_delete_post($eachpost->ID, true);
        }
    }
}

function rj_add_query_vars_filter($vars)
{
    $vars[] = "jobs_process";
    return $vars;
}

add_filter('query_vars', 'rj_add_query_vars_filter');

/**
 * clean some stuff from the head...
 */
remove_action('wp_head', 'rsd_link'); // blog editing link
remove_action('wp_head', 'wlwmanifest_link'); // windows blog editing link
remove_action('wp_head', 'wp_generator'); // remove version declaration


/**
 * Used in search filter scripts
 *
 * @param $name
 * @param $compare
 * @return string
 */
function select_if_match($name, $compare)
{
    if ($name === $compare) {
        return ' selected="selected"';
    }
    return '';
}

function jj_select_options($qs_param, $default = 'option')
{
    global $terms;

    $role_options = $pre_options = $selected = '';
    $selectedFound = false;
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $selected = select_if_match($term->slug, $qs_param);
            if ($selected !== '') {
                $selectedFound = $term->name;
            }
            $role_options .= '<option value="' . $term->slug . '"' . $selected . '>' . $term->name . '</option>';
        }
    }

    if (!$selectedFound) {
        $pre_options .= '<option value="" disabled selected>' . ucfirst($default) . '</option>';
        $selectedFound = '';
    }

    $pre_options .= '<option value="all">All ' . $default . 's</option>';

    return ['list' => $pre_options . $role_options, 'title' => ' title="' . $selectedFound . '"'];

}

if (!function_exists('moj_at_glance_cpt_display')) {
    function moj_at_glance_cpt_display()
    {
        $args = array(
            'public' => true,
            '_builtin' => false
        );
        $output = 'object';
        $operator = 'and';

        $post_types = get_post_types($args, $output, $operator);
        foreach ($post_types as $post_type) {
            $num_posts = wp_count_posts($post_type->name);
            $num = number_format_i18n($num_posts->publish);
            $text = _n($post_type->labels->singular_name, $post_type->labels->name, intval($num_posts->publish));
            if (current_user_can('edit_posts')) {
                $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . ' ' . $text . '</a>';
                echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
            }
        }
    }
}

add_action('dashboard_glance_items', 'moj_at_glance_cpt_display');

/**
 *
 */
function jobs_cron_page_create()
{
    $page_title = 'Jobs CRON Settings';
    $menu_title = 'Jobs CRON';
    $capability = 'manage_options';
    $menu_slug = 'jobs-cron-switch';
    $function = 'jobs_cron_page_display';
    $icon_url = '';
    $position = 24;

    add_options_page($page_title, $menu_title, $capability, $menu_slug, $function, $position);
}

add_action('admin_menu', 'jobs_cron_page_create');

function jobs_cron_page_display()
{
    if (!current_user_can('manage_options')) {
        wp_die('Sorry, you cannot access this page.');
    }

    $send_mail = false;
    $subject = "[Justice Jobs] Job CRON: ";
    $message = "Simple notification:\n\n";
    if (isset($_POST['jobs_cron_switch_input'])) {
        if (update_option('jobs-cron-switch-input', $_POST['jobs_cron_switch_input'])) {
            $send_mail = true;
            $subject .= 'STARTED';
            $message .= "The Jobs CRON has been restarted.";
        }
    } else {
        if (isset($_POST['jobs_cron_checker'])) {
            if (update_option('jobs-cron-switch-input', '0')) {
                $send_mail = true;
                $subject .= 'DEACTIVATED';
                $message .= "A request has been authorised to stop the Job CRON operation.\n\n" . admin_url('options-general.php?page=jobs-cron-switch');
            }
        }
    }

    if ($send_mail === true) {
        wp_mail(get_option('admin_email'), $subject, $message);
    }

    include('inc/job-cron-options-page.php');
}

function jobs_cron_display_notice($state)
{
    $message = 'The Jobs CRON is currently operational.';
    $class = 'success';

    if ($state !== '1') {
        $message = 'The current setting is preventing new jobs from being imported.<br>... please consider activating the CRON below.';
        $class = 'error';
    }

    return '<div class="notice notice-' . $class . '" style="display:inline-block;margin:10px 0 28px">
                <p><strong>' . $message . '</strong></p>
            </div>';
}

/**
 * There are only three variables needed to operate this function
 * Email, Subject and Message.
 * The email address is the first argument
 * The email subject is passed by the first pocket in the args array, message is the second.
 * @param $email string
 * @param $args array
 * @return true on success | false on failure | null if args are not set
 * @uses wp_mail()
 */
function jj_simple_mail($email, $args)
{
    if (!isset($args[0]) || !isset($args[1])) {
        return null;
    }
    return wp_mail($email, $args[0], $args[1]);
}

/**
 * A data holder for jobs CRON functions
 * Numbers represent hours in a 24 hour clock
 * @return array
 */
function jj_scheduled_hours(): array
{
    return [
        6,  // 6am
        12, // 12pm
        18  // 6pm
    ];
}

/**
 * Returns true when the current hour is listed in jj_scheduled_hours()
 *
 * requires the scheduled time is every 30 minutes
 *
 * @param $hour int
 * @return bool
 * @uses jj_scheduled_hours()
 */
function inside_schedule_window($hour = null)
{
    $time = $hour ?? (int)date("H");
    $windows = jj_scheduled_hours();
    if (in_array($time, $windows)) {
        return true;
    }

    return false;
}

/**
 * Determine if time is more than 2 hours from a schedule point but no greater than the next scheduled time
 *
 * @return bool
 * @uses jj_scheduled_hours()
 */
function is_2_hours_past_window()
{
    $windows = jj_scheduled_hours();

    // add 2 hours to time
    $time = (int)date("H") + 2;
    foreach ($windows as $key => $window) {
        // store next hour unless the last hour in the array
        $mod_key = ($key === count($windows) - 1 ? 0 : $key + 1);
        if ($time >= $window && $time < $windows[$mod_key]) {
            return true;
        }
    }
    return false;
}


/**
 * Remove post types from
 */
function remove_from_admin_menu()
{
    // Check capability (admin)
    if (current_user_can('manage_options')) {
        return null;
    }

    remove_menu_page('edit.php'); // add here your cpt name
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'remove_from_admin_menu');