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
 */
class Promotions_PostType_Promotion extends Snap_Wordpress_PostType
{
  
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
}