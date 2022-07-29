<?php
defined( 'ABSPATH' ) or die();

require_once( 'wl_ehrm_language.php' );
require_once( 'wl_ehrm_shortcode.php' );
require_once( 'inc/controllers/wl_ehrm_login_actions.php' );

/* Load text domain */
add_action( 'plugins_loaded', array( 'EHRMLanguage', 'load_translation' ) );

/* Enqueue Assets for shortcodes */
add_action( 'wp_enqueue_scripts', array( 'EHRMShortcode', 'shortcode_enqueue_assets' ) );

/* Login Form Shortcode files */
add_shortcode( 'WL_EHRM_LOGIN_FORM', array( 'EHRMShortcode', 'login_portal' ) );

add_shortcode( 'WL_EHRM_GO_LOGIN', array( 'EHRMShortcode', 'goto_login_portal') );

/**----------------------------------------------------------------Staff login actions for frontend shortcode----------------------------------------------------------------**/

/* Staff's clock actions */
add_action( 'wp_ajax_nopriv_ehrm_front_clock_action', array( 'FrontDashBoardAction', 'clock_actions' ) );
add_action( 'wp_ajax_ehrm_front_clock_action', array( 'FrontDashBoardAction', 'clock_actions' ) );

/* Late reson submit actions */
add_action( 'wp_ajax_nopriv_ehrm_front_late_reson_action', array( 'FrontDashBoardAction', 'late_reson_submit' ) );
add_action( 'wp_ajax_ehrm_front_late_reson_action', array( 'FrontDashBoardAction', 'late_reson_submit' ) );

/* Daily report submit actions */
add_action( 'wp_ajax_nopriv_ehrm_front_daily_report_action', array( 'FrontDashBoardAction', 'staff_daily_report' ) );
add_action( 'wp_ajax_ehrm_front_daily_report_action', array( 'FrontDashBoardAction', 'staff_daily_report' ) );

//add_filter('login_redirect', array('FrontDashBoardAction','admin_default_page'));

?>