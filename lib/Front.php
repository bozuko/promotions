<?php
/**
 * Front Controller
 * 
 */
class Promotions_Front extends Snap_Wordpress_Plugin
{
  
  public function __construct()
  {
    parent::__construct();
    Snap::inst('Promotions_Shortcodes');
  }
  
  /**
   * @wp.action init
   */
  public function fix_code_area()
  {
    remove_all_filters('acf/format_value_for_api/type=code_area', 10);
  }
  
  /**
   * @wp.action template_redirect
   */
  public function process()
  {
    do_action('promotions/process');
    
    if( is_singular('promotion') ){
      // enable our decorator
      Snap::inst('Snap_Wordpress_Form2_Decorator_Bootstrap3');
    }
  }
  
}
