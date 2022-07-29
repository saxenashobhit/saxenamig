<?php defined( 'ABSPATH' ) or die(); 
require_once(WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php');
// require WL_EHRM_PLUGIN_DIR_PATH . 'includes/constants.php';
require WL_EHRM_PLUGIN_DIR_PATH . 'includes/EHRM_Helper.php';

/**
 * Shift ajax action class
 */
class ShiftAjaxActions {

	/* Add Shift Action Call */
	public static function add_shift() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['start'] ) && ! empty ( $_POST['end'] ) && ! empty ( $_POST['late'] ) && ! empty ( $_POST['status'] ) ) {
			$name   = sanitize_text_field( $_POST['name'] );
			$start  = wp_kses_post( $_POST['start'] );
			$end    = sanitize_text_field( $_POST['end'] );
			$late   = sanitize_text_field( $_POST['late'] );
			$status = sanitize_text_field( $_POST['status'] );
			$result = EHRM_Helper::save_shift( $name, $start, $end, $late, $status );
			// $shifts = get_option( 'ehrm_shifts_data' );
			
			if ( $result === 1 ) {	
				$status  = 'success';
				$message = __( 'Shift added successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Shift not added!', 'employee-&-hr-management' );
				$content = '';
			}

		} else {
			if ( empty ( $_POST['name'] ) ) {
				$message = __( 'Please enter name.!', 'employee-&-hr-management' );		
			} elseif ( empty ( $_POST['start'] ) ) {
				$message = __( 'Please select start time.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['end'] ) ) {
				$message = __( 'Please select end time.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['late'] ) ) {
				$message = __( 'Please select late time.!', 'employee-&-hr-management' );
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

	/* Delete Shift Action Call */
	public static function delete_shift() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['shift_key'] ) ) {
			$shift_key = sanitize_text_field( $_POST['shift_key'] );
			$shifts    = get_option( 'ehrm_shifts_data' );

			unset( $shifts[$shift_key] );

			if ( update_option( 'ehrm_shifts_data', $shifts ) ) {

				$all_shifts = get_option( 'ehrm_shifts_data' );

				$staff_no = 0;

				if ( ! empty ( $all_shifts ) ) {
            		$sno = 1;
            		foreach ( $all_shifts as $key => $shift ) {
            	
		                $html .= '<tr>
				                	<td>'.esc_html( $sno ).'</td>
				                  	<td>'.esc_html( $shift['name'] ).'</td>
				                  	<td>'.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $shift['start'] ) ) ).'</td>
				                  	<td>'.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $shift['end'] ) ) ).'</td>
				                  	<td>'.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $shift['late'] ) ) ).'</td>
				                  	<td>'.esc_html( $staff_no ).'</td>
				                  	<td>'.esc_html( $shift['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a shift-edit-a" data-shift="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-grease-pencil"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a shift-delete-a" data-shift="'.esc_attr( $key ).'">
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
				$message = __( 'Shift deleted successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Shift not deleted!', 'employee-&-hr-management' );
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

	/* Edit Shift Action Call */
	public static function edit_shift() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['shift_key'] ) ) {
			$shift_key   = sanitize_text_field( $_POST['shift_key'] );
			$shifts		 = EHRM_Helper::fetch_shiftdata_id($shift_key);
			
			$data = array(
				'name'   => $shifts[0]->name,
				'start'  => $shifts[0]->start_time,
				'end'    => $shifts[0]->end_time,
				'late'   => $shifts[0]->late_time,
				'status' => $shifts[0]->status,
			);
			wp_send_json( $data );

		} else {
			wp_send_json( __( 'Something went wrong.!', 'employee-&-hr-management' ) );
		}
		wp_die();
	}

	/* Update Shift Action Call */
	public static function update_shift() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['start'] ) && ! empty ( $_POST['end'] ) && ! empty ( $_POST['late'] ) && ! empty ( $_POST['status'] ) ) {
			$shift_key = sanitize_text_field( $_POST['shift_key'] );
			$name      = sanitize_text_field( $_POST['name'] );
			$start     = wp_kses_post( $_POST['start'] );
			$end       = sanitize_text_field( $_POST['end'] );
			$late      = sanitize_text_field( $_POST['late'] );
			$status    = sanitize_text_field( $_POST['status'] );
			$shifts    = get_option( 'ehrm_shifts_data' );
			$html      = '';

			$data = array(
				'name'   => $name,
				'start'  => $start,
				'end'    => $end,
				'late'   => $late,
				'status' => $status,
			);
			$result_shift_update = EHRM_Helper::update_the_shift( $name, $start, $end, $late, $status, $shift_key );
			if ( $result_shift_update === 1 ) {
				$status  = 'success';
				$message = __( 'Shift updated successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Shift not updated!', 'employee-&-hr-management' );
				$content = '';
			}

		} else {
			if ( empty ( $_POST['name'] ) ) {
				$message = __( 'Please enter name.!', 'employee-&-hr-management' );		
			} elseif ( empty ( $_POST['start'] ) ) {
				$message = __( 'Please select start time.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['end'] ) ) {
				$message = __( 'Please select end time.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['late'] ) ) {
				$message = __( 'Please select late time.!', 'employee-&-hr-management' );
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
}

?>