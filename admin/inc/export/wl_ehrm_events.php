<?php 
defined( 'ABSPATH' ) or die();

/**
 *  Action calls for event export data as CSV file
 */
class EventsExportData {

    public static function export_data() {

        if ( empty( $_POST['event_action'] ) || 'export_settings' != $_POST['event_action'] )
            return;

        if ( ! wp_verify_nonce( $_POST['event_export_nonce'], 'event_export_nonce' ) )
            return;

        ignore_user_abort( true );
        nocache_headers();
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: inline; filename=ehrm-event-list-' . date( 'm-d-Y' ) . '.csv' );
        header( "Expires: 0" );

        $all_events = get_option( 'ehrm_events_data' );

        if ( ! empty ( $all_events ) ) {

            //Next 12 month	
            $sno         = 1;        
            echo "No., Name, Description, Date, Time, Status \r\n";
            
            foreach ( $all_events as $key => $event ) {
                    
                if ( ! empty ( $event['name'] ) ) {
                    $name = $event['name'];
                } else {
                    $name = '';
                }

                if ( ! empty ( $event['desc'] ) ) {
                    $desc = $event['desc'];
                    $desc = str_replace( ",", " ", $desc );
                } else {
                    $desc = '';
                }

                if ( ! empty ( $event['date'] ) ) {
                    $date = $event['date'];
                } else {
                    $date = '';
                }

                if ( ! empty ( $event['time'] ) ) {
                    $time = $event['time'];
                } else {
                    $time = '';
                }

                if ( ! empty ( $event['status'] ) ) {
                    $status = $event['status'];
                } else {
                    $status = '';
                }

                //add records
                echo "$sno, $name, $desc, $date, $time, $status \r\n";
                $sno++;
            }
        }
        exit;
    }
}