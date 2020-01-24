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
    define('MOJ_ENQUEUE_PATH', get_template_directory_uri() . '/dist');

    // CSS
    wp_enqueue_style('core-css', mix_asset('/css/main.min.css'), array(), null, 'all');

    // JS and jQuery
    wp_enqueue_script('slick-js', mix_asset('/js/slick.min.js'), array('jquery', 'core-js'), null, true);
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
    $manifest_path = MOJ_ENQUEUE_PATH . '/mix-manifest.json';
    $manifest = json_decode(file_get_contents($manifest_path), true);
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
add_filter('cron_schedules', 'example_add_cron_interval');

function example_add_cron_interval($schedules)
{
    $schedules['two_hours'] = array(
        'interval' => 7200,
        'display' => esc_html__('Every Two Hours'),
    );

    return $schedules;
}

// Add Options page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page();
}

/*
// Create cron hook and schedule event
add_action( 'savexml_cron_hook', 'saveJobsXMLFile' );

if ( ! wp_next_scheduled( 'savexml_cron_hook' ) ) {
    wp_schedule_event( 1556031600, 'two_hours', 'savexml_cron_hook' );
}


add_action( 'updatejobs_cron_hook', 'addJobPost' );

if ( ! wp_next_scheduled( 'updatejobs_cron_hook' ) ) {
    wp_schedule_event( 1556035200, 'two_hours', 'updatejobs_cron_hook' );
}
*/


// Function to be executed when the time for the schedule event arrives
// function bl_cron_exec() {
//   // Use wp_remote_get to fetch the data
//   // $url = "https://justicejobs.tal.net/vx/mobile-0/appcentre-1/brand-2/candidate/jobboard/vacancy/3/feed/structured";
//   $url = "https://justicejobs.tal.net/vx/mobile-0/appcentre-1/brand-2/candidate/jobboard/vacancy/3/feed";
//   // $response = wp_remote_get($url);
//
//   // $ch = curl_init();
//   // $timeout = 10;
//   // curl_setopt($ch, CURLOPT_URL, $url);
//   // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//   // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//   // $data = curl_exec($ch);
//   // curl_close($ch);
//
//   // Create the name of the file and the declare the directory and path
//   $file = "wp-content/uploads/job-feed/jobs_big.xml";
//   // $data = $response['body'];
//
//   $fp = fopen($file, "w");
//   // fwrite($fp, $data);
//   // fclose($fp);
//
//   $ch = curl_init();
//   curl_setopt($ch, CURLOPT_URL, $url);
//   // curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progressCallback');
//   // curl_setopt($ch, CURLOPT_BUFFERSIZE, (1024*1024*512));
//   // curl_setopt($ch, CURLOPT_NOPROGRESS, FALSE);
//   curl_setopt($ch, CURLOPT_FAILONERROR, 1);
//   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//   curl_setopt($ch, CURLOPT_TIMEOUT, 60);
//   // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
//   curl_setopt($ch, CURLOPT_FILE, $fp);
//
//   curl_exec($ch);
//   curl_close($ch);
//   fclose($fp);
//
//
// }

// Remove default styling from MCE tables
// function tinymce_fix_table_styles() {
//   echo '<script>jQuery(function($){
//     if (typeof tinymce !== "undefined") {
//       tinymce.overrideDefaults({
//         table_default_attributes:{},
//         table_default_styles:{}
//       });
//     }
//   });</script>';
// }
// add_action('admin_footer', 'tinymce_fix_table_styles');

// function mce_settings( $settings ) {
//     $settings['table_default_styles'] = false;
//     $settings['table_default_attributes'] = false;
//     return $settings;
// }
// add_filter( 'tiny_mce_before_init', 'mce_settings' );


// Implement Additional files
//==========
//
/**
 * Customizer additions.
 */
// require get_template_directory() . '/inc/customizer.php';
//
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
 * Load Jobs Handler file
 */
require 'inc/jobs-handler/save-jobs-xmlfile.php';
require 'inc/jobs-handler/add-edit-jobs.php';
require 'inc/jobs-handler/job-search.php';

/**
 * Load Custom Taxonomies file
 */
require 'inc/custom-taxonomies.php';

/* TEST IMPORT. TO REMOVE ON LAUNCH */
add_action('wp', 'test_import');
function test_import()
{
    if (is_user_logged_in() && current_user_can('administrator')) {
        $import_test = get_query_var('importScriptTest');
        if ($import_test == 'pull-jobs') {
            saveJobsXMLFile();
            die();
        } else {
            if ($import_test == 'import-jobs') {
                import_jobs_from_xml();
                die();
            }
            if ($import_test == 'delete-jobs') {
                deleteJobs();
                die();
            }
        }
    }
}

if (!function_exists('deleteJobs')) {

    function deleteJobs()
    {
        $allposts = get_posts(array('post_type' => 'job','numberposts' => -1));
        foreach ($allposts as $eachpost) {
            wp_delete_post($eachpost->ID, true);
        }

        echo "Jobs Deleted";
        die();
    }
}

function rj_add_query_vars_filter($vars)
{
    $vars[] = "importScriptTest";
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
        $pre_options .= '<option value="" disabled selected>' . ucwords($default) . '</option>';
        $selectedFound = '';
    }

    $pre_options .= '<option value="all">All ' . $default . 's</option>';

    return ['list' => $pre_options . $role_options, 'title' => ' title="' . $selectedFound . '"'];
}
