<?php
defined( 'ABSPATH' ) or die();
require_once(WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php');
// require WL_EHRM_PLUGIN_DIR_PATH . 'includes/constants.php';
require WL_EHRM_PLUGIN_DIR_PATH . 'includes/EHRM_Helper.php';
// $all_shifts = get_option( 'ehrm_shifts_data' );
$all_shifts = EHRM_Helper::fetch_shift_data();
$no_of_rows = count( $all_shifts );
?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          	<i class="mdi mdi-alarm-check"></i>                 
	        	</span>
	        	<?php esc_html_e( 'Shifts', 'employee-&-hr-management' ); ?>
	      	</h3>
	      	<nav aria-label="breadcrumb">
	        	<ul class="breadcrumb">
		          	<li class="breadcrumb-item active" aria-current="page">
		            	<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-toggle="modal" data-target="#AddShift">
		            		<i class="mdi mdi-plus"></i> <?php esc_html_e( 'Add Shift', 'employee-&-hr-management' ); ?></button>
		          	</li>
	        	</ul>
	      	</nav>
	    </div>
	    <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              	<div class="card table_card">
                	<div class="card-body">
                		<div class="table-responsive">
		                  	<h4 class="card-title"><?php esc_html_e( 'Shifts', 'employee-&-hr-management' ); ?></h4>
		                  	<table class="table table-striped shifts_table">
		                    	<thead>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Start time', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Ending time', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Late time', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Staffs', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Action', 'employee-&-hr-management' ); ?></th>
			                     	</tr>
		                    	</thead>
		                    	<tbody id="shift_tbody">
				                    <?php 
				                    	if ( ! empty ( $no_of_rows ) ) {
		                        		$sno = 1;
		                        		for ( $shift_i = 0; $shift_i < $no_of_rows; $shift_i++ ) {
		                        	?>
			                        <tr>
			                        	<td><?php if( ! empty ( $sno ) ) { echo esc_html( $sno ); } ?>.</td>
			                          	<td><?php if( ! empty ( $all_shifts[$shift_i]->name ) ) { echo esc_html( $all_shifts[$shift_i]->name ); } ?></td>
			                          	<td><?php if( ! empty ( $all_shifts[$shift_i]->start_time ) ) { echo esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $all_shifts[$shift_i]->start_time ) ) ); } ?></td>
			                          	<td><?php if( ! empty ( $all_shifts[$shift_i]->end_time ) ) { echo esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $all_shifts[$shift_i]->end_time ) ) ); } ?></td>
			                          	<td><?php if( ! empty ( $all_shifts[$shift_i]->late_time ) ) { echo esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $all_shifts[$shift_i]->late_time ) ) ); } ?></td>
			                          	<td><?php echo esc_html( '0' ); ?></td>
			                          	<td><?php if( ! empty (  $all_shifts[$shift_i]->status ) ) { echo esc_html(  $all_shifts[$shift_i]->status ); } ?></td>
			                          	<td class="designation-action-tools">
			                          		<ul class="designation-action-tools-ul">
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a shift-edit-a" data-shift="<?php echo esc_attr( $all_shifts[$shift_i]->id ); ?>">
			                          					<i class="mdi mdi-grease-pencil"></i>
			                          				</a>
			                          			</li>
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a shift-delete-a" data-shift="<?php echo esc_attr( $all_shifts[$shift_i]->id ); ?>">
			                          					<i class="mdi mdi-window-close"></i>
			                          				</a>
			                          			</li>
			                          		</ul>
			                          	</td>
			                        </tr>
				                    <?php $sno++; } } else { ?>
				                    <tr>
				                    	<td><?php esc_html_e( 'No Shift added yet.!', 'employee-&-hr-management' ); ?></td>
				                    </tr>
				                    <?php } ?>
		                    	</tbody>
		                    	<tfoot>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Start time', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Ending time', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Late time', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Staffs', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Action', 'employee-&-hr-management' ); ?></th>
			                     	</tr>
		                    	</tfoot>
		                  	</table>
	                  	</div>
                	</div>
              	</div>
            </div>
        </div>

        <!-- Add Description Modal -->
		<div class="modal fade" id="AddShift" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">

		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Shift Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_shift_form" autocomplete="off">
	                  	<div class="form-group">
	                      <label for="shift_name"><?php esc_html_e( 'Shift Name', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="shift_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
                  			<label><?php esc_html_e( 'Starting Time', 'employee-&-hr-management' ); ?></label>
				        	<input type="text" class="form-control datetimepicker-input" placeholder="<?php esc_html_e( 'Format:- 10:00 AM', 'employee-&-hr-management' ); ?>" id="shift_start" data-toggle="datetimepicker" data-target="#shift_start"/>
                  		</div>
                  		<div class="form-group">
                  			<label><?php esc_html_e( 'Ending Time', 'employee-&-hr-management' ); ?></label>
				        	<input type="text" id="shift_end" name="shift_end" placeholder="<?php esc_html_e( 'Format:- 1:39 PM', 'employee-&-hr-management' ); ?>" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#shift_end">
                  		</div>
                  		<div class="form-group">
                  			<label><?php esc_html_e( 'Late Time', 'employee-&-hr-management' ); ?></label>
				        	<input type="text" id="shift_late" name="shift_late" placeholder="<?php esc_html_e( 'Format:- 10:15 AM', 'employee-&-hr-management' ); ?>" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#shift_late">
                  		</div>
	                    <div class="form-group">
	                      	<label for="shift_status"><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></label>
	                      	<select name="shift_status" id="shift_status" class="form-control">
	                      		<option value="1"><?php esc_html_e( 'Active', 'employee-&-hr-management' ); ?></option>
	                      		<option value="0"><?php esc_html_e( 'Inactive', 'employee-&-hr-management' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_shift_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>

		    </div>
		  </div>
		</div>

		<!-- Add Description Modal -->
		<div class="modal fade" id="EditShift" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Shift Details99', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_shift_form" autocomplete="off">
	                  	<div class="form-group">
	                      <label for="edit_shift_name"><?php esc_html_e( 'Shift Name', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="edit_shift_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
                  			<label><?php esc_html_e( 'Starting Time', 'employee-&-hr-management' ); ?></label>
				        	<input type="text" class="form-control datetimepicker-input" placeholder="<?php esc_html_e( 'Format:- 10:00 AM', 'employee-&-hr-management' ); ?>" id="edit_shift_start" data-toggle="datetimepicker" data-target="#edit_shift_start"/>
                  		</div>
                  		<div class="form-group">
                  			<label><?php esc_html_e( 'Ending Time', 'employee-&-hr-management' ); ?></label>
				        	<input type="text" id="edit_shift_end" name="shift_end" placeholder="<?php esc_html_e( 'Format:- 1:39 PM', 'employee-&-hr-management' ); ?>" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#edit_shift_end">
                  		</div>
                  		<div class="form-group">
                  			<label><?php esc_html_e( 'Late Time', 'employee-&-hr-management' ); ?></label>
				        	<input type="text" id="edit_shift_late" name="edit_shift_late" placeholder="<?php esc_html_e( 'Format:- 10:15 AM', 'employee-&-hr-management' ); ?>" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#edit_shift_late">
                  		</div>
	                    <div class="form-group">
	                      	<label for="edit_shift_status"><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></label>
	                      	<select name="edit_shift_status" id="edit_shift_status" class="form-control">
	                      		<option value="1"><?php esc_html_e( 'Active', 'employee-&-hr-management' ); ?></option>
	                      		<option value="0"><?php esc_html_e( 'Inactive', 'employee-&-hr-management' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="hidden" name="shift_key" id="shift_key">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="edit_shift_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>

		    </div>
		  </div>
		</div>

	</div>
</div>