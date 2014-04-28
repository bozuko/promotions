<?php

class Promotions_Functions
{
  
  public function get_timezone( $post_id )
  {
    try {
      $timezone = new DateTimeZone( get_option('timezone_string') );
    }
    catch( Exception $e ){
      $timezone = new DateTimeZone( 'America/New_York' );
    }
    return apply_filters('promotions/functions/get_timezone', $timezone, $post_id );
  }
  
  public function is_before_start( $post_id )
  {
    $now = new DateTime( 'now', $this->get_timezone() );
    $start = $this->get_start( $post_id );
    $before = $now->getTimestamp() < $start->getTimestamp();
    return apply_filters('promotions/functions/is_before_start', $before, $post_id );
  }
  
  public function is_after_end( $post_id )
  {
    $now = new DateTime( 'now', $this->get_timezone() );
    $end = $this->get_end( $post_id );
    $after = $now->getTimestamp() > $end->getTimestamp();
    return apply_filters('promotions/functions/is_after_end', $after, $post_id );
  }
  
  public function is_active( $post_id )
  {
    $active = !$this->is_before_start() && !$this->is_after_end();
    return apply_filters('promotions/functions/is_active', $active, $post_id );
  }
  
  public function get_start( $post_id )
  {
    $start = get_field('start', $post_id);
    $date = date('Y-m-d H:i:s', strtotime( $start ));
    $time = new DateTime( $date, $this->get_timezone() );
    return apply_filters('promotions/functions/get_start', $time, $post_id );
  }
  
  public function get_end( $post_id )
  {
    $end = get_field('end', $post_id);
    $date = date('Y-m-d H:i:s', strtotime( $end ));
    $time = new DateTime( $date, $this->get_timezone() );
    return apply_filters('promotions/functions/get_end', $time, $post_id );
  }
}
