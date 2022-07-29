<?php
defined( 'ABSPATH' ) or die();
require WL_EHRM_PLUGIN_DIR_PATH . 'includes/constants.php';
require WL_EHRM_PLUGIN_DIR_PATH . 'includes/EHRM_Helper.php';
/**
 *  Ajax Action calls for designations menu
 */
class DesignationsAjaxAction {

	public static function add_department() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset( $_POST['deparment'] ) && ! empty( $_POST['deparment'] ) ) {
			
			$no_of_department_input = count($_POST['deparment']);
			for( $i = 0; $i<$no_of_department_input; $i++ ) {
				$deparment 		  = sanitize_text_field( $_POST['deparment'][$i] );
				$department_descp = sanitize_text_field( $_POST['department_descp'][$i] );
				$department_head  = sanitize_text_field( $_POST['department_head'][$i] );			
				EHRM_Helper::department_query($deparment, $department_descp, $department_head);
			}
			
			// $deparment    = serialize( $_POST['deparment'] );
			$html         = '';
			// $departments  = unserialize( $deparment );

			$deparment_fetch_result = EHRM_Helper::department_fetch_query();
			
			if ( update_option( 'ehrm_departments_data', $departments ) ) {

				$all_departments = get_option( 'ehrm_departments_data' );

				if ( ! empty ( $all_departments ) ) {
            		foreach ( $all_departments as $key => $deparment ) {
            	
		                $html .= '<div class="single-department-div">
                					<input type="text" name="department_name" value="'.esc_html__( $deparment, 'employee-&-hr-management' ).'"">
                					<a href="#" class="remove-department-single designation-action-tools-a"><i class="mdi mdi-window-close"></i></a>
                				  </div>';
		            } 
		        }
		        $status  = 'success';
				$message = __( 'Department added successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );

			} else {
				$status  = 'error';
				$message = __( 'Department not added!', 'employee-&-hr-management' );
				$content = '';
			}

		} else {

			if ( empty ( $_POST['deparment'] ) ) {
				$message = __( 'Please select Deparment.!', 'employee-&-hr-management' );		
			} else {
				$message = __( 'PSomething went wrong.!', 'employee-&-hr-management' );
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
	/**
	 * Add designation
	 */	
	public static function add_designations() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['deparment'] ) && ! empty ( $_POST['color'] ) && ! empty ( $_POST['status'] ) ) {
			$name         = sanitize_text_field( $_POST['name'] );
			$deparment    = wp_kses_post( $_POST['deparment'] );
			$color        = sanitize_text_field( $_POST['color'] );
			$status       = sanitize_text_field( $_POST['status'] );
			//$designations = get_option( 'ehrm_designations_data' );
			$html         = '';
			// $add_designation_query = EHRM_Helper::add_designation( $deparment, $name, $color, $status );
			
			if( EHRM_Helper::add_designation( $deparment, $name, $color, $status ) ) {
				// $all_designations = get_option( 'ehrm_designations_data' );
				$all_designations = EHRM_Helper::fetch_designation();
				// echo "<pre>";
				// var_dump( $all_designations );
				// echo "</pre>";
				if ( ! empty ( $all_designations ) ) {
            		$sno = 1;
            		foreach ( $all_designations as $key => $designation ) {
            	
		                $html .= '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $designation['name'] ).'</td>
				                  	<td>'.esc_html( $designation['deparment'] ).'</td>
				                  	<td><label class="badge" style="background-color:'.esc_attr( $designation['color'] ).';">'.esc_attr( $designation['color'] ).'</label></td>
				                  	<td>'.esc_html( $designation['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a designation-edit-a" data-designation="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-grease-pencil"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a designation-delete-a" data-designation="'.esc_attr( $key ).'">
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
				$message = __( 'Designation added successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );

			} else {
				$status  = 'error';
				$message = __( 'Designation not added!', 'employee-&-hr-management' );
				$content = '';
			}

		} else {

			if ( empty ( $_POST['deparment'] ) ) {
				$message = __( 'Please select Deparment.!', 'employee-&-hr-management' );		
			} elseif ( empty ( $_POST['name'] ) ) {
				$message = __( 'Please enter name.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['color'] ) ) {
				$message = __( 'Please select color.!', 'employee-&-hr-management' );
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

	/* Edit Designations Action Call */
	public static function edit_designations() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['key'] ) ) {
			$key          = sanitize_text_field( $_POST['key'] );
			//$designations = get_option( 'ehrm_designations_data' );
			$designations = EHRM_Helper::fetch_designation_id($key);
			$data = [
                'department_name'    => $designations[0]->title,
                'department_id'		 => $designations[0]->designation_info,
                'designation_name'   => $designations[0]->name,
                'designation_color'  => $designations[0]->color,
                'designation_status' => $designations[0]->status,
            ];			
			wp_send_json( $data );

		} else {
			wp_send_json( __( 'Something went wrong.!', 'employee-&-hr-management' ) );
		}
		wp_die();
	}

	/* Update Designations Action Call */
	public static function update_designations() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['deparment'] ) && ! empty ( $_POST['color'] ) && ! empty ( $_POST['status'] ) ) {
			$name         	= sanitize_text_field( $_POST['name'] );
			// $key          = sanitize_text_field( $_POST['key'] );
			$designation_id	= sanitize_text_field( $_POST['key'] );
			$deparment_id  	= sanitize_text_field( $_POST['deparment'] );
			$color        	= sanitize_text_field( $_POST['color'] );
			$status       	= sanitize_text_field( $_POST['status'] );
			$designations 	= get_option( 'ehrm_designations_data' );
			$html         	= '';
			$result = EHRM_Helper::update_designation( $deparment_id, $name, $color, $status, $designation_id );

			if ( $result === 1 ) {
				$status  = 'success';
				$message = __( 'Designation updated successfully!', 'employee-&-hr-management' );
				$content = '';
			} else {
				$status  = 'error';
				$message = __( 'Designation not Updated!', 'employee-&-hr-management' );
				$content = '';
			}

		} else {
			if ( empty ( $_POST['deparment'] ) ) {
				$message = __( 'Please select Deparment.!', 'employee-&-hr-management' );		
			} elseif ( empty ( $_POST['name'] ) ) {
				$message = __( 'Please enter name.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['color'] ) ) {
				$message = __( 'Please select color.!', 'employee-&-hr-management' );
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

	/* Delete Designations Action Call */
	public static function delete_designations() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['key'] ) ) {
			$key          = sanitize_text_field( $_POST['key'] );
			$designations = get_option( 'ehrm_designations_data' );
			$html         = '';

			unset($designations[$key]);

			if ( update_option( 'ehrm_designations_data', $designations ) ) {

				$designations = get_option( 'ehrm_designations_data' );

				if ( ! empty ( $designations ) ) {
            		$sno = 1;
            		foreach ( $designations as $key => $designation ) {
            	
		                $html .= '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $designation['name'] ).'</td>
				                  	<td>'.esc_html( $designation['deparment'] ).'</td>
				                  	<td><label class="badge" style="background-color:'.esc_attr( $designation['color'] ).';">'.esc_attr( $designation['color'] ).'</label></td>
				                  	<td>'.esc_html( $designation['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a designation-edit-a" data-designation="'.esc_attr( $key ).'">
		                          					<i class="mdi mdi-grease-pencil"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a designation-delete-a" data-designation="'.esc_attr( $key ).'">
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
				$message = __( 'Designation deleted successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Designation not Deleted!', 'employee-&-hr-management' );
				$content = '';
			}

		} else {
			$status  = 'error';
			$message = __( 'Something went wrong.!!', 'employee-&-hr-management' );
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