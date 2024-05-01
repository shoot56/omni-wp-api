<?php
/**
 * Template file for displaying BrainySearch form.
 *
 * This template file is loaded by the `brainy_search_handle_query()` function.
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$search_answer = esc_attr($data->form['search_answer']);
?>

<div class="omni-search <?php $search_answer ?? 'search_answer' ?>">
    <form>
        <div class="omni-search__search-wrp">
            <input type="text" id="query" placeholder="<?php _e('Search..', 'omni') ?>">
            <button id="get_results" type="button"><?php _e('Search', 'omni') ?></button>
        </div>
        <div class="omni-search__result-wrp" id="results"></div>
        <div class="omni-search__pagination_wrp">
            <button id="prev" type="button"><?php _e('Prev', 'omni') ?></button>
            <button id="next" type="button"><?php _e('Next', 'omni') ?></button>
        </div>
    </form>
</div>
