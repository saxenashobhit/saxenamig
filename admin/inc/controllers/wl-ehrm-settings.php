<?php
defined( 'ABSPATH' ) or die();

class EHRMSaveSettings {
	/* Register settings */
	public static function save_settings() {
		if ( ! wp_verify_nonce( $_REQUEST['wl_ehrm_setting_options'], 'wl_ehrm_save_settings' ) ) {
			die();
		}

		$timezone         = isset( $_POST['timezone'] ) ? sanitize_text_field( $_POST['timezone'] ) : 'Asia/Kolkata';
		$date_format      = isset( $_POST['date_format'] ) ? sanitize_text_field( $_POST['date_format'] ) : 'F j Y';
		$time_format      = isset( $_POST['time_format'] ) ? sanitize_text_field( $_POST['time_format'] ) : 'g:i A';
		$monday_status    = isset( $_POST['monday_status'] ) ? sanitize_text_field( $_POST['monday_status'] ) : 'Working';
		$tuesday_status   = isset( $_POST['tuesday_status'] ) ? sanitize_text_field( $_POST['tuesday_status'] ) : 'Working';
		$wednesday_status = isset( $_POST['wednesday_status'] ) ? sanitize_text_field( $_POST['wednesday_status'] ) : 'Working';
		$thursday_status  = isset( $_POST['thursday_status'] ) ? sanitize_text_field( $_POST['thursday_status'] ) : 'Working';
		$friday_status    = isset( $_POST['friday_status'] ) ? sanitize_text_field( $_POST['friday_status'] ) : 'Working';
		$saturday_status  = isset( $_POST['saturday_status'] ) ? sanitize_text_field( $_POST['saturday_status'] ) : 'Working';
		$sunday_status    = isset( $_POST['sunday_status'] ) ? sanitize_text_field( $_POST['sunday_status'] ) : 'Off';
		$halfday_start    = isset( $_POST['halfday_start'] ) ? sanitize_text_field( $_POST['halfday_start'] ) : '';
		$halfday_end      = isset( $_POST['halfday_end'] ) ? sanitize_text_field( $_POST['halfday_end'] ) : '';
		$lunch_start      = isset( $_POST['lunch_start'] ) ? sanitize_text_field( $_POST['lunch_start'] ) : '';
		$lunch_end        = isset( $_POST['lunch_end'] ) ? sanitize_text_field( $_POST['lunch_end'] ) : '';
		$cur_symbol       = isset( $_POST['currency_symbol'] ) ? sanitize_text_field( $_POST['currency_symbol'] ) : '₹';
		$ehrm_gmap_api    = isset( $_POST['ehrm_gmap_api'] ) ? sanitize_text_field( $_POST['ehrm_gmap_api'] ) : '';
		$cur_position     = isset( $_POST['currency_position'] ) ? sanitize_text_field( $_POST['currency_position'] ) : 'Right';
		$salary_method    = isset( $_POST['salary_method'] ) ? sanitize_text_field( $_POST['salary_method'] ) : 'Monthly';
		$lunchtime        = isset( $_POST['lunch_time_status'] ) ? sanitize_text_field( $_POST['lunch_time_status'] ) : 'Include';
		$shoot_mail       = isset( $_POST['shoot_mail'] ) ? sanitize_text_field( $_POST['shoot_mail'] ) : 'Yes';
		$ip_restriction   = isset( $_POST['ip_restriction'] ) ? sanitize_text_field( $_POST['ip_restriction'] ) : 'No';
		$ip_rest_type     = isset( $_POST['ip_rest_type'] ) ? sanitize_text_field( $_POST['ip_rest_type'] ) : 'single';
		$restrict_ips     = isset( $_POST['restrict_ips'] ) ? sanitize_text_field( $_POST['restrict_ips'] ) : '';
		$restrict_ips2    = isset( $_POST['restrict_ips2'] ) ? sanitize_text_field( $_POST['restrict_ips2'] ) : '';
		$show_holiday     = isset( $_POST['show_holiday'] ) ? sanitize_text_field( $_POST['show_holiday'] ) : 'Yes';
		$show_report      = isset( $_POST['report_submission'] ) ? sanitize_text_field( $_POST['report_submission'] ) : 'Yes';
		$show_notice      = isset( $_POST['show_notice'] ) ? sanitize_text_field( $_POST['show_notice'] ) : 'Yes';
		$late_reson       = isset( $_POST['late_reson'] ) ? sanitize_text_field( $_POST['late_reson'] ) : 'Yes';
		$salary_status    = isset( $_POST['salary_status'] ) ? sanitize_text_field( $_POST['salary_status'] ) : 'Yes';
		$show_projects    = isset( $_POST['show_projects'] ) ? sanitize_text_field( $_POST['show_projects'] ) : 'Yes';
		$geo_location     = isset( $_POST['geo_location'] ) ? sanitize_text_field( $_POST['geo_location'] ) : 'Yes';
		$user_roles       = isset( $_POST['user_roles'] ) ? ( $_POST['user_roles'] ) : '';
		$mail_logo        = isset( $_POST['mail_logo'] ) ? sanitize_text_field( $_POST['mail_logo'] ) : '';
		$office_in_sub    = isset( $_POST['office_in_sub'] ) ? sanitize_text_field( $_POST['office_in_sub'] ) : __( 'Login Alert From Employee & HR Management', 'employee-&-hr-management' );
		$office_out_sub   = isset( $_POST['office_out_sub'] ) ? sanitize_text_field( $_POST['office_out_sub'] ) : __( 'Logout Alert From Employee & HR Management', 'employee-&-hr-management' );
		$mail_heading     = isset( $_POST['mail_heading'] ) ? sanitize_text_field( $_POST['mail_heading'] ) : __( 'Staff Login/Logout Details', 'employee-&-hr-management' );

		$officein_text     = isset($_POST['officein_text']) ? sanitize_text_field($_POST['officein_text']) : __('Office In', 'employee-&-hr-management');
		$officeout_text     = isset($_POST['officeout_text']) ? sanitize_text_field($_POST['officeout_text']) : __('Office Out', 'employee-&-hr-management');
		$lunchin_text     = isset($_POST['lunchin_text']) ? sanitize_text_field($_POST['lunchin_text']) : __('Lunch In', 'employee-&-hr-management');
		$lunchout_text     = isset($_POST['lunchout_text']) ? sanitize_text_field($_POST['lunchout_text']) : __('Lunch Out', 'employee-&-hr-management');
		$latereson_text     = isset($_POST['latereson_text']) ? sanitize_text_field($_POST['latereson_text']) : __('Late Reason', 'employee-&-hr-management');
		$report_text     = isset($_POST['report_text']) ? sanitize_text_field($_POST['report_text']) : __('Daily Report', 'employee-&-hr-management');

		/* validations */
		$errors = [];
		if ( empty ( $timezone ) ) {
			$errors['timezone'] = __( 'Please select TimeZone', 'employee-&-hr-management' );
		}
		if ( empty ( $date_format ) ) {
			$errors['date_format'] = __( 'Please select Date format', 'employee-&-hr-management' );
		}
		if ( empty ( $time_format ) ) {
			$errors['time_format'] = __( 'Please select time format', 'employee-&-hr-management' );
		}
		if ( empty( $halfday_start ) ) {
			$errors['halfday_start'] = __('Please enter half start time', 'employee-&-hr-management');
		}
		if ( empty( $halfday_end ) ) {
			$errors['halfday_end'] = __('Please enter half end time', 'employee-&-hr-management');
		}
		if ( empty ( $lunch_start ) ) {
			$errors['lunch_start'] = __( 'Please enter lunch start time', 'employee-&-hr-management' );
		}
		if ( empty ( $lunch_end ) ) {
			$errors['lunch_end'] = __( 'Please enter lunch end time', 'employee-&-hr-management' );
		}
		if ( empty ( $cur_symbol ) ) {
			$errors['currency_symbol'] = __( 'Please enter currency symbol', 'employee-&-hr-management' );
		}
		if ( empty ( $cur_position ) ) {
			$errors['currency_position'] = __( 'Please select currency position', 'employee-&-hr-management' );
		}
		if ( $ip_restriction == 'Yes' && empty ( $restrict_ips ) ) {
			$errors['restrict_ips'] = __( 'Please enter restricted IP.', 'employee-&-hr-management' );
		}
		
		if ( $ip_restriction == 'Yes' && empty ( $restrict_ips2 ) ) {
			$errors['restrict_ips2'] = __( 'Please enter restricted IP.', 'employee-&-hr-management' );
		}
		
		if ( $geo_location == 'Yes' && empty ( $ehrm_gmap_api ) ) {
			$errors['ehrm_gmap_api'] = __( 'Please enter google API key', 'employee-&-hr-management' );
		}


		/* End validations */

		if ( count( $errors ) < 1 ) {
			$ehrm_settings_data = array(
				'timezone'         => $timezone,
				'date_format'      => $date_format,
				'time_format'      => $time_format,
				'monday_status'    => $monday_status,
				'tuesday_status'   => $tuesday_status,
				'wednesday_status' => $wednesday_status,
				'thursday_status'  => $thursday_status,
				'friday_status'    => $friday_status,
				'saturday_status'  => $saturday_status,
				'sunday_status'    => $sunday_status,
				'halfday_start'    => $halfday_start,
				'halfday_end'      => $halfday_end,
				'lunch_start'      => $lunch_start,
				'lunch_end'        => $lunch_end,
				'cur_symbol'       => $cur_symbol,
				'cur_position'     => $cur_position,
				'ehrm_gmap_api'    => $ehrm_gmap_api,
				'salary_method'    => $salary_method,
				'lunchtime'        => $lunchtime,
				'shoot_mail'       => $shoot_mail,
				'ip_restriction'   => $ip_restriction,
				'ip_rest_type'     => $ip_rest_type,
				'restrict_ips'     => $restrict_ips,
				'restrict_ips2'    => $restrict_ips2,
				'show_holiday'     => $show_holiday,
				'show_report'      => $show_report,
				'show_notice'      => $show_notice,
				'late_reson'       => $late_reson,
				'salary_status'    => $salary_status,
				'show_projects'    => $show_projects,
				'mail_logo'        => $mail_logo,
				'office_in_sub'    => $office_in_sub,
				'office_out_sub'   => $office_out_sub,
				'mail_heading'     => $mail_heading,
				'geo_location'     => $geo_location,
				'officein_text'     => $officein_text,
				'officeout_text'     => $officeout_text,
				'lunchin_text'     => $lunchin_text,
				'lunchout_text'     => $lunchout_text,
				'latereson_text'     => $latereson_text,
				'report_text'     => $report_text,
				'user_roles'       => serialize( $user_roles ),
			);

			update_option( 'ehrm_settings_data', $ehrm_settings_data );
			wp_send_json_success( array( 'message' => __( 'Settings updated successfully', 'employee-&-hr-management' ) ) );
		}
		wp_send_json_error( $errors );
	}
} 
?>