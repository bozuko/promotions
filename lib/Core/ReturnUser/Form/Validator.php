<?php
/**
 * @validator_form.name                     returnuser
 * @validator_form.label                    Return User
 *
 */
class Promotions_Core_ReturnUser_Form_Validator extends Snap_Wordpress_Form2_Validator_Form_Abstract
{
  const ALREADY_REGISTERED = 'AlreadyRegistered';
  
  public static $message_templates = array(
    self::ALREADY_REGISTERED  => 'You are already registered'
  );
  
  public function validate()
  {
    global $wpdb;
    
    $key_field = get_field('registration_key_field');
    $value = $this->get_form()->get_field( $key_field )->get_value();
    
    $sql = "
      SELECT COUNT(*) FROM {$wpdb->posts} `reg`
        WHERE `reg`.`post_title` = %s
          AND `reg`.`post_parent` = %d
    ";
    
    
    $statement = $wpdb->prepare( $sql, $value, get_the_ID() );
    
    //die( $statement );
    
    if( $wpdb->get_var( $statement ) ){
      $this->add_message( self::ALREADY_REGISTERED );
      return false;
    }
    return true;
  }
}
