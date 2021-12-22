<?php
/**
 * Plugin Name:     WP Edit All (for multisite)
 * Description:     редактирование контента на всех сайтах разом
 * Author:          Dmitriy Svistunov
 * Version:         0.0.0
 * Network: true
 */

const AUTHORIZATION = 'present:R9ti 1o3L OO2i Ro9C pimY ejiK';
require_once "admin-menu.php";
require_once "modules/category-extra-charge/main.php";
require_once "modules/product-attributes-synchronization/main.php";
require_once "modules/news/main.php";
require_once "modules/update-product-price/main.php";

add_action( 'admin_enqueue_scripts', function () {
	wp_register_script(
		'knockout',
		plugin_dir_url( __FILE__ ) . '/knockout-3.5.1.js'
	);
	wp_register_style(
		'wp-editing-all',
		plugin_dir_url( __FILE__ ) . '/style.css',
		[],
		time()
	);
} );
