<?php

use Drupal\acb\Model\AcbModelClass;
/**
 * @file acb_page.inc
 */

function acb_node_configure_block($type, $object) {
  $path = $type.'/'.$object->nid;
  
  $acb = new AcbModelClass();
  $acb_record = $acb->load_by_url($path);
  
  return drupal_get_form('acb_layout_form',$path, $acb_record);
}
