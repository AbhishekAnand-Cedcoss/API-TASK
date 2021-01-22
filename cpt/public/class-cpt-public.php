<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Cpt
 * @subpackage Cpt/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * namespace cpt_public.
 *
 * @package    Cpt
 * @subpackage Cpt/public
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Cpt_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function c_public_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, CPT_DIR_URL . 'public/css/cpt-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function c_public_enqueue_scripts() {

		wp_register_script( $this->plugin_name, CPT_DIR_URL . 'public/js/cpt-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'c_public_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name );

		$title_nonce = wp_create_nonce( 'title_example' );
		wp_localize_script(
			$this->plugin_name,
			'my_ajax_obj',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => $title_nonce,
			)
		);

	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $template_path includes global variable.
	 * @return $template_path
	 */
	public function template_path( $template_path ) {
		if ( is_single() ) {
			$template_path = CPT_DIR_PATH. '/public/partials/templates/checkpintemplate.php';
		}
		return $template_path;
	}

	/**
	 * Undocumented function
	 *
	 * @return void.
	 */
	public function my_ajax_handler() {
		
		check_ajax_referer( 'title_example' );
		$pin     = sanitize_text_field( wp_unslash( $_POST['pin'] ) );
		$options = get_option( 'mwb_options' );
		if ( in_array( $pin, $options, true ) ) {
			echo 'Deliverable Area';
		} else {
			echo 'Non Deliverable Area';
		}
		wp_die();
	}

}
