<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
    'id' => 'acf_registration-form',
    'title' => 'Registration Form',
    'fields' => array (
      array (
        'key' => 'field_530d73d749418',
        'label' => 'Form Fields',
        'name' => 'form_fields',
        'type' => 'repeater',
        'sub_fields' => array (
          array (
            'key' => 'field_530d76d74941c',
            'label' => 'Field',
            'name' => 'field',
            'type' => 'form_field',
            'column_width' => '',
          ),
        ),
        'row_min' => '',
        'row_limit' => '',
        'layout' => 'table',
        'button_label' => 'Add Field',
      ),
      array (
        'key' => 'field_532d1228477c2',
        'label' => 'Age Gate',
        'name' => 'age_gate',
        'type' => 'number',
        'instructions' => 'Set to 0 for no age gate.',
        'default_value' => 18,
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'min' => '',
        'max' => '',
        'step' => '',
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'promotion_tab',
          'operator' => '==',
          'value' => 'registration',
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
    