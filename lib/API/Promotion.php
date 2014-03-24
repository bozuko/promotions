<?php

class Promotions_API_Promotion extends Snap_Wordpress_Plugin
{
  
  /**
   * @wp.filter         promotions/api/register_methods
   */
  public function register( $methods )
  {
    $methods->enter = array(
        'fn'              => array(&$this, 'enter')
      , 'request_method'  => 'post'
    );
    $methods->ping = array(
        'fn'              => array(&$this, 'ping')
      , 'request_method'  => array('get','post')
    );
    return $methods;
  }
  
  public function enter( $params=array() )
  {
    return array(
      'success' => true,
      'params'  => $params
    );
  }
  
  public function ping( $params=array() )
  {
    return array(
        'ping'    => true
      , 'params'  => $params
    );
  }
  
}
