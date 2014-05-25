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
   * Only process requests if we are on a singular promotion
   * page.
   *
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
  
  /**
   * Remove all the extranneous Wordpress meta tags from
   * the header.
   * 
   * @wp.action     init
   * @wp.priority   100
   */
  public function cleanup_wordpress_meta()
  {
    remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
    remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
    remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
    remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
    remove_action( 'wp_head', 'index_rel_link' ); // index link
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
    remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
    remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
  }
}
