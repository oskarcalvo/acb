<?php

namespace Drupal\acb\Model;

use Drupal\acb\Helper\AcbLoadBlock;

class AcbBlockModelClass {
	
	static public function list_of_blocks(array $list_of_modules) {
		$blocks = &drupal_static(__FUNCTION__, array());
		
		if(!isset($blocks)) {
			foreach ($list_of_modules as $module) {
				$blocks[$module] = module_invoke($module, 'block_info');
			}
		}
		return $blocks;
	}
}