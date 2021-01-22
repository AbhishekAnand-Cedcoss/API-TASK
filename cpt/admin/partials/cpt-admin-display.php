<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Cpt
 * @subpackage Cpt/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}

global $c_mwb_c_obj;
$c_active_tab   = isset( $_GET['c_tab'] ) ? sanitize_key( $_GET['c_tab'] ) : 'cpt-general';
$c_default_tabs = $c_mwb_c_obj->mwb_c_plug_default_tabs();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="mwb-c-main-wrapper">
	<div class="mwb-c-go-pro">
		<div class="mwb-c-go-pro-banner">
			<div class="mwb-c-inner-container">
				<div class="mwb-c-name-wrapper">
					<p><?php esc_html_e( 'CPT', 'cpt' ); ?></p></div>
					<div class="mwb-c-static-menu">
						<ul>
							<li>
								<a href="<?php echo esc_url( 'https://makewebbetter.com/contact-us/' ); ?>" target="_blank">
									<span class="dashicons dashicons-phone"></span>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( 'https://docs.makewebbetter.com/hubspot-woocommerce-integration/' ); ?>" target="_blank">
									<span class="dashicons dashicons-media-document"></span>
								</a>
							</li>
							<?php $c_plugin_pro_link = apply_filters( 'c_pro_plugin_link', '' ); ?>
							<?php if ( isset( $c_plugin_pro_link ) && '' != $c_plugin_pro_link ) { ?>
								<li class="mwb-c-main-menu-button">
									<a id="mwb-c-go-pro-link" href="<?php echo esc_url( $c_plugin_pro_link ); ?>" class="" title="" target="_blank"><?php esc_html_e( 'GO PRO NOW', 'cpt' ); ?></a>
								</li>
							<?php } else { ?>
								<li class="mwb-c-main-menu-button">
									<a id="mwb-c-go-pro-link" href="#" class="" title=""><?php esc_html_e( 'GO PRO NOW', 'cpt' ); ?></a>
								</li>
							<?php } ?>
							<?php $c_plugin_pro = apply_filters( 'c_pro_plugin_purcahsed', 'no' ); ?>
							<?php if ( isset( $c_plugin_pro ) && 'yes' == $c_plugin_pro ) { ?>
								<li>
									<a id="mwb-c-skype-link" href="<?php echo esc_url( 'https://join.skype.com/invite/IKVeNkLHebpC' ); ?>" target="_blank">
										<img src="<?php echo esc_url( CPT_DIR_URL . 'admin/images/skype_logo.png' ); ?>" style="height: 15px;width: 15px;" ><?php esc_html_e( 'Chat Now', 'cpt' ); ?>
									</a>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="mwb-c-main-template">
			<div class="mwb-c-body-template">
				<div class="mwb-c-navigator-template">
					<div class="mwb-c-navigations">
						<?php
						if ( is_array( $c_default_tabs ) && ! empty( $c_default_tabs ) ) {

							foreach ( $c_default_tabs as $c_tab_key => $c_default_tabs ) {

								$c_tab_classes = 'mwb-c-nav-tab ';

								if ( ! empty( $c_active_tab ) && $c_active_tab === $c_tab_key ) {
									$c_tab_classes .= 'c-nav-tab-active';
								}
								?>
								
								<div class="mwb-c-tabs">
									<a class="<?php echo esc_attr( $c_tab_classes ); ?>" id="<?php echo esc_attr( $c_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=cpt_menu' ) . '&c_tab=' . esc_attr( $c_tab_key ) ); ?>"><?php echo esc_html( $c_default_tabs['title'] ); ?></a>
								</div>

								<?php
							}
						}
						?>
					</div>
				</div>

				<div class="mwb-c-content-template">
					<div class="mwb-c-content-container">
						<?php
							// if submenu is directly clicked on woocommerce.
						if ( empty( $c_active_tab ) ) {

							$c_active_tab = 'mwb_c_plug_general';
						}

							// look for the path based on the tab id in the admin templates.
						$c_tab_content_path = 'admin/partials/' . $c_active_tab . '.php';

						$c_mwb_c_obj->mwb_c_plug_load_template( $c_tab_content_path );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
