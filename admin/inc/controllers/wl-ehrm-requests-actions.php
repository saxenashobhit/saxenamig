<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );

/**
 *  Ajax Action calls for events menu
 */
class RequestsAjaxAction {

    /** Add requests **/
    public static function add_requests() {
        check_ajax_referer('backend_ajax_nonce', 'nounce');

        if ( isset( $_POST['name'] ) && isset( $_POST['start'] ) && isset( $_POST['desc'] ) && isset( $_POST['to'] ) ) {
            $name       = sanitize_text_field( $_POST['name'] );
            $desc       = sanitize_text_field( $_POST['desc'] );
            $start      = sanitize_text_field( $_POST['start'] );
            $to         = sanitize_text_field( $_POST['to'] );
            $staff_id   = sanitize_text_field( $_POST['staff_id'] );
            $staff_name = sanitize_text_field( $_POST['staff_name'] );
            $status     = sanitize_text_field( $_POST['status'] );
            $requests   = get_option( 'ehrm_requests_data' );
            $date1      = date_create( $start );
            $date2      = date_create( $to );
            $diff       = date_diff( $date1, $date2 );
            $leaves     = $diff->format( "%a" );
            $leaves     = $leaves + 1;
            $html       = '';

            $data = array(
                'name'   => $name,
                'desc'   => $desc,
                'date'   => date( 'Y-m-d' ),
                'start'  => $start,
                's_id'   => $staff_id,
                's_name' => $staff_name,
                'to'     => $to,
                'days'   => $leaves,
                'status' => $status,
            );

            if ( empty( $requests ) ) {
                $requests = array();
            }
            array_push( $requests, $data );

            if ( update_option( 'ehrm_requests_data', $requests ) ) {
                $all_requests = get_option( 'ehrm_requests_data' );

                if ( ! empty( $all_requests ) ) {
                    $sno = 1;
                    foreach ( $all_requests as $key => $request ) {

                        if ( $request['name'] == $name ) {
                            EHRMHelperClass::send_new_leave_mails( $key );
                            EHRMHelperClass::send_new_leave_sms( $key );
                        }

                        $html .= '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $request['name'] ).'</td>
				                  	<td>'.esc_html( $request['s_name'] ).'</td>
				                  	<td>'.esc_html( $request['desc'] ).'</td>
				                  	<td>'.esc_html( date( EHRMHelperClass::get_date_format(), strtotime( $request['date'] ) ) ).'</td>
				                  	<td>'.esc_html( "From ".date( EHRMHelperClass::get_date_format(), strtotime( $request['start'] ) )." to ".date( EHRMHelperClass::get_date_format(), strtotime( $request['to'] ) ) ).'</td>
				                  	<td>'.esc_html( $request['days'] ).'</td>
				                  	<td class="status-'.esc_attr( $request['status'] ).'">
                                        <span>'.esc_html( $request['status'] ).'</span>
                                    </td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a request-edit-a" data-request="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-grease-pencil"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a request-delete-a" data-request="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-window-close"></i>
		                          				</a>
		                          			</li>
		                          		</ul>
		                          	</td>
				                </tr>';
                        $sno++;
                    }
                }
                wp_send_json( wp_kses_post( $html ) );
            } else {
                wp_send_json( __( 'Request not added', 'employee-&-hr-management' ) );
            }
        } else {
            wp_send_json( __( 'Something went wrong.!', 'employee-&-hr-management' ) );
        }
        wp_die();
    }

    /** Edit requests **/
    public static function edit_requests() {
        check_ajax_referer('backend_ajax_nonce', 'nounce');

        if ( isset( $_POST['request_key'] ) ) {
            $request_key = sanitize_text_field( $_POST['request_key'] );
            $requests    = get_option( 'ehrm_requests_data' );
            wp_send_json( $requests[$request_key] );
        } else {
            wp_send_json( __( 'Something went wrong.!', 'employee-&-hr-management' ) );
        }
        wp_die();
    }

    /** Update requests **/
    public static function update_requests() {
        check_ajax_referer('backend_ajax_nonce', 'nounce');

        if ( isset( $_POST['name'] ) && isset( $_POST['start'] ) && isset( $_POST['desc'] ) && isset( $_POST['to'] ) ) {
            $key        = sanitize_text_field( $_POST['key'] );
            $name       = sanitize_text_field( $_POST['name'] );
            $desc       = sanitize_text_field( $_POST['desc'] );
            $start      = sanitize_text_field( $_POST['start'] );
            $to         = sanitize_text_field( $_POST['to'] );
            $staff_id   = sanitize_text_field( $_POST['staff_id'] );
            $staff_name = sanitize_text_field( $_POST['staff_name'] );
            $status     = sanitize_text_field( $_POST['status'] );
            $requests   = get_option( 'ehrm_requests_data' );
            $date1      = date_create( $start );
            $date2      = date_create( $to );
            $diff       = date_diff( $date1, $date2 );
            $leaves     = $diff->format( "%a" );
            $leaves     = $leaves + 1;
            $html       = '';

            $role = EHRMHelperClass::ehrm_get_current_user_roles();
            if ( $role == 'administrator'  ) {
                $data = array(
                    'name'   => $name,
                    'desc'   => $desc,
                    'date'   => $requests[$key]['date'],
                    'start'  => $start,
                    's_id'   => $staff_id,
                    's_name' => $staff_name,
                    'to'     => $to,
                    'days'   => $leaves,
                    'status' => $status,
                );

                if ( $status == 'Approved' ) {
                    EHRMHelperClass::send_approved_leave_mails( $key );
                    EHRMHelperClass::send_approved_leave_sms( $key );
                } elseif ( $status == 'Canceled' ) {
                    EHRMHelperClass::send_rejected_leave_mails( $key );
                    EHRMHelperClass::send_rejected_leave_sms( $key );
                }

            } else {
                $data = array(
                    'name'   => $name,
                    'desc'   => $desc,
                    'date'   => $requests[$key]['date'],
                    'start'  => $start,
                    's_id'   => $staff_id,
                    's_name' => $staff_name,
                    'to'     => $to,
                    'days'   => $leaves,
                    'status' => $requests[$key]['status'],
                );
            }

            $requests[$key] = $data;

            if ( update_option( 'ehrm_requests_data', $requests ) ) {
                $all_requests = get_option( 'ehrm_requests_data' );

                if ( ! empty( $all_requests ) ) {
                    $sno = 1;
                    foreach ( $all_requests as $key => $request ) {
                        $html .= '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $request['name'] ).'</td>
				                  	<td>'.esc_html( $request['s_name'] ).'</td>
				                  	<td>'.esc_html( $request['desc'] ).'</td>
				                  	<td>'.esc_html( date( EHRMHelperClass::get_date_format(), strtotime( $request['date'] ) ) ).'</td>
				                  	<td>'.esc_html( "From ".date( EHRMHelperClass::get_date_format(), strtotime( $request['start'] ) )." to ".date( EHRMHelperClass::get_date_format(), strtotime( $request['to'] ) ) ).'</td>
				                  	<td>'.esc_html( $request['days'] ).'</td>
				                  	<td class="status-'.esc_attr( $request['status'] ).'">
                                        <span>'.esc_html( $request['status'] ).'</span>
                                    </td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a request-edit-a" data-request="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-grease-pencil"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a request-delete-a" data-request="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-window-close"></i>
		                          				</a>
		                          			</li>
		                          		</ul>
		                          	</td>
				                </tr>';
                        $sno++;
                    }
                }
                wp_send_json( wp_kses_post( $html ) );
            } else {
                wp_send_json( __( 'Request not added', 'employee-&-hr-management' ) );
            }
        } else {
            wp_send_json( __( 'Something went wrong.!', 'employee-&-hr-management' ) );
        }
        wp_die();
    }

    /** Delete requests **/
    public static function delete_requests() {
        check_ajax_referer('backend_ajax_nonce', 'nounce');

        if ( isset( $_POST['request_key'] ) ) {
            $request_key = sanitize_text_field( $_POST['request_key'] );
            $html        = '';
            $requests    = get_option( 'ehrm_requests_data' );

            unset( $requests[$request_key] );

            if ( update_option( 'ehrm_requests_data', $requests ) ) {
                $all_requests = get_option( 'ehrm_requests_data' );

                if ( ! empty( $all_requests ) ) {
                    $sno = 1;
                    foreach ( $all_requests as $key => $request ) {
                        $html .= '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $request['name'] ).'</td>
				                  	<td>'.esc_html( $request['s_name'] ).'</td>
				                  	<td>'.esc_html( $request['desc'] ).'</td>
				                  	<td>'.esc_html( date( EHRMHelperClass::get_date_format(), strtotime( $request['date'] ) ) ).'</td>
				                  	<td>'.esc_html( "From ".date( EHRMHelperClass::get_date_format(), strtotime( $request['start'] ) )." to ".date( EHRMHelperClass::get_date_format(), strtotime( $request['to'] ) ) ).'</td>
				                  	<td>'.esc_html( $request['days'] ).'</td>
				                  	<td class="status-'.esc_attr( $request['status'] ).'">
                                        <span>'.esc_html( $request['status'] ).'</span>
                                    </td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a request-edit-a" data-request="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-grease-pencil"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a request-delete-a" data-request="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-window-close"></i>
		                          				</a>
		                          			</li>
		                          		</ul>
		                          	</td>
				                </tr>';
                        $sno++;
                    }
                }
                wp_send_json( wp_kses_post( $html ) );
            } else {
                wp_send_json( __( 'Request not deleted', 'employee-&-hr-management' ) );
            }
        } else {
            wp_send_json( __( 'Something went wrong.!', 'employee-&-hr-management' ) );
        }
        wp_die();
    }
}

?>