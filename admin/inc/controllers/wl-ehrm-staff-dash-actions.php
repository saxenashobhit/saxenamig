<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );
// require_once WL_EHRM_PLUGIN_DIR_PATH . 'includes/constants.php';
require_once WL_EHRM_PLUGIN_DIR_PATH . 'includes/EHRM_Helper.php';
/**
 *  Ajax Action calls for Staff action
 */
class StaffDashBoardAction {
	
	//---Break Time time
	public static function break_time($times) {
		$minutes = 0; //declare minutes either it gives Notice: Undefined variable
		// loop throught all the times
		foreach ($times as $time) {
			list($hour, $minute) = explode(':', $time);
			$minutes += (int)$hour * 60;
			$minutes += $minute;
		}
		$hours = floor($minutes / 60);
		$minutes -= $hours * 60;
		// returns the time already formatted
		return sprintf('%02d.%02d', $hours, $minutes);
	}
	
	public static function clock_actions() {
		check_ajax_referer( 'staff_ajax_nonce', 'nounce' );

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
				$office_in    = $current_time;

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
					$message = esc_html__( 'Your Office In Time is', 'employee-&-hr-management' ).' '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $current_time ) ) );
					$status  = 'success';
					// EHRMHelperClass::ehrm_shoot_mail_staff_details( get_current_user_id(), $current_time, '', EHRMHelperClass::get_user_location( $_SERVER['REMOTE_ADDR'] ), $_SERVER['REMOTE_ADDR'] );
					EHRMHelperClass::ehrm_shoot_mail_staff_details( $current_time, '', EHRMHelperClass::get_user_location( $_SERVER['REMOTE_ADDR'] ), $_SERVER['REMOTE_ADDR'], get_current_user_id() );

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
								$message = esc_html__( 'Your Lunch In Time is', 'employee-&-hr-management' ).' '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $current_time ) ) );
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
								$message = esc_html__( 'Your Lunch Out Time is', 'employee-&-hr-management' ).' '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $current_time ) ) );
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
				$breaksdata    = get_option( 'ehrm_breakpoints' ); 
				
				$duration_arr = array();
				if( !empty($breaksdata) && is_array($breaksdata) ) {
					foreach ( $breaksdata as $key => $breaks ) {
						array_push( $duration_arr, $breaks['break_time'] );	
					}
				}
				echo $attendence['staff_id'];
				// echo "<pre>"; var_dump($attendences); echo "</pre>";				
				
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
								
								// $total_working_duration = EHRMHelperClass::get_time_difference( $attendences[$key]['office_in'], $attendences[$key]['office_out'] );
								$total_working_duration = EHRMHelperClass::get_time_difference( $attendences[0]['office_in'], $attendences[0]['office_out'] );
							
								if ( ! empty( $lunch_duration ) && $save_settings['lunchtime'] == 'Exclude' ) {
									$today_total_hours = strtotime( $total_working_duration ) - strtotime( $lunch_duration );
								} else {
									$today_total_hours = $total_working_duration;
								}	
								
								if ( !empty ( $duration_arr ) ) {									
									$duration_arr = StaffDashBoardAction::break_time( $duration_arr );									
								}							
							
								$attendences[$key]['working_hour'] = EHRMHelperClass::get_time_difference( $total_working_duration, $duration_arr );
								
								//$attendences[$key]['working_hour'] = "page 1";
																
								if ( update_option( 'ehrm_staff_attendence_data', $attendences ) ) {
									$message = esc_html__( 'Your Office Out Time is', 'employee-&-hr-management' ).'  '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $attendence[$key]['office_out'] ) ) );
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
		check_ajax_referer( 'staff_ajax_nonce', 'nounce' );

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
							wp_send_json( esc_html__( 'Updated', 'employee-&-hr-management' ) );
						} else {
							wp_send_json( esc_html__( 'Something went wrong.!', 'employee-&-hr-management' ) );
						}
					}
				}
			}
		}
	}

	public static function staff_daily_report() {
		check_ajax_referer( 'staff_ajax_nonce', 'nounce' );
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
							wp_send_json( esc_html__( 'Updated', 'employee-&-hr-management' ) );
						} else {
							wp_send_json( esc_html__( 'Something went wrong.!', 'employee-&-hr-management' ) );
						}
					}
				}
			}
		}
	}
	
	public static function staff_break(){
		check_ajax_referer( 'staff_ajax_nonce', 'nounce' );
		
		if ( isset ( $_POST['counter'] ) ) {
	
		$ehrm_settings = get_option('ehrm_settings_data');	
		$counter      = 0;
		$all_breaks   = get_option( 'ehrm_breakpoints' );
		$date         = date('Y-m-d');
		$current_user = get_current_user_id();
		
		if ( ! empty ( $all_breaks ) ) {

			foreach ( $all_breaks as $key => $breaks ) {
				if ( $breaks['date'] == date( 'Y-m-d' ) && $breaks['user_id'] == $current_user ) {
					$counter++;
				}
			}
		}

		//check if multitimezone is enable or not
		/*if ( isset( $cip_settings['multi_time'] ) ) {
			if ( $cip_settings['multi_time'] == 'yes' ) {
				$user_time_zone = get_user_timezone ($userid );
				$new_date_time  = $date.' '.date( "H:i:s" );
				$new_convert_time = changeTimeZone( $new_date_time, $cip_settings['cip_timezone'], $user_time_zone, 'time' );
			} else {
				date_default_timezone_set( $cip_settings['cip_timezone'] );
				$new_convert_time   = date( "H:i:s" );
			}
		} else {*/
			date_default_timezone_set( $ehrm_settings['timezone'] );
			$new_convert_time   = date( "H:i:s" );
		/*}*/

		$data   = array(
			'date'       => date( 'Y-m-d' ),
			'user_id'    => $current_user,
			'break_in'   => $new_convert_time,
			'break_out'  => '',
			'counter'    => $counter,
			'break_time' => '',
		);

		if ( empty ( $all_breaks ) ) {
			$all_breaks = array();
		}
		array_push( $all_breaks, $data );

		if ( update_option( 'ehrm_breakpoints', $all_breaks ) ) {
			wp_send_json( 'Break added' );
		} else {
			wp_send_json( 'Break not added' );
		}

		} else {
			wp_send_json( 'Something went wrong.!' );
		}
		wp_die();
		
	}
	
	/* Breakout */
	public static function staff_breakout() {
		check_ajax_referer( 'staff_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['counter'] ) ) {
			$counter      = sanitize_text_field( $_POST['counter'] );
			$all_breaks   = get_option( 'ehrm_breakpoints' );
			$date         = date('Y-m-d');
			$current_user = get_current_user_id();
			$ehrm_settings = get_option('ehrm_settings_data');

			//check if multitimezone is enable or not
			/*if ( isset( $cip_settings['multi_time'] ) ) {
				if ( $cip_settings['multi_time'] == 'yes' ) {
					$user_time_zone = get_user_timezone ($userid );
					$new_date_time  = $date.' '.date( "H:i:s" );
					$new_convert_time = changeTimeZone( $new_date_time, $cip_settings['cip_timezone'], $user_time_zone, 'time' );
				} else {
					date_default_timezone_set( $cip_settings['cip_timezone'] );
					$new_convert_time   = date( "H:i:s" );
				}
			} else {*/
				date_default_timezone_set( $ehrm_settings['timezone'] );
				$new_convert_time   = date( "H:i:s" );
			/*}*/


			$dteStart = new DateTime( $all_breaks[$counter]['break_in'] ); 
			$dteEnd   = new DateTime( $new_convert_time ); 
			$dteDiff  = $dteStart->diff( $dteEnd ); 
			$duration = $dteDiff->format( "%H:%I:%S" );

			$data   = array(
				'date'       => $all_breaks[$counter]['date'],
				'user_id'    => $current_user,
				'break_in'   => $all_breaks[$counter]['break_in'],
				'break_out'  => $new_convert_time,
				'counter'    => $all_breaks[$counter]['counter'],
				'break_time' => $duration,
			);

			$all_breaks[$counter] = $data;

			if ( update_option( 'ehrm_breakpoints', $all_breaks ) ) {
				wp_send_json( 'Break Updated' );
			} else {
				wp_send_json( 'Break not Updated' );
			}

		} else {
			wp_send_json( 'Something went wrong.!' );
		}
		wp_die();
	}
}