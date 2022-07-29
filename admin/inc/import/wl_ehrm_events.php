<?php 
defined( 'ABSPATH' ) or die();

/**
 *  Action calls for events export data as CSV file
 */
class EventsImportData {

    public static function import_data() {

        if ( empty( $_POST['event_action'] ) || 'import_settings' != $_POST['event_action'] )
            return;

        if ( ! wp_verify_nonce( $_POST['event_import_nonce'], 'event_import_nonce' ) )
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
                $events = get_option( "ehrm_events_data" );
                $name   = sanitize_text_field( $csv_file_arr[1] );
                $desc   = sanitize_text_field( $csv_file_arr[2] );
                $date   = sanitize_text_field( $csv_file_arr[3] );
                $time   = sanitize_text_field( $csv_file_arr[4] );	
                $status = sanitize_text_field( $csv_file_arr[5] );

                $csv_new_events = array ( 
                    'name'   => $name,
                    'desc'   => $desc,
                    'date'   => $date,
                    'time'   => $time,
                    'status' => $status,
                );

                $events[] = $csv_new_events;

                if ( update_option( "ehrm_events_data" , $events ) ) {}	  
            }
            $count++;
        }
        fclose( $file );
        wp_safe_redirect( admin_url( 'admin.php?page=employee-and-hr-management-events' ) );

        exit;
    }
}