<?php
/**
 * Plugin Name: Blog, Posts and Category Filter for Elementor
 * Description: Posts and Category for Elementor
 * Author: Plugin Devs
 * Author URI: https://plugin-devs.com/
 * Plugin URI: https://plugin-devs.com/product/elementor-post-category-filter/
 * Version: 2.0.1
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pd-pcf
 * 
 * Elementor tested up to: 3.25.4
 * Elementor Pro tested up to: 3.25.2
*/

 // Exit if accessed directly.
 if ( ! defined( 'ABSPATH' ) ) { exit; }

 /**
  * Main class for News Ticker
  */
class PD_PCF_SLIDER
 {
 	
 	private static $instance;

	public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new PD_PCF_SLIDER();
            self::$instance->init();
        }
        return self::$instance;
    }

    //Empty Construct
 	function __construct(){}
 	
 	//initialize Plugin
 	public function init(){
 		$this->defined_constants();
 		$this->include_files();
		add_action( 'elementor/init', array( $this, 'pd_pcf_create_category') ); // Add a custom category for panel widgets
 	}

 	//Defined all constants for the plugin
 	public function defined_constants(){
 		define( 'PD_PCF_PATH', plugin_dir_path( __FILE__ ) );
		define( 'PD_PCF_URL', plugin_dir_url( __FILE__ ) ) ;
		define( 'PD_PCF_VERSION', '2.0.1' ) ; //Plugin Version
		define( 'PD_PCF_MIN_ELEMENTOR_VERSION', '3.0.0' ) ; //MINIMUM ELEMENTOR Plugin Version
		define( 'PD_PCF_MIN_PHP_VERSION', '7.4' ) ; //MINIMUM PHP Plugin Version
		define( 'PD_PCF_PRO_LINK', 'https://plugin-devs.com/product/elementor-post-category-filter/' ) ; //Pro Link
 	}

 	//Include all files
 	public function include_files(){

 		require_once( PD_PCF_PATH . 'functions.php' );
 		require_once( PD_PCF_PATH . 'admin/post-category-filter-utils.php' );
 		if( is_admin() ){
 			require_once( PD_PCF_PATH . 'admin/admin-pages.php' );	
 			require_once( PD_PCF_PATH . 'class-plugin-deactivate-feedback.php' );	
 			require_once( PD_PCF_PATH . 'class-plugin-review.php' );	
 			require_once( PD_PCF_PATH . 'support-page/class-support-page.php' );	
 		}
 		require_once( PD_PCF_PATH . 'class-ajax.php' );
 	}

 	//Elementor new category register method
 	public function pd_pcf_create_category() {
	   \Elementor\Plugin::$instance->elements_manager->add_category( 
		   	'plugin-devs-element',
		   	[
		   		'title' => esc_html( 'Plugin Devs Element', 'news-ticker-for-elementor' ),
		   		'icon' => 'fa fa-plug', //default icon
		   	],
		   	2 // position
	   );
	}
 }

function pd_pcf_register_function(){
	if( is_admin() ){
		$pd_pcf_feedback = new PD_PCF_Usage_Feedback(
			__FILE__,
			'webbuilders03@gmail.com',
			false,
			true
		);
	}
}
add_action('plugins_loaded', 'pd_pcf_register_function');
$pd_pcf = PD_PCF_SLIDER::getInstance();


add_action('wp_footer', 'pd_pcf_display_custom_css');
function pd_pcf_display_custom_css(){
	$custom_css = get_option( 'pd_pcf_custom_css' );
	$css ='';
	if ( ! empty( $custom_css ) ) {
		$css .= '<style type="text/css">';
		$css .= '/* Custom CSS */' . "\n";
		$css .= $custom_css . "\n";
		$css .= '</style>';
	}
	echo $css;
}

/**
 * Submenu filter function. Tested with Wordpress 4.1.1
 * Sort and order submenu positions to match your custom order.
 *
 */
function pd_pcf_order_submenu( $menu_ord ) {

  global $submenu;

  // Enable the next line to see a specific menu and it's order positions
  // echo '<pre>'; print_r( $submenu['pd-post-category-filter'] ); echo '</pre>'; exit();

  $arr = array();

  $arr[] = $submenu['pd-post-category-filter'][1];
  $arr[] = $submenu['pd-post-category-filter'][2];
  $arr[] = $submenu['pd-post-category-filter'][5];
  $arr[] = $submenu['pd-post-category-filter'][4];

  $submenu['pd-post-category-filter'] = $arr;

  return $menu_ord;

}

// add the filter to wordpress
add_filter( 'custom_menu_order', 'pd_pcf_order_submenu' );


/**
 * Setup Plugin Activation Time
 *
 * @since 1.0.1
 *
 */
register_activation_hook(__FILE__,  'pdpcf_setup_plugin_activation_time' );
add_action('upgrader_process_complete', 'pdpcf_setup_plugin_activation_time');
add_action('init', 'pdpcf_setup_plugin_activation_time');
function pdpcf_setup_plugin_activation_time(){
	$installation_time = get_option('pdpcf_installed_time');
	if( !$installation_time ){
		update_option('pdpcf_installed_time', current_time('timestamp'));
	}
}