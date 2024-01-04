<?php 
$api_key_status = get_option('_omni_api_key_status');
$omni_api_key = get_option('_omni_api_key');
$project_name = get_option('_omni_project_name');
$project_id = get_option('_omni_project_id');
 ?>

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
											<a href="https://google.com" target="_blank" class="btn-omni btn-omni--primary"><span class="dashicons dashicons-external"></span> Settings</a>
										<?php else: ?>
											<button type="submit" name="send_project_name" class="btn-omni btn-omni--primary">Create</button>
										<?php endif ?>
									</div>
									<div class="form-info">Your Omnimind Project name</div>
								</div>
							</div>
						</form>
						<form method="post">
							<?php 
							$ai_search_answer = get_option('_omni_ai_search_answer');
							$ai_search_content = get_option('_omni_ai_search_content');
							$ai_search_autocomplete = get_option('_omni_ai_search_autocomplete');
							$ai_search_results_limit = get_option('_omni_ai_search_results_limit');
							$ai_search_trust_level = get_option('_omni_ai_search_trust_level');
							$ai_cache = get_option('_omni_ai_cache');
							 ?>
							<div class="form-row">
								<div class="form-row__label">
									<div class="form-label">Options</div>
								</div>
								<div class="form-row__item">
									<ul class="checkbox-list">
										<li>
											<label class="checkbox-holder">
												<input name="ai_search_answer" type="checkbox" class="checkbox" <?php checked(1, $ai_search_answer); ?> />
												<span class="checkbox-item">&nbsp;</span>
												<span class="checkbox-label">AI answer</span>
											</label>
										</li>
										<li>
											<label class="checkbox-holder">
												<input name="ai_search_content" type="checkbox" class="checkbox" <?php checked(1, $ai_search_content); ?> />
												<span class="checkbox-item">&nbsp;</span>
												<span class="checkbox-label">Content</span>
											</label>
										</li>
										<li>
											<label class="checkbox-holder">
												<input name="ai_search_autocomplete" type="checkbox" class="checkbox" <?php checked(1, $ai_search_autocomplete); ?> />
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
											<input class="form-input" type="number" name="ai_search_results_limit" value="<?php echo (esc_attr($ai_search_results_limit)) ? esc_attr($ai_search_results_limit)  : '5'; ?>">
										</div>
									</div>
								</div>
								<div class="form-col">
									<div class="form-row">
										<div class="form-row__label">
											<div class="form-label">Trust level</div>
										</div>
										<div class="form-row__item">
											<input class="form-input" type="number" name="ai_search_trust_level" value="<?php echo (esc_attr($ai_search_trust_level) ? esc_attr($ai_search_trust_level) : '5'); ?>">
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
												<input class="form-input" type="number" name="ai_cache" value="<?php echo (esc_attr($ai_cache) ? esc_attr($ai_cache) : '24'); ?>">
												<div class="cache-input__info">hours</div>
											</div>
										</div>
										<div class="form-block__button">
											<button class="btn-omni btn-omni--warning btn-omni--block"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="16" height="16"><g><path d="m16.117 8.924-1.341.774a.194.194 0 0 1-.288-.203l.674-3.668a.195.195 0 0 1 .256-.148l3.514 1.249a.195.195 0 0 1 .028.355L17.72 8l1.308 2.266c.473.819.711 1.726.718 2.636a5.403 5.403 0 0 1-.679 2.655 5.317 5.317 0 0 1-1.958 2.021 5.344 5.344 0 0 1-2.72.734h-1.42a.195.195 0 0 1-.195-.196v-1.46c0-.107.088-.195.196-.195h1.418v.001a3.514 3.514 0 0 0 1.75-.464 3.566 3.566 0 0 0 1.759-3.084 3.468 3.468 0 0 0-.465-1.714l-.005-.009-1.31-2.267Zm-.47-4.513a.195.195 0 0 1-.07.285l-1.296.674a.196.196 0 0 1-.264-.083l-.003-.007-.961-1.665-.007-.01a3.497 3.497 0 0 0-2.13-1.635 3.558 3.558 0 0 0-1.832 0 3.423 3.423 0 0 0-.836.347l-.009.005a3.49 3.49 0 0 0-1.285 1.283L4.74 7.44l1.237.716a.195.195 0 0 1-.028.355L2.435 9.76v-.001a.195.195 0 0 1-.257-.148l-.674-3.668a.194.194 0 0 1 .288-.204l1.346.776 2.205-3.818A5.337 5.337 0 0 1 7.313.72 5.331 5.331 0 0 1 10 0a5.364 5.364 0 0 1 4.654 2.691l.993 1.72ZM.966 10.277l.006-.011a.195.195 0 0 1 .278-.064l1.253.723a.195.195 0 0 1 .07.266l-.005.01a3.467 3.467 0 0 0-.465 1.712 3.562 3.562 0 0 0 1.75 3.08l.01.005a3.512 3.512 0 0 0 1.749.463h3.234v-1.493a.195.195 0 0 1 .336-.134l2.824 2.405a.194.194 0 0 1-.002.298l-2.837 2.416a.194.194 0 0 1-.32-.148h-.001v-1.493H5.612a5.348 5.348 0 0 1-2.72-.734 5.32 5.32 0 0 1-1.959-2.02 5.398 5.398 0 0 1-.679-2.656 5.337 5.337 0 0 1 .712-2.625Z"/></g></svg>Purge cache</button>
										</div>
									</div>
								</div>
							</div>
							<button name="save_general" type="submit" class="btn-omni btn-omni--primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.1716 1C18.702 1 19.2107 1.21071 19.5858 1.58579L22.4142 4.41421C22.7893 4.78929 23 5.29799 23 5.82843V20C23 21.6569 21.6569 23 20 23H4C2.34315 23 1 21.6569 1 20V4C1 2.34315 2.34315 1 4 1H18.1716ZM4 3C3.44772 3 3 3.44772 3 4V20C3 20.5523 3.44772 21 4 21L5 21L5 15C5 13.3431 6.34315 12 8 12L16 12C17.6569 12 19 13.3431 19 15V21H20C20.5523 21 21 20.5523 21 20V6.82843C21 6.29799 20.7893 5.78929 20.4142 5.41421L18.5858 3.58579C18.2107 3.21071 17.702 3 17.1716 3H17V5C17 6.65685 15.6569 8 14 8H10C8.34315 8 7 6.65685 7 5V3H4ZM17 21V15C17 14.4477 16.5523 14 16 14L8 14C7.44772 14 7 14.4477 7 15L7 21L17 21ZM9 3H15V5C15 5.55228 14.5523 6 14 6H10C9.44772 6 9 5.55228 9 5V3Z" /></svg>Save</button>
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
										<button type="submit" name="check_api_key" class="btn-omni btn-omni--primary">Verify</button>
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
						
						<p>Select the content types in your project that will be included in the search results and be used to construct AI answers</p>

						<?php
						$args = array(
							'public'   => true,
							'_builtin' => false,
						);
						$output = 'objects';
						$operator = 'and';
						$post_types = get_post_types($args, $output, $operator);
						$selected_fields = get_option('_omni_selected_fields_option');
						$additional_fields = array('ID', 'Title', 'Content', 'Author');

						if ($post_types) {
							?>
							<ul class="content-types">
								<li class="content-types__item">
									<?php $checked = in_array('post', $selected_post_types) ? 'checked' : ''; ?>
									<label class="checkbox-holder content-type-head">
										<input name="post_types[]" value="post" type="checkbox" class="checkbox" <?php echo esc_attr($checked) ?> />
										<span class="checkbox-item">&nbsp;</span>
										<span class="checkbox-label"><?php echo esc_html('Posts (' . wp_count_posts('post')->publish . ')') ?></span>
									</label>
									<div class="attributes-wrap">
										<table class="attributes-table">
											<tr>
												<th>Attribute</th>
												<th>Searchable</th>
												<th>Label</th>
											</tr>
											<?php foreach ($additional_fields as $field): ?>
												<tr>
													<td><?php echo esc_html($field); ?></td>
													<td>
														<?php 
														if (isset($selected_fields['post']) && in_array($field, $selected_fields['post'])) {
															echo '<input name="post_type_fields[post][]" value="' . esc_attr($field) . '" type="checkbox" class="checkbox" checked />';
														} else {
															echo '<input name="post_type_fields[post][]" value="' . esc_attr($field) . '" type="checkbox" class="checkbox" />';
														}
														?>
													</td>
													<td>??</td>
												</tr>
											<?php endforeach ?>
										</table>
									</div>

								</li>
								<li class="content-types__item">
									<?php $checked = in_array('page', $selected_post_types) ? 'checked' : ''; ?>
									<label class="checkbox-holder content-type-head">
										<input name="post_types[]" value="page" type="checkbox" class="checkbox" <?php echo esc_attr($checked) ?> />
										<span class="checkbox-item">&nbsp;</span>
										<span class="checkbox-label"><?php echo esc_html('Pages (' . wp_count_posts('page')->publish . ')') ?></span>
									</label>
									<div class="attributes-wrap">
										<table class="attributes-table">
											<tr>
												<th>Attribute</th>
												<th>Searchable</th>
												<th>Label</th>
											</tr>
											<?php foreach ($additional_fields as $field): ?>
												<tr>
													<td><?php echo esc_html($field); ?></td>
													<td>
														<?php 
														if (isset($selected_fields['page']) && in_array($field, $selected_fields['page'])) {
															echo '<input name="post_type_fields[page][]" value="' . esc_attr($field) . '" type="checkbox" class="checkbox" checked />';
														} else {
															echo '<input name="post_type_fields[page][]" value="' . esc_attr($field) . '" type="checkbox" class="checkbox" />';
														}
														?>
													</td>
													<td>??</td>
												</tr>
											<?php endforeach ?>
										</table>
									</div>
								</li>
								<?php foreach ($post_types as $post_type): ?>
									<li class="content-types__item">
										<?php 
										$post_count = wp_count_posts($post_type->name);
										$label_with_count = $post_type->label . ' (' . $post_count->publish . ')';
										$checked = in_array($post_type->name, $selected_post_types) ? 'checked' : '';
										?>
										<label class="checkbox-holder content-type-head">
											<input name="post_types[]" value="<?php echo esc_attr($post_type->name); ?>" type="checkbox" class="checkbox" <?php echo esc_attr($checked); ?> />
											<span class="checkbox-item">&nbsp;</span>
											<span class="checkbox-label"><?php echo esc_html($label_with_count); ?></span>
										</label>
										<div class="attributes-wrap">
											<table class="attributes-table">
												<tr>
													<th>Attribute</th>
													<th>Searchable</th>
													<th>Label</th>
												</tr>
												<?php foreach ($additional_fields as $field): ?>
													<tr>
														<td><?php echo esc_html($field); ?></td>
														<td>
															<?php 
															if (isset($selected_fields[$post_type->name]) && in_array($field, $selected_fields[$post_type->name])) {
																echo '<input name="post_type_fields[' . esc_attr($post_type->name) . '][]" value="' . esc_attr($field) . '" type="checkbox" class="checkbox" checked />';
															} else {
																echo '<input name="post_type_fields[' . esc_attr($post_type->name) . '][]" value="' . esc_attr($field) . '" type="checkbox" class="checkbox" />';
															}
															?>
														</td>
														<td>??</td>
													</tr>
												<?php endforeach ?>
											</table>
										</div>


									</li>
								<?php endforeach ?>
							</ul>
							<script>
								var checkboxes = document.querySelectorAll('.content-type-head input[type="checkbox"]');
								function handleCheckboxChange(event) {
									var parentItem = event.target.closest('.content-types__item');
									if (!parentItem) {
										return; 
									}
									var attributesWrap = parentItem.querySelector('.attributes-wrap');
									if (event.target.checked) {
										attributesWrap.style.display = 'block'; 
									} else {
										attributesWrap.style.display = 'none'; 
									}
								}
								checkboxes.forEach(function(checkbox) {
									checkbox.addEventListener('change', handleCheckboxChange);
								});
								checkboxes.forEach(function(checkbox) {
									handleCheckboxChange({ target: checkbox });
								});
							</script>

							<?php

						}
						?>
						<p>After making changes, it is highly advisable to run a re-synchronization at the <strong>Indexing</strong> tab.</p>
						<button type="submit" name="save_post_types" class="btn-omni btn-omni--primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.1716 1C18.702 1 19.2107 1.21071 19.5858 1.58579L22.4142 4.41421C22.7893 4.78929 23 5.29799 23 5.82843V20C23 21.6569 21.6569 23 20 23H4C2.34315 23 1 21.6569 1 20V4C1 2.34315 2.34315 1 4 1H18.1716ZM4 3C3.44772 3 3 3.44772 3 4V20C3 20.5523 3.44772 21 4 21L5 21L5 15C5 13.3431 6.34315 12 8 12L16 12C17.6569 12 19 13.3431 19 15V21H20C20.5523 21 21 20.5523 21 20V6.82843C21 6.29799 20.7893 5.78929 20.4142 5.41421L18.5858 3.58579C18.2107 3.21071 17.702 3 17.1716 3H17V5C17 6.65685 15.6569 8 14 8H10C8.34315 8 7 6.65685 7 5V3H4ZM17 21V15C17 14.4477 16.5523 14 16 14L8 14C7.44772 14 7 14.4477 7 15L7 21L17 21ZM9 3H15V5C15 5.55228 14.5523 6 14 6H10C9.44772 6 9 5.55228 9 5V3Z" /></svg>Save Post Types</button>
					</form>
				</div>
				<div class="tab-item">
					<form method="post">
						<div class="form-block">
							<div class="form-block__title"><span>Sync Settings</span></div>
							<div class="form-block__frame">
								<div class="form-block__wrap">
									<div class="form-block__content">
										<p>If you notice missing information in your search outcomes or if you've recently incorporated new custom content categories to your platform, it's advisable to initiate a synchronization to update these modifications.</p>
										<p>Last sync status: <span style="color: green;">Success in Mon, March 07, 2022 14:41</span></p>
									</div>
									<div class="form-block__button">
										<button name="send_post_types" type="submit" class="btn-omni btn-omni--primary btn-omni--block"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="16" height="16" fill="none"><g><path d="M19.167 2.333a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-5a1 1 0 1 1 0-2h4v-4a1 1 0 0 1 1-1ZM-.167 11.666a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2h-4v4a1 1 0 1 1-2 0v-5Z"/><path d="M11.802 3.758a6.5 6.5 0 0 0-3.24-.096l-.222-.975.221.975a6.5 6.5 0 0 0-4.693 4.171 1 1 0 1 1-1.886-.667A8.5 8.5 0 0 1 4.35 3.65l.664.747-.664-.747a8.5 8.5 0 0 1 11.647.333l3.856 3.623a1 1 0 0 1-1.37 1.457L14.615 5.43a6.5 6.5 0 0 0-2.814-1.67ZM.105 10.982a1 1 0 0 1 1.413-.044l3.867 3.633.022.022a6.5 6.5 0 0 0 6.032 1.745 6.5 6.5 0 0 0 2.884-1.482l.664.748-.664-.748a6.5 6.5 0 0 0 1.81-2.69 1 1 0 0 1 1.885.668 8.501 8.501 0 0 1-6.137 5.454l-.221-.975.22.975a8.5 8.5 0 0 1-7.876-2.27L.15 12.395a1 1 0 0 1-.044-1.413Z"/></g></svg>Sync Now</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<form method="post">
						<div class="form-block">
							<div class="form-block__title"><span>Clear and Reinitialize</span></div>
							<div class="form-block__frame">
								<div class="form-block__wrap">
									<div class="form-block__content">
										<p>Should you continue to face discrepancies in your search outcomes, consider starting a thorough re-synchronization.</p>
										<p>Executing this will remove all indexed information from OmniMind, but your WordPress site remains unaffected. The entire process might span several hours, contingent on the volume of content awaiting</p>
									</div>
									<div class="form-block__button">
										<button name="reindex_project" type="submit" class="btn-omni btn-omni--warning btn-omni--block"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="16" height="16"><g><path d="m16.117 8.924-1.341.774a.194.194 0 0 1-.288-.203l.674-3.668a.195.195 0 0 1 .256-.148l3.514 1.249a.195.195 0 0 1 .028.355L17.72 8l1.308 2.266c.473.819.711 1.726.718 2.636a5.403 5.403 0 0 1-.679 2.655 5.317 5.317 0 0 1-1.958 2.021 5.344 5.344 0 0 1-2.72.734h-1.42a.195.195 0 0 1-.195-.196v-1.46c0-.107.088-.195.196-.195h1.418v.001a3.514 3.514 0 0 0 1.75-.464 3.566 3.566 0 0 0 1.759-3.084 3.468 3.468 0 0 0-.465-1.714l-.005-.009-1.31-2.267Zm-.47-4.513a.195.195 0 0 1-.07.285l-1.296.674a.196.196 0 0 1-.264-.083l-.003-.007-.961-1.665-.007-.01a3.497 3.497 0 0 0-2.13-1.635 3.558 3.558 0 0 0-1.832 0 3.423 3.423 0 0 0-.836.347l-.009.005a3.49 3.49 0 0 0-1.285 1.283L4.74 7.44l1.237.716a.195.195 0 0 1-.028.355L2.435 9.76v-.001a.195.195 0 0 1-.257-.148l-.674-3.668a.194.194 0 0 1 .288-.204l1.346.776 2.205-3.818A5.337 5.337 0 0 1 7.313.72 5.331 5.331 0 0 1 10 0a5.364 5.364 0 0 1 4.654 2.691l.993 1.72ZM.966 10.277l.006-.011a.195.195 0 0 1 .278-.064l1.253.723a.195.195 0 0 1 .07.266l-.005.01a3.467 3.467 0 0 0-.465 1.712 3.562 3.562 0 0 0 1.75 3.08l.01.005a3.512 3.512 0 0 0 1.749.463h3.234v-1.493a.195.195 0 0 1 .336-.134l2.824 2.405a.194.194 0 0 1-.002.298l-2.837 2.416a.194.194 0 0 1-.32-.148h-.001v-1.493H5.612a5.348 5.348 0 0 1-2.72-.734 5.32 5.32 0 0 1-1.959-2.02 5.398 5.398 0 0 1-.679-2.656 5.337 5.337 0 0 1 .712-2.625Z"/></g></svg>Re-Index</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<form method="post">
						<div class="form-block">
							<div class="form-block__title"><span>Purge and change API key</span></div>
							<div class="form-block__frame">
								<div class="form-block__wrap">
									<div class="form-block__content">
										<p>Clicking it you <span style="color: red;">remove</span> your project and purge all indexes at Omnimind. It doesn't affect you Wordpress data. This action is not reversible.</p>
										<p>You can setup a new API key and start from</p>
									</div>
									<div class="form-block__button">
										<button name="delete_project" type="submit" class="btn-omni btn-omni--danger btn-omni--block"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.98995 5.68115C8.41884 5.68115 8.76652 6.02883 8.76652 6.45772V8.78743C8.76652 9.21632 8.41884 9.564 7.98995 9.564C7.56106 9.564 7.21338 9.21632 7.21338 8.78743V6.45772C7.21338 6.02883 7.56106 5.68115 7.98995 5.68115Z" /><circle cx="7.98995" cy="11.1169" r="0.776569" /><path fill-rule="evenodd" clip-rule="evenodd" d="M6.65068 1.76591C7.08559 1.02657 8.03751 0.779774 8.77686 1.21468C9.00447 1.34858 9.19419 1.53829 9.32809 1.76591L15.736 12.6594C16.1709 13.3987 15.9241 14.3507 15.1848 14.7856C14.9461 14.926 14.6742 15 14.3973 15H1.58146C0.723684 15 0.0283203 14.3046 0.0283203 13.4469C0.0283203 13.1699 0.102355 12.8981 0.242756 12.6594L6.65068 1.76591ZM14.3973 13.4469L7.98938 2.55339L1.58146 13.4469H14.3973Z" /></svg>Delete</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="tab-item">
					<p>Here you can see you users search requests</p>
				</div>
			</div>
		</div>
		
	</div>

</div>