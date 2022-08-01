<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/sms/vendor/autoload.php' );
// require_once WL_EHRM_PLUGIN_DIR_PATH . 'includes/constants.php';
require_once WL_EHRM_PLUGIN_DIR_PATH . 'includes/EHRM_Helper.php';

/**
 * Helper Class
 */
class EHRMHelperClass {

	/**
	 * Helper function for Set Default Date Format
	 *
	 * @return string value
	 */
	public static function get_date_format() {
		$savesetting = get_option( 'ehrm_settings_data' );

		if ( ! empty ( $savesetting ) ) {
			$date_format  = $savesetting['date_format'];

			if ( ! empty ( $date_format ) ) {
				return $date_format;
			} else {
				return 'F j Y';
			}
		} else {
			return 'F j Y';
		}
	}

	/**
	 * Helper function for Set Default Time Format
	 *
	 * @return string value
	 */
	public static function get_time_format() {
		$savesetting = get_option( 'ehrm_settings_data' );

		if ( ! empty ( $savesetting ) ) {

			$time_format  = $savesetting['time_format'];
			if ( ! empty ( $time_format ) ) {
				return $time_format;
			} else {
				return 'g:i A';
			}
		} else {
			return 'g:i A';
		}
		
	}

	/**
	 * Helper function for Set Default Currency Position
	 *
	 * @return string value
	 */
	public static function get_currency_position() {
		$savesetting = get_option( 'ehrm_settings_data' );

		if ( ! empty ( $savesetting ) ) {

			$cur_position = $savesetting['cur_position'];

			if ( ! empty ( $cur_position ) ) {
				return $cur_position;
			} else {
				return 'Right';
			}
		} else {
			return 'Right';
		}
	}

	/**
	 * Helper function for get html with currencyy position
	 *
	 * @param string  $string text string.
	 * @return html
	 */
	public static function get_currency_position_html( $string ) {
		$position       = self::get_currency_position();
		$savesetting    = get_option( 'ehrm_settings_data' );
		$currency_symbl = $savesetting['cur_symbol'];

		if ( $position == 'Right' ) {
			$value = $string.' '.$currency_symbl;
		} else {
			$value = $currency_symbl.' '.$string;
		}
		return $value;
	}

	/**
	 * Helper function for Set Default Timezone
	 *
	 * @return string value
	 */
	public static function get_setting_timezone() {
		$savesetting = get_option( 'ehrm_settings_data' );

		if ( ! empty ( $savesetting ) ) {
			$time_zone    = $savesetting['timezone'];

			if ( ! empty ( $time_zone ) ) {
				return $time_zone;
			} else {
				return 'Asia/Kolkata';
			}
		} else {
			return 'Asia/Kolkata';
		}
	}

	/**
	 * Helper function for check multitimezone is enable/disable
	 *
	 * @return string value
	 */
	public static function check_multi_timezone_enable() {
		$savesetting = get_option( 'ehrm_settings_data' );

		if ( ! empty ( $savesetting ) ) {
			if ( isset( $savesetting['multilocation'] ) ) {
				if ( $savesetting['multilocation'] == 'Yes' ) {
					$value = 'Yes';	
				} else {
					$value = 'No';
				}
			} else {
				$value = 'No';
			}
		} else {
			$value = 'Yes';
		}
		return $value;	
	}

	/**
	 * Helper function for getting user role
	 *
	 * @return string value
	 */
	public static function ehrm_get_current_user_roles() {
		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();
			$roles = ( array ) $user->roles;
			return $roles[0];
		} else {
			return array();
		}
	}

	/**
	 * Helper function for check user availability in staff table
	 *
	 * @return boolean value
	 */
	public static function check_user_availability() {
		global $wpdb;
		//$staffs        = get_option( 'ehrm_staffs_data' );
		$status        = 0;
		$user_id       = get_current_user_id();
		$staffs = EHRM_Helper::check_staff_existance( $user_id );

		if ( ! empty ( $staffs[0]->total > 0 ) ) {        		
			$exist = 1;
		} else {
			$exist = 0;
		}	
			
		if ( $staffs[0]->total > 0 ) {
			$status++;
		}

		if ( $status != 0 ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Helper function for all staff's list
	 *
	 * @return array
	 */
	public static function ehrm_get_staffs_list() {
		$all_staffs = get_option('ehrm_staffs_data');
		$staffs     = array();
		if ( ! empty( $all_staffs ) ) {
			foreach ( $all_staffs as $key => $staff ) {
				if ( $staff['status'] == 'Active') {
					array_push( $staffs, $staff );
				}
			}
		}
		return $staffs;
	}

	/**
	 * Helper function for month filter
	 *
	 * @return array
	 */
	public static function ehrm_month_filter() {
		$months       = array();
		$current_mnth = date("F Y");
		array_push($months, $current_mnth);
		for ( $i=1; $i < 13; $i++ ) {
			$current_mnth = date( "F Y", strtotime( "-$i month" )  );
			array_push( $months, $current_mnth );
		}
		return $months;
	}

	/**
	 * Helper function for Settings Panel
	 *
	 * @return array
	 */
	public static function timezone_list() {

		//Timezone array
		//$timezones = DateTimeZone::listAbbreviations( DateTimeZone::ALL ); 
		$timezones = DateTimeZone::listAbbreviations(); 
		$tzlist    = DateTimeZone::listIdentifiers( DateTimeZone::ALL );
		$cities1   = array();
		$cities2   = array();
		$cities3   = array();

		foreach( $timezones as $key => $zones ) {
		    foreach( $zones as $id => $zone ) {  
		        array_push( $cities1, $zone["timezone_id"] ); 
		    }
		}

		foreach( timezone_abbreviations_list() as $abbr => $timezone ) {
		    foreach( $timezone as $val ) {
		        if ( isset( $val['timezone_id'] ) ) { 
		            array_push( $cities2, $val['timezone_id'] );
		        }
		    }
		}

		foreach( $tzlist as  $timezone ) {
		    if ( isset( $timezone ) ) {
		        array_push( $cities3, $timezone );
		    }
		} 

		$ALL_timezone    = array_merge( $cities1, $cities2, $cities3 );
		$result_timezone = array_unique( $ALL_timezone ); 
		sort( $result_timezone );

		return $result_timezone;
	}

	/**
	 * Helper function for generating reports
	 *
	 * @param  int  $$moth month value.
	 * @return array
	 */
	public static function get_all_dates_reports( $month ) {
		if ( $month == '1' ) {

			$first     = date( "Y-m-01" );
			$last      = date( "Y-m-t", strtotime( $first ) );            
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		} elseif ( $month == '2' ) {

			$first     = date( "Y-m-01", strtotime( "-1 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );              
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		}  elseif ( $month == '3' ) {

			$first     = date( "Y-m-01", strtotime( "-2 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );              
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		}  elseif ( $month == '4' ) {

			$first     = date( "Y-m-01", strtotime( "-3 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );          
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		}  elseif ( $month == '5' ) {

			$first     = date( "Y-m-01", strtotime( "-4 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );     
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		}  elseif ( $month == '6' ) {

			$first     = date( "Y-m-01", strtotime( "-5 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );         
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		}  elseif ( $month == '7' ) {
			$first     = date( "Y-m-01", strtotime( "-6 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );        
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		}  elseif ( $month == '8' ) {

			$first     = date( "Y-m-01", strtotime( "-7 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );       
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		}  elseif ( $month == '9' ) {

			$first     = date( "Y-m-01", strtotime( "-8 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );           
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		}  elseif ( $month == '10' ) {

			$first     = date( "Y-m-01", strtotime( "-9 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );    
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		}  elseif ( $month == '11' ) {

			$first     = date( "Y-m-01", strtotime( "-10 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );         
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		}  elseif ( $month == '12' ) {

			$first     = date( "Y-m-01", strtotime( "-11 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );        
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		}  elseif ( $month == '13' ) {

			$first     = date( "Y-m-01", strtotime( "-12 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );     
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		} elseif ( $month == '14' ) {

			$first     = date( "Y-m-01", strtotime( "-3 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );
			$last      = date( "Y-m-d", strtotime( "+2 month", strtotime( $last ) ) );      
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		} elseif( $month == "15" ) {

			$first    = date( "Y-m-01", strtotime( "-6 month" ) );
			$last     = date( "Y-m-t", strtotime( $first ) );
			$last     = date( "Y-m-d", strtotime( "+5 month", strtotime( $last ) ) );    
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		} elseif( $month == "16" ) {

			$first     = date( "Y-m-01", strtotime( "-9 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );
			$last      = date( "Y-m-d", strtotime( "+8 month", strtotime( $last ) ) );     
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		} elseif( $month == "17" ) {

			$first     = date( "Y-m-01", strtotime( "-12 month" ) );
			$last      = date( "Y-m-t", strtotime( $first ) );
			$last      = date( "Y-m-d", strtotime( "+11 month", strtotime( $last ) ) ); 
			$all_dates = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		}
		return $all_dates;
	}

	/**
	 * Helper function for Staff's Dashboard
	 *
	 * @return string
	 */
	public static function staff_greeting_status() {
		$save_settings  = get_option( 'ehrm_settings_data' );
		date_default_timezone_set( self::get_setting_timezone() );
		$current_time   = date( "H:i:s" );
		if ( $current_time < '12:00:00' ) {
			return esc_html__('Good Morning', '"employee-&-hr-management"' );
		}
		if ( $current_time > '12:00:00' && $current_time < '17:00:00') {
			return esc_html__('Good Afternoon ', '"employee-&-hr-management"' );
		}
		if ( $current_time > '17:00:00' && $current_time < '21:00:00') {
			return esc_html__('Good Evening ', '"employee-&-hr-management"' );
		}
		if ( $current_time > '21:00:00' && $current_time < '04:00:00') {
			return esc_html__('Good Night ', '"employee-&-hr-management"' );
		}
	}

	/**
	 * Helper function for staff total working hour
	 *
	 * @param  array  $times working hours array.
	 * @return string
	 */
	public static function total_salary_time( $times ) {
		$minutes = 0; //declare minutes either it gives Notice: Undefined variable
		// loop throught all the times
		foreach ( $times as $time ) {
			list( $hour, $minute ) = explode( ':', $time );
			$minutes += $hour * 60;
			$minutes += $minute;
		}
		$hours    = floor( $minutes / 60 );
		$minutes -= $hours * 60;
		// returns the time already formatted
		return sprintf( '%02d.%02d', $hours, $minutes );
	}

	/**
	 * Helper function for Getting time difference
	 *
	 * @param  string  $start starting time.
	 * @param  string  $end ending time.
	 * @return string
	 */
	public static function get_time_difference( $start, $end ) {
		$dteStart = new DateTime( $start );
		$dteEnd   = new DateTime( $end );
		$dteDiff  = $dteStart->diff( $dteEnd );
		// $dteDiff  = date_diff($dteStart, $dteEnd);
		$WorkHour = $dteDiff->format( "%H:%I:%S" );
		// $WorkHour = date_format( $dteDiff, "%H:%I:%S" );
		return $WorkHour;
	}

	/*public static function get_time_difference( $start, $end ) {
		$start = "13:23:05";
		$end = "15:01:55";
		// $end = "12:16";
		$dteStart = new DateTime( $start );
		$dteEnd   = new DateTime( $end );
		$dteDiff  = $dteStart->diff( $dteEnd );
		$WorkHour = $dteDiff->format( "%H:%I:%S" );
		return $WorkHour;
	}*/

	/**
	 * Helper function for Staff's Data
	 *
	 * @param  int  $id current User id.
	 * @param  string  $value index value.
	 * @return string
	 */
	public static function get_current_user_data( $id, $value ) {
		global $wpdb;
		// if( isset( $id[0] ) && empty($id[0]) ) {
		// 	$id = $id[0];
		// } else {
		// 	$id = $id;
		// }
		$user = get_userdata( $id );	
		//Call the staff table to get the staff phone no
		$staff_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . EHRM_STAFF . " WHERE user_id=%d", $id ));
		
		if ( ! empty ( $value ) && $value == 'First_name' ) {
			return $user->first_name;
		} elseif( ! empty ( $value ) && $value == 'Last_name' ) {
			return $user->last_name;
		} elseif( ! empty ( $value ) && $value == 'user_login' ) {
			return $user->user_login;	 	
		} elseif( ! empty ( $value ) && $value == 'user_nicename' ) {
			return $user->user_nicename;		
		} elseif( ! empty ( $value ) && $value == 'user_email' ) {
			return $user->user_email;			
		} elseif( ! empty ( $value ) && $value == 'display_name' ) {
			return $user->display_name;			
		} elseif( ! empty ( $value ) && $value == 'Fullname' ) {
			return $user->first_name.' '.$user->last_name;
		} elseif ( ! empty ( $value ) && $value == 'Phone' ) {
			return $staff_data->phone_no;
		}
	}

	/**
	 * Helper function for Staff's Shift data
	 *
	 * @param  int  $staff_id User id.
	 * @return array
	 */
	public static function get_staff_shift( $staff_id ) {
		global $wpdb;
		
		$staffs 		= $wpdb->get_results( 'SELECT * FROM ' . EHRM_STAFF . ' as st WHERE st.user_id= ' . $staff_id);
		$shift_id 		= $staffs[0]->shift_id;
		$staff_table_id = $staffs[0]->id;
		

		$shift_data = $wpdb->get_results( 'SELECT * FROM ' . EHRM_SHIFTS . ' WHERE id=' . $shift_id );		
		$data = array(
			'name'   => $shift_data[0]->name,
			'start'  => $shift_data[0]->start_time,
			'end'    => $shift_data[0]->end_time,
			'late'   => $shift_data[0]->late_time,
			'status' => $shift_data[0]->status,
		);
		return $data;
	}

	/**
	 * Helper function for Total staff's
	 *
	 * @return html
	 */
	public static function get_total_satffs() {
		$staffs      = get_option( 'ehrm_staffs_data' );
		$all_staffs  = get_option( 'ehrm_staffs_data' );
		$attendences = get_option( 'ehrm_staff_attendence_data' );
		$count       = 0;
		$count1      = 0;
		$html        = '';

		if ( ! empty ( $staffs ) ) {
			foreach ( $staffs as $staff_key => $staff ) {
				if ( $staff['status'] == 'Active' ) {
					$count++;
				}
			}
		}

		if ( ! empty ( $all_staffs ) ) {
			foreach ( $all_staffs as $key => $staff ) {
				$user_id     = $staff['ID'];
				if ( ! empty( $attendences ) ) {
					foreach ( $attendences as $key => $attendence ) {
						if ( $attendence['date'] == date( 'Y-m-d' ) && $attendence['staff_id'] == $user_id && ! empty( $attendence['office_in'] ) ) {
							$count1++;
						}
					}
				}
			}
		}

		$html .= '<h2 class="mb-5">'.esc_html( $count ).'</h2>
					<h6 class="card-text">'.esc_html__( 'Logged in', '"employee-&-hr-management"' ).' ( '.esc_html( $count1 ).' )</h6>';

		return $html;

	}

	/**
	 * Helper function for Total Projects
	 *
	 * @return html
	 */
	public static function get_total_projects() {
		$projects = get_option( 'ehrm_projects_data' );
		$count    = 0;
		$html     = '';

		if ( ! empty ( $projects ) ) {
			foreach ( $projects as $project_key => $project ) {
				if ( $project['status'] == 'Active' ) {
					$count++;
				}
			}
		}

		$html .= '<h2 class="mb-5">'.esc_html( $count ).'</h2>';

		return $html;

	}

	/**
	 * Helper function for Total Locations
	 *
	 * @return html
	 */
	public static function get_pending_requests() {
		$locations = get_option( 'ehrm_requests_data' );
		$count     = 0;
		$html      = '';

		if ( ! empty ( $locations ) ) {
			foreach ( $locations as $location_key => $location ) {
				if ( $location['status'] == 'Pending' ) {
					$count++;
				}
			}
		}

		$html .= '<h2 class="mb-5">'.esc_html( $count ).'</h2>';

		return $html;

	}

	/**
	 * Helper function for Total Locations
	 *
	 * @return html
	 */
	public static function get_total_shifts() {
		$shifts = get_option( 'ehrm_shifts_data' );
		$count  = 0;
		$html   = '';

		if ( ! empty ( $shifts ) ) {
			foreach ( $shifts as $shift_key => $shift ) {
				if ( $shift['status'] == 'Active' ) {
					$count++;
				}
			}
		}

		$html .= '<h2 class="mb-5">'.esc_html( $count ).'</h2>';

		return $html;

	}

	/**
	 * Helper function for Previous date
	 *
	 * @param  int  $staff_id User id.
	 * @return string
	 */
	public static function get_staff_last_attendence_date( $staff_id, $start, $last ) {
		$attendences   = get_option( 'ehrm_staff_attendence_data' );
		$all_dates     = self::ehrm_get_date_range( date( 'Y-m-01' ), date( 'Y-m-d' ) );
		$staff_dates   = array();
		
		foreach ( $all_dates as $key => $date ) {
			if ( ! empty ( $attendences ) ) {
				foreach ( $attendences as $key => $attendence ) {
					if ( $attendence['date'] == $date && $attendence['staff_id'] == $staff_id && ! empty( $attendence['office_in'] ) ) {
						array_push( $staff_dates, $date );
					}
				}
			}
		}
		return end( $staff_dates );
	}

	/**
	 * Helper function for Staff's Login location
	 *
	 * @return string
	 */
	public static function get_user_location( $ip ) {

		$request = wp_remote_get( 'http://ip-api.com/php/'.$ip.'?fields=status,message,continent,continentCode,country,countryCode,region,regionName,city,district,zip,lat,lon,timezone,isp,org,as,query' );
		$request = unserialize( $request['body'] );

		if ( ! empty ( $request['city'] ) ) {
			$city = $request['city'];
		} else {
			$city = '';
		}

		if ( ! empty ( $request['regionName'] ) ) {
			$regionName = $request['regionName'];
		} else {
			$regionName = '';
		}

		if ( ! empty ( $request['country'] ) ) {
			$country = $request['country'];
		} else {
			$country = '';
		}

		if ( ! empty ( $request['continent'] ) ) {
			$continent = $request['continent'];
		} else {
			$continent = '';
		}

		return $city.', '.$regionName.', '.$country.', '.$continent;
	}

	/**
	 * Helper function for Staff's Total Absents
	 *
	 * @param int $user_id User id.
	 * @param string $user_id User id.
	 * @param string $user_id User id.
	 * @return array
	 */
	public static function ehrm_total_absents( $user_id = null, $first = null, $last = null, $value = null ) {

		if ( empty ( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$attendences   = get_option( 'ehrm_staff_attendence_data' );
		$all_holidays  = EHRMHelperClass::ehrm_all_holidays();
		$present_days1 = array();
		$present_days2 = array();
		$present_days3 = array();
		$half_days     = self::get_halfdays();
		$off_days      = self::get_offdays();

		if ( empty ( $first ) && empty ( $last ) ) {
			$all_dates = self::ehrm_get_date_range( date( 'Y-m-01' ), date( 'Y-m-d' ) );
		} elseif ( $value == true ) {
			$all_dates = self::ehrm_get_date_range( $first, date( 'Y-m-d' ) );
		} else {
			$all_dates = self::ehrm_get_date_range( $first, $last );
		}

		foreach ( $all_dates as $key => $date ) {
			if ( ! empty ( $attendences ) ) {
				foreach ( $attendences as $key => $attendence ) {
					if ( $attendence['date'] == $date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) ) {
						array_push( $present_days1, $attendence['date'] );
					}
				}
			}
		}

		foreach ( $all_dates as $key => $date ) {
			if ( ! in_array( $date, $present_days1 ) ) {
				if ( ! in_array( $date, $all_holidays ) ) {
					if ( ! in_array( date( 'l', strtotime( $date ) ), $off_days ) ) {
						array_push( $present_days2, $date  );
						array_push( $present_days3, date( self::get_date_format(), strtotime( $date ) ) );
					}
				}
			}
		}

		$data  = array(
			'days'   => sizeof( $present_days2 ).' '.__( 'Days', '"employee-&-hr-management"' ),
			'dates1' => $present_days2,
			'dates2' => $present_days3,
		);

		return $data;
	}
	
	//---Break Time time
	public static function break_time($times) {
		$minutes = 0; //declare minutes either it gives Notice: Undefined variable
		// loop throught all the times
		foreach ($times as $time) {
			list($hour, $minute) = explode(':', $time);
			$minutes += $hour * 60;
			$minutes += $minute;
		}
		$hours = floor($minutes / 60);
		$minutes -= $hours * 60;
		// returns the time already formatted
		return sprintf('%02d.%02d', $hours, $minutes);
	}

	/**
	 * Helper function for Staff's Dashboard
	 *
	 * @return html
	 */
	public static function ehrm_staff_action_clock_buttons() {
		
		//$attendences   = get_option( 'ehrm_staff_attendence_data' );
		$user_id       = get_current_user_id();
		$staff_id	   = EHRM_Helper::fetch_staff_id_stafftable( $user_id );
		$attendence    = EHRM_Helper::staff_attendance_data( $staff_id->id );
		echo "<pre>";
		var_dump($attendence);
		echo "</pre>";
		$html          = '';
		$current_date  = date( 'Y-m-d' );
		$absent_days   = self::ehrm_total_absents();
		$save_settings = get_option( 'ehrm_settings_data' );

		$save_settings = get_option('ehrm_settings_data');

        $officein_text  = isset($save_settings['officein_text']) ? sanitize_text_field($save_settings['officein_text']) : __('Office In', '"employee-&-hr-management"');
        $officeout_text = isset($save_settings['officeout_text']) ? sanitize_text_field($save_settings['officeout_text']) : __('Office Out', '"employee-&-hr-management"');
        $lunchin_text   = isset($save_settings['lunchin_text']) ? sanitize_text_field($save_settings['lunchin_text']) : __('Lunch In', '"employee-&-hr-management"');
        $lunchout_text  = isset($save_settings['lunchout_text']) ? sanitize_text_field($save_settings['lunchout_text']) : __('Lunch Out', '"employee-&-hr-management"');
        $latereson_text = isset($save_settings['latereson_text']) ? sanitize_text_field($save_settings['latereson_text']) : __('Late Reason', '"employee-&-hr-management"');
        $report_text    = isset($save_settings['report_text']) ? sanitize_text_field($save_settings['report_text']) : __('Daily Report', '"employee-&-hr-management"');

		if ( in_array( $current_date, $absent_days['dates1'] ) ) {
			$html .= '<li class="breadcrumb-item" aria-current="page">
		                <button class="btn btn-block btn-lg btn-gradient-success  custom-btn clock-action-btn" data-value="office-in" data-timezone="'.esc_attr( self::get_setting_timezone() ).'">
		                  <i class="mdi mdi-file-import"></i>'.esc_html__( $officein_text, '"employee-&-hr-management"' ).'
						</button>
					  </li>';
		}
		//  echo "<pre>"; var_dump($attendences); echo "</pre>"; die();
		// echo get_current_user_id();
		if ( ! empty ( $attendences ) ) {
			foreach ( $attendences as $key => $attendence ) {
				if( $attendence['staff_id'] == get_current_user_id() ) {
					if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && empty ( $attendence['office_out'] ) ) {
						$html .= '<li class="breadcrumb-item" aria-current="page">
									<button class="btn btn-block btn-lg btn-gradient-danger custom-btn clock-action-btn" data-value="office-out" data-timezone="'.esc_attr( self::get_setting_timezone() ).'">
									  <i class="mdi mdi-file-export"></i>'.esc_html__(  $officeout_text, '"employee-&-hr-management"' ).'
									</button>
								  </li>';
					}
					if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && ! empty ( $attendence['lunch_in'] ) && empty ( $attendence['lunch_out'] ) && empty ( $attendence['office_out'] ) ) {
						$html .= '<li class="breadcrumb-item active" aria-current="page">
									<button class="btn btn-block btn-lg btn-gradient-danger  custom-btn clock-action-btn" data-value="lunch-out" data-timezone="'.esc_attr( self::get_setting_timezone() ).'">
									  <i class="mdi mdi-plus"></i> '.esc_html__( $lunchout_text, '"employee-&-hr-management"' ).'
									</button>
								  </li>';
					} elseif ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && empty ( $attendence['lunch_out'] ) && empty ( $attendence['office_out'] ) ) {
						$html .= '<li class="breadcrumb-item active" aria-current="page">
									<button class="btn btn-block btn-lg btn-gradient-success custom-btn clock-action-btn" data-value="lunch-in" data-timezone="'.esc_attr( self::get_setting_timezone() ).'">
									  <i class="mdi mdi-plus"></i>'.esc_html__( $lunchin_text, '"employee-&-hr-management"' ).'
									</button>
								  </li>';
					}
	
					if ( ! empty ( $save_settings['late_reson'] ) && $save_settings['late_reson'] == 'Yes' ) {
						if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && $attendence['late'] == 'Late' && empty ( $attendence['late_reson'] ) && empty ( $attendence['office_out'] ) ) {
							$html .= '<li class="breadcrumb-item active" aria-current="page">
										<button class="btn btn-block btn-lg btn-gradient-danger custom-btn" data-toggle="modal" data-target="#LateReson" id="late_reson_btn">
										<i class="mdi mdi-plus"></i>'.esc_html__( $latereson_text, '"employee-&-hr-management"' ).'
										</button>
									</li>';
						}
					}
	
					if ( ! empty ( $save_settings['show_report'] ) && $save_settings['show_report'] == 'Yes' ) {
						if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && empty ( $attendence['report'] ) && empty ( $attendence['report']  ) && empty ( $attendence['office_out'] ) ) {
							$html .= '<li class="breadcrumb-item active" aria-current="page">
										<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-toggle="modal" data-target="#DailyReport" id="daily_reportbtn">
										<i class="mdi mdi-plus"></i>'.esc_html__( $report_text, '"employee-&-hr-management"' ).'
										</button>
									</li>';
						}
					}	
							
					$all_breaks = get_option( 'ehrm_breakpoints' );
					// echo "<pre>"; print_r($all_breaks); echo "</pre>"; //die();
					//$html       = '';
					$counter    = 0;
					
					if ( ! empty ( $all_breaks ) ) {
						//echo "<pre>"; var_dump($all_breaks); echo "</pre>";
						$key = 1;
						foreach ( $all_breaks as $key => $breaks ) {							
							if ( $breaks['date'] == date( 'Y-m-d') && $breaks['user_id'] == get_current_user_id() ) {
								$counter = $key;
							}
						}						
						$new_count = $counter;						
						// echo "<pre>"; var_dump($all_breaks[$new_count]); echo "</pre>";
						if ( $all_breaks[$new_count]['date'] == date( 'Y-m-d') && !empty ( $all_breaks[$new_count]['break_in'] ) && empty ( $all_breaks[$new_count]['break_out'] ) ) {
						// if ( $all_breaks[$new_count]['date'] == date( 'Y-m-d') && empty ( $all_breaks[$new_count]['break_out'] ) ) {
							if($attendence['office_out']==""){
							$html .= '<li>
										<button type="submit" id="whrm-breakout-btn-'.esc_attr( $new_count ).'" data-counter="'.esc_attr( $new_count ).'" name="whrm-breakout-btn" class="btn btn-gradient-danger btn-mg whrm-breakout-btn">
											<i class="fa fa-sign-out" aria-hidden="true"></i>'.esc_html__( "Break Out").'
										</button>
									</li>';
							}
						} elseif ( $all_breaks[$new_count]['date'] == date( 'Y-m-d') && !empty ( $all_breaks[$new_count]['break_in'] ) && !empty ( $all_breaks[$new_count]['break_out'] ) ) {
							if($attendence['office_out']==""){
							$html .= '<li>
											<button type="submit" id="whrm-breakin-btn-'.esc_attr( $counter ).'" data-counter="'.esc_attr( $counter ).'" name="whrm-breakin-btn" class="btn btn-gradient-success btn-mg whrm-breakin-btn" >
											<i class="fa fa-sign-in" aria-hidden="true"></i>'.esc_html__( "Break In" ).'
											</button>
										</li>';
						}}
						else {
								if($attendence['office_out'] == "") {
									$html .= '<li>
													<button type="submit" id="whrm-breakin-btn-'.esc_attr( $counter ).'" data-counter="'.esc_attr( $counter ).'" name="whrm-breakin-btn" class="btn btn-gradient-success btn-mg whrm-breakin-btn" >
													<i class="fa fa-sign-in" aria-hidden="true"></i>'.esc_html__( "Break In" ).'
													</button>
											 </li>';
							}
						}	
					}
					else {
						$html .= '<li>
									<button type="submit" id="whrm-breakin-btn-'.esc_attr( $counter ).'" data-counter="'.esc_attr( $counter ).'" name="whrm-breakin-btn" class="btn btn-gradient-success btn-mg whrm-breakin-btn" >
									<i class="fa fa-sign-in" aria-hidden="true"></i>'.esc_html__( "Break In" ).'
									</button>
								</li>';
					}
				 }
			}
			
			$no = 1;
			
			$duration_arr = array();
			if ( ! empty ( $all_breaks ) ) {
				$all_breaks = get_option( 'ehrm_breakpoints' );			
				$mu = count($all_breaks);
				foreach ( $all_breaks as $key => $breaks ) {
					
					if ( $breaks['date'] == date( 'Y-m-d') && $breaks['user_id'] == get_current_user_id() ) {
						//echo $no;
						$html .='<div class="col-md-3 breakpoints-activities-div">
									<ul>
										<p>'.esc_html__( "Break Out").' '. esc_html( $no ).' </p>
										<li>'.esc_html__( "Break In Time:-" ).' '. esc_html( $breaks['break_in'] ).' </li>
										<li>'.esc_html__( "Break Out Time:-" ).' '. esc_html( $breaks['break_out'] ).' </li>
										<li>'.esc_html__( "Break Duration Time:-" ).' '. esc_html( $breaks['break_time'] ).' </li>
									</ul>
								</div>';
					$no++;
					//echo $breaks['break_time'];
					array_push( $duration_arr, $breaks['break_time'] );	
					}
					
					//if($mu==$no) 
					//break; //Break the loop when $count=$brk_val
				}
				
				//echo EHRMHelperClass::break_time($duration_arr);
			}
		}
		return $html;
	}

	/**
	 * Helper function for Displaying Staff's Action
	 *
	 * @return html
	 */
	public static function ehrm_staff_action_activity() {
		$attendences   = get_option( 'ehrm_staff_attendence_data' );
		$user_id       = get_current_user_id();
		$html          = '';
		$current_date  = date( 'Y-m-d' );

		if ( ! empty ( $attendences ) ) {
			foreach ( $attendences as $key => $attendence ) {
				if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && $attendence['late'] != 'Late' ) {
					$html .= '<div class="alert alert-success">
				                <strong>'.esc_html( 'Success!', '"employee-&-hr-management"' ).'</strong> '.esc_html( 'Your Office In Time is', '"employee-&-hr-management"' ).' '.esc_html( date( self::get_time_format(), strtotime( $attendence['office_in'] ) ) ).'
				            </div>';
				} elseif ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && $attendence['late'] == 'Late' ) {
					$html .= '<div class="alert alert-danger">
				                <strong>'.esc_html( 'You are late today!', '"employee-&-hr-management"' ).'</strong> '.esc_html( 'Your Office In Time is', '"employee-&-hr-management"' ).' '.esc_html( date( self::get_time_format(), strtotime( $attendence['office_in'] ) ) ).'
				            </div>';
				}

				if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && ! empty ( $attendence['lunch_in'] ) ) {
					$html .= '<div class="alert alert-success">
				                <strong>'.esc_html( 'Success!', '"employee-&-hr-management"' ).'</strong> '.esc_html( 'Your Lunch In Time is', '"employee-&-hr-management"' ).' '.esc_html( date( self::get_time_format(), strtotime( $attendence['lunch_in'] ) ) ).'
				            </div>';
				}

				if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && ! empty ( $attendence['lunch_out'] ) ) {
					$html .= '<div class="alert alert-success">
				                <strong>'.esc_html( 'Success!', '"employee-&-hr-management"' ).'</strong> '.esc_html( 'Your Lunch Out Time is', '"employee-&-hr-management"' ).' '.esc_html( date( self::get_time_format(), strtotime( $attendence['lunch_out'] ) ) ).'
				            </div>';
				}

				if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && ! empty ( $attendence['office_out'] ) ) {
					$html .= '<div class="alert alert-success">
				                <strong>'.esc_html( 'Success!', '"employee-&-hr-management"' ).'</strong> '.esc_html( 'Your Office Out Time is', '"employee-&-hr-management"' ).' '.esc_html( date( self::get_time_format(), strtotime( $attendence['office_out'] ) ) ).'
				            </div>';
				}

				if ( $attendence['date'] != $current_date && $attendence['staff_id'] == $user_id && empty ( $attendence['office_in'] ) ) {
					$html .= '<p class="no-staff-activity">'.esc_html( 'No activity found.!', '"employee-&-hr-management"' ).'</p>';
				}
			}
		}
		return $html;
	}

	/**
	 * Helper function for Gettting Dates in a range
	 *
	 * @param  string  $first date format value.
	 * @param  string  $last date format value.
	 * @return array
	 */
	public static function ehrm_get_date_range( $first, $last ) {
		$arr  = array();
		$now  = strtotime( $first );
		$last = strtotime( $last );
		$arr = array();
		while( $now <= $last ) {
		  array_push( $arr, date( "Y-m-d", $now ) );
		  $now = strtotime( '+1 day', $now );
		}
		return $arr;
	}

	/**
	 * Helper function for Gettting Dates from current month 
	 *
	 * @return array
	 */
	public static function ehrm_get_current_date_range() {
		$first       = new \DateTime(  date( "Y-m" )."-01" );                                                                
		$first       = $first->format( "Y-m-d" );
		$plusOneYear = date("Y")+1;
		$last        = new \DateTime( $plusOneYear."-12-31" );                                                                   
		$last        = $last->format( "Y-m-d" );
		$all_dates   = self::ehrm_get_date_range( $first, $last );
		return $all_dates;
	}

	/**
	 * Helper function for Displaying Notices on dashboard
	 *
	 * @return html
	 */
	public static function ehrm_display_notices() {
		$all_events  = get_option( 'ehrm_notices_data' );
		$all_dates   = self::ehrm_get_current_date_range();
		$html        = '';

		foreach ( $all_dates as $key => $date ) {
			if ( ! empty ( $all_events  ) ) {
				foreach ( $all_events as $event_key => $event ) {
					if ( $event['date'] == $date ) {
						$html .= '<tr>
				                    <td>'.esc_html( $event["name"] ).'</td>
				                    <td>
				                    	<p class="badge-notice-desc">
				                    		<a href="#" data-toggle="tooltip" data-placement="right" title="'.esc_attr( $event["desc"] ).'" >
				                    			'.esc_html( $event["desc"] ).'
				                    		</a>
				                    	</p>
				                    </td>
				                  </tr>';
					}
				}
			}
		}
		if ( ! empty ( $html ) ) {
			return $html;
		} else {
			$html .= '<tr>
				          <td><p class="no-data-found">'.esc_html( 'No Notice Found.!', '"employee-&-hr-management"' ).' </p><td>
				      </tr><tr></tr>';
			return $html;
		}
	}

	/**
	 * Helper function for Displaying Events on dashboard
	 *
	 * @return html
	 */
	public static function ehrm_display_events() {
		$all_events  = get_option( 'ehrm_events_data' );
		$all_dates   = self::ehrm_get_current_date_range();
		$html        = '';

		foreach ( $all_dates as $key => $date ) {
			if ( ! empty ( $all_events  ) ) {
				foreach ( $all_events as $event_key => $event ) {
					if ( $event['date'] == $date ) {
						$html .= '<tr>
				                    <td>
				                    	<a href="#" data-toggle="tooltip" data-placement="right" title="'.esc_attr( $event["desc"] ).'">
				                    		'.esc_html( $event["name"] ).'
				                    	</a>
				                    </td>
				                    <td>'.esc_html( date( self::get_date_format(), strtotime( $event['date'] ) ) ).'</td>
				                  </tr>';
					}
				}
			}
		}
		if ( ! empty ( $html ) ) {
			return $html;
		} else {
			$html .= '<tr>
				          <td><p class="no-data-found"> '.esc_html( 'No Events Found.!', '"employee-&-hr-management"' ).'</p><td>
				      </tr><tr></tr>';
			return $html;
		}
	}

	/**
	 * Helper function for Displaying Holidays on dashboard
	 *
	 * @return html
	 */
	public static function ehrm_display_holidays() {
		$all_holidays = get_option( 'ehrm_holidays_data' );
		$all_dates   = self::ehrm_get_current_date_range();
		$html        = '';

		foreach ( $all_dates as $key => $date ) {
			if ( ! empty ( $all_holidays  ) ) {
				foreach ( $all_holidays as $holiday_key => $holiday ) {
					if ( $holiday['start'] == $date ) {
						$html .= '<tr>
				                    <td>'.esc_html( $holiday["name"] ).'</td>
				                    <td>'.esc_html( "From ".date( self::get_date_format(), strtotime( $holiday['start'] ) )." to ".date( self::get_date_format(), strtotime( $holiday['to'] ) ) ).'</td>
				                    <td>'.esc_html( $holiday["days"] ).'</td>
				                  </tr>';
					}
				}
			}
		}
		if ( ! empty ( $html ) ) {
			return $html;
		} else {
			$html .= '<tr>
				          <td><p class="no-data-found"> '.esc_html( 'No Holiday Found.!', '"employee-&-hr-management"' ).'</p><td>
				      </td><td></td><td></tr>';
			return $html;
		}
	}

	/**
	 * Helper function for Holidays dates
	 *
	 * @return array
	 */
	public static function ehrm_all_holidays() {
		$all_holidays = get_option( 'ehrm_holidays_data' );
		$all_dates    = self::ehrm_get_current_date_range();
		$holiday_arr1 = array();
		$holiday_arr2 = array();
		foreach ( $all_dates as $key => $date ) {
			if ( ! empty ( $all_holidays  ) ) {
				foreach ( $all_holidays as $holiday_key => $holiday ) {
					if ( $holiday['start'] == $date ) {
						$start_date = $holiday['start'];
						$end_date   = $holiday['to'];
						if ( $end_date == $start_date ) { 
						    array_push( $holiday_arr1, $start_date );
						} else {
							for ( $i=0; $i < $holiday['days'] ; $i++ ) {
								$start_date1 = date( 'Y-m-d', strtotime( $start_date . ' +'.$i.' day' ) );
								array_push( $holiday_arr2, $start_date1 );
							}
						}
					}
				}
			}
		}
		$main_holidays = array_merge( $holiday_arr1, $holiday_arr2 );
		return $main_holidays;
	}

	/**
	 * Helper function for getting Holiday name
	 *
	 * @param  string $date holiday date.
	 * @return string
	 */
	public static function ehrm_get_holiday_name( $date1 ) {
		$all_holidays = get_option( 'ehrm_holidays_data' );
		$all_dates    = self::ehrm_get_current_date_range();
		$holiday_arr1 = array();
		$holiday_arr2 = array();
		foreach ( $all_dates as $key => $date ) {
			if ( ! empty ( $all_holidays  ) ) {
				foreach ( $all_holidays as $holiday_key => $holiday ) {
					if ( $holiday['start'] == $date ) {
						$start_date = $holiday['start'];
						$end_date   = $holiday['to'];
						$name       = $holiday['name'];
						if ( $end_date == $start_date ) { 
							array_push( $holiday_arr1, ["$name" => "$start_date"] );
						} else {
							for ( $i=0; $i < $holiday['days'] ; $i++ ) {
								$start_date1 = date( 'Y-m-d', strtotime( $start_date . ' +'.$i.' day' ) );
								array_push( $holiday_arr2, ["$name" => "$start_date1"] );
							}
						}
					}
				}
			}
		}
		$main_holidays = array_merge( $holiday_arr1, $holiday_arr2 );
		foreach ( $main_holidays as $value ) {
        	foreach( $value as $key => $values ) { 
            	if( $values == $date1 ){
                	return $key;
            	}
        	}
    	}
	}

	/**
	 * Helper function for No of staff's in one location
	 *
	 * @return array
	 */
	public static function ehrm_no_of_staff_location( $location_id ) {
		$staffs  = get_option( 'ehrm_staffs_data' );
		$counter = 0;

		if ( ! empty ( $staffs ) ) {
    		foreach ( $staffs as $key => $staff ) {
    			$locations = unserialize( $staff['locations'] );
    			if ( in_array( $location_id, $locations ) && $staff['status'] == 'Active' ) {
    				$counter++;
    			}
    		}
    	}
    	return $counter;
	}

	/**
	 * Helper function for Staff's Total Attendance
	 *
	 * @param  int $user_id user id.
	 * @return string
	 */
	public static function ehrm_total_attendance( $user_id = null ) {

		if ( empty ( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$attendences = get_option( 'ehrm_staff_attendence_data' );
		$all_dates   = self::ehrm_get_date_range( date( 'Y-m-01' ), date( 'Y-m-t' ) );
		$count_days  = 0;

		foreach ( $all_dates as $key => $date ) {
			if ( ! empty ( $attendences ) ) {
				foreach ( $attendences as $key => $attendence ) {
					if ( $attendence['date'] == $date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) ) {
						$count_days++;
					}
				}
			}
		}
		return $count_days.' '.__( 'Days', '"employee-&-hr-management"' );
	}

	/**
	 * Helper function for Last day working hour
	 *
	 * @param  int $user_id user id.
	 * @return string
	 */
	public static function ehrm_last_day_working_hour( $user_id = null ) {

		if ( empty ( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$attendences   = get_option( 'ehrm_staff_attendence_data' );
		$current_date  = date( 'Y-m-d' );
		$previous_date = date( 'Y-m-d', strtotime( $current_date . ' -1 day' ) );
		$save_settings = get_option( 'ehrm_settings_data' );

		if ( ! empty ( $attendences ) ) {
			foreach ( $attendences as $key => $attendence ) {
				if ( $attendence['date'] == $previous_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) ) {

					if ( ! empty( $attendence['office_out'] ) ) {
						$working_hours = $attendence['working_hour'];
					} elseif ( empty( $attendence['office_out'] ) ) {

						$all_staffs = get_option( 'ehrm_staffs_data' );
						if ( ! empty ( $all_staffs ) ) {
							foreach ( $all_staffs as $key => $staff ) {
								if ( $user_id == $staff['ID'] ) {
									$shift_end     = $staff['shift_end'];
									$shift_end     = date( "H:i:s", strtotime( $shift_end ) );
									$working_hours = self::get_time_difference( $attendence['office_in'], $shift_end );

									/* Lunch duration */
									if ( ! empty( $attendence['lunch_out'] ) && ! empty( $attendence['lunch_in'] ) ) { 
										$lunch_duration = self::get_time_difference( $attendence['lunch_in'], $attendence['lunch_out'] );
									} elseif ( empty( $attendence['lunch_out'] ) && ! empty( $attendence['lunch_in'] ) ) {
										$savesetting    = get_option( 'ehrm_settings_data' );
										$lunch_out      = $savesetting['lunch_end'];
										$lunch_out      = date( "H:i:s", strtotime( $lunch_out ) );
										$lunch_duration = strtotime( $lunch_out ) - strtotime( $attendence['lunch_in'] );
									}

									// /* Actuall working hours */
									if ( ! empty( $lunch_duration ) && $save_settings['lunchtime'] == 'Exclude' ) {
										$working_hours = self::get_time_difference( $working_hours, $lunch_duration );
									} else {
										$working_hours = $working_hours;
									}
								}
							}
						}
					}
				}
			}
		}

		if ( isset( $working_hours ) && ! empty ( $working_hours ) ) {
			return $working_hours.' '.__( 'Hours', '"employee-&-hr-management"' );	
		} else {
			return __( 'No data found', '"employee-&-hr-management"' );
		}
	}

	public static function get_halfdays() {

		$save_settings = get_option( 'ehrm_settings_data' );
		$halfdays      = array();

		if ( $save_settings['monday_status'] == 'Half Day' && ! empty ( $save_settings['monday_status'] ) && isset ( $save_settings['monday_status'] ) ) {
			array_push( $halfdays, 'Monday' );
		}
		if ( $save_settings['tuesday_status'] == 'Half Day' && ! empty ( $save_settings['tuesday_status'] ) && isset ( $save_settings['tuesday_status'] ) ) {
			array_push( $halfdays, 'Tuesday' );
		}
		if ( $save_settings['wednesday_status'] == 'Half Day' && ! empty ( $save_settings['wednesday_status'] ) && isset ( $save_settings['wednesday_status'] ) ) {
			array_push( $halfdays, 'Wednesday' );
		}
		if ( $save_settings['thursday_status'] == 'Half Day' && ! empty ( $save_settings['thursday_status'] ) && isset ( $save_settings['thursday_status'] ) ) {
			array_push( $halfdays, 'Thursday' );
		}
		if ( $save_settings['friday_status'] == 'Half Day' && ! empty ( $save_settings['friday_status'] ) && isset ( $save_settings['friday_status'] ) ) {
			array_push( $halfdays, 'Friday' );
		}
		if ( $save_settings['saturday_status'] == 'Half Day' && ! empty ( $save_settings['saturday_status'] ) && isset ( $save_settings['saturday_status'] ) ) {
			array_push( $halfdays, 'Saturday' );
		}
		if ( $save_settings['sunday_status'] == 'Half Day' && ! empty ( $save_settings['sunday_status'] ) && isset ( $save_settings['sunday_status'] ) ) {
			array_push( $halfdays, 'Sunday' );
		}

		return $halfdays;

	}

	public static function get_offdays() {

		$save_settings = get_option( 'ehrm_settings_data' );
		$offdays       = array();

		if ( $save_settings['monday_status'] == 'Off' && ! empty ( $save_settings['monday_status'] ) && isset ( $save_settings['monday_status'] ) ) {
			array_push( $offdays, 'Monday' );
		}
		if ( $save_settings['tuesday_status'] == 'Off'&& ! empty ( $save_settings['tuesday_status'] ) && isset ( $save_settings['tuesday_status'] ) ) {
			array_push( $offdays, 'Tuesday' );
		}
		if ( $save_settings['wednesday_status'] == 'Off'&& ! empty ( $save_settings['wednesday_status'] ) && isset ( $save_settings['wednesday_status'] ) ) {
			array_push( $offdays, 'Wednesday' );
		}
		if ( $save_settings['thursday_status'] == 'Off'&& ! empty ( $save_settings['thursday_status'] ) && isset ( $save_settings['thursday_status'] ) ) {
			array_push( $offdays, 'Thursday' );
		}
		if ( $save_settings['friday_status'] == 'Off'&& ! empty ( $save_settings['friday_status'] ) && isset ( $save_settings['friday_status'] ) ) {
			array_push( $offdays, 'Friday' );
		}
		if ( $save_settings['saturday_status'] == 'Off'&& ! empty ( $save_settings['saturday_status'] ) && isset ( $save_settings['saturday_status'] ) ) {
			array_push( $offdays, 'Saturday' );
		}
		if ( $save_settings['sunday_status'] == 'Off'&& ! empty ( $save_settings['sunday_status'] ) && isset ( $save_settings['sunday_status'] ) ) {
			array_push( $offdays, 'Sunday' );
		}

		return $offdays;
	}

	/**
	 * Helper function for Working Fulldays
	 *
	 * @param  string  $first date format value.
	 * @param  string  $last date format value.
	 * @return int
	 */
	public static function full_working_days( $start, $last ) {
		$all_dates     = self::ehrm_get_date_range( $start, $last );
		$save_settings = get_option( 'ehrm_settings_data' );
		$all_holidays  = self::ehrm_all_holidays();
		$workdays      = 0;
		$half_days     = self::get_halfdays();
		$off_days      = self::get_offdays();

		foreach ( $all_dates as $key => $date ) {
			if ( ! in_array( $date, $all_holidays ) ) {
				if ( ! in_array( date( 'l', strtotime( $date ) ), $off_days ) && ! in_array( date( 'l', strtotime( $date ) ), $half_days ) ) {
					$workdays++;
				}
			}
		}
		return intval( $workdays );
	}

	/**
	 * Helper function for Working Halfday
	 *
	 * @param  string  $first date format value.
	 * @param  string  $last date format value.
	 * @return int
	 */
	public static function half_working_days( $start, $last ) {
		$all_dates     = self::ehrm_get_date_range( $start, $last );
		$save_settings = get_option( 'ehrm_settings_data' );
		$all_holidays  = self::ehrm_all_holidays();
		$workdays      = 0;
		$half_days     = self::get_halfdays();
		$off_days      = self::get_offdays();

		foreach ( $all_dates as $key => $date ) {
			if ( ! in_array( $date, $all_holidays ) ) {
				if ( ! in_array( date( 'l', strtotime( $date ) ), $off_days ) ) {
					if ( in_array( date( 'l', strtotime( $date ) ), $half_days ) ) {
						$workdays++;
					}
				}
			}
		}
		return intval( $workdays );
	}

	/**
	 * Helper function for get total attendance days
	 *
	 * @param  int  $user_id user id.
	 * @param  string  $first starting date.
	 * @param  string  $last ending date.
	 * @return int
	 */
	public static function ehrm_total_attendance_count( $user_id = null, $first = null, $last = null ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}
		$attendences = get_option( 'ehrm_staff_attendence_data' );

		if ( empty ( $first ) && empty ( $last ) ) {
			$all_dates = self::ehrm_get_date_range( date( 'Y-m-01' ), date( 'Y-m-t' ) );
		} else {
			$all_dates   = self::ehrm_get_date_range( $first, $last );
		}
		
		$count_days = 0;

		foreach ( $all_dates as $key => $date) {
			if ( ! empty( $attendences ) ) {
				foreach ( $attendences as $key => $attendence ) {
					if ( $attendence['date'] == $date && $attendence['staff_id'] == $user_id && ! empty( $attendence['office_in'] ) ) {
						$count_days++;
					}
				}
			}
		}
		return $count_days;
	}

	/**
	 * Helper function for display live status of staff
	 *
	 * @param  int  $user_id user id.
	 * @return array
	 */
	public static function ehrm_staff_today_status( $user_id = null ) {
		$attendences   = get_option( 'ehrm_staff_attendence_data' );
		$html          = '';
		$save_settings = get_option( 'ehrm_settings_data' );

		if ( ! empty( $attendences ) ) {
			foreach ( $attendences as $key => $attendence ) {
				if ( $attendence['date'] == date( 'Y-m-d' ) && $attendence['staff_id'] == $user_id && ! empty( $attendence['office_in'] ) ) {

					$html .= '<td>'.esc_html( date( self::get_time_format(), strtotime( $attendence['office_in'] ) ) ).'</td>';

					if ( ! empty ( $attendence['office_out'] ) ) {
						$html .= '<td>'.esc_html( date( self::get_time_format(), strtotime( $attendence['office_out'] ) ) ).'</td>';
					} else {
						$html .= '<td>---</td>';
					}

					if ( ! empty ( $attendence['lunch_in'] ) ) {
						$html .= '<td>'.esc_html( date( self::get_time_format(), strtotime( $attendence['lunch_in'] ) ) ).'</td>';
					} else {
						$html .= '<td>---</td>';
					}

					if ( ! empty ( $attendence['lunch_out'] ) ) {
						$html .= '<td>'.esc_html( date( self::get_time_format(), strtotime( $attendence['lunch_out'] ) ) ).'</td>';
					} else {
						$html .= '<td>---</td>';
					}

					if ( ! empty ( $attendence['working_hour'] ) ) {
						$html .= '<td>'.esc_html( $attendence['working_hour'] ).'</td>';
					} else {
						$html .= '<td>---</td>';
					}

					$html .= '<td>'.esc_html__( $attendence['late'], '"employee-&-hr-management"'  ).'</td>';
					$html .= '<td>'.esc_html( $attendence['id_address'] ).'</td>';
					$html .= '<td class="none">'.esc_html( $attendence['location'] ).'</td>';
					
					if ( ! empty ( $attendence['office_in'] ) && empty( $attendence['office_out'] ) ) {
						$html .= '<td class="text-green">'.esc_html__( 'Logged In', '"employee-&-hr-management"' ).'</td>';
					} elseif( ! empty ( $attendence['office_in'] ) && ! empty ( $attendence['office_out'] ) ) {
						$html .= '<td class="text-red">'.esc_html__( 'Logged Out', '"employee-&-hr-management"' ).'</td>';
					}
				}
			}
		}
		
		return $html;
	}

	/**
	 * Helper function for Staff's total working hours
	 *
	 * @param  int  $user_id user id.
	 * @param  string  $first date format value.
	 * @param  string  $last date format value.
	 * @return string
	 */
	// public static function get_staff_total_working_hours( $user_id = null, $first, $last ) {
	public static function get_staff_total_working_hours( $first, $last, $user_id = null ) {
		// die();
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}
		$attendences     = get_option( 'ehrm_staff_attendence_data' );
		$all_dates       = self::ehrm_get_date_range( $first, $last );
		$savesetting     = get_option( 'ehrm_settings_data' );
		$all_staffs_data = get_option('ehrm_staffs_data');
		$save_settings   = get_option( 'ehrm_settings_data' );
		$WorkingHour1    = array();
		$WorkingHour2    = array();
		$lunchHours1     = array();
		$lunchHours2     = array();
		$half_days       = self::get_halfdays();
		$off_days        = self::get_offdays();

		/** Staff's data **/
		if ( ! empty( $all_staffs_data ) ) {
			foreach ( $all_staffs_data as $key => $staffs ) {
				if ( $staffs['ID'] == $user_id ) {
					$shift_end = $staffs['shift_end'];
				}
			}
		}

		foreach ( $all_dates as $key => $date ) {
			if ( ! empty( $attendences ) ) {
				foreach ( $attendences as $key => $attendence ) {
					if ( $attendence['date'] == $date && $attendence['staff_id'] == $user_id && ! empty( $attendence['office_in'] ) && empty( $attendence['office_out'] ) ) {

						if ( in_array( date( 'l', strtotime( $date ) ), $half_days ) ) {
							$EndTime = $savesetting['lunch_end'];
						} else {
							$EndTime = $shift_end;
						}

						if ( ! empty( $attendence['lunch_out'] ) && ! empty( $attendence['lunch_in'] ) ) {
							$lunch_duration = EHRMHelperClass::get_time_difference( $attendence['lunch_in'], $attendence['lunch_out'] );
							array_push( $lunchHours1, $lunch_duration );
						} elseif ( empty( $attendence['lunch_out'] ) && ! empty( $attendence['lunch_in'] ) ) {
							$savesetting    = get_option('ehrm_settings_data');
							$lunch_out      = $savesetting['lunch_end'];
							$lunch_duration = strtotime( $lunch_out ) - strtotime( $attendence['lunch_in'] );
							array_push( $lunchHours1, $lunch_duration );
						}

						$WorkHour = self::get_time_difference( $attendence['office_in'], $EndTime );
						array_push( $WorkingHour1, $WorkHour );
						
					} elseif ( $attendence['date'] == $date && $attendence['staff_id'] == $user_id && ! empty( $attendence['office_in'] ) && ! empty( $attendence['office_out'] ) ) {

						if ( ! empty( $attendence['lunch_out'] ) && ! empty( $attendence['lunch_in'] ) ) {
							$lunch_duration = EHRMHelperClass::get_time_difference( $attendence['lunch_in'], $attendence['lunch_out'] );
							array_push( $lunchHours2, $lunch_duration );
						} elseif ( empty( $attendence['lunch_out'] ) && ! empty( $attendence['lunch_in'] ) ) {
							$savesetting    = get_option('ehrm_settings_data');
							$lunch_out      = $savesetting['lunch_end'];
							$lunch_duration = strtotime( $lunch_out ) - strtotime( $attendence['lunch_in'] );
							array_push( $lunchHours2, $lunch_duration );
						}

						$WorkHour = self::get_time_difference( $attendence['office_in'], $attendence['office_out'] );
						array_push( $WorkingHour2, $WorkHour );
					}
				}
			}
		}

		$WorkingHours = array_merge( $WorkingHour1, $WorkingHour2 );
		$LunchHours   = array_merge( $WorkingHour1, $WorkingHour2 );

		$TotalWorkingHours = self::total_salary_time( $WorkingHours );
		$TotalLunchHours   = self::total_salary_time( $LunchHours );

		if ( ! empty( $TotalLunchHours ) && $save_settings['lunchtime'] == 'Exclude') {
			$today_total_hours = strtotime( $TotalWorkingHours ) - strtotime( $TotalLunchHours );
		} else {
			$today_total_hours = $TotalWorkingHours;
		}

		
		return self::total_salary_time( $WorkingHours );
	}

	/**
	 * Helper function for Project based payment:- to find out projects is assigned to this user or not
	 *
	 * @param  int  $staff_id user id.
	 * @return array
	 */
	public static function ehrm_project_based_user_info( $staff_id ) {
		$project_data   	= get_option('ehrm_projects_data');
		if( isset( $project_data  ) && is_array( $project_data  ) ) {
			$no_of_projects 	= count( $project_data );
			$projects_completed = 0;
			$no_of_assigned_pro = 0;
			for( $i=0; $i < $no_of_projects; $i++ ) { 
				$project_assigned_to = unserialize($project_data[$i]['members']);
				if( in_array($staff_id, $project_assigned_to) ) {
					if($project_data[$i]['status'] == 'Completed') {
						$projects_completed += 1;
					}
					$no_of_assigned_pro += 1;
				}
			}
			$data = array( $no_of_assigned_pro, $projects_completed );
		} else {
			$data = '';
		}
		
		return $data;
	}

	/**
	 * Helper function for Project based payment
	 *
	 * @param  int  $staff_id user id.
	 * @return html
	 */
	public static function ehrm_project_based_salary_status( $staff_id ) {
		/**
		 * Get the projects assigned to this user
		 */		
		$final_data     = array();
		$html 		    = '';
		$total_salary   = 0;
		$project_data   = get_option('ehrm_projects_data');		
		$no_of_projects = count( $project_data );
		$j 				= 1;
		for( $i=0; $i < $no_of_projects; $i++ ) {
			$project_assigned_to = unserialize($project_data[$i]['members']);
			if( in_array($staff_id, $project_assigned_to) && $project_data[$i]['status'] == 'Completed') {				
				$final_data[ $project_data[$i]['name'] ] =  $project_data[$i]['project_cost'];
				$total_salary += round($project_data[$i]['project_cost']);
			}
		}		
		foreach( $final_data as $project_title => $project_title_cost ) {
			$html .= '<tr>
						<td>'.$j.'</td>
                        <td>'.$project_title.'</td>
                        <td class="right-td"><span class="info-value-span">'.esc_html( self::get_currency_position_html( $project_title_cost ) ).'</span></td>
                     </tr>';
					 $j++;
		}
		$html .= '<tr>
					<td colspan="2">'.esc_html__( 'Your Total Salary', '"employee-&-hr-management"' ).'</td>
					<td class="right-td"><span class="info-value-span">'.esc_html( self::get_currency_position_html( $total_salary ) ).'</span></td>
			</tr>';
		
		return wp_kses_post( $html );
	}

	/**
	 * Helper function for Actual Salary Status
	 *
	 * @param  string  $first date format value.
	 * @param  string  $last date format value.
	 * @param  string  $type action value.
	 * @param  int  $staff_id user id.
	 * @return html
	 */
	public static function ehrm_exact_salary_status( $start, $last, $type, $staff_id, $return_type = null ) {

		$full_working_days = self::full_working_days( $start, $last );
		$half_working_days = self::half_working_days( $start, $last );
		$total_absents     = self::ehrm_total_absents( $staff_id, $start, $last, true );
		$total_absents     = sizeof( $total_absents['dates1'] );
		$total_presents    = self:: ehrm_total_attendance_count( $staff_id, $start, $last );
		$all_staffs_data   = get_option( 'ehrm_staffs_data' );
		$html              = '';
		$savesetting       = get_option( 'ehrm_settings_data' );

		/** Halfday working hours **/
		$dteStart 	 = new DateTime( $savesetting['halfday_start'] );
		$dteEnd   	 = new DateTime( $savesetting['halfday_end'] );
		$dteDiff  	 = $dteStart->diff( $dteEnd );
		$HalfDayHour = $dteDiff->format( "%H" );

		/** Staff's data **/
		if ( ! empty( $all_staffs_data ) ) {
			foreach ( $all_staffs_data as $key => $staffs ) {
				if ( $staffs['ID'] == $staff_id ) {
					$salary      = $staffs['salary'];
					$shift_start = $staffs['shift_start'];
					$shift_end   = $staffs['shift_end'];
					$all_leaves  = unserialize( $staffs['leave_value'] );
					$size        = sizeof( $all_leaves );
					$leaves      = 0;

					if ( ! empty ( $all_leaves ) ) {
						for ( $i = 0; $i < $size; $i++ ) {
							if($all_leaves[$i] == ""){
								$leaves = $leaves+0;
							}else{
								$leaves = $leaves+$all_leaves[$i];
							}
							
						}
					}
					
					$dteStart 	    = new DateTime( $shift_start );
					$dteEnd   	    = new DateTime( $shift_end );
					$dteDiff  	    = $dteStart->diff( $dteEnd );
					$EstWorkingHour = $dteDiff->format( "%H" );
				}
			}
		}

		if ( $type == 'Monthly' ) {

			/* Total Working Days */
			$total_working_days = ( $full_working_days + $half_working_days ) - $leaves;

			/* Per day salary */
			$PerDaySalary   = $salary / $total_working_days;

			/* Salary upto current date */
			$CurrentSalary  = $PerDaySalary * $total_presents;

			/* Unpaid salary upto current date */
			$UnpaidSalary = $PerDaySalary * $total_absents;

			$html .= '<tr>
                        <td>'.esc_html__( 'Your Monthly Salary', '"employee-&-hr-management"' ).'</td>
                        <td class="right-td"><span class="info-value-span">'.esc_html( self::get_currency_position_html( $salary ) ).'</span></td>
                     </tr>';

			$html .= '<tr>
                        <td>'.esc_html__( 'Total Working days', '"employee-&-hr-management"' ). '</td>
                        <td class="right-td"><span class="info-value-span">' . esc_html( $total_working_days ) . ' ' . esc_html__( 'Days', '"employee-&-hr-management"' ) . '</span></td>
                     </tr>';

            $html .= '<tr>
                        <td>'.esc_html__( 'This Month ('.esc_html( date( "F" ) ).')', '"employee-&-hr-management"' ).' <span class="info-span">'.esc_html__( "as per day Salary", '"employee-&-hr-management"' ).'</span> [ '.esc_html( $salary ).'/'.esc_html( $total_working_days ).' ]</td>
                        <td class="right-td">'.esc_html( self::get_currency_position_html( round( $PerDaySalary ) ) ).'</td>
                     </tr>';

            $html .= '<tr>
                        <td>'.esc_html__( 'This Month ('.esc_html( date( "F" ) ).')', '"employee-&-hr-management"' ).' <span class="info-span">'.esc_html__( "as per day total Salary", '"employee-&-hr-management"' ).'</span> [ '.esc_html( round( $PerDaySalary ) ).' X '.esc_html( date( "Y-m-d" ) ).']</td>
                        <td class="right-td">'.esc_html( self::get_currency_position_html( round( $CurrentSalary ) ) ).'</td>
					 </tr>';

			$html .= '<tr>
						<td>' . esc_html__( 'This Month Total Unpaid salary', '"employee-&-hr-management"' ) . ' [ ' . esc_html( round( $PerDaySalary ) ) . ' X ' . esc_html( $total_absents ) . ']</td>
						<td class="right-td">' . esc_html( self::get_currency_position_html( round( $UnpaidSalary ) ) ) . '</td>
					 </tr>';

			$TotalSalary  = self::get_currency_position_html( round( $PerDaySalary*$total_presents ) );
			$TotalSalary1 = round( $PerDaySalary*$total_presents );

			$html .= '<tr class="final-result-tr">
						<td>' . esc_html__( 'Total Paid Salary as per total attendance', '"employee-&-hr-management"') . ' [ ' . esc_html( round( $PerDaySalary ) ) . ' X ' . esc_html( $total_presents ) . ' ]</td>
						<td class="right-td">' . esc_html( $TotalSalary ) . '</td>
					 </tr>';
						
		} else {
			$TotalEstimateTime = ( $EstWorkingHour * $full_working_days ) + ( $HalfDayHour * $half_working_days );
			$TotalEstimateTime = $TotalEstimateTime - ( $leaves * $EstWorkingHour );
			$PerHourSalary     = number_format( (float) $salary / $TotalEstimateTime, 2, '.', '' );
			$TotalWorkingHours = self::get_staff_total_working_hours( $staff_id, $start, $last );
			$TotalSalary       = self::get_currency_position_html( round( $PerHourSalary * $TotalWorkingHours ) );
			$TotalSalary1      = round( $PerHourSalary * $TotalWorkingHours );

			$html .= '<tr>
						<td>' . esc_html__( 'Estimate total time', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td">' . esc_html( $TotalEstimateTime ) . ' ' . esc_html__( 'Hours', '"employee-&-hr-management"' ) . '</td>
					 </tr>';
			$html .= '<tr>
						<td>' . esc_html__( 'Per hour salary', '"employee-&-hr-management"' ) . ' ( ' . esc_html( round( $salary ) ) . ' / ' . esc_html( $TotalEstimateTime ) . ' )</td>
						<td class="right-td">' . esc_html( self::get_currency_position_html( $PerHourSalary ) ) . '</td>
					 </tr>';
			$html .= '<tr>
						<td>' . esc_html__( 'Calculated salary', '"employee-&-hr-management"' ) . ' ( ' . esc_html( $TotalEstimateTime ) . ' X ' . esc_html( $PerHourSalary ) . ' )</td>
						<td class="right-td">' . esc_html( self::get_currency_position_html( round( $TotalEstimateTime * $PerHourSalary ) ) ) . '</td>
					 </tr>';
			$html .= '<tr>
						<td>' . esc_html__( 'Your total working hours', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td">'.esc_html( $TotalWorkingHours ).' ' . esc_html__( 'Hours', '"employee-&-hr-management"' ) . '</td>
					 </tr>';
			$html .= '<tr class="final-result-tr">
						<td>' . esc_html__( 'Your total salary', '"employee-&-hr-management"' ) . ' ( ' . esc_html( $PerHourSalary ) . ' X '.esc_html( $TotalWorkingHours ).' ' . esc_html__( 'Hours', '"employee-&-hr-management"' ) . ' )</td>
						<td class="right-td">'.esc_html( $TotalSalary ).'</td>
					 </tr>';
		}

		if ( ! empty ( $return_type ) && $return_type == true ) {
			return $TotalSalary1;
		} else {
			return wp_kses_post( $html );
		}
		
	}

	/**
	 * Helper function for Estimate Salary Status
	 *
	 * @param  string  $first date format value.
	 * @param  string  $last date format value.
	 * @param  string  $type action value.
	 * @param  int  $staff_id user id.
	 * @return html
	 */
	public static function ehrm_estimate_salary_status( $start, $last, $type, $staff_id ) {
		$full_working_days = self::full_working_days( $start, $last );
		$half_working_days = self::half_working_days( $start, $last );
		$all_staffs_data   = get_option( 'ehrm_staffs_data' );
		$html              = '';
		$savesetting       = get_option( 'ehrm_settings_data' );
		$currency_symbl    = $savesetting['cur_symbol'];

		/** Halfday working hours **/
		$dteStart 	 = new DateTime( $savesetting['halfday_start'] );
		$dteEnd   	 = new DateTime( $savesetting['halfday_end'] );
		$dteDiff  	 = $dteStart->diff( $dteEnd );
		$HalfDayHour = $dteDiff->format( "%H" );

		/** Staff's data **/
		if ( ! empty( $all_staffs_data ) ) {
			foreach ( $all_staffs_data as $key => $staffs ) {
				if ( $staffs['ID'] == $staff_id ) {
					$salary      = $staffs['salary'];
					$shift_start = $staffs['shift_start'];
					$shift_end   = $staffs['shift_end'];
					$all_leaves  = unserialize( $staffs['leave_value'] );
					$size        = sizeof( $all_leaves );
					$leaves      = 0;

					for ( $i = 0; $i < $size; $i++ ) {
						$leaves = $leaves + $all_leaves[$i];
					}
					
					$dteStart 	    = new DateTime( $shift_start );
					$dteEnd   	    = new DateTime( $shift_end );
					$dteDiff  	    = $dteStart->diff( $dteEnd );
					$EstWorkingHour = $dteDiff->format( "%H" );
				}
			}
		}

		/* Total Working Days */
		$total_working_days = ( $full_working_days + $half_working_days ) - $leaves;

		if ( $type == 'Monthly' ) {

			/* Per day salary */
			$PerDaySalary   = $salary / $total_working_days;

			/* Salary upto current date */
			$CurrentSalary  = $PerDaySalary * ( self::full_working_days( $start, date( 'Y-m-d' ) ) + self::half_working_days( $start, date( 'Y-m-d' ) ) );

			$html .= '<tr>
						<td>' . esc_html__( 'Estimate for this month', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td"></td>
					 </tr>';
			$html .= '<tr>
						<td>' . esc_html__( 'Total days in this month', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td"><span class="info-value-span">'.esc_html( date( 't' ) ). ' ' . esc_html__( 'Days', '"employee-&-hr-management"' ) . '</span></td>
					 </tr>';
			$html .= '<tr>
						<td>' . esc_html__( 'Total Full days', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td"><span class="info-value-span">'.esc_html( $full_working_days ). ' ' . esc_html__( 'Days', '"employee-&-hr-management"' ) . '</span></td>
					 </tr>';
			$html .= '<tr>
						<td>' . esc_html__( 'Total Half days', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td"><span class="info-value-span">'.esc_html( $half_working_days ). ' ' . esc_html__( 'Days', '"employee-&-hr-management"' ) . '</span></td>
					 </tr>';
			$html .= '<tr>
						<td>' . esc_html__( 'Total Working days', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td"><span class="info-value-span">' . esc_html( $total_working_days ) . ' ' . esc_html__('Days', '"employee-&-hr-management"' ) . '</span></td>
					 </tr>';
			$html .= '<tr>
                        <td>' . esc_html__( 'This Month (' . esc_html( date( "F" ) ) . ')', '"employee-&-hr-management"' ) . ' <span class="info-span">' . esc_html__( "as per day Salary", '"employee-&-hr-management"' ) . '</span> [ ' . esc_html( $salary ) . '/' . esc_html( $total_working_days ) . ' ]</td>
                        <td class="right-td">' . esc_html( self::get_currency_position_html( round( $PerDaySalary ) ) ) . '</td>
                     </tr>';
			$html .= '<tr class="final-result-tr">
                        <td>' . esc_html__( 'This Month (' . esc_html( date( "F" ) ) . ')', '"employee-&-hr-management"' ) . ' <span class="info-span">' . esc_html__( "as per day total Salary", '"employee-&-hr-management"' ) . '</span> [ ' . esc_html( round( $PerDaySalary ) ) . ' X ' . esc_html( date( "Y-m-d" ) ) . ']</td>
                        <td class="right-td">' . esc_html( self::get_currency_position_html( round( $CurrentSalary ) ) ) . '</td>
					 </tr>';
		} else {
			$html .= '<tr>
						<td>' . esc_html__( 'Total Working days', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td"><span class="info-value-span">' . esc_html( $total_working_days ) .' '.esc_html__( 'Days', '"employee-&-hr-management"' ).'</span></td>
					 </tr>';
			$html .= '<tr>
						<td>' . esc_html__( 'Total Full days(' . esc_html( $full_working_days ) . ') Working Hours', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td"><span class="info-value-span">( ' . esc_html( $full_working_days ) . ' X '.esc_html( $EstWorkingHour ).' ) = '. esc_html( $EstWorkingHour*$full_working_days ).' '.esc_html__( 'Hours', '"employee-&-hr-management"' ).'</span></td>
					 </tr>';
			$html .= '<tr>
						<td>' . esc_html__( 'Total Half days(' . esc_html( $half_working_days ) . ') Working Hours', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td"><span class="info-value-span">( ' . esc_html( $half_working_days ) . ' X ' . esc_html( $HalfDayHour ) . ' ) = ' . esc_html( $HalfDayHour * $half_working_days ) . ' ' . esc_html__( 'Hours', '"employee-&-hr-management"' ) . '</span></td>
					 </tr>';
			$TotalEstimateTime = ( $EstWorkingHour * $full_working_days ) +( $HalfDayHour * $half_working_days );
			$html .= '<tr>
						<td>' . esc_html__( 'Total Working Hours for this month', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td"><span class="info-value-span">( ' . esc_html( $EstWorkingHour * $full_working_days ) . ' + ' . esc_html( $HalfDayHour * $half_working_days ) . ' ) = ' . esc_html( $TotalEstimateTime ) . ' ' . esc_html__('Hours', '"employee-&-hr-management"' ) . '</span></td>
					 </tr>';
			$html .= '<tr>
						<td>' . esc_html__( 'Total leave Hours for this Month', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td"><span class="info-value-span">( ' . esc_html( $leaves ) . ' X ' . esc_html( $EstWorkingHour ) . ' ) = ' . esc_html( $leaves* $EstWorkingHour ) . ' ' . esc_html__( 'Hours', '"employee-&-hr-management"' ) . '</span></td>
					 </tr>';
			$html .= '<tr class="final-result-tr">
						<td>' . esc_html__( 'Estimated Total Working Hours', '"employee-&-hr-management"' ) . '</td>
						<td class="right-td"><span class="info-value-span">( ' . esc_html( $TotalEstimateTime ) . ' - ' . esc_html( $leaves * $EstWorkingHour ) . ' ) = ' . esc_html( $TotalEstimateTime - ( $leaves * $EstWorkingHour ) ) . ' ' . esc_html__('Hours', '"employee-&-hr-management"' ) . '</span></td>
					 </tr>';
		}
		return wp_kses_post( $html );

	}

	/**
	 * Helper function for Shoot mails to admin on staff actions
	 *
	 * @param int $staff_id user id.
	 * @return void
	 */
	public static function ehrm_shoot_mail_staff_details( $office_in, $office_out, $user_location, $ip, $user_id = null ) {

		if ( empty ( $user_id ) ) {
			$user_id = get_current_user_id();
		}
		// var_dump($user_id);
		$save_settings = get_option( 'ehrm_settings_data' );
		$all_staffs    = get_option( 'ehrm_staffs_data' );
		$login_sub     = $save_settings['office_in_sub'];
		$logout_sub    = $save_settings['office_out_sub'];
		$mail_heading  = $save_settings['mail_heading'];
		$mail_logo     = $save_settings['mail_logo'];
		$mail_api_data = get_option( 'ehrm_notification_api' );
		// echo "<pre>"; 
		// var_dump( $all_staffs);
		// echo "</pre>";
		// die();
		$count_all_staffs = count($all_staffs);
		for( $i = 0; $i < $count_all_staffs; $i++ ) {
			// var_dump($all_staffs[$i]);
			if( $all_staffs[$i]['ID'] == $user_id ) {
				$full_name = $all_staffs[$i]['fullname'];
			}						
		}
		// if ( ! empty ( $all_staffs ) ) {
		// 	foreach ( $all_staffs as $staff_key => $staff ) {
		// 		if ( $staff['ID'] == $user_id ) {
		// 			$full_name = $staff['fullname'];
		// 		} else {
		// 			echo "<pre>";  var_dump($staff_key); echo "</pre>";
		// 			die('Here is the end');
		// 		}
		// 	}
		// }
  
	  	// Admin Mail Address
		$admin_email = get_option( 'admin_email' );
		$headers     = array( 'Content-Type: text/html; charset=UTF-8' );

		if ( ! empty ( $office_out ) ) {
			$subject = $logout_sub;
		} else {
			$subject = $login_sub;
		}

		$message = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>';
		if ( ! empty ( $mail_logo ) ) {
			$message .= '<tr>
							<td bgcolor="#ffffff" align="center">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="wrapper">
									<tbody><tr>
										<td align="center" valign="top" style="padding: 15px 0;" class="logo">
											<a href="'.esc_url( home_url('/') ).'" target="_blank">
												<img alt="Logo" width="100" height="100" src="'.esc_url( $mail_logo ).'" width="60" height="60" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
											</a>
										</td>
									</tr>
								</tbody></table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>';
		}
						
		$message .= '   <tr>
							<td bgcolor="#ffffff" align="center" style="padding: 15px;">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="responsive-table">
									<tbody>
									<tr>
										<td>
											<!-- COPY -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
												<tr>
													<td align="center" style="font-size: 32px; font-family: Helvetica, Arial, sans-serif; font-weight: 700;color: #FF9800; padding-top: 30px;" class="padding-copy">'.esc_html__( $mail_heading, '"employee-&-hr-management"' ).'</td>
												</tr>
											</tbody>
											</table>
										</td>
									</tr>
								</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>
						<tr>
						<td bgcolor="#ffffff" align="center" style="padding: 15px;" class="padding">
							<!--[if (gte mso 9)|(IE)]>
							<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
							<tr>
							<td align="center" valign="top" width="500">
							<![endif]-->
							<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="responsive-table">
								<tbody>
									<tr>
										<td style="padding: 10px 0 0 0; border-top: 1px dashed #aaaaaa;">
											<!-- TWO COLUMNS -->
											<table cellspacing="0" cellpadding="0" border="0" width="100%">
												<tbody><tr>
													<td valign="top" class="mobile-wrapper">
														<!-- LEFT COLUMN -->
														<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="left">
															<tbody><tr>
																<td style="padding: 0 0 10px 0;">
																	<table cellpadding="0" cellspacing="0" border="0" width="100%">
																		<tbody><tr>
																			<td align="left" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">Detail</td>
																		</tr>
																	</tbody></table>
																</td>
															</tr>
														</tbody></table>
														<!-- RIGHT COLUMN -->
														<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="right">
															<tbody><tr>
																<td style="padding: 0 0 10px 0;">
																	<table cellpadding="0" cellspacing="0" border="0" width="100%">
																		<tbody><tr>
																			<td align="right" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">Value</td>
																		</tr>
																	</tbody></table>
																</td>
															</tr>
														</tbody></table>
													</td>
												</tr>
											</tbody></table>
										</td>
									</tr>
									<tr>
										<td style="padding: 10px 0 0 0; border-top: 1px dashed #aaaaaa;">
											<!-- TWO COLUMNS -->
											<table cellspacing="0" cellpadding="0" border="0" width="100%">
												<tbody>
												<tr>
													<td valign="top" class="mobile-wrapper">
														<!-- LEFT COLUMN -->
														<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="left">
															<tbody><tr>
																<td style="padding: 0 0 10px 0;">
																	<table cellpadding="0" cellspacing="0" border="0" width="100%">
																		<tbody><tr>
																			<td align="left" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">'.esc_html__( 'Name', '"employee-&-hr-management"' ).'</td>
																		</tr>
																	</tbody></table>
																</td>
															</tr>
														</tbody></table>
														<!-- RIGHT COLUMN -->
														<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="right">
															<tbody><tr>
																<td style="padding: 0 0 10px 0;">
																	<table cellpadding="0" cellspacing="0" border="0" width="100%">
																		<tbody><tr>
																			<td align="right" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">'.esc_html( $full_name ).'</td>
																		</tr>
																	</tbody></table>
																</td>
															</tr>
														</tbody></table>
													</td>
												</tr>
												<tr>
													<td valign="top" class="mobile-wrapper">
														<!-- LEFT COLUMN -->
														<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="left">
															<tbody><tr>
																<td style="padding: 0 0 10px 0;">
																	<table cellpadding="0" cellspacing="0" border="0" width="100%">
																		<tbody><tr>
																			<td align="left" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">'.esc_html__( 'Date', '"employee-&-hr-management"' ).'</td>
																		</tr>
																	</tbody></table>
																</td>
															</tr>
														</tbody></table>
														<!-- RIGHT COLUMN -->
														<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="right">
															<tbody><tr>
																<td style="padding: 0 0 10px 0;">
																	<table cellpadding="0" cellspacing="0" border="0" width="100%">
																		<tbody><tr>
																			<td align="right" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">'.esc_html( date( self::get_date_format(), strtotime( date( 'Y-m-d' ) ) ) ).'</td>
																		</tr>
																	</tbody></table>
																</td>
															</tr>
														</tbody></table>
													</td>
												</tr>
												<tr>
													<td valign="top" class="mobile-wrapper">
														<!-- LEFT COLUMN -->
														<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="left">
															<tbody><tr>
																<td style="padding: 0 0 10px 0;">
																	<table cellpadding="0" cellspacing="0" border="0" width="100%">
																		<tbody><tr>
																			<td align="left" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">'.esc_html__( 'Office IN', '"employee-&-hr-management"' ).'</td>
																		</tr>
																	</tbody></table>
																</td>
															</tr>
														</tbody></table>
														<!-- RIGHT COLUMN -->
														<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="right">
															<tbody><tr>
																<td style="padding: 0 0 10px 0;">
																	<table cellpadding="0" cellspacing="0" border="0" width="100%">
																		<tbody><tr>
																			<td align="right" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">'.esc_html( date( self::get_time_format(), strtotime( $office_in ) ) ).'</td>
																		</tr>
																	</tbody></table>
																</td>
															</tr>
														</tbody></table>
													</td>
												</tr>';
                            if ( ! empty( $office_out ) ) {
                                $message .= '   <tr>
													<td valign="top" class="mobile-wrapper">
														<!-- LEFT COLUMN -->
														<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="left">
															<tbody><tr>
																<td style="padding: 0 0 10px 0;">
																	<table cellpadding="0" cellspacing="0" border="0" width="100%">
																		<tbody><tr>
																			<td align="left" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">'.esc_html__( 'Office OUT', '"employee-&-hr-management"' ).'</td>
																		</tr>
																	</tbody></table>
																</td>
															</tr>
														</tbody></table>
														<!-- RIGHT COLUMN -->
														<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="right">
															<tbody><tr>
																<td style="padding: 0 0 10px 0;">
																	<table cellpadding="0" cellspacing="0" border="0" width="100%">
																		<tbody><tr>
																			<td align="right" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">'.esc_html( date( self::get_time_format(), strtotime( $office_out ) ) ).'</td>
																		</tr>
																	</tbody></table>
																</td>
															</tr>
														</tbody></table>
													</td>
												</tr>';
							}
							
							$message .= ' <tr>
											<td valign="top" class="mobile-wrapper">
												<!-- LEFT COLUMN -->
												<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="left">
													<tbody><tr>
														<td style="padding: 0 0 10px 0;">
															<table cellpadding="0" cellspacing="0" border="0" width="100%">
																<tbody><tr>
																	<td align="left" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">'.esc_html__( 'User IP', '"employee-&-hr-management"' ).'</td>
																</tr>
															</tbody></table>
														</td>
													</tr>
												</tbody></table>
												<!-- RIGHT COLUMN -->
												<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="right">
													<tbody>
														<tr>
														<td style="padding: 0 0 10px 0;">
															<table cellpadding="0" cellspacing="0" border="0" width="100%">
																<tbody>
																<tr>
																	<td align="right" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">'.esc_html( $ip ).'</td>
																</tr>
																</tbody>
															</table>
														</td>
														</tr>
													</tbody>
												</table>
											</td>
											</tr>
											<tr>
												<td valign="top" class="mobile-wrapper">
														<!-- LEFT COLUMN -->
														<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="left">
															<tbody><tr>
																<td style="padding: 0 0 10px 0;">
																	<table cellpadding="0" cellspacing="0" border="0" width="100%">
																		<tbody><tr>
																			<td align="left" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">'.esc_html__( 'User Location', '"employee-&-hr-management"' ).'</td>
																		</tr>
																	</tbody></table>
																</td>
															</tr>
														</tbody>
													</table>
													<!-- RIGHT COLUMN -->
													<table cellpadding="0" cellspacing="0" border="0" width="47%" style="width: 47%;" align="right">
														<tbody>
														<tr>
															<td style="padding: 0 0 10px 0;">
																<table cellpadding="0" cellspacing="0" border="0" width="100%">
																	<tbody><tr>
																		<td align="right" style="font-family: Arial, sans-serif; color: #333333; font-size: 16px;">'.esc_html( $user_location ).'</td>
																	</tr>
																</tbody></table>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
										</tbody>
										</table>
										</td>
									</tr>
								</tbody>
							</table>
							</td>
						</tr>
					</tbody>
				</table>';

		if ( !empty( $mail_api_data['email_optin'] ) && $mail_api_data['email_optin'] == 'sendgrid' ) {
			$result = self::send_mail_via_sendgrid( $admin_email, 'Administrator', $subject, wp_kses_post( $message ) );
			return $result;
		} else {
			$enquerysend = wp_mail( $admin_email, $subject, $message, $headers );
			if ( $enquerysend ) {
				return __( 'Mail sent', '"employee-&-hr-management"' );
			} else {
				return __( 'Mail not sent', '"employee-&-hr-management"' );
			}
		}
	}

	/**
	 * Helper function for Sfrontend login shortcode html
	 *
	 * @param int $user_id user id.
	 * @return html
	 */
	public static function frondent_login_portal( $user_id = null ) {

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$attendences   = get_option( 'ehrm_staff_attendence_data' );
		$html          = '';
		$current_date  = date( 'Y-m-d' );
		$absent_days   = self::ehrm_total_absents();

		$save_settings = get_option('ehrm_settings_data');

		$officein_text     = isset($save_settings['officein_text']) ? sanitize_text_field($save_settings['officein_text']) : __('Office In', '"employee-&-hr-management"');
		$officeout_text     = isset($save_settings['officeout_text']) ? sanitize_text_field($save_settings['officeout_text']) : __('Office Out', '"employee-&-hr-management"');
		$lunchin_text     = isset($save_settings['lunchin_text']) ? sanitize_text_field($save_settings['lunchin_text']) : __('Lunch In', '"employee-&-hr-management"');
		$lunchout_text     = isset($save_settings['lunchout_text']) ? sanitize_text_field($save_settings['lunchout_text']) : __('Lunch Out', '"employee-&-hr-management"');
		$latereson_text     = isset($save_settings['latereson_text']) ? sanitize_text_field($save_settings['latereson_text']) : __('Late Reason', '"employee-&-hr-management"');
		$report_text     = isset($save_settings['report_text']) ? sanitize_text_field($save_settings['report_text']) : __('Daily Report', '"employee-&-hr-management"');

        if ( in_array( $current_date, $absent_days['dates1'] ) ) {
            $html .= '<div class="form-group">
				<button type="button" class="btn btn-primary btn-lg btn-block clock-action-btn" data-value="office-in" data-timezone="'.esc_attr( self::get_setting_timezone() ).'">'.esc_html__( $officein_text, '"employee-&-hr-management"' ).'</button>
				</div>';
		}

		if ( ! empty ( $attendences ) ) {
            foreach ( $attendences as $key => $attendence ) {
				
				if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && empty ( $attendence['office_out'] ) ) {
                    $html .= '<div class="form-group">
					<button type="button" class="btn btn-danger btn-lg btn-block clock-action-btn" data-value="office-out" data-timezone="'.esc_attr( self::get_setting_timezone() ).'">'.esc_html__( $officeout_text, '"employee-&-hr-management"' ).'</button>
				</div>';
				}
				
                if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty( $attendence['office_in'] ) && ! empty( $attendence['lunch_in'] ) && empty( $attendence['lunch_out'] ) ) {
                    $html .= '<div class="form-group">
								<button type="button" class="btn btn-primary btn-lg btn-block clock-action-btn" data-value="lunch-out" data-timezone="'.esc_attr( self::get_setting_timezone() ).'">'.esc_html__( $lunchout_text, '"employee-&-hr-management"' ).'</button>
							</div>';
                } elseif ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && empty ( $attendence['lunch_in'] ) ) {
					$html .= '<div class="form-group">
								<button type="button" class="btn btn-danger btn-lg btn-block clock-action-btn" data-value="lunch-in" data-timezone="'.esc_attr( self::get_setting_timezone() ).'">'.esc_html__( $lunchin_text, '"employee-&-hr-management"' ).'</button>
							</div>';
				}

                if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty( $attendence['office_in'] ) && $attendence['late'] == 'Late' && empty( $attendence['late_reson'] ) ) {
                    $html .= '<div class="form-group">
								<button type="button" class="btn btn-danger btn-lg btn-block" id="late_reson_btn" data-toggle="modal" data-target="#LateReson">'.esc_html__( $latereson_text, '"employee-&-hr-management"' ).'</button>
							</div>';
                }

                if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty( $attendence['office_in'] ) && empty( $attendence['report'] ) && empty( $attendence['report'] ) ) {
                    $html .= '<div class="form-group">
									<button type="button" class="btn btn-primary btn-lg btn-block" id="daily_reportbtn" data-toggle="modal" data-target="#DailyReport">'.esc_html__( $report_text, '"employee-&-hr-management"' ).'</button>
								</div>';
                }

            }
		}
		return $html;
	}

	/**
	 * Helper function for calculate daily working hours
	 *
	 * @param int $user_in user id.
	 * @param int $month user id.
	 * @param string $date user id.
	 * @return string
	 */
	public static function ehrm_daily_working_hours( $date, $user_id = null ) {

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$attendences  = get_option( 'ehrm_staff_attendence_data' );
		$staffs_data  = get_option( 'ehrm_staffs_data' );
		$savesettings = get_option( 'ehrm_settings_data' );
		$half_days    = self::get_halfdays();
		$off_days     = self::get_offdays();
		$today_total_hours = '';

		if ( ! empty ( $staffs_data ) ) {
			foreach ( $staffs_data as $key => $staff ) {
				if ( $staff['ID'] == $user_id ) {
					$shift_start = $staff['shift_start'];
					$shift_end   = $staff['shift_end'];
				}
			}
		}

		if ( ! empty ( $savesettings  ) ) {
			$end_time  =  $savesettings['halfday_end'];
		}

		foreach ( $attendences as $key => $attendence ) {
			if ( $attendence['date'] == $date && $attendence['staff_id'] == $user_id ) {
				// echo "<pre>"; var_dump($attendence);  echo "</pre>";
				$working_hour = $attendence['working_hour'];

				if ( ! empty( $attendence['lunch_out'] ) && ! empty( $attendence['lunch_in'] ) ) {
					$lunch_duration = EHRMHelperClass::get_time_difference( $attendence['lunch_in'], $attendence['lunch_out'] );
				} elseif ( empty( $attendence['lunch_out'] ) && ! empty( $attendence['lunch_in'] ) ) {
					$savesetting    = get_option('ehrm_settings_data');
					$lunch_out      = $savesetting['lunch_end'];
					$lunch_duration = strtotime( $lunch_out ) - strtotime( $attendence['lunch_in'] );
				}

				$office_in = $attendence['office_in'];
				$day       = date( 'l', strtotime( $date ) );

				if ( empty ( $attendence['office_out'] ) ) {
					if ( ! is_null( $half_days ) ) {

						if ( in_array( $day, $half_days ) ) {
							$strEnd = $end_time; 
						} else {
							$strEnd = $shift_end; 
						}
					} else {
						$strEnd = $shift_end; 
					}
				} else {
					$strEnd = $attendence['office_out']; 
				}		

				$dte_Start    = new DateTime( $office_in ); 
				$dte_End      = new DateTime( $strEnd ); 
				$dte_Diff     = $dte_Start->diff( $dte_End );
				$working_hour = $dte_Diff->format( "%H:%I:%S" );

				if ( ! empty( $lunch_duration ) && $savesettings['lunchtime'] == 'Exclude') {		
					$today_total_hours = strtotime( $working_hour ) - strtotime( $lunch_duration );
					$today_total_hours = date( 'H:i:s' , $today_total_hours ); 
				} else {
					$today_total_hours = $working_hour;
				}
			}
		}
		return $today_total_hours;
	}

	/**
	 * Helper function for calculate daily working hours
	 *
	 * @param int $lat Latitude
	 * @param int $long Longitute
	 * @return string
	 */
	public static function get_address_by_latlong( $lat, $long ) {

		if ( ! empty ( $lat ) && ! empty ( $long ) ) {
			$save_settings = get_option( 'ehrm_settings_data' );
			$address       = __( 'GEO Location is not enable.!!', 'employee-&-hr-management' );
			if ( $save_settings['geo_location'] == 'Yes' && ! empty ( $save_settings['ehrm_gmap_api'] ) ) {
				$geocode = file_get_contents( 'https://maps.google.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&key=' . $save_settings['ehrm_gmap_api'] );
				$output  = json_decode( $geocode );
				$address = $output->results[0]->formatted_address;
				return $address;
			}
		} else {
			return __( 'No Latitute & Longitute Found.!', 'employee-&-hr-management' );
		}
		
	}

	/**
	 * Helper function for show assigned tasks
	 *
	 * @param int $userid userid
	 * @return string
	 */
	public static function get_assigned_tasks() {
		$projects = get_option( 'ehrm_projects_data' );
		$html    = '';
		$no      = 1;
		if ( ! empty ( $projects ) ) {
			foreach ( $projects as $project_key => $project_value ) {
				$tasks = $project_value['tasks'];
				foreach ( $tasks as $task_key => $task_value ) {
					$assign = unserialize( $task_value['assign'] );
					if ( in_array( get_current_user_id(), $assign ) ) {

						$current_date = strtotime( date( 'Y-m-d' ) );
						$duedate_task = strtotime( $task_value['due_start'] );

						if( $duedate_task > $current_date ) { 

							$project_id = $task_value['project_id'];
							$html .= '<tr><td>'.$no.'</td>';
							$html .= '<td>'.$projects[$project_id ]['name'].'</td>';
							$html .= '<td>'.$task_value['name'].'</td>';
							$html .= '<td><span class="'.$task_value['progress'].'">'.$task_value['progress'].'</span></td>';
							$html .= '<td>'.date( 'd M Y', strtotime( $task_value['due_start'] ) ).'</td></tr>';
							$no++;

						}
					}
				}
			}
		}
		return wp_kses_post( $html );
	}

	/**
	 * Helper function for send project detail mails
	 *
	 * @param int $project_id Project id
	 * @return void
	 */
	public static function send_project_detail_mails( $project_id ) {
		$email_content = get_option( 'ehrm_email_new_project_assigned' );
		$projects      = get_option( 'ehrm_projects_data' );
		$email_option  = get_option( 'ehrm_notification_api' );
		$mail_logo     = !empty($email_option['email_logo']) ? $email_option['email_logo'] : '';

		$tags = array(
			'{employee_name}',
			'{project_title}',
			'{created_by}'
		);

		$members = unserialize( $projects[$project_id]['members'] );
		foreach ( $members as $member_key => $value ) {
			$tag_replace = array(
				self::get_current_user_data( $value, 'Fullname' ),
				$projects[$project_id]['name'],
				self::get_current_user_data( $projects[$project_id]['members'], 'Fullname' ),
			);

			$member_email = self::get_current_user_data( $value, 'user_email' );
	
			$body = str_replace( $tags, $tag_replace, $email_content['body'] );

			$admin_email = get_option( 'admin_email' );
			$headers     = array( 'Content-Type: text/html; charset=UTF-8' );
			$subject     = $email_content['subject'];

			$message = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>';
			if ( ! empty ( $mail_logo ) ) {
				$message .= '<tr>
								<td bgcolor="#ffffff" align="center">
									<!--[if (gte mso 9)|(IE)]>
									<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
									<tr>
									<td align="center" valign="top" width="500">
									<![endif]-->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="wrapper">
										<tbody>
											<tr>
												<td align="center" valign="top" style="padding: 15px 0;" class="logo">
													<a href="'.esc_url( home_url('/') ).'" target="_blank">
														<img alt="Logo" width="100" height="100" src="'.esc_url( $mail_logo ).'" width="60" height="60" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
													</a>
												</td>
											</tr>
										</tbody>
									</table>
									<!--[if (gte mso 9)|(IE)]>
									</td>
									</tr>
									</table>
									<![endif]-->
								</td>
							</tr>';
			}
							
			$message .= '   <tr>
								<td bgcolor="#ffffff" align="center" style="padding: 15px;">
									<!--[if (gte mso 9)|(IE)]>
									<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
									<tr>
									<td align="center" valign="top" width="500">
									<![endif]-->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="responsive-table">
										<tbody>
										<tr>
											<td>
												<!-- COPY -->
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tbody>
													<tr>
														<td align="center" style="font-size: 32px; font-family: Helvetica, Arial, sans-serif; font-weight: 700;color: #FF9800; padding-top: 30px;" class="padding-copy">'.esc_html__( $email_content['heading'], '"employee-&-hr-management"' ).'</td>
													</tr>
												</tbody>
												</table>
											</td>
										</tr>
										<tr>
											<!-- COPY -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
													<tr>
														<td align="" valign="top" style="padding: 15px 0;" class="logo">
															<p>'.$body.'<p>
														</td>
													</tr>
												</tbody>
											</table>
										</tr>
									</tbody>
									</table>
									<!--[if (gte mso 9)|(IE)]>
									</td>
									</tr>
									</table>
									<![endif]-->
								</td>
							</tr>
						</tbody>
					</table>';
			if ( !empty($email_option['mail_project_assign']) && $email_option['mail_project_assign'] == 'yes' ) {
				if ( !empty( $email_option['email_optin'] ) && $email_option['email_optin'] == 'sendgrid' ) {
					$result = self::send_mail_via_sendgrid( $member_email, self::get_current_user_data( $value, 'Fullname' ), $subject, wp_kses_post( $message ) );
					return $result;
				} else {
					$enquerysend = wp_mail( $member_email, $subject, wp_kses_post( $message ), $headers );
					if ( $enquerysend ) {
						return __( 'Mail sent', '"employee-&-hr-management"' );
					} else {
						return __( 'Mail not sent', '"employee-&-hr-management"' );
					}
				}
			} else {
				return '';
			}
		}
	}

	/**
	 * Helper function for send task detail mails
	 *
	 * @param int $project_id Project id
	 * @param int $task_id Task id
	 * @return void
	 */
	public static function send_task_detail_mails( $project_id, $task_id ) {
		$email_content = get_option( 'ehrm_email_new_task_assigned' );
		$projects      = get_option( 'ehrm_projects_data' );
		$email_option  = get_option( 'ehrm_notification_api' );
		$mail_logo     = $email_option['email_logo'];

		$tags = array(
			'{employee_name}',
			'{project_title}',
			'{task_title}',
			'{created_by}',
			'{due_date}'
		);

		$members = unserialize( $projects[$project_id]['tasks'][$task_id]['assign'] );
		foreach ( $members as $member_key => $value ) {
			$tag_replace = array(
				self::get_current_user_data( $value, 'Fullname' ),
				$projects[$project_id]['name'],
				$projects[$project_id]['tasks'][$task_id]['name'],
				self::get_current_user_data( $projects[$project_id]['tasks'][$task_id]['staff_id'], 'Fullname' ),
				$projects[$project_id]['tasks'][$task_id]['due_start'],
			);

			$member_email = self::get_current_user_data( $value, 'user_email' );
	
			$body = str_replace( $tags, $tag_replace, $email_content['body'] );

			$admin_email = get_option( 'admin_email' );
			$all_emails  = array( $member_email, $admin_email );
			$headers     = array( 'Content-Type: text/html; charset=UTF-8' );
			$subject     = $email_content['subject'];

			$message = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>';
			if ( ! empty ( $mail_logo ) ) {
				$message .= '<tr>
								<td bgcolor="#ffffff" align="center">
									<!--[if (gte mso 9)|(IE)]>
									<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
									<tr>
									<td align="center" valign="top" width="500">
									<![endif]-->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="wrapper">
										<tbody>
											<tr>
												<td align="center" valign="top" style="padding: 15px 0;" class="logo">
													<a href="'.esc_url( home_url('/') ).'" target="_blank">
														<img alt="Logo" width="100" height="100" src="'.esc_url( $mail_logo ).'" width="60" height="60" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
													</a>
												</td>
											</tr>
										</tbody>
									</table>
									<!--[if (gte mso 9)|(IE)]>
									</td>
									</tr>
									</table>
									<![endif]-->
								</td>
							</tr>';
			}
							
			$message .= '   <tr>
								<td bgcolor="#ffffff" align="center" style="padding: 15px;">
									<!--[if (gte mso 9)|(IE)]>
									<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
									<tr>
									<td align="center" valign="top" width="500">
									<![endif]-->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="responsive-table">
										<tbody>
										<tr>
											<td>
												<!-- COPY -->
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tbody>
													<tr>
														<td align="center" style="font-size: 32px; font-family: Helvetica, Arial, sans-serif; font-weight: 700;color: #FF9800; padding-top: 30px;" class="padding-copy">'.esc_html__( $email_content['heading'], '"employee-&-hr-management"' ).'</td>
													</tr>
												</tbody>
												</table>
											</td>
										</tr>
										<tr>
											<!-- COPY -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
													<tr>
														<td align="" valign="top" style="padding: 15px 0;" class="logo">
															<p>'.$body.'<p>
														</td>
													</tr>
												</tbody>
											</table>
										</tr>
									</tbody>
									</table>
									<!--[if (gte mso 9)|(IE)]>
									</td>
									</tr>
									</table>
									<![endif]-->
								</td>
							</tr>
						</tbody>
					</table>';
			if ( $email_option['mail_task_assign'] == 'yes' ) {
				if ( $email_option['email_optin'] == 'sendgrid' ) {
					$result = self::send_mail_via_sendgrid( $member_email, self::get_current_user_data( $value, 'Fullname' ), $subject, wp_kses_post( $message ) );
					return $result;
				} else {
					$enquerysend = wp_mail( $member_email, $subject, wp_kses_post( $message ), $headers );
					if ( $enquerysend ) {
						return __( 'Mail sent', '"employee-&-hr-management"' );
					} else {
						return __( 'Mail not sent', '"employee-&-hr-management"' );
					}
				}
			}
		}
	}

	/**
	 * Helper function for send Comment detail sms
	 *
	 * @param int $project_id Project id
	 * @param int $task_id Task id
	 * @param int $comment_id Comment id
	 * @return void
	 */
	public static function send_comment_detail_mails( $project_id, $task_id, $comment_id ) {
		$email_content = get_option( 'ehrm_email_new_comment_assigned' );
		$projects      = get_option( 'ehrm_projects_data' );
		$email_option  = get_option( 'ehrm_notification_api' );
		$mail_logo     = $email_option['email_logo'];

		$tags = array(
			'{employee_name}',
			'{project_title}',
			'{task_title}',
			'{created_by}',
			'{comment_text}',
			'{task_due_date}'
		);

		$members    = unserialize( $projects[$project_id]['tasks'][$task_id]['assign'] );
		$created_by = $projects[$project_id]['tasks'][$task_id]['comments'][$comment_id]['fullname'];

		foreach ( $members as $member_key => $value ) {
			$tag_replace = array(
				self::get_current_user_data( $value, 'Fullname' ),
				$projects[$project_id]['name'],
				$projects[$project_id]['tasks'][$task_id]['name'],
				$created_by,
				$projects[$project_id]['tasks'][$task_id]['due_start'],
			);

			$member_email = self::get_current_user_data( $value, 'user_email' );
	
			$body = str_replace( $tags, $tag_replace, $email_content['body'] );

			$admin_email = get_option( 'admin_email' );
			$all_emails  = array( $member_email, $admin_email );
			$headers     = array( 'Content-Type: text/html; charset=UTF-8' );
			$subject     = $email_content['subject'];

			$message = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>';
			if ( ! empty ( $mail_logo ) ) {
				$message .= '<tr>
								<td bgcolor="#ffffff" align="center">
									<!--[if (gte mso 9)|(IE)]>
									<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
									<tr>
									<td align="center" valign="top" width="500">
									<![endif]-->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="wrapper">
										<tbody>
											<tr>
												<td align="center" valign="top" style="padding: 15px 0;" class="logo">
													<a href="'.esc_url( home_url('/') ).'" target="_blank">
														<img alt="Logo" width="100" height="100" src="'.esc_url( $mail_logo ).'" width="60" height="60" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
													</a>
												</td>
											</tr>
										</tbody>
									</table>
									<!--[if (gte mso 9)|(IE)]>
									</td>
									</tr>
									</table>
									<![endif]-->
								</td>
							</tr>';
			}
							
			$message .= '   <tr>
								<td bgcolor="#ffffff" align="center" style="padding: 15px;">
									<!--[if (gte mso 9)|(IE)]>
									<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
									<tr>
									<td align="center" valign="top" width="500">
									<![endif]-->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="responsive-table">
										<tbody>
										<tr>
											<td>
												<!-- COPY -->
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tbody>
													<tr>
														<td align="center" style="font-size: 32px; font-family: Helvetica, Arial, sans-serif; font-weight: 700;color: #FF9800; padding-top: 30px;" class="padding-copy">'.esc_html__( $email_content['heading'], '"employee-&-hr-management"' ).'</td>
													</tr>
												</tbody>
												</table>
											</td>
										</tr>
										<tr>
											<!-- COPY -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
													<tr>
														<td align="" valign="top" style="padding: 15px 0;" class="logo">
															<p>'.$body.'<p>
														</td>
													</tr>
												</tbody>
											</table>
										</tr>
									</tbody>
									</table>
									<!--[if (gte mso 9)|(IE)]>
									</td>
									</tr>
									</table>
									<![endif]-->
								</td>
							</tr>
						</tbody>
					</table>';
			if ( $email_option['mail_comment_assign'] == 'yes' ) {
				if ( $email_option['email_optin'] == 'sendgrid' ) {
					$result = self::send_mail_via_sendgrid( $member_email, self::get_current_user_data( $value, 'Fullname' ), $subject, wp_kses_post( $message ) );
					return $result;
				} else {
					$enquerysend = wp_mail( $member_email, $subject, wp_kses_post( $message ), $headers );
					if ( $enquerysend ) {
						return __( 'Mail sent', '"employee-&-hr-management"' );
					} else {
						return __( 'Mail not sent', '"employee-&-hr-management"' );
					}
				}
			}
		}
	}

	/**
	 * Helper function for new request details mail
	 *
	 * @param int $id request id
	 * @return void
	 */
	public static function send_new_leave_mails( $id ) {
		$email_content = get_option( 'ehrm_email_new_leave_request' );
		$requests      = get_option( 'ehrm_requests_data' );
		$email_option  = get_option( 'ehrm_notification_api' );
		$mail_logo     = $email_option['email_logo'];

		$tags = array(
			'{employee_name}',
			'{date_from}',
			'{date_to}',
			'{no_days}',
			'{reason}'
		);

		$tag_replace = array(
			$requests[$id]['s_name'],
			$requests[$id]['start'],
			$requests[$id]['to'],
			$requests[$id]['days'],
			$requests[$id]['desc'],
		);

		$body        = str_replace( $tags, $tag_replace, $email_content['body'] );
		$admin_email = get_option( 'admin_email' );
		$headers     = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject     = str_replace( $tags, $tag_replace, $email_content['subject'] );

		$message = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>';
		if ( ! empty ( $mail_logo ) ) {
			$message .= '<tr>
							<td bgcolor="#ffffff" align="center">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="wrapper">
									<tbody>
										<tr>
											<td align="center" valign="top" style="padding: 15px 0;" class="logo">
												<a href="'.esc_url( home_url('/') ).'" target="_blank">
													<img alt="Logo" width="100" height="100" src="'.esc_url( $mail_logo ).'" width="60" height="60" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
												</a>
											</td>
										</tr>
									</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>';
		}
						
		$message .= '   <tr>
							<td bgcolor="#ffffff" align="center" style="padding: 15px;">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="responsive-table">
									<tbody>
									<tr>
										<td>
											<!-- COPY -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
												<tr>
													<td align="center" style="font-size: 32px; font-family: Helvetica, Arial, sans-serif; font-weight: 700;color: #FF9800; padding-top: 30px;" class="padding-copy">'.esc_html__( $email_content['heading'], '"employee-&-hr-management"' ).'</td>
												</tr>
											</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<!-- COPY -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td align="" valign="top" style="padding: 15px 0;" class="logo">
														<p>'.$body.'<p>
													</td>
												</tr>
											</tbody>
										</table>
									</tr>
								</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>
					</tbody>
				</table>';
			if ( $email_option['mail_new_leave'] == 'yes' ) {
				if ( $email_option['email_optin'] == 'sendgrid' ) {
					$result = self::send_mail_via_sendgrid( $admin_email, 'Adminstrator', $subject, wp_kses_post( $message ) );
					return $result;
				} else {
					$enquerysend = wp_mail( $admin_email, $subject, wp_kses_post( $message ), $headers );
					if ( $enquerysend ) {
						return __( 'Mail sent', '"employee-&-hr-management"' );
					} else {
						return __( 'Mail not sent', '"employee-&-hr-management"' );
					}
				}
			}
	}

	/**
	 * Helper function for new request details mail
	 *
	 * @param int $id request id
	 * @return void
	 */
	public static function send_approved_leave_mails( $id ) {
		$email_content = get_option( 'ehrm_email_approved_leave_request' );
		$requests      = get_option( 'ehrm_requests_data' );
		$email_option  = get_option( 'ehrm_notification_api' );
		$mail_logo     = $email_option['email_logo'];

		$tags = array(
			'{employee_name}',
			'{date_from}',
			'{date_to}',
			'{no_days}',
			'{reason}'
		);

		$tag_replace = array(
			$requests[$id]['s_name'],
			$requests[$id]['start'],
			$requests[$id]['to'],
			$requests[$id]['days'],
			$requests[$id]['desc'],
		);

		$body        = str_replace( $tags, $tag_replace, $email_content['body'] );
		$admin_email = self::get_current_user_data( $requests[$id]['s_id'], 'user_email' );
		$headers     = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject     = $email_content['subject'];

		$message = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>';
		if ( ! empty ( $mail_logo ) ) {
			$message .= '<tr>
							<td bgcolor="#ffffff" align="center">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="wrapper">
									<tbody>
										<tr>
											<td align="center" valign="top" style="padding: 15px 0;" class="logo">
												<a href="'.esc_url( home_url('/') ).'" target="_blank">
													<img alt="Logo" width="100" height="100" src="'.esc_url( $mail_logo ).'" width="60" height="60" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
												</a>
											</td>
										</tr>
									</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>';
		}
						
		$message .= '   <tr>
							<td bgcolor="#ffffff" align="center" style="padding: 15px;">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="responsive-table">
									<tbody>
									<tr>
										<td>
											<!-- COPY -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
												<tr>
													<td align="center" style="font-size: 32px; font-family: Helvetica, Arial, sans-serif; font-weight: 700;color: #FF9800; padding-top: 30px;" class="padding-copy">'.esc_html__( $email_content['heading'], '"employee-&-hr-management"' ).'</td>
												</tr>
											</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<!-- COPY -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td align="" valign="top" style="padding: 15px 0;" class="logo">
														<p>'.$body.'<p>
													</td>
												</tr>
											</tbody>
										</table>
									</tr>
								</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>
					</tbody>
				</table>';
			if ( $email_option['mail_approv_leave'] == 'yes' ) {
				if ( $email_option['email_optin'] == 'sendgrid' ) {
					$result = self::send_mail_via_sendgrid( $admin_email, self::get_current_user_data( $requests[$id]['s_id'], 'Fullname' ), $subject, wp_kses_post( $message ) );
					return $result;
				} else {
					$enquerysend = wp_mail( $admin_email, $subject, wp_kses_post( $message ), $headers );
					if ( $enquerysend ) {
						return __( 'Mail sent', '"employee-&-hr-management"' );
					} else {
						return __( 'Mail not sent', '"employee-&-hr-management"' );
					}
				}
			}
	}

	/**
	 * Helper function for new request details mail
	 *
	 * @param int $id request id
	 * @return void
	 */
	public static function send_rejected_leave_mails( $id ) {
		$email_content = get_option( 'ehrm_email_rejected_leave_request' );
		$requests      = get_option( 'ehrm_requests_data' );
		$email_option  = get_option( 'ehrm_notification_api' );
		$mail_logo     = $email_option['email_logo'];

		$tags = array(
			'{employee_name}',
			'{date_from}',
			'{date_to}',
			'{no_days}',
		);

		$tag_replace = array(
			$requests[$id]['s_name'],
			$requests[$id]['start'],
			$requests[$id]['to'],
			$requests[$id]['days'],
		);

		$body        = str_replace( $tags, $tag_replace, $email_content['body'] );
		$admin_email = self::get_current_user_data( $requests[$id]['s_id'], 'user_email' );
		$headers     = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject     = $email_content['subject'];

		$message = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>';
		if ( ! empty ( $mail_logo ) ) {
			$message .= '<tr>
							<td bgcolor="#ffffff" align="center">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="wrapper">
									<tbody>
										<tr>
											<td align="center" valign="top" style="padding: 15px 0;" class="logo">
												<a href="'.esc_url( home_url('/') ).'" target="_blank">
													<img alt="Logo" width="100" height="100" src="'.esc_url( $mail_logo ).'" width="60" height="60" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
												</a>
											</td>
										</tr>
									</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>';
		}
						
		$message .= '   <tr>
							<td bgcolor="#ffffff" align="center" style="padding: 15px;">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="responsive-table">
									<tbody>
									<tr>
										<td>
											<!-- COPY -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
												<tr>
													<td align="center" style="font-size: 32px; font-family: Helvetica, Arial, sans-serif; font-weight: 700;color: #FF9800; padding-top: 30px;" class="padding-copy">'.esc_html__( $email_content['heading'], '"employee-&-hr-management"' ).'</td>
												</tr>
											</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<!-- COPY -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td align="" valign="top" style="padding: 15px 0;" class="logo">
														<p>'.$body.'<p>
													</td>
												</tr>
											</tbody>
										</table>
									</tr>
								</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>
					</tbody>
				</table>';
		if ( $email_option['mail_reject_leave'] == 'yes' ) {
			if ( $email_option['email_optin'] == 'sendgrid' ) {
				$result = self::send_mail_via_sendgrid( $admin_email, self::get_current_user_data( $requests[$id]['s_id'], 'Fullname' ), $subject, wp_kses_post( $message ) );
				return $result;
			} else {
				$enquerysend = wp_mail( $admin_email, $subject, wp_kses_post( $message ), $headers );
				if ( $enquerysend ) {
					return __( 'Mail sent', '"employee-&-hr-management"' );
				} else {
					return __( 'Mail not sent', '"employee-&-hr-management"' );
				}
			}
		}
	}

	/**
	 * Helper function for new notice alert
	 *
	 * @return void
	 */
	public static function send_new_notice_mails( $notice_text ) {
		$email_content  = get_option( 'ehrm_email_new_notice_assigned' );
		$all_staffs     = get_option( 'ehrm_staffs_data' );
		$email_option   = get_option( 'ehrm_notification_api' );
		$mail_logo      = $email_option['email_logo'];
		$employee_mails = array();

		$tags = array(
			'{site_name}',
			'{employee_name}',
			'{notice_text}',
		);

		if ( ! empty ( $all_staffs ) ) {
			foreach ( $all_staffs as $key => $staff ) {
				$tag_replace = array(
					get_bloginfo(),
					$staff['ID']['fullname'],
					$notice_text,
				);
				array_push( $employee_mails, self::get_current_user_data( $staff ['ID'], 'user_email' ) );
			}
		}

	
		$body        = str_replace( $tags, $tag_replace, $email_content['body'] );
		$admin_email = $employee_mails;
		$headers     = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject     = $email_content['subject'];

		$message = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>';
		if ( ! empty ( $mail_logo ) ) {
			$message .= '<tr>
							<td bgcolor="#ffffff" align="center">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="wrapper">
									<tbody>
										<tr>
											<td align="center" valign="top" style="padding: 15px 0;" class="logo">
												<a href="'.esc_url( home_url('/') ).'" target="_blank">
													<img alt="Logo" width="100" height="100" src="'.esc_url( $mail_logo ).'" width="60" height="60" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
												</a>
											</td>
										</tr>
									</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>';
		}
						
		$message .= '   <tr>
							<td bgcolor="#ffffff" align="center" style="padding: 15px;">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="responsive-table">
									<tbody>
									<tr>
										<td>
											<!-- COPY -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
												<tr>
													<td align="center" style="font-size: 32px; font-family: Helvetica, Arial, sans-serif; font-weight: 700;color: #FF9800; padding-top: 30px;" class="padding-copy">'.esc_html__( $email_content['heading'], '"employee-&-hr-management"' ).'</td>
												</tr>
											</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<!-- COPY -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td align="" valign="top" style="padding: 15px 0;" class="logo">
														<p>'.$body.'<p>
													</td>
												</tr>
											</tbody>
										</table>
									</tr>
								</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>
					</tbody>
				</table>';
		if ( $email_option['mail_notice'] == 'yes' ) {
			if ( $email_option['email_optin'] == 'sendgrid' ) {
				foreach ( $employee_mails as $key => $value ) {
					$result = self::send_mail_via_sendgrid( $admin_email, $value, $subject, wp_kses_post( $message ) );
					return $result;
				}
			} else {
				$enquerysend = wp_mail( $admin_email, $subject, wp_kses_post( $message ), $headers );
				if ( $enquerysend ) {
					return __( 'Mail sent', '"employee-&-hr-management"' );
				} else {
					return __( 'Mail not sent', '"employee-&-hr-management"' );
				}
			}
		}
	}

	/**
	 * Helper function for new user details awareness mail
	 *
	 * @param int $id User id
	 * @return void
	 */
	public static function send_new_joining_employee_mails( $id ) {
		$email_content  = get_option( 'ehrm_email_new_contact_assigned' );
		$all_staffs     = get_option( 'ehrm_staffs_data' );
		$email_option   = get_option( 'ehrm_notification_api' );
		$mail_logo      = !empty( $email_option['email_logo'] ) ? $email_option['email_logo'] : '';
		$employee_mails = array();

		$tags = array(
			'{employee_name}',
			'{employee_email}',
			'{employee_designation}',
		);

		if ( ! empty ( $all_staffs ) ) {
			foreach ( $all_staffs as $key => $staff ) {
				if ( $staff ['ID'] == $id ) {
					$tag_replace = array(
						$staff['fullname'],
						self::get_current_user_data( [$id], 'user_email' ),
						$staff['desig_name'],
					);
				} else {
					array_push( $employee_mails, self::get_current_user_data( $staff['ID'], 'user_email' ) );
				}
			}
		}

		$body        = str_replace( $tags, $tag_replace, $email_content['body'] );
		$admin_email = $employee_mails;
		$headers     = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject     = $email_content['subject'];

		$message = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>';
		if ( ! empty ( $mail_logo ) ) {
			$message .= '<tr>
							<td bgcolor="#ffffff" align="center">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="wrapper">
									<tbody>
										<tr>
											<td align="center" valign="top" style="padding: 15px 0;" class="logo">
												<a href="'.esc_url( home_url('/') ).'" target="_blank">
													<img alt="Logo" width="100" height="100" src="'.esc_url( $mail_logo ).'" width="60" height="60" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
												</a>
											</td>
										</tr>
									</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>';
		}
						
		$message .= '   <tr>
							<td bgcolor="#ffffff" align="center" style="padding: 15px;">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="responsive-table">
									<tbody>
									<tr>
										<td>
											<!-- COPY -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
												<tr>
													<td align="center" style="font-size: 32px; font-family: Helvetica, Arial, sans-serif; font-weight: 700;color: #FF9800; padding-top: 30px;" class="padding-copy">'.esc_html__( $email_content['heading'], '"employee-&-hr-management"' ).'</td>
												</tr>
											</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<!-- COPY -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td align="" valign="top" style="padding: 15px 0;" class="logo">
														<p>'.$body.'<p>
													</td>
												</tr>
											</tbody>
										</table>
									</tr>
								</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>
					</tbody>
				</table>';
		if ( !empty( $email_option['introduction_mail'] ) && $email_option['introduction_mail'] == 'yes' ) {
			if ( $email_option['email_optin'] == 'sendgrid' ) {
				foreach ( $employee_mails as $key => $value ) {
					$result = self::send_mail_via_sendgrid( $admin_email, $value, $subject, wp_kses_post( $message ) );
					return $result;
				}
			} else {
				$enquerysend = wp_mail( $admin_email, $subject, wp_kses_post( $message ), $headers );
				if ( $enquerysend ) {
					return __( 'Mail sent', '"employee-&-hr-management"' );
				} else {
					return __( 'Mail not sent', '"employee-&-hr-management"' );
				}
			}
		}
	}

	/**
	 * Helper function for new user details awareness mail
	 *
	 * @param int $id User id
	 * @return void
	 */
	public static function send_new_joining_greet_mail( $id ) {
		$email_content  = get_option( 'ehrm_email_employee_welcome' );
		$all_staffs     = get_option( 'ehrm_staffs_data' );
		$email_option   = get_option( 'ehrm_notification_api' );
		if( isset( $email_option['email_logo'] ) && ! empty( $email_option['email_logo'] ) ) {
			$mail_logo      = $email_option['email_logo'];
		} else {
			$mail_logo      = '';
		}		

		$tags = array(
			'{full_name}',
			'{last_name}',
			'{first_name}',
			'{job_title}',
			'{company_name}',
		);
		// echo $id;
		// var_dump($all_staffs);
		// echo "<hr><br><hr>";
		// var_dump( $email_content );
		// echo "<hr><br><hr>";
		// var_dump( $email_option );
		// die();
		if ( ! empty ( $all_staffs ) ) {
			foreach ( $all_staffs as $key => $staff ) {
				if ( $staff ['ID'] == $id ) {
					$tag_replace = array(
						$staff['fullname'],
						$staff['last_name'],
						$staff['first_name'],
						$staff['desig_name'],
						get_bloginfo('name'),
					);
				}
			}
		}

	
		$body        = str_replace( $tags, $tag_replace, $email_content['body'] );
		$Heading     = str_replace( $tags, $tag_replace, $email_content['heading'] );
		$admin_email = self::get_current_user_data( [$id], 'user_email' );
		$headers     = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject     = str_replace( $tags, $tag_replace, $email_content['subject'] );

		$message = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>';
		if ( ! empty ( $mail_logo ) ) {
			$message .= '<tr>
							<td bgcolor="#ffffff" align="center">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="wrapper">
									<tbody>
										<tr>
											<td align="center" valign="top" style="padding: 15px 0;" class="logo">
												<a href="'.esc_url( home_url('/') ).'" target="_blank">
													<img alt="Logo" width="100" height="100" src="'.esc_url( $mail_logo ).'" width="60" height="60" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
												</a>
											</td>
										</tr>
									</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>';
		}
						
		$message .= '   <tr>
							<td bgcolor="#ffffff" align="center" style="padding: 15px;">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
								<tr>
								<td align="center" valign="top" width="500">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 670px;" class="responsive-table">
									<tbody>
									<tr>
										<td>
											<!-- COPY -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
												<tr>
													<td align="center" style="font-size: 32px; font-family: Helvetica, Arial, sans-serif; font-weight: 700;color: #FF9800; padding-top: 30px;" class="padding-copy">'.esc_html__( $Heading, '"employee-&-hr-management"' ).'</td>
												</tr>
											</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<!-- COPY -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td align="" valign="top" style="padding: 15px 0;" class="logo">
														<p>'.$body.'<p>
													</td>
												</tr>
											</tbody>
										</table>
									</tr>
								</tbody>
								</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->
							</td>
						</tr>
					</tbody>
				</table>';
		if ( !empty( $email_option['email_optin'] ) && $email_option['email_optin'] == 'sendgrid' ) {
			// $result = self::send_mail_via_sendgrid( $admin_email, $value, $subject, wp_kses_post( $message ) );
			$result = self::send_mail_via_sendgrid( $admin_email, 'Administrator', $subject, wp_kses_post( $message ) );
			return $result;
		} else {
			$enquerysend = wp_mail( $admin_email, $subject, wp_kses_post( $message ), $headers );
			if ( $enquerysend ) {
				return __( 'Mail sent', '"employee-&-hr-management"' );
			} else {
				return __( 'Mail not sent', '"employee-&-hr-management"' );
			}
		}
	}

	public static function get_column_array() {
		$column_array = array( 'Date', 'Day', 'Office In', 'Office Out', 'Lunch In', 'Lunch Out', 'Working Hours', 'IP', 'Location', 'Late Reason', 'Punctuality', 'Daily Report' );
		return $column_array;
	}

	public static function nexmo_send_sms( $text, $to = null, $from = null ) {

		$api_data = get_option( 'ehrm_notification_api' );

		if ( empty ( $from ) ) {
			if ( ! empty ( $api_data['sms_from_name'] ) ) {
				$from = $api_data['sms_from_name'];
			} else {
				$from = get_bloginfo();
			}
		}

		if ( empty ( $to ) ) {
			$to = $api_data['sms_admin_no'];
		}

		if ( empty ( $api_data['nexmo_api'] ) ) {
			return __( "Nexmo API key not found.!", '"employee-&-hr-management"' );
		} elseif ( empty ( $api_data['nexmo_secret'] ) ) {
			return __( "Nexmo API Secret not found.!!", '"employee-&-hr-management"' );
		}

		$basic  = new \Nexmo\Client\Credentials\Basic( $api_data['nexmo_api'], $api_data['nexmo_secret'] );
		$client = new \Nexmo\Client($basic);

		try {
		    $message = $client->message()->send( array(
			    'to'   => $to,
			    'from' => $from,
			    'text' => $text
			) );
		    $response = $message->getResponseData();

		    if ( $response['messages'][0]['status'] == 0 ) {
		        return __( "The message was sent successfully", '"employee-&-hr-management"' );
		    } else {
		        return __( "The message failed with status: " . $response['messages'][0]['status'] . "\n", '"employee-&-hr-management"' );
		    }
		} catch (Exception $e) {
		    return __( "The message was not sent. Error: " . $e->getMessage() . "\n", '"employee-&-hr-management"' );
		}

	}

	public static function send_mail_via_sendgrid( $receiver_mail, $receiver_name, $subject, $html_content, $sender_mail = null ) {

		$api_data = get_option( 'ehrm_notification_api' );

		if ( ! empty ( $api_data['sendgrid_api'] ) ) {

			if ( empty ( $sender_mail ) ) {
				if ( ! empty ( $api_data['email_from'] ) ) {
					$sender_mail = $api_data['email_from'];
				} else {
					$sender_mail = get_option( 'admin_email' );
				}
			}

			$email = new \SendGrid\Mail\Mail();
			$email->setFrom( $sender_mail, get_bloginfo('name') );
			$email->setSubject( $subject );
			$email->addTo( $receiver_mail, $receiver_name );
			$email->addContent( "text/html", $html_content );
			$sendgrid = new \SendGrid( $api_data['sendgrid_api'] );

			try {

				$response      = $sendgrid->send( $email );
				$response_body = json_decode( $response->body() );
				$response_code = $response->statusCode();
				$email_sent    = ( $response_code >= 200 and $response_code < 300 );

				if ( isset( $response_body->errors[0]->message ) || ! $email_sent ) {
					return __( 'Mail Not Sent Successfully.!!', '"employee-&-hr-management"' );
				} else {
					return __( 'Mail Sent Successfully.!!', '"employee-&-hr-management"' );
				}

			} catch ( Exception $e ) {
				echo 'Caught exception: '. $e->getMessage() ."\n";
			}
		} else {
			return __( 'Sendgrid API key not found.! Please enter sendgrind API key first in Notification options.', '"employee-&-hr-management"' );
		}
	}

	public static function send_mail_via_smtp( $receiver_mail, $receiver_name, $subject, $html_content, $sender_mail = null ) {
		$api_data = get_option( 'ehrm_notification_api' );

		/*
		* Initialize phpmailer class
		*/
		global $phpmailer;

		// (Re)create it, if it's gone missing
		/*if ( ! ( $phpmailer instanceof PHPMailer ) ) {
			require_once ABSPATH . WPINC . '/class-phpmailer.php';
			require_once ABSPATH . WPINC . '/class-smtp.php';
		}
		$mail = new PHPMailer;*/
	require_once(ABSPATH . WPINC . '/PHPMailer/PHPMailer.php');
	require_once(ABSPATH . WPINC . '/PHPMailer/SMTP.php');
	require_once(ABSPATH . WPINC . '/PHPMailer/Exception.php');
	$mail = new PHPMailer\PHPMailer\PHPMailer( true );

		// SMTP configuration
		$mail->CharSet    = 'UTF-8';
		$mail->Encoding   = 'base64';
		$mail->isSMTP();
		$mail->SMTPDebug  = 2; 
		$mail->Host       = $api_data['smtp_hostname'];
		$mail->Port       = (int) $api_data['smtp_port'];
		$mail->SMTPAuth   = true;
		$mail->Username   = $api_data['smtp_user'];
		$mail->Password   = $api_data['smtp_passwd'];
		$mail->SMTPSecure = $api_data['smtp_encription'];
		$message          = '';

		if ( empty( $mail->Host ) || empty( $mail->Username ) || empty( $mail->Password ) || empty( $mail->SMTPSecure ) || empty( $mail->Port ) ) {
			$message = esc_html__( 'Please configure SMTP Settings to send email notifications.', '"employee-&-hr-management"' );
		}

		if ( empty ( $message ) ) {

			if ( empty ( $sender_mail ) ) {
				if ( ! empty ( $api_data['email_from'] ) ) {
					$sender_mail = $api_data['email_from'];
				} else {
					$sender_mail = get_option( 'admin_email' );
				}
			}

			$mail->setFrom( $sender_mail, get_bloginfo('name') );
			$mail->AddAddress( $receiver_mail, $receiver_name );
			$mail->IsHTML( true );
			$mail->Subject = $subject;
			$mail->Body    = $html_content;
			$email_sent    = $mail->Send();

			if ( $email_sent ) {
				return __( 'Mail Sent Successfully.!!', '"employee-&-hr-management"' );
			} else {
				return __( 'Mail Not Sent Successfully.!!', '"employee-&-hr-management"' );
			}

		} else {
			return $message;
		}
		
	}

	public static function get_email_notification_list() {
		$email_notifications = array(
			'mail_new_welcome'    => 'Employee welcome',
			'mail_new_leave'      => 'New Leave request',
			'mail_approv_leave'   => 'Approve Leave request',
			'mail_reject_leave'   => 'Reject Leave Request',
			'mail_project_assign' => 'Assign New Project',
			'mail_task_assign'    => 'Assign New Task',
			'mail_comment_assign' => 'New Comment Created',
			'mail_notice'         => 'Notice created',
			'introduction_mail'   => 'New Employee Introduction Email'
		);
		return $email_notifications;
	}

	public static function get_sms_notification_list() {
		$sms_notifications = array(
			'sms_new_leave'      => 'New Leave request',
			'sms_approv_leave'   => 'Approve Leave request',
			'sms_reject_leave'   => 'Reject Leave Request',
			'sms_project_assign' => 'Assign New Project',
			'sms_task_assign'    => 'Assign New Task',
			'sms_comment_assign' => 'New Comment Created',
			'sms_notice'         => 'Notice created',
		);
		return $sms_notifications;
	}

	/**
	 * Helper function for send project detail sms
	 *
	 * @param int $project_id Project id
	 * @return void
	 */
	public static function send_project_detail_sms( $project_id ) {
		$email_content = get_option( 'ehrm_sms_new_project_assigned' );
		$projects      = get_option( 'ehrm_projects_data' );
		$email_option  = get_option( 'ehrm_notification_api' );

		$tags = array(
			'{employee_name}',
			'{project_title}',
			'{created_by}'
		);

		$members = unserialize( $projects[$project_id]['members'] );
		foreach ( $members as $member_key => $value ) {

			$tag_replace = array(
				self::get_current_user_data( $value, 'Fullname' ),
				$projects[$project_id]['name'],
				self::get_current_user_data( $projects[$project_id]['members'], 'Fullname' ),
			);

			$member_phone = self::get_current_user_data( $value, 'phone' );
			$body         = str_replace( $tags, $tag_replace, $email_content['body'] );
			if ( !empty($email_option['sms_project_assign']) && !empty($email_option['sms_enable']) && $email_option['sms_project_assign'] == 'yes' && $email_option['sms_enable'] != 'no' ) {
				$result       = self::nexmo_send_sms( $body, $member_phone );
				return $result;
			}
		}
	}

	/**
	 * Helper function for send task detail sms
	 *
	 * @param int $project_id Project id
	 * @param int $task_id Task id
	 * @return void
	 */
	public static function send_task_detail_sms( $project_id, $task_id ) {
		$email_content = get_option( 'ehrm_sms_new_task_assigned' );
		$projects      = get_option( 'ehrm_projects_data' );
		$email_option  = get_option( 'ehrm_notification_api' );

		$tags = array(
			'{employee_name}',
			'{project_title}',
			'{task_title}',
			'{created_by}',
			'{due_date}'
		);

		$members = unserialize( $projects[$project_id]['tasks'][$task_id]['assign'] );
		foreach ( $members as $member_key => $value ) {
			$tag_replace = array(
				self::get_current_user_data( $value, 'fullname' ),
				$projects[$project_id]['name'],
				$projects[$project_id]['tasks'][$task_id]['name'],
				self::get_current_user_data( $projects[$project_id]['tasks'][$task_id]['staff_id'], 'fullname' ),
				$projects[$project_id]['tasks'][$task_id]['due_start'],
			);
			$member_phone = self::get_current_user_data( $value, 'phone' );
			$body         = str_replace( $tags, $tag_replace, $email_content['body'] );
			if ( $email_option['sms_task_assign'] == 'yes' && $email_option['sms_enable'] != 'no' ) {
				$result       = self::nexmo_send_sms( $body, $member_phone );
				return $result;
			}
		}
	}

	/**
	 * Helper function for send Comment detail sms
	 *
	 * @param int $project_id Project id
	 * @param int $task_id Task id
	 * @param int $comment_id Comment id
	 * @return void
	 */
	public static function send_comment_detail_sms( $project_id, $task_id, $comment_id ) {
		$email_content = get_option( 'ehrm_sms_new_comment_assigned' );
		$projects      = get_option( 'ehrm_projects_data' );
		$email_option  = get_option( 'ehrm_notification_api' );

		$tags = array(
			'{employee_name}',
			'{project_title}',
			'{task_title}',
			'{created_by}',
			'{comment_text}',
			'{task_due_date}'
		);

		$members    = unserialize( $projects[$project_id]['tasks'][$task_id]['assign'] );
		$created_by = $projects[$project_id]['tasks'][$task_id]['comments'][$comment_id]['fullname'];

		foreach ( $members as $member_key => $value ) {
			$tag_replace = array(
				self::get_current_user_data( $value, 'fullname' ),
				$projects[$project_id]['name'],
				$projects[$project_id]['tasks'][$task_id]['name'],
				$created_by,
				$projects[$project_id]['tasks'][$task_id]['due_start'],
			);

			$member_phone = self::get_current_user_data( $value, 'phone' );
			$body         = str_replace( $tags, $tag_replace, $email_content['body'] );
			if ( $email_option['sms_comment_assign'] == 'yes' && $email_option['sms_enable'] != 'no' ) {
				$result       = self::nexmo_send_sms( $body, $member_phone );
				return $result;
			}

		}
	}

	/**
	 * Helper function for new request details sms
	 *
	 * @param int $id request id
	 * @return void
	 */
	public static function send_new_leave_sms( $id ) {
		$email_content = get_option( 'ehrm_sms_new_leave_request' );
		$requests      = get_option( 'ehrm_requests_data' );
		$email_option  = get_option( 'ehrm_notification_api' );

		$tags = array(
			'{employee_name}',
			'{date_from}',
			'{date_to}',
			'{no_days}',
			'{reason}'
		);

		$tag_replace = array(
			$requests[$id]['s_name'],
			$requests[$id]['start'],
			$requests[$id]['to'],
			$requests[$id]['days'],
			$requests[$id]['desc'],
		);

		$body   = str_replace( $tags, $tag_replace, $email_content['body'] );
		if ( $email_option['sms_new_leave'] == 'yes' && $email_option['sms_enable'] != 'no' ) {
			$result = self::nexmo_send_sms( $body );
			return $result;
		}
	}

	/**
	 * Helper function for new request details mail
	 *
	 * @param int $id request id
	 * @return void
	 */
	public static function send_approved_leave_sms( $id ) {
		$email_content = get_option( 'ehrm_email_approved_leave_request' );
		$requests      = get_option( 'ehrm_requests_data' );
		$email_option  = get_option( 'ehrm_notification_api' );

		$tags = array(
			'{employee_name}',
			'{date_from}',
			'{date_to}',
			'{no_days}',
			'{reason}'
		);

		$tag_replace = array(
			$requests[$id]['s_name'],
			$requests[$id]['start'],
			$requests[$id]['to'],
			$requests[$id]['days'],
			$requests[$id]['desc'],
		);

		$body         = str_replace( $tags, $tag_replace, $email_content['body'] );
		$member_phone = self::get_current_user_data( $requests[$id]['s_id'], 'phone' );
		if ( $email_option['sms_approv_leave'] == 'yes' && $email_option['sms_enable'] != 'no' ) {
			$result = self::nexmo_send_sms( $body, $member_phone );
			return $result;
		}
		
	}

	/**
	 * Helper function for new request details mail
	 *
	 * @param int $id request id
	 * @return void
	 */
	public static function send_rejected_leave_sms( $id ) {
		$email_content = get_option( 'ehrm_email_rejected_leave_request' );
		$requests      = get_option( 'ehrm_requests_data' );
		$email_option  = get_option( 'ehrm_notification_api' );

		$tags = array(
			'{employee_name}',
			'{date_from}',
			'{date_to}',
			'{no_days}',
		);

		$tag_replace = array(
			$requests[$id]['s_name'],
			$requests[$id]['start'],
			$requests[$id]['to'],
			$requests[$id]['days'],
		);

		$body         = str_replace( $tags, $tag_replace, $email_content['body'] );
		$member_phone = self::get_current_user_data( $requests[$id]['s_id'], 'phone' );
		if ( $email_option['sms_reject_leave'] == 'yes' && $email_option['sms_enable'] != 'no' ) {
			$result = self::nexmo_send_sms( $body, $member_phone );
			return $result;
		}

	}

	/**
	 * Helper function for new notice alert
	 *
	 * @return void
	 */
	public static function send_new_notice_sms( $notice_text ) {
		$email_content  = get_option( 'ehrm_email_new_notice_assigned' );
		$all_staffs     = get_option( 'ehrm_staffs_data' );
		$email_option   = get_option( 'ehrm_notification_api' );
		$mail_logo      = $email_option['email_logo'];
		$employee_mails = array();

		$tags = array(
			'{site_name}',
			'{employee_name}',
			'{notice_text}',
		);

		if ( ! empty ( $all_staffs ) ) {
			foreach ( $all_staffs as $key => $staff ) {
				$tag_replace = array(
					get_bloginfo(),
					$staff['ID']['fullname'],
					$notice_text,
				);
				array_push( $employee_phones, self::get_current_user_data( $staff ['ID'], 'phone' ) );
			}
		}

		$body = str_replace( $tags, $tag_replace, $email_content['body'] );
		foreach ( $employee_phones as $key => $value ) {
			if ( $email_option['sms_notice'] == 'yes' && $email_option['sms_enable'] != 'no' ) {
				$result = self::nexmo_send_sms( $body, $value );
				return $result;
			}
		}		
	}

}

?>