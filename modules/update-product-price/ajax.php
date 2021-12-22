<?php

$action = 'wea-update-price';
add_action( "wp_ajax_{$action}", 'ajaxUpdatePrice' );
add_action( "wp_ajax_nopriv_{$action}", 'ajaxUpdatePrice' );

function ajaxUpdatePrice() {
	update_product_price();
	wp_send_json( [
		'success' => true,
//		"log"     => $log
	] );
}
