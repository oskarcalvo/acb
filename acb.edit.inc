<?php

/**
 * @file acb.edit.inc
 */

/**
 * @param array $acb_record
 */
function acb_edit_record($acb_record) {
  if(is_object($acb_record) && isset($acb_record->acbid)) {
    $acb_record->data = unserialize($acb_record->data);
  }
  return drupal_get_form('acb_layout_form', $acb_record->url,$acb_record);
  

}