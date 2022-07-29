<?php
defined( 'ABSPATH' ) or die();

class WL_EHRM_Menu {
	public static function create_menu() {
		require_once WL_EHRM_PLUGIN_DIR_PATH . '/admin/core/WL_EHRM_LM.php';
		$wl_ehrm_lm = WL_EHRM_LM::get_instance();
		$wl_ehrm_lm_val = $wl_ehrm_lm->is_valid();

		if ( ! ( isset( $wl_ehrm_lm_val ) && $wl_ehrm_lm_val ) ) {
		$wl_admin_menu = add_menu_page( __( 'Employee & HR Management', 'employee-&-hr-management' ), __( 'Employee & HR Management', 'employee-&-hr-management' ), 'manage_options', 'employee-and-hr-managemen-license', array( 'WL_EHRM_Menu', 'admin_menu' ), 'dashicons-groups', 25 );
		add_action( 'admin_print_styles-' . $wl_admin_menu, array( 'WL_EHRM_Menu', 'admin_menu_assets' ) );
		}
	}

	public static function admin_menu() {
		require_once( 'inc/admin_menu.php' );
	}

	public static function admin_menu_assets() {
		wp_enqueue_style( 'wp_cip_lc', WL_EHRM_PLUGIN_URL . '/admin/core/inc/css/admin_menu.css' );
	}
}
?>