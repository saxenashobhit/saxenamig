<?php
defined('ABSPATH') or die();
require_once(WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php');

/**
 *  Add Admin Menu Panel 
 */
class WL_EHRM_AdminMenu {
	public static function create_menu() {

		$dashboard = add_menu_page(__('Employee & HR Management', 'employee-&-hr-management'), __('Employee & HR Management', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management', array(
			'WL_EHRM_AdminMenu',
			'dashboard'
		), 'dashicons-groups', 25);
		add_action('admin_print_styles-' . $dashboard, array('WL_EHRM_AdminMenu', 'dashboard_assets'));

		/* Dashboard submenu */
		$dashboard_submenu = add_submenu_page('employee-and-hr-management', __('Employee & HR Management', 'employee-&-hr-management'), __('Dashboard', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management', array(
			'WL_EHRM_AdminMenu',
			'dashboard'
		));
		add_action('admin_print_styles-' . $dashboard_submenu, array('WL_EHRM_AdminMenu', 'dashboard_assets'));

		/* Designation submenu */
		$designation_submenu = add_submenu_page('employee-and-hr-management', __('Designation', 'employee-&-hr-management'), __('Designation', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management-designation', array(
			'WL_EHRM_AdminMenu',
			'designation'
		));
		add_action('admin_print_styles-' . $designation_submenu, array('WL_EHRM_AdminMenu', 'dashboard_assets'));

		/* Leave Requests submenu */
		$requests_submenu = add_submenu_page('employee-and-hr-management', __('Leave Requests', 'employee-&-hr-management'), __('Leave Requests', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management-requests', array(
			'WL_EHRM_AdminMenu',
			'requests'
		));
		add_action('admin_print_styles-' . $requests_submenu, array('WL_EHRM_AdminMenu', 'dashboard_assets'));

		/* Shift submenu */
		$shift_submenu = add_submenu_page('employee-and-hr-management', __('Shifts', 'employee-&-hr-management'), __('Shifts', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management-shift', array(
			'WL_EHRM_AdminMenu',
			'shift'
		));
		add_action('admin_print_styles-' . $shift_submenu, array('WL_EHRM_AdminMenu', 'event_assets'));

		/* Staff submenu */
		$staff_submenu = add_submenu_page('employee-and-hr-management', __('Staff', 'employee-&-hr-management'), __('Staff', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management-staff', array(
			'WL_EHRM_AdminMenu',
			'staff'
		));
		add_action('admin_print_styles-' . $staff_submenu, array('WL_EHRM_AdminMenu', 'dashboard_assets'));

		/* Reports submenu */
		$reports_submenu = add_submenu_page('employee-and-hr-management', __('Reports', 'employee-&-hr-management'), __('Reports', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management-reports', array(
			'WL_EHRM_AdminMenu',
			'reports'
		));
		add_action('admin_print_styles-' . $reports_submenu, array('WL_EHRM_AdminMenu', 'report_assets'));

		/* Pay roll submenu */
		$payroll_submenu = add_submenu_page('employee-and-hr-management', __('Payroll', 'employee-&-hr-management'), __('Payroll', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management-payroll', array(
			'WL_EHRM_AdminMenu',
			'payroll'
		));
		add_action('admin_print_styles-' . $payroll_submenu, array('WL_EHRM_AdminMenu', 'payroll_assets'));

		/* Events submenu */
		$event_submenu = add_submenu_page('employee-and-hr-management', __('Events', 'employee-&-hr-management'), __('Events', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management-events', array(
			'WL_EHRM_AdminMenu',
			'events'
		));
		add_action('admin_print_styles-' . $event_submenu, array('WL_EHRM_AdminMenu', 'event_assets'));

		/* Notices submenu */
		$notices_submenu = add_submenu_page('employee-and-hr-management', __('Notices', 'employee-&-hr-management'), __('Notices', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management-notices', array(
			'WL_EHRM_AdminMenu',
			'notices'
		));
		add_action('admin_print_styles-' . $notices_submenu, array('WL_EHRM_AdminMenu', 'dashboard_assets'));

		/* Holidays submenu */
		$holiday_submenu = add_submenu_page('employee-and-hr-management', __('Holidays', 'employee-&-hr-management'), __('Holidays', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management-holidays', array(
			'WL_EHRM_AdminMenu',
			'holidays'
		));
		add_action('admin_print_styles-' . $holiday_submenu, array('WL_EHRM_AdminMenu', 'holiday_assets'));

		/* Projects submenu */
		$holiday_submenu = add_submenu_page('employee-and-hr-management', __('Projects', 'employee-&-hr-management'), __('Projects', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management-projects', array(
			'WL_EHRM_AdminMenu',
			'projects'
		));
		add_action('admin_print_styles-' . $holiday_submenu, array('WL_EHRM_AdminMenu', 'project_assets'));

		/* Notifications submenu */
		$notification_submenu = add_submenu_page('employee-and-hr-management', __('Notifications', 'employee-and-hr-management'), __('Notifications', 'employee-and-hr-management'), 'manage_options', 'employee-and-hr-management-lite-notifications', array(
			'WL_EHRM_AdminMenu',
			'notifications'
		));
		add_action('admin_print_styles-' . $notification_submenu, array('WL_EHRM_AdminMenu', 'notification_assets'));

		/* Settings submenu */
		$settings_submenu = add_submenu_page('employee-and-hr-management', __('Settings', 'employee-&-hr-management'), __('Settings', 'employee-&-hr-management'), 'manage_options', 'employee-and-hr-management-settings', array(
			'WL_EHRM_AdminMenu',
			'settings'
		));
		add_action('admin_print_styles-' . $settings_submenu, array('WL_EHRM_AdminMenu', 'event_assets'));


		/***----------------------------------------------------------Menus for subscriber----------------------------------------------------------***/
		/* Dashboard submenu */
		$save_settings  = get_option('ehrm_settings_data');
		if (!empty($save_settings['user_roles'])) {
			$user_roles = unserialize($save_settings['user_roles']);
		} else {
			$user_roles = array('subscriber');
		}

		$role    = EHRMHelperClass::ehrm_get_current_user_roles();
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$check_1 = 0;

		if (is_array($user_roles)) {
			if (in_array($role, $user_roles) &&  EHRMHelperClass::check_user_availability() == true) {

				/** IP Restriction Check **/
				if ($save_settings['ip_restriction'] == 'Yes') {

					if (isset($save_settings['restrict_ips']) || isset($save_settings['restrict_ips2'])) {

						$rstd_ip  = $save_settings['restrict_ips'];
						$rstd_ip2  = $save_settings['restrict_ips2'];
						
						$ip_parts = explode('.', $rstd_ip);
						$ip_parts2 = explode('.', $rstd_ip2);

						if ($save_settings['ip_rest_type'] == 'single') {
							$rstd_ip = $ip_parts[0] . '.' . $ip_parts[1] . '.' . $ip_parts[2] . '.' . $ip_parts[3];
						} else {
							$rstd_ip = $ip_parts[0] . '.' . $ip_parts[1] . '.' . $ip_parts[2];
						}
						
						if ($save_settings['ip_rest_type'] == 'single') {
							$rstd_ip2 = $ip_parts2[0] . '.' . $ip_parts2[1] . '.' . $ip_parts2[2] . '.' . $ip_parts2[3];
						} else {
							$rstd_ip2 = $ip_parts2[0] . '.' . $ip_parts2[1] . '.' . $ip_parts2[2];
						}
					}

					if (strpos($user_ip, $rstd_ip) === 0) {
						$check_1++;
					} else {
						/* Error submenu */
						$login_error_submenu = add_submenu_page('employee-and-hr-management', __('Unauthorized', 'employee-&-hr-management'), __('Unauthorized', 'employee-&-hr-management'), $role, 'employee-and-hr-management-staff-unauthorized', array(
							'WL_EHRM_AdminMenu',
							'login_error'
						));
						add_action('admin_print_styles-' . $login_error_submenu, array('WL_EHRM_AdminMenu', 'dashboard_assets'));
					}
					
					if (strpos($user_ip, $rstd_ip2) === 0) {
						$check_1++;
					} else {
						/* Error submenu */
						$login_error_submenu = add_submenu_page('employee-and-hr-management', __('Unauthorized', 'employee-&-hr-management'), __('Unauthorized', 'employee-&-hr-management'), $role, 'employee-and-hr-management-staff-unauthorized', array(
							'WL_EHRM_AdminMenu',
							'login_error'
						));
						add_action('admin_print_styles-' . $login_error_submenu, array('WL_EHRM_AdminMenu', 'dashboard_assets'));
					}
				} else {
					$check_1++;
				}
				/** EOD of IP Restriction Check **/

				if ($check_1 != 0) {

					/** Dashboard**/
					$sub_dash_submenu = add_submenu_page('employee-and-hr-management', __('Dashboard', 'employee-&-hr-management'), __('Dashboard', 'employee-&-hr-management'), $role, 'employee-and-hr-management-staff-dashboard', array(
						'WL_EHRM_AdminMenu',
						'staff_dashboard'
					));
					add_action('admin_print_styles-' . $sub_dash_submenu, array('WL_EHRM_AdminMenu', 'staff_dashboard_assets'));

					/* Reports submenu */
					$staff_report_submenu = add_submenu_page('employee-and-hr-management', __('Reports', 'employee-&-hr-management'), __('Reports', 'employee-&-hr-management'), $role, 'employee-and-hr-management-staff-reports', array(
						'WL_EHRM_AdminMenu',
						'staff_reports'
					));
					add_action('admin_print_styles-' . $staff_report_submenu, array('WL_EHRM_AdminMenu', 'report_assets'));

					/* Leave Requests submenu */
					$staff_requests_submenu = add_submenu_page('employee-and-hr-management', __('Leaves', 'employee-&-hr-management'), __('Leaves', 'employee-&-hr-management'), $role, 'employee-and-hr-management-staff-requests', array(
						'WL_EHRM_AdminMenu',
						'staff_requests'
					));
					add_action('admin_print_styles-' . $staff_requests_submenu, array('WL_EHRM_AdminMenu', 'staff_requests_assets'));

					if (!empty($save_settings['show_holiday']) && $save_settings['show_holiday'] == 'Yes') {
						/* Holidays submenu */
						$staff_holidays_submenu = add_submenu_page('employee-and-hr-management', __('Holidays', 'employee-&-hr-management'), __('Holidays', 'employee-&-hr-management'), $role, 'employee-and-hr-management-staff-holidays', array(
							'WL_EHRM_AdminMenu',
							'staff_holidays'
						));
						add_action('admin_print_styles-' . $staff_holidays_submenu, array('WL_EHRM_AdminMenu', 'holiday_assets'));
					}

					if (!empty($save_settings['show_notice']) && $save_settings['show_notice'] == 'Yes') {
						/* Notice submenu */
						$staff_notice_submenu = add_submenu_page('employee-and-hr-management', __('Notice', 'employee-&-hr-management'), __('Notice', 'employee-&-hr-management'), $role, 'employee-and-hr-management-staff-notice', array(
							'WL_EHRM_AdminMenu',
							'staff_notice'
						));
						add_action('admin_print_styles-' . $staff_notice_submenu, array('WL_EHRM_AdminMenu', 'dashboard_assets'));
					}

					if (!empty($save_settings['show_projects']) && $save_settings['show_projects'] == 'Yes') {
						/* Projects submenu */
						$staff_project_submenu = add_submenu_page('employee-and-hr-management', __('Projects', 'employee-&-hr-management'), __('Projects', 'employee-&-hr-management'), $role, 'employee-and-hr-management-staff-project', array(
							'WL_EHRM_AdminMenu',
							'staff_project'
						));
						add_action('admin_print_styles-' . $staff_project_submenu, array('WL_EHRM_AdminMenu', 'project_assets'));
					}
					
					$staff_officein_submenu = add_submenu_page('employee-and-hr-management', __('Office In / Out', 'employee-&-hr-management'), __('Office In / Out', 'employee-&-hr-management'), $role, 'employee-and-hr-management-office-in', array(
						'WL_EHRM_AdminMenu',
						'officein_func'
					));
					add_action('admin_print_styles-' . $staff_officein_submenu, array('WL_EHRM_AdminMenu', 'addlibs_clockin'));
				}
			}
		}
	}
	
	public static function officein_func(){		
		echo do_shortcode('[WL_EHRM_LOGIN_FORM]');
	}

	/* Dashboard menu/submenu callback */
	public static function dashboard() {
		require_once('inc/wl_ehrm_dashboard.php');
	}

	/* Designation menu/submenu callback */
	public static function designation() {
		require_once('inc/administrator/wl_ehrm_designation.php');
	}

	/* Requests menu/submenu callback */
	public static function requests() {
		require_once('inc/administrator/wl_ehrm_requests.php');
	}

	/* Shift menu/submenu callback */
	public static function shift() {
		require_once('inc/administrator/wl_ehrm_shift.php');
	}

	/* Staff menu/submenu callback */
	public static function staff() {
		require_once('inc/administrator/wl_ehrm_staff.php');
	}

	/* Reports menu/submenu callback */
	public static function reports() {
		require_once('inc/administrator/wl_ehrm_reports.php');
	}

	/* Pay roll menu/submenu callback */
	public static function payroll() {
		require_once('inc/administrator/wl_ehrm_payroll.php');
	}

	/* Events menu/submenu callback */
	public static function events() {
		require_once('inc/administrator/wl_ehrm_event.php');
	}

	/* Notices menu/submenu callback */
	public static function notices() {
		require_once('inc/administrator/wl_ehrm_notice.php');
	}

	/* Holidays menu/submenu callback */
	public static function holidays() {
		require_once('inc/administrator/wl_ehrm_holiday.php');
	}

	/* Projects menu/submenu callback */
	public static function projects() {
		require_once('inc/administrator/wl_ehrm_project.php');
	}

	/* Notifications menu/submenu callback */
	public static function notifications() {
		require_once('inc/administrator/wl_ehrm_notification.php');
	}

	/* Settings menu/submenu callback */
	public static function settings() {
		require_once('inc/wl_ehrm_settings.php');
	}

	/* Staff's dashboard */
	public static function staff_dashboard() {
		require_once('inc/subscriber/wl_ehrm_staff_dash.php');
	}

	/* Staff's reports */
	public static function staff_reports() {
		require_once('inc/subscriber/wl_ehrm_staff_report.php');
	}

	/* Staff's requests */
	public static function staff_requests() {
		require_once('inc/subscriber/wl_ehrm_staff_requests.php');
	}

	/* Staff's Holidays */
	public static function staff_holidays() {
		require_once('inc/subscriber/wl_ehrm_staff_holidays.php');
	}

	/* Staff's Notice */
	public static function staff_notice() {
		require_once('inc/subscriber/wl_ehrm_staff_notices.php');
	}

	/* Staff's Notice */
	public static function staff_project() {
		require_once('inc/subscriber/wl_ehrm_staff_projects.php');
	}

	/* Unauthorized user login */
	public static function login_error() {
		require_once('inc/subscriber/wl_ehrm_staff_error.php');
	}

	/* Dashboard menu/submenu assets */
	public static function dashboard_assets() {
		self::enqueue_libraries();
		self::enqueue_datatable_assets();
		self::enqueue_custom_assets();
	}

	/* Event menu/submenu assets */
	public static function event_assets() {
		self::enqueue_libraries();
		self::enqueue_datatable_assets();
		self::enqueue_custom_assets();
		self::enqueue_datetimepicker();
	}

	/* Holiday menu/submenu assets */
	public static function holiday_assets() {
		self::enqueue_libraries();
		self::enqueue_datatable_assets();
		self::enqueue_custom_assets();
		self::enqueue_daterangepicker();
	}

	/* Staff's dashboard assets */
	public static function staff_dashboard_assets() {
		WL_EHRM_AdminMenu::enqueue_libraries();
		WL_EHRM_AdminMenu::enqueue_datatable_assets();
		WL_EHRM_AdminMenu::enqueue_custom_assets();
		WL_EHRM_AdminMenu::staffs_dashboard();
	}

	/* Staff's Requests assets */
	public static function staff_requests_assets() {
		self::enqueue_libraries();
		self::enqueue_datatable_assets();
		self::enqueue_daterangepicker();
		self::enqueue_custom_assets();
	}

	/* Staff's dashboard assets */
	public static function report_assets() {
		self::enqueue_datatable_assets();
		self::enqueue_libraries();
		self::enqueue_datetimepicker();
		self::enqueue_custom_assets();
		self::reports_dashboard();
	}

	/* Projects assets */
	public static function project_assets() {
		self::enqueue_libraries();
		self::enqueue_datatable_assets();
		self::enqueue_datetimepicker();
		self::enqueue_custom_assets();
		self::enqueue_project_assets();
	}

	public static function payroll_assets() {
		self::enqueue_libraries();
		self::enqueue_datatable_assets();
		self::enqueue_datetimepicker();
		self::enqueue_payroll_assets();
	}

	/* Notifications menu/submenu assets */
	public static function notification_assets() {
		self::enqueue_libraries();
		self::enqueue_datatable_assets();
		self::enqueue_custom_assets();
		self::enqueue_notification_assets();
	}

	public static function enqueue_datatable_assets() {
		wp_enqueue_style('jquery-dataTables', WL_EHRM_PLUGIN_URL . '/assets/css/jquery.dataTables.min.css');
		wp_enqueue_style('dataTables-bootstrap4', WL_EHRM_PLUGIN_URL . '/assets/css/dataTables.bootstrap4.min.css');
		wp_enqueue_script('jquery-datatable-js', WL_EHRM_PLUGIN_URL . '/assets/js/jquery.dataTables.min.js');
		wp_enqueue_script('datatable-bootstrap4-js', WL_EHRM_PLUGIN_URL . '/assets/js/dataTables.bootstrap4.min.js');
		wp_enqueue_script('dataTables-buttons-min-js', WL_EHRM_PLUGIN_URL . 'assets/js/dataTables.buttons.min.js');
		wp_enqueue_script('jszip-min-js', WL_EHRM_PLUGIN_URL . 'assets/js/jszip.min.js');
		wp_enqueue_script('pdfmake-min-js', WL_EHRM_PLUGIN_URL . 'assets/js/pdfmake.min.js');
		wp_enqueue_script('vfs_fonts-js', WL_EHRM_PLUGIN_URL . 'assets/js/vfs_fonts.js');
		wp_enqueue_script('buttons-html5-min-js', WL_EHRM_PLUGIN_URL . 'assets/js/buttons.html5.min.js');
		wp_enqueue_script('buttons-print-min-js', WL_EHRM_PLUGIN_URL . 'assets/js/buttons.print.min.js');
		wp_enqueue_script('buttons-colVis-min-js', WL_EHRM_PLUGIN_URL . 'assets/js/buttons.colVis.min.js');
	}

	/* Enqueue third party libraties */
	public static function enqueue_libraries() {

		/* Enqueue styles */
		wp_enqueue_style('wl-ehrm-dashboard', WL_EHRM_PLUGIN_URL . 'assets/css/dashboard-style.css');
		wp_enqueue_style('wl-ehrm-iconfonts', WL_EHRM_PLUGIN_URL . 'assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css');
		wp_enqueue_style('toastr', WL_EHRM_PLUGIN_URL . 'assets/css/toastr.min.css');
		wp_enqueue_style('jquery-confirm', WL_EHRM_PLUGIN_URL . 'admin/css/jquery-confirm.min.css');
		wp_enqueue_style('bootstrap-multiselect', WL_EHRM_PLUGIN_URL . 'assets/css/bootstrap-multiselect.css');
		wp_enqueue_style('buttons-dataTables-min-css', WL_EHRM_PLUGIN_URL . 'assets/css/buttons.dataTables.min.css');

		/* Add the color picker css file */
		wp_enqueue_style('wp-color-picker');

		/* Enqueue Scripts */
		wp_enqueue_script('jquery');
		wp_enqueue_media();
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_script('jquery-form');
		wp_enqueue_script('popper-js', WL_EHRM_PLUGIN_URL . 'assets/js/popper.min.js', array('jquery'), true, true);
		wp_enqueue_script('bootstrap-js', WL_EHRM_PLUGIN_URL . 'assets/js/bootstrap.min.js', array('jquery'), true, true);
		wp_enqueue_script('moment-js', WL_EHRM_PLUGIN_URL . 'assets/js/moment.min.js', array('jquery'), true, true);
		wp_enqueue_script('toastr-js', WL_EHRM_PLUGIN_URL . 'assets/js/toastr.min.js', array('jquery'), true, true);
		wp_enqueue_script('jquery-confirm-js', WL_EHRM_PLUGIN_URL . 'admin/js/jquery-confirm.min.js', array('jquery'), true, true);
		wp_enqueue_script('bootstrap-multiselect-js', WL_EHRM_PLUGIN_URL . 'assets/js/bootstrap-multiselect.js', array('jquery'), true, true);
	}

	public static function staffs_dashboard() {
		wp_enqueue_script('moment-js', WL_EHRM_PLUGIN_URL . 'assets/js/moment.min.js');
		wp_enqueue_script('momenttimezone', WL_EHRM_PLUGIN_URL . 'assets/js/momenttimezone.js');

		/* Staff dash board ajax js */
		wp_enqueue_script('wl-ehrm-staff-ajax-js', WL_EHRM_PLUGIN_URL . 'admin/js/wl-ehrm-staff-ajax.js');
		wp_localize_script('wl-ehrm-staff-ajax-js', 'ajax_staff', array(
			'ajax_url'      => admin_url('admin-ajax.php'),
			'staff_nonce'   => wp_create_nonce('staff_ajax_nonce'),
			'ehrm_timezone' => EHRMHelperClass::get_setting_timezone(),
		));	
	}

	public static function reports_dashboard() {

		/* Staff dash board ajax js */
		wp_enqueue_script('wl-ehrm-report-ajax-js', WL_EHRM_PLUGIN_URL . 'admin/js/wl-ehrm-report-ajax.js', array('jquery'), true, true);
		wp_localize_script('wl-ehrm-report-ajax-js', 'ajax_report', array(
			'ajax_url'      => admin_url('admin-ajax.php'),
			'report_nonce'  => wp_create_nonce('report_ajax_nonce'),
		));
	}

	public static function enqueue_daterangepicker() {
		wp_enqueue_style('daterangepicker', WL_EHRM_PLUGIN_URL . 'assets/css/daterangepicker.css');
		wp_enqueue_script('daterangepicker-js', WL_EHRM_PLUGIN_URL . 'assets/js/daterangepicker.min.js', array('jquery'), true, true);
		wp_enqueue_script('wl-ehrm-holiday-js', WL_EHRM_PLUGIN_URL . 'admin/js/wl-ehrm-holiday.js', array('jquery'), true, true);
	}

	public static function enqueue_datetimepicker() {
		wp_enqueue_style('datetimepicker', WL_EHRM_PLUGIN_URL . 'assets/css/tempusdominus-bootstrap-4.min.css');
		wp_enqueue_style('font-awesome', WL_EHRM_PLUGIN_URL . 'assets/css/font-awesome.min.css');
		wp_enqueue_script('datetimepicker-js', WL_EHRM_PLUGIN_URL . 'assets/js/tempusdominus-bootstrap-4.min.js', array('jquery'), true, true);
		wp_enqueue_script('wl-ehrm-event-js', WL_EHRM_PLUGIN_URL . 'admin/js/wl-ehrm-event.js', array('jquery'), true, true);
	}

	public static function enqueue_project_assets() {
		wp_enqueue_media();

		/** For CKEDITOR **/
		wp_enqueue_script('moment-js', WL_EHRM_PLUGIN_URL . '/assets/js/moment.min.js', array('jquery'), true, true);
		wp_enqueue_script('ckeditor-js', 'https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js', array('jquery'));

		/** For Multi tags field **/
		wp_enqueue_style('bootstrap-tokenfield', WL_EHRM_PLUGIN_URL . '/assets/css/bootstrap-tokenfield.min.css');
		wp_enqueue_script('bootstrap-tokenfiled-js', WL_EHRM_PLUGIN_URL . '/assets/js/bootstrap-tokenfield.min.js', array('jquery'), true, true);

		/* Project ajax js */
		wp_enqueue_script('wl-ehrm-project-ajax-js', WL_EHRM_PLUGIN_URL . 'admin/js/wl-ehrm-project-ajax.js', array('jquery'), true, true);
		wp_localize_script('wl-ehrm-project-ajax-js', 'ajax_project', array(
			'ajax_url'       => admin_url('admin-ajax.php'),
			'project_nonce'  => wp_create_nonce('project_ajax_nonce'),
		));
	}

	public static function enqueue_payroll_assets() {

		/* Enqueue styles */
		wp_enqueue_style('wl-ehrm-style', WL_EHRM_PLUGIN_URL . 'admin/css/wl-ehrm-backend-style.css');

		/* Pay roll ajax js */
		wp_enqueue_script('wl-ehrm-payroll-ajax-js', WL_EHRM_PLUGIN_URL . 'admin/js/wl-ehrm-payroll-ajax.js', array('jquery'), true, true);
		wp_localize_script('wl-ehrm-payroll-ajax-js', 'ajax_payroll', array(
			'ajax_url'       => admin_url('admin-ajax.php'),
			'payroll_nonce'  => wp_create_nonce('payroll_ajax_nonce'),
		));
	}

	public static function enqueue_notification_assets() {
		/* Enqueue scripts */
		wp_enqueue_script('wl-ehrm-notification-js', WL_EHRM_PLUGIN_URL . 'admin/js/wl-ehrm-notification.js', array('jquery'), true, true);
		wp_localize_script('wl-ehrm-notification-js', 'ajax_notification', array(
			'ajax_url'           => admin_url('admin-ajax.php'),
			'notification_nonce' => wp_create_nonce('notification_ajax_nonce'),
		));
	}
	
	public static function addlibs_clockin(){
		wp_enqueue_script('wl-ehrm-ajax-js', WL_EHRM_PLUGIN_URL . 'admin/js/wl-ehrm-ajax.js', array('jquery'), true, true);
	/* Enqueue styles */
		wp_enqueue_style( 'wl-ehrm-bootstrap-custom', WL_EHRM_PLUGIN_URL . 'public/css/custom-bootstrap.css' );
		wp_enqueue_style( 'toastr', WL_EHRM_PLUGIN_URL . 'assets/css/toastr.min.css' );
        wp_enqueue_style( 'wl-ehrm-front-end', WL_EHRM_PLUGIN_URL . 'public/css/front_end_css.css' );
        
		/* Enqueue scripts */
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'popper-js', WL_EHRM_PLUGIN_URL . 'assets/js/popper.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'bootstrap-js', WL_EHRM_PLUGIN_URL . 'assets/js/bootstrap.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'toastr-js', WL_EHRM_PLUGIN_URL . 'assets/js/toastr.min.js', array( 'jquery' ), true, true );
        
        /* Staff dash board ajax js */
		wp_enqueue_script( 'wl-ehrm-login-ajax-js', WL_EHRM_PLUGIN_URL . 'public/js/wl-ehrm-login-ajax.js', array( 'jquery' ), true, true );
		wp_localize_script( 'wl-ehrm-login-ajax-js', 'ajax_login', array(
			'ajax_url'      => admin_url( 'admin-ajax.php' ),
			'login_nonce'   => wp_create_nonce( 'login_ajax_nonce' ),
			'ehrm_timezone' => EHRMHelperClass::get_setting_timezone(),
		));

	}

	/* Enqueue custom assets */
	public static function enqueue_custom_assets() {

		/* Enqueue styles */
		wp_enqueue_style('wl-ehrm-style', WL_EHRM_PLUGIN_URL . 'admin/css/wl-ehrm-backend-style.css');

		$server_ip = $_SERVER['REMOTE_ADDR'];

		/* Enqueue scripts */
		wp_enqueue_script('wl-ehrm-settings-js', WL_EHRM_PLUGIN_URL . 'admin/js/wl-ehrm-settings.js', array('jquery'), true, true);
		wp_enqueue_script('wl-ehrm-backend-js', WL_EHRM_PLUGIN_URL . 'admin/js/wl-ehrm-backend.js', array('jquery', 'wp-color-picker'), true, true);
		wp_enqueue_script('wl-ehrm-ajax-js', WL_EHRM_PLUGIN_URL . 'admin/js/wl-ehrm-ajax.js', array('jquery'), true, true);
		wp_localize_script('wl-ehrm-ajax-js', 'ajax_backend', array(
			'ajax_url'      => admin_url('admin-ajax.php'),
			'backend_nonce' => wp_create_nonce('backend_ajax_nonce'),
			'restrict_ip'   => $server_ip
		));

		$role = EHRMHelperClass::ehrm_get_current_user_roles();
		if (is_admin() && $role == 'administrator') {
			/** Staff Login/Logout action from Admin Dashboard **/
			wp_enqueue_script('wl-ehrm-admin-ajax-js', WL_EHRM_PLUGIN_URL . 'admin/js/wl-ehrm-admin-dashboard.js', array('jquery'), true, true);
			wp_localize_script('wl-ehrm-admin-ajax-js', 'ajax_admin', array(
				'ajax_url'      => admin_url('admin-ajax.php'),
				'admin_nonce'   => wp_create_nonce('admin_ajax_nonce'),
			));
		}
	}
}
