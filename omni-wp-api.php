<?php
/**
 * Plugin Name: Omni WP API
 * Description: Customizable REST API endpoints with API Key authentication.
 * Version: 1.0.6
 * Author: Procoders
 * Requires PHP: 8.0
 * Text Domain: omni
 * Domain Path: /languages
 * GitHub Plugin URI: shoot56/omni-wp-api
 * Primary Branch: main
 */

namespace Procoders\Omni;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

define('ENV_URL', 'https://dev-api.omnimind.ai');
//define('ENV_URL', 'https://app-api.omnimind.ai');

// Temporary id set
define('WIDGET_TYPE_ID', 12);
define('OMNI_FILE', __FILE__);
define('OMNI_PLUGIN_DIR', plugin_dir_path(__FILE__));

use Procoders\Omni\Admin\{ClassAdmin as AdminInit, ClassAssets as AdminAssets, ClassNav as AdminNav};
use Procoders\Omni\Public\{ClassAssets as PublicAssets, ClassPublic as PublicInit};

class Omni
{
    private static ?omni $instance = null;

    public static function get_instance(): Omni
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Class initializer.
     */
    public function plugins_loaded(): void
    {
        load_plugin_textdomain(
            'omni',
            false,
            basename(__FILE__) . '/languages'
        );

        // Register the admin menu.
        AdminNav::run();
        // Register Script.
        PublicAssets::run();
        AdminAssets::run();

        $public_init = new PublicInit();
        $admin_init = new AdminInit();

        // Register ajax cals for search.
        add_action('wp_ajax_nopriv_omni_search_handle_query', array($public_init, 'omni_search_handle_query'));
        add_action('wp_ajax_omni_search_handle_query', array($public_init, 'omni_search_handle_query'));

        add_action('admin_init', array($admin_init, 'add_omni_columns_to_post_types'));
        add_action('admin_init', array($admin_init, 'add_quick_and_bulk_edit_to_post_types'));
        add_action('add_meta_boxes', array($admin_init, 'add_meta_box'));
        add_action('wp_ajax_sync_data_action', array($admin_init, 'sync_data_ajax_handler'));

        // Add shortcode and search query handler to WordPress hooks
        add_shortcode('omni_search', array($public_init, 'omni_search_shortcode'));
    }
}

add_action(
    'plugins_loaded',
    function () {
        $omni = Omni::get_instance();
        $omni->plugins_loaded();
    }
);

register_activation_hook(
    __FILE__,
    function () {
        //
    }
);