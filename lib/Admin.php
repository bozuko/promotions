<?php

class Promotions_Admin extends Snap_Wordpress_Plugin
{
  /**
   * @wp.action
   */
  public function admin_enqueue_scripts()
  {
    wp_enqueue_style('promotions-admin', PROMOTIONS_URL.'/assets/css/admin.css');
  }
  
  /**
   * @wp.filter
   */
  public function admin_footer_text()
  {
    echo '<span id="footer-thankyou">Promotions powered by Bozuko</span>';
  }
  
  /**
   * @wp.action           promotions/init
   */
  public function init_ui()
  {
    $tabs = Promotions_UI_Tabs::get_instance( 'promotion' );
    $meta_boxes = new Promotions_UI_Promotion_MetaBoxes( $tabs );
  }
  
  /**
   * @wp.filter       acf/load_field/name=promotion_enabled_features
   */
  public function get_features( $field )
  {
    $field['choices'] = apply_filters('promotions/features', array());
    return $field;
  }
  
  /**
   * @wp.filter         acf/location/rule_types
   */
  public function acf_location_rule_types( $types )
  {
    $obj = get_post_type_object('promotion');
    if( !isset($types[$obj->labels->name]) ){
      return array( $obj->labels->name => array('promotion_feature'=>'Feature')) + $types;
    }
    $types[$obj->labels->name]['promotion_feature'] = 'Feature';
    return $types;
  }
  
  /**
   * @wp.filter       acf/location/rule_values/promotion_feature
   */
  public function acf_location_rule_values( $values )
  {
    return apply_filters('promotions/features', array());
  }
  
  /**
   * @wp.filter       acf/location/rule_match/promotion_feature
   */
  public function acf_location_rule_match( $match, $rule, $options )
  {
    $value = $rule['value'];
    $match = false;
    $enabled = Snap::inst('Promotions_Functions')->is_enabled( $value );
    if( $rule['operator'] == '==' ){
      if( get_post_type() == 'promotion' && $enabled ){
        $match = true;
      }
    }
    else if( $rule['operator'] == '!=' ){
      if( get_post_type() !== 'promotion' || !$enabled ){
        $match = true;
      }
    }
    return $match;
  }
  
  /**
   * @wp.filter       promotions/tabs/promotion/register
   * @wp.priority     1
   */
  public function register_tabs( $tabs )
  {
    $tabs['basic']          = 'Basic';
    $tabs['registration']   = 'Registration';
    $tabs['messages']       = 'Messages';
    return $tabs;
  }
}
