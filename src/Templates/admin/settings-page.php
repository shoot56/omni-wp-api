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
?>
<div class="omni-config wrap">

    <div class="omni-config__container">
        <h2><?php _e('Omnimind Configuration', 'omni'); ?></h2>
        <div class="tabset">
            <ul class="tab-control">
                <li><a class="tab-opener" href="#"><?php _e('General', 'omni'); ?></a></li>
                <li><a class="tab-opener" href="#"><?php _e('Content types', 'omni'); ?></a></li>
                <li><a class="tab-opener" href="#"><?php _e('Indexing', 'omni'); ?></a></li>
                <li><a class="tab-opener" href="#"><?php _e('Requests', 'omni'); ?></a></li>
            </ul>
            <div class="tabs-list">
                <div class="tab-item">
                    <?php if ($api_key_status): ?>
                        <div class="form-row">
                            <div class="form-row__label">
                                <div class="form-label"><?php _e('API Key', 'omni'); ?></div>
                            </div>
                            <div class="form-row__item">
                                <div class="api-status">
                                    <input class="form-input" type="text" name="verify_api_key"
                                           value="<?php echo esc_html($omni_api_key); ?>">
                                    <div class="status"><?php echo (esc_attr($api_key_status)) ? '<span class="dashicons dashicons-yes-alt"></span>' : '<span class="dashicons dashicons-dismiss"></span>'; ?></div>
                                </div>
                                <div class="form-info"><?php _e('Your Omnimind API key', 'omni'); ?></div>
                            </div>
                        </div>
                        <form method="post">
                            <div class="form-row">
                                <div class="form-row__label">
                                    <div class="form-label"><?php _e('Project', 'omni'); ?></div>
                                </div>
                                <div class="form-row__item">
                                    <div class="inputs-wrap">
                                        <input class="form-input" type="text" name="project_name"
                                               value="<?php echo esc_html($project_name); ?>">
                                        <?php if ($project_id): ?>
                                            <a href="https://google.com" target="_blank"
                                               class="btn-omni btn-omni--primary"><span
                                                        class="dashicons dashicons-external"></span> <?php _e('Settings', 'omni') ?>
                                            </a>
                                        <?php else: ?>
                                            <button type="submit" name="send_project_name"
                                                    class="btn-omni btn-omni--primary"><?php _e('Create', 'omni') ?>
                                            </button>
                                        <?php endif ?>
                                    </div>
                                    <div class="form-info"><?php _e('Your Omnimind Project name', 'omni') ?></div>
                                </div>
                            </div>
                        </form>
                        <form method="post">
                            <div class="form-row">
                                <div class="form-row__label">
                                    <div class="form-label"><?php _e('Options', 'omni') ?></div>
                                </div>
                                <div class="form-row__item">
                                    <ul class="checkbox-list">
                                        <li>
                                            <label class="checkbox-holder">
                                                <input name="ai_search_answer" type="checkbox"
                                                       class="checkbox" <?php checked(1, esc_html($ai_search_answer)); ?> />
                                                <span class="checkbox-item">&nbsp;</span>
                                                <span class="checkbox-label"><?php _e('AI answer', 'omni') ?></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="checkbox-holder">
                                                <input name="ai_search_content" type="checkbox"
                                                       class="checkbox" <?php checked(1, esc_html($ai_search_content)); ?> />
                                                <span class="checkbox-item">&nbsp;</span>
                                                <span class="checkbox-label"><?php _e('Content', 'omni') ?></span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="checkbox-holder">
                                                <input name="ai_search_autocomplete" type="checkbox"
                                                       class="checkbox" <?php checked(1, esc_html($ai_search_autocomplete)); ?> />
                                                <span class="checkbox-item">&nbsp;</span>
                                                <span class="checkbox-label"><?php _e('Autocomplete', 'omni') ?></span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-columns">
                                <div class="form-col">
                                    <div class="form-row">
                                        <div class="form-row__label">
                                            <div class="form-label"><?php _e('Results limit', 'omni') ?></div>
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
                                            <div class="form-label"><?php _e('Trust level', 'omni') ?></div>
                                        </div>
                                        <div class="form-row__item">
                                            <input class="form-input" type="number" name="ai_search_trust_level"
                                                   value="<?php echo(esc_attr($ai_search_trust_level) ? esc_attr($ai_search_trust_level) : '5'); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-block">
                                <div class="form-block__title"><span><?php _e('Cache', 'omni') ?></span></div>
                                <div class="form-block__frame">
                                    <div class="form-block__wrap">
                                        <div class="form-block__content">
                                            <p><?php _e('Output results can be cached to prevent numerous requests to AI and save
                                                the costs. If you set it to 0 no cache is going to be applied', 'omni') ?></p>
                                            <div class="cache-input">
                                                <div class="form-label"><?php _e('Cache period', 'omni') ?></div>
                                                <input class="form-input" type="number" name="ai_cache"
                                                       value="<?php echo(esc_attr($ai_cache) ? esc_attr($ai_cache) : '24'); ?>">
                                                <div class="cache-input__info"><?php _e('hours', 'omni') ?></div>
                                            </div>
                                        </div>
                                        <div class="form-block__button">
                                            <button class="btn-omni btn-omni--warning btn-omni--block">
                                                <svg class="svg-icon" width="16" height="16">
                                                    <use xlink:href="<?php echo plugins_url('../../assets/images/icons.svg#icon-purge', dirname(__FILE__)); ?>"></use>
                                                </svg>
                                                <span><?php _e('Purge cache', 'omni') ?></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button name="save_general" type="submit" class="btn-omni btn-omni--primary">
                                <svg class="svg-icon" width="16" height="16">
                                    <use xlink:href="<?php echo plugins_url('../../assets/images/icons.svg#icon-save', dirname(__FILE__)); ?>"></use>
                                </svg>
                                <span><?php _e('Save', 'omni') ?></span>
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="post">
                            <div class="form-row">
                                <div class="form-row__label">
                                    <div class="form-label"><?php _e('API Key', 'omni') ?></div>
                                </div>
                                <div class="form-row__item">
                                    <div class="api-verify">
                                        <input class="form-input" type="text" name="verify_api_key"
                                               value="<?php echo esc_html($omni_api_key); ?>">
                                        <div class="status"><?php echo (esc_attr($api_key_status)) ? '<span class="dashicons dashicons-yes-alt"></span>' : '<span class="dashicons dashicons-dismiss"></span>'; ?></div>
                                        <button type="submit" name="check_api_key" class="btn-omni btn-omni--primary">
                                            <?php _e('Verify', 'omni') ?>
                                        </button>
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

                        <p><?php _e('Select the content types in your project that will be included in the search results and be
                            used to construct AI answers', 'omni') ?></p>

                        <?php
                        $args = array(
                            'public' => true,
                            // '_builtin' => false,
                        );
                        $output = 'objects';
                        $operator = 'and';
                        $post_types = get_post_types($args, $output, $operator);
                        unset($post_types['attachment']);
                        $selected_fields = esc_attr(get_option('_omni_selected_fields_option'));

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
                                            <input name="post_types[]" value="<?php echo esc_attr($post_type->name); ?>"
                                                   type="checkbox" class="checkbox" <?php echo esc_attr($checked); ?> />
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
                                                $jsonData = json_encode(array_keys($custom_fields));
                                            } else {
                                                $jsonData = json_encode([]);
                                            }
                                            ?>
                                            <div class="autocomplete-data"
                                                 data-post-type="<?php echo esc_attr($post_type->name); ?>"
                                                 style="display:none;">
                                                <?php echo esc_attr($jsonData); ?>
                                            </div>
                                            <table class="attributes-table">
                                                <tr>
                                                    <th><?php _e('Attribute', 'omni'); ?></th>
                                                    <th><?php _e('Searchable', 'omni'); ?></th>
                                                    <th><?php _e('Label', 'omni'); ?></th>
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
                                                                echo '<input name="post_type_fields[' . esc_attr($post_type->name) . '][' . $key . '][status]" value="1" type="checkbox" class="checkbox" checked />';
                                                            } else {
                                                                echo '<input name="post_type_fields[' . esc_attr($post_type->name) . '][' . $key . '][status]" value="1" type="checkbox" class="checkbox" />';
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
                                                <input type="text" class="new-field-name"
                                                       data-post-type="<?php echo esc_attr($post_type->name); ?>">

                                                <button type="button" class="add-field btn-omni btn-omni--success"
                                                        data-post-type="<?php echo esc_attr($post_type->name); ?>"><?php _e('add
                                                    field', 'omni'); ?>
                                                </button>
                                            </div>

                                            <div class="advanced-settings">
                                                <button class="advanced-settings__opener btn-omni btn-omni--primary">
                                                    <?php _e('Advanced Settings', 'omni'); ?>
                                                </button>
                                                <div class="advanced-settings__content">
                                                    <div class="advanced-settings__row">
                                                        <div class="advanced-settings__label"> <?php _e('Select Title Columns', 'omni'); ?></div>
                                                        <div class="advanced-settings__input">
                                                            <select class="js-example-basic-multiple"
                                                                    name="post_type_fields[<?php echo esc_attr($post_type->name); ?>][advanced-title-columns][]"
                                                                    multiple="multiple">
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
                                                                        <option value="<?php echo esc_html($key); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($key); ?></option>
                                                                    <?php endforeach ?>
                                                                <?php endif ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="advanced-settings__row">
                                                        <div class="advanced-settings__label"> <?php _e('Select Metadata Columns', 'omni'); ?>
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
                        <p><?php _e('After making changes, it is highly advisable to run a re-synchronization at the <strong>Indexing</strong>
                            tab.', 'omni'); ?></p>
                        <button <?php echo ($api_key_status) ? '' : 'disabled'; ?> type="submit" name="save_post_types"
                                                                                   class="btn-omni btn-omni--primary">
                            <svg class="svg-icon" width="16" height="16">
                                <use xlink:href="<?php echo plugins_url('../../assets/images/icons.svg#icon-save', dirname(__FILE__)); ?>"></use>
                            </svg>
                            <span><?php _e('Save Post Types', 'omni'); ?></span>
                        </button>
                    </form>
                </div>
                <div class="tab-item">
                    <?php
                    $selected_fields = esc_attr(get_option('_omni_selected_fields_option'));
                    $selected_post_types = esc_attr(get_option('_omni_selected_post_types'));
                    ?>

                    <form method="post" id="syncForm">
                        <div class="form-block">
                            <div class="form-block__title">
                                <span><?php _e('Sync Settings', 'omni'); ?></span>
                            </div>

                            <div class="form-block__frame">
                                <div class="form-block__wrap">
                                    <div class="form-block__content">
                                        <p><?php _e('If you notice missing information in your search outcomes or if you\'ve
                                            recently incorporated new custom content categories to your platform, it\'s
                                            advisable to initiate a synchronization to update these modifications.', 'omni'); ?></p>

                                        <?php // Get last Sync Date
                                        $sync_date = get_option('_omni_last_sync_date'); ?>

                                        <p>Last sync status:
                                            <span id="last-sync-date"
                                                  style="<?= !empty($sync_date) ? 'color: green;' : '' ?>">
                                                <?= !empty($sync_date) ? $sync_date : 'N/A'; ?>
                                            </span>
                                        </p>
                                    </div>

                                    <div class="form-block__button">
                                        <button <?php echo ($api_key_status) ? '' : 'disabled'; ?>
                                                name="send_post_types" type="submit" id="sync-button"
                                                class="btn-omni btn-omni--primary btn-omni--block">
                                            <svg class="svg-icon" width="16" height="16">
                                                <use xlink:href="<?php echo plugins_url('../../assets/images/icons.svg#icon-sync', dirname(__FILE__)); ?>"></use>
                                            </svg>
                                            <span><?php _e('Sync Now', 'omni'); ?></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form method="post">
                        <div class="form-block">
                            <div class="form-block__title"><span><?php _e('Clear and Reinitialize', 'omni'); ?></span>
                            </div>
                            <div class="form-block__frame">
                                <div class="form-block__wrap">
                                    <div class="form-block__content">
                                        <p><?php _e('Should you continue to face discrepancies in your search outcomes, consider
                                            starting a thorough re-synchronization.', 'omni'); ?> </p>
                                        <p>
                                            <?php _e('Executing this will remove all indexed information from OmniMind, but your
                                            WordPress site remains unaffected. The entire process might span several
                                            hours, contingent on the volume of content awaiting', 'omni'); ?></p>
                                    </div>
                                    <div class="form-block__button">
                                        <button <?php echo ($api_key_status) ? '' : 'disabled'; ?>name="reindex_project"
                                                type="submit"
                                                class="btn-omni btn-omni--warning btn-omni--block">
                                            <svg class="svg-icon" width="16" height="16">
                                                <use xlink:href="<?php echo plugins_url('../../assets/images/icons.svg#icon-purge', dirname(__FILE__)); ?>"></use>
                                            </svg>
                                            <span><?php _e('Re-Index', 'omni'); ?></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form method="post">
                        <div class="form-block">
                            <div class="form-block__title"><span><?php _e('Purge and change API key', 'omni'); ?></span>
                            </div>
                            <div class="form-block__frame">
                                <div class="form-block__wrap">
                                    <div class="form-block__content">
                                        <p><?php _e('Clicking it you', 'omni'); ?> <span
                                                    style="color: red;"><?php _e('remove', 'omni'); ?> </span> <?php _e('your project and
                                            purge all indexes at Omnimind. It doesn\'t affect you Wordpress data. This
                                            action is not reversible.', 'omni'); ?></p>
                                        <p><?php _e('You can setup a new API key and start from', 'omni'); ?></p>
                                    </div>
                                    <div class="form-block__button">
                                        <button <?php echo ($api_key_status) ? '' : 'disabled'; ?> name="delete_project"
                                                                                                   type="submit"
                                                                                                   class="btn-omni btn-omni--danger btn-omni--block">
                                            <svg class="svg-icon" width="16" height="16">
                                                <use xlink:href="<?php echo plugins_url('../../assets/images/icons.svg#icon-delete', dirname(__FILE__)); ?>"></use>
                                            </svg>
                                            <span><?php _e('Delete', 'omni'); ?></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-item">
                    <p><?php _e('Here you can see you users search requests', 'omni'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="omniAlertModal" class="omni-modal">
    <span class="omni-modal__close">
        <svg class="svg-icon" width="16" height="16">
            <use xlink:href="<?php echo plugins_url('../../assets/images/icons.svg#icon-close', dirname(__FILE__)); ?>"></use>
        </svg>
    </span>

    <div class="omni-modal-content">
        <p class="omni-modal__text"></p>
    </div>
</div>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        omniAlertHandler(
            <?php echo esc_html($data->form['popup']['status']) ?>,
            <?php echo esc_html($data->form['popup']['message']) ?>
        );
    });
</script>