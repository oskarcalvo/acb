<?php
/**
 * @file acb_delete_layout.php
 */

function acb_delete_layout($form, &$form_state) {
 
  $form['description'] = [
    '#prefix' => '<div>',
    '#markup' => t('Are you sure that you want to delete this record?'),
    '#suffix' => '</div>',
  ];
  $form['delete'] = array(
    '#type' => 'button',
    '#value' => t('Delete'),
  );
  $form['cancel'] = array(
    '#markup' => l(t('Cancel'), 'admin/structure/acb/list'),
  );
  
  return $form;
}


function acb_delete_layout_submit($form, &$form_state) {

}


