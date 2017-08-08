<?php

namespace Drupal\acb\Model;

use Drupal\acb\Helper\AcbLoadBlock;

class AcbBlockModelClass {
	
	static public function list_of_blocks(array $list_of_modules) {
		
		if(isset($list_of_modules['all'])){
			$list_of_modules = AcbLoadBlock::list_of_modules_with_blocks();
		}
		
		foreach ($list_of_modules as $module) {
			$blocks[$module] = module_invoke($module, 'block_info');
		}
		
		return $blocks;
	}
}