<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
    'id' => 'acf_promotion-messages',
    'title' => 'Promotion Messages',
    'fields' => array (
      array (
        'key' => 'field_532fa1361a1d6',
        'label' => 'Before Start',
        'name' => 'message_before_start',
        'type' => 'wysiwyg',
        'default_value' => '',
        'toolbar' => 'full',
        'media_upload' => 'yes',
      ),
      array (
        'key' => 'field_532fa1411a1d7',
        'label' => 'After End',
        'name' => 'message_after_end',
        'type' => 'wysiwyg',
        'default_value' => '',
        'toolbar' => 'full',
        'media_upload' => 'yes',
      ),
      array (
        'key' => 'field_5376418dcac27',
        'label' => 'Introduction',
        'name' => 'message_introduction',
        'type' => 'wysiwyg',
        'default_value' => '',
        'toolbar' => 'full',
        'media_upload' => 'yes',
      ),
      array (
        'key' => 'field_532fa14e1a1d8',
        'label' => 'Thank You',
        'name' => 'message_thank_you',
        'type' => 'wysiwyg',
        'default_value' => '',
        'toolbar' => 'full',
        'media_upload' => 'yes',
      ),
      array (
        'key' => 'field_532fa15a1a1d9',
        'label' => 'Ineligible',
        'name' => 'message_ineligible',
        'type' => 'wysiwyg',
        'default_value' => '',
        'toolbar' => 'full',
        'media_upload' => 'yes',
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'promotion_tab',
          'operator' => '==',
          'value' => 'messages',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'normal',
      'layout' => 'no_box',
      'hide_on_screen' => array (
      ),
    ),
    'menu_order' => 0,
  ));
}
    