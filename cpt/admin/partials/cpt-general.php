<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
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
global $c_mwb_c_obj;
$c_genaral_settings = apply_filters( 'c_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<div class="c-secion-wrap">
	<table class="form-table c-settings-table">
		<?php
			$c_general_html = $c_mwb_c_obj->mwb_c_plug_generate_html( $c_genaral_settings );
			echo esc_html( $c_general_html );
		?>
	</table>
</div>
