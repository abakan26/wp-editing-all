<?php
function fetchAttributes(): array {
	return array_values(
		array_map(
			function ( $attribute ) {
				return [
					'attribute' => $attribute,
					'terms'     => get_terms( [
						'taxonomy'   => wc_attribute_taxonomy_name( $attribute->attribute_name ),
						'hide_empty' => false,
					] )
				];
			},
			wc_get_attribute_taxonomies()
		)
	);
}