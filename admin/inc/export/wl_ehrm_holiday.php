<?php 
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );

/**
 *  Action calls for holiday export data as CSV file
 */
class HolidayExportData {

    public static function export_data() {

        if ( empty( $_POST['holiday_action'] ) || 'export_settings' != $_POST['holiday_action'] )
            return;

        if ( ! wp_verify_nonce( $_POST['holiday_export_nonce'], 'holiday_export_nonce' ) )
            return;

        ignore_user_abort( true );
        nocache_headers();
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: inline; filename=ehrm-holiday-list-' . date( 'm-d-Y' ) . '.csv' );
        header( "Expires: 0" );

        $all_holidays = get_option( 'ehrm_holidays_data' );

        if ( ! empty ( $all_holidays ) ) {

            //Next 12 month	
            $sno         = 1;        
            $first       = new \DateTime( date( "Y" )."-01-01" );
            $first       = $first->format( "Y-m-d" );
            $plusOneYear = date( "Y" )+1;
            $last        = new \DateTime( $plusOneYear."-12-31" );          
            $last        = $last->format( "Y-m-d" );          
            $all_dates   = EHRMHelperClass::ehrm_get_date_range( $first, $last );

            echo "No., Name, From, To, Days, Status \r\n";
            
            foreach ( $all_holidays as $key => $holiday ) {
                if ( in_array( $holiday['to'], $all_dates ) ) {
                    
                    if ( ! empty ( $holiday['name'] ) ) {
                        $name = $holiday['name'];
                    } else {
                        $name = '';
                    }

                    if ( ! empty ( $holiday['start'] ) ) {
                        $start = $holiday['start'];
                    } else {
                        $start = '';
                    }

                    if ( ! empty ( $holiday['to'] ) ) {
                        $to = $holiday['to'];
                    } else {
                        $to = '';
                    }

                    if ( ! empty ( $holiday['days'] ) ) {
                        $days = $holiday['days'];
                    } else {
                        $days = '';
                    }

                    if ( ! empty ( $holiday['status'] ) ) {
                        $status = $holiday['status'];
                    } else {
                        $status = '';
                    }

                    //add records
                    echo "$sno, $name, $start, $to, $days, $status \r\n";
                    $sno++;
                }
            }
        }
        exit;
    }
}