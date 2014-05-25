<?php

class Promotions_Analytics extends Snap_Wordpress_Plugin
{
  protected $db_version = '1.8';
  protected $metrics = array();
  protected $data = array();
  protected $intervals;
  
  public function init()
  {
    $this->init_db();
    do_action_ref_array('promotions/analytics/register', array(&$this));
    $this->intervals = apply_filters('promotions/analytics/intervals', array());
  }
  
  /**
   * @wp.filter   promotions/analytics/intervals
   */
  public function default_intervals( $intervals )
  {
    return array_merge( $intervals, array(
      'all'         => array(
        'name'          => 'All',
        'format'        => '\a\l\l',
        'get_start'     => function( $time, $promotion_id ){
          return Snap::inst('Promotions_Functions')->get_start( $promotion_id )->format('Y-m-d H:i:s');
        },
        'get_end'       => function( $time, $promotion_id ){
          return Snap::inst('Promotions_Functions')->get_end( $promotion_id )->format('Y-m-d H:i:s');
        }
      ),
      'month'       => array(
        'name'          => 'Monthly',
        'format'        => 'Y-m',
        'get_start'     => function( $time ){
          $start = clone $time;
          $start->setDate( (int)$start->format('Y'), (int)$start->format('m'), 1)
            ->setTime(0,0,0);
          return $start->format('Y-m-d H:i:s');
        },
        'get_end'       => function( $time ){
          $end = clone $time;
          $end->setDate( (int)$end->format('Y'), (int)$end->format('m'), 1)
            ->setTime(0,0,0);
          return $end->modify('+1 month')->modify('-1 second')->format('Y-m-d H:i:s');
        }
      ),
      'week'        => array(
        'name'          => 'Weekly',
        'format'        => 'Y-\WW',
        'get_start'     => function( $time ){
          $start = clone $time;
          $start->setISODate( (int)$time->format('Y'), (int)$time->format('W'), 1 );
          $start->setTime(0,0,0);
          return $start->format('Y-m-d H:i:s');
        },
        'get_end'     => function( $time ){
          $end = clone $time;
          $end->setISODate( (int)$time->format('Y'), (int)$time->format('W'), 1 );
          $end->setTime(0,0,0);
          return $end->modify( '+7 days' )->modify('-1 second')->format('Y-m-d H:i:s');
        }
      ),
      'day'         => array(
        'name'          => 'Daily',
        'format'        => 'Y-m-d',
        'get_start'     => function( $time ){
          return $time->format('Y-m-d 00:00:00');
        },
        'get_end'     => function( $time ){
          $end = clone $time;
          $end->setTime(0,0,0);
          return $end->modify('+1 day')->modify('-1 second')->format('Y-m-d H:i:s');
        }
      ),
      'hour'        => array(
        'name'          => 'Hourly',
        'format'        => 'Y-m-d H',
        'get_start'     => function( $time ){
          return $time->format('Y-m-d H:00:00');
        },
        'get_end'     => function( $time ){
          $end = clone $time;
          $end->setTime( (int)$time->format('H'),0,0 );
          return $end->modify( '+1 hour')->modify('-1 second')->format('Y-m-d H:i:s');
        }
      )
    ));
  }
  
  protected function init_db()
  {
    global $wpdb, $charset_collate;
    
    $wpdb->promotions_analytics = "{$wpdb->prefix}promotions_analytics";
    $version = get_option('promotions_analytics_db_version');
    if( !$version || $version < $this->db_version ){
      $sql = <<<SQL
CREATE TABLE {$wpdb->promotions_analytics} (
  id BIGINT(20) unsigned NOT NULL auto_increment,
  last_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  start TIMESTAMP NOT NULL,
  end TIMESTAMP NOT NULL,
  promotion_id BIGINT NOT NULL DEFAULT '0',
  metric VARCHAR(30) NOT NULL DEFAULT '',
  unit_interval VARCHAR(30) NOT NULL DEFAULT '',
  unit VARCHAR(20) DEFAULT '',
  total BIGINT(20) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY  (id),
  UNIQUE KEY bucket (promotion_id,metric,unit_interval,unit)
) {$charset_collage};
SQL;
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      $wpdb->show_errors(true);
      dbDelta( $sql );
      $wpdb->show_errors(false);
      //update_option( 'promotions_analytics_db_version', $this->db_version );
    }
  }
  
  protected function get_data( $metric )
  {
    
    $data = get_option('promotion_'.get_the_ID().'_'.$metric);
  }
  
  public function register( $metric, $options )
  {
    $this->metrics[$metric] = $options;
  }
  
  public function get_metrics()
  {
    return $this->metrics;
  }
  
  public function get_intervals()
  {
    return $this->intervals;
  }
  
  public function get_interval_format( $interval )
  {
    if( isset( $this->intervals[$interval]) ) return $this->intervals[$interval]['format'];
    return false;
  }
  
  public function increment( $metric, $timestamp=null, $promotion_id=null )
  {
    global $wpdb;
    if( !isset( $this->metrics[$metric] ) ) return;
    
    
    if( !$timestamp ){
      $timestamp = Snap::inst('Promotions_Functions')->now();
    }
    
    $mysql_now = $timestamp->format('Y-m-d H:i:s');
    //$wpdb->show_errors(true);
    
    if( !isset( $promotion_id ) ) $promotion_id = get_the_ID();
    
    foreach( $this->intervals as $interval => $options ){
      $date = $timestamp->format( $options['format'] );
      
      $start = $options['get_start']( $timestamp, $promotions_id );
      $end = $options['get_end']( $timestamp, $promotions_id );
      
      $sql = <<<SQL
INSERT INTO {$wpdb->promotions_analytics}
  (`promotion_id`,`metric`,`unit_interval`,`unit`,`start`,`end`,`total`) VALUES
  (%d, %s, %s, %s, %s, %s, 1)
  ON DUPLICATE KEY UPDATE `total` = `total`+1, `last_modified` = %s
SQL;
      $sql = $wpdb->prepare($sql, $promotion_id, $metric, $interval, $date, $start, $end, $mysql_now);
      //echo $sql;
      $wpdb->query( $sql );
    }
    
    return $this;
  }
  
  public function get( $promotion_id, $metric, $interval )
  {
    global $wpdb;
    $sql = <<<SQL
SELECT start, unit, total FROM {$wpdb->promotions_analytics}
  WHERE promotion_id = %d
    AND metric = %s
    AND unit_interval = %s
  ORDER BY unit ASC
SQL;
    $sql = $wpdb->prepare( $sql, $promotion_id, $metric, $interval );
    return $wpdb->get_results( $sql );
  }
  
  public function get_all( $promotion_id, $metric )
  {
    $all = $this->get( $promotion_id, $metric, 'all');
    if( $all && is_array($all) ){
      return $all[0]->total;
    }
    return 0;
  }
}
