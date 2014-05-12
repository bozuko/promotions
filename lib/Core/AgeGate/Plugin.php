<?php
/**
 * Age Gate Plugin
 */
class Promotions_Core_AgeGate_Plugin extends Promotions_Plugin_Base
{
  
  /**
   * @wp.action snap/form/validator/form/register
   */
  public function register_validator($form)
  {
    $form->register('Promotions_Core_AgeGate_Form_Validator');
  }
  
  /**
   * Detect if ineligibility has been decided and make sure that we
   * do not display the form if so.
   *
   * @wp.filter       promotions/content/template
   * @wp.priority     1000
   */
  public function ineligible_template( $template, $promotion )
  {
    if( isset($_COOKIE['ineligible'] ) ){
      return 'ineligible';
    }
    return $template;
  }
  
  /**
   * @wp.filter       promotions/api/can_call_method
   */
  public function disable_registration()
  {
    // check for cookie
    if( isset($_COOKIE['ineligible'] ) ){
      //return false;
    }
    return true;
  }
}
