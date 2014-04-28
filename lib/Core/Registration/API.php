<?php

class Promotions_Core_Registration_API
{
  
  /**
   * @methods     post
   */
  public function register( $params=array() )
  {
    $form = Snap::inst('Promotions_PostType_Promotion')->get_registration_form( get_the_ID() );
    
    $form->set_data( $params );
    
    // try to validate...
    if( !$form->validate() ){
      
      return array(
        'success' => false,
        'errors'  => array(
          'form'    => $form->get_form_errors(),
          'fields'  => $form->get_field_errors()
        )
      );
      
    }
    
    $data = $form->get_data();
    
    $key = $data[ get_field('registration_key_field') ];
    
    // Create a new registration
    $post_id = wp_insert_post(array(
      'post_type'         => 'registration',
      'post_title'        => $key,
      'post_status'       => 'publish',
      'post_parent'       => get_the_ID()
    ));
    
    
    foreach( $data as $name => $value ){
      $value = apply_filters('promotions/registration/meta/save_to_db', $value, $name, get_the_ID());
      update_post_meta( $post_id, $name, $value );
    }
    
    return array(
      'success'           => true,
      'registration_id'   => $post_id
    );
  }
  
  /**
   * @methods     post
   */
  public function enter( $params=array() )
  {
    return array(
      'success' => true,
      'params'  => $params
    );
  }
  
  /**
   * @methods     ["post","get"]
   */
  public function ping( $params=array() )
  {
    return array(
        'ping'    => 'pong'
      , 'params'  => $params
    );
  }
}
