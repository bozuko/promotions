<?php

/**
 * @validator_form.name                     entrylimit
 * @validator_form.label                    Entry Limit
 *
 * @validator_form.args.source.label        Source
 * @validator_form.args.source.input        field
 * @validator_form.args.source.default      18
 *
 * @validator_form.args.frequency.label     Frequency
 * @validator_form.args.frequency.input     select
 * @validator_form.args.frequency.options   ["once","daily","weekly"]
 *
 */
class Promotions_Core_EntryLimit_Form_Validator extends Snap_Wordpress_Form2_Validator_Form_Abstract
{
  
  const ALREADY_ENTERED = 'AlreadyEntered';
  
  public static $message_templates = array(
    self::ALREADY_ENTERED  => 'Sorry, you have already entered today'
  );
  
  public function validate()
  {
    global $wpdb;
    
    $field = $this->get_form()->get_field( $this->get_config('arg.source') );
    
    
    // okay, we need to figure out how often 
    
    $sql = "
      SELECT COUNT(*) FROM {$wpdb->posts} `entry`
        JOIN {$wpdb->posts} `reg` ON `reg`.`post_type` = 'registration'
          AND `reg`.`ID` = `entry`.`post_parent`
          AND `reg`.`post_parent` = %d
        WHERE `reg`.`post_title` = %s
    ";
    
    switch( $this->get_config('arg.frequency') ){
      
      case 'weekly':
        
        $timestamp = Snap::inst('Promotions_Functions')->now()->getTimestamp();
        $week = strftime("%U", $timestamp);
        
        $sql.="
          AND DATE_FORMAT(`entry`.`post_date`, '%%U') = %s
        ";
        $statement = $wpdb->prepare($sql, get_the_ID(), $field->get_value(), $week);
        break;
      
      case 'daily':
        
        $date = Snap::inst('Promotions_Functions')->now()->format('Y-m-d');
        
        $sql.="
          AND DATE_FORMAT(`entry`.`post_date`, '%%Y-%%m-%%d') = %s
        ";
        $statement = $wpdb->prepare($sql, get_the_ID(), $field->get_value(), $date);
        break;
      
      case 'once':
      default:
        $statement = $wpdb->prepare($sql, get_the_ID(), $field->get_value() );
        break;
      
    }
    
    if( $wpdb->get_var( $statement ) ){
    
      $this->add_message( self::ALREADY_ENTERED );
      return false;
    }
    return true;
  }
}