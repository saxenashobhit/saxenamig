<?php
defined( 'ABSPATH' ) or die();

class EHRMLanguage {
	public static function load_translation() {
		load_plugin_textdomain( 'employee-&-hr-management', false, basename( WL_EHRM_PLUGIN_DIR_PATH ) . '/lang' );
	}
}