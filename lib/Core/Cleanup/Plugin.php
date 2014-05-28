<?php

class Promotions_Core_Cleanup_Plugin extends Promotions_Plugin_Base
{
  /**
   * @wp.metabox
   * @wp.post_type      promotion
   * @wp.context        side
   * @wp.priority       low
   * @wp.title          Cleanup
   * @promotion.tab     all
   */
  public function metabox( $post )
  {
    global $wpdb;
    
    // lets show how many things are
    wp_enqueue_script('promotions-admin', PROMOTIONS_URL.'/assets/js/promotion-admin.js', array('jquery'), '1.0', true);
    
    // check how many things we have...
    $query = "
      SELECT COUNT(*) FROM `{$wpdb->posts}` `p`
        WHERE `p`.`post_type` = %s
          AND `p`.`post_parent` = %d
    ";
    
    $registrations = $wpdb->get_var( $wpdb->prepare( $query, 'registration', $post->ID ) );
    
    $query = "
      SELECT COUNT(*) FROM `{$wpdb->posts}` `p`
        JOIN `{$wpdb->posts}` `parent`
          ON `p`.`post_parent` = `parent`.`ID`
        WHERE `p`.`post_type` = %s
          AND `parent`.`post_parent` = %d
    ";
    
    $entries = $wpdb->get_var( $wpdb->prepare( $query, 'entry', $post->ID ) );
    ?>
    <p>
      <strong><?= $registrations ?></strong> Registrations<br />
      <strong><?= $entries ?></strong> Entries<br />
    </p>
    <p style="text-align:left;">
    <a href="<?= add_query_arg('cleanup_promotion', '1') ?>" class="delete" data-action="cleanup">
      Clean up Registrations and Entries
    </a>
    </p>
    <?php
  }
  
  /**
   * @wp.action         promotions/init
   */
  public function do_cleanup()
  {
    if( !is_admin() ) return;
    
    global $pagenow;
    if( 'post.php' != $pagenow ) return;
    
    $post_id = @$_GET['post'];
    
    if( !$post_id ) return;
    if( 'promotion' != get_post_type( $post_id ) ) return;
    
    if( !@$_REQUEST['cleanup_promotion'] ) return;
    
    global $wpdb;
    
    $post = get_post( $post_id );
    
    $sql = "
      DELETE `meta` FROM `{$wpdb->posts}` AS `entry`
        INNER JOIN `{$wpdb->posts}` AS `reg`
        JOIN `{$wpdb->postmeta}` AS `meta`
        WHERE `entry`.`ID` = `meta`.`post_id`
          AND `reg`.`ID` = `entry`.`post_parent`
          AND `reg`.`post_parent` = %d
          AND `reg`.`post_type` = 'registration'
          AND `entry`.`post_type` = 'entry'
    ";
    $wpdb->query( $wpdb->prepare( $sql, $post_id ) );
    
    $sql = "
      DELETE `entry` FROM `{$wpdb->posts}` AS `entry`
        INNER JOIN `{$wpdb->posts}` AS `reg`
        WHERE `reg`.`ID` = `entry`.`post_parent`
          AND `reg`.`post_parent` = %d
          AND `reg`.`post_type` = 'registration'
          AND `entry`.`post_type` = 'entry'
    ";
    $wpdb->query( $wpdb->prepare( $sql, $post_id ) );
    
    $sql = "
      DELETE `meta` FROM `{$wpdb->postmeta}` AS `meta`
        INNER JOIN `{$wpdb->posts}` AS `reg`
        WHERE `reg`.`ID` = `meta`.`post_id`
          AND `reg`.`post_parent` = %d
          AND `reg`.`post_type` = 'registration'
    ";
    $wpdb->query( $wpdb->prepare( $sql, $post_id ) );
    
    $sql = "
      DELETE FROM `{$wpdb->posts}`
        WHERE `post_parent` = %d
          AND `post_type` = 'registration'
    ";
    $wpdb->query( $wpdb->prepare( $sql, $post_id ) );
    
    $sql = "
      DELETE FROM `{$wpdb->promotions_analytics}`
        WHERE `promotion_id` = %d
    ";
    $wpdb->query( $wpdb->prepare( $sql, $post_id ) );
    
    do_action('promotions/cleanup', $post_id);
    
    Promotions_Flash::set('cleanup', 'Cleaned up '.$post->post_title);
    wp_safe_redirect(remove_query_arg('cleanup_promotion'));
    exit;
  }
  
  /**
   * @wp.action         
   */
  public function admin_notices()
  {
    global $pagenow;
    if( 'post.php' != $pagenow ) return;
    
    $post_id = @$_GET['post'];
    if( !$post_id ) return;
    
    if( 'promotion' != get_post_type( $post_id ) ) return;
    
    $message = Promotions_Flash::get('cleanup');
    if( !$message ) return;
    ?>
    
    <div class="updated">
      <p><?= $message ?></p>
    </div>
    <?php
  }
  
}
