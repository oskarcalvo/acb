<?php



use drupal\abr\Helper\AbrLoadBlock;
/**
 * @file
 */
function abr_settings_form() {
	$form = [];
	
	$form['abr_list_modules'] = [
		'#type' => 'checkboxes',
		'#title' => t('Modules defining blocks that can be referenced'),
		'#options' => AbrLoadBlock::list_of_modules_with_blocks(),
		'#default_value' => variable_get('abr_list_modules', NULL),
		'#description' => t('If no modules are selected, blocks from all modules will be available.'),
	];
	
	return system_settings_form($form);
}