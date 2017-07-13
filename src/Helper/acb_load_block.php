<?php

/**
 * @Autocomplete
 */

function acb_load_block ($string = "") {
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
 * @param array $list
 *
 * @return array
 */
function acb_get_enabled_themes(array $list){
  return array_filter($list,
    function($theme){
      if($theme->status == 1) {
        return $theme;
      }
    }
  );
}

/**
 * @param array $list_theme
 *
 * @return array
 */
function acb_get_regions(array $list_theme){

  return array_map(function($theme){
    return system_region_list($theme->name);
  }, $list_theme);
}