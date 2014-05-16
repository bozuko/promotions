<?php
/**
 * This is the main plugin file.
 *
 * It instantiates all the core objects and fires of the
 * inital event 'promotions/init' for other plugins and modules
 * to hook in to.
 */
class Promotions extends Promotions_Plugin_Base
{
  public function __construct()
  {
    parent::__construct();
    
    $this->register_field_groups(
      'basic-settings',
      'registration-form',
      'promotions-basic',
      'promotion-messages'
    );
    
    // Core post types
    $this->register_post_types();
    
    // Enable our core API
    Snap::inst('Promotions_API');
  }
  
  /**
   * @wp.action     init
   */
  public function launch()
  {
    
    // 3rd party plugins
    $this->init_3rd_party_plugins();
    
    // Promotions Plugins
    do_action('promotions/plugins/load');
    
    // Launch our primary controllers
    Snap::inst(is_admin()?'Promotions_Admin':'Promotions_Front');
    
    // Run our initialization hook
    do_action('promotions/init');
    
    // Register fields
    do_action('promotions/register_fields');
  }
  
  /**
   * @wp.action   promotions/plugins/load
   */
  public function load_core_plugins()
  {
    $core = dirname(__FILE__).'/Core';
    $files = scandir( $core );
    foreach( $files as $file ){
      if( strpos( $file, '.' ) === 0 || !is_dir( $core.'/'.$file)  ) continue;
      if( !file_exists( $core.'/'.$file.'/Plugin.php') ) continue;
      
      $plugin = Snap::inst("Promotions_Core_{$file}_Plugin");
      $plugin->register_data_dir( PROMOTIONS_DIR.'/data' );
    }
  }
  
  protected function register_post_types()
  {
    Snap::inst('Promotions_PostType_Entry');
    Snap::inst('Promotions_PostType_Registration');
    Snap::inst('Promotions_PostType_Promotion');
  }
  
  protected function init_3rd_party_plugins()
  {
    Snap::inst('Promotions_ACF');
  }
}
