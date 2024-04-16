<?php
/**
 * Handle search query and return cached results if they exist
 *
 * @return void Echoes json data and exits.
 */
function omni_search_handle_query(): void
{
    // Capture input values, if they exist
    $nonce = $_POST['nonce'] ?? null;
    $query = $_POST['query'] ?? null;
    $offset = $_POST['offset'] ?? null;

    // If not all necessary data has been posted or the nonce doesn't verify, return an error
    if (!$nonce || !$query || !wp_verify_nonce($nonce, 'omni_search_handle_query')) {
        wp_send_json_error(['message' => 'Permission denied...']);
        return;
    }

    // Sanitize query, validate offset (must be integer >= 0)
    $query = sanitize_text_field($query);
    $offset = filter_var($offset, FILTER_VALIDATE_INT, array('options' => array('min_range' => 0)));

    // Create a unique cache key for this query
    $cache_key = 'brainy_search_results_' . md5($query);

    // Try to retrieve the result from the cache
    $cache = get_transient($cache_key);

    // If a cached response exists, return it and stop there
    if ($cache !== false) {
        wp_send_json_success($cache);
        return;
    }

    // If no cached response exits, make the search request
    $response = make_search_req($query, $offset);

    // If the search request fails, return an error
    if ($response === false) {
        wp_send_json_error(['message' => 'Unable to process request.']);
        return;
    }

    // If the search request succeeds, store response in cache and return response
    set_transient($cache_key, $response, 60 * MINUTE_IN_SECONDS); // Cache for 5 minutes
    wp_send_json_success($response);
}

/**
 * Include and display the omni search form template
 *
 * @return void Outputs the search form template.
 */
function omni_search_form(): void
{
    // Load the search form template and echo it.
    include wp_kses_post( omni_search_load_template('omni-search-form') );
}

/**
 * Load a template for Omni Search
 *
 * @param string $template_name The name of the template to load.
 * @return string The path of the template file.
 */
function omni_search_load_template(string $template_name): string
{
    // Check if a custom template exists in the theme folder, if not, load the plugin template file
    $theme_template = locate_template('omnimind/' . $template_name . '.php');
    if ($theme_template) {
        return $theme_template;
    }
    // Fallback to plugin template file
    return plugin_dir_path(dirname(__FILE__)) . 'partials/' . $template_name . '.php';
}

/**
 * Creates a shortcode for displaying the Omni Search form.
 *
 * @return string The output of the shortcode.
 */
function omni_search_shortcode()
{
    ob_start();
    include wp_kses_post( omni_search_load_template('omni-search-form') );
    return ob_get_clean();
}

