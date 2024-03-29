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
											<button class="btn-omni btn-omni--warning btn-omni--block">
												<svg class="svg-icon" width="16" height="16" >
													<use xlink:href="<?php echo plugins_url('assets/images/icons.svg#icon-purge', dirname(__FILE__)); ?>"></use>
												</svg>
												<span>Purge cache</span>
											</button>
										</div>
									</div>
								</div>
							</div>
							<button name="save_general" type="submit" class="btn-omni btn-omni--primary">
								<svg class="svg-icon" width="16" height="16" >
									<use xlink:href="<?php echo plugins_url('assets/images/icons.svg#icon-save', dirname(__FILE__)); ?>"></use>
								</svg>
								<span>Save</span>
							</button>
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
							// '_builtin' => false,
						);
						$output = 'objects';
						$operator = 'and';
						$post_types = get_post_types($args, $output, $operator);
						unset( $post_types[ 'attachment' ] );
						$selected_fields = get_option('_omni_selected_fields_option');

						if ($post_types) {
							?>
							<?php 
							// echo '<pre>',print_r($selected_fields,1),'</pre>'; 
							?>
							<ul class="content-types">
								<?php foreach ($post_types as $post_type): ?>
									<?php $additional_fields = array(
										'Title' => array(),
										'Content' => array(),
										'Author' => array(),
									); ?>
									<li class="content-types__item">
										<?php 
										$post_count = wp_count_posts($post_type->name);
										$label_with_count = $post_type->label . ' (' . $post_count->publish . ')';
										if (is_array($selected_post_types)) {
											$checked = in_array($post_type->name, $selected_post_types) ? 'checked' : '';
										} else {
											$checked = '';
										}
										// $selected_post_types_array = explode(',', $selected_post_types);

										// $checked = in_array($post_type->name, $selected_post_types_array) ? 'checked' : '';
										?>
										<label class="checkbox-holder content-type-head">
											<input name="post_types[]" value="<?php echo esc_attr($post_type->name); ?>" type="checkbox" class="checkbox" <?php echo esc_attr($checked); ?> />
											<span class="checkbox-item">&nbsp;</span>
											<span class="checkbox-label"><?php echo esc_html($label_with_count); ?></span>
										</label>
										<div class="attributes-wrap">
											<?php
												$post_ids = get_posts(
													[
														'numberposts' => -1,
														'post_type'   => $post_type->name,
													]
												);
												$custom_fields = [];

												foreach ($post_ids as $post) {
													$post_id = $post->ID;
													$post_custom_fields = get_post_custom($post_id);

													foreach ($post_custom_fields as $key => $values) {
														if (!isset($custom_fields[$key])) {
															$custom_fields[$key] = $values;
														} else {
															$custom_fields[$key] = array_merge($custom_fields[$key], $values);
														}
													}
												}
												if (is_array($custom_fields)) {
													$jsonData = json_encode(array_keys($custom_fields));
												} else {
													$jsonData = json_encode([]);
												}
												?>
												<div class="autocomplete-data" data-post-type="<?php echo esc_attr($post_type->name); ?>" style="display:none;">
													<?php echo $jsonData; ?>
												</div>
											<table class="attributes-table">
												<tr>
													<th>Attribute</th>
													<th>Searchable</th>
													<th>Label</th>
												</tr>
												
												
												<?php foreach ($additional_fields as  $key => $values): ?>
													<tr>
														<td><?php echo esc_html($key); ?></td>
														<td>
															
															<input class="form-input" type="hidden" name="post_type_fields[<?php echo esc_attr($post_type->name); ?>][<?php echo $key; ?>][name]" value="<?php echo esc_attr($key); ?>">
															
															<?php 
															if (isset($selected_fields[$post_type->name][$key]['status']) && $selected_fields[$post_type->name][$key]['status'] == 1) {
																echo '<input name="post_type_fields['.esc_attr($post_type->name).']['.$key.'][status]" value="1" type="checkbox" class="checkbox" checked />';
															} else {
																echo '<input name="post_type_fields['.esc_attr($post_type->name).']['.$key.'][status]" value="1" type="checkbox" class="checkbox" />';
															}
															?>

														</td>
														<td><input class="form-input" type="text" name="post_type_fields[<?php echo esc_attr($post_type->name); ?>][<?php echo $key; ?>][label]" value="<?php echo isset($selected_fields[$post_type->name][$key]['label']) ? esc_attr($selected_fields[$post_type->name][$key]['label']) : ''; ?>"></td>
														
													</tr>
													
												<?php endforeach ?>
												<?php 
												// additional fields
												if (isset($selected_fields[$post_type->name])) {
													foreach ($selected_fields[$post_type->name] as $key => $values) {
														if ($key == 'advanced-title-columns' || $key == 'advanced-metadata-columns' || $key == 'Title' || $key == 'Content' || $key == 'Author') {
															continue; 
														}
														echo '<tr>';
														echo '<td>' . esc_html($key) . '</td>';
														echo '<td>';
														echo '<input class="form-input" type="hidden" name="post_type_fields[' . esc_attr($post_type->name) . '][' . $key . '][name]" value="' . esc_attr($key) . '">';
														$checked = (isset($values['status']) && $values['status'] == 1) ? 'checked' : '';
														echo '<input name="post_type_fields[' . esc_attr($post_type->name) . '][' . $key . '][status]" value="1" type="checkbox" class="checkbox" ' . $checked . ' />';
														echo '</td>';
														echo '<td><input class="form-input" type="text" name="post_type_fields[' . esc_attr($post_type->name) . '][' . $key . '][label]" value="' . (isset($values['label']) ? esc_attr($values['label']) : '') . '"></td>';
														echo '</tr>';
													}
												}
												?>

											</table>
											<div class="custom-field">
												<!-- <input type="text" class="new-field-name autoComplete" name=""> -->
												<input type="text" class="new-field-name" data-post-type="<?php echo esc_attr($post_type->name); ?>">

												<button type="button" class="add-field btn-omni btn-omni--success" data-post-type="<?php echo esc_attr($post_type->name); ?>">add field</button>
											</div>

											<div class="advanced-settings">
												<button class="advanced-settings__opener btn-omni btn-omni--primary">Advanced Settings</button>
												<div class="advanced-settings__content">
													<div class="advanced-settings__row">
														<div class="advanced-settings__label">Select Title Columns</div>
														<div class="advanced-settings__input">
															<select class="js-example-basic-multiple" name="post_type_fields[<?php echo esc_attr($post_type->name); ?>][advanced-title-columns][]" multiple="multiple">
																<?php 
																$saved_title_columns = isset($selected_fields[$post_type->name]['advanced-title-columns']) ? $selected_fields[$post_type->name]['advanced-title-columns'] : array();
																foreach ($additional_fields as $key => $values): 
																	$selected = in_array($key, $saved_title_columns) ? 'selected' : '';
																	?>
																	<option value="<?php echo esc_html($key); ?>" <?php echo $selected; ?>><?php echo esc_html($key); ?></option>
																<?php endforeach ?>
																<?php if (isset($selected_fields[$post_type->name])): ?>
																	<?php foreach ($selected_fields[$post_type->name] as $key => $values): ?>
																		<?php 
																		if ($key == 'advanced-title-columns' || $key == 'advanced-metadata-columns' || $key == 'Title' || $key == 'Content' || $key == 'Author') {
																			continue; 
																		}
																		$selected = in_array($key, $saved_title_columns) ? 'selected' : '';
																		?>
																		<option value="<?php echo esc_html($key); ?>" <?php echo $selected; ?>><?php echo esc_html($key); ?></option>
																	<?php endforeach ?>
																<?php endif ?>
															</select>
														</div>
													</div>
													<div class="advanced-settings__row">
														<div class="advanced-settings__label">Select Metadata Columns</div>
														<div class="advanced-settings__input">
															<select class="js-example-basic-multiple" name="post_type_fields[<?php echo esc_attr($post_type->name); ?>][advanced-metadata-columns][]" multiple="multiple">
																<?php 
																$saved_title_columns = isset($selected_fields[$post_type->name]['advanced-metadata-columns']) ? $selected_fields[$post_type->name]['advanced-metadata-columns'] : array();
																foreach ($additional_fields as $key => $values): 
																	$selected = in_array($key, $saved_title_columns) ? 'selected' : '';
																	?>
																	<option value="<?php echo esc_html($key); ?>" <?php echo $selected; ?>><?php echo esc_html($key); ?></option>
																<?php endforeach ?>

															</select>
														</div>
													</div>
												</div>
											</div>



										</div>
										


									</li>
								<?php endforeach ?>
							</ul>
							<script>

							</script>

							<?php

						}
						?>
						<p>After making changes, it is highly advisable to run a re-synchronization at the <strong>Indexing</strong> tab.</p>
						<button <?php echo ($api_key_status) ? '' : 'disabled'; ?> type="submit" name="save_post_types" class="btn-omni btn-omni--primary">
							<svg class="svg-icon" width="16" height="16" >
								<use xlink:href="<?php echo plugins_url('assets/images/icons.svg#icon-save', dirname(__FILE__)); ?>"></use>
							</svg>
							<span>Save Post Types</span>
						</button>
					</form>
				</div>
				<div class="tab-item">
					<?php 
					$selected_fields = get_option('_omni_selected_fields_option');
					$selected_post_types = get_option('_omni_selected_post_types');
					 ?>

                    <form method="post" id="syncForm">
                        <div class="form-block">
                            <div class="form-block__title">
                                <span>Sync Settings</span>
                            </div>

                            <div class="form-block__frame">
                                <div class="form-block__wrap">
                                    <div class="form-block__content">
                                        <p>If you notice missing information in your search outcomes or if you've recently incorporated new custom content categories to your platform, it's advisable to initiate a synchronization to update these modifications.</p>

										<?php // Get last Sync Date
										$sync_date = get_option( '_omni_last_sync_date' ); ?>

                                        <p>Last sync status:
                                            <span id="last-sync-date"
                                                  style="<?= ! empty( $sync_date ) ? 'color: green;' : '' ?>">
                                                <?= ! empty( $sync_date ) ? $sync_date : 'N/A'; ?>
                                            </span>
                                        </p>
                                    </div>

                                    <div class="form-block__button">
                                        <button <?php echo ( $api_key_status ) ? '' : 'disabled'; ?>
                                                name="send_post_types" type="submit" id="sync-button"
                                                class="btn-omni btn-omni--primary btn-omni--block">
                                            <svg class="svg-icon" width="16" height="16">
                                                <use xlink:href="<?= plugins_url( 'assets/images/icons.svg#icon-sync', dirname( __FILE__ ) ); ?>"></use>
                                            </svg>
                                            <span>Sync Now</span>
                                        </button>
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
										<button <?php echo ($api_key_status) ? '' : 'disabled'; ?> name="reindex_project" type="submit" class="btn-omni btn-omni--warning btn-omni--block">
											<svg class="svg-icon" width="16" height="16" >
												<use xlink:href="<?php echo plugins_url('assets/images/icons.svg#icon-purge', dirname(__FILE__)); ?>"></use>
											</svg>
											<span>Re-Index</span>
										</button>
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
										<button <?php echo ($api_key_status) ? '' : 'disabled'; ?> name="delete_project" type="submit" class="btn-omni btn-omni--danger btn-omni--block">
											<svg class="svg-icon" width="16" height="16" >
												<use xlink:href="<?php echo plugins_url('assets/images/icons.svg#icon-delete', dirname(__FILE__)); ?>"></use>
											</svg>
											<span>Delete</span>
										</button>
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

<div id="omniAlertModal" class="omni-modal">
    <span class="omni-modal__close">
        <svg class="svg-icon" width="16" height="16">
            <use xlink:href="<?= plugins_url( 'assets/images/icons.svg#icon-close', dirname( __FILE__ ) ); ?>"></use>
        </svg>
    </span>

    <div class="omni-modal-content">
        <p class="omni-modal__text"></p>
    </div>
</div>
