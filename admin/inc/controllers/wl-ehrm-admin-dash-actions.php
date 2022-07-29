<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );

/**
 *  Ajax Action calls for Staff action
 */
class AdminDashBoardAction {
	
	public static function clock_actions() {
        check_ajax_referer('admin_ajax_nonce', 'nounce');

        if ( isset( $_POST['timezone'] ) && isset( $_POST['value'] ) ) {
            $timezone    = sanitize_text_field( $_POST['timezone'] );
            $value       = sanitize_text_field( $_POST['value'] );
            $staff_key   = sanitize_text_field( $_POST['staff_key'] );
            $attendences = get_option( 'ehrm_staff_attendence_data' );
            $all_staffs  = get_option( 'ehrm_staffs_data' );
            $html        = '';

            date_default_timezone_set( $timezone );
            $current_time = date( "H:i:s" );
            $current_date = date( 'Y-m-d' );

            /** Perform action by type value **/
            if ( $value == 'office-in' ) {

                $shift_data   = EHRMHelperClass::get_staff_shift($staff_key);
                $late_time    = date( "H:i:s", strtotime( $shift_data['late'] ) );
				$office_in    = date( "H:i:s", strtotime( $current_time ) );

                if ( $office_in > $late_time ) {
					$late = 'Late';
				} else {
					$late = 'On time';
				}

                $data = array(
                    'staff_id'     => $staff_key,
                    'name'         => EHRMHelperClass::get_current_user_data( $staff_key, 'fullname' ),
                    'email'        => EHRMHelperClass::get_current_user_data( $staff_key, 'user_email' ),
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

                if ( empty( $attendences ) ) {
                    $attendences = array();
                }
                array_push( $attendences, $data );

                if ( update_option( 'ehrm_staff_attendence_data', $attendences ) ) {           
                    $message = __( 'Staff Office In Time is ', 'employee-&-hr-management' ).' '.$current_time;
                    $status  = 'success';
                } else {
                    $message = __( 'Something went wrong.!', 'employee-&-hr-management' );
                    $status  = 'error';
                }

            } elseif (  $value == 'office-out' ) {

				$attendences   = get_option( 'ehrm_staff_attendence_data' );
				$save_settings = get_option( 'ehrm_settings_data' );

				if ( ! empty ( $attendences ) ) {
					foreach ( $attendences as $key => $attendence ) {
						if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $staff_key && ! empty ( $attendence['office_in'] ) && empty ( $attendence['office_out'] ) ) {
                            
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
                                    $today_total_hours = date( "H:i:s", $today_total_hours );
								} else {
									$today_total_hours = $total_working_duration;
								}
	
								$attendences[$key]['working_hour'] = $today_total_hours;

								if ( update_option( 'ehrm_staff_attendence_data', $attendences ) ) {
									$message = __( 'Staff Office Out Time is ', 'employee-&-hr-management' ).' '.$attendence[$key]['office_in'];
									$status  = 'success';
								} else {
									$message = __( 'Something went wrong.!', 'employee-&-hr-management' );
									$status  = 'error';
								}

							} else {
								$message = __( 'Something went wrong.!', 'employee-&-hr-management' );
								$status  = 'error';
							}	
						} else {
                            $message = __( 'Staff already logged out.!', 'employee-&-hr-management' );
							$status  = 'error';
                        }
					}
				}
            }
            
            $return = array(
                'message' => $message,
                'status'  => $status,
                'html'    => $html
            );
            wp_send_json( $return );
        }

    }
}

?>