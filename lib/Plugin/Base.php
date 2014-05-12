<?php

class Promotions_Plugin_Base extends Snap_Wordpress_Plugin
{
  
  protected $field_groups = array();
  
  protected $data_dir = false;
  
  public function __construct()
  {
    parent::__construct();
    Snap::inst('Promotions_Plugin_Manager')->register( $this );
    $this->init();
  }
  
  protected function init()
  {
    // override me.
  }
  
  public function register_field_groups()
  {
    $this->field_groups = array_merge( $this->field_groups, func_get_args() );
  }
  
  public function get_field_groups()
  {
    return $this->field_groups;
  }
  
  public function register_data_dir( $dir )
  {
    $this->data_dir = $dir;
  }
  
  public function get_data_dir()
  {
    $dir = $this->data_dir;
    if( !$dir ){
      if( !$dir ){
        // lets infer this from our standard structure
        $reflector = new ReflectionClass( $this );
        $filename = $reflector->getFileName();
        if( !$filename ) continue;
        
        $dir = dirname($filename).'/../data';
      }
    }
    return $dir;
  }
  
}
