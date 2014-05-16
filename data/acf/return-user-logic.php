<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
    'id' => 'acf_return-user-logic',
    'title' => 'Return User Logic',
    'fields' => array (
      array (
        'key' => 'field_536b975ede04c',
        'label' => 'Return User Key',
        'name' => 'return_user_key',
        'type' => 'select',
        'choices' => array (
          'registration_key' => 'Registration Key',
        ),
        'default_value' => 'registration_key',
        'allow_null' => 0,
        'multiple' => 0,
      ),
      array (
        'key' => 'field_536bd97043fb3',
        'label' => 'Return User Error Message',
        'name' => 'return_user_error_message',
        'type' => 'text',
        'default_value' => 'You have already registered.',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'formatting' => 'html',
        'maxlength' => '',
      ),
      array (
        'key' => 'field_536c2bbc39b7c',
        'label' => 'Entry Frequency',
        'name' => 'return_user_entry_frequency',
        'type' => 'select',
        'choices' => array (
          'once' => 'Once',
          'daily' => 'Daily',
          'weekly' => 'Weekly',
          'monthly' => 'Monthly',
        ),
        'default_value' => 'once',
        'allow_null' => 0,
        'multiple' => 0,
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'promotion_feature',
          'operator' => '==',
          'value' => 'returnuser',
          'order_no' => 0,
          'group_no' => 0,
        ),
        array (
          'param' => 'promotion_tab',
          'operator' => '==',
          'value' => 'registration',
          'order_no' => 1,
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
    