<?php
function custom_post_type_rest_api() {
	register_rest_route('custom/v1', '/posts', array(
		'methods' => 'GET',
		'callback' => 'get_custom_posts',
		'permission_callback' => 'check_api_key',
	));
}
add_action('rest_api_init', 'custom_post_type_rest_api');

// Callback function to retrieve custom posts
function get_custom_posts($request) {
	$selected_post_types = get_option('custom_post_type_selected_post_types', array());

	$args = array(
		'post_type' => $selected_post_types,
		'posts_per_page' => -1,
	);
	$query = new WP_Query($args);
	$posts = $query->get_posts();

	$data = array();

	foreach ($posts as $post) {
		$author = get_userdata($post->post_author);

		$data[] = array(
			'id' => $post->ID,
			'title' => $post->post_title,
			'content' => $post->post_content,
			'author' => $author ? $author->display_name : 'Unknown Author',
		);
	}

	return $data;
}

