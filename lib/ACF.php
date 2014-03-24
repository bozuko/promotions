<?php

class Promotions_ACF extends Snap_Wordpress_Plugin
{
  /**
   * @wp.action     promotions/register_fields
   */
  public function register_fields()
  {
    $this->dev_mode = get_field('promotions_dev', 'option');
    if( get_field('promotions_dev', 'option') ){
      // we are going to use the fields from the database
      return;
    }
    
    define('ACF_LITE', true);
    
    // Remove the filter for pulling fields from db.
		remove_all_filters('acf/get_field_groups', 1);
    $plugins = Snap::inst('Promotions_Plugin_Manager')->get_plugins();
    foreach( $plugins as $plugin ){
      $field_groups = $plugin->get_field_groups();
      $dir = $plugin->get_data_dir().'/acf';
      
      foreach( $field_groups as $group ){
        $file = $dir.'/'.$group.'.php';
        if( file_exists( $file ) ) require($dir.'/'.$group.'.php');
      }
    }
    
  }
  
  /**
   * @wp.action     acf/save_post
   * @wp.priority   11
   */
  public function fix_dev_mode_change($post_id)
  {
    if( $post_id != 'options' ) return;
    
    $dev_mode = get_field('promotions_dev','options');
    if( $dev_mode != $this->dev_mode ){
      ?>
      <script type="text/javascript">
      window.location = "<?= add_query_arg('options-updated', '1') ?>";
      </script>
      <?php
    }
  }
  
  /**
   * @wp.action     save_post
   * @wp.priority   100
   */
  public function write_fields( $post_id )
  {
    if( !$this->dev_mode || get_post_type( $post_id ) != 'acf' ) return;
    
    $plugins = Snap::inst('Promotions_Plugin_Manager')->get_plugins();
    
    $name = preg_replace('/^acf_/', '', get_post( $post_id )->post_name );
    
    foreach( $plugins as $plugin ){
      $field_groups = $plugin->get_field_groups();
      
      if( !in_array( $name, $field_groups ) ) continue;
      
      // write the fields to the data directory
      $dir = $plugin->get_data_dir();
      if( !is_dir( $dir ) ) mkdir( $dir );
      
      $dir .= '/acf';
      if( !is_dir( $dir ) ) mkdir( $dir );
      
      // okay, save the fields.
      $file = $dir.'/'.$name.'.php';
      
      file_put_contents($file, $this->get_field_group_php( $post_id ) );
      return;
    }
  }
  
  /**
   * @wp.action     acf/options_page/settings
   */
  public function options_settings( $settings )
  {
    $settings['pages'] = array('General','Theme');
    return $settings;
  }
  
  protected function get_field_group_php( $post_id )
  {
    $acf = get_post( $post_id );
    ob_start();
    echo "<?php\n";
			?>
if(function_exists("register_field_group"))
{
<?php
      // populate acfs
      $var = array(
        'id' => $acf->post_name,
        'title' => $acf->post_title,
        'fields' => apply_filters('acf/field_group/get_fields', array(), $acf->ID),
        'location' => apply_filters('acf/field_group/get_location', array(), $acf->ID),
        'options' => apply_filters('acf/field_group/get_options', array(), $acf->ID),
        'menu_order' => $acf->menu_order,
      );
      
      $var['fields'] = apply_filters('acf/export/clean_fields', $var['fields']);
      
      // create html
      $html = var_export($var, true);
      
      // change double spaces to tabs
      $html = str_replace("  ", "\t", $html);
      
      // correctly formats "=> array("
      $html = preg_replace('/([\t\r\n]+?)array/', 'array', $html);
      
      // Remove number keys from array
      $html = preg_replace('/[0-9]+ => array/', 'array', $html);
      
      // add extra tab at start of each line
      $html = str_replace("\n", "\n\t", $html);
      
      // change the tabs back to double spaces
      $html = str_replace("\t", "  ", $html);
      
      // add the WP __() function to specific strings for translation in theme
      //$html = preg_replace("/'label'(.*?)('.*?')/", "'label'$1__($2)", $html);
      //$html = preg_replace("/'instructions'(.*?)('.*?')/", "'instructions'$1__($2)", $html);
      
								
?>	register_field_group(<?php echo $html ?>);
}
    <?php
    return ob_get_clean();
  }
}