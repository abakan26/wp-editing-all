<?php
add_action( 'network_admin_menu', function () {
	add_menu_page(
		'Настройки плагина',
		'WP Editing All',
		'manage_options',
		'wp-editing-all',
		'weaRenderMenu',
		'dashicons-images-alt',
		20
	);
} );

function weaRenderMenu() {
	?>
    Test
	<?php
}