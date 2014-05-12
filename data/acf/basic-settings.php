<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
    'id' => 'acf_basic-settings',
    'title' => 'Basic Settings',
    'fields' => array (
      array (
        'key' => 'field_52feec1628450',
        'label' => 'Start',
        'name' => 'start',
        'type' => 'date_time_picker',
        'show_date' => 'true',
        'date_format' => 'm/d/y',
        'time_format' => 'h:mm:ss tt',
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
        'time_format' => 'h:mm:ss tt',
        'show_week_number' => 'false',
        'picker' => 'slider',
        'save_as_timestamp' => 'true',
        'get_as_timestamp' => 'false',
      ),
      array (
        'key' => 'field_536b90c09e9a0',
        'label' => 'Features',
        'name' => 'promotion_enabled_features',
        'type' => 'checkbox',
        'choices' => array (
          'demo' => 'Demo Mode',
        ),
        'default_value' => '',
        'layout' => 'vertical',
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
    