<?php

namespace Procoders\Omni\Public;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Procoders\Omni\ClassLoader as Loader;
use Procoders\Omni\Includes\api as Api;

class ClassPublic
{
    private $template;
    private $api;

    /**
     * Creates a shortcode for displaying the Omni Search form.
     *
     * @return string The output of the shortcode.
     */
    public function __construct()
    {
        $this->template = new Loader();
        $this->api = new Api();
    }

    public function omni_search_shortcode()
    {
        ob_start();
        $this->template->set_template_data(
            array(
                'template' => $this->template,
                'form' => [
                    'search_answer' => get_option('_omni_ai_search_answer')
                ]
            )
        )->get_template_part('public/search-form');
        return ob_get_clean();
    }

    /**
     * Handle search query and return cached results if they exist
     *
     * @return void Echoes json data and exits.
     */
    public function omni_search_handle_query(): void
    {
        // Check and sanitize input values
        if (!isset($_POST['nonce'], $_POST['query'], $_POST['offset']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'omni_search_handle_query')) {
            wp_send_json_error(['message' => __('Permission denied...', 'omni')]);
            return;
        }

        // Assign sanitized form values to variables
        $nonce = sanitize_text_field($_POST['nonce']);
        $query = sanitize_text_field($_POST['query']);
        $offset = filter_var($_POST['offset'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 0)));

        $this->perform_search($nonce, $query, $offset);
    }

    private function perform_search($nonce, $query, $offset): void
    {
        // Create a unique cache key for this query
        $cache_key = 'omni_search_results_' . md5($query);
        // Try to retrieve the result from the cache
        //$cache = get_transient($cache_key);

//        if ($cache !== false) {
//            wp_send_json_success($cache);
//            return;
//        }

        // If no cached response exits, make the search request
        $response = $this->api->make_search_req($query, $offset);

        // If the search request fails, return an error
        if ($response === false) {
            wp_send_json_error(['message' => __('Unable to process request.', 'omni')]);
            return;
        }

        // If the search request succeeds, store response in cache and return response
        set_transient($cache_key, $response, 60 * MINUTE_IN_SECONDS); // Cache for 5 minutes
        wp_send_json_success($response);
    }
}

