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
					return false;
				} else {
					$delete_response_code = wp_remote_retrieve_response_code($delete_response);
					if ($delete_response_code === 200) {
						echo 'deleted';
						$selected_post_types = get_option('_omni_selected_post_types');

						$data_sended = send_data($selected_post_types);
						if ($data_sended === true) {
							echo 'Данные успешно Updated';
						} else {
							echo 'Произошла ошибка при отправке данных на удаленный сервер.';
						}
						return true;
					} else {
						return false;
					}
				}
			}
			
			return true;
		} else {
			return false;
		}
	}
}

function delete_project() {
	$omni_api_key = get_option('_omni_api_key');
	$project_id = get_option('_omni_project_id');
	$url = 'https://dev-api.omnimind.ai/rest/v1/projects/' . $project_id;
	// $data = array(
	//     'omni_key' => $omni_api_key,
	//     // 'name' => sanitize_text_field($project_name),
	// );
	$args = array(
		// 'body' => json_encode($data), 
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
			// $response_data = json_decode(wp_remote_retrieve_body($response), true);
			// $project_id = $response_data['id'];
			// $project_name = $response_data['name'];
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
	$all_post_data = '';

	foreach ($post_types as $post_type) {
		$args = array(
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
		);
		$posts = get_posts($args);


		$post_type_data = array();

		foreach ($posts as $post) {
			$post_id = $post->ID;
			$post_title = $post->post_title;
			$post_content = $post->post_content;
			$post_url = get_permalink($post->ID);
			$post_author = get_the_author_meta('display_name', $post->post_author);
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
				
			}
			// end
			
			$all_post_data .= <<<EOD
			url: {$post_url}

			EOD;
		}
	}

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
		echo 'Произошла ошибка при отправке данных на удаленный сервер.';
		return false;
	} else {
		echo '<pre style="white-space: pre-line;">',print_r($json_body,1),'</pre>';
		$response_code = wp_remote_retrieve_response_code($response);
		if ($response_code === 200) {
			$body = wp_remote_retrieve_body($response);
			$data = json_decode($body);
			if ($data && isset($data->id)) {
				$id = $data->id;
				update_option('_omni_chain_id', $id);
			}
			return true;
		} else {
			return false;
		}
	}
}

