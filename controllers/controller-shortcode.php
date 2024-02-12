<?php
function alst_template_import_search_terms()
{

    if (is_user_logged_in()) {

        if (!current_user_can('administrator')) {

            $user_id    = get_current_user_id();
            $level_page = 0;

            ob_start();
            include ALST_PATH . '/views/app/shortcodes/import_search_terms.php';
            return ob_get_clean();

        }
    } else {
        wp_redirect(home_url());
    }
}
add_shortcode('alst_template_import_search_terms', 'alst_template_import_search_terms');
