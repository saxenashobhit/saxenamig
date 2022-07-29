<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );
$all_holidays = get_option( 'ehrm_holidays_data' );
?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          	<i class="mdi mdi-tree"></i>                 
	        	</span>
	        	<?php esc_html_e( 'Holidays', 'employee-&-hr-management' ); ?>
	      	</h3>
	      	<nav aria-label="breadcrumb holiday">
	        	<ul class="breadcrumb">
	          	<li class="breadcrumb-item" aria-current="page">
	          		<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-toggle="modal" data-target="#ImportHoliday">
	            		<i class="mdi mdi-file-import"></i> <?php esc_html_e( 'Import', 'employee-&-hr-management' ); ?>
	            	</button>
	            </li>
	            <li class="breadcrumb-item export" aria-current="page">
					<form method="post">
						<p><input type="hidden" name="holiday_action" value="export_settings" /></p>
						<p>
							<?php wp_nonce_field( 'holiday_export_nonce', 'holiday_export_nonce' ); ?>
							<?php submit_button( __( 'Export', 'employee-&-hr-management' ), 'secondary', 'submit', false ); ?>
							
						</p>
					</form>
	            </li>
	            <li class="breadcrumb-item active" aria-current="page">
	            	<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-toggle="modal" data-target="#AddHolidays">
	            		<i class="mdi mdi-plus"></i> <?php esc_html_e( 'Add Holiday', 'employee-&-hr-management' ); ?>
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
		                  	<h4 class="card-title"><?php esc_html_e( 'Holiday', 'employee-&-hr-management' ); ?></h4>
		                  	<table class="table table-striped events_table">
		                    	<thead>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Date(s)', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Days', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Action', 'employee-&-hr-management' ); ?></th>
			                     	</tr>
		                    	</thead>
		                    	<tbody id="holiday_tbody">
								<?php 
				                    	if ( ! empty ( $all_holidays ) ) {

                                        $sno         = 1;        
                                        $first       = new \DateTime( date( "Y" )."-01-01" );
                                        $first       = $first->format( "Y-m-d" );
                                        $plusOneYear = date( "Y" )+1;
                                        $last        = new \DateTime( $plusOneYear."-12-31" );          
                                        $last        = $last->format( "Y-m-d" );          
                                        $all_dates   = EHRMHelperClass::ehrm_get_date_range( $first, $last );

		                        		foreach ( $all_holidays as $key => $holiday ) {
                                            if ( in_array( $holiday['to'], $all_dates ) ) {
                                ?>
			                        <tr>
										<td><?php echo esc_html( $sno ); ?>.</td>
			                          	<td><?php echo esc_html( $holiday['name'] ); ?></td>
			                          	<td><?php echo( "From ".date( EHRMHelperClass::get_date_format(), strtotime( $holiday['start'] ) )." to ".date( EHRMHelperClass::get_date_format(), strtotime( $holiday['to'] ) ) ); ?></td>
			                          	<td><?php echo esc_html( $holiday['days'] ); ?></td>
			                          	<td><?php echo esc_html( $holiday['status'] ); ?></td>
			                          	<td class="designation-action-tools">
			                          		<ul class="designation-action-tools-ul">
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a holiday-edit-a" data-holiday="<?php echo esc_attr($key); ?>">
			                          					<i class="mdi mdi-grease-pencil"></i>
			                          				</a>
			                          			</li>
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a holiday-delete-a" data-holiday="<?php echo esc_attr($key); ?>">
			                          					<i class="mdi mdi-window-close"></i>
			                          				</a>
			                          			</li>
			                          		</ul>
			                          	</td>
			                        </tr>
				                    <?php $sno++;
                                            } } } else { ?>
				                    <tr>
				                    	<td><?php esc_html_e( 'No Holidays added yet.!', 'employee-&-hr-management' ); ?></td>
				                    </tr>
				                    <?php } ?>
		                    	</tbody>
		                    	<tfoot>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Date(s)', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Days', 'employee-&-hr-management' ); ?></th>
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
		
		<!-- Import Holiday Modal -->
		<div class="modal fade" id="ImportHoliday" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
								<input type="hidden" name="holiday_action" value="import_settings" />
								<?php wp_nonce_field( 'holiday_import_nonce', 'holiday_import_nonce' ); ?>
								<?php submit_button( __( 'Import' ), 'secondary', 'submit_import', false ); ?>
								</div>
							</div>
						</form>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

        <!-- Add Holiday Modal -->
		<div class="modal fade" id="AddHolidays" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">

		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Holiday Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_holiday_form">
	                  	<div class="form-group">
	                      <label for="holiday_name"><?php esc_html_e( 'Holiday Name', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="holiday_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="holiday_start"><?php esc_html_e( 'Holiday From', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" name="holiday_start" id="holiday_start" placeholder="Format:- YYYY-MM-DD">
	                    </div>
	                    <div class="form-group">
	                      <label for="holiday_to"><?php esc_html_e( 'Holiday To', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="holiday_to" placeholder="Format:- YYYY-MM-DD">
	                    </div>
	                    <div class="form-group">
	                      	<label for="holiday_status"><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></label>
	                      	<select name="holiday_status" id="holiday_status" class="form-control">
	                      		<option value="Active"><?php esc_html_e( 'Active', 'employee-&-hr-management' ); ?></option>
	                      		<option value="Inactive"><?php esc_html_e( 'Inactive', 'employee-&-hr-management' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_holiday_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>

		    </div>
		  </div>
		</div>

		<!-- Edit Holiday Modal -->
		<div class="modal fade" id="EditHoliday" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Holiday Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_holiday_form">
	                  	<div class="form-group">
	                      <label for="edit_holiday_name"><?php esc_html_e( 'Holiday Name', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="edit_holiday_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_holiday_start"><?php esc_html_e( 'Holiday From', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" name="edit_holiday_start" id="edit_holiday_start" placeholder="Format:- YYYY-MM-DD">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_holiday_to"><?php esc_html_e( 'Holiday To', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" name="edit_holiday_to" id="edit_holiday_to" placeholder="Format:- YYYY-MM-DD">
	                    </div>
	                    <div class="form-group">
	                      	<label for="edit_holiday_status"><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></label>
	                      	<select name="edit_holiday_status" id="edit_holiday_status" class="form-control">
	                      		<option value="Active"><?php esc_html_e( 'Active', 'employee-&-hr-management' ); ?></option>
	                      		<option value="Inactive"><?php esc_html_e( 'Inactive', 'employee-&-hr-management' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="hidden" name="holiday_key" id="holiday_key">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="edit_holiday_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>

		    </div>
		  </div>
		</div>
	</div>
</div>