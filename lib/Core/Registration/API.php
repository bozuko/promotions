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
    
    $valid = $form->validate();
    // try to validate...
    if( !$valid ){
      
      return array(
        'success' => false,
        'errors'  => array(
          'form'    => $form->get_form_errors(),
          'fields'  => $form->get_field_errors()
        )
      );
      
    }
    
    if( ($result = apply_filters('promotions/api/register/after_validate', true, $form, $params)) !== true ){
      return $result;
    }
    
    $data = $form->get_data();
    
    $key = $data[ get_field('registration_key_field') ];
    
    $now = Snap::inst('Promotions_Functions')->now()->format('Y-m-d H:i:s');
    
    
    // Create a new registration
    $registration_id = wp_insert_post(array(
      'post_type'         => 'registration',
      'post_title'        => $key,
      'post_status'       => 'publish',
      'post_parent'       => get_the_ID(),
      'post_date'         => $now
    ));
    
    /**
     * TODO: we should check to see if this was an error
     * in wp_insert_post
     */
    
    foreach( $data as $name => $value ){
      $value = apply_filters('promotions/registration/meta/save_to_db', $value, $name, get_the_ID());
      update_post_meta( $registration_id, $name, $value );
    }
    
    // should we enter an entry? I think so.
    
    // Add an entry
    $entry_id = wp_insert_post(array(
      'post_type'         => 'entry',
      'post_title'        => 'registration',
      'post_status'       => 'publish',
      'post_parent'       => $registration_id,
      'post_name'         => 'registration-entry-for-'.$registration_id,
      'post_date'         => $now
    ));
    
    update_post_meta( $entry_id, 'entry_type', 'registration');
    
    return array(
      'success'           => true,
      'registration_id'   => $registration_id,
      'entry_id'          => $entry_id
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
