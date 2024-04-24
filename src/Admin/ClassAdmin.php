<?php

namespace Procoders\Omni\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Procoders\Omni\ClassLoader as Loader;
use Procoders\Omni\Includes\api as Api;

class ClassAdmin
{
    private $template;
    private $api;
    private $message;

    public function __construct()
    {
        $this->template = new Loader();
        $this->api = new Api();
    }

    private function checkUserPermission(): bool
    {
        return current_user_can('manage_options');
    }

    public function omniSettingsPage()
    {
        if (!$this->checkUserPermission()) {
            $this->renderMessage(__('Permission error'), 'admin/error');
            return;
        }

        $this->handlePostRequests();
        $form = $this->getForm();

        $this->template->set_template_data(
            array(
                'template' => $this->template,
                'form' => $form)
        )->get_template_part('admin/settings-page');
    }

    public function handlePostRequests()
    {
        if (isset($_POST['check_api_key'])) {
            $this->handleApiKey();
        }

        if (isset($_POST['save_post_types'])) {
            $this->handleSavePostTypes();
        }

        if (isset($_POST['send_post_types'])) {
            $this->handleSendPostTypes();
        }

        if (isset($_POST['send_project_name'])) {
            $this->handleSendProjectName();
        }

        if (isset($_POST['delete_project'])) {
            $this->handleDeleteProject();
        }

        if (isset($_POST['reindex_project'])) {
            $this->handleReindexProject();
        }

        if (isset($_POST['save_general'])) {
            $this->handleSaveGeneral();
        }
    }

    public function getForm()
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
                add_filter("manage_{$post_type}_posts_columns", 'add_omni_column');
                add_action("manage_{$post_type}_posts_custom_column", 'my_column_content', 10, 2);
            }
        }
    }

    public function my_admin_column_width(): void
    {

    }

    /**
     * @return void
     */
    public function add_quick_and_bulk_edit_to_post_types(): void
    {
        $selected_post_types = get_option('_omni_selected_post_types');

        if (is_array($selected_post_types) && !empty($selected_post_types)) {
            foreach ($selected_post_types as $post_type) {
                add_action("bulk_edit_custom_box", 'omni_edit_exclude_function', 10, 2);
                add_action("quick_edit_custom_box", 'omni_edit_exclude_function', 10, 2);
            }
        }
    }

    private function renderMessage(string $msg, string $part): void
    {
        $this->template->set_template_data(
            array('message' => $msg)
        )->get_template_part($part);
        die();
    }

    private function handleApiKey()
    {
        $api_key_status = $this->api->verify_api_key(sanitize_text_field($_POST['verify_api_key']));

        update_option('_omni_api_key_status', $api_key_status);
        update_option('_omni_api_key', sanitize_text_field($_POST['verify_api_key']));

        $this->message = $api_key_status === true
            ? ['status' => 'success', 'message' => __('API Key stored successfully!')]
            : ['status' => 'success', 'message' => __('Something went wrong! Please check your API key or try again later.')];
    }

    private function handleSavePostTypes()
    {
        $selected_post_types = isset($_POST['post_types']) ? $_POST['post_types'] : array();
        update_option('_omni_selected_post_types', $selected_post_types);
        if (isset($_POST['post_type_fields'])) {
            $selected_fields = $_POST['post_type_fields'];
            $filtered_selected_fields = array();
            foreach ($selected_fields as $post_type => $fields) {
                if (in_array($post_type, $selected_post_types)) {
                    $title_columns = isset($fields['advanced-title-columns'])
                        ? $fields['advanced-title-columns']
                        : array();
                    $metadata_columns = isset($fields['advanced-metadata-columns'])
                        ? $fields['advanced-metadata-columns']
                        : array();
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

    private function handleSendPostTypes()
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

    private function handleSendProjectName()
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

    private function handleDeleteProject()
    {
        $project_deleted = $this->api->delete_project();
        $this->message = [
            'status' => 'success',
            'message' => $project_deleted === true
                ? __('Project Deleted successfully! API Key has been cleared')
                : __('Failed to delete project! Please try again later.'),
        ];
    }

    private function handleReindexProject()
    {
        $project_reindexed = $this->api->reindex_project();
        $this->message = [
            'status' => 'success',
            'message' => $project_reindexed === true
                ? __('Project Re-indexed successfully!')
                : __('Failed to update project! Please try again later.'),
        ];
    }

    private function handleSaveGeneral()
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

}