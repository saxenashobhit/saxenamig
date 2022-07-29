<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );

/**
 *  Ajax Action calls for Pay roll menu
 */
class PayRollAjaxAction {
	
	public static function generate_pay_roll() {
        check_ajax_referer('payroll_ajax_nonce', 'nounce');

        if ( ! empty( $_POST['first'] ) && ! empty( $_POST['last'] ) ) {
            $first             = sanitize_text_field( $_POST['first'] );
            $last              = sanitize_text_field( $_POST['last'] );
            $all_staffs        = get_option( 'ehrm_staffs_data' );
            $full_working_days = EHRMHelperClass::full_working_days( $first, $last );
		    $half_working_days = EHRMHelperClass::half_working_days( $first, $last );
            $html              = '';
            $Total             = array();
            $save_settings     = get_option( 'ehrm_settings_data' );

            if ( ! empty ( $save_settings['salary_method']  ) ) {
                $method = $save_settings['salary_method'];
            } else {
                $method = 'Monthly';
            }

            $month_1 = date( 'M Y', strtotime( $first ) );
            $month_2 = date( 'M Y', strtotime( $last ) );

            if ( $month_1 == $month_2 ) {
                $month = $month_1;
            } else {
                $month = __( 'From', 'employee-&-hr-management' ).' '.$month_1.' '.__( 'To', 'employee-&-hr-management' ).' '.$month_2;
            }

            if ( ! empty( $all_staffs ) ) {
                $sno = 1;
                foreach ( $all_staffs as $key => $staff ) {
                    $staff_name       = $staff['fullname'];
                    $salary           = $staff['salary'];
					$all_leaves       = unserialize( $staff['leave_value'] );
					$size             = sizeof( $all_leaves );
                    $leaves           = 0;
                    $total_attendance = EHRMHelperClass::ehrm_total_attendance_count( $staff['ID'], $first, $last );
                    $total_absent     = EHRMHelperClass::ehrm_total_absents( $staff['ID'], $first, $last );
                    $total_absent     = sizeof( $total_absent['dates1'] );
                    $TotalWorkingHour = EHRMHelperClass::get_staff_total_working_hours( $staff['ID'], $first, $last );
                    $TotalSalary      = EHRMHelperClass::ehrm_exact_salary_status( $first, $last, $method, $staff['ID'], true );
                    
					for ( $i = 0; $i < $size; $i++ ) {
						$leaves = $leaves + $all_leaves[$i];
                    }

                    /* Total Working Days */
                    $total_working_days = ( $full_working_days + $half_working_days ) - $leaves;
                    
                    $html .= '<tr>
                                <td>'.esc_html( $sno ).'</td>
                                <td>'.esc_html( $staff_name ).'</td>
                                <td>'.esc_html__( $month,  'employee-&-hr-management' ).'</td>
                                <td>'.esc_html( $total_working_days ).'</td>
                                <td>'.esc_html( $total_attendance ).'</td>
                                <td>'.esc_html( $total_absent ).'</td>
                                <td>'.esc_html( $TotalWorkingHour ).' '.esc_html__( 'Hours', 'employee-&-hr-management' ).'</td>
                                <td>'.esc_html__( $method, 'employee-&-hr-management' ).'</td>
                                <td>'.esc_html( EHRMHelperClass::get_currency_position_html( $salary ) ).'</td>
                                <td>'.esc_html( EHRMHelperClass::get_currency_position_html( $TotalSalary ) ).'</td>
                            </tr>';
                    array_push( $Total, $TotalSalary );
                    $sno++;
                }
            }
            $Total   = array_sum( $Total );
            $status  = 'success';
            $message = __( 'Pay roll generated successfully.!', 'employee-&-hr-management' );
            $content = wp_kses_post( $html );
            $total   = $Total;
        } else {
            $status  = 'error';
            $message = __( 'Something went wrong.!', 'employee-&-hr-management' );
            $content = '';
            $total   = '';
        }
        $return = array(
            'status'  => $status,
            'message' => $message,
            'content' => $content,
            'total'   => EHRMHelperClass::get_currency_position_html( $total ),
        );
        wp_send_json( $return );
		wp_die();
    }
}