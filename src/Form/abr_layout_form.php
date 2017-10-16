<?php

use Drupal\abr\Helper\AbrHelper;
use Drupal\abr\Model\AbrModelClass;
/**
 * @file Form to configure the layout.
 */

/**
 * @param array  $form
 * @param array  $form_state
 * @param string  $path
 * @param object  $abr
 *
 * @return mixed
 */
function abr_layout_form($form, &$form_state, $path = NULL, $abr_record = NULL) {
  
  $theme_regions = AbrHelper::get_enabled_theme_regions();
  
	$form_state['storage']['theme_regions'] = $theme_regions;
	$form['#tree'] = TRUE;

	$url = isset($path)? $path : NULL;
  $form['url'] = [
    '#type' => 'textfield',
    '#title' => t('Url'),
    '#default_value' => $url,
    '#size' => 60,
    '#maxlength' => 128,
    '#required' => TRUE,
    '#element_validate' => ['_abr_validate_url'],
  ];
  
  $abrid = (isset($abr_record) && isset($abr_record->abrid)) ? $abr_record->abrid : '';
  $form['abrid'] = [
		'#type' => 'hidden',
		'#title' => t('id'),
		'#default_value' => $abrid,
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

	$blocks = isset($abr_record->data) ? $abr_record->data : NULL ;
  foreach ($theme_regions as $theme_name => $regions) {
    
    $form['theme'][$theme_name] = [
      '#type' => 'fieldset',
      '#title' => $theme_name,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
	
    foreach ($regions as $machine_name => $human_name) {
	
	    $quantity_blocks = count($blocks[$theme_name][$machine_name]);
	    
      if( $quantity_blocks == 0 && empty( $form_state['block_number'][$theme_name][$machine_name])) {
	      $form_state['block_number'][$theme_name][$machine_name] = 1;
      }
        
      if( $quantity_blocks >= 1 &&
          isset( $form_state['block_number'][$theme_name][$machine_name]) &&
          $quantity_blocks >=  $form_state['block_number'][$theme_name][$machine_name]) {
	      $form_state['block_number'][$theme_name][$machine_name] =  $quantity_blocks;
      }
      
      if( $quantity_blocks >= 1 && !isset( $form_state['block_number'][$theme_name][$machine_name])) {
	      $form_state['block_number'][$theme_name][$machine_name] =  $quantity_blocks;
      }
  
      $collapsed = (  count($blocks[$theme_name][$machine_name]) === 0) ? TRUE : FALSE;
      
      $form['theme'][$theme_name][$machine_name] = [
        '#type' => 'fieldset',
        '#title' => $human_name,
        '#collapsible' => TRUE,
        '#collapsed' => $collapsed,
        '#description' => t('Set the blocks for this region.'),
      ];

      for($i = 1; $i <=  $form_state['block_number'][$theme_name][$machine_name]; $i++) {
      	
        $form['theme'][$theme_name][$machine_name][$i] = [
          '#type' => 'textfield',
          '#title' => t('Block'),
	        '#default_value' => isset($blocks[$theme_name][$machine_name][$i])
		        ? $blocks[$theme_name][$machine_name][$i] : '',
          '#autocomplete_path' => 'abr/abr_load_block_autocomplete_callback'
        ];
      }

      $form['theme'][$theme_name][$machine_name]['add_item'] = array(
        '#type' => 'submit',
        '#value' => t("Add another block in $theme_name - $machine_name"),
        '#submit' => ['abr_layout_form_add_item'],
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
function abr_layout_form_add_item($form, &$form_state) {

  $region = $form_state['clicked_button']['#attributes']['region'][0];
  $theme = $form_state['clicked_button']['#attributes']['theme'][0];
  $form_state['block_number'][$theme][$region] = $form_state['clicked_button']['#attributes']['block_number'];
  $form_state['rebuild'] = TRUE;

}

function abr_layout_form_submit($form, &$form_state) {
	
	unset($form_state['values']['theme']['theme__active_tab']);
	$results = AbrHelper::clean_submited_values(
		$form_state['values']['theme'],
		$form_state['storage']['theme_regions']
	);
	$new_record = new AbrModelClass();
	if(isset($form_state['values']['abrid']) && ($form_state['values']['abrid']) ){
		//update a record
		$new_record->update(
			$form_state['values']['url'],
			$results,
			$form_state['values']['abrid']
		);
	}else {
		// save new record
		$new_record->save(
			$form_state['values']['url'],
			$results
		);
	}
	
}

/**
 * @param array $url
 * @return bool
 */
function _abr_validate_url($url) {
	return (url_is_external($url['#value']) === TRUE) ?  FALSE : TRUE;
}
