<?php

/**
 * @file Form to configure the layout.
 */

function acb_layout_form($form,$form_state,$objet = NULL, $layout = NULL) {

  $list_themes = list_themes();
  $list_themes = acb_get_enabled_themes($list_themes);
  $theme_regions = acb_get_regions($list_themes);

  $form['#tree'] = TRUE;

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
    '#title' => t('Configure layout'),
  ];


  foreach ($theme_regions as $theme_name => $regions) {

    $form['theme'][$theme_name] = [
      '#type' => 'fieldset',
      '#title' => $theme_name,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    foreach ($regions as $machine_name => $human_name) {

      if(empty($form_state['block_number'][$theme_name][$machine_name])){
        $form_state['block_number'][$theme_name][$machine_name] = 1;
      }

      $form['theme'][$theme_name][$machine_name] = [
        '#type' => 'fieldset',
        '#title' => $human_name,
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
        '#description' => t('Set the blocks for this region.'),
      ];

      for($i = 1; $i <= $form_state['block_number'][$theme_name][$machine_name]; $i++) {
        $form['theme'][$theme_name][$machine_name][$i]['block'] = [
          '#type' => 'textfield',
          '#title' => t('Block'),
          '#autocomplete_path' => 'acb/acb_load_block_autocomplete_callback'
        ];
      }


      $form['theme'][$theme_name][$machine_name]['add_item'] = array(
        '#type' => 'submit',
        '#value' => t("Add another block in $theme_name - $machine_name"),
        '#submit' => ['acb_layout_form_add_item'],
        '#attributes' => [
          'theme' => [$theme_name],
          'region' => [$machine_name],
          'block_number' => $i,
        ],
      );
      unset($i);

    }
  }

  $form['submit'] = array('#type' => 'submit', '#value' => t('Save'));

  return $form;
}


function acb_layout_form_add_item($form, &$form_state) {


  $region = $form_state['clicked_button']['#attributes']['region'][0];
  $theme = $form_state['clicked_button']['#attributes']['theme'][0];
  $form_state['block_number'][$theme][$region] = $form_state['clicked_button']['#attributes']['block_number'];
  $form_state['rebuild'] = TRUE;

}