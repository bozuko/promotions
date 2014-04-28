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
 * @validator_form.args.frequency.input     text
 * @validator_form.args.frequency.default   daily
 *
 */
class Promotions_Core_Duration_Form_Validator extends Snap_Wordpress_Form2_Validator_Form_Abstract
{
  
  const INACTIVE = 'Inactive Time';
  
  public static $message_templates = array(
    self::INACTIVE  => 'Sorry, this promotion is not currently active.'
  );
  
  public function validate()
  {
    $config = $this->get_form()->get_config();
    $valid = Snap::inst('Promotions_Functions')->is_active( $config->get('promotion_id') );
    if( !$valid ){
      $this->add_message( self::INACTIVE );
    }
    return $valid;
  }
}