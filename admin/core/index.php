<?php
defined( 'ABSPATH' ) or die();
require_once WL_EHRM_PLUGIN_DIR_PATH . '/admin/core/WL_EHRM_LM.php';
require_once WL_EHRM_PLUGIN_DIR_PATH . '/admin/core/WL_EHRM_Menu.php';

$wl_ehrm_lm = WL_EHRM_LM::get_instance();
$wl_ehrm_lm_val = $wl_ehrm_lm->is_valid();
if ( ( isset( $wl_ehrm_lm_val ) && empty ( $wl_ehrm_lm_val ) ) ) {
	add_action( 'admin_menu', array( 'WL_EHRM_Menu', 'create_menu' ) );
}

?>