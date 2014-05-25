<?php

class Promotions_Core_Registration_Plugin extends Promotions_Plugin_Base
{
  protected $flash_key = 'registration_success';
  protected $post_id;
  protected $result;
  
  /**
   * @wp.action         promotions/api/register
   */
  public function register_methods( $api )
  {
    $api->add('Promotions_Core_Registration_API');
  }
  
  public function get_post()
  {
    if( isset($this->post_id) ){
      return get_post( $this->post_id );
    }
    return false;
  }
  
  public function get_result()
  {
    return $this->result;
  }
  
  /**
   * @wp.action promotions/process
   */
  public function main_action()
  {
    
    // first, lets check for a previous success (if redirected)
    if( ($result = Promotions_Flash::get( $this->flash_key )) ){
      $this->result = $result;
      $this->post_id = $result['registration_id'];
      return;
    }
    
    // look for a nonce
    if( !wp_verify_nonce( $_REQUEST['_promotion'], get_post()->post_name ) ){
      return;
    }
    $method = $_REQUEST['_method'];
    $api = Snap::inst('Promotions_API');
    
    if( !$api->has_method( $method ) ){
      return;
    }
    
    $post = $_POST;
    $post = stripslashes_deep( $post );
    foreach( $post as $key => $value ) $post[$key] = wp_kses( $value );
    $result = Snap::inst('Promotions_API')->call($method, $post);
    
    
    if( $result['success'] ){
      Promotions_Flash::set( $this->flash_key, $result);
      wp_safe_redirect(add_query_arg('success', 1));
      exit;
    }
  }
  
  /**
   * @wp.action   promotions/html
   */
  public function main_content()
  {
    $promotion = get_post();
    
    // check to see if we processed anything...
    $template = apply_filters('promotions/content/template', 'register', $promotion);
    
    // otherwise, we will render the form template
    get_template_part('promotion', $template);
  }
  
  /**
   * @wp.filter   promotions/content/template
   */
  public function success_template( $template )
  {
    
    if( !$this->post_id ){
      return $template;
    }
    return 'thanks';
  }
  
  /**
   * @wp.filter   snap/form/field/options
   */
  public function extra_field_options( $options )
  {
    $options['no_save'] = array(
      'input'       => 'checkbox',
      'label'       => 'Don\'t Save to DB'
    );
    $options['export_label'] = array(
      'input'       => 'text',
      'label'       => 'Export Label',
      'placeholder' => 'Leave blank to use "Label"'
    );
    return $options;
  }
  
  /**
   * @wp.action   promotions/analytics/register
   */
  public function register_analytics_buckets( $analytics )
  {
    $analytics->register('registrations', array(
      'label'     => 'Registrations'
    ));
    
    $analytics->register('entries', array(
      'label'     => 'Entries'
    ));
    
    $analytics->register('registration_entries', array(
      'label'     => 'Registration Entries'
    ));
  }
  
  /**
   * @wp.action   promotions/api/result?method=register
   */
  public function increment_counters( $result )
  {
    if( $result && @$result['success'] ){
      Snap::inst('Promotions_Analytics')
        ->increment('registrations')
        ->increment('entries')
        ->increment('registration_entries');
    }
    return $result;
  }
}