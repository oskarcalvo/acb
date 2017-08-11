<?php

namespace Drupal\acb\Model;

use Drupal\acb\Helper\AcbLoadBlock;

class AcbBlockModelClass {
  
  /**
   * @param array $list_of_modules
   *
   * @return array
   */
	static public function list_of_blocks(array $list_of_modules) {
		
		if(isset($list_of_modules['all'])){
			$list_of_modules = AcbLoadBlock::list_of_modules_with_blocks();
		}
		
		foreach ($list_of_modules as $module) {
			$blocks[$module] = module_invoke($module, 'block_info');
		}
		
		return $blocks;
	}
  
  /**
   * @param string $theme
   *
   * @return array|NULL
   */
	static public function get_default_blocks($theme) {
	  
    $query = db_select('block', 'b')
      ->condition('b.region','-1','not like')
      ->condition('b.theme',$theme,'like')
      ->fields('b',['theme','region','title','module','delta','weight'])
      ->orderBy('theme', 'desc')
      ->orderBy('region','desc')
      ->orderBy('weight','desc');
    
    $blocks = $query->execute()->fetchAll();
    
    return $blocks;
  }
  
  /**
   * @param string $delta
   * @param string $theme
   *
   * @return mixed
   */
  static function get_block_filter_by_delta_theme($delta, $theme) {
    $query = db_select('block', 'b')
      ->condition('b.delta', $delta, 'IN')
      ->condition('b.theme', $theme, 'LIKE')
      ->fields('b',['bid','module','delta','theme','status',
        'weight','region','custom','visibility','pages','title','cache','i18n_mode']);
    $blocks = $query->execute()->fetchAll();
    return $blocks;
  }
}