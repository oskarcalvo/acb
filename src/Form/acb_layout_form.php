<?php

/**
 * @file Form to configure the layout.
 */

function acb_layout_form($form,$form_state,$objet = NULL, $objet = NULL,
                         $layout = NULL) {


  $list_themes = list_themes();

  $theme_regions = array_map(function($theme){
    return system_region_list($theme->name);
  }, $list_themes);


  $form['url'] = [
    '#type' => 'textfield',
    '#title' => t('Url'),
    '#default_value' => '',
    '#size' => 60,
    '#maxlength' => 128,
    //'#required' => TRUE,
    //'#element_validate' => ['_acb_validate_url'],
  ];

  $form['theme'] = [
    '#type' => 'vertical_tabs',
    '#title' => 'wadus',
  ];

  foreach ($theme_regions as $theme_name => $region_data) {

    $form['theme'][$theme_name] = [
      '#type' => 'fieldset',
      '#title' => $theme_name,
      '#collapsible' => TRUE,
    ];

    foreach ($region_data as $machine_name => $human_name) {
      $form['theme'][$theme_name][$machine_name] = [
        '#type' => 'fieldset',
        '#title' => $human_name,
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
        '#description' => t('Set the blocks for this region.'),
      ];

      $form['theme'][$theme_name][$machine_name]['block'] = [
        '#type' => 'textfield',
        '#title' => 'Block',
        '#autocomplete_path' => 'acb/acb_load_block_autocomplete_callback'
      ];

      $form['theme'][$theme_name][$machine_name]['add_item'] = array(
        '#type' => 'submit',
        '#value' => t("Add another block in $theme_name - $machine_name"),
        '#submit' => ['acb_layout_form_add_item'],
        '#attributes' => [
          'theme' => [$theme_name],
          'region' => [$machine_name]
        ],
      );


    }
  }

  $form['submit'] = array('#type' => 'submit', '#value' => t('Save'));

  return $form;
}


function acb_layout_form_add_item($form, &$form_state) {

  $region = '';
  $theme = '';

  if(isset($form_state['clicked_button']['#attributes']['region'][0])) {
    $region = $form_state['clicked_button']['#attributes']['region'][0];
  }

  if(isset($form_state['clicked_button']['#attributes']['theme'][0])){
    $theme = $form_state['clicked_button']['#attributes']['theme'][0];
  }
  if(!empty($region) && !empty($theme)) {
    $form_state['theme'][$theme][$region]['block']['number']++;
    $form_state['rebuild'] = TRUE;
  }
}