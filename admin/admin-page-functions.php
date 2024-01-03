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

		$all_post_data = '';

		foreach ($selected_post_types as $post_type) {
			$args = array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => -1,
			);
			$posts = get_posts($args);

			$post_type_data = array();

			foreach ($posts as $post) {
	            $all_post_data .= "\n" . 'id: ' . $post->ID . "\n";
	            $all_post_data .= 'title: ' . $post->post_title . "\n";
	            $all_post_data .= 'content: ' . $post->post_content . "\n";
	            $all_post_data .= 'url: '. get_permalink($post->ID) . "\n";
	            $all_post_data .= 'author: ' . get_the_author_meta('display_name', $post->post_author) . "\n";
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
		} else {
			$response_code = wp_remote_retrieve_response_code($response);
			if ($response_code === 200) {
				echo 'Данные успешно отправлены на удаленный сервер в формате CSV.';
			} else {
				echo 'Произошла ошибка при отправке данных на удаленный сервер.';
			}
		}
		update_option('_omni_selected_post_types', $selected_post_types);
	}

	if (isset($_POST['send_project_name'])) {
		$project_name = sanitize_text_field($_POST['project_name']);
		$project_created = create_project($project_name);
		if ($project_created === true) {
			echo 'Проект успешно создан.';
		} else {
			echo 'Не удалось создать проект.';
		}
	}

	$selected_post_types = get_option('_omni_selected_post_types', array());

	include('settings-page.php');
}

