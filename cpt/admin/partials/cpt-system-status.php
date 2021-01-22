<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html for system status.
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
// Template for showing information about system status.
global $c_mwb_c_obj;
$c_default_status = $c_mwb_c_obj->mwb_c_plug_system_status();
$c_wordpress_details = is_array( $c_default_status['wp'] ) && ! empty( $c_default_status['wp'] ) ? $c_default_status['wp'] : array();
$c_php_details = is_array( $c_default_status['php'] ) && ! empty( $c_default_status['php'] ) ? $c_default_status['php'] : array();
?>
<div class="mwb-c-table-wrap">
	<div class="mwb-c-table-inner-container">
		<table class="mwb-c-table" id="mwb-c-wp">
			<thead>
				<tr>
					<th><?php esc_html_e( 'WP Variables', 'cpt' ); ?></th>
					<th><?php esc_html_e( 'WP Values', 'cpt' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( is_array( $c_wordpress_details ) && ! empty( $c_wordpress_details ) ) { ?>
					<?php foreach ( $c_wordpress_details as $wp_key => $wp_value ) { ?>
						<?php if ( isset( $wp_key ) && 'wp_users' != $wp_key ) { ?>
							<tr>
								<td><?php echo esc_html( $wp_key ); ?></td>
								<td><?php echo esc_html( $wp_value ); ?></td>
							</tr>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="mwb-c-table-inner-container">
		<table class="mwb-c-table" id="mwb-c-php">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Sysytem Variables', 'cpt' ); ?></th>
					<th><?php esc_html_e( 'System Values', 'cpt' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( is_array( $c_php_details ) && ! empty( $c_php_details ) ) { ?>
					<?php foreach ( $c_php_details as $php_key => $php_value ) { ?>
						<tr>
							<td><?php echo esc_html( $php_key ); ?></td>
							<td><?php echo esc_html( $php_value ); ?></td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
