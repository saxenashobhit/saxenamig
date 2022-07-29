<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );
$all_events = get_option( 'ehrm_events_data' );
?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          	<i class="mdi mdi-counter"></i>                 
	        	</span>
	        	<?php esc_html_e( 'Events', 'employee-&-hr-management' ); ?>
	      	</h3>
	      	<nav aria-label="breadcrumb">
	        	<ul class="breadcrumb">
	        		<li class="breadcrumb-item" aria-current="page">
		          		<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-toggle="modal" data-target="#ImportEvents">
		            		<i class="mdi mdi-file-import"></i> <?php esc_html_e( 'Import', 'employee-&-hr-management' ); ?>
		            	</button>
		            </li>
		            <li class="breadcrumb-item export" aria-current="page">
						<form method="post">
							<p><input type="hidden" name="event_action" value="export_settings" /></p>
							<p>
								<?php wp_nonce_field( 'event_export_nonce', 'event_export_nonce' ); ?>
								<?php submit_button( __( 'Export', 'employee-&-hr-management' ), 'secondary', 'submit', false ); ?>
								
							</p>
						</form>
					</li>
		          	<li class="breadcrumb-item active" aria-current="page">
		            	<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-toggle="modal" data-target="#AddEvents">
		            		<i class="mdi mdi-plus"></i> <?php esc_html_e( 'Add Events', 'employee-&-hr-management' ); ?></button>
		          	</li>
	        	</ul>
	      	</nav>
	    </div>
	    <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              	<div class="card table_card">
                	<div class="card-body">
                		<div class="table-responsive">
		                  	<h4 class="card-title"><?php esc_html_e( 'Events', 'employee-&-hr-management' ); ?></h4>
		                  	<table class="table table-striped events_table">
		                    	<thead>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Description', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Date', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Time', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Action', 'employee-&-hr-management' ); ?></th>
			                     	</tr>
		                    	</thead>
		                    	<tbody id="event_tbody">
				                    <?php 
				                    	if ( ! empty ( $all_events ) ) {
		                        		$sno = 1;
		                        		foreach ( $all_events as $key => $event ) {
		                        	?>
			                        <tr>
			                        	<td><?php echo esc_html( $sno ); ?>.</td>
			                          	<td><?php echo esc_html( $event['name'] ); ?></td>
			                          	<td class="badge-desc">
			                          		<p><?php echo esc_html( $event['desc'] ); ?></p>
			                          	</td>
			                          	<td><?php echo esc_html( date( EHRMHelperClass::get_date_format(), strtotime( $event['date'] ) ) ); ?></td>
			                          	<td><?php echo esc_html( $event['time'] ); ?></td>
			                          	<td><?php echo esc_html( $event['status'] ); ?></td>
			                          	<td class="designation-action-tools">
			                          		<ul class="designation-action-tools-ul">
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a event-edit-a" data-event="<?php echo esc_attr( $key ); ?>">
			                          					<i class="mdi mdi-grease-pencil"></i>
			                          				</a>
			                          			</li>
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a event-delete-a" data-event="<?php echo esc_attr( $key ); ?>">
			                          					<i class="mdi mdi-window-close"></i>
			                          				</a>
			                          			</li>
			                          		</ul>
			                          	</td>
			                        </tr>
				                    <?php $sno++; } } else { ?>
				                    <tr>
				                    	<td><?php esc_html_e( 'No Events added yet.!', 'employee-&-hr-management' ); ?></td>
				                    </tr>
				                    <?php } ?>
		                    	</tbody>
		                    	<tfoot>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Description', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Date', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Time', 'employee-&-hr-management' ); ?></th>
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
		
		<!-- Import Events Modal -->
		<div class="modal fade" id="ImportEvents" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  	<h4 class="card-title"><?php esc_html_e( 'Import', 'employee-&-hr-management' ); ?></h4>
						  <form class="md-form" method="post" enctype="multipart/form-data">
							<div class="file-field export">
								<a class="btn-floating purple-gradient mt-0 float-left">
									<i class="fa fa-cloud-upload" aria-hidden="true"></i>
									<input type="file" name="import_file" required>
								</a>
								<div class="file-path-wrapper">
								<input class="file-path validate" type="hidden" placeholder="Upload your file">
								<input type="hidden" name="event_action" value="import_settings" />
								<?php wp_nonce_field( 'event_import_nonce', 'event_import_nonce' ); ?>
								<?php submit_button( __( 'Import' ), 'secondary', 'submit_import', false ); ?>
								</div>
							</div>
						</form>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

        <!-- Add Description Modal -->
		<div class="modal fade" id="AddEvents" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">

		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Event Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_event_form" autocomplete="off">
	                  	<div class="form-group">
	                      <label for="event_name"><?php esc_html_e( 'Event Name', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="event_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="event_desc"><?php esc_html_e( 'Event Description', 'employee-&-hr-management' ); ?></label>
	                      <textarea class="form-control" rows="4" id="event_desc" name="event_desc" placeholder="<?php esc_html_e( 'Description....', 'employee-&-hr-management' ); ?>"></textarea>
	                    </div>
	                    <div class="form-group">
	                      <label for="event_date"><?php esc_html_e( 'Event Date', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="event_date" placeholder="Format:- YYYY-MM-DD" data-toggle="datetimepicker" data-target="#event_date">
	                    </div>
	                    <div class="form-group">
	                      <label for="event_time"><?php esc_html_e( 'Event Time', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="event_time" placeholder="10:00 AM" data-toggle="datetimepicker" data-target="#event_time">
	                    </div>
	                    <div class="form-group">
	                      	<label for="event_status"><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></label>
	                      	<select name="event_status" id="event_status" class="form-control">
	                      		<option value="Active"><?php esc_html_e( 'Active', 'employee-&-hr-management' ); ?></option>
	                      		<option value="Inactive"><?php esc_html_e( 'Inactive', 'employee-&-hr-management' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_event_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>

		    </div>
		  </div>
		</div>

		<!-- Add Description Modal -->
		<div class="modal fade" id="EditEvent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Event Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_event_form" autocomplete="off">
	                  	<div class="form-group">
	                      <label for="edit_event_name"><?php esc_html_e( 'Event Name', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="edit_event_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_event_desc"><?php esc_html_e( 'Event Description', 'employee-&-hr-management' ); ?></label>
	                      <textarea class="form-control" rows="4" id="edit_event_desc" name="edit_event_desc" placeholder="<?php esc_html_e( 'Description....', 'employee-&-hr-management' ); ?>"></textarea>
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_event_date"><?php esc_html_e( 'Event Date', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="edit_event_date" placeholder="Format:- YYYY-MM-DD" data-toggle="datetimepicker" data-target="#event_date">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_event_time"><?php esc_html_e( 'Event Time', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="edit_event_time" placeholder="10:00 AM" data-toggle="datetimepicker" data-target="#event_time">
	                    </div>
	                    <div class="form-group">
	                      	<label for="edit_event_status"><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></label>
	                      	<select name="edit_event_status" id="edit_event_status" class="form-control">
	                      		<option value="Active"><?php esc_html_e( 'Active', 'employee-&-hr-management' ); ?></option>
	                      		<option value="Inactive"><?php esc_html_e( 'Inactive', 'employee-&-hr-management' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="hidden" name="event_key" id="event_key">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="edit_event_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>

		    </div>
		  </div>
		</div>

	</div>
</div>