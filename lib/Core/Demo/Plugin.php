<?php

class Promotions_Core_Demo_Plugin extends Promotions_Plugin_Base
{
  
  /**
   * @wp.filter       promotions/features
   */
  public function add_feature( $features )
  {
    $features['demo'] = 'Demo Mode';
    return $features;
  }
  
  /**
   * @wp.filter      promotions/functions/is_before_start
   */
  public function is_before_start( $val, $post_id )
  {
    
    if( !Snap::inst('Promotions_Functions')->is_enabled('demo', $post_id) ) return $val;
    switch( @$_REQUEST['_demo'] ){
      case 'coming-soon':
      case 'before-start':
        return true;
      default:
        return false;
    }
  }
  
  /**
   * @wp.filter      promotions/functions/is_after_end
   */
  public function is_after_end( $val, $post_id )
  {
    // check for demo var
    if( !Snap::inst('Promotions_Functions')->is_enabled('demo', $post_id) ) return $val;
    switch( @$_REQUEST['_demo'] ){
      case 'promotion-over':
      case 'after-end':
        return true;
      default:
        return false;
    }
  }
}
