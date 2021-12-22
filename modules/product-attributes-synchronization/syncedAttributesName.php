<?php
function syncedAttributesName($name, $slug): int {
	$attribute_id = wc_attribute_taxonomy_id_by_name( $slug );
	$attribute    = wc_get_attribute( $attribute_id );
	if ( $attribute->name !== $name ) {
		wc_update_attribute( $attribute_id, [
			'name' => $name,
			'slug' => $slug,
		] );
	}
	return $attribute_id;
}