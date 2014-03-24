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
   * @wp.action       promotions/tabs/promotion/register
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
