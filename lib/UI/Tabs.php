<?php

class Promotions_UI_Tabs extends Snap_Wordpress_Plugin
{
  
  protected static $post_types = array();
  
  public static function get_instance( $type )
  {
    if( !isset( self::$post_types[$type] ) ){
      new self( $type );
    }
    return self::$post_types[$type];
  }
  
  protected static function register( $type, $object )
  {
    self::$post_types[$type] = $object;
  }
  
  protected $tabs = array();
  protected $post_type;
  
  public function __construct($post_type, $tabs = array())
  {
    parent::__construct();
    
    Promotions_UI_Tabs::register( $post_type, $this );
    
    $this->post_type = $post_type;
    $this->tabs = apply_filters('promotions/tabs/'.$post_type.'/register', (array)$tabs);
    
    // need to add this manually because the hook name
    // includes a variable value
    add_filter('acf/location/rule_values/'.$this->post_type.'_tab', array(
      &$this, 'acf_location_rule_values'
    ));
    
    add_filter('acf/location/rule_match/'.$this->post_type.'_tab', array(
      &$this, 'acf_location_rule_match'
    ), 10, 3);
  }
  
  public function set_tabs( $tabs )
  {
    $this->tabs = $tabs;
  }
  
  public function get_current_tab()
  {
    $tab_ids = array_keys( $this->tabs );
    if( in_array( @$_REQUEST['tab'], $tab_ids) ){
      return @$_REQUEST['tab'];
    }
    return $tab_ids[0];
  }
  
  public function get_post_type()
  {
    return $this->post_type;
  }
  
  /**
   * @wp.filter
   */
  public function post_row_actions( $actions=array(), $post )
  {
    if( $post->post_type != $this->post_type ) return $actions;
    unset( $actions['edit'] );
    unset( $actions['inline hide-if-no-js'] );
    $new_actions = array();
    foreach( $this->tabs as $tab => $label ){
      $new_actions[$tab] = '<a href="'.add_query_arg('tab', $tab, get_edit_post_link( $post->ID )).'">'.$label.'</a>';
    }
    return $new_actions + $actions;
  }
  
  /**
   * @wp.filter         acf/location/rule_types
   */
  public function acf_location_rule_types( $types )
  {
    $obj = get_post_type_object( $this->post_type );
    if( !isset( $types[$obj->labels->name] ) ){
      return array( $obj->labels->name => array($this->post_type.'_tab'=>'Tab')) + $types;
    }
    $types[$obj->labels->name][$this->post_type.'_tab'] = 'Tab';
    return $types;
  }
  
  public function acf_location_rule_values( $choices )
  {
    return $this->tabs;
  }
  
  public function acf_location_rule_match( $match, $rule, $options)
  {
    $value = $rule['value'];
    $tab = $this->get_current_tab();
    $match = false;
    if( $rule['operator'] == '==' ){
      if( get_post_type() == $this->post_type && $tab == $value ){
        $match = true;
      }
    }
    else if( $rule['operator'] == '!=' ){
      if( get_post_type() !== $this->post_type || $tab != $value ){
        $match = true;
      }
    }
    return $match;
  }
  
  /**
   * Promotions tabs
   *
   * @wp.action
   */
  public function edit_form_after_title( $post )
  {
    if( $post->post_type !== $this->post_type || !count($this->tabs) ) return;
    $tab = $this->get_current_tab();
    $url = remove_query_arg('message');
    ?>
    <input type="hidden" name="tab" value="<?= $tab ?>" />
    <h2 class="nav-tab-wrapper promotion-tabs">
      <?php
      
      foreach( $this->tabs as $id => $label ){
        $classes = array('nav-tab');
        
        if( $tab == $id ){
          $classes[] = 'nav-tab-active';
        }
        ?>
      <a href="<?= add_query_arg('tab', $id, $url) ?>" class="<?= implode(' ',$classes) ?>">
        <?= $label ?>
      </a>
        <?php
      }
      ?>
    </h2>
    <?php
  }
  
  /**
    * Detect which tab we were on during save POST requests
    *
    * @wp.filter
    */
  public function redirect_post_location( $location, $post_id )
  {
    if( get_post_type($post_id) == $this->post_type ){
      $location = add_query_arg('tab', $this->get_current_tab(), $location );
    }
    return $location;
  }
}
