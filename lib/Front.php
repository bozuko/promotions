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
   * @wp.priority 20
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
    if( is_singular('promotion') ){
      do_action('promotions/process');
      // enable our decorator
      Snap::inst(
        apply_filters(
          'promotions/form/decorator',
          'Snap_Wordpress_Form2_Decorator_Bootstrap3'
        )
      );
    }
  }
  
}
