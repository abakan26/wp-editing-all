<?php
$action = 'wea-sync-news';
add_action( "wp_ajax_{$action}", 'weaAjaxSyncNews' );
add_action( "wp_ajax_nopriv_{$action}", 'weaAjaxSyncNews' );
function weaAjaxSyncNews() {
	$domain = get_site()->domain;
	$response = wp_remote_request(
		"https://$domain/wp-json/wp/v2/posts?" . http_build_query( [
			'per_page' => 100,
			'order'    => 'asc',
			'orderby'  => 'id',
		] ),
		[
			'method'  => 'GET',
			'headers' => [
				'Authorization' => 'Basic ' . base64_encode( AUTHORIZATION )
			]
		]
	);
	$log      = [];
	if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
		$responseData  = json_decode( wp_remote_retrieve_body( $response ), true );
		$log['update'] = [];
		$log['insert'] = [];
		foreach ( $responseData as $post ) {
			$ex       = get_page_by_path( $post['slug'], OBJECT, 'post' );
			$postData = [
				'post_status'  => $post['status'],
				'post_type'    => 'post',
				'post_name'    => $post['slug'],
				'post_title'   => $post['title']['rendered'],
				'post_content' => $post['content']['rendered'],
				'post_excerpt' => $post['excerpt']['rendered'],
				'post_date' => $post['date'],
				'post_date_gmt' => $post['date_gmt'],
				'post_modified' => $post['modified'],
				'post_modified_gmt' => $post['modified_gmt'],
			];
			if ( $ex ) {
				unset( $postData['slug'] );
				$postData['ID']  = $ex->ID;
				$log['update'][] = wp_update_post( $postData );
			} else {
				$log['insert'][] = wp_insert_post( wp_slash( $postData ), true );
			}
		}
		wp_send_json( [
			'success' => true,
			"log"     => $log
		] );
	} else {
		wp_send_json( [
			'success' => false,
			"log"     => $log
		] );
	}
}