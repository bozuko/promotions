<?php

class Promotions_Core_Recaptcha_Plugin extends Promotions_Plugin_Base
{
  /**
   * @wp.action         snap/form/field/register
   */
  public function register_field( $form )
  {
    $form->register('Promotions_Core_Recaptcha_Field');
  }
}