<?php

/**
 * Plugin Name:       AttributeLinkerSearchTerms 
 * Plugin URI:        https://github.com/juanjcgo
 * Description:       El plugin WordPress permite explorar y obtener los atributos de todos los productos públicos, relacionándolos con la taxonomía 'product_filter_data' para darle funcionamiento al motor de búsqueda.
 * Version:           1.2
 * Requires at least: 6.3.1
 * Requires PHP:      7.4
 * Author:            Juan Carlos Garcia
 * Author URI:        https://juankaweb.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       alst_linker_search_terms
 */

define('ALST_PATH', plugin_dir_path(__FILE__));

/* Controllers */
require_once ALST_PATH . "controllers/controller_scripts_register.php";
require_once ALST_PATH . "controllers/controller_option_menu.php";
require_once ALST_PATH . "controllers/controller_linker.php";


