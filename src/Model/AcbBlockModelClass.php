<?php

namespace Drupal\acb\Model;

class AcbBlockModelClass {
	
	static public function list_of_blocks(array $list_of_modules, $id ) {
		$all_block_infos = &drupal_static(__FUNCTION__, array());
		if(!isset($all_block_infos[$i])) {
			foreach ($list_of_modules as $module) {
				$blocks = module_invoke($module, 'block_info');
				foreach ($blocks as $delta => $info) {
					$moddelta = $module . ':' . $delta;
					$all_block_infos[$id][$moddelta] = _blockreference_block($module, $delta, $info);
				}
			}
		}
	}
}