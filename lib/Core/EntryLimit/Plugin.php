<?php
/**
 * Age Gate Plugin
 */

class Promotions_Core_EntryLimit_Plugin extends Promotions_Plugin_Base
{
  /**
   * @wp.action snap/form/validator/form/register
   */
  public function register_validator($form)
  {
    $form->register('Promotions_Core_EntryLimit_Form_Validator');
  }
}
