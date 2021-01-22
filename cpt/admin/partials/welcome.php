<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the welcome html.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Cpt
 * @subpackage Cpt/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="mwb-c-main-wrapper">
	<div class="mwb-c-go-pro">
		<div class="mwb-c-go-pro-banner">
			<div class="mwb-c-inner-container">
				<div class="mwb-c-name-wrapper" id="mwb-c-page-header">
					<h3><?php esc_html_e( 'Welcome To MakeWebBetter', 'cpt' ); ?></h4>
					</div>
				</div>
			</div>
			<div class="mwb-c-inner-logo-container">
				<div class="mwb-c-main-logo">
					<img src="<?php echo esc_url( CPT_DIR_URL . 'admin/images/logo.png' ); ?>">
					<h2><?php esc_html_e( 'We make the customer experience better', 'cpt' ); ?></h2>
					<h3><?php esc_html_e( 'Being best at something feels great. Every Business desires a smooth buyer’s journey, WE ARE BEST AT IT.', 'cpt' ); ?></h3>
				</div>
				<div class="mwb-c-active-plugins-list">
					<?php
					$mwb_c_all_plugins = get_option( 'mwb_all_plugins_active', false );
					if ( is_array( $mwb_c_all_plugins ) && ! empty( $mwb_c_all_plugins ) ) {
						?>
						<table class="mwb-c-table">
							<thead>
								<tr class="mwb-plugins-head-row">
									<th><?php esc_html_e( 'Plugin Name', 'cpt' ); ?></th>
									<th><?php esc_html_e( 'Active Status', 'cpt' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if ( is_array( $mwb_c_all_plugins ) && ! empty( $mwb_c_all_plugins ) ) { ?>
									<?php foreach ( $mwb_c_all_plugins as $c_plugin_key => $c_plugin_value ) { ?>
										<tr class="mwb-plugins-row">
											<td><?php echo esc_html( $c_plugin_value['plugin_name'] ); ?></td>
											<?php if ( isset( $c_plugin_value['active'] ) && '1' != $c_plugin_value['active'] ) { ?>
												<td><?php esc_html_e( 'NO', 'cpt' ); ?></td>
											<?php } else { ?>
												<td><?php esc_html_e( 'YES', 'cpt' ); ?></td>
											<?php } ?>
										</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
						</table>
						<?php
					}
					?>
				</div>
			</div>
		</div>
