<?php

namespace Drupal\acb\Helper;
use Drupal\acb\Model\AcbBlockModelClass;

/**
 * @file
 */

class AcbLoadBlock {
	
	/**
	 * This method is a copy from the function		 +	 * @return null|array
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
	
	/**
	 * @return null|array
	 */
	public static function selected_modules() {
		return variable_get('acb_list_modules', ['all' => 'ALL']);
	}
	
	/**
	 * @param $string
	 */
	public static function get_blocks($string = '') {
		//get list of modules enabled.
		$list_of_modules = self::selected_modules();
		
		// Load all the blocks availables in those modules
		$blocks = AcbBlockModelClass::list_of_blocks($list_of_modules);
		
		// Prepare blocks
		$blocks = self::build_blocks_array($blocks);
		
		// filter by search string
		$blocks = self::filter_blocks($string,$blocks);
		
		// Build output
		$blocks = self::build_output($blocks);
		
		drupal_json_output($blocks);
	}
	
	/**
	 * @param $blocks
	 * @return mixed
	 */
	private static function build_output(array $blocks) {
		
		foreach ($blocks as $block) {
			$key = $block->info .'['. $block->module .':'.$block->delta .']';
			$return_block[$key] = $block->info;
		}
		return $return_block;
	}
	/**
	 * @param $search_string
	 * @param $blocks
	 * @return mixed
	 */
	public static function filter_blocks($search_string,$blocks){
		$check_string = drupal_strtolower($search_string);
		foreach ($blocks as $moddelta => $block) {
			if (strpos(drupal_strtolower($block->info), $check_string) === FALSE) {
				unset($blocks[$moddelta]);
			}
		}
		return $blocks;
	}
	/**
	 * @param array $list_of_blocks
	 *
	 * @return mixed
	 */
	public static function build_blocks_array(array $list_of_blocks) {
		foreach ( $list_of_blocks as $module => $blocks ) {
			foreach ($blocks as $delta => $info) {
				$all_blocks[$module .':'.$delta] = self::prepare_blocks_info($module,	$delta,	$info);
			}
		}
		return $all_blocks;
	}
	
	/**
	 * @param string $module
	 * @param string $delta
	 * @param null $info
	 *
	 * @return object
	 */
	public static function prepare_blocks_info($module, $delta, $info = NULL) {
		if (!$info) {
			$infos = module_invoke($module, 'block_info');
			$info = @$infos[$delta];
		}
		
		if ($info) {
			$info = compact('module', 'delta') + $info;
			$info['info'] = trim($info['info']);
			
			return (object) $info;
		}
	}
}