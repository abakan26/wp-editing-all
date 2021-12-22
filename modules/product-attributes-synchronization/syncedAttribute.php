<?php
function syncedAttribute( $name, $slug ): ?stdClass {
	$taxonomyName = wc_attribute_taxonomy_name( $slug );
	if ( taxonomy_exists( $taxonomyName ) ) {
		$attribute_id = syncedAttributesName($name, $slug);
	} else {
		$attribute_id = wc_create_attribute( [
			'name' => $name,
			'slug' => $slug,
		] );
		register_taxonomy(
			$taxonomyName,
			array( 'product' ),
			array(
				'hierarchical' => false,
				'rewrite'      => false,
			)
		);
	}
	return wc_get_attribute( $attribute_id );
}