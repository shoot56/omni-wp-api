<?php
/**
 * Template file for displaying BrainySearch form.
 *
 * This template file is loaded by the `brainy_search_handle_query()` function.
 *
 */
?>
<div class="omni-search">
    <form>
        <div class="omni-search__search-wrp">
            <input type="text" id="query" placeholder="<?php _e('Search..') ?>">
            <button id="get_results" type="button"><?php _e('Search') ?></button>
        </div>
        <div class="omni-search__result-wrp" id="results"></div>
        <div class="omni-search__pagination_wrp">
            <button id="prev" type="button"><?php _e('Prev') ?></button>
            <button id="next" type="button"><?php _e('Next') ?></button>
        </div>
    </form>
</div>
