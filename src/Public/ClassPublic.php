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
                    'search_answer' => get_option('_omni_ai_search_answer'),
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
        if (!isset($_POST['nonce'], $_POST['query']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'omni_search_handle_query')) {
            wp_send_json_error(['message' => __('Permission denied...', 'omni')]);
            return;
        }

        $query = sanitize_text_field($_POST['query']);
        $this->perform_search( $query );
    }

    public function omni_search_handle_autocomplete(): void
    {

        // Check and sanitize input values
        if (!isset($_POST['nonce'], $_POST['query']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'omni_search_handle_autocomplete')) {
            wp_send_json_error(['message' => __('Permission denied...', 'omni')]);
            return;
        }

        $args = array(
            'post_type' => array('post', 'page'),
            'posts_per_page' => 10,
            's' => sanitize_text_field($_POST['query']),
        );

        $query = new \WP_Query($args);
        $posts = $query->posts;

        $data = [];

        foreach ($posts as $post) {
            $data[] = array(
                'id' => $post->ID,
                'text' => $post->post_title,
            );
        }

        wp_send_json_success($data);

    }

    private function perform_search($query): void
    {
        // Create a unique cache key for this query
        $cache_key = 'omni_search_results_' . md5($query);
        // Try to retrieve the result from the cache
        $cache = get_transient($cache_key);
        $cache_lifetime = (int)get_options('_omni_ai_cache') ?? 1;

        if (!empty($cache)) {
            wp_send_json_success($cache);
            return;
        }
        $answer = '';
        // If no cached response exits, make the search request
        $response = $this->api->make_search_req($query);
        if(get_option('_omni_ai_search_answer') === '1'){
                $response_ = $this->api->make_answer_req($query);
                $answer = $response_['results'][0]['results'];
        }
        $res['results'] = json_decode($response['results'][0]['results']);
        $res['answer'] = $answer;
        // If the search request fails, return an error
        if ($response === false) {
            wp_send_json_error(['message' => __('Unable to process request.', 'omni')]);
            return;
        }

        // If the search request succeeds, store response in cache and return response
        $cached = set_transient($cache_key, $res, $cache_lifetime * 60 * MINUTE_IN_SECONDS); // Cache for 5 minutes
        if ($cached) {
            wp_send_json_success($res);
        } else {
            wp_send_json_error(['message' => __('Unable to cache request.', 'omni')]);
        }
    }


}

