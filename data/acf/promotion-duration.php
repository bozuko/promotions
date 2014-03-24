<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
    'id' => 'acf_promotion-duration',
    'title' => 'Promotion Duration',
    'fields' => array (
      array (
        'key' => 'field_52feec1628450',
        'label' => 'Start',
        'name' => 'start',
        'type' => 'date_time_picker',
        'show_date' => 'true',
        'date_format' => 'm/d/y',
        'time_format' => 'h:mm tt',
        'show_week_number' => 'false',
        'picker' => 'slider',
        'save_as_timestamp' => 'true',
        'get_as_timestamp' => 'false',
      ),
      array (
        'key' => 'field_52feec2428451',
        'label' => 'End',
        'name' => 'end',
        'type' => 'date_time_picker',
        'show_date' => 'true',
        'date_format' => 'm/d/y',
        'time_format' => 'h:mm tt',
        'show_week_number' => 'false',
        'picker' => 'slider',
        'save_as_timestamp' => 'true',
        'get_as_timestamp' => 'false',
      ),
      array (
        'key' => 'field_532d119896645',
        'label' => 'Age Gate',
        'name' => 'age_gate',
        'type' => 'number',
        'default_value' => 18,
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'min' => 0,
        'max' => '',
        'step' => '',
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'promotion_tab',
          'operator' => '==',
          'value' => 'basic',
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
    