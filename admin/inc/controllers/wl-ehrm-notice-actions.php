<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );

/**
 *  Ajax Action calls for notices menu
 */
class NoticeAjaxAction {
	
	public static function add_notices() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['desc'] ) && ! empty ( $_POST['status'] ) ) {
			$name    = sanitize_text_field( $_POST['name'] );
			$desc    = sanitize_text_field( $_POST['desc'] );
			$status  = sanitize_text_field( $_POST['status'] );
			$notices = get_option( 'ehrm_notices_data' );
			$html    = '';

			$data = array(
				'name'   => $name,
				'desc'   => $desc,
				'status' => $status,
				'date'   => date( 'Y-m-d' ),
			);

			if ( empty ( $notices ) ) {
				$notices = array();
			}
			array_push( $notices, $data );

			if ( update_option( 'ehrm_notices_data', $notices ) ) {

				EHRMHelperClass::send_new_notice_mails( $desc );
				EHRMHelperClass::send_new_notice_sms( $desc );

				$all_notices = get_option( 'ehrm_notices_data' );

				if ( ! empty ( $all_notices ) ) {
            		$sno = 1;
            		foreach ( $all_notices as $key => $notice ) {
            	
		                $html .= '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $notice['name'] ).'</td>
				                  	<td class="badge-desc">'.esc_html( $notice['desc'] ).'</td>
				                  	<td>'.esc_html( $notice['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a notice-edit-a" data-notice="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-grease-pencil"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a notice-delete-a" data-notice="'.esc_attr( $key ).'">
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
				$message = __( 'Notice added successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Notice not added!', 'employee-&-hr-management' );
				$content = '';
			}

		} else {
			if ( empty ( $_POST['name'] ) ) {
				$message = __( 'Please enter name.!', 'employee-&-hr-management' );		
			} elseif ( empty ( $_POST['desc'] ) ) {
				$message = __( 'Please enter description.!', 'employee-&-hr-management' );
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

	/* Edit notices Action Call */
	public static function edit_notices() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['key'] ) ) {
			$key          = sanitize_text_field( $_POST['key'] );
			$notices = get_option( 'ehrm_notices_data' );

			$data = array(
				'name'   => $notices[$key]['name'],
				'desc'   => $notices[$key]['desc'],
				'status' => $notices[$key]['status'],
				'date'   => $notices[$key]['date'],
			);
			wp_send_json( $data );

		} else {
			wp_send_json( __( 'Something went wrong.!', 'employee-&-hr-management' ) );
		}
		wp_die();
	}

	/* Update notices Action Call */
	public static function update_notices() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['desc'] ) & ! empty ( $_POST['status'] ) ) {
			$name   = sanitize_text_field( $_POST['name'] );
			$desc   = sanitize_text_field( $_POST['desc'] );
			$status = sanitize_text_field( $_POST['status'] );
			$key    = sanitize_text_field( $_POST['key'] );
			$notices = get_option( 'ehrm_notices_data' );

			$data = array(
				'name'   => $name,
				'desc'   => $desc,
				'status' => $status,
				'date'   => $notices[$key]['date'],
			);

			$notices[$key] = $data;

			if ( update_option( 'ehrm_notices_data', $notices ) ) {

				$notices = get_option( 'ehrm_notices_data' );

				if ( ! empty ( $notices ) ) {
            		$sno = 1;
            		foreach ( $notices as $key => $notice ) {
            	
		                $html .= '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $notice['name'] ).'</td>
				                  	<td class="badge-desc">'.esc_html( $notice['desc'] ).'</td>
				                  	<td>'.esc_html( $notice['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a notice-edit-a" data-notice="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-grease-pencil"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a notice-delete-a" data-notice="'.esc_attr( $key ).'">
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
				$message = __( 'Notice updated successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Notice not updated!', 'employee-&-hr-management' );
				$content = '';
			}

		} else {
			if ( empty ( $_POST['name'] ) ) {
				$message = __( 'Please enter name.!', 'employee-&-hr-management' );		
			} elseif ( empty ( $_POST['desc'] ) ) {
				$message = __( 'Please enter description.!', 'employee-&-hr-management' );
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

	/* Delete notices Action Call */
	public static function delete_notices() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['key'] ) ) {
			$key    = sanitize_text_field( $_POST['key'] );
			$notices = get_option( 'ehrm_notices_data' );

			unset($notices[$key]);

			if ( update_option( 'ehrm_notices_data', $notices ) ) {

				$all_notices = get_option( 'ehrm_notices_data' );

				if ( ! empty ( $all_notices ) ) {
            		$sno = 1;
            		foreach ( $all_notices as $key => $notice ) {
            	
		                $html .= '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $notice['name'] ).'</td>
				                  	<td class="badge-desc">'.esc_html( $notice['desc'] ).'</td>
				                  	<td>'.esc_html( $notice['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a notice-edit-a" data-notice="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-grease-pencil"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a notice-delete-a" data-notice="'.esc_attr( $key ).'">
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
				$message = __( 'Notice deleted successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Notice not deleted!', 'employee-&-hr-management' );
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