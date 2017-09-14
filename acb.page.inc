<?php

use Drupal\acb\Model\AcbModelClass;
/**
 * @file acb_page.inc
 */

function acb_node_configure_block($type, $object) {
  $path = $type.'/'.$object->nid;
  
  $acb = new AcbModelClass();
  $acb_record = $acb->load_by_url($path);
  if(is_object($acb_record)) {
    if (isset($acb_record->acbid)) {
      $acb_record->data = unserialize($acb_record->data);
    }
  }
  else {
    //if we don't have records use the default block configuration.
  }
  
  
  return drupal_get_form('acb_layout_form',$path, $acb_record);
}
