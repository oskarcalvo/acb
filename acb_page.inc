<?php

/**
 * @file acb_page.inc
 */

function acb_node_configure_block($type, $object) {
  $path = $type.'/'.$object->nid;
  return drupal_get_form('acb_layout_form',$path, $type);
    
}
