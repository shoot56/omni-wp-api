<?php
// Function to generate a new API key
function generate_api_key() {
    return bin2hex(random_bytes(32)); // Generate a 64-character hexadecimal key
}

function verify_api_key($api_key) {
    $url = 'https://dev-api.omnimind.ai/v1/functions/users/me';
    $headers = array(
        'Authorization' => 'Bearer ' . $api_key,
    );
    $args = array(
        'headers' => $headers,
    );
    $response = wp_remote_get($url, $args);
    if (is_wp_error($response)) {
        return false;
    } else {
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code === 200) {
            return true;
        } else {
            return false; 
        }
    }
}

function create_project($project_name) {
    $url = 'https://dev-api.omnimind.ai/rest/v1/projects/';
    $omni_api_key = get_option('_omni_api_key');
    $data = array(
        'omni_key' => $omni_api_key,
        'name' => sanitize_text_field($project_name),
    );
    $args = array(
        'body' => json_encode($data), 
        'headers' => array(
            'Content-Type' => 'application/json', 
        ),
    );
    $response = wp_safe_remote_post($url, $args);

    if (is_wp_error($response)) {
        return false;
    } else {
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code === 200) {
            $response_data = json_decode(wp_remote_retrieve_body($response), true);
            $project_id = $response_data['id'];
            $project_name = $response_data['name'];
            update_option('_omni_project_id', $project_id);
            update_option('_omni_project_name', $project_name);
            return true;
        } else {
            return false;
        }
    }
}