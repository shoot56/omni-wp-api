<?php

// Callback function to display the settings page
function custom_post_type_settings_page() {
	if (!current_user_can('manage_options')) {
		return;
	}

	if (isset($_POST['check_api_key'])) {
		$user_api_key = sanitize_text_field($_POST['verify_api_key']);
		$api_key_status = verify_api_key($user_api_key);
		if ($api_key_status === true) {
			update_option('_omni_api_key_status', true);
		} else {
			update_option('_omni_api_key_status', false);
		}
		update_option('_omni_api_key', $user_api_key);
	}
	$api_key_status = get_option('_omni_api_key_status');
	$omni_api_key = get_option('_omni_api_key');
	$project_name = get_option('_omni_project_name');
	$project_id = get_option('_omni_project_id');

	
	if (isset($_POST['save_post_types'])) {
		$selected_post_types = isset($_POST['post_types']) ? $_POST['post_types'] : array();

		update_option('_omni_selected_post_types', $selected_post_types);

		if (isset($_POST['post_type_fields'])) {
			$selected_fields = $_POST['post_type_fields'];

			$filtered_selected_fields = array();
			foreach ($selected_fields as $post_type => $fields) {
				if (in_array($post_type, $selected_post_types)) {
					$title_columns = isset($fields['advanced-title-columns']) ? $fields['advanced-title-columns'] : array();
					$metadata_columns = isset($fields['advanced-metadata-columns']) ? $fields['advanced-metadata-columns'] : array();
					$filtered_fields = array();
					foreach ($fields as $field) {
						if (!empty($field['status'])) {
							$filtered_fields[$field['name']] = $field;
						}
					}
					$filtered_selected_fields[$post_type] = $filtered_fields;
					if (!empty($title_columns)) {
						$filtered_selected_fields[$post_type]['advanced-title-columns'] = $title_columns;
					}
					if (!empty($metadata_columns)) {
						$filtered_selected_fields[$post_type]['advanced-metadata-columns'] = $metadata_columns;
					}
				}
			}

			update_option('_omni_selected_fields_option', $filtered_selected_fields);
		}
	}




	if (isset($_POST['send_post_types'])) {
		$selected_post_types = get_option('_omni_selected_post_types');

		// $data_sended = send_data($selected_post_types);
		$data_sended = sync_data($selected_post_types);
















		
		if ($data_sended === true) {
			// echo 'Data successfully sent to remote server in CSV format.';
			omni_error_log('Data successfully sent to remote server in CSV format.');
		} else {
			// echo 'An error occurred when sending data to a remote server.';
			omni_error_log('An error occurred when sending data to a remote server.');
		}
		
	}

	if (isset($_POST['send_project_name'])) {
		$project_name = sanitize_text_field($_POST['project_name']);
		$project_created = create_project($project_name);
		if ($project_created === true) {
			// echo 'Project created';
		} else {
			// echo 'Failed to create project';
		}
	}

	if (isset($_POST['delete_project'])) {
		$project_deleted = delete_project();
		if ($project_deleted === true) {
			// echo 'Project deleted';
		} else {
			// echo 'Failed to delete project';
		}
	}

	if (isset($_POST['reindex_project'])) {
		$project_reindexed = reindex_project();
		if ($project_reindexed === true) {
			// echo 'Project updated';
		} else {
			// echo 'Failed to update project';
		}
	}


	if (isset($_POST['save_general'])) {
		$ai_search_answer = isset($_POST['ai_search_answer']) ? 1 : 0;
		$ai_search_content = isset($_POST['ai_search_content']) ? 1 : 0;
		$ai_search_autocomplete = isset($_POST['ai_search_autocomplete']) ? 1 : 0;
		$ai_search_results_limit = isset($_POST['ai_search_results_limit']) ? intval($_POST['ai_search_results_limit']) : 5;
		$ai_search_trust_level = isset($_POST['ai_search_trust_level']) ? intval($_POST['ai_search_trust_level']) : 5;
		$ai_cache = isset($_POST['ai_cache']) ? intval($_POST['ai_cache']) : 24;

		// save data
		update_option('_omni_ai_search_answer', $ai_search_answer);
		update_option('_omni_ai_search_content', $ai_search_content);
		update_option('_omni_ai_search_autocomplete', $ai_search_autocomplete);
		update_option('_omni_ai_search_results_limit', $ai_search_results_limit);
		update_option('_omni_ai_search_trust_level', $ai_search_trust_level);
		update_option('_omni_ai_cache', $ai_cache);
	}

	$selected_post_types = get_option('_omni_selected_post_types', array());

	include('settings-page.php');
}

