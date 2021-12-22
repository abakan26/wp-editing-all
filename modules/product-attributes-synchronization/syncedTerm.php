<?php
function syncedTerm( $name, $slug, $taxonomyName ) {
	$term = get_term_by( 'name', $name, $taxonomyName );
	if ( $term ) {
		if ( $term->slug !== $slug ) {
			$termObjectBySlug = get_term_by( 'slug', $slug, $taxonomyName );
			if ( $termObjectBySlug ) {
				wp_delete_term( $termObjectBySlug->term_id, $taxonomyName );
			}
			$result = wp_update_term( $term->term_id, $taxonomyName, [
				'slug' => $slug
			] );
			$termId = $result['term_id'];
		} else {
			$termId = $term->term_id;
		}
	} else {
		$termObjectBySlug = get_term_by( 'slug', $slug, $taxonomyName );
		if ( $termObjectBySlug ) {
			$result = wp_update_term( $termObjectBySlug->term_id, $taxonomyName, [
				'name' => $name
			] );
		} else {
			$result = wp_insert_term( $name, $taxonomyName, [
				'slug' => $slug
			] );
		}
		$termId = $result['term_id'];
	}

	return get_term( $termId, $taxonomyName );
}