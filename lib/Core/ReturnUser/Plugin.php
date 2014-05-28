<?php

class Promotions_Core_ReturnUser_Plugin extends Promotions_Plugin_Base
{
  
  protected function init()
  {
    $this->register_field_groups('return-user-logic');
    
  }
  /**
   * @wp.action     promotions/init
   */
  public function fix_stats()
  {
    if( !is_admin() ||  @$_REQUEST['fix_stats'] != 'return-entries' ){
      return;
    }
    
    
    global $wpdb;
    
    $sql = <<<SQL
SELECT `post_date`,`ID`,`post_parent` FROM `{$wpdb->posts}`
  WHERE `post_type` = 'entry'
  AND `post_name` LIKE 'registration%'
  AND `post_name` NOT LIKE 'registration-entry-%'
SQL;

    $results = $wpdb->get_results( $sql );
    
    foreach( $results as $result ){
      if(!get_post_meta( $result->ID, 'entry_type', true) ){
        
        $reg = get_post( $result->post_parent );
        $promotion_id = $reg->post_parent;
        
        $timestamp = new DateTime($result->post_date, Snap::inst('Promotions_Functions')->get_timezone() );
        $str = $timestamp->format('Y-m-d H:i:s');
        
        $wpdb->show_errors(true);
        Snap::inst('Promotions_Analytics')
          ->decrement('registration_entries', $timestamp, $promotion_id );
        
        Snap::inst('Promotions_Analytics')
          ->increment('return_entries', $timestamp, $promotion_id );
        
        update_post_meta( $result->ID, 'entry_type', 'return');
        
      }
    }
  }
  
  /**
   * @wp.filter       promotions/features
   */
  public function add_feature( $features )
  {
    $features['returnuser'] = 'Return User Logic';
    return $features;
  }
  
  
  /**
   * @wp.action         promotions/api/register
   */
  public function register_methods( $api )
  {
    $api->add('Promotions_Core_ReturnUser_API');
  }
  
  /**
   * @wp.action   promotions/analytics/register
   */
  public function register_analytics_buckets( $analytics )
  {
    $analytics->register('return_entries', array(
      'label'     => 'Return Entries'
    ));
  }
  
  /**
   * @wp.action         promotions/api/result?method=enter
   */
  public function on_enter( $result )
  {
    
    if( @$result['success'] ){
      Snap::inst('Promotions_Analytics')
        ->increment('entries')
        ->increment('return_entries');
    }
    return $result;
  }
  
  /**
   * Detect if ineligibility has been decided and make sure that we
   * do not display the form if so.
   *
   * @wp.filter       promotions/content/template
   * @wp.priority     20
   */
  public function already_entered( $template, $promotion )
  {
    
    if( !Snap::inst('Promotions_Functions')->is_enabled('returnuser') ) return $template;
    
    $form = Snap::inst('Promotions_PostType_Promotion')->get_registration_form( get_the_ID() );
    if( $form->has_validated() ){
      $errors = $form->get_form_errors();
      if( isset($errors['returnuser']) ){
        return 'already-entered';
      }
    }
    
    return $template;
  }
  
  /**
   * @wp.filter         promotions/registration_form/create
   */
  public function add_validator( $form )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('returnuser') ) return $form;
    
    // add our form validator
    $form->add_validator( new Promotions_Core_ReturnUser_Form_Validator(array(
      'arg' => array(
        'return_user_key'=> get_field('return_user_key')
      ),
      'message' =>array(
        Promotions_Core_ReturnUser_Form_Validator::ALREADY_REGISTERED =>get_field('return_user_error_message')
      )
    )));
  }
  
  /**
   * @wp.filter         promotions/api/result?method=register
   */
  public function filter_register_result( $result, $name, $params )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('returnuser') ) return $result;
    if( $result['success'] ) return $result;
    if( !isset($result['errors']['form']) && !isset($result['errors']['form']['returnuser'] ) ){
      return $result;
    }
    
    // check to see if we can enter.
    $form = Snap::inst('Promotions_PostType_Promotion')->get_registration_form( get_the_ID() );
    $value = $form->get_field( get_field('registration_key_field') )->get_value();
    
    $id = $this->get_registration_id( $value );
    $result['can_enter'] = $this->can_enter( $id );
    return $result;
  }
  
  public function get_registration_id( $value )
  {
    global $wpdb;
    $query = $wpdb->prepare("
      SELECT `p`.`ID` FROM `{$wpdb->posts}` `p`
      WHERE `p`.`post_title` = %s
      AND `p`.`post_type` = 'registration'
      AND `p`.`post_parent` = %d
      LIMIT 1
    ", $value, get_the_ID());
    
    return $wpdb->get_var( $query );
  }
  
  public function can_enter( $id )
  {
    global $wpdb;
    
    $sql = "
      SELECT COUNT(*) FROM {$wpdb->posts} `entry`
        WHERE `entry`.`post_type` = 'entry'
          AND `entry`.`post_parent` = %d
          AND `entry`.`post_title` = 'registration'
    ";
    
    switch( get_field('return_user_entry_frequency') ){
      
      case 'monthly':
        
        $timestamp = Snap::inst('Promotions_Functions')->now()->getTimestamp();
        $month = strftime("%Y-%m", $timestamp);
        
        $sql.="
          AND DATE_FORMAT(`entry`.`post_date`, '%%Y-%%m') = %s
        ";
        $statement = $wpdb->prepare($sql, $id, $month);
        break;
      
      case 'weekly':
        
        $timestamp = Snap::inst('Promotions_Functions')->now()->getTimestamp();
        $week = strftime("%Y-%U", $timestamp);
        
        $sql.="
          AND DATE_FORMAT(`entry`.`post_date`, '%%Y-%%U') = %s
        ";
        $statement = $wpdb->prepare($sql, $id, $week);
        break;
      
      case 'daily':
        
        $date = Snap::inst('Promotions_Functions')->now()->format('Y-m-d');
        
        $sql.="
          AND DATE_FORMAT(`entry`.`post_date`, '%%Y-%%m-%%d') = %s
        ";
        $statement = $wpdb->prepare($sql, $id, $date);
        break;
      
      case 'once':
      default:
        $statement = $wpdb->prepare($sql, $id );
        break;
      
    }
    
    if( $wpdb->get_var( $statement ) ){
      return false;
    }
    return true;
  }
}
