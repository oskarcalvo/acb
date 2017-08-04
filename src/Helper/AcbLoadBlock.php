<?php

namespace Drupal\acb\Helper;
use Drupal\acb\Model\AcbBlockModelClass;

/**
 * @file
 */

class AcbLoadBlock {
	
	/**
	 * @return null|array
	 */
	public static function selected_modules() {
		return variable_get('acb_list_modules', NULL);
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
		if($string) {
			$blocks = self::filter_blocks($string,$blocks);
		}
	}
	
	
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