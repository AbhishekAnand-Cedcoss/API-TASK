<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Cpt
 *
 * @wordpress-plugin
 * Plugin Name:       CPT
 * Plugin URI:        https://makewebbetter.com/product/cpt/
 * Description:       Cusrom Post Type
 * Version:           1.0.0
 * Author:            makewebbetter
 * Author URI:        https://makewebbetter.com/
 * Text Domain:       cpt
 * Domain Path:       /languages
 *
 * Requires at least: 4.6
 * Tested up to:      4.9.5
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Define plugin constants.
 *
 * @since             1.0.0
 */
function define_cpt_constants() {

	cpt_constants( 'CPT_VERSION', '1.0.0' );
	cpt_constants( 'CPT_DIR_PATH', plugin_dir_path( __FILE__ ) );
	cpt_constants( 'CPT_DIR_URL', plugin_dir_url( __FILE__ ) );
	cpt_constants( 'CPT_SERVER_URL', 'https://makewebbetter.com' );
	cpt_constants( 'CPT_ITEM_REFERENCE', 'CPT' );
}


/**
 * Callable function for defining plugin constants.
 *
 * @param   String $key    Key for contant.
 * @param   String $value   value for contant.
 * @since             1.0.0
 */
function cpt_constants( $key, $value ) {

	if ( ! defined( $key ) ) {

		define( $key, $value );
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cpt-activator.php
 */
function activate_cpt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cpt-activator.php';
	Cpt_Activator::cpt_activate();
	$mwb_c_active_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_c_active_plugin ) && ! empty( $mwb_c_active_plugin ) ) {
		$mwb_c_active_plugin['cpt'] = array(
			'plugin_name' => __( 'CPT', 'cpt' ),
			'active' => '1',
		);
	} else {
		$mwb_c_active_plugin = array();
		$mwb_c_active_plugin['cpt'] = array(
			'plugin_name' => __( 'CPT', 'cpt' ),
			'active' => '1',
		);
	}
	update_option( 'mwb_all_plugins_active', $mwb_c_active_plugin );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cpt-deactivator.php
 */
function deactivate_cpt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cpt-deactivator.php';
	Cpt_Deactivator::cpt_deactivate();
	$mwb_c_deactive_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_c_deactive_plugin ) && ! empty( $mwb_c_deactive_plugin ) ) {
		foreach ( $mwb_c_deactive_plugin as $mwb_c_deactive_key => $mwb_c_deactive ) {
			if ( 'cpt' === $mwb_c_deactive_key ) {
				$mwb_c_deactive_plugin[ $mwb_c_deactive_key ]['active'] = '0';
			}
		}
	}
	update_option( 'mwb_all_plugins_active', $mwb_c_deactive_plugin );
}

register_activation_hook( __FILE__, 'activate_cpt' );
register_deactivation_hook( __FILE__, 'deactivate_cpt' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cpt.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cpt() {
	define_cpt_constants();

	$c_plugin_standard = new Cpt();
	$c_plugin_standard->c_run();
	$GLOBALS['c_mwb_c_obj'] = $c_plugin_standard;

}
run_cpt();

// Add rest api endpoint for plugin.
add_action( 'rest_api_init', 'c_add_default_endpoint' );

/**
 * Callback function for endpoints.
 *
 * @since    1.0.0
 */
function c_add_default_endpoint() {
	register_rest_route(
		'c-route',
		'/c-dummy-data/',
		array(
			'methods'  => 'POST',
			'callback' => 'mwb_c_default_callback',
			'permission_callback' => 'mwb_c_default_permission_check',
		)
	);
}

/**
 * API validation
 * @param 	Array 	$request 	All information related with the api request containing in this array.
 * @since    1.0.0
 */
function mwb_c_default_permission_check($request) {

	// Add rest api validation for each request.
	$result = true;
	return $result;
}

/**
 * Begins execution of api endpoint.
 *
 * @param   Array $request    All information related with the api request containing in this array.
 * @return  Array   $mwb_c_response   return rest response to server from where the endpoint hits.
 * @since    1.0.0
 */
function mwb_c_default_callback( $request ) {
	require_once CPT_DIR_PATH . 'includes/class-cpt-api-process.php';
	$mwb_c_api_obj = new Cpt_Api_Process();
	$mwb_c_resultsdata = $mwb_c_api_obj->mwb_c_default_process( $request );
	if ( is_array( $mwb_c_resultsdata ) && isset( $mwb_c_resultsdata['status'] ) && 200 == $mwb_c_resultsdata['status'] ) {
		unset( $mwb_c_resultsdata['status'] );
		$mwb_c_response = new WP_REST_Response( $mwb_c_resultsdata, 200 );
	} else {
		$mwb_c_response = new WP_Error( $mwb_c_resultsdata );
	}
	return $mwb_c_response;
}


// Add settings link on plugin page.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'cpt_settings_link' );

/**
 * Settings link.
 *
 * @since    1.0.0
 * @param   Array $links    Settings link array.
 */
function cpt_settings_link( $links ) {

	$my_link = array(
		'<a href="' . admin_url( 'admin.php?page=cpt_menu' ) . '">' . __( 'Settings', 'cpt' ) . '</a>',
	);
	return array_merge( $my_link, $links );
}

// cURL.

// $request = wp_remote_get( 'https://api.postalpincode.in/postoffice/Lucknow' );

// if( is_wp_error( $request ) ) {
// 	return false; // Bail early
// }

// $body = wp_remote_retrieve_body( $request );

// $data = json_decode( $body );
// $pin_count = count( $data[0]->PostOffice);
// echo $pin_count;
// die();
// echo '<pre>';
// print_r( $data[0]->PostOffice[0]);
// echo '</pre>';
// die();

// for ( $i = 0; $i < $pin_count; $i++ ) {
	// echo $data[0]->PostOffice[ $i ]->Pincode;
	// echo $data[0]->PostOffice[ $i ]->Name;
	// echo '<br>';
// }

