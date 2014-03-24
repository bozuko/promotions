<?php
/*
Plugin Name: Promotions
Plugin URI: http://bozuko.com
Description: Bozuko Promotions is a Wordpress based promotion management system
Version: 2.0.0
Author: Bozuko
Author URI: http://bozuko.com
License: Proprietary
*/

add_action('plugins_loaded', function()
{
    
    if( !class_exists('Snap') ){
        // The Snap library is required
        function sweeps_snap_required(){
            echo '<div class="error"><p>The Sweepstakes Plugin requires the <a href="https://github.com/fabrizim/Snap">Snap Library</a>. Please download the zip file and install.</p></div>';
        }
        add_action( 'admin_notices', 'sweeps_snap_required' );
        return;
    }

    define( 'PROMOTIONS_DIR', dirname(__FILE__) );
    define( 'PROMOTIONS_URL', plugins_url( '', __FILE__ ) );
    
    Snap_Loader::register( 'Promotions', PROMOTIONS_DIR . '/lib' );
    Snap::inst('Promotions');
});