<?php

namespace Procoders\Omni\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Procoders\Omni\ClassLoader as Loader;
use Procoders\Omni\Includes\api as Api;
use Procoders\Omni\Includes\debugger as debugger;
class ClassAdmin
{
    private $template;
    private $api;
    private $message;

    public function __construct()
    {
        $this->template = new Loader();
        $this->api = new Api();
        $this->debug = new debugger();
    }

    private function check_user_permission(): bool
    {
        return current_user_can('manage_options');
    }

    public function omni_settings_page()
    {
        if (!$this->check_user_permission()) {
            $this->render_message(__('Permission error'), 'admin/error');
            return;
        }

        $this->handle_post_requests();
        $form = $this->get_form();

        $this->template->set_template_data(
            array(
                'template' => $this->template,
                'form' => $form)
        )->get_template_part('admin/settings-page');
    }

    public function handle_post_requests()
    {
        if (isset($_POST['check_api_key'])) {
            $this->handle_api_key();
        }

        if (isset($_POST['save_post_types'])) {
            $this->handle_save_post_types();
        }

        if (isset($_POST['send_post_types'])) {
            $this->handle_send_post_types();
        }

        if (isset($_POST['send_project_name'])) {
            $this->handle_send_project_name();
        }

        if (isset($_POST['delete_project'])) {
            $this->handle_delete_project();
        }

        if (isset($_POST['reindex_project'])) {
            $this->handle_reindex_project();
        }

        if (isset($_POST['save_general'])) {
            $this->handle_save_general();
        }
    }

    public function get_form()
    {
        return array(
            "selected_post_types" => get_option('_omni_selected_post_types', array()),
            'api_key_status' => get_option('_omni_api_key_status'),
            'omni_api_key' => get_option('_omni_api_key'),
            'project_name' => get_option('_omni_project_name'),
            'project_id' => get_option('_omni_project_id'),
            'ai_search_answer' => get_option('_omni_ai_search_answer'),
            'ai_search_content' => get_option('_omni_ai_search_content'),
            'ai_search_autocomplete' => get_option('_omni_ai_search_autocomplete'),
            'ai_search_results_limit' => get_option('_omni_ai_search_results_limit'),
            'ai_search_trust_level' => get_option('_omni_ai_search_trust_level'),
            'ai_cache' => get_option('_omni_ai_cache'),
            'popup' => $this->message,
        );
    }

    /**
     * @return void
     */
    public function add_omni_columns_to_post_types(): void
    {
        $selected_post_types = get_option('_omni_selected_post_types');
        if (is_array($selected_post_types) && !empty($selected_post_types)) {
            foreach ($selected_post_types as $post_type) {
                add_filter("manage_{$post_type}_posts_columns", array($this, 'add_omni_column'));
                add_action("manage_{$post_type}_posts_custom_column", array($this, 'omni_column_content'), 10, 2);
            }
        }
    }

    /**
     * @return void
     */
    public function add_quick_and_bulk_edit_to_post_types(): void
    {
        $selected_post_types = get_option('_omni_selected_post_types');

        if (is_array($selected_post_types) && !empty($selected_post_types)) {
            foreach ($selected_post_types as $post_type) {
                add_action("bulk_edit_custom_box", array($this, 'omni_edit_exclude_function'), 10, 2);
                add_action("quick_edit_custom_box", array($this, 'omni_edit_exclude_function'), 10, 2);
            }
        }
    }

    /**
     * @param $column_name
     * @param $post_type
     *
     * @return void
     */
    public function omni_edit_exclude_function($column_name, $post_type): void
    {
        // ToDo: $post_type unused

        if ($column_name == 'omni_column') {
            wp_nonce_field('save_exclude_from_omni', 'exclude_from_omni_nonce');
            ?>
            <fieldset class="inline-edit-col-right">
                <div class="inline-edit-col">
                    <label class="alignleft">
                        <input type="checkbox" name="exclude_from_omni_bulk" value="1"/>
                        <span class="checkbox-title">Exclude from Omnimind</span>
                    </label>
                </div>
            </fieldset>
            <?php
        }
    }

    public function add_omni_column($columns)
    {
        $columns['omni_column'] = 'Omni sync status';
        return $columns;
    }


    /**
     * @param $column_name
     * @param $post_id
     *
     * @return void
     */
    public function omni_column_content($column_name, $post_id): void
    {
        if ($column_name == 'omni_column') {
            $post_exclude = get_post_meta($post_id, '_exclude_from_omni', true);
            if ($post_exclude) {
                echo '<span style="color:#d03030;" class="dashicons dashicons-no"></span>';
            } else {
                echo '<span style="color:#2baf3a;" class="dashicons dashicons-yes"></span>';
            }
        }
    }

    private function render_message(string $msg, string $part): void
    {
        $this->template->set_template_data(
            array('message' => $msg)
        )->get_template_part($part);
        die();
    }

    private function handle_api_key(): void
    {
        $api_key_status = $this->api->verify_api_key(sanitize_text_field($_POST['verify_api_key']));

        update_option('_omni_api_key_status', $api_key_status);
        update_option('_omni_api_key', sanitize_text_field($_POST['verify_api_key']));

        $this->message = $api_key_status === true
            ? ['status' => 'success', 'message' => __('API Key stored successfully!')]
            : ['status' => 'success', 'message' => __('Something went wrong! Please check your API key or try again later.')];
    }

    private function handle_save_post_types(): void
    {
        $selected_post_types = isset($_POST['post_types']) ? $_POST['post_types'] : array();
        update_option('_omni_selected_post_types', $selected_post_types);
        if (isset($_POST['post_type_fields'])) {
            $selected_fields = $_POST['post_type_fields'];
            $filtered_selected_fields = array();
            foreach ($selected_fields as $post_type => $fields) {
                if (in_array($post_type, $selected_post_types)) {
                    $title_columns = $fields['advanced-title-columns'] ?? array();
                    $metadata_columns = $fields['advanced-metadata-columns'] ?? array();
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

    private function handle_send_post_types(): void
    {
        $selected_post_types = get_option('_omni_selected_post_types');

        // ToDo: sync_data() does not expect parameters
        $data_sent = sync_data($selected_post_types);
        if ($data_sent === true) {
            omni_error_log('Data successfully sent to remote server in CSV format.');
        } else {
            omni_error_log('data not sended.');
        }
    }

    private function handle_send_project_name(): void
    {
        $project_name = sanitize_text_field($_POST['project_name']);
        $project_created = $this->api->create_project($project_name);
        $this->message = [
            'status' => 'success',
            'message' => $project_created === true
                ? __('Project Created successfully!')
                : __('Failed to create project! Please try again later'),
        ];
    }

    private function handle_delete_project(): void
    {
        $project_deleted = $this->api->delete_project();
        $this->message = [
            'status' => 'success',
            'message' => $project_deleted === true
                ? __('Project Deleted successfully! API Key has been cleared')
                : __('Failed to delete project! Please try again later.'),
        ];
    }

    private function handle_reindex_project(): void
    {
        $project_reindexed = $this->reindex_project();
        $this->message = [
            'status' => 'success',
            'message' => $project_reindexed === true
                ? __('Project Re-indexed successfully!')
                : __('Failed to update project! Please try again later.'),
        ];
    }

    private function handle_save_general(): void
    {
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

    /**
     * @return bool|null
     */
    private function sync_data(): ?bool
    {
        $omni_api_key = get_option('_omni_api_key');
        $project_id = get_option('_omni_project_id');
        $fields_array = get_option('_omni_selected_fields_option');

        $uploaded_fields_array = get_option('_omni_uploaded_fields_option');

        $chains = array();
        if (!is_array($uploaded_fields_array)) {
            $uploaded_fields_array = array();
        }
        foreach ($fields_array as $type => $fields) {
            if (isset($uploaded_fields_array[$type])) {
                if ($this->compare_second_level($fields, $uploaded_fields_array[$type])) {
                    $this->delete_posts_of_type($type, $project_id, $chains);
                    $this->add_posts_of_type($type, $project_id, $chains, $fields);
                }
            } else {
                $this->add_posts_of_type($type, $project_id, $chains, $fields);
            }
        }
        foreach ($uploaded_fields_array as $type => $fields) {
            if (!isset($fields_array[$type])) {
                $this->delete_posts_of_type($type, $project_id, $chains);
            }
        }

        if (!count($chains)) {
            update_option('_omni_last_sync_date', current_time('mysql'));
            return true;
        }
        $status = $this->api->send_requests($chains, $omni_api_key, $project_id, $fields_array);
        if ($status) {
            update_option('_omni_last_sync_date', current_time('mysql'));
        }
        return $status;
    }

    /**
     * @return bool
     */
    public function reindex_project(): bool
    {
        $project_id = get_option('_omni_project_id');
        $data = $this->api->get_resources($project_id);

        if (!$data) {
            $this->debug->omni_error_log('Reindex error code: ' . $data);
            return false;
        } else {
            if ($data && isset($data[0]->url)) {
                $data_url = $data[0]->url;
                $this->api->del_resources($data_url, $project_id);

                $data_sent = $this->sync_data();
                if ($data_sent === true) {
                    $this->debug->omni_error_log('Data successfully updated in reindex_project.');
                    $this->debug->omni_error_log('=========='); // Separator
                } else {
                    $this->debug->omni_error_log('Error sending data in reindex_project.');
                    $this->debug->omni_error_log('=========='); // Separator
                }
                return true;
            } else {
                return false;
            }

        }
    }


    /**
     * @return void
     */
    public function sync_data_ajax_handler(): void
    {
        $result = $this->sync_data();
        wp_send_json_success(array('synced' => $result));
    }


    /**
     * @param $post_type
     * @param $project_id
     * @param $chains
     *
     * @return void
     */
    private function delete_posts_of_type($post_type, $project_id, &$chains): void
    {
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
            $chains[] = $chain_item;
        }
    }


    /**
     * @param $post_type
     * @param $project_id
     * @param $chains
     * @param $fields
     *
     * @return void
     */
    private function add_posts_of_type($post_type, $project_id, &$chains, $fields): void
    {
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
                    'key' => '_exclude_from_omni',
                    'value' => '1',
                    'compare' => '!=',
                ),
                array(
                    'key' => '_exclude_from_omni',
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
                    "widgetTypeId" => WIDGET_TYPE_ID,
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

            $chains[] = $chain_item;
        }
    }

    /**
     * @param $array1
     * @param $array2
     *
     * @return bool
     */
    private function compare_second_level($array1, $array2): bool
    {
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

    public function add_meta_box(): void
    {
        $post_types = get_post_types(array('public' => true));
        foreach ($post_types as $post_type) {
            add_meta_box(
                'exclude_omni', // ID
                __('Exclude from Omnimind', 'omni'), // Title
                array($this, 'exclude_omni_meta_box_callback'), // Callback function
                $post_type, // Screen (post type)
                'side', // Context
                'default' // Priority
            );
        }
    }

    public function exclude_omni_meta_box_callback($post): void
    {
        $value = get_post_meta($post->ID, '_exclude_from_omni', true);
        echo '<label><input type="checkbox" name="exclude_from_omni" value="1"' . checked($value, 1, false) . '/> ' . __('Exclude from Omnimind', 'omni') . '</label>';
    }

    /**
     * @param $post_id
     *
     * @return void
     */
    public function bulk_quick_save_post($post_id): void
    {
        // Check if this function has already been executed in the current request
        if (defined('OMNI_CUSTOM_FUNCTION_EXECUTED') && OMNI_CUSTOM_FUNCTION_EXECUTED) {
            return;
        }
        // Do not execute on DOING_AUTOSAVE
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        // Do not execute on post revision
        if (wp_is_post_revision($post_id)) {
            return;
        }
        // check inlint edit nonce if _inline_edit nonce is set and verify it
        if (isset($_POST['_inline_edit']) && !wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) {
            return;
        }
        // not inline - fix multiple sending request
        if (!isset($_POST['_inline_edit'])) {
            if (wp_doing_ajax() || !is_admin()) {
                return;
            }
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST['exclude_from_omni_bulk'])) {
            update_post_meta($post_id, '_exclude_from_omni', $_POST['exclude_from_omni_bulk']);
        } elseif (isset($_POST['exclude_from_omni'])) {
            update_post_meta($post_id, '_exclude_from_omni', $_POST['exclude_from_omni']);
        } else {
            delete_post_meta($post_id, '_exclude_from_omni');
        }
        $post_exclude = get_post_meta($post_id, '_exclude_from_omni', true);

        // Send updates
        $fields_array = get_option('_omni_selected_fields_option');
        $post_type = get_post_type($post_id);
        $status = get_post_status($post_id);

        // Send post to Omnimind
        $this->handle_post($fields_array, $post_type, $post_id, $status);
        omni_error_log('=========='); // Separator

        // Mark that the function has been executed to prevent further executions
        define('OMNI_CUSTOM_FUNCTION_EXECUTED', true);
    }

    private function handle_post($fields_array, $post_type, $post_id, $status, bool $deactivateAjax = true): void
    {
        if (isset($fields_array[$post_type])) {
            $exclude_from_omni = get_post_meta($post_id, '_exclude_from_omni', true);
            if ($status == 'publish') {
                $this->api->delete_post($post_id);
                if ('1' !== $exclude_from_omni) {
                    $this->api->send_post($post_id, $deactivateAjax);
                }
            }
            if ($status == 'draft' || $status == 'trash') {
                $this->api->delete_post($post_id);
            }
        }
    }


}