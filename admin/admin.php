<?php
defined( 'ABSPATH' ) or die();

require_once( 'WL_EHRM_MENU.php' );

/** Administrator **/
require_once( 'inc/controllers/wl-ehrm-designation-actions.php' );
require_once( 'inc/controllers/wl-ehrm-events-actions.php' );
require_once( 'inc/controllers/wl-ehrm-holiday-actions.php' );
require_once( 'inc/controllers/wl-ehrm-notice-actions.php' );
require_once( 'inc/controllers/wl-ehrm-shift-actions.php' );
require_once( 'inc/controllers/wl-ehrm-staff-actions.php' );
require_once( 'inc/controllers/wl-ehrm-reports-actions.php' );
require_once( 'inc/controllers/wl-ehrm-pay-roll-action.php' );
require_once( 'inc/controllers/wl-ehrm-requests-actions.php' );
require_once( 'inc/controllers/wl-ehrm-projects-actions.php' );
require_once( 'inc/controllers/wl-ehrm-admin-dash-actions.php' );
require_once( 'inc/controllers/wl-ehrm-settings.php' );
require_once( 'inc/controllers/wl-ehrm-notification-actions.php' );

/** Staff Members **/
require_once( 'inc/controllers/wl-ehrm-staff-dash-actions.php' );

/** After Staff Login action calls **/
//require_once( 'inc/controllers/wl-ehrm-after-staff-login.php' );

require_once( 'inc/export/wl_ehrm_holiday.php' );
require_once( 'inc/import/wl_ehrm_holiday.php' );
require_once( 'inc/export/wl_ehrm_events.php' );
require_once( 'inc/import/wl_ehrm_events.php' );
require_once( 'inc/import/wl_ehrm_reports.php' );
require_once( 'inc/export/wl_ehrm_reports.php' );

add_option( 'ehrm_designations_data' );
add_option( 'ehrm_events_data' );
add_option( 'ehrm_holidays_data' );
add_option( 'ehrm_notices_data' );
add_option( 'ehrm_shifts_data' );
add_option( 'ehrm_staffs_data' );
add_option( 'ehrm_projects_data' );
add_option( 'ehrm_settings_data' );
add_option( 'ehrm_requests_data' );
add_option( 'ehrm_staff_attendence_data' );
add_option( 'ehrm_breakpoints' );
add_option( 'ehrm_departments_data' );
add_option( 'ehrm_notification_api' );

/** Default email template data **/
add_option( 'ehrm_email_employee_welcome' );
add_option( 'ehrm_email_new_leave_request' );
add_option( 'ehrm_email_approved_leave_request' );
add_option( 'ehrm_email_rejected_leave_request' );
add_option( 'ehrm_email_new_project_assigned' );
add_option( 'ehrm_email_new_task_assigned' );
add_option( 'ehrm_email_new_comment_assigned' );
add_option( 'ehrm_email_new_contact_assigned' );
add_option( 'ehrm_email_new_notice_assigned' );

/** Default sms template data **/
add_option( 'ehrm_sms_new_leave_request' );
add_option( 'ehrm_sms_approved_leave_request' );
add_option( 'ehrm_sms_rejected_leave_request' );
add_option( 'ehrm_sms_new_project_assigned' );
add_option( 'ehrm_sms_new_task_assigned' );
add_option( 'ehrm_sms_new_comment_assigned' );
add_option( 'ehrm_sms_new_notice_assigned' );

require_once WL_EHRM_PLUGIN_DIR_PATH . '/admin/core/WL_EHRM_LM.php';

$wl_ehrm_lm = WL_EHRM_LM::get_instance();
$wl_ehrm_lm_val = $wl_ehrm_lm->is_valid();
if ( ( isset( $wl_ehrm_lm_val ) && $wl_ehrm_lm_val ) ) {
	/* Action for creating Staff Time Manager Pro menu pages */
	add_action( 'admin_menu', array( 'WL_EHRM_AdminMenu', 'create_menu' ) );
}

/* On admin init */
add_action( 'wp_ajax_wl-ehrm-settings', array( 'EHRMSaveSettings', 'save_settings' ) );

/**-------------------------------------------------------------Designations-------------------------------------------------------------**/

/* Add Designations */
add_action( 'wp_ajax_nopriv_ehrm_add_department_action', array( 'DesignationsAjaxAction', 'add_department' ) );
add_action( 'wp_ajax_ehrm_add_department_action', array( 'DesignationsAjaxAction', 'add_department' ) );

/* Add Designations */
add_action( 'wp_ajax_nopriv_ehrm_add_designation_action', array( 'DesignationsAjaxAction', 'add_designations' ) );
add_action( 'wp_ajax_ehrm_add_designation_action', array( 'DesignationsAjaxAction', 'add_designations' ) );

/* Edit Designations */
add_action( 'wp_ajax_nopriv_ehrm_edit_designation_action', array( 'DesignationsAjaxAction', 'edit_designations' ) );
add_action( 'wp_ajax_ehrm_edit_designation_action', array( 'DesignationsAjaxAction', 'edit_designations' ) );

/* Update Designations */
add_action( 'wp_ajax_nopriv_ehrm_update_designation_action', array( 'DesignationsAjaxAction', 'update_designations' ) );
add_action( 'wp_ajax_ehrm_update_designation_action', array( 'DesignationsAjaxAction', 'update_designations' ) );

/* Delete Designations */
add_action( 'wp_ajax_nopriv_ehrm_delete_designation_action', array( 'DesignationsAjaxAction', 'delete_designations' ) );
add_action( 'wp_ajax_ehrm_delete_designation_action', array( 'DesignationsAjaxAction', 'delete_designations' ) );

/**----------------------------------------------------------------Events----------------------------------------------------------------**/

/* Add Events */
add_action( 'wp_ajax_nopriv_ehrm_add_event_action', array( 'EventsAjaxAction', 'add_events' ) );
add_action( 'wp_ajax_ehrm_add_event_action', array( 'EventsAjaxAction', 'add_events' ) );

/* Edit Events */
add_action( 'wp_ajax_nopriv_ehrm_edit_event_action', array( 'EventsAjaxAction', 'edit_events' ) );
add_action( 'wp_ajax_ehrm_edit_event_action', array( 'EventsAjaxAction', 'edit_events' ) );

/* Update Events */
add_action( 'wp_ajax_nopriv_ehrm_update_event_action', array( 'EventsAjaxAction', 'update_events' ) );
add_action( 'wp_ajax_ehrm_update_event_action', array( 'EventsAjaxAction', 'update_events' ) );

/* Delete Events */
add_action( 'wp_ajax_nopriv_ehrm_delete_event_action', array( 'EventsAjaxAction', 'delete_events' ) );
add_action( 'wp_ajax_ehrm_delete_event_action', array( 'EventsAjaxAction', 'delete_events' ) );

/**----------------------------------------------------------------Holidayss----------------------------------------------------------------**/

/* Add Holidays */
add_action( 'wp_ajax_nopriv_ehrm_add_holiday_action', array( 'HolidaysAjaxAction', 'add_holidays' ) );
add_action( 'wp_ajax_ehrm_add_holiday_action', array( 'HolidaysAjaxAction', 'add_holidays' ) );

/* Edit Holidays */
add_action( 'wp_ajax_nopriv_ehrm_edit_holiday_action', array( 'HolidaysAjaxAction', 'edit_holidays' ) );
add_action( 'wp_ajax_ehrm_edit_holiday_action', array( 'HolidaysAjaxAction', 'edit_holidays' ) );

/* Update Holidays */
add_action( 'wp_ajax_nopriv_ehrm_update_holiday_action', array( 'HolidaysAjaxAction', 'update_holidays' ) );
add_action( 'wp_ajax_ehrm_update_holiday_action', array( 'HolidaysAjaxAction', 'update_holidays' ) );

/* Delete Holidays */
add_action( 'wp_ajax_nopriv_ehrm_delete_holiday_action', array( 'HolidaysAjaxAction', 'delete_holidays' ) );
add_action( 'wp_ajax_ehrm_delete_holiday_action', array( 'HolidaysAjaxAction', 'delete_holidays' ) );

/**----------------------------------------------------------------Notice----------------------------------------------------------------**/

/* Add Notices */
add_action( 'wp_ajax_nopriv_ehrm_add_notice_action', array( 'NoticeAjaxAction', 'add_notices' ) );
add_action( 'wp_ajax_ehrm_add_notice_action', array( 'NoticeAjaxAction', 'add_notices' ) );

/* Edit Notices */
add_action( 'wp_ajax_nopriv_ehrm_edit_notice_action', array( 'NoticeAjaxAction', 'edit_notices' ) );
add_action( 'wp_ajax_ehrm_edit_notice_action', array( 'NoticeAjaxAction', 'edit_notices' ) );

/* Update Notices */
add_action( 'wp_ajax_nopriv_ehrm_update_notice_action', array( 'NoticeAjaxAction', 'update_notices' ) );
add_action( 'wp_ajax_ehrm_update_notice_action', array( 'NoticeAjaxAction', 'update_notices' ) );

/* Delete Notices */
add_action( 'wp_ajax_nopriv_ehrm_delete_notice_action', array( 'NoticeAjaxAction', 'delete_notices' ) );
add_action( 'wp_ajax_ehrm_delete_notice_action', array( 'NoticeAjaxAction', 'delete_notices' ) );

/**----------------------------------------------------------------Shifts----------------------------------------------------------------**/

/* Add Shifts */
add_action( 'wp_ajax_nopriv_ehrm_add_shift_action', array( 'ShiftAjaxActions', 'add_shift' ) );
add_action( 'wp_ajax_ehrm_add_shift_action', array( 'ShiftAjaxActions', 'add_shift' ) );

/* Edit Shifts */
add_action( 'wp_ajax_nopriv_ehrm_edit_shift_action', array( 'ShiftAjaxActions', 'edit_shift' ) );
add_action( 'wp_ajax_ehrm_edit_shift_action', array( 'ShiftAjaxActions', 'edit_shift' ) );

/* Update Shifts */
add_action( 'wp_ajax_nopriv_ehrm_update_shift_action', array( 'ShiftAjaxActions', 'update_shift' ) );
add_action( 'wp_ajax_ehrm_update_shift_action', array( 'ShiftAjaxActions', 'update_shift' ) );

/* Delete Shifts */
add_action( 'wp_ajax_nopriv_ehrm_delete_shift_action', array( 'ShiftAjaxActions', 'delete_shift' ) );
add_action( 'wp_ajax_ehrm_delete_shift_action', array( 'ShiftAjaxActions', 'delete_shift' ) );

/**----------------------------------------------------------------Staff----------------------------------------------------------------**/

/* Fetch user's data */
add_action( 'wp_ajax_nopriv_ehrm_fetch_staff_action', array( 'StaffAjaxActions', 'fetch_userdata' ) );
add_action( 'wp_ajax_ehrm_fetch_staff_action', array( 'StaffAjaxActions', 'fetch_userdata' ) );

/* Add Staff */
add_action( 'wp_ajax_nopriv_ehrm_add_staff_action', array( 'StaffAjaxActions', 'add_staff' ) );
add_action( 'wp_ajax_ehrm_add_staff_action', array( 'StaffAjaxActions', 'add_staff' ) );

/* Edit Staff */
add_action( 'wp_ajax_nopriv_ehrm_edit_staff_action', array( 'StaffAjaxActions', 'edit_staff' ) );
add_action( 'wp_ajax_ehrm_edit_staff_action', array( 'StaffAjaxActions', 'edit_staff' ) );

/* Update Staff */
add_action( 'wp_ajax_nopriv_ehrm_update_staff_action', array( 'StaffAjaxActions', 'update_staff' ) );
add_action( 'wp_ajax_ehrm_update_staff_action', array( 'StaffAjaxActions', 'update_staff' ) );

/* Delete Staff */
add_action( 'wp_ajax_nopriv_ehrm_delete_staff_action', array( 'StaffAjaxActions', 'delete_staff' ) );
add_action( 'wp_ajax_ehrm_delete_staff_action', array( 'StaffAjaxActions', 'delete_staff' ) );

/**----------------------------------------------------------------Requests----------------------------------------------------------------**/

/* Add Requests */
add_action( 'wp_ajax_nopriv_ehrm_add_req_action', array( 'RequestsAjaxAction', 'add_requests' ) );
add_action( 'wp_ajax_ehrm_add_req_action', array( 'RequestsAjaxAction', 'add_requests' ) );

/* Edit Requests */
add_action( 'wp_ajax_nopriv_ehrm_edit_req_action', array( 'RequestsAjaxAction', 'edit_requests' ) );
add_action( 'wp_ajax_ehrm_edit_req_action', array( 'RequestsAjaxAction', 'edit_requests' ) );

/* Update Requests */
add_action( 'wp_ajax_nopriv_ehrm_update_req_action', array( 'RequestsAjaxAction', 'update_requests' ) );
add_action( 'wp_ajax_ehrm_update_req_action', array( 'RequestsAjaxAction', 'update_requests' ) );

/* Delete Requests */
add_action( 'wp_ajax_nopriv_ehrm_delete_req_action', array( 'RequestsAjaxAction', 'delete_requests' ) );
add_action( 'wp_ajax_ehrm_delete_req_action', array( 'RequestsAjaxAction', 'delete_requests' ) );

/**----------------------------------------------------------------Reports----------------------------------------------------------------**/

/* Generate report */
add_action( 'wp_ajax_nopriv_ehrm_get_reports_action', array( 'ReportAjaxAction', 'get_reports' ) );
add_action( 'wp_ajax_ehrm_get_reports_action', array( 'ReportAjaxAction', 'get_reports' ) );

/* Calculate salary */
add_action( 'wp_ajax_nopriv_ehrm_show_salary_action', array( 'ReportAjaxAction', 'display_salary' ) );
add_action( 'wp_ajax_ehrm_show_salary_action', array( 'ReportAjaxAction', 'display_salary' ) );

/* Edit report */
add_action( 'wp_ajax_nopriv_ehrm_edit_report_action', array( 'ReportAjaxAction', 'edit_reports' ) );
add_action( 'wp_ajax_ehrm_edit_report_action', array( 'ReportAjaxAction', 'edit_reports' ) );

/* Update report */
add_action( 'wp_ajax_nopriv_ehrm_update_report_action', array( 'ReportAjaxAction', 'update_reports' ) );
add_action( 'wp_ajax_ehrm_update_report_action', array( 'ReportAjaxAction', 'update_reports' ) );

/* Display export data */
add_action( 'wp_ajax_nopriv_ehrm_view_export_data', array( 'ReportAjaxAction', 'generate_export_report' ) );
add_action( 'wp_ajax_ehrm_view_export_data', array( 'ReportAjaxAction', 'generate_export_report' ) );

/* Download report */
add_action( 'admin_init', array( 'ReportAjaxAction', 'download_reports' ) );

/**----------------------------------------------------------------Staff Dashboard----------------------------------------------------------------**/

/* Staff's clock actions */
add_action( 'wp_ajax_nopriv_ehrm_clock_action', array( 'StaffDashBoardAction', 'clock_actions' ) );
add_action( 'wp_ajax_ehrm_clock_action', array( 'StaffDashBoardAction', 'clock_actions' ) );

/* Late reson submit actions */
add_action( 'wp_ajax_nopriv_ehrm_late_reson_action', array( 'StaffDashBoardAction', 'late_reson_submit' ) );
add_action( 'wp_ajax_ehrm_late_reson_action', array( 'StaffDashBoardAction', 'late_reson_submit' ) );

/* Daily report submit actions */
add_action( 'wp_ajax_nopriv_ehrm_daily_report_action', array( 'StaffDashBoardAction', 'staff_daily_report' ) );
add_action( 'wp_ajax_ehrm_daily_report_action', array( 'StaffDashBoardAction', 'staff_daily_report' ) );

add_action( 'wp_ajax_nopriv_ehrm_staff_break_action', array( 'StaffDashBoardAction', 'staff_break' ) );
add_action( 'wp_ajax_ehrm_staff_break_action', array( 'StaffDashBoardAction', 'staff_break' ) );

add_action( 'wp_ajax_nopriv_ehrm_add_breakout_action', array( 'StaffDashBoardAction', 'staff_breakout' ) );
add_action( 'wp_ajax_ehrm_add_breakout_action', array( 'StaffDashBoardAction', 'staff_breakout' ) );



/**----------------------------------------------------------------Admin Login Dashboard----------------------------------------------------------------**/

/* Staff's clock actions */
add_action( 'wp_ajax_nopriv_ehrm_login_dash_action', array( 'AdminDashBoardAction', 'clock_actions' ) );
add_action( 'wp_ajax_ehrm_login_dash_action', array( 'AdminDashBoardAction', 'clock_actions' ) );

/**----------------------------------------------------------------Projects and Tasks----------------------------------------------------------------**/

/* Project Add actions */
add_action( 'wp_ajax_nopriv_ehrm_add_project_ajax', array( 'ProjectAjaxAction', 'add_projects' ) );
add_action( 'wp_ajax_ehrm_add_project_ajax', array( 'ProjectAjaxAction', 'add_projects' ) );

/* Project Edit actions */
add_action( 'wp_ajax_nopriv_ehrm_edit_project_ajax', array( 'ProjectAjaxAction', 'edit_projects' ) );
add_action( 'wp_ajax_ehrm_edit_project_ajax', array( 'ProjectAjaxAction', 'edit_projects' ) );

/* Project Update actions */
add_action( 'wp_ajax_nopriv_ehrm_update_project_ajax', array( 'ProjectAjaxAction', 'update_projects' ) );
add_action( 'wp_ajax_ehrm_update_project_ajax', array( 'ProjectAjaxAction', 'update_projects' ) );

/* Project Delete actions */
add_action( 'wp_ajax_nopriv_ehrm_delete_project_ajax', array( 'ProjectAjaxAction', 'delete_projects' ) );
add_action( 'wp_ajax_ehrm_delete_project_ajax', array( 'ProjectAjaxAction', 'delete_projects' ) );

/* View all tasks actions */
add_action( 'wp_ajax_nopriv_ehrm_view_all_tasks_ajax', array( 'ProjectAjaxAction', 'view_all_tasks' ) );
add_action( 'wp_ajax_ehrm_view_all_tasks_ajax', array( 'ProjectAjaxAction', 'view_all_tasks' ) );

/* Add tasks actions */
add_action( 'wp_ajax_nopriv_ehrm_add_task_ajax', array( 'ProjectAjaxAction', 'add_tasks' ) );
add_action( 'wp_ajax_ehrm_add_task_ajax', array( 'ProjectAjaxAction', 'add_tasks' ) );

/* Edit tasks actions */
add_action( 'wp_ajax_nopriv_ehrm_edit_task_ajax', array( 'ProjectAjaxAction', 'edit_tasks' ) );
add_action( 'wp_ajax_ehrm_edit_task_ajax', array( 'ProjectAjaxAction', 'edit_tasks' ) );

/* Update tasks actions */
add_action( 'wp_ajax_nopriv_ehrm_update_task_ajax', array( 'ProjectAjaxAction', 'update_tasks' ) );
add_action( 'wp_ajax_ehrm_update_task_ajax', array( 'ProjectAjaxAction', 'update_tasks' ) );

/* Delete tasks actions */
add_action( 'wp_ajax_nopriv_ehrm_delete_task_ajax', array( 'ProjectAjaxAction', 'delete_tasks' ) );
add_action( 'wp_ajax_ehrm_delete_task_ajax', array( 'ProjectAjaxAction', 'delete_tasks' ) );

/* View task details */
add_action( 'wp_ajax_nopriv_ehrm_view_task_ajax', array( 'ProjectAjaxAction', 'view_task_details' ) );
add_action( 'wp_ajax_ehrm_view_task_ajax', array( 'ProjectAjaxAction', 'view_task_details' ) );

/* Add Comment details */
add_action( 'wp_ajax_nopriv_ehrm_add_comment_ajax', array( 'ProjectAjaxAction', 'add_comments' ) );
add_action( 'wp_ajax_ehrm_add_comment_ajax', array( 'ProjectAjaxAction', 'add_comments' ) );

/* Edit Comment details */
add_action( 'wp_ajax_nopriv_ehrm_edit_comment_ajax', array( 'ProjectAjaxAction', 'edit_comments' ) );
add_action( 'wp_ajax_ehrm_edit_comment_ajax', array( 'ProjectAjaxAction', 'edit_comments' ) );

/* Update Comment details */
add_action( 'wp_ajax_nopriv_ehrm_update_comment_ajax', array( 'ProjectAjaxAction', 'update_comments' ) );
add_action( 'wp_ajax_ehrm_update_comment_ajax', array( 'ProjectAjaxAction', 'update_comments' ) );

/* Delete Comment details */
add_action( 'wp_ajax_nopriv_ehrm_delete_comment_ajax', array( 'ProjectAjaxAction', 'delete_comments' ) );
add_action( 'wp_ajax_ehrm_delete_comment_ajax', array( 'ProjectAjaxAction', 'delete_comments' ) );

/**----------------------------------------------------------------Pay Roll----------------------------------------------------------------**/

/* Generate pay roll action */
add_action( 'wp_ajax_nopriv_ehrm_payroll_action', array( 'PayRollAjaxAction', 'generate_pay_roll' ) );
add_action( 'wp_ajax_ehrm_payroll_action', array( 'PayRollAjaxAction', 'generate_pay_roll' ) );

/**----------------------------------------------------------------Import/Export----------------------------------------------------------------**/

/** Export/Import Holidays **/
add_action( 'admin_init', array( 'HolidayExportData', 'export_data' ) );
add_action( 'admin_init', array( 'HolidayImportData', 'import_data' ) );

/** Export/Import Events **/
add_action( 'admin_init', array( 'EventsExportData', 'export_data' ) );
add_action( 'admin_init', array( 'EventsImportData', 'import_data' ) );

/** Import Reports **/
add_action( 'admin_init', array( 'ReportsImportData', 'import_data' ) );
add_action( 'admin_init', array( 'ReportsExportData', 'export_data' ) );

/**----------------------------------------------------------------Email notifications----------------------------------------------------------------**/

/* Save options */
add_action( 'wp_ajax_nopriv_ehrm_email_options_ajax', array( 'NotificationsAjaxAction', 'save_options' ) );
add_action( 'wp_ajax_ehrm_email_options_ajax', array( 'NotificationsAjaxAction', 'save_options' ) );

/* Fetch options */
add_action( 'wp_ajax_nopriv_ehrm_email_options_data', array( 'NotificationsAjaxAction', 'show_email_template_data' ) );
add_action( 'wp_ajax_ehrm_email_options_data', array( 'NotificationsAjaxAction', 'show_email_template_data' ) );

/* Save data */
add_action( 'wp_ajax_nopriv_ehrm_save_email_options_ajax', array( 'NotificationsAjaxAction', 'save_email_template_data' ) );
add_action( 'wp_ajax_ehrm_save_email_options_ajax', array( 'NotificationsAjaxAction', 'save_email_template_data' ) );

/* Save data */
add_action( 'wp_ajax_nopriv_ehrm_save_noti_api_ajax', array( 'NotificationsAjaxAction', 'save_notification_api_data' ) );
add_action( 'wp_ajax_ehrm_save_noti_api_ajax', array( 'NotificationsAjaxAction', 'save_notification_api_data' ) );

/* Fetch options */
add_action( 'wp_ajax_nopriv_ehrm_sms_options_data', array( 'NotificationsAjaxAction', 'show_sms_template_data' ) );
add_action( 'wp_ajax_ehrm_sms_options_data', array( 'NotificationsAjaxAction', 'show_sms_template_data' ) );

/* Save data */
add_action( 'wp_ajax_nopriv_ehrm_save_sms_options_ajax', array( 'NotificationsAjaxAction', 'save_sms_template_data' ) );
add_action( 'wp_ajax_ehrm_save_sms_options_ajax', array( 'NotificationsAjaxAction', 'save_sms_template_data' ) );

$api_data = get_option( 'ehrm_notification_api' );
if ( ! empty ( $api_data ) && ! empty ( $api_data['email_optin'] ) && $api_data['email_optin'] == 'smtp' ) {
	if ( ! empty ( $api_data['email_from'] ) && ! empty ( $api_data['smtp_hostname'] ) && ! empty ( $api_data['smtp_port'] ) && ! empty ( $api_data['smtp_encription'] ) && ! empty ( $api_data['smtp_user'] ) && ! empty ( $api_data['smtp_passwd'] ) ) {
		add_action( 'phpmailer_init', array( 'NotificationsAjaxAction', 'set_phpmailer_details' ) );
	}
}
