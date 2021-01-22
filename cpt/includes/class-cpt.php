<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Cpt
 * @subpackage Cpt/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cpt
 * @subpackage Cpt/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Cpt {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Cpt_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'CPT_VERSION' ) ) {

			$this->version = CPT_VERSION;
		} else {

			$this->version = '1.0.0';
		}

		$this->plugin_name = 'cpt';

		$this->cpt_dependencies();
		$this->cpt_locale();
		$this->cpt_admin_hooks();
		$this->cpt_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cpt_Loader. Orchestrates the hooks of the plugin.
	 * - Cpt_i18n. Defines internationalization functionality.
	 * - Cpt_Admin. Defines all hooks for the admin area.
	 * - Cpt_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function cpt_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cpt-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cpt-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cpt-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cpt-public.php';

		$this->loader = new Cpt_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cpt_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function cpt_locale() {

		$plugin_i18n = new Cpt_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function cpt_admin_hooks() {

		$c_plugin_admin = new Cpt_Admin( $this->c_get_plugin_name(), $this->c_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $c_plugin_admin, 'c_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $c_plugin_admin, 'c_admin_enqueue_scripts' );

		// Add settings menu for CPT.
		$this->loader->add_action( 'admin_menu', $c_plugin_admin, 'c_options_page' );

		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter( 'mwb_add_plugins_menus_array', $c_plugin_admin, 'c_admin_submenu_page', 15 );
		$this->loader->add_filter( 'c_general_settings_array', $c_plugin_admin, 'c_admin_general_settings_page', 10 );

		// My Custom Code.

		// Register Setting.
		$this->loader->add_action( 'admin_init', $c_plugin_admin, 'mwb_settings_init' );
		$this->loader->add_action( 'admin_menu', $c_plugin_admin, 'mwb_options_page' );

		// Template Include.
		// $this->loader->add_filter( 'template_include', $c_plugin_admin, 'template_path', 10 );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function cpt_public_hooks() {

		$c_plugin_public = new Cpt_Public( $this->c_get_plugin_name(), $this->c_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $c_plugin_public, 'c_public_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $c_plugin_public, 'c_public_enqueue_scripts' );

		// Template Include.
		$this->loader->add_filter( 'template_include', $c_plugin_public, 'template_path', 10 );
		$this->loader->add_action( 'wp_ajax_checkpin', $c_plugin_public, 'my_ajax_handler' );

	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function c_run() {
		$this->loader->c_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function c_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Cpt_Loader    Orchestrates the hooks of the plugin.
	 */
	public function c_get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function c_get_version() {
		return $this->version;
	}

	/**
	 * Predefined default mwb_c_plug tabs.
	 *
	 * @return  Array       An key=>value pair of CPT tabs.
	 */
	public function mwb_c_plug_default_tabs() {

		$c_default_tabs = array();

		$c_default_tabs['cpt-general'] = array(
			'title'       => esc_html__( 'General Setting', 'cpt' ),
			'name'        => 'cpt-general',
		);
		$c_default_tabs = apply_filters( 'mwb_c_plugin_standard_admin_settings_tabs', $c_default_tabs );

		$c_default_tabs['cpt-system-status'] = array(
			'title'       => esc_html__( 'System Status', 'cpt' ),
			'name'        => 'cpt-system-status',
		);

		return $c_default_tabs;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 * @param string $path path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function mwb_c_plug_load_template( $path, $params = array() ) {

		$c_file_path = CPT_DIR_PATH . $path;

		if ( file_exists( $c_file_path ) ) {

			include $c_file_path;
		} else {

			/* translators: %s: file path */
			$c_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'cpt' ), $c_file_path );
			$this->mwb_c_plug_admin_notice( $c_notice, 'error' );
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param  string $c_message    Message to display.
	 * @param  string $type       notice type, accepted values - error/update/update-nag.
	 * @since  1.0.0
	 */
	public static function mwb_c_plug_admin_notice( $c_message, $type = 'error' ) {

		$c_classes = 'notice ';

		switch ( $type ) {

			case 'update':
				$c_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$c_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$c_classes .= 'notice-success is-dismissible';
				break;

			default:
				$c_classes .= 'notice-error is-dismissible';
		}

		$c_notice  = '<div class="' . esc_attr( $c_classes ) . '">';
		$c_notice .= '<p>' . esc_html( $c_message ) . '</p>';
		$c_notice .= '</div>';

		echo wp_kses_post( $c_notice );
	}


	/**
	 * Show wordpress and server info.
	 *
	 * @return  Array $c_system_data       returns array of all wordpress and server related information.
	 * @since  1.0.0
	 */
	public function mwb_c_plug_system_status() {
		global $wpdb;
		$c_system_status = array();
		$c_wordpress_status = array();
		$c_system_data = array();

		// Get the web server.
		$c_system_status['web_server'] = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';

		// Get PHP version.
		$c_system_status['php_version'] = function_exists( 'phpversion' ) ? phpversion() : __( 'N/A (phpversion function does not exist)', 'cpt' );

		// Get the server's IP address.
		$c_system_status['server_ip'] = isset( $_SERVER['SERVER_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_ADDR'] ) ) : '';

		// Get the server's port.
		$c_system_status['server_port'] = isset( $_SERVER['SERVER_PORT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_PORT'] ) ) : '';

		// Get the uptime.
		$c_system_status['uptime'] = function_exists( 'exec' ) ? @exec( 'uptime -p' ) : __( 'N/A (make sure exec function is enabled)', 'cpt' );

		// Get the server path.
		$c_system_status['server_path'] = defined( 'ABSPATH' ) ? ABSPATH : __( 'N/A (ABSPATH constant not defined)', 'cpt' );

		// Get the OS.
		$c_system_status['os'] = function_exists( 'php_uname' ) ? php_uname( 's' ) : __( 'N/A (php_uname function does not exist)', 'cpt' );

		// Get WordPress version.
		$c_wordpress_status['wp_version'] = function_exists( 'get_bloginfo' ) ? get_bloginfo( 'version' ) : __( 'N/A (get_bloginfo function does not exist)', 'cpt' );

		// Get and count active WordPress plugins.
		$c_wordpress_status['wp_active_plugins'] = function_exists( 'get_option' ) ? count( get_option( 'active_plugins' ) ) : __( 'N/A (get_option function does not exist)', 'cpt' );

		// See if this site is multisite or not.
		$c_wordpress_status['wp_multisite'] = function_exists( 'is_multisite' ) && is_multisite() ? __( 'Yes', 'cpt' ) : __( 'No', 'cpt' );

		// See if WP Debug is enabled.
		$c_wordpress_status['wp_debug_enabled'] = defined( 'WP_DEBUG' ) ? __( 'Yes', 'cpt' ) : __( 'No', 'cpt' );

		// See if WP Cache is enabled.
		$c_wordpress_status['wp_cache_enabled'] = defined( 'WP_CACHE' ) ? __( 'Yes', 'cpt' ) : __( 'No', 'cpt' );

		// Get the total number of WordPress users on the site.
		$c_wordpress_status['wp_users'] = function_exists( 'count_users' ) ? count_users() : __( 'N/A (count_users function does not exist)', 'cpt' );

		// Get the number of published WordPress posts.
		$c_wordpress_status['wp_posts'] = wp_count_posts()->publish >= 1 ? wp_count_posts()->publish : __( '0', 'cpt' );

		// Get PHP memory limit.
		$c_system_status['php_memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'cpt' );

		// Get the PHP error log path.
		$c_system_status['php_error_log_path'] = ! ini_get( 'error_log' ) ? __( 'N/A', 'cpt' ) : ini_get( 'error_log' );

		// Get PHP max upload size.
		$c_system_status['php_max_upload'] = function_exists( 'ini_get' ) ? (int) ini_get( 'upload_max_filesize' ) : __( 'N/A (ini_get function does not exist)', 'cpt' );

		// Get PHP max post size.
		$c_system_status['php_max_post'] = function_exists( 'ini_get' ) ? (int) ini_get( 'post_max_size' ) : __( 'N/A (ini_get function does not exist)', 'cpt' );

		// Get the PHP architecture.
		if ( PHP_INT_SIZE == 4 ) {
			$c_system_status['php_architecture'] = '32-bit';
		} elseif ( PHP_INT_SIZE == 8 ) {
			$c_system_status['php_architecture'] = '64-bit';
		} else {
			$c_system_status['php_architecture'] = 'N/A';
		}

		// Get server host name.
		$c_system_status['server_hostname'] = function_exists( 'gethostname' ) ? gethostname() : __( 'N/A (gethostname function does not exist)', 'cpt' );

		// Show the number of processes currently running on the server.
		$c_system_status['processes'] = function_exists( 'exec' ) ? @exec( 'ps aux | wc -l' ) : __( 'N/A (make sure exec is enabled)', 'cpt' );

		// Get the memory usage.
		$c_system_status['memory_usage'] = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage( true ) / 1024 / 1024, 2 ) : 0;

		// Get CPU usage.
		// Check to see if system is Windows, if so then use an alternative since sys_getloadavg() won't work.
		if ( stristr( PHP_OS, 'win' ) ) {
			$c_system_status['is_windows'] = true;
			$c_system_status['windows_cpu_usage'] = function_exists( 'exec' ) ? @exec( 'wmic cpu get loadpercentage /all' ) : __( 'N/A (make sure exec is enabled)', 'cpt' );
		}

		// Get the memory limit.
		$c_system_status['memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'cpt' );

		// Get the PHP maximum execution time.
		$c_system_status['php_max_execution_time'] = function_exists( 'ini_get' ) ? ini_get( 'max_execution_time' ) : __( 'N/A (ini_get function does not exist)', 'cpt' );

		// Get outgoing IP address.
		$c_system_status['outgoing_ip'] = function_exists( 'file_get_contents' ) ? file_get_contents( 'http://ipecho.net/plain' ) : __( 'N/A (file_get_contents function does not exist)', 'cpt' );

		$c_system_data['php'] = $c_system_status;
		$c_system_data['wp'] = $c_wordpress_status;

		return $c_system_data;
	}

	/**
	 * Generate html components.
	 *
	 * @param  string $c_components    html to display.
	 * @since  1.0.0
	 */
	public function mwb_c_plug_generate_html( $c_components = array() ) {
		if ( is_array( $c_components ) && ! empty( $c_components ) ) {
			foreach ( $c_components as $c_component ) {
				switch ( $c_component['type'] ) {

					case 'hidden':
					case 'number':
					case 'password':
					case 'email':
					case 'text':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $c_component['id'] ); ?>"><?php echo esc_html( $c_component['title'] ); // WPCS: XSS ok. ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $c_component['type'] ) ); ?>">
								<input
								name="<?php echo esc_attr( $c_component['id'] ); ?>"
								id="<?php echo esc_attr( $c_component['id'] ); ?>"
								type="<?php echo esc_attr( $c_component['type'] ); ?>"
								value="<?php echo esc_attr( $c_component['value'] ); ?>"
								class="<?php echo esc_attr( $c_component['class'] ); ?>"
								placeholder="<?php echo esc_attr( $c_component['placeholder'] ); ?>"
								/>
								<p class="c-descp-tip"><?php echo esc_html( $c_component['description'] ); // WPCS: XSS ok. ?></p>
							</td>
						</tr>
						<?php
						break;

					case 'textarea':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $c_component['id'] ); ?>"><?php echo esc_html( $c_component['title'] ); ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $c_component['type'] ) ); ?>">
								<textarea
								name="<?php echo esc_attr( $c_component['id'] ); ?>"
								id="<?php echo esc_attr( $c_component['id'] ); ?>"
								class="<?php echo esc_attr( $c_component['class'] ); ?>"
								rows="<?php echo esc_attr( $c_component['rows'] ); ?>"
								cols="<?php echo esc_attr( $c_component['cols'] ); ?>"
								placeholder="<?php echo esc_attr( $c_component['placeholder'] ); ?>"
								><?php echo esc_textarea( $c_component['value'] ); // WPCS: XSS ok. ?></textarea>
								<p class="c-descp-tip"><?php echo esc_html( $c_component['description'] ); // WPCS: XSS ok. ?></p>
							</td>
						</tr>
						<?php
						break;

					case 'select':
					case 'multiselect':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $c_component['id'] ); ?>"><?php echo esc_html( $c_component['title'] ); ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $c_component['type'] ) ); ?>">
								<select
								name="<?php echo esc_attr( $c_component['id'] ); ?><?php echo ( 'multiselect' === $c_component['type'] ) ? '[]' : ''; ?>"
								id="<?php echo esc_attr( $c_component['id'] ); ?>"
								class="<?php echo esc_attr( $c_component['class'] ); ?>"
								<?php echo 'multiselect' === $c_component['type'] ? 'multiple="multiple"' : ''; ?>
								>
								<?php
								foreach ( $c_component['options'] as $c_key => $c_val ) {
									?>
									<option value="<?php echo esc_attr( $c_key ); ?>"
										<?php
										if ( is_array( $c_component['value'] ) ) {
											selected( in_array( (string) $c_key, $c_component['value'], true ), true );
										} else {
											selected( $c_component['value'], (string) $c_key );
										}
										?>
										>
										<?php echo esc_html( $c_val ); ?>
									</option>
									<?php
								}
								?>
								</select> 
								<p class="c-descp-tip"><?php echo esc_html( $c_component['description'] ); // WPCS: XSS ok. ?></p>
							</td>
						</tr>
						<?php
						break;

					case 'checkbox':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc"><?php echo esc_html( $c_component['title'] ); ?></th>
							<td class="forminp forminp-checkbox">
								<label for="<?php echo esc_attr( $c_component['id'] ); ?>"></label>
								<input
								name="<?php echo esc_attr( $c_component['id'] ); ?>"
								id="<?php echo esc_attr( $c_component['id'] ); ?>"
								type="checkbox"
								class="<?php echo esc_attr( isset( $c_component['class'] ) ? $c_component['class'] : '' ); ?>"
								value="1"
								<?php checked( $c_component['value'], '1' ); ?>
								/> 
								<span class="c-descp-tip"><?php echo esc_html( $c_component['description'] ); // WPCS: XSS ok. ?></span>

							</td>
						</tr>
						<?php
						break;

					case 'radio':
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $c_component['id'] ); ?>"><?php echo esc_html( $c_component['title'] ); ?>
							</th>
							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $c_component['type'] ) ); ?>">
								<fieldset>
									<span class="c-descp-tip"><?php echo esc_html( $c_component['description'] ); // WPCS: XSS ok. ?></span>
									<ul>
										<?php
										foreach ( $c_component['options'] as $c_radio_key => $c_radio_val ) {
											?>
											<li>
												<label><input
													name="<?php echo esc_attr( $c_component['id'] ); ?>"
													value="<?php echo esc_attr( $c_radio_key ); ?>"
													type="radio"
													class="<?php echo esc_attr( $c_component['class'] ); ?>"
												<?php checked( $c_radio_key, $c_component['value'] ); ?>
													/> <?php echo esc_html( $c_radio_val ); ?></label>
											</li>
											<?php
										}
										?>
									</ul>
								</fieldset>
							</td>
						</tr>
						<?php
						break;

					case 'button':
						?>
						<tr valign="top">
							<td scope="row">
								<input type="button" class="button button-primary" 
								name="<?php echo esc_attr( $c_component['id'] ); ?>"
								id="<?php echo esc_attr( $c_component['id'] ); ?>"
								value="<?php echo esc_attr( $c_component['button_text'] ); ?>"
								/>
							</td>
						</tr>
						<?php
						break;

					case 'submit':
						?>
						<tr valign="top">
							<td scope="row">
								<input type="submit" class="button button-primary" 
								name="<?php echo esc_attr( $c_component['id'] ); ?>"
								id="<?php echo esc_attr( $c_component['id'] ); ?>"
								value="<?php echo esc_attr( $c_component['button_text'] ); ?>"
								/>
							</td>
						</tr>
						<?php
						break;

					default:
						break;
				}
			}
		}
	}
}
