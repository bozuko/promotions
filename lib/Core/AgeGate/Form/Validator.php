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
 *
 * @validator_form.args.exceptions.label        Exceptions
 * @validator_form.args.exceptions.input        text
 * @validator_form.args.exceptions.description  Exceptions in the format <pre>field_name=value:age</pre> separated by commas
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
    
    if( ($exceptions = $this->get_config('arg.exceptions')) ){
      $has_all_fields = true;
      $exceptions = explode(';', $exceptions);
      foreach( $exceptions as $exception ){
        
        list($condition, $exception_age) = explode(':', $exception);
        list($field, $values) = explode('=', $condition);
        $values = explode(',', $values);
        
        $field = $this->get_form()->get_field($field);
        if( !$field || !$field->get_value() ){
          $has_all_fields = false;
        }
        
        if( $field && in_array( $field->get_value(), $values ) ){
          $age = $exception_age;
        }
      }
      
      if( !$has_all_fields ) return true;
    }
    
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