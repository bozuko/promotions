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
 *
 * @validator_form.args.vtype.label         Validation Type
 * @validator_form.args.vtype.input         select
 * @validator_form.args.vtype.options       {"day":"Validate on Day", "month":"Validate on Month"}
 */
class Promotions_Core_AgeGate_Form_Validator extends Snap_Wordpress_Form2_Validator_Form_Abstract
{
  
  const INELIGIBLE = 'Ineligible';
  
  public static $message_templates = array(
    self::INELIGIBLE  => 'Sorry, you are ineligible to enter this promotion'
  );
  
  public function validate()
  {
    $valid = true;
    $source = $this->get_config('arg.source');
    $age = $this->get_config('arg.age');
    
    $value = array();
    
    // check if coming from multiple fields
    if( strpos( $source, ',' ) !== false ){
      foreach( array_map('trim', explode(',', $source)) as $field ){
        list($name, $val) = explode(':',$field);
        $value[$name] = $this->get_form()->get_field($val)->get_value();
      }
    }
    
    // single field
    else {
      $time = strtotime( $this->get_form()->get_field( $source )->get_value_formatted() );
      $value['year'] = date('Y',$time);
      $value['month'] = date('m',$time);
      $value['day'] = date('d',$time);
    }
    
    $now = Snap::inst('Promotions_Functions')->now();
    
    $valid = apply_filters('promotions/agegate/valid', $this->check_age($value, $age), $value, $this);
    if( !$valid ){
      $this->add_message( self::INELIGIBLE );
      $parsed = parse_url( get_permalink() );
      setcookie('ineligible', true, 0, $parsed['path'] );
    }
    return $valid;
  }
  
  protected function check_age( $value, $age )
  {
    
    $now = Snap::inst('Promotions_Functions')->now();
    
    // okay, easy one first, just check year.
    $diff = (int)$now->format('Y') - (int)$value['year'];
    if( $diff > $age ){
      return true;
    }
    
    if( $diff < $age ){
      return false;
    }
    
    switch( $this->get_config('arg.vtype') ){
      case 'month':
        if( (int)$value['month'] >= (int)$now->format('n') ){
          return false;
        }
        break;
        
      case 'day':
        if( (int)$value['month'] > (int)$now->format('n') ||
            (int)$value['day'] > (int)$now->format('j') ){
          return false;
        }
      
    }
    
    return true;
  }
}