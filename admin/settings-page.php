<div class="omni-config wrap">
	<div class="omni-config__container">
		<h2>Omnimind Configuration</h2>
		<div class="tabset">
			<ul class="tab-control">
				<li><a class="tab-opener" href="#">General</a></li>
				<li><a class="tab-opener" href="#">Content types</a></li>
				<li><a class="tab-opener" href="#">Indexing</a></li>
				<li><a class="tab-opener" href="#">Requests</a></li>
			</ul>
			<div class="tabs-list">
				<div class="tab-item ">
					<?php if ($api_key_status): ?>
						<div class="form-row">
							<div class="form-row__label">
								<div class="form-label">API Key</div>
							</div>
							<div class="form-row__item">
								<div class="api-status">
									<input class="form-input" type="text" name="verify_api_key" value="<?php echo esc_html($omni_api_key); ?>">
									<div class="status"><?php echo ($api_key_status) ? '<span class="dashicons dashicons-yes-alt"></span>' : '<span class="dashicons dashicons-dismiss"></span>'; ?></div>
								</div>
								<div class="form-info">Your Omnimind API key</div>
							</div>
						</div>
						<form method="post">
							<div class="form-row">
								<div class="form-row__label">
									<div class="form-label">Project</div>
								</div>
								<div class="form-row__item">
									<div class="inputs-wrap">
										<input class="form-input" type="text" name="project_name" value="<?php echo esc_html($project_name); ?>">
										<?php if ($project_id): ?>
											<a href="https://google.com" target="_blank" class="button button-primary"><span class="dashicons dashicons-external"></span> Settings</a>
										<?php else: ?>
											<input type="submit" name="send_project_name" class="button button-primary" value="Create">
										<?php endif ?>
									</div>
									<div class="form-info">Your Omnimind Project name</div>
								</div>
							</div>
						</form>
						<form method="post">
							<div class="form-row">
								<div class="form-row__label">
									<div class="form-label">Options</div>
								</div>
								<div class="form-row__item">
									<ul class="checkbox-list">
										<li>
											<label class="checkbox-holder">
												<input name="ai_search_answer" type="checkbox" class="checkbox" />
												<span class="checkbox-item">&nbsp;</span>
												<span class="checkbox-label">AI answer</span>
											</label>
										</li>
										<li>
											<label class="checkbox-holder">
												<input name="ai_search_content" type="checkbox" class="checkbox" />
												<span class="checkbox-item">&nbsp;</span>
												<span class="checkbox-label">Content</span>
											</label>
										</li>
										<li>
											<label class="checkbox-holder">
												<input name="ai_search_autocomplete" type="checkbox" class="checkbox" />
												<span class="checkbox-item">&nbsp;</span>
												<span class="checkbox-label">Autocomplete</span>
											</label>
										</li>
									</ul>
								</div>
							</div>
							<div class="form-columns">
								<div class="form-col">
									<div class="form-row">
										<div class="form-row__label">
											<div class="form-label">Results limit</div>
										</div>
										<div class="form-row__item">
											<input class="form-input" type="number" name="ai_search_results_limit" value="">
										</div>
									</div>
								</div>
								<div class="form-col">
									<div class="form-row">
										<div class="form-row__label">
											<div class="form-label">Trust level</div>
										</div>
										<div class="form-row__item">
											<input class="form-input" type="number" name="ai_search_trust_level" value="">
										</div>
									</div>
								</div>
							</div>

							<div class="form-block">
								<div class="form-block__title"><span>Cache</span></div>
								<div class="form-block__frame">
									<div class="form-block__wrap">
										<div class="form-block__content">
											<p>Output results can be cached to prevent numerous requests to AI and save the costs. If you set it to 0 no cache is going to be applied</p>
											<div class="cache-input">
												<div class="form-label">Cache period</div>
												<input class="form-input" type="number" name="ai_cache" value="">
												<div class="cache-input__info">hours</div>
											</div>
										</div>
										<div class="form-block__button">
											<button class="button button-warning">Purge cache</button>
										</div>
									</div>
								</div>
							</div>
							<button name="save_general" type="submit" class="button button-primary">Save</button>
						</form>
					<?php else: ?>
						<form method="post">
							<div class="form-row">
								<div class="form-row__label">
									<div class="form-label">API Key</div>
								</div>
								<div class="form-row__item">
									<div class="api-verify">
										<input class="form-input" type="text" name="verify_api_key" value="<?php echo esc_html($omni_api_key); ?>">
										<div class="status"><?php echo ($api_key_status) ? '<span class="dashicons dashicons-yes-alt"></span>' : '<span class="dashicons dashicons-dismiss"></span>'; ?></div>
										<input type="submit" name="check_api_key" class="button button-primary" value="Verify">
									</div>
									<div class="form-info">
										<ol>
											<li><a href="#">Sign Up</a> at Omnimind</li>
											<li>Get a key at <a href="#">Settings - Profile - API Keys</a> section</li>
											<li>Copy paste it here and clock Verify</li>
											<li>By happy with a new AI omni search :-)</li>
										</ol>
									</div>
								</div>
							</div>
						</form>
					<?php endif ?>
				</div>
				<div class="tab-item">
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
										<input name="post_types[]" value="post" type="checkbox" class="checkbox" ' . esc_attr($checked) . ' />
										<span class="checkbox-item">&nbsp;</span>
										<span class="checkbox-label">' . esc_html('Posts (' . wp_count_posts('post')->publish . ')') . '</span>
										</label></li>';

										$checked = in_array('page', $selected_post_types) ? 'checked' : '';
										echo '<li><label class="checkbox-holder">
										<input name="post_types[]" value="page" type="checkbox" class="checkbox" ' . esc_attr($checked) . ' />
										<span class="checkbox-item">&nbsp;</span>
										<span class="checkbox-label">' . esc_html('Pages (' . wp_count_posts('page')->publish . ')') . '</span>
										</label></li>';
										foreach ($post_types as $post_type) {
											$post_count = wp_count_posts($post_type->name);
											$label_with_count = $post_type->label . ' (' . $post_count->publish . ')';
											$checked = in_array($post_type->name, $selected_post_types) ? 'checked' : '';
											echo '<li><label class="checkbox-holder">
											<input name="post_types[]" value="' . esc_attr($post_type->name) . '" type="checkbox" class="checkbox" ' . esc_attr($checked) . ' />
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
				<div class="tab-item">
					
					<div class="form-block">
						<div class="form-block__title"><span>Sync Settings</span></div>
						<div class="form-block__frame">
							<div class="form-block__wrap">
								<div class="form-block__content">
									<p>Output results can be cached to prevent numerous requests to AI and save the costs. If you set it to 0 no cache is going to be applied</p>
								</div>
								<div class="form-block__button">
									<button class="button button-warning">Sync Now</button>
								</div>
							</div>
						</div>
					</div>
					<div class="form-block">
						<div class="form-block__title"><span>Clear and Reinitialize</span></div>
						<div class="form-block__frame">
							<div class="form-block__wrap">
								<div class="form-block__content">
									<p>Output results can be cached to prevent numerous requests to AI and save the costs. If you set it to 0 no cache is going to be applied</p>
								</div>
								<div class="form-block__button">
									<button class="button button-warning">Re-Index</button>
								</div>
							</div>
						</div>
					</div>
					<div class="form-block">
						<div class="form-block__title"><span>Purge and change API key</span></div>
						<div class="form-block__frame">
							<div class="form-block__wrap">
								<div class="form-block__content">
									<p>Output results can be cached to prevent numerous requests to AI and save the costs. If you set it to 0 no cache is going to be applied</p>
								</div>
								<div class="form-block__button">
									<button class="button button-warning">Delete</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-item">
					<p>Here you can see you users search requests</p>
				</div>
			</div>
		</div>
		
	</div>

</div>