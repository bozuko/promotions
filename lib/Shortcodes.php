<?php

class Promotions_Shortcodes extends Snap_Wordpress_Shortcodes
{
  
  protected $_form = false;
  
  /**
   * @wp.shortcode
   */
  public function form($atts=array(), $content='')
  {
    extract( shortcode_atts( array(
      'class'       => '',
      'method'      => 'post',
      'action'      => '?',
      'promotion'   => false,
      'form_id'     => false
    ), $atts, 'form'));
    
    if( $form_id ){
      $form = Snap_Wordpress_Form2::get_form( $form_id );
      if( !$form ) return;
      $this->_form = $form;
    }
    
    else {
      if( !$promotion ){
        if( !is_singular('promotion') ) return;
        $promotion = get_the_ID();
      }
      else {
        $promotion = get_page_by_path( $promotion, OBJECT, 'promotion' );
        if( !$promotion ) return;
        $promotion = $promotion->ID;
      }
      
      $content.=Snap_Util_Html::tag(array(
        'tag'         =>'input',
        'attributes'  =>array(
          'type'        =>'hidden',
          'name'        =>'_method',
          'value'       =>'register'
        )
      ));
      
      $this->_form = Snap::inst('Promotions_PostType_Promotion')
            ->get_registration_form( $promotion );
      
      $content = apply_filters('promotions/registration_form/content', $content, $this->form, $promotion );
    }
    
    echo Snap_Util_Html::tag(array(
      'tag'         => 'form',
      'attributes'  => array(
        'method'      => $method,
        'action'      => get_permalink( $promotion ),
        'class'       => $class,
        'data-validate-config' => json_encode($this->_form->get_jquery_validate_config())
      ),
      'content'     => do_shortcode( $content ) 
    ));
    
    $this->_form = null;
    
  }
  
  /**
   * @wp.shortcode
   */
  public function field($atts=array(), $content='')
  {
    extract( shortcode_atts( array(
      'class'       => '',
      'name'        => ''
    ), $atts, 'field') );
    
    echo $this->_form
          ->get_field( $name )
          ->get_html();
    
  }
}
