<?php
namespace Procoders\Omni\Public;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Procoders\Omni\ClassLoader as Loader;
use Procoders\Omni\Includes\api as Api;

class ClassPublic
{
    /**
     * Creates a shortcode for displaying the Omni Search form.
     *
     * @return string The output of the shortcode.
     */
    public function omni_search_shortcode()
    {
        $template = new Loader();

        ob_start();
        $template->set_template_data(
            array(
                'template' => $template,
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
        $api = new Api();
        // Capture input values, if they exist
        $nonce = sanitize_text_field($_POST['nonce']) ?? null;
        $query = $_POST['query'] ?? null;
        $offset = filter_var($_POST['offset'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 0))) ?? null;

        // If not all necessary data has been posted or the nonce doesn't verify, return an error
        if (!$nonce || !$query || !wp_verify_nonce($nonce, 'omni_search_handle_query')) {
            wp_send_json_error(['message' => 'Permission denied...']);
            return;
        }

        // Create a unique cache key for this query
        $cache_key = 'omni_search_results_' . md5($query);

        // Try to retrieve the result from the cache
        $cache = get_transient($cache_key);

        // If a cached response exists, return it and stop there
        if ($cache !== false) {
            wp_send_json_success($cache);
            return;
        }

        // If no cached response exits, make the search request
        $response = $api->make_search_req($query, $offset);

        // If the search request fails, return an error
        if ($response === false) {
            wp_send_json_error(['message' => 'Unable to process request.']);
            return;
        }

        // If the search request succeeds, store response in cache and return response
        set_transient($cache_key, $response, 60 * MINUTE_IN_SECONDS); // Cache for 5 minutes
        wp_send_json_success($response);
    }
}

