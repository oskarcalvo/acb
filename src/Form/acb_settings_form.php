<?php



use drupal\acb\Helper\AcbLoadBlock;
/**
 * @file
 */
function acb_settings_form() {
	$form = [];
	
	$form['acb_list_modules'] = [
		'#type' => 'checkboxes',
		'#title' => t('Modules defining blocks that can be referenced'),
		'#options' => AcbLoadBlock::list_of_modules_with_blocks(),
		// '#default_value' => new AcbLoadBlock::waduis(),
		'#description' => t('If no modules are selected, blocks from all modules will be available.'),
	];
	
	return system_settings_form($form);
}