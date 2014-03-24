<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
    'id' => 'acf_promotions-basic',
    'title' => 'Promotions Basic',
    'fields' => array (
      array (
        'key' => 'field_532b8e9b5f5ce',
        'label' => 'Developer Mode',
        'name' => 'promotions_dev',
        'type' => 'true_false',
        'instructions' => 'If this is enabled, ACF fields will be sourced from the database instead of the files. Any modifications to the ACF fields will be saved back to each plugins data directory.',
        'message' => 'Enable Development Mode',
        'default_value' => 0,
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'acf-options-general',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'normal',
      'layout' => 'default',
      'hide_on_screen' => array (
      ),
    ),
    'menu_order' => 0,
  ));
}
    