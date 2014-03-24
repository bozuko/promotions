<?php

class Promotions_Plugin_Manager extends Snap_Wordpress_Plugin
{
  
  protected $plugins = array();
  
  public function register( $plugin )
  {
    $this->plugins[] = $plugin;
  }
  
  public function get_plugins()
  {
    return $this->plugins;
  }
}
