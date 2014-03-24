<?php

class Promotions_API extends Snap_Wordpress_Plugin
{
  
  public $methods;
  
  /**
   * @wp.action         init
   */
  public function init()
  {
    $methods = new stdClass;
    $this->methods = apply_filters( 'promotions/api/register_methods', $methods );
    add_rewrite_endpoint('api', EP_PERMALINK | EP_ROOT );
  }
  
  /**
   * HTTP api calls
   *
   * @wp.action         template_redirect
   */
  public function http_interface()
  {
    if( is_admin() ) return;
    $name = get_query_var('api');
    if( !$name ) return;
    
    // check if we have this in our methods
    if( !isset( $this->methods->$name ) ){
      return $this->returnJSON(array(
        'error' => 'Invalid API Method'
      ));
    }
    
    $method = $this->methods->$name;
    
    $request_method = strtolower($_SERVER['REQUEST_METHOD']);
    $valid_methods = (array) $method['request_method'];
    if( !in_array( $request_method, $valid_methods ) ){
      return $this->returnJSON(array(
          'error' => 'Invalid request method: '.$request_method
      ));
    }
    
    $contentType = explode(';', $_SERVER['CONTENT_TYPE']);

    if( $contentType[0] == 'application/json'){
      $json = file_get_contents("php://input");
      $params = json_decode( $json, true );
    }
    else {
      $params = $method['request_method'] === 'get' ? $_GET : $_POST;
    }
    
    // additional params
    global $wp_query;
    //$params['wp_query'] = $wp_query;
    if( is_single() ){
      $params['post_id'] = get_the_ID();
    }
    
    $params = apply_filters('promotions/api/params_http', $params);
    $result = $this->call($name, $params);
    
    $result = apply_filters('promotions/api/result_http', $result);
    return $this->returnJSON( $result );
  }
  
  /**
   * Internal api calls
   *
   * @param string  name    the name of the API method to call
   * @param array   params  associative array of parameters
   */
  public function call( $name, $params )
  {
    // check if we have this in our methods
    if( !isset( $this->methods->$name ) ){
      throw new Exception( '[Promotions API] Invalid method: '.$name );
    }
    
    $method = $this->methods->$name;
    
    return call_user_func( $method['fn'], $params );
    
  }
}
