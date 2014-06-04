<?php

class Promotions_Core_Export_Plugin extends Promotions_Plugin_Base
{
  /**
   * @wp.metabox
   * @wp.post_type      promotion
   * @wp.context        side
   * @wp.priority       low
   * @wp.title          Export
   * @promotion.tab     all
   */
  public function export_meta_box( $post )
  {
    $url = admin_url('export.php');
    $url = add_query_arg('download', 1, $url );
    $url = add_query_arg('content', $post->post_type, $url);
    $url = add_query_arg('start_date', $post->post_date, $url);
    $url = add_query_arg('end_date', $post->post_date, $url);
    ?>
    <a href="<?= $url ?>" class="button button-primary">
      Export Promotion
    </a>
    <?php
  }
}
