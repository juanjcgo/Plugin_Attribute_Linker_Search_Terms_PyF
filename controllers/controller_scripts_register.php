<?php
/*************************** Backend ****************************/
function alst_admin_script_register()
{
    /* Globals scripts */
    wp_register_script("alst_admin_jquey_js", 'https://code.jquery.com/jquery-3.6.3.min.js');
    wp_register_script("alst_xlsx_js", 'https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js');
    /* wp_register_script("alst_admin_bootstrap_js", 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'); */

    /* Globals styles */
    wp_register_style('alst_admin_bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css');
    wp_register_style('alst_admin_bootstrap_icon_css', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css');

    /* Locals styles */
    
    wp_register_style('alst_page_attribute_linker_css', plugins_url("../admin/views/assets/css/page_attribute_linker_search_terms.css", __FILE__));
    wp_register_style('alst_preloader_css', plugins_url("../admin/views/assets/css/preloader.css", __FILE__));
    
    /* Local Scripts */
    wp_register_script("alst_service_attribute_linker_js", plugins_url("../admin/views/assets/js/services/service_attribute_linker_search_terms.js", __FILE__), array('alst_admin_jquey_js'), '1.0', true);
    wp_register_script("alst_attribute_linker_js", plugins_url("../admin/views/assets/js/page_attribute_linker_search_terms.js", __FILE__), array('alst_admin_jquey_js'), '1.0', true);
    wp_localize_script(
        'alst_attribute_linker_js',
        'alst',
        array(
            'ajax_url' => admin_url('admin-ajax.php')
        )
    );

}
add_action('admin_enqueue_scripts', 'alst_admin_script_register');