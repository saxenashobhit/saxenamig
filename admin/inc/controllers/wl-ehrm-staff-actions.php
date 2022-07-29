<?php defined( 'ABSPATH' ) or die(); 
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );
// require_once WL_EHRM_PLUGIN_DIR_PATH . 'includes/constants.php';
require_once WL_EHRM_PLUGIN_DIR_PATH . 'includes/EHRM_Helper.php';

/**
 * Staff ajax action class
 */
class StaffAjaxActions {

	/* Add Fetch User's data Action Call */
	public static function fetch_userdata() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['staff_key'] ) ) {
			$user_id = sanitize_text_field( $_POST['staff_key'] );
			$user    = get_userdata( $user_id );
			if ( ! empty ( $user ) ) {
				$data = array(
					'ID'            => $user->ID,
					'first_name'    => $user->first_name,
					'last_name'     => $user->last_name,
					'user_login'    => $user->user_login,
					'user_nicename' => $user->user_nicename,
					'user_email'    => $user->user_email,
					'display_name'  => $user->display_name,
				);
				wp_send_json( $data );
			} else {
				wp_send_json( 'No data' );
			}

		} else {
			wp_send_json( 'Something went wrong.!' );
		}
		wp_die();
	}

	/* Add Staff Action Call */
	public static function add_staff() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['first'] ) && ! empty ( $_POST['last'] ) && ! empty ( $_POST['email'] ) && ! empty ( $_POST['phone'] ) && ( ! empty ( $_POST['shift'] ) || $_POST['shift'] == '0' ) && ! empty ( $_POST['staff'] ) && ( ! empty ( $_POST['designation'] ) || $_POST['designation'] == '0' ) && ! empty ( $_POST['status'] ) ) {
			$name        = sanitize_text_field( $_POST['name'] );
			$first       = sanitize_text_field( $_POST['first'] );
			$last        = sanitize_text_field( $_POST['last'] );
			$email       = sanitize_text_field( $_POST['email'] );
			$phone       = sanitize_text_field( $_POST['phone'] );
			$shift       = sanitize_text_field( $_POST['shift'] );
			$desig_id    = sanitize_text_field( $_POST['designation'] );
			$pay_type    = isset( $_POST['pay_type'] ) ? sanitize_text_field( $_POST['pay_type'] ) : '';			
			$status      = sanitize_text_field( $_POST['status'] );
			$leave_name  = serialize( $_POST['leave_name'] );
			$leave_value = serialize( $_POST['leave_value'] );
			$user_id     = sanitize_text_field( $_POST['staff'] );
			$exist       = 0;
			$html        = '';
			$salary = 0;
			$staffs = EHRM_Helper::check_staff_existance( $user_id );			
			if( $pay_type ==  'project') {
				$salary = 0;
			} else {
				$salary = sanitize_text_field( $_POST['salary'] );
			}

			if ( ! empty ( $staffs[0]->total > 0 ) ) {        		
				$exist = 1;
        	} else {
				$exist = 0;
			}

        	if ( $exist == 0 ) {							
				//add new staff function
				$result_add_staff = EHRM_Helper::add_new_staff($first, $last, $email, $phone, '', '', $shift, $desig_id, $pay_type, $salary, '', $user_id, 1);				
				
				if( $result_add_staff == 1 ) {

					EHRMHelperClass::send_new_joining_greet_mail( $user_id );
					EHRMHelperClass::send_new_joining_employee_mails( $user_id );
					
					//$all_staffs = get_option( 'ehrm_staffs_data' );
					$staff_data = EHRM_Helper::fetch_the_staff();
					//if ( ! empty ( $all_staffs ) ) {
						$counted_staff = count( $staff_data );
	            		$sno = 1;
						for( $i = 0; $i< $counted_staff; $i++ ) {
							// echo "<pre>"; 
							// print_r($staff_data[$i]);
							// echo "</pre>";
							// foreach ( $all_staffs as $key => $staff ) {

	            			// $leave_name  = unserialize( $staff['leave_name'] );
							// $leave_value = unserialize( $staff['leave_value'] );
							// $leave_no    = sizeof( $leave_name );
	            	
			                $html .= '<tr>
					                	<td>'.esc_html( $sno ).'</td>
					                  	<td>'.esc_html( $staff['fullname'] ).'</td>
					                  	<td>'.esc_html( $staff['email'] ).'</td>
					                  	<td>'.esc_html( $staff['phone'] ).'</td>
					                  	<td>'.esc_html( $staff['shift_name'] . '( ' . date( EHRMHelperClass::get_time_format(), strtotime( $staff['shift_start'] ) ) . ' to ' . date( EHRMHelperClass::get_time_format(), strtotime( $staff['shift_end'] ) ) . ' )').'</td>
					                  	<td>'.esc_html( $staff['desig_name'] ).'</td>';

							$html .= '<td>';
					        for ( $i = 0; $i < $leave_no; $i++ ) {
	                            $html .= '<span>'.$leave_name[$i].' ( '.$leave_value[$i].')</br></br></span>';
	                        }
							$html .= '</td>
										<td>'.esc_html( $staff['salary'] ).'</td>
					                  	<td>'.esc_html( $staff['status'] ).'</td>
					                  	<td class="designation-action-tools">
			                          		<ul class="designation-action-tools-ul">
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" title="Edit" class="designation-action-tools-a staff-edit-a" data-staff="'.esc_attr( $key ).'">
			                          					<i class="mdi mdi-grease-pencil"></i>
			                          				</a>
			                          			</li>
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" title="Delete" class="designation-action-tools-a staff-delete-a" data-staff="'.esc_attr( $key ).'">
			                          					<i class="mdi mdi-window-close"></i>
			                          				</a>
			                          			</li>
			                          		</ul>
			                          	</td>
					                </tr>';
			                $sno++; 
			            } 
			        //}
					$status  = 'success';
					$message = __( 'Staff added successfully!', 'employee-&-hr-management' );
					$content = wp_kses_post( $html );
				} else {
					$status  = 'error';
					$message = __( 'Staff not added!', 'employee-&-hr-management' );
					$content = '';
				}
			} else {
				$status  = 'error';
				$message = __( 'Staff already exist.!!', 'employee-&-hr-management' );
				$content = '';
			}
		} else {

			if ( empty ( $_POST['name'] ) ) {
				$message = __( 'Please enter name.!', 'employee-&-hr-management' );		
			} elseif ( empty ( $_POST['first'] ) ) {
				$message = __( 'Please enter first name.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['last'] ) ) {
				$message = __( 'Please enter last name.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['email'] ) ) {
				$message = __( 'Please enter email.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['phone'] ) ) {
				$message = __( 'Please enter phone no.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['shift'] ) && $_POST['shift'] != '0' ) {
				$message = __( 'Please select shift.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['designation'] )  && $_POST['designation'] != '0'  ) {
				$message = __( 'Please select designation.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['salary'] ) ) {
				$message = __( 'Please enter salary.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['status'] ) ) {
				$message = __( 'Please select status.!', 'employee-&-hr-management' );
			} else {
				$message = '';
			}

			$status  = 'error';
			$content = '';
		}
		$return = array(
			'status'  => $status,
			'message' => $message,
			'content' => $content
		);

		wp_send_json( $return );
		wp_die();
	}

	/* Edit Staff Action Call */
	public static function edit_staff() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['staff_key'] ) ) {
			$key    = sanitize_text_field( $_POST['staff_key'] );
			// $staffs = get_option( 'ehrm_staffs_data' );
			// $names  = json_encode( unserialize( $staffs[$key]['leave_name'] ) );
            // $values = json_encode( unserialize( $staffs[$key]['leave_value'] ) );
			$staffs = EHRM_Helper::fetch_the_staff_edit( $key );
			/*$data = array(
					'ID'          => $staffs[$key]['ID'],
					'fullname'    => $staffs[$key]['fullname'],
					'first_name'  => $staffs[$key]['first_name'],
					'last_name'   => $staffs[$key]['last_name'],
					'username'    => $staffs[$key]['username'],
					'email'       => $staffs[$key]['email'],
					'phone'       => $staffs[$key]['phone'],
					'salary'      => $staffs[$key]['salary'],
					'shift_id'    => $staffs[$key]['shift_id'],
					'shift_name'  => $staffs[$key]['shift_name'],
					'shift_start' => $staffs[$key]['shift_start'],
					'shift_end'   => $staffs[$key]['shift_end'],
					'desig_id'    => $staffs[$key]['desig_id'],
					'pay_type'    => $staffs[$key]['pay_type'],
					'deparment'   => $staffs[$key]['deparment'],
					'desig_name'  => $staffs[$key]['desig_name'],
					'desig_color' => $staffs[$key]['desig_color'],
					'leave_name'  => $names,
					'leave_value' => $values,
					'status'      => $staffs[$key]['status'],
				);*/
				$data = array(
					'ID'          => $staffs->user_id,
					'id'		  => $staffs->id,
					'fullname'    => $staffs->fullname,
					'first_name'  => $staffs->first_name,
					'last_name'   => $staffs->last_name,
					'username'    => $staffs->user_login,
					'email'       => $staffs->email,
					'phone'       => $staffs->phone_no,
					'salary'      => $staffs->amount,
					'shift_id'    => $staffs->shift_id,
					'shift_name'  => $staffs->shift_name,
					'shift_start' => $staffs->shift_start,
					'shift_end'   => $staffs->shift_end,
					'desig_id'    => $staffs->designation_id,
					'pay_type'    => $staffs->pay_type,
					'deparment'   => $staffs->deparment,
					'desig_name'  => $staffs->desig_name,
					'desig_color' => $staffs->desig_color,
					'leave_name'  => '',
					'leave_value' => '',
					'status'      => $staffs->status,
				);
			wp_send_json( $data );
		} else {
			wp_send_json( __( 'Something went wrong.!', 'employee-&-hr-management' ) );
		}
		wp_die();
	}

	/* Update Staff Action Call */
	public static function update_staff() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['first'] ) && ! empty ( $_POST['last'] ) && ! empty ( $_POST['email'] ) && ! empty ( $_POST['phone'] ) && ( ! empty ( $_POST['shift'] ) || $_POST['shift'] == '0' ) && ! empty ( $_POST['staff'] ) && ( ! empty ( $_POST['designation'] ) || $_POST['designation'] == '0' ) && ! empty ( $_POST['salary'] ) && ! empty ( $_POST['status'] ) ) {
			$staff_key   = sanitize_text_field( $_POST['staff_key'] ); // wp_user table id
			$user_id_ct  = sanitize_text_field( $_POST['user_id_ct'] ); 
			$name        = sanitize_text_field( $_POST['name'] );
			$first       = sanitize_text_field( $_POST['first'] );
			$last        = sanitize_text_field( $_POST['last'] );
			$email       = sanitize_text_field( $_POST['email'] );
			$phone       = sanitize_text_field( $_POST['phone'] );
			$shift       = sanitize_text_field( $_POST['shift'] );
			$desig_id    = sanitize_text_field( $_POST['designation'] );
			$pay_type    = isset( $_POST['pay_type'] ) ? sanitize_text_field( $_POST['pay_type'] ) : '';
			$salary      = sanitize_text_field( $_POST['salary'] );
			$status      = sanitize_text_field( $_POST['status'] );
			$leave_name  = serialize( $_POST['leave_name'] );
			$leave_value = serialize( $_POST['leave_value'] );
			$user_id     = sanitize_text_field( $_POST['staff'] );
			$staffs      = get_option( 'ehrm_staffs_data' );
			$fullname    = $first.' '.$last;
			$shifts      = get_option( 'ehrm_shifts_data' );
			$designation = get_option( 'ehrm_designations_data' );
			$html        = '';

		
			$result = EHRM_Helper::update_staff($first, $last, $email, $phone, '', '', $shift, $desig_id, $pay_type, $salary, '', $staff_key, '', $user_id_ct);
			
			if ( $result == 1 ) {
				
				$all_staffs = get_option( 'ehrm_staffs_data' );
				$staff_data = EHRM_Helper::fetch_the_staff();
	
				$counted_staff = count( $staff_data );
				$sno = 1;
					
				if ( ! empty ( $counted_staff ) ) {
					
            		$sno = 1;
					for( $i = 0; $i< $counted_staff; $i++ ) {

            			// $leave_name  = unserialize( $staff['leave_name'] );
						// $leave_value = unserialize( $staff['leave_value'] );
						// $leave_no    = sizeof( $leave_name );
            	
		                /*$html .= '<tr>
				                	<td>'.esc_html( $sno ).'</td>
				                  	<td>'.esc_html( $staff_data[$i]->first_name ).'</td>
				                  	<td>'.esc_html( $staff_data[$i]->email ).'</td>
				                  	<td>'.esc_html( $staff_data[$i]->phone_no ).'</td>
				                  	<td>'.esc_html( $staff_data[$i]->shift_name . '( ' . date( EHRMHelperClass::get_time_format(), strtotime( $staff_data['shift_start'] ) ) . ' to ' . date( EHRMHelperClass::get_time_format(), strtotime( $staff['shift_end'] ) ) . ' )').'</td>
				                  	<td>'.esc_html( $staff_data[$i]->$first_name ).'</td>';*/
						$html .= '<tr>
									  <td>'.esc_html( $sno ).'</td>
										<td>'.esc_html( $staff_data[$i]->first_name ).'</td>
										<td>'.esc_html( $staff_data[$i]->email ).'</td>
										<td>'.esc_html( $staff_data[$i]->phone_no ).'</td>
										<td>'.esc_html( $staff_data[$i]->shift_id ).'</td>
										<td>'.esc_html( $staff_data[$i]->designation_id ).'</td>';
						$html .= '<td>';
						// for ($i = 0; $i < $leave_no; $i++) {
						// 	$html .= '<span>' . $leave_name[$i] . ' ( ' . $leave_value[$i] . ')</br></br></span>';
						// }
						$html .= '</td>
									<td>'.esc_html( $staff_data[$i]->amount ).'</td>
				                  	<td>'.esc_html( $staff_data[$i]->status ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" title="Edit" class="designation-action-tools-a staff-edit-a" data-staff="'.esc_attr( $staff_data[$i]->id ).'">
		                          					<i class="mdi mdi-grease-pencil"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" title="Delete" class="designation-action-tools-a staff-delete-a" data-staff="'.esc_attr( $staff_data[$i]->id ).'">
		                          					<i class="mdi mdi-window-close"></i>
		                          				</a>
		                          			</li>
		                          		</ul>
		                          	</td>
				                </tr>';
		                $sno++; 
		            } 
		        }
				$status  = 'success';
				$message = __( 'Staff updated successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Staff not updated!', 'employee-&-hr-management' );
				$content = '';
			}
		 } else {
			if ( empty ( $_POST['name'] ) ) {
				$message = __( 'Please enter name.!', 'employee-&-hr-management' );		
			} elseif ( empty ( $_POST['first'] ) ) {
				$message = __( 'Please enter first name.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['last'] ) ) {
				$message = __( 'Please enter last name.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['email'] ) ) {
				$message = __( 'Please enter email.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['phone'] ) ) {
				$message = __( 'Please enter phone no.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['shift'] ) && $_POST['shift'] != '0' ) {
				$message = __( 'Please select shift.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['designation'] )  && $_POST['designation'] != '0'  ) {
				$message = __( 'Please select designation.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['salary'] ) ) {
				$message = __( 'Please enter salary.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['status'] ) ) {
				$message = __( 'Please select status.!', 'employee-&-hr-management' );
			}

			$status  = 'error';
			$content = '';
		}
		$return = array(
			'status'  => $status,
			'message' => $message,
			'content' => $content
		);

		wp_send_json( $return );
		wp_die();
	}

	/* Delete Staff Action Call */
	public static function delete_staff() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['staff_key'] ) ) {
			$staff_key = sanitize_text_field( $_POST['staff_key'] );
			$staffs    = get_option( 'ehrm_staffs_data' );
			$html      = '';

			unset( $staffs[$staff_key] );

			if ( update_option( 'ehrm_staffs_data', $staffs ) ) {
				
				$all_staffs = get_option( 'ehrm_staffs_data' );

				if ( ! empty ( $all_staffs ) ) {
            		$sno = 1;
            		foreach ( $all_staffs as $key => $staff ) {

            			$leave_name  = unserialize( $staff['leave_name'] );
						$leave_value = unserialize( $staff['leave_value'] );
						$leave_no    = sizeof( $leave_name );
            	
		                $html .= '<tr>
				                	<td>'.esc_html( $sno ).'</td>
				                  	<td>'.esc_html( $staff['fullname'] ).'</td>
				                  	<td>'.esc_html( $staff['email'] ).'</td>
				                  	<td>'.esc_html( $staff['phone'] ).'</td>
				                  	<td>'.esc_html( $staff['shift_name'] . '( ' . date( EHRMHelperClass::get_time_format(), strtotime( $staff['shift_start'] ) ) . ' to ' . date( EHRMHelperClass::get_time_format(), strtotime( $staff['shift_end'] ) ) . ' )').'</td>
				                  	<td>'.esc_html( $staff['desig_name'] ).'</td>';

						$html .= '<td>';
						for ($i = 0; $i < $leave_no; $i++) {
							$html .= '<span>' . $leave_name[$i] . ' ( ' . $leave_value[$i] . ')</br></br></span>';
						}
						$html .= '</td>
									<td>'.esc_html( $staff['salary'] ).'</td>
				                  	<td>'.esc_html( $staff['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" title="Edit" class="designation-action-tools-a staff-edit-a" data-staff="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-grease-pencil"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" title="Delete" class="designation-action-tools-a staff-delete-a" data-staff="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-window-close"></i>
		                          				</a>
		                          			</li>
		                          		</ul>
		                          	</td>
				                </tr>';
		                $sno++; 
		            } 
		        }
				$status  = 'success';
				$message = __( 'Staff deleted successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Staff not deleted!', 'employee-&-hr-management' );
				$content = '';
			}
		} else {
			$status  = 'error';
			$message = __( 'Something went wrong.!', 'employee-&-hr-management' );
			$content = '';
		}
		$return = array(
			'status'  => $status,
			'message' => $message,
			'content' => $content
		);

		wp_send_json( $return );
		wp_die();
	}

}

?>