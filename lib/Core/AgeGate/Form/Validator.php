<?php

/**
 * @validator_form.name                 agegate
 * @validator_form.label                Age Gate
 *
 * @validator_form.args.source.label    Source
 * @validator_form.args.source.input    field
 *
 * @validator_form.args.age.label       Age
 * @validator_form.args.age.input       text
 */
class Promotions_Core_AgeGate_Form_Validator extends Snap_Wordpress_Form2_Validator_Form_Abstract
{
  
  const INELIGIBLE = 'Ineligible';
  
  public static $message_templates = array(
    self::INELIGIBLE  => 'Sorry, you are ineligible to enter this promotion'
  );
  
  public function validate()
  {
    $valid = $this->form->get_field('first_name')->get_value() == 'Mark';
    if( !$valid ){
      $this->add_message( self::INELIGIBLE );
    }
    return $valid;
  }
}