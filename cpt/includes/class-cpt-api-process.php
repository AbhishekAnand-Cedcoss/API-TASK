<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Cpt
 * @subpackage Cpt/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Cpt_Api_Process' ) ) {

	/**
	 * The plugin API class.
	 *
	 * This is used to define the functions and data manipulation for custom endpoints.
	 *
	 * @since      1.0.0
	 * @package    Hydroshop_Api_Management
	 * @subpackage Hydroshop_Api_Management/includes
	 * @author     MakeWebBetter <makewebbetter.com>
	 */
	class Cpt_Api_Process {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {

		}

		/**
		 * Define the function to process data for custom endpoint.
		 *
		 * @since    1.0.0
		 * @param   Array $c_request  data of requesting headers and other information.
		 * @return  Array $mwb_c_rest_response    returns processed data and status of operations.
		 */
		public function mwb_c_default_process( $c_request ) {
			$mwb_c_rest_response = array();

			// Write your custom code here.

			$mwb_c_rest_response['status'] = 200;
			$mwb_c_rest_response['data'] = $c_request->get_headers();
			return $mwb_c_rest_response;
		}
	}
}
