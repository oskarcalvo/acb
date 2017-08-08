<?php

use Drupal\acb\Helper\AcbHelper;
use Drupal\acb\Model\AcbModelClass;
/**
 * @file Form to configure the layout.
 */

/**
 * @param array  $form
 * @param array  $form_state
 * @param string  $path
 * @param object  $acb
 *
 * @return mixed
 */
function acb_layout_form($form, &$form_state, $path = NULL, $acb_record = NULL) {
  
  $theme_regions = AcbHelper::get_enabled_theme_regions();
  
	$form_state['storage']['theme_regions'] = $theme_regions;
	$form['#tree'] = TRUE;

	$url = isset($path)? $path : '';
  $form['url'] = [
    '#type' => 'textfield',
    '#title' => t('Url'),
    '#default_value' => $url,
    '#size' => 60,
    '#maxlength' => 128,
    '#required' => TRUE,
    '#element_validate' => ['_acb_validate_url'],
  ];
  
  $acbid = (isset($acb_record) && isset($acb_record->acbid)) ? $acb_record->acbid : '';
  $form['acbid'] = [
		'#type' => 'hidden',
		'#title' => t('id'),
		'#default_value' => $acbid,
		'#size' => 60,
		'#maxlength' => 128,
		'#required' => TRUE,
		'#disabled' =>  TRUE,
	];

  if(isset($url) ) {
    unset($form['url']['#required']);
    unset($form['url']['#element_validate']);
    $form['url']['#default_value'] = $url;
    $form['url']['#disabled'] =  TRUE;
    // $form['url']['#type'] = 'hidden';
  }


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


      $collapsed = ($form_state['block_number'][$theme_name][$machine_name] >
        1) ? FALSE : TRUE;

      $form['theme'][$theme_name][$machine_name] = [
        '#type' => 'fieldset',
        '#title' => $human_name,
        '#collapsible' => TRUE,
        '#collapsed' => $collapsed,
        '#description' => t('Set the blocks for this region.'),
      ];

      for($i = 1; $i <= $form_state['block_number'][$theme_name][$machine_name]; $i++) {
      	
        $form['theme'][$theme_name][$machine_name][$i] = [
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

/**
 * @param $form
 * @param $form_state
 */
function acb_layout_form_add_item($form, &$form_state) {

  $region = $form_state['clicked_button']['#attributes']['region'][0];
  $theme = $form_state['clicked_button']['#attributes']['theme'][0];
  $form_state['block_number'][$theme][$region] = $form_state['clicked_button']['#attributes']['block_number'];
  $form_state['rebuild'] = TRUE;

}

function acb_layout_form_submit($form, &$form_state) {
	
	unset($form_state['values']['theme']['theme__active_tab']);
	$results = AcbHelper::clean_submited_values(
		$form_state['values']['theme'],
		$form_state['storage']['theme_regions']
	);
	$new_record = new AcbModelClass();
	if(isset($form_state['values']['acbid']) && ($form_state['values']['acbid']) ){
		//update a record
		$new_record->update(
			$form_state['values']['url'],
			$form_state['values']['theme'],
			$form_state['values']['acbid']
		);
	}else {
		// save new record
		$new_record->save(
			$form_state['values']['url'],
			$form_state['values']['theme']
		);
	}
	
}

/**
 * @param array $url
 * @return bool
 */
function _acb_validate_url($url) {
	return (url_is_external($url['#value']) === TRUE) ?  FALSE : TRUE;
}