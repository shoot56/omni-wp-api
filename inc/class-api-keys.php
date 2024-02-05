<?php
// Function to generate a new API key




function verify_api_key($api_key) {
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

function create_project($project_name) {
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

function reindex_project() {
	$omni_api_key = get_option('_omni_api_key');
	$project_id = get_option('_omni_project_id');
	$selected_post_types = get_option('_omni_selected_post_types');
	$url = ENV_URL . '/rest/v1/projects/'. $project_id .'/resources/urls/';
	
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


				$new_url = ENV_URL . '/rest/v1/projects/'. $project_id .'/resources/urls/';

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
	$url = ENV_URL . '/rest/v1/projects/' . $project_id;
	
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

function sync_data() {
	$omni_api_key = get_option('_omni_api_key');
	$project_id = get_option('_omni_project_id');
	$fields_array = get_option('_omni_selected_fields_option');

	$uploaded_fields_array = get_option('_omni_uploaded_fields_option');

	$chains = array();
	if (!is_array($uploaded_fields_array)) {
		$uploaded_fields_array = array();
	}
	$types_to_delete = array_diff_key($uploaded_fields_array, $fields_array);
	$types_to_add = array_diff_key($fields_array, $uploaded_fields_array);



	foreach ($fields_array as $type => $fields) {
		if (isset($uploaded_fields_array[$type])) {
			if (compare_second_level($fields, $uploaded_fields_array[$type])) {
				delete_posts_of_type($type, $project_id, $chains);
				add_posts_of_type($type, $project_id, $chains, $fields);
			}
		} else {
			add_posts_of_type($type, $project_id, $chains, $fields);
		}
	}
	foreach ($uploaded_fields_array as $type => $fields) {
		if (!isset($fields_array[$type])) {
			delete_posts_of_type($type, $project_id, $chains);
		}
	}
	omni_error_log('sync_data chains:' . print_r($chains, true));
	if (!count($chains)) {
		return true;
	}
	$status = send_requests($chains, $omni_api_key, $project_id, $fields_array);
	if ($status) {
		update_option('_omni_last_sync_date', current_time('mysql'));
	}
	// update_option('_omni_uploaded_fields_option', $fields_array);
	// omni_error_log(print_r($chains, true));
	return $status;
}

function compare_second_level($array1, $array2) {
	foreach ($array1 as $key => $value) {
		if (!isset($array2[$key]) || array_diff_assoc($value, $array2[$key])) {
			return true;
		}
	}
	foreach ($array2 as $key => $value) {
		if (!isset($array1[$key])) {
			return true;
		}
	}
	return false;
}

function delete_posts_of_type($post_type, $project_id, &$chains) {
	$args = array(
		'post_type' => $post_type,
		'post_status' => 'publish',
		'posts_per_page' => -1,
	);
	$posts = get_posts($args);

	foreach ($posts as $post) {
		$chain_item = array(
			"chain" => "basic-delete",
			"payload" => array(
				"indexName" => $project_id,
				"where" => json_encode(array(
					"operator" => "Equal",
					"path" => array("eid"),
					"valueNumber" => $post->ID
				))
			)
		);
		array_push($chains, $chain_item);
	}
}

function add_posts_of_type($post_type, $project_id, &$chains, $fields) {
	if (!isset($fields)) {
		return;
	}

	$args = array(
		'post_type' => $post_type,
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => '_exclude_from_omni',
				'value'   => '1',
				'compare' => '!=',
			),
			array(
				'key'     => '_exclude_from_omni',
				'compare' => 'NOT EXISTS',
			),
		),
	);
	$posts = get_posts($args);

	foreach ($posts as $post) {
		$post_id = $post->ID;
		$post_title = $post->post_title;
		$post_content = wp_strip_all_tags($post->post_content, false);
		$post_url = get_permalink($post->ID);
		$post_author = get_the_author_meta('display_name', $post->post_author);
		$post_title = $post_title ?: $post_url;
		$post_data = '';
		omni_error_log( 'post type: ' . $post_type . ', Post title: ' . print_r($post_title, true));
		foreach ($fields as $field) {
			if (isset($field['status']) && $field['status'] == 1) {
				$label = $field['label'] ?: $field['name'];
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
				}

				$post_data .= "{$label}: {$content}\n";
			}
		}

		$post_data .= "url: {$post_url}\n";

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
								"eid" => $post_id
							)
						)
					)
				)
			)
		);

		array_push($chains, $chain_item);
	}
}


function send_requests($chains, $omni_api_key, $project_id, $fields_array) {
	$json_data = array("chains" => $chains);
	$json_body = json_encode($json_data);
	$endpoint = ENV_URL . '/v1/functions/chain/template/run-multiple';

	$response = wp_safe_remote_post($endpoint, array(
		'headers' => array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer ' . $omni_api_key,
		),
		'body' => $json_body, 
		'method'     => 'POST'
	));

	if (is_wp_error($response)) {
		omni_error_log('An error occurred when sending data to a remote server: ' . wp_remote_retrieve_response_code($response));
	} else {
		$response_code = wp_remote_retrieve_response_code($response);
		if ($response_code === 200) {
			
			update_option('_omni_uploaded_fields_option', $fields_array);
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
	// $all_post_data = '';

	$post_exclude = get_post_meta($post_id, '_exclude_from_omni', true);
	if ($post_exclude == '1') {
		return false;
	}
	omni_error_log('send_post exclude status: ' . $post_exclude);
	$chains = array();

	$post_title = get_the_title($post_id);
	$post_content = get_post_field('post_content', $post_id);
	$post_url = get_permalink($post_id);
	$author_id = get_post_field ('post_author', $post_id);
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
							"eid" => $post_id
						)
					)
				)
			)
		)
	);
	array_push($chains, $chain_item);

	$json_data = array(
		"chains" => $chains
	);
	
	$json_body = json_encode($json_data);
	$endpoint = ENV_URL . '/v1/functions/chain/template/run-multiple';
	$response = wp_safe_remote_post( $endpoint, array(
		'headers' => array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer ' . $omni_api_key,
		),
		'body' => $json_body, 
		'method'     => 'POST'
	));

	if (is_wp_error($response)) {
		$error_message = $response->get_error_message();
		omni_error_log('An error occurred when sending post to a remote server: ' . $error_message);
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
			omni_error_log('Error when sending post: server response with code: ' . $response_code);
			return false;
		}
	}
}
function delete_post($post_id) {
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
		array_push($chains, $chain_item);
		$json_data = array(
			"chains" => $chains
		);
		$json_body = json_encode($json_data);
		$endpoint = ENV_URL . '/v1/functions/chain/template/run-multiple';
		$response = wp_safe_remote_post( $endpoint, array(
			'headers' => array(
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $omni_api_key,
			),
			'body' => $json_body, 
			'method'     => 'POST'
		));
		// omni_error_log('body: ' .  print_r($json_body, true));
		if (is_wp_error($response)) {
			omni_error_log('An error occurred when deleting post: ' . wp_remote_retrieve_response_code($response));
			return false;
		} else {
			$response_code = wp_remote_retrieve_response_code($response);
			if ($response_code === 200) {
				
				omni_error_log('post: ' . $post_id . ' deleted');
				return true;
			} else {
				omni_error_log('Error when sending post deleting: server response with code: ' . $response_code);
				return false;
			}
		}
	}
}

function omni_error_log($message) {
	$log_file = plugin_dir_path(dirname(__FILE__)) . 'omni-logs.log';
	$message = date("Y-m-d H:i:s") . " - " . $message . "\n";
	file_put_contents($log_file, $message, FILE_APPEND | LOCK_EX);
}

function update_post_status($new_status, $old_status, $post){
	// fix multiple sending request
	if (wp_doing_ajax() || !is_admin()) return;
	$post_id = $post->ID;
	$fields_array = get_option('_omni_selected_fields_option');
	$post_type = get_post_type($post_id);
	if (isset($fields_array[$post_type])) {

		$exclude_from_omni = get_post_meta($post_id, '_exclude_from_omni', true);
		omni_error_log('update_post_status exclude status: ' . $exclude_from_omni . '; POST_ID: ' . $post_id);
		if ( $new_status == 'publish') {
			if ('1' === $exclude_from_omni) {
				delete_post($post_id); 
			} else {
				delete_post($post_id); 
				send_post($post_id);
			}
		}
		if ( $new_status == 'draft' || $new_status == 'trash') {
			delete_post($post_id);
		}
	}
}

add_action('transition_post_status', 'update_post_status', 999, 3);

add_action('wp_ajax_sync_data_action', 'sync_data_ajax_handler');
function sync_data_ajax_handler() {
	$result = sync_data();
	wp_send_json_success(array('synced' => $result));
}


add_action('add_meta_boxes', function() {
	$post_types = get_post_types(array('public' => true));
	foreach ($post_types as $post_type) {
		add_meta_box(
			'exclude_omni', // ID
			'Exclude from Omnimind', // Title
			'exclude_omni_meta_box_callback', // Callback function
			$post_type, // Screen (post type)
			'side', // Context
			'default' // Priority
		);
	}
});

function exclude_omni_meta_box_callback($post) {
	$value = get_post_meta($post->ID, '_exclude_from_omni', true);
	echo '<label><input type="checkbox" name="exclude_from_omni" value="1"'.checked($value, 1, false).'/> Exclude from Omnimind</label>';
}

// add_action('save_post', function($post_id) {
// 	if (isset($_POST['exclude_from_omni'])) {
// 		update_post_meta($post_id, '_exclude_from_omni', '1');
// 	} else {
// 		delete_post_meta($post_id, '_exclude_from_omni');
// 	}
// });




function add_omni_columns_to_post_types() {
	$selected_post_types = get_option('_omni_selected_post_types');

	if (is_array($selected_post_types) && !empty($selected_post_types)) {
		foreach ($selected_post_types as $post_type) {
			add_filter("manage_{$post_type}_posts_columns", 'add_omni_column');
			add_action("manage_{$post_type}_posts_custom_column", 'my_column_content', 10, 2);
		}
	}
}
add_action('admin_init', 'add_omni_columns_to_post_types');

function add_omni_column($columns) {
	$columns['omni_column'] = 'Omni sync status';
	return $columns;
}

function my_column_content($column_name, $post_id) {
	if ($column_name == 'omni_column') {
		$post_exclude = get_post_meta($post_id, '_exclude_from_omni', true);
		if ($post_exclude) {
			echo '<span style="color:#d03030;" class="dashicons dashicons-no"></span>';
		} else {
			echo '<span  style="color:#2baf3a;" class="dashicons dashicons-yes"></span>';
		}
	}
}

function add_quick_and_bulk_edit_to_post_types() {
	$selected_post_types = get_option('_omni_selected_post_types');

	if (is_array($selected_post_types) && !empty($selected_post_types)) {
		foreach ($selected_post_types as $post_type) {
			add_action("bulk_edit_custom_box", 'omni_edit_exclude_function', 10, 2);
			add_action("quick_edit_custom_box", 'omni_edit_exclude_function', 10, 2);
		}
	}
}
add_action('admin_init', 'add_quick_and_bulk_edit_to_post_types');

function omni_edit_exclude_function($column_name, $post_type) {
	if ($column_name == 'omni_column') {
		wp_nonce_field('save_exclude_from_omni', 'exclude_from_omni_nonce');
		?>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<label class="alignleft">
					<input type="checkbox" name="exclude_from_omni_bulk" value="1" />
					<span class="checkbox-title">Exclude from Omnimind</span>
				</label>
			</div>
		</fieldset>
		<?php
	}
}

function bulk_quick_save_post($post_id) {
	omni_error_log('bulk_quick_save_post INIT');
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;
	// if (!isset($_POST['exclude_from_omni_nonce']) || !wp_verify_nonce($_POST['exclude_from_omni_nonce'], 'save_exclude_from_omni')) {
	// 	return;
	// }
	if (isset($_POST['exclude_from_omni_bulk'])) {
		update_post_meta($post_id, '_exclude_from_omni', $_POST['exclude_from_omni_bulk']);
	} elseif (isset($_POST['exclude_from_omni'])) {
		update_post_meta($post_id, '_exclude_from_omni', $_POST['exclude_from_omni']);
	} else {
		delete_post_meta($post_id, '_exclude_from_omni');
	}
	$post_exclude = get_post_meta($post_id, '_exclude_from_omni', true);
	omni_error_log('bulk_quick_save_post: post_id ' . $post_id . ' saved, status: ' . $post_exclude);
}
add_action('save_post', 'bulk_quick_save_post');


// add_action('pre_post_update', function($post_id) {
// 	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
// 	if (!current_user_can('edit_post', $post_id)) return;
// 	if (wp_doing_ajax() || !is_admin()) return;

// 	$exclude_from_omni = isset($_POST['exclude_from_omni']) ? '1' : '0'; 
// 	update_post_meta($post_id, '_exclude_from_omni', $exclude_from_omni);
// 	$post_exclude = get_post_meta($post_id, '_exclude_from_omni', true);
// 	omni_error_log('pre_post_update: post_id ' . $post_id . ' saved, status: ' . $post_exclude);
// });

function my_admin_column_width() {
	$css = '
	.wp-list-table .column-omni_column { width: 130px; }
	';

	wp_add_inline_style('wp-admin', $css);
}
add_action('admin_enqueue_scripts', 'my_admin_column_width');
