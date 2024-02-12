<?php

function alst_menu_attribute_linker_search_terms()
{
    add_submenu_page(
        'edit.php?post_type=product', // Slug del menú principal de Productos de WooCommerce
        'Importar Términos', // Título de la página
        'Importar Términos', // Título del menú
        'manage_woocommerce', // Capacidad requerida para acceder al submenú (puede ser 'manage_woocommerce' o 'manage_options' u otra)
        'custom-woocommerce-submenu', // Slug de la página
        'attribute_linker_search_terms_callback', // Callback para mostrar el contenido de la página
        6
    );
    
}

function attribute_linker_search_terms_callback()
{
    include ALST_PATH . '/admin/views/page_attribute_linker_search_terms.php';
}

add_action('admin_menu', 'alst_menu_attribute_linker_search_terms');