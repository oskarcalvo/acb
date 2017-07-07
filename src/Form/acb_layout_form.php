<?php

/**
 * @file Form to configure the layout.
 */

function acb_layout_form($form,$form_state,$objet = NULL, $objet = NULL,
                         $layout = NULL) {


  $list_themes = list_themes();

  $regions = array_map(function($theme){
    return system_region_list($theme->name);
  }, $list_themes);


  $form['url'] = [
    '#type' => 'textfield',
    '#title' => t('Url'),
    '#default_value' => '',
    '#size' => 60,
    '#maxlength' => 128,
    '#required' => TRUE,
    '#element_validate' => ['_acb_validate_url'],
  ];

  $form['theme'] = [
    '#type' => 'vertical_tabs',
    '#title' => 'wadus',
  ];

  foreach ($regions as $region_name => $region_data) {

    $form['theme'][$region_name] = [
      '#type' => 'fieldset',
      '#title' => $region_name,
      '#collapsible' => TRUE,
    ];

    foreach ($region_data as $machine_name => $human_name) {
      $form['theme'][$region_name][$machine_name] = [
        '#type' => 'fieldset',
        '#title' => $human_name,
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
        '#description' => t('Set the blocks for this region.'),
      ];

      $form['theme'][$region_name][$machine_name]['field'] = [
        '#type' => 'textfield',
        '#title' => 'Block',
      ];


    }
  }

  $form['submit'] = array('#type' => 'submit', '#value' => t('Save'));

  return $form;
}