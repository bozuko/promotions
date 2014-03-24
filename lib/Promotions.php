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
    Snap::inst('Promotions_API_Promotion');
    
    // 3rd party plugins
    $this->init_3rd_party_plugins();
    
    // Promotions Plugins
    do_action('promotions/plugins/load');
    
    // Register fields
    do_action('promotions/register_fields');
    
    // Launch our primary controllers
    Snap::inst(is_admin()?'Promotions_Admin':'Promotions_Front');
    
    // Run our initialization hook
    do_action('promotions/init');
  }
  
  public function register_post_types()
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
