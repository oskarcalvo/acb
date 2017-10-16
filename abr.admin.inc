<?php

use Drupal\abr\Model\AbrModelClass;
use Drupal\abr\Helper\AbrHelper;
/**
 * @file abr.admin.inc
 */

function abr_list_elements() {
	$items = AbrModelClass::list_of_items();
	
	$header = [
		['data' =>'URL'],
		['data' => 'operations']
	];
	$rows = [];
	foreach ($items as $item) {
		$rows[] = [
			['data' => $item->url],
			['data' => AbrHelper::operations_links($item->abrid)]
		];
	}
	
	$build['pager_table'] = [
		'#theme' => 'table',
		'#header' => $header,
		'#rows' => $rows,
		'#empty' => t('There are not data yet.')
	];
	
	$build['pager_pager'] = ['#theme' => 'pager'];
	
	return $build;
}