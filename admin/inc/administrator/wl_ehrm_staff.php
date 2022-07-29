<?php
defined('ABSPATH') or die();
require_once(WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php');
// require_once WL_EHRM_PLUGIN_DIR_PATH . 'includes/constants.php';
require_once WL_EHRM_PLUGIN_DIR_PATH . 'includes/EHRM_Helper.php';

//Database
$shift_data		   = EHRM_Helper::fetch_shift_data();
$count_shifts_data = count($shift_data);

$the_designation 		= EHRM_Helper::fetch_designation();
$count_designation_data = count($the_designation);

$staff_data	   = EHRM_Helper::fetch_the_staff();
$counted_staff = count( $staff_data );
$sno = 1;
// for( $i = 0; $i< $counted_staff; $i++ ) {
// 	echo "<pre>"; 
// 	print_r($staff_data[$i]);
// 	echo "</pre>";
// }

?>
<!-- partial -->
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">
				<span class="page-title-icon bg-gradient-primary text-white mr-2">
					<i class="mdi mdi-android"></i>
				</span>
				<?php esc_html_e('Staffs', 'employee-&-hr-management' ); ?>
			</h3>
			<nav aria-label="breadcrumb">
				<ul class="breadcrumb">
					<li class="breadcrumb-item active" aria-current="page">
						<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-toggle="modal" data-target="#AddStaff">
							<i class="mdi mdi-plus"></i> <?php esc_html_e('Add Staff', 'employee-&-hr-management' ); ?></button>
					</li>
				</ul>
			</nav>
		</div>
		<div class="row">
			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card table_card">
					<div class="card-body">
						<div class="table-responsive">
							<h4 class="card-title"><?php esc_html_e('Staffs', 'employee-&-hr-management'); ?></h4>
							<table class="table table-striped staffs_table">
								<thead>
									<tr>
										<th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Email', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Phone No.', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Shift', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Designation', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Leaves', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Salary', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Action', 'employee-&-hr-management' ); ?></th>
									</tr>
								</thead>
								<tbody id="staff_tbody">
									<?php
									if ( $counted_staff > 0 ) {
										$sno = 1;
										
											for( $i = 0; $i< $counted_staff; $i++ ) {
												$staff_fullname 	  = $staff_data[$i]->first_name . ' ' . $staff_data[$i]->last_name;
												$staff_email 		  = $staff_data[$i]->email;
												$staff_phoneno		  = $staff_data[$i]->phone_no;
												$staff_shift_name 	  = $staff_data[$i]->name;
												$staff_designation_id = $staff_data[$i]->designation_id;
												$staff_amount 		  = $staff_data[$i]->amount;
												$staff_table_id 	  = $staff_data[$i]->id;

												// $leave_name  = unserialize( $staff['leave_name'] );
												// $leave_value = unserialize( $staff['leave_value'] );
												// $leave_no    = sizeof( $leave_name );
												$staff_designation_name = EHRM_Helper::fetch_designation_name($staff_designation_id);
									?>
											<tr>
												<td><?php echo esc_html( $sno ); ?>.</td>
												<td><?php echo esc_html( $staff_fullname ); ?></td>
												<td><?php echo esc_html( $staff_email ); ?></td>
												<td><?php echo esc_html($staff_phoneno); ?></td>
												<td><?php echo esc_html( $staff_shift_name ); ?></td>
												<td><?php echo esc_html( $staff_designation_name->name ); ?></td>
												<td>
													
												</td>
												<td><?php echo esc_html( $staff_amount ); ?></td>
												<td><?php //echo esc_html( $staff['status'] ); ?></td>
												<td class="designation-action-tools">
													<ul class="designation-action-tools-ul">
														<li class="designation-action-tools-li">
															<a href="#" title="Edit" class="designation-action-tools-a staff-edit-a" data-staff="<?php echo esc_attr($staff_table_id); ?>">
																<i class="mdi mdi-grease-pencil"></i>
															</a>
														</li>
														<li class="designation-action-tools-li">
															<a href="#" title="Delete" class="designation-action-tools-a staff-delete-a" data-staff="<?php echo esc_attr($staff_table_id); ?>">
																<i class="mdi mdi-window-close"></i>
															</a>
														</li>
													</ul>
												</td>
											</tr>
											<?php $sno++;
										}
									} else { ?>
										<tr>
											<td><?php esc_html_e( 'No Staff added yet.!', 'employee-&-hr-management' ); ?></td>
										</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Email', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Phone No.', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Shift', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Designation', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Leaves', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Salary', 'employee-&-hr-management' ); ?></th>
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

		<!-- Add Staff Modal -->
		<div class="modal fade" id="AddStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-notify modal-lg modal-info">
				<div class="modal-content">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title"><?php esc_html_e( 'Staff Details', 'employee-&-hr-management' ); ?></h4>
							<form class="forms-sample" method="post" id="add_staff_form" autocomplete="off">
								<div class="form-group">
									<label for="staff_name"><?php esc_html_e( 'Select User', 'employee-&-hr-management' ); ?></label>
									<select class="form-control" name="select_user_id" id="select_user_id">
										<option value=""><?php esc_html_e( '--------Select user---------', 'employee-&-hr-management' ); ?></option>
										<?php global $wpdb;
										$user_table = $wpdb->base_prefix . "users";
										$users_data = $wpdb->get_results( "SELECT * FROM $user_table" );

										if ( ! empty( $users_data ) )  {
											foreach ( $users_data as $key => $users ) {
												?>
												<option value="<?php echo esc_attr( $users->ID ); ?>"><?php echo esc_html( $users->display_name ); ?>
												</option>
											<?php }
									} ?>
									</select>
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'Username', 'employee-&-hr-management' ); ?></label>
									<input type="text" id="user_name" name="user_name" placeholder="<?php esc_html_e( 'User Name', 'employee-&-hr-management' ); ?>" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'First Name', 'employee-&-hr-management' ); ?></label>
									<input type="text" id="first_name" name="first_name" placeholder="<?php esc_html_e( 'First Name', 'employee-&-hr-management' ); ?>" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'Last Name', 'employee-&-hr-management' ); ?></label>
									<input type="text" id="last_name" name="last_name" placeholder="<?php esc_html_e( 'Last Name', 'employee-&-hr-management' ); ?>" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'Email', 'employee-&-hr-management' ); ?></label>
									<input type="text" id="staff_email" name="staff_email" placeholder="<?php esc_html_e( 'Email', 'employee-&-hr-management' ); ?>" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'Phone No.', 'employee-&-hr-management' ); ?></label>
									<input type="text" id="staff_phone" name="staff_phone" placeholder="<?php esc_html_e( 'Phone no with country code ( 91XXXXXXXXXX )', 'employee-&-hr-management' ); ?>" class="form-control">
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'Select Shift', 'employee-&-hr-management' ); ?></label>
									<select class="form-control" name="user_shift" id="user_shift">
										<option value="">
											<?php esc_html_e( 'Select user shift', 'employee-&-hr-management' ); ?>
										</option>
										<?php 
											for( $i = 0; $i < $count_shifts_data; $i++ ){
												?>
													<option value="<?php echo $shift_data[$i]->id; ?>"><?php echo $shift_data[$i]->name; ?></option>
												<?php
											}
										?>
									</select>
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'Select Designation', 'employee-&-hr-management' ); ?></label>
									<select class="form-control" name="user_designation" id="user_designation">
										<option value="">
											<?php esc_html_e( 'Select User Designation', 'employee-&-hr-management' ); ?>
										</option>
										<?php
										for( $j = 0; $j < $count_designation_data; $j++) {
											?>
											<option value="<?php echo $the_designation[$j]->id; ?>">
											<?php echo $the_designation[$j]->name; ?>
											</option>
											<?php 
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<label for="pay_type"><?php esc_html_e( 'Select Pay Type', 'employee-&-hr-management' ); ?></label>
									<select name="pay_type" id="pay_type" class="form-control">
										<option value=""><?php esc_html_e( '---------Select User Pay Type---------', 'employee-&-hr-management' ); ?></option>
										<option value="1"><?php esc_html_e( 'Salary', 'employee-&-hr-management' ); ?></option>
										<option value="2"><?php esc_html_e( 'Project', 'employee-&-hr-management' ); ?></option>
									</select>
								</div>
								<div class="form-group" id="salary_block">
									<label><?php esc_html_e( 'Salary', 'employee-&-hr-management' ); ?></label>
									<input type="text" id="staff_salary" name="staff_salary" placeholder="<?php esc_html_e( '$1000', 'employee-&-hr-management' ); ?>" class="form-control ">
								</div>
								<div class="form-group dynamic_input_js">
									<label for="location_name"><?php esc_html_e( 'Leaves', 'employee-&-hr-management') ; ?></label>
									<br>
									<input type="text" id="leave_name_-1" class="form-control leave_name" name="leave_name[]" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
									<input type="text" id="leave_value_-1" class="form-control leave_value" name="leave_value[]" placeholder="<?php esc_html_e( 'Value', 'employee-&-hr-management' ); ?>">
									<div id="dynamic_leave_fields" class="dynamic_input_js"></div>
									<br>
									<button class="btn btn-success btn-sm add_leave_fields"><?php esc_html_e( 'Add More', 'employee-&-hr-management' ); ?></button>
									<button class="btn btn-danger btn-sm remove_leave_fields"><?php esc_html_e( 'Remove', 'employee-&-hr-management' ); ?></button>
								</div>
								<div class="form-group">
									<label for="staff_status"><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></label>
									<select name="staff_status" id="staff_status" class="form-control">
										<option value="Active"><?php esc_html_e( 'Active', 'employee-&-hr-management' ); ?></option>
										<option value="Inactive"><?php esc_html_e( 'Inactive', 'employee-&-hr-management' ); ?></option>
									</select>
								</div>
								<input type="button" class="btn btn-gradient-primary mr-2" id="add_staff_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
							</form>
						</div>
					</div>

				</div>
			</div>
		</div>

		<!-- Update staff Modal -->
		<div class="modal fade" id="EditStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-notify modal-lg modal-info">
				<div class="modal-content">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title"><?php esc_html_e( 'Staff Details', 'employee-&-hr-management' ); ?></h4>
							<form class="forms-sample" method="post" id="edit_staff_form" autocomplete="off">
								<div class="form-group">
									<!-- <label for="staff_name"><?php esc_html_e( 'Select User', 'employee-&-hr-management' ); ?></label> -->
									<input type="hidden" name="select_user_id" id="select_user_id">
									<input type="hidden" name="user_id_ct" id="user_id_ct">
									<!-- <select class="form-control" name="select_user_id" id="select_user_id">
										<option value=""><?php esc_html_e( '--------Select user---------', 'employee-&-hr-management' ); ?></option>
										<?php /*global $wpdb;
										$user_table = $wpdb->base_prefix . "users";
										$users_data = $wpdb->get_results( "SELECT * FROM $user_table" );

										if ( ! empty( $users_data ) ) {
											foreach ( $users_data as $key => $users ) {
												?>
												<option value="<?php echo esc_attr( $users->ID ); ?>"><?php echo esc_html( $users->display_name ); ?></option>
											<?php }
									} */?>
									</select> -->
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'Username', 'employee-&-hr-management' ); ?></label>
									<input type="text" id="user_name" name="user_name" placeholder="<?php esc_html_e( 'User Name', 'employee-&-hr-management' ); ?>" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'First Name', 'employee-&-hr-management' ); ?></label>
									<input type="text" id="first_name" name="first_name" placeholder="<?php esc_html_e( 'First Name', 'employee-&-hr-management' ); ?>" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'Last Name', 'employee-&-hr-management' ); ?></label>
									<input type="text" id="last_name" name="last_name" placeholder="<?php esc_html_e( 'Last Name', 'employee-&-hr-management' ); ?>" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'Email', 'employee-&-hr-management' ); ?></label>
									<input type="text" id="staff_email" name="staff_email" placeholder="<?php esc_html_e( 'Email', 'employee-&-hr-management' ); ?>" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'Phone No.', 'employee-&-hr-management' ); ?></label>
									<input type="text" id="staff_phone" name="staff_phone" placeholder="<?php esc_html_e( 'Phone no with country code ( 91XXXXXXXXXX )', 'employee-&-hr-management' ); ?>" class="form-control">
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'Select Shift', 'employee-&-hr-management' ); ?></label>
									<select class="form-control" name="user_shift" id="user_shift">
										<option value=""><?php esc_html_e( '----------------Select user shift----------------', 'employee-&-hr-management' ); ?></option>										
										<?php 
											for( $i = 0; $i < $count_shifts_data; $i++ ){
												?>
													<option value="<?php echo $shift_data[$i]->id; ?>"><?php echo $shift_data[$i]->name; ?></option>
												<?php
											}
										?>
									</select>
								</div>
								<div class="form-group">
									<label><?php esc_html_e( 'Select Designation', 'employee-&-hr-management' ); ?></label>
									<select class="form-control" name="user_designation" id="user_designation">
										<option value=""><?php esc_html_e( '---------Select User Designation---------', 'employee-&-hr-management' ); ?></option>
									<?php
										for( $j = 0; $j < $count_designation_data; $j++) {
											?>
											<option value="<?php echo $the_designation[$j]->id; ?>">
											<?php echo $the_designation[$j]->name; ?>
											</option>
											<?php 
										}
									?>
									</select>
								</div>
								<div class="form-group">
									<label for="pay_type"><?php esc_html_e( 'Select Pay Type', 'employee-&-hr-management' ); ?></label>
									<select name="pay_type" id="pay_type" class="form-control">
										<option value=""><?php esc_html_e( '---------Select User Pay Type---------', 'employee-&-hr-management' ); ?></option>
										<option value="1"><?php esc_html_e( 'Salary', 'employee-&-hr-management' ); ?></option>
										<option value="2"><?php esc_html_e( 'Project', 'employee-&-hr-management' ); ?></option>
									</select>
								</div>
								<div class="form-group" id="salary_block">
									<label><?php esc_html_e( 'Salary', 'employee-&-hr-management' ); ?></label>
									<input type="text" id="staff_salary" name="staff_salary" placeholder="<?php esc_html_e( '$1000', 'employee-&-hr-management' ); ?>" class="form-control ">
								</div>
								<div class="form-group dynamic_input_js">
									<label for="location_name"><?php esc_html_e( 'Leaves', 'employee-&-hr-management' ); ?></label>
									<br>
									<input type="text" id="edit_leave_name_-1" class="form-control edit_leave_name" name="edit_leave_name[]" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
									<input type="text" id="edit_leave_value_-1" class="form-control edit_leave_value" name="edit_leave_value[]" placeholder="<?php esc_html_e( 'Value', 'employee-&-hr-management' ); ?>">
									<div id="edit_dynamic_leave_fields" class="dynamic_input_js"></div>
									<br>
									<button class="btn btn-success btn-sm edit_add_leave_fields"><?php esc_html_e( 'Add More', 'employee-&-hr-management' ); ?></button>
									<button class="btn btn-danger btn-sm edit_remove_leave_fields"><?php esc_html_e( 'Remove', 'employee-&-hr-management' ); ?></button>
								</div>
								<div class="form-group">
									<label for="staff_status"><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></label>
									<select name="staff_status" id="staff_status" class="form-control">
										<option value="1"><?php esc_html_e( 'Active', 'employee-&-hr-management' ); ?></option>
										<option value="0"><?php esc_html_e( 'Inactive', 'employee-&-hr-management' ); ?></option>
									</select>
								</div>
								<input type="hidden" name="staff_key" id="staff_key">
								<input type="button" class="btn btn-gradient-primary mr-2" id="edit_staff_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
							</form>
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>