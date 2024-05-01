<?php
/**
 * Assets Class
 *
 * @package Omni
 */

namespace Procoders\Omni\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/**
 * Create the admin menu.
 */
class ClassAssets
{

    /**
     * Main class runner.
     */
    public static function run(): void
    {
        add_action('admin_enqueue_scripts', array(static::class, 'admin_assets'));
    }

    /**
     * Enqueues assets for the admin area.
     *
     * @return void
     */
    public static function admin_assets(): void
    {
        global $hook_suffix;
        // Check if the current page is a plugin page.
        if (str_contains($hook_suffix, 'omni-')) {

            $css = '.wp-list-table .column-omni_column { width: 130px; }';
            wp_add_inline_style('wp-admin', $css);

            wp_register_style('omni-style', plugins_url('../../assets/css/omni-wp-api.css', __FILE__), array(), '1.0.0', 'all');
            wp_enqueue_style('select2', plugins_url('../../assets/vendor/select2/select2.min.css', __FILE__));
            wp_enqueue_style('omni-style');

            wp_register_script('omni-script', plugins_url('../../assets/js/omni-wp-api.js', __FILE__), array('jquery'), null, true);
            wp_enqueue_script('select2', plugins_url('../../assets/vendor/select2/select2.min.js', __FILE__), array('jquery'), null, true);
            wp_enqueue_script('omni-script');
        }
    }
}
