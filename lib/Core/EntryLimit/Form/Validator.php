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
class Promotions_Core_EntryLimit_Form_Validator extends Snap_Wordpress_Form2_Validator_Form_Abstract
{
  
  const ALREADY_ENTERED = 'Already Entered';
  
  public static $message_templates = array(
    self::ALREADY_ENTERED  => 'Sorry, you have already entered today'
  );
  
  public function validate()
  {
    
    return true;
  }
}