<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );

/**
 *  Ajax Action calls for Staff action
 */
class FrontDashBoardAction {
	public static function admin_default_page() {
	  return 'https://www.google.co.in';
	}
	
	public static function clock_actions() {
		check_ajax_referer( 'login_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['timezone'] ) && isset ( $_POST['value'] ) ) {
			$timezone    = sanitize_text_field( $_POST['timezone'] );
			$value       = sanitize_text_field( $_POST['value'] );
			$attendences = get_option( 'ehrm_staff_attendence_data' );

			date_default_timezone_set( $timezone );
			$current_time = date( "H:i:s" );
			$current_date = date( 'Y-m-d' );

			if ( $value == 'office-in' ) {
				$shift_data   = EHRMHelperClass::get_staff_shift( get_current_user_id() );
				$late_time    = date( "H:i:s", strtotime( $shift_data['late'] ) );
				$office_in    = strtotime( $current_time );

				if ( $office_in > $late_time ) {
					$late = 'Late';
				} else {
					$late = 'On time';
				}

				$data = array(
					'staff_id'     => get_current_user_id(),
					'name'         => EHRMHelperClass::get_current_user_data( get_current_user_id(), 'fullname' ),
					'email'        => EHRMHelperClass::get_current_user_data( get_current_user_id(), 'user_email' ),
					'office_in'    => $current_time,
					'office_out'   => '',
					'lunch_in'     => '',
					'lunch_out'    => '',
					'late'         => $late,
					'late_reson'   => '',
					'report'       => '',
					'working_hour' => '',
					'date'         => $current_date,
					'timestamp'    => time(),
					'id_address'   => $_SERVER['REMOTE_ADDR'],
					'location'     => EHRMHelperClass::get_user_location( $_SERVER['REMOTE_ADDR'] ),
				);

				if ( empty ( $attendences ) ) {
					$attendences = array();
				}
				array_push( $attendences, $data );

				if ( update_option( 'ehrm_staff_attendence_data', $attendences ) ) {
					$message = esc_html__( 'Your Office In Time is ', 'employee-&-hr-management' ).' '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $current_time ) ) );
					$status  = 'success';

					$save_settings  = get_option( 'ehrm_settings_data' );
					if ( isset ( $save_settings['shoot_mail'] ) && $save_settings['shoot_mail'] == 'Yes' ) {
						EHRMHelperClass::ehrm_shoot_mail_staff_details( get_current_user_id(), $current_time, '', EHRMHelperClass::get_user_location( $_SERVER['REMOTE_ADDR'] ), $_SERVER['REMOTE_ADDR'] );
					}

				} else {
					$message = esc_html__( 'Something went wrong.!', 'employee-&-hr-management' );
					$status  = 'error';
				}

				$return = array(
				    'message' => $message,
				    'status'  => $status,
				    'late'    => $late,
				);

				wp_send_json( $return );
			} elseif ( $value == 'lunch-in' ) {
				$staff_id    = get_current_user_id();
				$attendences = get_option( 'ehrm_staff_attendence_data' );

				if ( ! empty ( $attendences ) ) {
					foreach ( $attendences as $key => $attendence ) {
						if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $staff_id && ! empty ( $attendence['office_in'] ) ) {
							$attendences[$key]['lunch_in'] = $current_time;
							if ( update_option( 'ehrm_staff_attendence_data', $attendences ) ) {
								$message = esc_html__( 'Your Lunch In Time is ', 'employee-&-hr-management' ).' '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $current_time ) ) );
								$status  = 'success';
							} else {
								$message = esc_html__( 'Something went wrong.!', 'employee-&-hr-management' );
								$status  = 'error';
							}

							$return = array(
							    'message' => $message,
							    'status'  => $status,
							);

							wp_send_json( $return );
						}
					}
				}

			} elseif ( $value == 'lunch-out' ) {
				$staff_id    = get_current_user_id();
				$attendences = get_option( 'ehrm_staff_attendence_data' );

				if ( ! empty ( $attendences ) ) {
					foreach ( $attendences as $key => $attendence ) {
						if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $staff_id && ! empty ( $attendence['office_in'] ) && ! empty ( $attendence['lunch_in'] ) && empty ( $attendence['lunch_out'] ) ) {
							$attendences[$key]['lunch_out'] = $current_time;
							if ( update_option( 'ehrm_staff_attendence_data', $attendences ) ) {
								$message = esc_html__( 'Your Lunch Out Time is ', 'employee-&-hr-management' ).' '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $current_time ) ) );
								$status  = 'success';
							} else {
								$message = esc_html__( 'Something went wrong.!', 'employee-&-hr-management' );
								$status  = 'error';
							}

							$return = array(
							    'message' => $message,
							    'status'  => $status,
							);

							wp_send_json( $return );
						}
					}
				}

			} elseif (  $value == 'office-out' ) {
				$staff_id      = get_current_user_id();
				$attendences   = get_option( 'ehrm_staff_attendence_data' );
				$save_settings = get_option( 'ehrm_settings_data' );
				$current_time  = date( 'H:i:s' );
				$current_date  = date( 'Y-m-d' );

				if ( ! empty ( $attendences ) ) {
					foreach ( $attendences as $key => $attendence ) {
						if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $staff_id && ! empty ( $attendence['office_in'] ) && empty ( $attendence['office_out'] ) ) {

							$attendences[$key]['office_out'] = $current_time;
							if ( update_option( 'ehrm_staff_attendence_data', $attendences ) ) {

								/* Working hours */
								$attendences = get_option( 'ehrm_staff_attendence_data' );

								if ( ! empty( $attendence['lunch_out'] ) && ! empty( $attendence['lunch_in'] ) ) {
									$lunch_duration = EHRMHelperClass::get_time_difference( $attendence['lunch_in'], $attendence['lunch_out'] );
								} elseif ( empty( $attendence['lunch_out'] ) && ! empty( $attendence['lunch_in'] ) ) {
									$savesetting    = get_option('ehrm_settings_data');
									$lunch_out      = $savesetting['lunch_end'];
									$lunch_duration = strtotime( $lunch_out ) - strtotime( $attendence['lunch_in'] );
								}

								$total_working_duration = EHRMHelperClass::get_time_difference( $attendence['office_in'], $attendence[$key]['office_in'] );
							
								if ( ! empty( $lunch_duration ) && $save_settings['lunchtime'] == 'Exclude') {
									$today_total_hours = strtotime( $total_working_duration ) - strtotime( $lunch_duration );
								} else {
									$today_total_hours = $total_working_duration;
								}
	
								$attendences[$key]['working_hour'] = $today_total_hours;

								if ( update_option( 'ehrm_staff_attendence_data', $attendences ) ) {
									$message = esc_html__( 'Your Office Out Time is ', 'employee-&-hr-management' ).' '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $attendence[$key]['office_out'] ) ) );
									$status  = 'success';
									EHRMHelperClass::ehrm_shoot_mail_staff_details( get_current_user_id(), $attendence['office_in'], $current_time, EHRMHelperClass::get_user_location( $_SERVER['REMOTE_ADDR'] ), $_SERVER['REMOTE_ADDR'] );
								} else {
									$message = esc_html__( 'Something went wrong.!', 'employee-&-hr-management' );
									$status  = 'error';
								}

							} else {
								$message = esc_html__( 'Something went wrong.!', 'employee-&-hr-management' );
								$status  = 'error';
							}

							$return = array(
							    'message' => $message,
							    'status'  => $status,
							);
							wp_send_json( $return );
						}
					}
				}
			}
			
		} else {
			wp_send_json( esc_html__( 'Something went wrong.!', 'employee-&-hr-management' ) );
		}
		wp_die();
	}

	public static function late_reson_submit() {
		check_ajax_referer( 'login_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['staff_id'] ) && isset ( $_POST['reson'] ) ) {
			$staff_id      = sanitize_text_field( $_POST['staff_id'] );
			$reson         = sanitize_text_field( $_POST['reson'] );
			$attendences   = get_option( 'ehrm_staff_attendence_data' );
			$current_date  = date( 'Y-m-d' );

			if ( ! empty ( $attendences ) ) {
				foreach ( $attendences as $key => $attendence ) {
					if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $staff_id && ! empty ( $attendence['office_in'] ) ) {
						$attendences = get_option( 'ehrm_staff_attendence_data' );
						$attendences[$key]['late_reson'] = $reson;
						if ( update_option( 'ehrm_staff_attendence_data', $attendences ) ) {
							wp_send_json( 'Late Reason Updated.!' );
						} else {
							wp_send_json( esc_html__( 'Something went wrong.!', 'employee-&-hr-management' ) );
						}
					}
				}
			}
		}
	}

	public static function staff_daily_report() {
		check_ajax_referer( 'login_ajax_nonce', 'nounce' );
		if ( isset ( $_POST['staff_id'] ) && isset ( $_POST['report'] ) ) {
			$staff_id      = sanitize_text_field( $_POST['staff_id'] );
			$report        = sanitize_text_field( $_POST['report'] );
			$attendences   = get_option( 'ehrm_staff_attendence_data' );
			$current_date  = date( 'Y-m-d' );

			if ( ! empty ( $attendences ) ) {
				foreach ( $attendences as $key => $attendence ) {
					if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $staff_id && ! empty ( $attendence['office_in'] ) ) {
						$attendences = get_option( 'ehrm_staff_attendence_data' );
						$attendences[$key]['report'] = $report;
						if ( update_option( 'ehrm_staff_attendence_data', $attendences ) ) {
							wp_send_json( 'Daily Report Submitted.!' );
						} else {
							wp_send_json( esc_html__( 'Something went wrong.!', 'employee-&-hr-management' ) );
						}
					}
				}
			}
		}
	}
}

?>