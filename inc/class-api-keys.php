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

function reindex_project() {
	$omni_api_key = get_option('_omni_api_key');
	$project_id = get_option('_omni_project_id');
	$selected_post_types = get_option('_omni_selected_post_types');
	$url = 'https://dev-api.omnimind.ai/rest/v1/projects/'. $project_id .'/resources/urls/';
	
	$args = array(
		'headers' => array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer ' . $omni_api_key,
		),
		'method'     => 'GET'
	);
	$response = wp_remote_request($url, $args);

	if (is_wp_error($response)) {
		return false;
		omni_error_log('Reindex error code: ' . $response);
	} else {
		$response_code = wp_remote_retrieve_response_code($response);
		if ($response_code === 200) {

			$body = wp_remote_retrieve_body($response);
			$data = json_decode($body);
			if ($data && isset($data[0]->url)) {
				$data_url = $data[0]->url;


				$new_url = 'https://dev-api.omnimind.ai/rest/v1/projects/'. $project_id .'/resources/urls/';

				$new_data = array(
					'omni_key' => $omni_api_key,
					'url' => $data_url,
				);

				$new_args = array(
					'body' => json_encode($new_data),
					'headers' => array(
						'Content-Type' => 'application/json',
					),
					'method'     => 'DELETE'
				);
				$delete_response = wp_remote_request($new_url, $new_args);
				if (is_wp_error($delete_response)) {
					omni_error_log('Error in DELETE request in reindex_project: ' . $delete_response->get_error_message());

					return false;
				} else {
					$delete_response_code = wp_remote_retrieve_response_code($delete_response);
					if ($delete_response_code === 200) {
						omni_error_log('Successful deletion in reindex_project.');
					} else {
						omni_error_log('Error in DELETE request: Response code ' . $delete_response_code);
						return false;
					}
				}
			}
			
			$data_sended = send_data($selected_post_types);
			if ($data_sended === true) {
				omni_error_log('Data successfully updated in reindex_project.');
			} else {
				omni_error_log('Error sending data in reindex_project.');
			}
			return true;
		} else {
			omni_error_log('Error in GET request: Response code ' . $response_code);
			return false;
		}
	}
}

function delete_project() {
	$omni_api_key = get_option('_omni_api_key');
	$project_id = get_option('_omni_project_id');
	$url = 'https://dev-api.omnimind.ai/rest/v1/projects/' . $project_id;
	
	$args = array(
		'headers' => array(
			// 'Content-Type' => 'application/json',
			'Authorization' => 'Bearer ' . $omni_api_key,
		),
		'method'     => 'DELETE'
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
			update_option('_omni_api_key_status', false);
			return true;
		} else {
			return false;
		}
	}
}


function send_data($post_types) {
	$omni_api_key = get_option('_omni_api_key');
	$project_id = get_option('_omni_project_id');
	$fields_array = get_option('_omni_selected_fields_option');
	// $all_post_data = '';

	$chains = array();

	foreach ($post_types as $post_type) {
		$args = array(
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
		);
		$posts = get_posts($args);


		// $post_type_data = array();

		foreach ($posts as $post) {

			$post_id = $post->ID;
			$post_title = $post->post_title;
			$post_content = $post->post_content;
			// remove all tags and br from content
			$post_content = wp_strip_all_tags($post_content, false);
			$post_url = get_permalink($post->ID);
			$post_author = get_the_author_meta('display_name', $post->post_author);
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
						$content = get_post_meta( $post_id, $field['name'], true );
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
					"json" => array(
						"informer" => array(
							"type" => "text",
							"family" => "informer",
							"settings" => array(
								"content" => $post_data,
								"metadata" => array(
									"title" => $post_title,
									"url" => $post_url,
									"id" => $post_id
								)
							)
						)
					)
				)
			);
			array_push($chains, $chain_item);
		}
	}
	$json_data = array(
		"chains" => $chains
	);
	$json_body = json_encode($json_data);
	$response = wp_safe_remote_post('https://dev-api.omnimind.ai/v1/functions/chain/template/run-multiple', array(
		'headers' => array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer ' . $omni_api_key,
		),
		'body' => $json_body, 
		'method'     => 'POST'
	));
	if (is_wp_error($response)) {
		omni_error_log('An error occurred when sending data to a remote server: ' . $error_message);
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
			// echo '<pre>',print_r($json_body,1),'</pre>';
			return true;
		} else {
			omni_error_log('Error when sending data: server response with code: ' . $response_code);
			return false;
		}
	}
}

function send_post($post_id) {

	// fix multiple sending request
	if (wp_doing_ajax() || !is_admin()) return;

	$omni_api_key = get_option('_omni_api_key');
	$project_id = get_option('_omni_project_id');
	$fields_array = get_option('_omni_selected_fields_option');
	$all_post_data = '';

	$post_title = get_the_title($post_id);
	$post_content = get_post_field('post_content', $post_id);
	$post_url = get_permalink($post_id);
	$author_id = get_post_field ('post_author', $post_id);
	$post_author = get_the_author_meta('display_name', $author_id);
	$post_type = get_post_type($post_id);
			// start
	$all_post_data .= <<<EOD
	ID: {$post_id}

	EOD;
	if (isset($fields_array[$post_type])) {
		foreach ($fields_array[$post_type] as $field) {
			if (isset($field['status']) && $field['status'] == 1) {
				if ($field['label']) {
					$label = $field['label'];
				} else {
					$label = $field['name'];
				}
				$content = get_post_meta( $post_id, $field['name'], true );
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
				$all_post_data .= <<<EOD

				{$label}: {$content}

				EOD;
			}
		}
	} else {
		return;
	}
			// end

	$all_post_data .= <<<EOD
	url: {$post_url}

	EOD;

	$json_data = array(
		'omni_key' => $omni_api_key, 
		'nowait' => true,
		'title' => get_site_url(), 
		'content' => $all_post_data, 
		'metadata' => array('title' => get_bloginfo('name')),
	);
	$json_body = json_encode($json_data);
	$response = wp_safe_remote_post('https://dev-api.omnimind.ai/rest/v1/projects/'. $project_id .'/training/text', array(
		'body' => $json_body, 
		'headers' => array(
			'Content-Type' => 'application/json', 
		),
	));

	if (is_wp_error($response)) {
		$error_message = $response->get_error_message();
		omni_error_log('An error occurred when sending data to a remote server: ' . $error_message);
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
			omni_error_log('Post updated: ' . $post_title . ' - Post type: ' .$post_type);
			return true;
		} else {
			omni_error_log('Error when sending data: server response with code: ' . $response_code);
			return false;
		}
	}
}

function omni_error_log($message) {
    $log_file = plugin_dir_path(dirname(__FILE__)) . 'omni-logs.log';

    $message = date("Y-m-d H:i:s") . " - " . $message . "\n";

    file_put_contents($log_file, $message, FILE_APPEND | LOCK_EX);
}