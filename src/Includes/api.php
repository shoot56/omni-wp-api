<?php

namespace Procoders\Omni\Includes;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Procoders\Omni\Includes\debugger as debugger;

class api
{
    private $debug;

    public function __construct()
    {
        $this->debug = new debugger();
    }

    /**
     * @param $api_key
     *
     * @return bool
     */
    public function verify_api_key($api_key): bool
    {
        $url = ENV_URL . '/v1/functions/users/me';
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

    /**
     * @param $project_name
     *
     * @return bool
     */
    public function create_project($project_name): bool
    {
        $url = ENV_URL . '/rest/v1/projects/';
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


    /**
     * Makes a search request to the Omni API.
     *
     * @param string $query The search query.
     * @param int $offset The offset for pagination of search results.
     *
     * @return bool|array Returns either false if there was an error or an array of search results.
     */
    public function make_search_req(string $query, int $offset): bool|array
    {
        $omni_api_key = get_option('_omni_api_key');
        $project_id = get_option('_omni_project_id');
        $url = ENV_URL . '/rest/v1/projects/' . $project_id . '/actions/search';
        $data = array(
            'query' => $query,
            'hybrid' => 0,
            'offset' => $offset,
            'limit' => (int) get_option('_omni_ai_search_results_limit'),
            'language' => get_locale()
        );
        $headers = array(
            'Authorization' => 'Bearer ' . $omni_api_key,
            'Content-Type' => 'application/json',
        );
        $args = array(
            'body' => wp_json_encode($data),
            'headers' => $headers,
        );
        $response = wp_safe_remote_post($url, $args);
        if (is_wp_error($response)) {
            $this->debug->omni_error_log('Search req error: ' . $response);
            return false;
        } else {
            return json_decode(wp_remote_retrieve_body($response), true);
        }
    }


    /**
     * @return bool
     */
    public function delete_project(): bool
    {
        $omni_api_key = get_option('_omni_api_key');
        $project_id = get_option('_omni_project_id');
        $url = ENV_URL . '/rest/v1/projects/' . $project_id;

        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $omni_api_key,
            ),
            'method' => 'DELETE'
        );
        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            return false;
        } else {
            $response_code = wp_remote_retrieve_response_code($response);
            if ($response_code === 200) {
                update_option('_omni_api_key', '');
                update_option('_omni_project_id', '');
                update_option('_omni_project_name', '');
                update_option('_omni_selected_post_types', '');
                update_option('_omni_selected_fields_option', '');
                update_option('_omni_uploaded_fields_option', '');
                update_option('_omni_last_sync_date', '');
                update_option('_omni_api_key_status', false);

                return true;
            } else {
                return false;
            }
        }
    }


    public function get_resources(string $project_id): bool|array
    {
        $url = ENV_URL . '/rest/v1/projects/' . $project_id . '/resources/urls/';
        $omni_api_key = get_option('_omni_api_key');
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $omni_api_key,
            ),
            'method' => 'GET'
        );
        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            $this->debug->omni_error_log('Reindex error code: ' . $response);
            return false;
        } else {
            $response_code = wp_remote_retrieve_response_code($response);
            if ($response_code === 200) {
                $body = wp_remote_retrieve_body($response);
                return json_decode($body);
            } else {
                return false;
            }
        }
    }

    public function del_resources(string $data_url, string $project_id): bool|array
    {
        $omni_api_key = get_option('_omni_api_key');
        $new_url = ENV_URL . '/rest/v1/projects/' . $project_id . '/resources/urls/';

        $new_data = array(
            'omni_key' => $omni_api_key,
            'url' => $data_url,
        );

        $new_args = array(
            'body' => json_encode($new_data),
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'method' => 'DELETE'
        );
        $delete_response = wp_remote_request($new_url, $new_args);
        if (is_wp_error($delete_response)) {
            $this->debug->omni_error_log('Error in DELETE request in reindex_project: ' . $delete_response->get_error_message());
            return false;
        } else {
            $delete_response_code = wp_remote_retrieve_response_code($delete_response);
            if ($delete_response_code === 200) {
                $this->debug->omni_error_log('Successful deletion in reindex_project.');
                return true;
            } else {
                $this->debug->omni_error_log('Error in DELETE request: Response code ' . $delete_response_code);
                return false;
            }
        }
    }

    /**
     * @param $chains
     * @param $omni_api_key
     * @param $project_id
     * @param $fields_array
     *
     * @return bool|void
     */
    public function send_requests($chains, $omni_api_key, $project_id, $fields_array)
    {
        // ToDo: $project_id unused

        $json_data = array("chains" => $chains);
        $json_body = json_encode($json_data);
        $endpoint = ENV_URL . '/v1/functions/chain/template/run-multiple';

        $response = wp_safe_remote_post($endpoint, array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $omni_api_key,
            ),
            'body' => $json_body,
            'method' => 'POST'
        ));

        if (is_wp_error($response)) {
            $this->debug->omni_error_log('An error occurred when sending data to a remote server: ' . wp_remote_retrieve_response_code($response));
        } else {
            $response_code = wp_remote_retrieve_response_code($response);
            if ($response_code === 200) {
                update_option('_omni_uploaded_fields_option', $fields_array);
                $this->debug->omni_error_log('Data synced');
                return true;
            } else {
                $this->debug->omni_error_log('Error when sending data: server response with code: ' . $response_code);

                return false;
            }
        }
    }


    /**
     * @param $post_id
     *
     * @return bool|void
     */
    public function delete_post($post_id)
    {
        $omni_api_key = get_option('_omni_api_key');
        $project_id = get_option('_omni_project_id');
        $fields_array = get_option('_omni_selected_fields_option');
        $post_type = get_post_type($post_id);

        if (isset($fields_array[$post_type])) {
            $chains = array();

            $chain_item = array(
                "chain" => "basic-delete",
                "payload" => array(
                    "indexName" => $project_id,
                    "where" => json_encode(array(
                        "operator" => "Equal",
                        "path" => array("eid"),
                        "valueNumber" => $post_id
                    ))
                )
            );

            $chains[] = $chain_item;

            $json_data = array(
                "chains" => $chains
            );
            $json_body = json_encode($json_data);
            $endpoint = ENV_URL . '/v1/functions/chain/template/run-multiple';
            $response = wp_safe_remote_post($endpoint, array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $omni_api_key,
                ),
                'body' => $json_body,
                'method' => 'POST'
            ));

            if (is_wp_error($response)) {
                $this->debug->omni_error_log('An error occurred when deleting post: ' . wp_remote_retrieve_response_code($response));

                return false;
            } else {
                $response_code = wp_remote_retrieve_response_code($response);
                if ($response_code === 200) {

                    $this->debug->omni_error_log('post: ' . $post_id . ' deleted');

                    return true;
                } else {
                    $this->debug->omni_error_log('Error when sending post deleting: server response with code: ' . $response_code);

                    return false;
                }
            }
        }
    }


    /**
     * @param $post_id
     *
     * @return bool
     */
    public function send_post($post_id)
    {
        $omni_api_key = get_option('_omni_api_key');
        $project_id = get_option('_omni_project_id');
        $fields_array = get_option('_omni_selected_fields_option');
        $post_exclude = get_post_meta($post_id, '_exclude_from_omni', true);

        if ($post_exclude == '1') {
            return false;
        }
        $chains = array();

        $post_title = get_the_title($post_id);
        $post_content = get_post_field('post_content', $post_id);
        $post_url = get_permalink($post_id);
        $author_id = get_post_field('post_author', $post_id);
        $post_author = get_the_author_meta('display_name', $author_id);
        $post_type = get_post_type($post_id);

        // remove all tags and br from content
        $post_content = wp_strip_all_tags($post_content, false);
        if ($post_title == '') {
            $post_title = $post_url;
        }
        $post_data = '';
        if (isset($fields_array[$post_type])) {
            foreach ($fields_array[$post_type] as $field) {
                if (isset($field['status']) && $field['status'] == 1) {
                    if ($field['label']) {
                        $label = $field['label'];
                    } else {
                        $label = $field['name'];
                    }
                    $content = get_post_meta($post_id, $field['name'], true);
                    switch ($field['name']) {
                        case 'Title':
                            $content = $post_title;
                            break;
                        case 'Content':
                            $content = $post_content;
                            break;
                        case 'Author':
                            $content = $post_author;
                            break;
                        default:
                            break;
                    }
                    $post_data .= <<<EOD

				{$label}: {$content}

				EOD;
                }
            }
        }

        $post_data .= <<<EOD
	url: {$post_url}

	EOD;
        $chain_item = array(
            "chain" => "basic-informer",
            "payload" => array(
                "indexName" => $project_id,
                "no-wait" => true,
                "widgetTypeId" => WIDGET_TYPE_ID,
                "json" => array(
                    "informer" => array(
                        "type" => "text",
                        "family" => "informer",
                        "settings" => array(
                            "content" => $post_data,
                            "metadata" => array(
                                "title" => $post_title,
                                "url" => $post_url,
                                "eid" => $post_id
                            )
                        )
                    )
                )
            )
        );
        $chains[] = $chain_item;

        $json_data = array(
            "chains" => $chains
        );

        $json_body = json_encode($json_data);
        $endpoint = ENV_URL . '/v1/functions/chain/template/run-multiple';
        $response = wp_safe_remote_post($endpoint, array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $omni_api_key,
            ),
            'body' => $json_body,
            'method' => 'POST'
        ));

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            $this->debug->omni_error_log('An error occurred when sending post to a remote server: ' . $error_message);

            return false;
        } else {
            $response_code = wp_remote_retrieve_response_code($response);
            if ($response_code === 200) {
                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body);
                if ($data && isset($data->id)) {
                    $id = $data->id;
                    update_option('_omni_chain_id', $id);
                }
                $this->debug->omni_error_log('Post updated: ' . $post_title . ' - Post type: ' . $post_type);

                return true;
            } else {
                $this->debug->omni_error_log('Error when sending post: server response with code: ' . $response_code);

                return false;
            }
        }
    }
}






