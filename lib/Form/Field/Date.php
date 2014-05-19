<?php

/**
 * @field.name    date
 * @field.label   Date
 *
 * @field.extra.input_type.input        select
 * @field.extra.input_type.options      ["Month (2 Fields)","Day (3 Fields)","HTML5 Date Field"]
 * @field.extra.input_type.label        Date Field Type
 *
 * @field.extra.month_format.label      Month Format
 * @field.extra.month_format.default    F
 * 
 */
class Promotions_Form_Field_Date extends Snap_Wordpress_Form2_Field_Abstract
{
  public function is_empty()
  {
    $value = @$this->get_value();
    return ( !@$value['month'] && !@$value['exchange'] && !@$value['number'] );
  }
  
  public function get_value_formatted()
  {
    $value = (array) $this->get_value();
    return $this->valid ?
      $value['year'].'-'.$value['month'].'-'.(@$value['day'] ? $value['day'] : 1) :
      '';
  }
  
  public function get_html()
  {
    $value = (array) $this->get_value();
    
    $month_options = array();
    
    $month_options[] = array(
      'tag'         => 'option',
      'content'     => '-Month-',
      'attributes'  => array(
        'value'       => ''
      )
    );
    
    for($i=1; $i<=12; $i++) {
      $month_name = date( $this->get_config('extra.month_format'), strtotime( '2014-'.$i.'-1'));
      $value = $i;
      $month_option = array(
        'tag'       => 'option',
        'content'   => $month_name,
        'attributes'  => array(
          'value'     => $i
        )
      );
      if( $value['month'] == $value ) $month_option['attributes']['selected'] = 'selected';
      $month_options[] = $month_option;
    }
    
    $year_options = array();
    
    $year_options[] = array(
      'tag'         => 'option',
      'content'     => '-Year-',
      'attributes'  => array(
        'value'       => ''
      )
    );
    
    $year = date('Y');
    for($i=0; $i<120; $i++) {
      $val = $year - $i;
      $year_option = array(
        'tag'       => 'option',
        'content'   => $val,
        'attributes'  => array(
          'value'     => $val
        )
      );
      if( $value['year'] == $val ){
        $year_option['attributes']['selected'] = 'selected';
      }
      $year_options[] = $year_option;
    }
    
    $month = Snap_Util_Html::tag('select', $this->apply_filters('attributes', array(
      'name'  => $this->get_name().'[month]',
      'id'    => $this->get_id(),
      'class' => array_merge(array('date-month'), $this->get_classes())
    )), $month_options);
    
    $year = Snap_Util_Html::tag('select', $this->apply_filters('attributes', array(
      'name'  => $this->get_name().'[year]',
      'id'    => $this->get_id(),
      'class' => array_merge(array('date-year'), $this->get_classes())      
    )), $year_options);
    
    $html = '<div class="complex-input">'.$month.' '.$year.'</div>';
    return $this->apply_filters('html', $html);
    
  }
  
  public function get_jquery_validate_config()
  {
    return false;
  }
}
