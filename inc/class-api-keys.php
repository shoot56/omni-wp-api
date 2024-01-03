<?php

// Callback function to check API key
function check_api_key($request) {
    $api_key = $request->get_header('X-API-Key'); // Retrieve API key from request header

    // Get the saved API key from plugin settings
    $saved_api_key = get_option('custom_post_type_api_key');

    // Check if the provided API key matches the saved one
    if ($api_key === $saved_api_key) {
        return true; // API key is valid, allow access
    } else {
        return new WP_Error('rest_forbidden', 'Invalid API Key', array('status' => 403));
        // If API key is invalid, return a 403 (Forbidden) error
    }
}