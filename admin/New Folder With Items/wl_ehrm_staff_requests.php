<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );
$all_requests = get_option( 'ehrm_requests_data' );
?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          	<i class="mdi mdi-note-plus-outline"></i>                 
	        	</span>
	        	<?php esc_html_e( 'Leave Requests', 'employee-&-hr-management' ); ?>
            </h3>
            <nav aria-label="breadcrumb requests">
                <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-toggle="modal" data-target="#AddRequests">
                        <i class="mdi mdi-plus"></i> <?php esc_html_e( 'Add Leave Requests', 'employee-&-hr-management' ); ?>
                    </button>
                </li>
                </ul>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              	<div class="card table_card">
                	<div class="card-body">
                		<div class="table-responsive">
		                  	<h4 class="card-title"><?php esc_html_e( 'Requests', 'employee-&-hr-management' ); ?></h4>
		                  	<table class="table table-striped shifts_table">
		                    	<thead>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Title', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Staff Name', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Short Description', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Date', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Request For', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Leaves', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Action', 'employee-&-hr-management' ); ?></th>
			                     	</tr>
		                    	</thead>
		                    	<tbody id="request_tbody">
				                    <?php 
				                    	if ( ! empty ( $all_requests ) ) {
		                        		$sno     = 1;
		                        		$user_id = get_current_user_id();
		                        		foreach ( $all_requests as $key => $request ) {
		                        			if ( $user_id == $request['s_id'] ) {
		                        	?>
			                        <tr>
			                        	<td><?php if( ! empty ( $sno ) ) { echo esc_html( $sno ); } ?>.</td>
			                          	<td><?php if( ! empty ( $request['name'] ) ) { echo esc_html( $request['name'] ); } ?></td>
			                          	<td><?php if( ! empty ( $request['s_name'] ) ) { echo esc_html( $request['s_name']  ); } ?></td>
			                          	<td><?php if( ! empty ( $request['desc'] ) ) { echo esc_html( $request['desc'] ); } ?></td>
			                          	<td><?php if( ! empty ( $request['date'] ) ) { echo esc_html( date( EHRMHelperClass::get_date_format(), strtotime( $request['date'] ) ) ); } ?></td>
			                          	<td><?php if( ! empty ( $request['date'] ) ) { echo esc_html( "From ".date( EHRMHelperClass::get_date_format(), strtotime( $request['start'] ) )." to ".date( EHRMHelperClass::get_date_format(), strtotime( $request['to'] ) ) ); } ?></td>
			                          	<td>
                                            <?php 
                                            if( ! empty ( $request['days'] ) ) {
                                                echo esc_html( $request['days'] );
                                            } 
                                            ?>
                                        </td>
										<td class="status-<?php echo esc_attr( $request['status'] ); ?>">
										  	<span><?php echo esc_html( $request['status'] ); ?></span>
										</td>
			                          	<td class="designation-action-tools">
			                          		<ul class="designation-action-tools-ul">
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a request-edit-a" data-request="<?php echo esc_attr( $key ); ?>">
			                          					<i class="mdi mdi-grease-pencil"></i>
			                          				</a>
			                          			</li>
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a request-delete-a" data-request="<?php echo esc_attr( $key ); ?>">
			                          					<i class="mdi mdi-window-close"></i>
			                          				</a>
			                          			</li>
			                          		</ul>
			                          	</td>
			                        </tr>
				                    <?php $sno++; } } } else { ?>
				                    <tr>
				                    	<td><?php esc_html_e( 'No Requests added yet.!', 'employee-&-hr-management' ); ?></td>
				                    </tr>
				                    <?php } ?>
		                    	</tbody>
		                    	<tfoot>
			                      	<tr>
                                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Title', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Staff Name', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Short Description', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Date', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Request For', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Leaves', 'employee-&-hr-management' ); ?></th>
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

        <!-- Add Request Modal -->
		<div class="modal fade" id="AddRequests" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Request Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_request_form">
	                  	<div class="form-group">
	                      <label for="request_name"><?php esc_html_e( 'Request Title', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="request_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="notice_desc"><?php esc_html_e( 'Request Description', 'employee-&-hr-management' ); ?></label>
	                      <textarea class="form-control" rows="4" id="notice_desc" name="notice_desc" placeholder="<?php esc_html_e( 'Description....', 'employee-&-hr-management' ); ?>"></textarea>
                        </div>
                        <div class="form-group">
	                      <label for="holiday_start"><?php esc_html_e( 'Holiday From', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" name="holiday_start" id="holiday_start" placeholder="<?php esc_html_e( 'Format:- YYYY-MM-DD', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="holiday_to"><?php esc_html_e( 'Holiday To', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="holiday_to" placeholder="<?php esc_html_e( 'Format:- YYYY-MM-DD', 'employee-&-hr-management' ); ?>">
	                    </div>
                        <input type="hidden" name="staff_id" id="staff_id" value="<?php echo esc_html( get_current_user_id() ); ?>" >
                        <input type="hidden" name="staff_name" id="staff_name" value="<?php echo esc_html( EHRMHelperClass::get_current_user_data( get_current_user_id(), 'fullname' ) ); ?>" >
                        <input type="hidden" name="request_status" id="request_status" value="Pending" >
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_request_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
        </div>

        <!-- Edit Request Modal -->
		<div class="modal fade" id="EditRequests" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Request Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_request_form">
	                  	<div class="form-group">
	                      <label for="edit_request_name"><?php esc_html_e( 'Request Title', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="edit_request_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_notice_desc"><?php esc_html_e( 'Request Description', 'employee-&-hr-management' ); ?></label>
	                      <textarea class="form-control" rows="4" id="edit_notice_desc" name="edit_notice_desc" placeholder="<?php esc_html_e( 'Description....', 'employee-&-hr-management' ); ?>"></textarea>
                        </div>
                        <div class="form-group">
	                      <label for="edit_holiday_start"><?php esc_html_e( 'Holiday From', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" name="edit_holiday_start" id="edit_holiday_start" placeholder="<?php esc_html_e( 'Format:- YYYY-MM-DD', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_holiday_to"><?php esc_html_e( 'Holiday To', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="edit_holiday_to" placeholder="<?php esc_html_e( 'Format:- YYYY-MM-DD', 'employee-&-hr-management' ); ?>">
                        </div>
                        <input type="hidden" name="request_key" id="request_key" value="">
                        <input type="hidden" name="edit_staff_id" id="edit_staff_id" value="" >
                        <input type="hidden" name="edit_staff_name" id="edit_staff_name" value="" >
                        <input type="hidden" name="edit_request_status" id="edit_request_status" value="" >
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="edit_request_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
        </div>
        
    </div>
</div>