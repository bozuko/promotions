<?php
/**
 * Promotion
 *
 * @wp.posttype.name                        promotion
 * @wp.posttype.single                      Promotion
 * @wp.posttype.plural                      Promotions
 *
 * @wp.posttype.args.menu_position          4
 * @wp.posttype.args.rewrite.with_front     false
 * @wp.posttype.args.hierarchical           false
 * @wp.posttype.args.capability_type        promotion
 * @wp.posttype.args.map_meta_cap           true  
 * 
 * @wp.posttype.supports.editor             false
 * @wp.posttype.supports.revisions          true
 */
class Promotions_PostType_Promotion extends Snap_Wordpress_PostType
{
  
  protected $forms = array();
  
  /**
   * Give administrators all the capabilities for this post type
   *
   * @wp.action       init
   * @wp.priority     11
   */
  public function add_capabilties_to_roles()
  {
    $post_type_object = get_post_type_object($this->name);
    global $wp_roles;
    $roles = array('administrator');
    foreach( (array)$post_type_object->cap as $capability ){
      foreach( $roles as $role_name ){
        $role = get_role($role_name);
        $role->add_cap($capability);
      }
    }
  }
  
  public function get_registration_form( $post_id=null )
  {
    if( !$post_id ) $post_id = get_the_ID();
    if( isset( $this->forms[$post_id] ) ) return $this->forms[$post_id];
    
    $fields = get_field('form_fields', $post_id);
    $form = new Snap_Wordpress_Form2_Form(array('promotion_id' => $post_id));
    
    foreach( $fields as $field ){
      $config = json_decode( $field['field'], true );
      $form->add_field( $config['name'], $config['type'], $config );
    }
    
    $validators = get_field('form_validators', $post_id);
    if( $validators && is_array($validators) ) foreach( $validators as $validator ){
      $config = json_decode( $validator['validator'], true );
      if( is_array($config) && isset($config['classname'])){
        $classname = $config['classname'];
        if( class_exists( $classname ) ){
          $form->add_validator( new $classname( $config ) );
        }
      }
    }
    do_action_ref_array('promotions/registration_form/create', array(&$form) );
    
    $this->forms[$post_id] = $form;
    return $form;
  }
}