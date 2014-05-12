<?php

class Promotions_API extends Snap_Wordpress_Plugin
{
  
  public $methods;
  protected $history = array();
  
  /**
   * @wp.action         init
   */
  public function init()
  {
    if( !isset($this->methods) ) $this->methods = new stdClass;
    $this->methods = apply_filters( 'promotions/api/register_methods', $methods );
    add_rewrite_endpoint('api', EP_PERMALINK | EP_ROOT );
    do_action_ref_array('promotions/api/register', array( $this ));
  }
  
  public function register_method( $name, $callback, $request_method='post')
  {
    if( !isset($this->methods) ) $this->methods = new stdClass;
    
    $this->methods->$name = array(
      'fn'              => $callback,
      'request_method'  => $request_method
    );
  }
  
  public function add( $class )
  {
    $snap = Snap::get( $class );
    $methods = $snap->getRegistry()->get('method');
    $inst = Snap::inst( $class );
    
    foreach( array_keys($methods) as $name ){
      if( !$snap->method($name, 'snap.public' ) ) continue;
      $this->register_method( $name, array(&$inst, $name), $snap->method($name, 'methods', 'post') );
    }
    
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
    
    /********************************************************
    * This filter allows us to restrict direct API calls by
    * IP or api key
    *********************************************************/
    if( !apply_filters('promotions/api/allowed', true, $name ) ){
      // invalid...
      return $this->returnJSON(array(
        'error' => 'API call is not allowed'
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
    
    if( ($result = apply_filters("promotions/api/$name/before", true, $params, $name )) !== true ){
      return $result;
    }
    
    $result = call_user_func( $method['fn'], $params );
    $result = apply_filters('promotions/api/result', $result, $name, $params );
    $result = apply_filters('promotions/api/result?method='.$name, $result, $params );
    
    $this->_history[] = array(
      'method'  => $name,
      'result'  => $result
    );
    
    return $result;
    
  }
  
  public function has_method( $name )
  {
    return isset( $this->methods->$name );
  }
  
  public function get_method( $name )
  {
    return $this->methods->$name;
  }
}
