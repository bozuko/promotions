<?php

class Promotions_Shortcodes extends Snap_Wordpress_Shortcodes
{
  
  /**
   * @wp.shortcode
   */
  public function form($atts=array(), $content='')
  {
    extract( shortcode_atts( array(
      'class'       => '',
      'method'      => 'post',
      'action'      => '?',
      'promotion'   => false
    ), $atts, 'form'));
    
    if( !$promotion ){
      if( !is_singular('promotion') ) return;
      $promotion = get_the_ID();
    }
    else {
      $promotion = get_page_by_path( $promotion, OBJECT, 'promotion' );
      if( !$promotion ) return;
      $promotion = $promotion->ID;
    }
    
    $content.=wp_nonce_field( get_post($promotion)->post_name, '_promotion');
    $content.=Snap_Util_Html::tag(array(
      'tag'         =>'input',
      'attributes'  =>array(
        'type'        =>'hidden',
        'name'        =>'_method',
        'value'       =>'register'
      )
    ));
    
    echo Snap_Util_Html::tag(array(
      'tag'         => 'form',
      'attributes'  => array(
        'method'      => $method,
        'action'      => get_permalink( $promotion )
      ),
      'content'     => do_shortcode( $content ) 
    ));
    
  }
  
  /**
   * @wp.shortcode
   */
  public function field($atts=array(), $content='')
  {
    extract( shortcode_atts( array(
      'class'       => '',
      'name'        => '',
      'promotion'   => false
    ), $atts, 'field') );
    
    if( !$promotion ){
      if( !is_singular('promotion') ) return;
      $promotion = get_the_ID();
    }
    else {
      $promotion = get_page_by_path( $promotion, OBJECT, 'promotion' );
      if( !$promotion ) return;
      $promotion = $promotion->ID;
    }
    
    echo Snap::inst('Promotions_PostType_Promotion')
          ->get_registration_form( $promotion )
          ->get_field( $name )
          ->get_html();
    
  }
}
