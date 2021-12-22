<?php

$action = 'product_cat_extra';
add_action("wp_ajax_{$action}", 'productCatExtra');
add_action("wp_ajax_nopriv_{$action}", 'productCatExtra');

function productCatExtra() {
	$log = '';
	foreach (get_sites() as $site) {
		switch_to_blog($site->blog_id);
		$term = get_term_by('slug', $_POST['product_cat'], 'product_cat');
		$oldExtra = get_field('процент', $term);
		$newExtra = intval($_POST['extra']);
		$domain = $site->domain;
		$isUpdated = update_field('процент', $newExtra, $term);
		$log .= "<strong>Субдомен $domain</strong>";
		if ($isUpdated) {
			$log .= sprintf(
				' поле наценка обновлено. Старое значение %s, новое значение: %s',
				$oldExtra, $newExtra
			);
		} else {
			$log .= ' поле наценка не было обновлено из-за ошибки';
		}
		$log .= '<br><br>';
	}
	restore_current_blog();
	wp_send_json([
		'notice' => $log
	]);
}