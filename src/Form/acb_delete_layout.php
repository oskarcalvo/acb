<?php
use Drupal\acb\Model\AcbModelClass;
/**
 * @file acb_delete_layout.php
 */

function acb_delete_layout($form, &$form_state) {
  $form_state['storage']['acbid'] = arg(3);
  
  $form['description'] = [
    '#prefix' => '<div>',
    '#markup' => t('Are you sure that you want to delete this record?'),
    '#suffix' => '</div>',
  ];
  $form['delete'] = array(
    '#type' => 'submit',
    '#value' => t('Delete'),
  );
  $form['cancel'] = array(
    '#markup' => l(t('Cancel'), 'admin/structure/acb/list'),
  );
  
  return $form;
}


function acb_delete_layout_submit($form, &$form_state) {
  
  if ($form_state['values']['op'] === 'Delete' && is_numeric($form_state['storage']['acbid'])) {
    $delete = AcbModelClass::delete((int) $form_state['storage']['acbid']);
    if ($delete === '1') {
      drupal_set_message(t('The record has been deleted'));
      $form_state['redirect'] = 'admin/structure/acb';
    }
  }
}


