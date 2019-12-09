<?php
/**
 * Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ben_theme
 */

 if ( ! function_exists( 'theme_setup' ) ) :
 /**
  * Sets up theme defaults and registers support for various WordPress features.
  *
  * Note that this function is hooked into the after_setup_theme hook, which
  * runs before the init hook. The init hook is too late for some features, such
  * as indicating support for post thumbnails.
  */
 function theme_setup() { 
 	/*
 	 * Make theme available for translation.
 	 * Translations can be filed in the /languages/ directory.
 	 * If you're building a theme based on RRE Theme, use a find and replace
 	 * to change 'rre-theme' to the name of your theme in all the template files.
 	 */
 	load_theme_textdomain( 'ben-theme', get_template_directory() . '/languages' );

 	// Add default posts and comments RSS feed links to head.
 	add_theme_support( 'automatic-feed-links' );

 	/*
 	 * Let WordPress manage the document title.
 	 * By adding theme support, we declare that this theme does not use a
 	 * hard-coded <title> tag in the document head, and expect WordPress to
 	 * provide it for us.
 	 */
 	add_theme_support( 'title-tag' );

 	/*
 	 * Enable support for Post Thumbnails on posts and pages.
 	 *
 	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
 	 */
 	add_theme_support( 'post-thumbnails' );

  // Enable custom menus
  // =======================
  add_theme_support( 'menus' );

 	// This theme uses wp_nav_menu() in one location.
 	register_nav_menus( array(
 		'header-main-menu' => esc_html__( 'Main Menu', 'ben-theme' ),
 		'footer-menu' => esc_html__( 'Footer Menu', 'ben-theme' )
 	) );

 	/*
 	 * Switch default core markup for search form, comment form, and comments
 	 * to output valid HTML5.
 	 */
 	add_theme_support( 'html5', array(
 		'search-form',
 		'comment-form',
 		'comment-list',
 		'gallery',
 		'caption',
 	) );

 	// Add theme support for selective refresh for widgets.
 	add_theme_support( 'customize-selective-refresh-widgets' );
 }
 endif;
 add_action( 'after_setup_theme', 'theme_setup' );

 // Adds CSS
 // ============
 function theme_styles() {
   // wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
   wp_enqueue_style( 'mainCSS', get_template_directory_uri() . '/style.min.css' );
 }
 add_action( 'wp_enqueue_scripts', 'theme_styles');

 // Adds JS
 // ==========
 function theme_js() {
   wp_enqueue_script( 'slickJS', get_template_directory_uri() . '/js/vendor/slick.min.js', array('jquery'), '', true);
   //wp_enqueue_script( 'job-search-form', get_template_directory_uri() . '/js/vendor/job-search-form.js', array('jquery'), '', true);
   wp_enqueue_script( 'mainJS', get_template_directory_uri() . '/js/bundle.min.js', array('jquery'), '', true);

   $local_attr = array(
         'root_url' => get_template_directory_uri(),
         'map_key' => ''
     );

   $map_key = get_field( 'google_maps_api_key', 'option' );

   if(strlen($map_key) > 0) {
     $local_attr['map_key'] = $map_key;
   }

   wp_localize_script( 'mainJS', 'justice', $local_attr);
 }
 add_action( 'wp_enqueue_scripts', 'theme_js');


// Add style select button to the Wysiwyg editor
// ==========
function add_style_select_buttons( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
// Register our callback to the appropriate filter
add_filter( 'mce_buttons_2', 'add_style_select_buttons' );



//Add custom styles to the Wysiwyg editor
function my_custom_styles( $init_array ) {

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
    $init_array['style_formats'] = json_encode( $style_formats );

    return $init_array;

}
// Attach callback to 'tiny_mce_before_init'
add_filter( 'tiny_mce_before_init', 'my_custom_styles' );

// ************ Cron Jobs for Jobs Feed ****************************************
// Create custom cron interval
add_filter( 'cron_schedules', 'example_add_cron_interval' );

function example_add_cron_interval( $schedules ) {
    $schedules['two_hours'] = array(
        'interval' => 7200,
        'display'  => esc_html__( 'Every Two Hours' ),
    );

    return $schedules;
}

// Add Options page
if( function_exists('acf_add_options_page') ) {

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
require get_template_directory() . '/inc/extras.php';

/**
* Load Custom Posts file
*/
require get_template_directory() . '/inc/custom-posts.php';

/**
* Load Jobs Handler file
*/
require get_template_directory() . '/inc/jobs-handler/save-jobs-xmlfile.php';
require get_template_directory() . '/inc/jobs-handler/add-edit-jobs.php';
require get_template_directory() . '/inc/jobs-handler/job-search.php';

/**
* Load Custom Taxonomies file
*/
require get_template_directory() . '/inc/custom-taxonomies.php';

/* TEST IMPORT. TO REMOVE ON LAUNCH */
add_action( 'wp', 'test_import');
function test_import() {

    if ( is_user_logged_in() && current_user_can('administrator') ) {

        $import_test = get_query_var('importScriptTest');
         if($import_test == 'pull-jobs'){
             saveJobsXMLFile();
             die();
         }
         else if($import_test == 'import-jobs'){
             addJobPost();
             die();
         }
    }
}

function rj_add_query_vars_filter( $vars ){
    $vars[] = "importScriptTest";
    return $vars;
}
add_filter( 'query_vars', 'rj_add_query_vars_filter' );