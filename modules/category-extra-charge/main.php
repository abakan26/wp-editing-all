<?php
if (wp_doing_ajax()) {
    require_once "ajax.php";
}
add_action( 'network_admin_menu', function () {
	$hookSuffix = add_submenu_page(
		'wp-editing-all',
		'Наценка категории',
		'Наценка категории',
		'manage_options',
		'product_cat_extra_charge',
		'weaCategoryExtraCharge',
	);
	add_action( 'load-' . $hookSuffix, function () {
		add_action( 'admin_enqueue_scripts', function () {
			wp_enqueue_script(
                    'knockout',
				plugin_dir_url( dirname( __FILE__, 2 ) ) . '/knockout-3.5.1.js'
            );
			wp_enqueue_script( 'product_cat_extra_charge',
				plugin_dir_url( __FILE__ ) . '/scripts.js',
				[ 'jquery', 'knockout' ],
				time(),
				true
			);
            wp_enqueue_style('wp-editing-all');
		} );
	} );
	add_action( 'admin_print_styles-' . $hookSuffix, function () {
		?>
        <style><?php include 'styles.css' ?></style>
		<?php
	} );
} );

function weaCategoryExtraCharge() {
	$args = [
		'cats' => get_terms( [ 'taxonomy' => 'product_cat', 'hide_empty' => false ] )
	];
	include "template.php";
}