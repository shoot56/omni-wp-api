<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$api_key_status = $data->form['api_key_status'];
$omni_api_key = $data->form['omni_api_key'];
$project_name = $data->form['project_name'];
$project_id = $data->form['project_id'];
$selected_post_types = $data->form['selected_post_types'];

$ai_search_answer = $data->form['ai_search_answer'];
$ai_search_content = $data->form['ai_search_content'];
$ai_search_autocomplete = $data->form['ai_search_autocomplete'];
$ai_search_results_limit = $data->form['ai_search_results_limit'];
$ai_search_trust_level = $data->form['ai_search_trust_level'];
$ai_cache = $data->form['ai_cache'];
$settings = $data->form['ai_omni_setting'];
$logs = $data->form['search_log'];
?>
    <div class="omni-config wrap">

        <div class="omni-config__container">
            <h2><?php esc_html_e('Omnimind Configuration', 'omni-wp-api'); ?></h2>
            <div class="tabset">
                <ul class="tab-control">
                    <li><a class="tab-opener" href="#"><?php esc_html_e('General', 'omni-wp-api'); ?></a></li>
                    <li><a class="tab-opener" href="#"><?php esc_html_e('Content types', 'omni-wp-api'); ?></a></li>
                    <li><a class="tab-opener" href="#"><?php esc_html_e('Indexing', 'omni-wp-api'); ?></a></li>
                    <li><a class="tab-opener" href="#"><?php esc_html_e('Requests', 'omni-wp-api'); ?></a></li>
                    <li><a class="tab-opener" href="#"><?php esc_html_e('Info', 'omni-wp-api'); ?></a></li>
                </ul>
                <div class="tabs-list">
                    <div class="tab-item">
                        <?php if ($api_key_status): ?>
                            <div class="form-row">
                                <div class="form-row__label">
                                    <div class="form-label"><?php esc_html_e('API Key', 'omni-wp-api'); ?></div>
                                </div>
                                <div class="form-row__item">
                                    <div class="api-status">
                                        <input class="form-input" type="text" name="verify_api_key"
                                               value="<?php echo esc_html($omni_api_key); ?>">
                                        <div class="status"><?php echo (esc_attr($api_key_status)) ? '<span class="dashicons dashicons-yes-alt"></span>' : '<span class="dashicons dashicons-dismiss"></span>'; ?></div>
                                    </div>
                                    <div class="form-info"><?php esc_html_e('Your Omnimind API key', 'omni-wp-api'); ?></div>
                                </div>
                            </div>
                            <form method="post">
                                <?php wp_nonce_field('project_name_nonce_action', 'project_name_nonce'); ?>
                                <div class="form-row">
                                    <div class="form-row__label">
                                        <div class="form-label"><?php esc_html_e('Project', 'omni-wp-api'); ?></div>
                                    </div>
                                    <div class="form-row__item">
                                        <div class="inputs-wrap">
                                            <input class="form-input" type="text" name="project_name"
                                                   value="<?php echo esc_html($project_name); ?>">
                                            <?php if ($project_id): ?>
                                                <a href="<?php echo esc_attr($settings) ?>" target="_blank"
                                                   class="btn-omni btn-omni--primary"><span
                                                            class="dashicons dashicons-external"></span> <?php esc_html_e('Settings', 'omni-wp-api') ?>
                                                </a>
                                            <?php else: ?>
                                                <button type="submit" name="send_project_name"
                                                        class="btn-omni btn-omni--primary"><?php esc_html_e('Create', 'omni-wp-api') ?>
                                                </button>
                                            <?php endif ?>
                                        </div>
                                        <div class="form-info"><?php esc_html_e('Your Omnimind Project name', 'omni-wp-api') ?></div>
                                    </div>
                                </div>
                            </form>
                            <form method="post">
                                <?php wp_nonce_field('project_config_nonce_action', 'project_config_nonce'); ?>
                                <div class="form-row">
                                    <div class="form-row__label">
                                        <div class="form-label"><?php esc_html_e('Options', 'omni-wp-api') ?></div>
                                    </div>
                                    <div class="form-row__item">
                                        <ul class="checkbox-list">
                                            <li>
                                                <label class="checkbox-holder">
                                                    <input name="ai_search_answer" type="checkbox"
                                                           class="checkbox" <?php checked(1, esc_html($ai_search_answer)); ?> />
                                                    <span class="checkbox-item">&nbsp;</span>
                                                    <span class="checkbox-label"><?php esc_html_e('AI answer', 'omni-wp-api') ?></span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="checkbox-holder">
                                                    <input name="ai_search_content" type="checkbox"
                                                           class="checkbox" <?php checked(1, esc_html($ai_search_content)); ?> />
                                                    <span class="checkbox-item">&nbsp;</span>
                                                    <span class="checkbox-label"><?php esc_html_e('Content', 'omni-wp-api') ?></span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="checkbox-holder">
                                                    <input name="ai_search_autocomplete" type="checkbox"
                                                           class="checkbox" <?php checked(1, esc_html($ai_search_autocomplete)); ?> />
                                                    <span class="checkbox-item">&nbsp;</span>
                                                    <span class="checkbox-label"><?php esc_html_e('Autocomplete', 'omni-wp-api') ?></span>
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-columns">
                                    <div class="form-col">
                                        <div class="form-row">
                                            <div class="form-row__label">
                                                <div class="form-label"><?php esc_html_e('Results limit', 'omni-wp-api') ?></div>
                                            </div>
                                            <div class="form-row__item">
                                                <input class="form-input" type="number" name="ai_search_results_limit"
                                                       value="<?php echo (esc_attr($ai_search_results_limit)) ? esc_attr($ai_search_results_limit) : '5'; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-row">
                                            <div class="form-row__label">
                                                <div class="form-label"><?php esc_html_e('Proof level', 'omni-wp-api') ?></div>
                                            </div>
                                            <div class="form-row__item">
                                                <input class="form-input" type="number" step=".1" max=".9" min=".1"
                                                       name="ai_search_trust_level"
                                                       value="<?php echo esc_attr($ai_search_trust_level) ?? 0.6 ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-block">
                                    <div class="form-block__title">
                                        <span><?php esc_html_e('Cache', 'omni-wp-api') ?></span></div>
                                    <div class="form-block__frame">
                                        <div class="form-block__wrap">
                                            <div class="form-block__content">
                                                <p><?php esc_html_e('Output results can be cached to prevent numerous requests to AI and save
                                                the costs. If you set it to 0 no cache is going to be applied', 'omni-wp-api') ?></p>
                                                <div class="cache-input">
                                                    <div class="form-label"><?php esc_html_e('Cache period', 'omni-wp-api') ?></div>
                                                    <input class="form-input" type="number" min="1" name="ai_cache"
                                                           value="<?php echo(esc_attr($ai_cache) ? esc_attr($ai_cache) : '24'); ?>">
                                                    <div class="cache-input__info"><?php esc_html_e('hours', 'omni-wp-api') ?></div>
                                                </div>
                                            </div>
                                            <div class="form-block__button">
                                                <button name="purge_cache" id="purge_cache_button"
                                                        class="btn-omni btn-omni--warning btn-omni--block">
                                                    <svg class="svg-icon" width="16" height="16">
                                                        <use xlink:href="<?php echo esc_html(plugins_url('../../assets/images/icons.svg#icon-purge', dirname(__FILE__))); ?>"></use>
                                                    </svg>
                                                    <span><?php esc_html_e('Purge cache', 'omni-wp-api') ?></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button name="save_general" type="submit" class="btn-omni btn-omni--primary">
                                    <svg class="svg-icon" width="16" height="16">
                                        <use xlink:href="<?php echo esc_html(plugins_url('../../assets/images/icons.svg#icon-save', dirname(__FILE__))); ?>"></use>
                                    </svg>
                                    <span><?php esc_html_e('Save', 'omni-wp-api') ?></span>
                                </button>
                            </form>
                        <?php else: ?>
                            <form method="post">
                                <?php wp_nonce_field('project_apikey_nonce_action', 'project_apikey_nonce'); ?>
                                <div class="form-row">
                                    <div class="form-row__label">
                                        <div class="form-label"><?php esc_html_e('API Key', 'omni-wp-api') ?></div>
                                    </div>
                                    <div class="form-row__item">
                                        <div class="api-verify">
                                            <input class="form-input" type="text" name="verify_api_key"
                                                   value="<?php echo esc_html($omni_api_key); ?>">
                                            <div class="status"><?php echo (esc_attr($api_key_status)) ? '<span class="dashicons dashicons-yes-alt"></span>' : '<span class="dashicons dashicons-dismiss"></span>'; ?></div>
                                            <button type="submit" name="check_api_key"
                                                    class="btn-omni btn-omni--primary">
                                                <?php esc_html_e('Verify', 'omni-wp-api') ?>
                                            </button>
                                        </div>
                                        <div class="form-info">
                                            <ol>
                                                <li><a href="#">Sign Up</a> at Omnimind</li>
                                                <li>Get a key at <a href="#">Settings - Profile - API Keys</a> section
                                                </li>
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
                            <?php wp_nonce_field('project_content_nonce_action', 'project_content_nonce'); ?>

                            <p><?php esc_html_e('Select the content types in your project that will be included in the search results and be
                            used to construct AI answers', 'omni-wp-api') ?></p>

                            <?php
                            $args = array(
                                'public' => true,
                                // '_builtin' => false,
                            );
                            $output = 'objects';
                            $operator = 'and';
                            $post_types = get_post_types($args, $output, $operator);
                            unset($post_types['attachment']);
                            $selected_fields = get_option('_omni_selected_fields_option');

                            if ($post_types) {
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
                                                <input name="post_types[]"
                                                       value="<?php echo esc_attr($post_type->name); ?>"
                                                       type="checkbox"
                                                       class="checkbox" <?php echo esc_attr($checked); ?> />
                                                <span class="checkbox-item">&nbsp;</span>
                                                <span class="checkbox-label"><?php echo esc_html($label_with_count); ?></span>
                                            </label>
                                            <div class="attributes-wrap">
                                                <?php
                                                $post_ids = get_posts(
                                                    [
                                                        'numberposts' => -1,
                                                        'post_type' => $post_type->name,
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
                                                    $jsonData = wp_json_encode(array_keys($custom_fields));
                                                } else {
                                                    $jsonData = wp_json_encode([]);
                                                }
                                                ?>
                                                <div class="autocomplete-data"
                                                     data-post-type="<?php echo esc_attr($post_type->name); ?>"
                                                     style="display:none;">
                                                    <?php echo esc_attr($jsonData); ?>
                                                </div>
                                                <table class="attributes-table">
                                                    <tr>
                                                        <th><?php esc_html_e('Attribute', 'omni-wp-api'); ?></th>
                                                        <th><?php esc_html_e('Searchable', 'omni-wp-api'); ?></th>
                                                        <th><?php esc_html_e('Label', 'omni-wp-api'); ?></th>
                                                    </tr>


                                                    <?php foreach ($additional_fields as $key => $values): ?>
                                                        <tr>
                                                            <td><?php echo esc_html($key); ?></td>
                                                            <td>

                                                                <input class="form-input" type="hidden"
                                                                       name="post_type_fields[<?php echo esc_attr($post_type->name); ?>][<?php echo esc_attr($key); ?>][name]"
                                                                       value="<?php echo esc_attr($key); ?>">

                                                                <?php
                                                                if (isset($selected_fields[$post_type->name][$key]['status']) && $selected_fields[$post_type->name][$key]['status'] == 1) {
                                                                    echo '<input name="post_type_fields[' . esc_attr($post_type->name) . '][' . esc_attr($key) . '][status]" value="1" type="checkbox" class="checkbox" checked />';
                                                                } else {
                                                                    echo '<input name="post_type_fields[' . esc_attr($post_type->name) . '][' . esc_attr($key) . '][status]" value="1" type="checkbox" class="checkbox" />';
                                                                }
                                                                ?>

                                                            </td>
                                                            <td><input class="form-input" type="text"
                                                                       name="post_type_fields[<?php echo esc_attr($post_type->name); ?>][<?php echo esc_attr($key); ?>][label]"
                                                                       value="<?php echo isset($selected_fields[$post_type->name][$key]['label']) ? esc_attr($selected_fields[$post_type->name][$key]['label']) : ''; ?>">
                                                            </td>

                                                        </tr>

                                                    <?php endforeach ?>
                                                    <?php
                                                    // additional fields
                                                    if (isset($selected_fields[$post_type->name])) {
                                                        foreach ($selected_fields[$post_type->name] as $key => $values) {
                                                            $key = esc_attr($key);
                                                            if ($key == 'advanced-title-columns' || $key == 'advanced-metadata-columns' || $key == 'Title' || $key == 'Content' || $key == 'Author') {
                                                                continue;
                                                            }
                                                            echo '<tr>';
                                                            echo '<td>' . esc_html($key) . '</td>';
                                                            echo '<td>';
                                                            echo '<input class="form-input" type="hidden" name="post_type_fields[' . esc_attr($post_type->name) . '][' . esc_attr($key) . '][name]" value="' . esc_attr($key) . '">';
                                                            $checked = (isset($values['status']) && $values['status'] == 1) ? 'checked' : '';
                                                            echo '<input name="post_type_fields[' . esc_attr($post_type->name) . '][' . esc_attr($key) . '][status]" value="1" type="checkbox" class="checkbox" ' . esc_html($checked) . ' />';
                                                            echo '</td>';
                                                            echo '<td><input class="form-input" type="text" name="post_type_fields[' . esc_attr($post_type->name) . '][' . esc_attr($key) . '][label]" value="' . (isset($values['label']) ? esc_attr($values['label']) : '') . '"></td>';
                                                            echo '</tr>';
                                                        }
                                                    }
                                                    ?>

                                                </table>
                                                <div class="custom-field">
                                                    <!-- <input type="text" class="new-field-name autoComplete" name=""> -->
                                                    <input type="text" class="new-field-name"
                                                           data-post-type="<?php echo esc_attr($post_type->name); ?>">

                                                    <button type="button" class="add-field btn-omni btn-omni--success"
                                                            data-post-type="<?php echo esc_attr($post_type->name); ?>"><?php esc_html_e('add
                                                    field', 'omni-wp-api'); ?>
                                                    </button>
                                                </div>

                                                <div class="advanced-settings">
                                                    <button class="advanced-settings__opener btn-omni btn-omni--primary">
                                                        <?php esc_html_e('Advanced Settings', 'omni-wp-api'); ?>
                                                    </button>
                                                    <div class="advanced-settings__content">
                                                        <div class="advanced-settings__row">
                                                            <div class="advanced-settings__label"> <?php esc_html_e('Select Title Columns', 'omni-wp-api'); ?></div>
                                                            <div class="advanced-settings__input">
                                                                <select class="js-example-basic-multiple"
                                                                        name="post_type_fields[<?php echo esc_attr($post_type->name); ?>][advanced-title-columns][]"
                                                                        multiple="multiple">
                                                                    <?php
                                                                    $saved_title_columns = isset($selected_fields[$post_type->name]['advanced-title-columns']) ? $selected_fields[$post_type->name]['advanced-title-columns'] : array();
                                                                    foreach ($additional_fields as $key => $values):
                                                                        $selected = in_array($key, $saved_title_columns) ? 'selected' : '';
                                                                        ?>
                                                                        <option value="<?php echo esc_html($key); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($key); ?></option>
                                                                    <?php endforeach ?>
                                                                    <?php if (isset($selected_fields[$post_type->name])): ?>
                                                                        <?php foreach ($selected_fields[$post_type->name] as $key => $values): ?>
                                                                            <?php
                                                                            if ($key == 'advanced-title-columns' || $key == 'advanced-metadata-columns' || $key == 'Title' || $key == 'Content' || $key == 'Author') {
                                                                                continue;
                                                                            }
                                                                            $selected = in_array($key, $saved_title_columns) ? 'selected' : '';
                                                                            ?>
                                                                            <option value="<?php echo esc_html($key); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($key); ?></option>
                                                                        <?php endforeach ?>
                                                                    <?php endif ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="advanced-settings__row">
                                                            <div class="advanced-settings__label"> <?php esc_html_e('Select Metadata Columns', 'omni-wp-api'); ?>
                                                            </div>
                                                            <div class="advanced-settings__input">
                                                                <select class="js-example-basic-multiple"
                                                                        name="post_type_fields[<?php echo esc_attr($post_type->name); ?>][advanced-metadata-columns][]"
                                                                        multiple="multiple">
                                                                    <?php
                                                                    $saved_title_columns = isset($selected_fields[$post_type->name]['advanced-metadata-columns']) ? $selected_fields[$post_type->name]['advanced-metadata-columns'] : array();
                                                                    foreach ($additional_fields as $key => $values):
                                                                        $selected = in_array($key, $saved_title_columns) ? 'selected' : '';
                                                                        ?>
                                                                        <option value="<?php echo esc_html($key); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($key); ?></option>
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
                                <?php
                            }
                            ?>
                            <p><?php esc_html_e('After making changes, it is highly advisable to run a re-synchronization at the <strong>Indexing</strong>
                            tab.', 'omni-wp-api'); ?></p>
                            <button <?php echo ($api_key_status) ? '' : 'disabled'; ?> type="submit"
                                                                                       name="save_post_types"
                                                                                       class="btn-omni btn-omni--primary">
                                <svg class="svg-icon" width="16" height="16">
                                    <use xlink:href="<?php echo esc_html(plugins_url('../../assets/images/icons.svg#icon-save', dirname(__FILE__))); ?>"></use>
                                </svg>
                                <span><?php esc_html_e('Save Post Types', 'omni-wp-api'); ?></span>
                            </button>
                        </form>
                    </div>
                    <div class="tab-item">
                        <?php
                        $selected_fields = get_option('_omni_selected_fields_option');
                        $selected_post_types = get_option('_omni_selected_post_types');
                        ?>

                        <form method="post" id="syncForm">
                            <?php wp_nonce_field('project_sync_nonce_action', 'project_sync_nonce'); ?>

                            <div class="form-block">
                                <div class="form-block__title">
                                    <span><?php esc_html_e('Sync Settings', 'omni-wp-api'); ?></span>
                                </div>

                                <div class="form-block__frame">
                                    <div class="form-block__wrap">
                                        <div class="form-block__content">
                                            <p><?php esc_html_e('If you notice missing information in your search outcomes or if you\'ve
                                            recently incorporated new custom content categories to your platform, it\'s
                                            advisable to initiate a synchronization to update these modifications.', 'omni-wp-api'); ?></p>

                                            <?php // Get last Sync Date
                                            $sync_date = get_option('_omni_last_sync_date'); ?>

                                            <p>Last sync status:
                                                <span id="last-sync-date"
                                                      style="<?php echo !empty($sync_date) ? 'color: green;' : '' ?>">
                                                <?php echo !empty($sync_date) ? esc_html($sync_date) : 'N/A'; ?>
                                            </span>
                                            </p>
                                            <!-- Progress bar -->
                                            <div class="progress-bar__wrap omni-progress--hide"
                                                 style="margin: 20px 0;width:100%;">
                                                <p>Progress: <span id="remaining_time"></span></p>
                                                <progress id="progress-bar" value="0" max="100"></progress>
                                            </div>
                                            <div id="progress-bar__res"></div>
                                        </div>
                                        <div class="form-block__button">
                                            <button <?php echo ($api_key_status) ? '' : 'disabled'; ?>
                                                    name="send_post_types" type="submit" id="sync-button"
                                                    class="btn-omni btn-omni--primary btn-omni--block">
                                                <svg class="svg-icon" width="16" height="16">
                                                    <use xlink:href="<?php echo esc_html(plugins_url('../../assets/images/icons.svg#icon-sync', dirname(__FILE__))); ?>"></use>
                                                </svg>
                                                <span><?php esc_html_e('Sync Now', 'omni-wp-api'); ?></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form method="post">
                            <?php wp_nonce_field('project_wipe_nonce_action', 'project_wipe_nonce'); ?>

                            <div class="form-block">
                                <div class="form-block__title">
                                    <span><?php esc_html_e('Clear and Reinitialize', 'omni-wp-api'); ?></span>
                                </div>
                                <div class="form-block__frame">
                                    <div class="form-block__wrap">
                                        <div class="form-block__content">
                                            <p><?php esc_html_e('Should you continue to face discrepancies in your search outcomes, consider
                                            starting a thorough re-synchronization.', 'omni-wp-api'); ?> </p>
                                            <p>
                                                <?php esc_html_e('Executing this will remove all indexed information from OmniMind, but your
                                            WordPress site remains unaffected. The entire process might span several
                                            hours, contingent on the volume of content awaiting', 'omni-wp-api'); ?></p>
                                        </div>
                                        <div class="form-block__button">
                                            <button
                                                <?php echo ($api_key_status) ? '' : 'disabled'; ?>name="reindex_project"
                                                type="submit"
                                                class="btn-omni btn-omni--warning btn-omni--block">
                                                <svg class="svg-icon" width="16" height="16">
                                                    <use xlink:href="<?php echo esc_html(plugins_url('../../assets/images/icons.svg#icon-purge', dirname(__FILE__))); ?>"></use>
                                                </svg>
                                                <span><?php esc_html_e('Re-Index', 'omni-wp-api'); ?></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form method="post">
                            <?php wp_nonce_field('project_purge_nonce_action', 'project_purge_nonce'); ?>

                            <div class="form-block">
                                <div class="form-block__title">
                                    <span><?php esc_html_e('Purge and change API key', 'omni-wp-api'); ?></span>
                                </div>
                                <div class="form-block__frame">
                                    <div class="form-block__wrap">
                                        <div class="form-block__content">
                                            <p><?php esc_html_e('Clicking it you', 'omni-wp-api'); ?> <span
                                                        style="color: red;"><?php esc_html_e('remove', 'omni-wp-api'); ?> </span> <?php esc_html_e('your project and
                                            purge all indexes at Omnimind. It doesn\'t affect you Wordpress data. This
                                            action is not reversible.', 'omni-wp-api'); ?></p>
                                            <p><?php esc_html_e('You can setup a new API key and start from', 'omni-wp-api'); ?></p>
                                        </div>
                                        <div class="form-block__button">
                                            <button <?php echo ($api_key_status) ? '' : 'disabled'; ?>
                                                    name="delete_project"
                                                    type="submit"
                                                    class="btn-omni btn-omni--danger btn-omni--block">
                                                <svg class="svg-icon" width="16" height="16">
                                                    <use xlink:href="<?php echo esc_html(plugins_url('../../assets/images/icons.svg#icon-delete', dirname(__FILE__))); ?>"></use>
                                                </svg>
                                                <span><?php esc_html_e('Delete', 'omni-wp-api'); ?></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-item">
                        <p><?php esc_html_e('Here you can see you users search requests', 'omni-wp-api'); ?></p>

                        <table id="request_table" class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th><?php esc_html_e('Date', 'omni-wp-api') ?></th>
                                <th><?php esc_html_e('Question', 'omni-wp-api') ?></th>
                                <th><?php esc_html_e('Answer', 'omni-wp-api') ?></th>
                                <th><?php esc_html_e('Content', 'omni-wp-api') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($logs as $log) {
                                $datetime = gmdate("d/m/Y H:i", $log['date'] / 1000);
                                $content = [];
                                foreach ($log['data'] ?? [] as $datum) {
                                    $content[] .= '<a href="' . esc_html($datum->url) . '">' . esc_html($datum->title) . '</a>';
                                }
                                echo '<tr>';
                                echo '<td>' . esc_html($datetime) . '</td>';
                                echo '<td>' . esc_html($log['question']) . '</td>';
                                echo '<td>' . esc_html($log['answer']) . '</td>';
                                echo '<td class="data-links">' . implode(', ', $content) . '</td>';
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th><?php esc_html_e('Date', 'omni-wp-api') ?></th>
                                <th><?php esc_html_e('Question', 'omni-wp-api') ?></th>
                                <th><?php esc_html_e('Answer', 'omni-wp-api') ?></th>
                                <th><?php esc_html_e('Content', 'omni-wp-api') ?></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="tab-item">
                        <p><?php esc_html_e('Use the shortcode [omni_search] to display the search field on the website page.', 'omni-wp-api'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="omniAlertModal" class="omni-modal">
    <span class="omni-modal__close">
        <svg class="svg-icon" width="16" height="16">
            <use xlink:href="<?php echo esc_html(plugins_url('../../assets/images/icons.svg#icon-close', dirname(__FILE__))); ?>"></use>
        </svg>
    </span>

        <div class="omni-modal-content">
            <p class="omni-modal__text"></p>
        </div>
    </div>
<?php if ($data->form['popup']) { ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            omniAlertHandler(
                '<?php echo esc_html($data->form['popup']['status']) ?>',
                '<?php echo esc_html($data->form['popup']['message']) ?>'
            )
        });
    </script>
<?php } ?>