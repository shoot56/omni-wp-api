<?php
/**
 * Assets class
 *
 * @package Omni
 */

namespace Procoders\Omni\Public;

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
        add_action('wp_enqueue_scripts', array(static::class, 'public_assets'));
    }

    /**
     * Enqueues assets for the admin area.
     *
     * @return void
     */
    public static function public_assets(): void
    {
        wp_enqueue_style('omni-search-style', plugins_url('../../assets/css/omni-wp-search.css', __FILE__));
        //
        wp_register_script('omni-search-script', plugins_url('../../assets/js/omni-wp-search.js', __FILE__), array(), null, true);
        wp_enqueue_script('omni-search-script');
        wp_localize_script('omni-search-script', 'omni_ajax',
            array(
                'url' => admin_url('admin-ajax.php'),
                'query_nonce' => wp_create_nonce('omni_search_handle_query'),
                'search_answer' => get_option('_omni_ai_search_answer'),
                '_read_more' => __('Read More', 'omni'),
                '_search' => __('Search', 'omni'),
                '_results' => __('Results', 'omni'),
                '_prev' => __('Prev', 'omni'),
                '_next' => __('Next', 'omni'),
            )
        );
    }
}
