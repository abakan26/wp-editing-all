<?php
if ( wp_doing_ajax() ) {
	require_once "ajax.php";
}

add_action( 'network_admin_menu', function () {
	$hookSuffix = add_submenu_page(
		'wp-editing-all',
		'Синхронизация новостей',
		'Синхронизация новостей',
		'manage_options',
		'news_synchronization',
		'weaNewsSynchronization',
	);
	add_action( 'load-' . $hookSuffix, function () {
		add_action( 'admin_enqueue_scripts', function () {
			wp_enqueue_script( 'news_synchronization',
				plugin_dir_url( __FILE__ ) . '/scripts.js',
				[ 'jquery', 'knockout' ],
				time(),
				true
			);
			wp_localize_script( 'news_synchronization', 'wpEditingAll', [
				'sites' => get_sites(),
				'mainSite' => get_site()
			] );
			wp_enqueue_style( 'wp-editing-all' );
		} );
	} );
	add_action( 'admin_print_styles-' . $hookSuffix, function () {
		/* ?>
        <style><?php include 'styles.css' ?></style>
		<?php */
	} );
} );

function weaNewsSynchronization() {
	$args = [
		'sites' => get_sites()
	];
	include "template.php";
}