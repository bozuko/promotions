<?php

/**
 * @validator_form.name                     agegate
 * @validator_form.label                    Age Gate
 *
 * @validator_form.args.source.label        Source
 * @validator_form.args.source.input        field
 * @validator_form.args.source.description  If multiple fields are used for the date, use the format "month:fieldname" for "year", "month", and "day" fields
 *
 * @validator_form.args.age.label           Age
 * @validator_form.args.age.input           text
 */
class Promotions_Core_AgeGate_Form_Validator extends Snap_Wordpress_Form2_Validator_Form_Abstract
{
  
  const INELIGIBLE = 'Ineligible';
  
  public static $message_templates = array(
    self::INELIGIBLE  => 'Sorry, you are ineligible to enter this promotion'
  );
  
  public function validate()
  {
    $source = $this->get_config('arg.source');
    
    $value = array();
    
    // check if coming from multiple fields
    if( strpos( $source, ',' ) !== false ){
      foreach( array_map('trim', explode(',', $source)) as $field ){
        list($name, $val) = explode(':',$field);
        $value[$name] = $val;
      }
    }
    
    // single field
    else {
      
    }
    
    return $valid;
  }
}