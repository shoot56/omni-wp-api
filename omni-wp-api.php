<?php
/**
 * Plugin Name: Omni WP API
 * Description: Customizable REST API endpoints with API Key authentication.
 * Version: 1.0.5
 * Author: Procoders
 * GitHub Plugin URI: shoot56/omni-wp-api
 * Primary Branch: main
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('ENV_URL', 'https://dev-api.omnimind.ai');
//define('ENV_URL', 'https://app-api.omnimind.ai');

// Temporary id set
define('WIDGET_TYPE_ID', 12);

require_once plugin_dir_path(__FILE__) . 'inc/debugger.php';
require_once plugin_dir_path(__FILE__) . 'inc/class-api-keys.php';
require_once plugin_dir_path(__FILE__) . 'inc/search.php';
require_once plugin_dir_path(__FILE__) . 'admin/admin-menu.php';
require_once plugin_dir_path(__FILE__) . 'admin/admin-page-functions.php';

function omni_wp_api_enqueue_styles()
{
    wp_register_style('omni-wp-api-style', plugins_url('assets/css/omni-wp-api.css', __FILE__));
    wp_enqueue_style('select2', plugins_url('vendor/select2/select2.min.css', __FILE__));
    wp_enqueue_style('omni-wp-api-style');
}

add_action('admin_enqueue_scripts', 'omni_wp_api_enqueue_styles');

function omni_wp_api_enqueue_scripts()
{
    wp_register_script('omni-wp-api-script', plugins_url('assets/js/omni-wp-api.js', __FILE__), array('jquery'), null, true);
    wp_enqueue_script('select2', plugins_url('vendor/select2/select2.min.js', __FILE__), array('jquery'), null, true);
    wp_enqueue_script('omni-wp-api-script');
}

add_action('admin_enqueue_scripts', 'omni_wp_api_enqueue_scripts');

function omni_frontend_scripts(): void
{
    wp_register_script('omni-wp-search-script', plugins_url('assets/js/omni-wp-search.js', __FILE__), array(), null, true);
    wp_enqueue_script('omni-wp-search-script');
    wp_localize_script('omni-wp-search-script', 'omni_ajax',
        array(
            'url' => admin_url('admin-ajax.php'),
            'query_nonce' => wp_create_nonce('omni_search_handle_query'),
        )
    );
}
add_action('wp_enqueue_scripts', 'omni_frontend_scripts', 99);

function omniwp_search_enqueue_styles()
{
    wp_enqueue_style('omni-wp-search-style', plugins_url('assets/css/omni-wp-search.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'omniwp_search_enqueue_styles');

// Register ajax cals for search.
add_action('wp_ajax_nopriv_omni_search_handle_query', 'omni_search_handle_query');
add_action('wp_ajax_omni_search_handle_query', 'omni_search_handle_query');

// Add shortcode and search query handler to WordPress hooks
add_shortcode('omni_search', 'omni_search_shortcode');

