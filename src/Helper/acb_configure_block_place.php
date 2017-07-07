<?php

/**
 * @file acb_configure_block_place.php
 */
module_load_include('php', 'acb', 'src/Form/acb_layout_form');


function acb_node_configure_block($object) {

  return drupal_get_form('acb_layout_form');
    
}
