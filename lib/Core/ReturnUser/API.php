<?php

class Promotions_Core_ReturnUser_API extends Promotions_Plugin_Base
{
  /**
   * @methods     post
   */
  public function is_registered( $params=array() )
  {
    
    $key = get_field('registration_key_field');
    $value = @$params[$key];
    
    if( !$value ){
      return array(
        'success'       => false,
        'error'         => 'NO VALUE',
        'error_message' => 'No Value Passed'
      );
    }
    
    $id = Snap::inst('Promotions_Core_ReturnUser_Plugin')->get_registration_id( $value );
    
    if( !$id ){
      return array(
        'success'       => false,
        'error'         => 'NOT FOUND',
        'error_message' => 'No registration found.'
      );
    }
    
    return array(
      'success'         => true
    );
  }
  
  /**
   * @methods     post
   */
  public function enter( $params=array() )
  {
    
    $key = get_field('registration_key_field');
    $value = @$params[$key];
    
    if( !@$value ){
      return array(
        'success'       => false,
        'error'         => 'NO VALUE',
        'error_message' => 'No Value Passed'
      );
    }
    
    $id = Snap::inst('Promotions_Core_ReturnUser_Plugin')->get_registration_id( $value );
    
    if( !$id ){
      return array(
        'success'       => false,
        'error'         => 'NOT FOUND',
        'error_message' => 'No registration found.'
      );
    }
    
    // okay, now lets see if we can enter
    if( !Snap::inst('Promotions_Core_ReturnUser_Plugin')->can_enter( $id ) ){
      return array(
        'success'       => false,
        'error'         => 'ALREADY ENTERED',
        'error_message' => 'This registration has already entered.'
      );
    }
    
    $entry_id = wp_insert_post(array(
      'post_type'     => 'entry',
      'post_parent'   => $id,
      'post_title'    => 'registration',
      'post_status'   => 'publish'
    ));
    
    return array(
      'success'         => true,
      'entry_id'        => $entry_id
    );
    
  }
  
}
