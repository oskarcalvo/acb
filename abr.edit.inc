<?php

/**
 * @file abr.edit.inc
 */

/**
 * @param array $abr_record
 */
function abr_edit_record($abr_record) {
  if(is_object($abr_record) && isset($abr_record->abrid)) {
    $abr_record->data = unserialize($abr_record->data);
  }
  return drupal_get_form('abr_layout_form', $abr_record->url,$abr_record);
  

}