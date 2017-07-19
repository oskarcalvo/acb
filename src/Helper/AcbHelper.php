<?php

namespace drupal\acb\Helper;


Class AcbHelper {
	
	
	/**
	 * @Autocomplete
	 */
	
	function load_block ($string = '') {
		$matches = [];
		if($string) {
			$result = db_select('block')
				->fields('block', ['bid','title'])
				->condition('title',db_like($string).'%', 'LIKE')
				->range(0,10)
				->execute();
			foreach ($result as $object) {
				$matches[$object->title . "[$object->bid]"] = check_plain
				($object->title);
			}
		}
		drupal_json_output($matches);
	}
	
	/**
	 * @return array
	 */
	static function get_enabled_theme_regions() {
		$list_theme = self::get_enabled_themes();
		$regions = self::get_regions($list_theme);
		return $regions;
	}
	
	/**
	 * @param array $list list of themes in the website.
	 *
	 * @return array
	 */
	static function get_enabled_themes(){
		$cache = cache_get('acb_enabled_themes');
		unset($cache->data);
		if (isset($cache->data)) {
			$data = $cache->data;
		}
		else {
			$list = list_themes();
			$data = array_filter($list, function ($theme) {
				if ($theme->status == 1) {
					return $theme;
				}
			});
			cache_set('acb_enabled_themes', $data);
		}
		return $data;
	}
	
	/**
	 * @param array $list_theme
	 *
	 * @return array
	 */
	static function get_regions(array $list_theme){
		$cache = cache_get('acb_theme_regions');
		unset($cache->data);
		if (isset($cache->data)) {
			$data = $cache->data;
		}
		else {
			$data = array_map(function ($theme) {
				return system_region_list($theme->name);
			}, $list_theme);
			cache_set('acb_theme_regions', $data);
		}
		return $data;
	}
	

	
	
	function clean_submited_values(array $values) {
	
	
	}
	
}