<?php
/**
 * Entry Limit Plugin
 */

class Promotions_Core_EntryLimit_Plugin extends Promotions_Plugin_Base
{
  /**
   * @wp.action snap/form/validator/form/register
   */
  public function register_validator($form)
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('returnuser') )
      $form->register('Promotions_Core_EntryLimit_Form_Validator');
  }
}
