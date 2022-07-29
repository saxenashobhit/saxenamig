<?php 
defined( 'ABSPATH' ) or die();

/**
 *  Action calls for holiday export data as CSV file
 */
class HolidayImportData {

    public static function import_data() {

        if ( empty( $_POST['holiday_action'] ) || 'import_settings' != $_POST['holiday_action'] )
            return;

        if ( ! wp_verify_nonce( $_POST['holiday_import_nonce'], 'holiday_import_nonce' ) )
            return;

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
        $file  = fopen( $import_file,"r" );
        $count = 1;
        while( ! feof( $file ) ) {
            $csv_file_arr = fgetcsv( $file );
            if ( $count>1 ) {
                $holidays = get_option( "ehrm_holidays_data" );
                $name     = sanitize_text_field( $csv_file_arr[1] );
                $start    = sanitize_text_field( $csv_file_arr[2] );
                $to       = sanitize_text_field( $csv_file_arr[3] );
                $days     = sanitize_text_field( $csv_file_arr[4] );	
                $status   = sanitize_text_field( $csv_file_arr[5] );

                $csv_new_holiday = array ( 
                    'name'   => $name,
                    'start'  => $start,
                    'to'     => $to,
                    'days'   => $days,
                    'status' => $status,
                );

                $holidays[] = $csv_new_holiday;

                if ( update_option( "ehrm_holidays_data" , $holidays ) ) {}	  
            }
            $count++;
        }
        fclose( $file );
        wp_safe_redirect( admin_url( 'admin.php?page=employee-and-hr-management-holidays' ) );

        exit;
    }
}