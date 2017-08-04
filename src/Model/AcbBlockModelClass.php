<?php

namespace Drupal\acb\Model;

use Drupal\acb\Helper\AcbLoadBlock;

class AcbBlockModelClass {
	
	static public function list_of_blocks(array $list_of_modules, $id ) {
		$list_of_blocks = &drupal_static(__FUNCTION__, array());
		unset($list_of_blocks);
		if(!isset($list_of_blocks)) {
			foreach ($list_of_modules as $module) {
				$blocks[$module] = module_invoke($module, 'block_info');
			}
		}
		return $blocks;
	}
}