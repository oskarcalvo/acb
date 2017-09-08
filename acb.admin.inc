<?php

use Drupal\acb\Model\AcbModelClass;
use Drupal\acb\Helper\AcbHelper;
/**
 * @file acb.admin.inc
 */

function acb_list_elements() {
	$items = AcbModelClass::list_of_items();
	
	$header = [
		['data' =>'URL'],
		['data' => 'operations']
	];
	
	foreach ($items as $item) {
		$rows[] = [
			['data' => $item->url],
			['data' => AcbHelper::operations_links($item->acbid)]
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