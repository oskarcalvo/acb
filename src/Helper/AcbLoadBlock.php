<?php

namespace drupal\acb\Helper;
/**
 * @file
 */

class AcbLoadBlock {
	
	/**
	 * This method is a copy from the function
	 * _blockreference_get_block_modules() from blockreference module.
	 *
	 * @return array|bool|mixed
	 */
	public static function list_of_modules_with_blocks() {
		$modules = &drupal_static(__FUNCTION__);
		
		if (!$modules) {
			// Get modules that define blocks.
			$modules = module_implements('block_info');
			$modules = array_flip($modules);
			
			// And get their pretty names.
			$all_modules = system_list('module_enabled');
			foreach ($modules as $machine_name => $foo) {
				$modules[$machine_name] = @$all_modules[$machine_name]->info['name'] ?: $machine_name;
			}
			
			natcasesort($modules);
		}
		
		return $modules;
	}
}