<?php

class Promotions_Form_Validator_Field_Phone extends Snap_Wordpress_Form2_Validator_Field_Abstract
{
  const INVALID_PHONE = 'Invalid Phone';
  
  public static $message_templates = array(
    self::INVALID_PHONE  => 'Please enter a valid phone number'
  );
  
  public function validate()
  {
    if( !$this->_validate() ){
      $this->add_message( self::INVALID_PHONE );
      return false;
    }
    
    return true;
  }
  
  protected function _validate()
  {
    $value = (array)$this->get_field()->get_value();
    if( !preg_match('/^\d{3}$/', @$value['area']) ) return false;
    if( !preg_match('/^\d{3}$/', @$value['exchange']) ) return false;
    if( !preg_match('/^\d{4}$/', @$value['number']) ) return false;
    return true;
  }
  
}