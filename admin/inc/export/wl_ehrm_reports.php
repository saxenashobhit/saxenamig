<?php 
defined( 'ABSPATH' ) or die();
require_once(WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php');

/**
 *  Action calls for reports export
 */
class ReportsExportData {

    public static function export_data() {

    	if ( empty( $_POST['all_export_report_action'] ) || 'export_settings' != $_POST['all_export_report_action'] )
            return;

        if ( ! wp_verify_nonce( $_POST['all_export_report_nonce'], 'all_export_report_nonce' ) )
            return;

        $sids     = sanitize_text_field( $_POST['sids'] );
        $scolumns = sanitize_text_field( $_POST['scolumns'] );
        $sfrom    = sanitize_text_field( $_POST['sfrom'] );
        $sto      = sanitize_text_field( $_POST['sto'] );
        $staffs   = explode( ',', $sids );
        $columns  = explode( ',', $scolumns );

        $attendences  = get_option( 'ehrm_staff_attendence_data' );
        $all_dates    = EHRMHelperClass::ehrm_get_date_range( $sfrom, $sto );

        ignore_user_abort( true );
        nocache_headers();
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: inline; filename=ehrm-reports-' . date( 'm-d-Y' ) . '.csv' );
        header( "Expires: 0" );

        foreach ( $staffs as $key => $staff_id ) {

        	echo esc_html( EHRMHelperClass::get_current_user_data( $staff_id, 'fullname' ) )."\r\n";

        	echo "No., Date, Day, Office In, Office Out, Lunch In, Lunch Out, Working Hours, IP, Location, Punctuality, Late Reason, Reports \r\n";

        	$s_no = 1;
            foreach ( $all_dates as $key => $date ) {
                foreach ( $attendences as $key => $attendence ) {
                	if ( $attendence['date'] == $date && $attendence['staff_id'] == $staff_id && ! empty( $attendence['office_in'] ) ) {
                		$late          = $attendence['late'];
                        $working_hours = EHRMHelperClass::ehrm_daily_working_hours( $staff_id, $date );

                        if ( ! empty ( $attendence['office_in']  ) ) {
                            $office_in = date( EHRMHelperClass::get_time_format(), strtotime( $attendence['office_in'] ) );
                        } else {
                            $office_in = '---';
                        }

                        if ( ! empty ( $attendence['office_out']  ) ) {
                            $office_out = date( EHRMHelperClass::get_time_format(), strtotime( $attendence['office_out'] ) );
                        } else {
                            $office_out = '---';
                        }

                        if ( ! empty ( $attendence['lunch_in']  ) ) {
                            $lunch_in = date( EHRMHelperClass::get_time_format(), strtotime( $attendence['lunch_in'] ) );
                        } else {
                            $lunch_in = '---';
                        }

                        if ( ! empty ( $attendence['lunch_out']  ) ) {
                            $lunch_out = date( EHRMHelperClass::get_time_format(), strtotime( $attendence['lunch_out'] ) );
                        } else {
                            $lunch_out = '---';
                        }

						$date       = esc_html( date( EHRMHelperClass::get_date_format(), strtotime( $date ) ) );
						$day        = esc_html( date( 'l', strtotime( $date ) ) );
						$ip         = $attendence['id_address'];
						$location   = $attendence['location'];
						$late_reson = $attendence['late_reson'];
						$report     = $attendence['report'];

                        echo "$s_no, $date, $day, $office_in, $office_out, $lunch_in, $lunch_out, $working_hours, $ip, $location, $late, $late_reson, $report \r\n";
                        $s_no++;
                	}
                }
            }
        }
        exit;
    }
}