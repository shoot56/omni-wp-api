<?php
// Create a menu item for plugin settings in the admin menu
function custom_post_type_settings_menu() {
	add_menu_page(
		'Custom Post Type API Settings',
		'Omni WP API',
		'manage_options',
		'custom-post-type-api-settings',
		'custom_post_type_settings_page'
	);
}
add_action('admin_menu', 'custom_post_type_settings_menu');

// Callback function to display the settings page
function custom_post_type_settings_page() {
	if (!current_user_can('manage_options')) {
		return;
	}

	if (isset($_POST['generate_api_key'])) {
		$api_key = generate_api_key();
		update_option('custom_post_type_api_key', $api_key);
	}

	if (isset($_POST['save_post_types'])) {
		$selected_post_types = array();
		if (isset($_POST['post'])) {
			$selected_post_types[] = 'post';
		}

		if (isset($_POST['page'])) {
			$selected_post_types[] = 'page';
		}

		$args = array(
			'public'   => true,
			'_builtin' => false,
		);
		$output = 'names';
		$operator = 'and';
		$post_types = get_post_types($args, $output, $operator);

		if ($post_types) {
			foreach ($post_types as $post_type) {
				if (isset($_POST[$post_type])) {
					$selected_post_types[] = $post_type;
				}
			}
		}
		if (empty($selected_post_types)) {
			$selected_post_types = array();
		}

		update_option('custom_post_type_selected_post_types', $selected_post_types);
	}

	$api_key = get_option('custom_post_type_api_key');

	$selected_post_types = get_option('custom_post_type_selected_post_types', array());

	?>

	<div class="wrap">
		<h2>Omnimind Configuration</h2>

		<div class="nav-tab-wrapper">
			<a href="#tab1" class="nav-tab">API Settings</a>
			<a href="#tab2" class="nav-tab">Content types</a>
		</div>

		<div id="tab1" class="tab-content">
			<form method="post">
				<table class="form-table">
					<tr>
						<th scope="row">API Key</th>
						<td><?php echo esc_html($api_key); ?></td>
						<td>
							<input type="submit" name="generate_api_key" class="button button-primary" value="Generate New Key">
						</td>
					</tr>
				</table>
			</form>
		</div>

		<div id="tab2" class="tab-content">
			<form method="post">
				<table class="form-table">
					<tr>
						<th scope="row">Content Types</th>
						<td>
							<?php
							$args = array(
								'public'   => true,
								'_builtin' => false,
							);
							$output = 'objects';
							$operator = 'and';
							$post_types = get_post_types($args, $output, $operator);

							if ($post_types) {
								echo '<ul>';
								$checked = in_array('post', $selected_post_types) ? 'checked' : '';
								echo '<li><label class="checkbox-holder">
								<input name="post" type="checkbox" class="checkbox" ' . esc_attr($checked) . ' />
								<span class="checkbox-item">&nbsp;</span>
								<span class="checkbox-label">' . esc_html('Posts (' . wp_count_posts('post')->publish . ')') . '</span>
								</label></li>';

								$checked = in_array('page', $selected_post_types) ? 'checked' : '';
								echo '<li><label class="checkbox-holder">
								<input name="page" type="checkbox" class="checkbox" ' . esc_attr($checked) . ' />
								<span class="checkbox-item">&nbsp;</span>
								<span class="checkbox-label">' . esc_html('Pages (' . wp_count_posts('page')->publish . ')') . '</span>
								</label></li>';
								foreach ($post_types as $post_type) {
									$post_count = wp_count_posts($post_type->name);
									$label_with_count = $post_type->label . ' (' . $post_count->publish . ')';
									$checked = in_array($post_type->name, $selected_post_types) ? 'checked' : '';
									echo '<li><label class="checkbox-holder">
									<input name="' . esc_attr($post_type->name) . '" type="checkbox" class="checkbox" ' . esc_attr($checked) . ' />
									<span class="checkbox-item">&nbsp;</span>
									<span class="checkbox-label">' . esc_html($label_with_count) . '</span>
									</label></li>';
								}
								echo '</ul>';
							}
							?>
						</td>
					</tr>
					<tr>
						<td><input type="submit" name="save_post_types" class="button button-primary" value="Save Post Types"></td>
					</tr>
				</table>
			</form>
		</div>
	</div>

	<?php
}

// Function to generate a new API key
function generate_api_key() {
	return bin2hex(random_bytes(32)); // Generate a 64-character hexadecimal key
}