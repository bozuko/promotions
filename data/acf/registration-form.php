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
        'key' => 'field_535cfef8fcd34',
        'label' => 'Key Field',
        'name' => 'registration_key_field',
        'type' => 'text',
        'instructions' => 'This field will be used as the unique key in the database.',
        'default_value' => 'email',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'formatting' => 'html',
        'maxlength' => '',
      ),
      array (
        'key' => 'field_5345bc4b16eb6',
        'label' => 'Form Validators',
        'name' => 'form_validators',
        'type' => 'repeater',
        'instructions' => 'Form validators are a high priority validation that can replace the entire form with a message (like ineligible, etc).',
        'sub_fields' => array (
          array (
            'key' => 'field_5345bc5416eb7',
            'label' => 'Form Validator',
            'name' => 'validator',
            'type' => 'form_validator',
            'column_width' => '',
          ),
        ),
        'row_min' => '',
        'row_limit' => '',
        'layout' => 'table',
        'button_label' => 'Add Validator',
      ),
      array (
        'key' => 'field_5331ecbb7f6c0',
        'label' => 'Template HTML',
        'name' => 'registration_template_html',
        'type' => 'code_area',
        'instructions' => 'Leave this template blank to auto-generate the form. All shortcodes are allowed. The shortcode for fields is [field name="name"]. A theme may or may not use this template.',
        'language' => 'htmlmixed',
        'theme' => 'blackboard',
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
    