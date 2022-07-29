<?php 
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );

/**
 *  Action calls for Reports export data as CSV file
 */
class ReportsImportData {

    public static function import_data() {

        if ( empty( $_POST['report_action'] ) || 'import_settings' != $_POST['report_action'] )
            return;

        if ( ! wp_verify_nonce( $_POST['report_import_nonce'], 'report_import_nonce' ) )
            return;

        $staff_id  = sanitize_text_field( $_POST['import_staffid'] );
        $extension = end( explode( '.', $_FILES['import_file']['name'] ) );

        if ( empty ( $extension ) ) {
            wp_die( __( 'Please upload a file first.!', 'employee-&-hr-management' ) );
        }

        if ( $extension != 'csv' ) {
            wp_die( __( 'Please upload a valid .csv file.!', 'employee-&-hr-management' ) );
        }

        $import_file = $_FILES['import_file']['tmp_name'];
        if ( empty( $import_file ) ) {
            wp_die( __( 'Please upload a file to import.!', 'employee-&-hr-management' ) );
        }

        $file  = fopen( $import_file, "r" );
        $count = 1;

        while( ! feof( $file ) ) {
            $csv_file_arr = fgetcsv( $file );
            if ( $count > 1 ) {
                if ( ! empty ( $csv_file_arr[4] ) && trim( $csv_file_arr[4] ) != 'Staff' && trim( $csv_file_arr[4] ) != 'Day Off' ) { 

                    $attendences   = get_option( "ehrm_staff_attendence_data" );
                    $name          = trim( $csv_file_arr[1] );
                    $date          = trim( $csv_file_arr[2] );
                    $day           = trim( $csv_file_arr[3] );
                    $office_in     = trim( $csv_file_arr[4] );
                    $office_out    = trim( $csv_file_arr[5] );
                    $lunch_in      = trim( $csv_file_arr[6] );
                    $lunch_out     = trim( $csv_file_arr[7] );
                    $working_hours = trim( $csv_file_arr[8] );
                    $user_ip       = trim( $csv_file_arr[9] );
                    $location      = trim( $csv_file_arr[10] );
                    $puntuality    = trim( $csv_file_arr[11] );
                    $late_reason   = trim( $csv_file_arr[12] );
                    $daily_report  = trim( $csv_file_arr[13] );
                    $office_in     = date( 'H:i:s', strtotime( $office_in ) );
                    $date          = date( 'Y-m-d', strtotime( $date ) );

                    if ( ! empty ( $office_out ) ) {
                        $office_out = date( 'H:i:s', strtotime( $office_out ) );
                    }
                    
                    if ( ! empty ( $lunch_in ) ) {
                        $lunch_in   = date( 'H:i:s', strtotime( $lunch_in ) );
                    }

                    if ( ! empty ( $lunch_out ) ) {
                        $lunch_out  = date( 'H:i:s', strtotime( $lunch_out ) );
                    }

                    $data = array (
                        'staff_id'     => $staff_id,
                        'name'         => EHRMHelperClass::get_current_user_data( $staff_id, 'fullname' ),
                        'email'        => EHRMHelperClass::get_current_user_data( $staff_id, 'user_email' ),
                        'office_in'    => $office_in,
                        'office_out'   => $office_out,
                        'lunch_in'     => $lunch_in,
                        'lunch_out'    => $lunch_out,
                        'late'         => $puntuality,
                        'late_reson'   => $late_reason,
                        'report'       => $daily_report,
                        'working_hour' => $working_hours,
                        'date'         => $date,
                        'timestamp'    => time(),
                        'id_address'   => $user_ip,
                        'location'     => $location,
                    );

                    if ( empty ( $attendences ) ) {
                        $attendences = array();
                    }
                    array_push( $attendences, $data );

                    if ( update_option( 'ehrm_staff_attendence_data', $attendences ) ) {
                        echo 'Report Imported';
                    } else {
                        echo 'Report Not Imported';
                    }
                    
                }
            }
            $count++;
        }

        fclose( $file );
        wp_safe_redirect( admin_url( 'admin.php?page=employee-and-hr-management-reports' ) );

        exit;
    }
}