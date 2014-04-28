<?php

require_once(dirname(__FILE__).'/_lib/recaptchalib.php');

/**
 * @validator_field.name    recaptcha
 * @validator_field.label   Recaptcha
 *
 */
class Promotions_Core_Recaptcha_Validator extends Snap_Wordpress_Form2_Validator_Field_Abstract
{
  const INVALID = 'Invalid';
  
  public static $message_templates = array(
    self::INVALID  => 'The text did not match, please try to enter again.'
  );
  
  public function validate()
  {
    $value = $this->get_field()->get_value();
    $resp = recaptcha_check_answer( $this->get_field()->get_config('extra.private'),
                                    $_SERVER["REMOTE_ADDR"],
                                    $_POST["recaptcha_challenge_field"],
                                    $_POST["recaptcha_response_field"]);
    
    if( !$resp->is_valid ){
      self::add_message( self::INVALID );
    }
    
    return $resp->is_valid;
  }
}