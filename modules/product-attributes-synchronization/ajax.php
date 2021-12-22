<?php

$action = 'wea-fetch-attributes';
add_action( "wp_ajax_{$action}", 'ajaxFetchAttributes' );
add_action( "wp_ajax_nopriv_{$action}", 'ajaxFetchAttributes' );

function ajaxFetchAttributes() {
	wp_send_json( [
		'data' => fetchAttributes(),
	] );
}

$action = 'wea-sync-attributes';
add_action( "wp_ajax_{$action}", 'weaSyncAttributes' );
add_action( "wp_ajax_nopriv_{$action}", 'weaSyncAttributes' );
function weaSyncAttributes() {
	global $wpdb;
	$attributes = json_decode( wp_unslash( $_POST['attributes'] ), true );
	foreach ( $attributes as $preAttribute ) {
		$slug         = $preAttribute['attribute']['attribute_name'];
		$name         = $preAttribute['attribute']['attribute_label'];
		$taxonomyName = wc_attribute_taxonomy_name( $slug );
		$attribute    = syncedAttribute( $name, $slug );
		foreach ( $preAttribute['terms'] as $preTerm ) {
			$term = syncedTerm( $preTerm['name'], $preTerm['slug'], $taxonomyName );
		}
	}
	$mAttributes = [];
	foreach ( $attributes as $preAttribute ) {
		$mTerms = [];
		foreach ( $preAttribute['terms'] as $term ) {
			$mTerms[ $term['slug'] ] = $term;
		}
		$mAttributes[ $preAttribute['attribute']['attribute_name'] ] = [
			'attribute' => $preAttribute['attribute'],
			'terms'     => $mTerms,
		];
	}
	$m   = [];
	$t   = [];
	foreach ( fetchAttributes() as $attr ) {
		if ( ! array_key_exists( $attr['attribute']->attribute_name, $mAttributes ) ) {
			$m[] = "Лишний аттрибут: {$attr['attribute']->attribute_name}";
			wc_delete_attribute( $attr['attribute']->attribute_id );
		} else {
			$taxonomyName = wc_attribute_taxonomy_name( $attr['attribute']->attribute_name );
			foreach ( $attr['terms'] as $term ) {
				if ( ! array_key_exists( $term->slug, $mAttributes[ $attr['attribute']->attribute_name ]['terms'] ) ) {
					$t[] = "Лишний термин: {$term->slug}";
					wp_delete_term( $term->term_id, $taxonomyName );
				}
			}
		}
	}
	wp_send_json( [
		'status' => 'success',
		'$m'     => $m,
		'$t'     => $t,
	] );
}
