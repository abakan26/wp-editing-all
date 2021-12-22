<?php
require_once "syncedAttribute.php";
require_once "syncedAttributesName.php";
require_once "syncedTerm.php";
require_once "fetchAttributes.php";
if ( wp_doing_ajax() ) {
	require_once "ajax.php";
}

add_action( 'network_admin_menu', function () {
	$hookSuffix = add_submenu_page(
		'wp-editing-all',
		'Синхронизация аттрибутов',
		'Синхронизация аттрибутов',
		'manage_options',
		'product_attributes_synchronization',
		'weaProductAttributesSynchronization',
	);
	add_action( 'load-' . $hookSuffix, function () {
		add_action( 'admin_enqueue_scripts', function () {
			wp_enqueue_script( 'product_attributes_synchronization',
				plugin_dir_url( __FILE__ ) . '/scripts.js',
				[ 'jquery', 'knockout' ],
				time(),
				true
			);
			wp_localize_script( 'product_attributes_synchronization', 'wpEditingAll', [
				'sites' => get_sites()
			] );
			wp_enqueue_style( 'wp-editing-all' );
		} );
	} );
	add_action( 'admin_print_styles-' . $hookSuffix, function () {
		?>
        <style><?php include 'styles.css' ?></style>
		<?php
	} );
} );

function weaProductAttributesSynchronization() {
	$args = [
		'sites' => get_sites()
	];
	include "template.php";
}