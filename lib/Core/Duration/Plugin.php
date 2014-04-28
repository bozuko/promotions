<?php
/**
 * Duration Plugin
 */
class Promotions_Core_Duration_Plugin extends Promotions_Plugin_Base
{
  
  /**
   * Detect if ineligibility has been decided and make sure that we
   * do not display the form if so.
   *
   * @wp.filter       promotions/content/template
   * @wp.priority     100
   */
  public function inactive_template( $template, $promotion )
  {
    if( Snap::inst('Promotions_Functions')->is_before_start( $promotion->ID ) ){
      return 'beforestart';
    }
    if( Snap::inst('Promotions_Functions')->is_after_end( $promotion->ID ) ){
      return 'afterend';
    }
    return $template;
  }
  
  /**
   * @wp.action   promotions/registration_form/create
   */
  public function add_validator( $form )
  {
    $form->add_validator( new Promotions_Core_Duration_Form_Validator() );
  }
}
