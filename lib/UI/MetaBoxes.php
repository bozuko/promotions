<?php

class Promotions_UI_MetaBoxes extends Snap_Wordpress_Plugin
{
  
  public function __construct( $tabs )
  {
    $this->tabs = $tabs;
    $this->post_type = $tabs->get_post_type();
    parent::__construct();
  }
  
  public function _wp_add_meta_box( $id, $title, $callback, $post_type, $context, $priority )
  {
    return $this->add( $id, $title, $callback, $post_type, $context, $priority );
  }
  
  public function add( $id, $title, $callback, $post_type, $context, $priority )
  {
    $the_tab = $this->snap->method($id, $this->post_type.'.tab', 'all');
    
    if( $the_tab != 'all' && $this->tabs->get_current_tab() != $the_tab ) return;
    
    if( !$post_type ) $post_type = $this->post_type;
    return add_meta_box( $id, $title, $callback, $post_type, $context, $priority );
  }
}
