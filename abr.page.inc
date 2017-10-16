<?php

use Drupal\abr\Model\AbrModelClass;
/**
 * @file abr.page.inc
 */

function abr_node_configure_block($type, $node) {
  $path = $type.'/'.$node->nid;
  
  $abr = new AbrModelClass();
  $abr_record = $abr->load_by_url($path);
  if(is_object($abr_record)) {
    if (isset($abr_record->abrid)) {
      $abr_record->data = unserialize($abr_record->data);
    }
  }
  /*
  else {
    $abr_record = new stdClass();
    // $abr_record->data =
  }
  */
  
  return drupal_get_form('abr_layout_form',$path, $abr_record);
}
