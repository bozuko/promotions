<?php

/**
 * @field.name    phone
 * @field.label   Phone
 *
 * @field.extra.error_message.input        text
 * @field.extra.error_message.label        Phone Error Message
 */
class Promotions_Form_Field_Phone extends Snap_Wordpress_Form2_Field_Abstract
{
  
  public function init()
  {
    $validator = new Promotions_Form_Validator_Field_Phone(array(
      'message' => array(
        Promotions_Form_Validator_Field_Phone::INVALID_PHONE =>
          $this->get_config('extra.error_message')
      )
    ));
    $this->add_validator($validator);
  }
  
  public function is_empty()
  {
    $value = @$this->get_value();
    return ( !@$value['area'] && !@$value['exchange'] && !@$value['number'] );
  }
  
  public function get_value_formatted()
  {
    $value = (array) $this->get_value();
    return $this->valid && !$this->is_empty() ?
      '('.$value['area'].') '.$value['exchange'].' - '.$value['number'] :
      '';
  }
  
  public function get_html()
  {
    $value = (array) $this->get_value();
    
    $area = Snap_Util_Html::tag('input', $this->apply_filters('attributes', array(
      'name'  => $this->get_name().'[area]',
      'id'    => $this->get_id(),
      'class' => array_merge(array('phone-area'), $this->get_classes()),
      'value' => @$value['area'],
      'maxlength' => 3
    )));
    
    $exchange = Snap_Util_Html::tag('input', $this->apply_filters('attributes', array(
      'name'  => $this->get_name().'[exchange]',
      'id'    => $this->get_id().'_exchange',
      'class' => array_merge(array('phone-exchange'), $this->get_classes()),
      'value' => @$value['exchange'],
      'maxlength' => 3
    )));
    
    $number = Snap_Util_Html::tag('input', $this->apply_filters('attributes', array(
      'name'  => $this->get_name().'[number]',
      'id'    => $this->get_id().'_number',
      'class' => array_merge(array('phone-number'), $this->get_classes()),
      'value' => @$value['number'],
      'maxlength' => 4
    )));
    
    $html = '<div class="complex-input">( '.$area.' ) '.$exchange.' - '.$number.'</div>';
    return $this->apply_filters('html', $html);
    
  }
  
  public function get_jquery_validate_config()
  {
    return false;
  }
}
