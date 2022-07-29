<?php
/*
Plugin Name: Employee & HR Management DB
Plugin URI:  https://weblizar.com/
Description: World's most advanced Employee & HR Management Plugin. You can manage Departments, Employees Attendance, Salary, Real Time Working Hours, Projects, Tasks, Monthly Report Generation, Multi TimeZone Login, Leaves, Notices.
Author: weblizar
Author URI: https://weblizar.com/
Version: 2.6.1
Text Domain: employee-&-hr-management
Domain Path: /lang/
*/

defined( 'ABSPATH' ) or die();

if ( ! defined( 'WL_EHRM_PLUGIN_URL' ) ) {
	define( 'WL_EHRM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'WL_EHRM_PLUGIN_DIR_PATH' ) ) {
	define( 'WL_EHRM_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WL_EHRM_PLUGIN_BASENAME' ) ) {
	define( 'WL_EHRM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'WL_EHRM_PLUGIN_FILE' ) ) {
	define( 'WL_EHRM_PLUGIN_FILE', __FILE__ );
}

define( 'WL_EHRM_PRO_PLUGIN_URL', 'https://weblizar.com/plugins/employee-and-hr-management/' );
define( 'WL_EHRM_VERSION', '2.6' );
include 'wlehrm-update-checker.php';

final class EmployeeHRManagement {
	private static $instance = null;

	private function __construct() {
		$this->initialize_hooks();
		$this->setup_init();
		$this->setupDatabase();
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function initialize_hooks() {
		if ( is_admin() ) {
			require_once( 'admin/admin-setup-wizard.php' );
			require_once( 'admin/core/index.php' );
			require_once( 'admin/admin.php' );			
		}
		require_once( 'public/public.php' );
	}

	private function setup_init() {

		require_once( 'admin/inc/wl_ehrm_default_options.php' );

		register_activation_hook( __FILE__, array( 'SetDeafaultOptions', 'ehrm_activation_actions' ) );
		add_action( 'ehrm_extension_activation', array( 'SetDeafaultOptions', 'default_settings' ) );
		add_action( 'init', array( 'SetDeafaultOptions', 'ehrm_allow_subscriber_uploads' ) );
		add_action( 'pre_get_posts', array( 'SetDeafaultOptions', 'ehrm_users_own_attachments' ) );

		register_activation_hook( __FILE__, array( 'SetDeafaultOptions', 'ehrm_activation_default_emails' ) );
		add_action( 'ehrm_default_emails_activation', array( 'SetDeafaultOptions', 'ehrm_setup_default_emails' ) );

		register_activation_hook( __FILE__, array( 'SetDeafaultOptions', 'ehrm_activation_default_sms' ) );
		add_action( 'ehrm_default_sms_activation', array( 'SetDeafaultOptions', 'ehrm_setup_default_sms' ) );

		register_activation_hook(__FILE__, array( 'SetDeafaultOptions', 'ehrm_setup_wizard_activation_hook') );
		if ( empty ( get_option( 'ehrm_settings_data' ) ) || empty ( get_option( 'ehrm_departments_data' ) ) || empty ( get_option( 'ehrm_shifts_data' ) ) || empty ( get_option( 'ehrm_designations_data' ) ) ) {
			add_action( 'admin_init', array( 'SetDeafaultOptions', 'ehrm_setup_wizard_redirect' ) );
		}

		register_deactivation_hook( __FILE__, array( 'SetDeafaultOptions', 'remove_items' ) );
		register_uninstall_hook( __FILE__, array( 'SetDeafaultOptions', 'remove_items' ) );
	}

	/**
	 * Setup Database
	 */
	private function setupDatabase() {
		require_once( WL_EHRM_PLUGIN_DIR_PATH . 'admin/inc/EHRM_Database.php' );
		register_activation_hook( __FILE__, array( 'EHRM_DATABASE', 'activation' ) );	
	}
}

EmployeeHRManagement::get_instance();

require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );
function ehrm_staff_login_redorect( $url, $request, $user ) {

	$save_settings = get_option( 'ehrm_settings_data' );
	$user_ip       = $_SERVER['REMOTE_ADDR'];

	if ( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {

		if( $user->has_cap( 'administrator') ) {

			$url = admin_url();

			} else {

				if ( $save_settings['ip_restriction'] == 'Yes' ) {

					if ( isset ( $save_settings['restrict_ips'] ) ) {

						$rstd_ip  = $save_settings['restrict_ips'];
						$ip_parts = explode( '.', $rstd_ip );

							if ( $save_settings['ip_rest_type'] == 'single' ) {
								$rstd_ip = $ip_parts[0].'.'.$ip_parts[1].'.'.$ip_parts[2].'.'.$ip_parts[3];
							} else {
								$rstd_ip = $ip_parts[0].'.'.$ip_parts[1].'.'.$ip_parts[2];
							}
						}

						if ( strpos( $user_ip, $rstd_ip ) === 0 ) {
							$url = admin_url('/admin.php?page=employee-and-hr-management-staff-dashboard/');
						} else {
							$url = admin_url('/admin.php?page=employee-and-hr-management-staff-unauthorized/');
						}

				} else {
					$url = admin_url('/admin.php?page=employee-and-hr-management-staff-dashboard/');
				}

			}
		}
	return $url;
}
add_filter( 'login_redirect', 'ehrm_staff_login_redorect', 10, 3 );
?>
