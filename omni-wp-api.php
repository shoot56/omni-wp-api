<?php
/**
 * Plugin Name: Omni WP API
 * Description: Customizable REST API endpoints with API Key authentication.
 * Version: 1.0.3
 * Author: Procoders
 * GitHub Plugin URI: shoot56/omni-wp-api
 * Primary Branch: main
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define('ENV_URL', 'https://dev-api.omnimind.ai');
//define('ENV_URL', 'https://app-api.omnimind.ai');


require_once plugin_dir_path( __FILE__ ) . 'inc/debugger.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/class-api-keys.php';
// require_once plugin_dir_path( __FILE__ ) . 'inc/search.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/admin-menu.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/admin-page-functions.php';

function omni_wp_api_enqueue_styles() {
    wp_register_style('omni-wp-api-style', plugins_url('assets/css/omni-wp-api.css', __FILE__));
    wp_enqueue_style('select2', plugins_url('vendor/select2/select2.min.css', __FILE__));
    wp_enqueue_style('omni-wp-api-style');
}
add_action('admin_enqueue_scripts', 'omni_wp_api_enqueue_styles');

function omni_wp_api_enqueue_scripts() {
    wp_register_script('omni-wp-api-script', plugins_url('assets/js/omni-wp-api.js', __FILE__), array('jquery'), null, true);
    wp_enqueue_script('select2', plugins_url('vendor/select2/select2.min.js', __FILE__), array('jquery'), null, true);
    wp_enqueue_script('omni-wp-api-script');
}
add_action('admin_enqueue_scripts', 'omni_wp_api_enqueue_scripts');


