<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );
$staffs       = EHRMHelperClass::ehrm_get_staffs_list();
$all_projects = get_option( 'ehrm_projects_data' );
?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          	<i class="mdi mdi-wunderlist"></i>                 
	        	</span>
	        	<?php esc_html_e( 'Projects', 'employee-&-hr-management' ); ?>
	      	</h3>
	      	<nav aria-label="breadcrumb project">
	        	<ul class="breadcrumb">
	            <li class="breadcrumb-item active" aria-current="page">
	            	<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-toggle="modal" data-target="#AddProjects">
	            		<i class="mdi mdi-plus"></i> <?php esc_html_e( 'Add Project', 'employee-&-hr-management' ); ?>
	            	</button>
	          	</li>
	        	</ul>
	      	</nav>
	    </div>
        <div class="row report_row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card table_card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="project_table" class="table table-striped report_table" cellspacing="0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Started On', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Member(s)', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Tags', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Actions', 'employee-&-hr-management' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody id="report_tbody">
                                <?php 
                                    if ( ! empty ( $all_projects ) ) {
                                    $sno = 1;
                                    foreach ( $all_projects as $key => $project ) {
										$members = unserialize( $project['members'] );
		                        ?>
								<tr>
									<td><?php echo esc_html( $sno ); ?>.</td>
									<td><?php echo esc_html( $project['name'] ); ?></td>
									<td><?php echo esc_html( date( EHRMHelperClass::get_date_format(), strtotime( $project['date'] ) ) ); ?></td>
									<td>
									<?php
										foreach ( $members as $member_key => $value ) {											
											echo EHRMHelperClass::get_current_user_data( $value, 'Fullname' ) . ', ';
										}
									?>
									</td>
									<td class="project-token-tags">
										<?php $tags = explode( ",", $project['tags'] );
											foreach ( $tags as $tag_key => $value ) {
												echo '<span class="token-field-value-span">'.$value.'</span>';
											}
										?>
									</td>
									<td><?php echo esc_html( $project['status'] ); ?></td>
									<td class="designation-action-tools">
										<ul class="designation-action-tools-ul">
											<li class="designation-action-tools-li">
												<a href="#" title="<?php esc_html_e( 'View Tasks', 'employee-&-hr-management' ); ?>" class="designation-action-tools-a project-view-a" data-project="<?php echo esc_attr( $key ); ?>">
													<i class="mdi mdi-eye"></i>
												</a>
											</li>
											<li class="designation-action-tools-li">
												<a href="#" title="<?php esc_html_e( 'Edit', 'employee-&-hr-management' ); ?>" class="designation-action-tools-a project-edit-a" data-project="<?php echo esc_attr( $key ); ?>">
													<i class="mdi mdi-grease-pencil"></i>
												</a>
											</li>
											<li class="designation-action-tools-li">
												<a href="#" title="<?php esc_html_e( 'Delete', 'employee-&-hr-management' ); ?>" class="designation-action-tools-a project-delete-a" data-project="<?php echo esc_attr( $key ); ?>">
													<i class="mdi mdi-window-close"></i>
												</a>
											</li>
										</ul>
									</td>
								</tr>
                                <?php $sno++; } } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Started On', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Member(s)', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Tags', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Actions', 'employee-&-hr-management' ); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Project Modal -->
		<div class="modal fade" id="AddProjects" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Project Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_project_form">
	                  	<div class="form-group">
	                      <label for="project_name"><?php esc_html_e( 'Project Title', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="project_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="project_desc"><?php esc_html_e( 'Project Description', 'employee-&-hr-management' ); ?></label>
	                      <textarea class="form-control" rows="4" id="project_desc" name="project_desc" placeholder="<?php esc_html_e( 'Description....', 'employee-&-hr-management' ); ?>"></textarea>
	                    </div>
						<div class="form-group">
	                      	<label for="project_members"><?php esc_html_e( 'Select Members', 'employee-&-hr-management' ); ?></label>
							<select name="project_members" id="project_members" class="member-select" multiple data-live-search="true">
								<?php foreach ( $staffs as $key => $staff ) { ?>
                                    <option value="<?php echo esc_attr( $staff['ID'] ); ?>"><?php echo esc_html( $staff['fullname'] ); ?></option>
                                <?php } ?>
							</select>
	                    </div>
						<div class="form-group">
	                      	<label for="project_tags"><?php esc_html_e( 'Tags', 'employee-&-hr-management' ); ?></label>
							<input type="text" class="form-control" id="project_tags" name="project_tags" placeholder="<?php esc_html_e( 'Type something and hit enter', 'employee-&-hr-management' ); ?>"/>
	                    </div>
						<div class="form-group">
							<label for="project_cost"><?php esc_html_e( 'Project cost', 'employee-&-hr-management' ); ?></label>
							<input type="text" class="form-control" id="project_cost" name="project_cost" placeholder="<?php esc_html_e( 'Enter project cost', 'employee-&-hr-management' ); ?>"/>
						</div>
	                    <div class="form-group">
	                      	<label for="project_status"><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></label>
	                      	<select name="project_status" id="project_status" class="form-control">
	                      		<option value="Active"><?php esc_html_e( 'Active', 'employee-&-hr-management' ); ?></option>
	                      		<option value="Inactive"><?php esc_html_e( 'Inactive', 'employee-&-hr-management' ); ?></option>
								<option value="Completed"><?php esc_html_e( 'Completed', 'employee-&-hr-management' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_project_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

		<!-- Edit Project Modal -->
		<div class="modal fade" id="EditProjects" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Project Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_project_form">
	                  	<div class="form-group">
	                      <label for="edit_project_name"><?php esc_html_e( 'Project Title', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="edit_project_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_project_desc"><?php esc_html_e( 'Project Description', 'employee-&-hr-management' ); ?></label>
	                      <textarea class="form-control" rows="4" id="edit_project_desc" name="edit_project_desc" placeholder="<?php esc_html_e( 'Description....', 'employee-&-hr-management' ); ?>"></textarea>
	                    </div>
						<div class="form-group">
	                      	<label for="edit_project_members"><?php esc_html_e( 'Select Members', 'employee-&-hr-management' ); ?></label>
							<select name="edit_project_members" id="edit_project_members" class="member-select" multiple data-live-search="true">
								<?php foreach ( $staffs as $key => $staff ) { ?>
                                    <option value="<?php echo esc_attr( $staff['ID'] ); ?>"><?php echo esc_html( $staff['fullname'] ); ?></option>
                                <?php } ?>
							</select>
	                    </div>
						<div class="form-group">
	                      	<label for="edit_project_tags"><?php esc_html_e( 'Tags', 'employee-&-hr-management' ); ?></label>
							<input type="text" class="form-control" id="edit_project_tags" name="edit_project_tags" placeholder="<?php esc_html_e( 'Type something and hit enter', 'employee-&-hr-management' ); ?>"/>
	                    </div>
						<div class="form-group">
							<label for="edit_project_cost"><?php esc_html_e( 'Project cost', 'employee-&-hr-management' ); ?></label>
							<input type="text" class="form-control" id="edit_project_cost" name="edit_project_cost" placeholder="<?php esc_html_e( 'Enter project cost', 'employee-&-hr-management' ); ?>"/>
						</div>
	                    <div class="form-group">
	                      	<label for="edit_project_status"><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></label>
	                      	<select name="edit_project_status" id="edit_project_status" class="form-control">
	                      		<option value="Active"><?php esc_html_e( 'Active', 'employee-&-hr-management' ); ?></option>
	                      		<option value="Inactive"><?php esc_html_e( 'Inactive', 'employee-&-hr-management' ); ?></option>
								<option value="Completed"><?php esc_html_e( 'Completed', 'employee-&-hr-management' ); ?></option>
	                      	</select>
						</div>
						<input type="hidden" name="project_key" id="project_key">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="edit_project_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

		<!-- To show all tasks -->
		<div class="modal fade" id="ViewProjects" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-custom-lg modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
						<div class="page-header">
							<h3 class="page-title">
								<span class="page-title-icon bg-gradient-primary text-white mr-2">
								<i class="mdi mdi-certificate"></i>                 
								</span>
								<?php esc_html_e( 'Task List', 'employee-&-hr-management' ); ?>
							</h3>
							<nav aria-label="breadcrumb Tasks">
								<ul class="breadcrumb">
									<li class="breadcrumb-item active" aria-current="page">
										<button class="btn btn-block btn-lg btn-gradient-primary custom-btn task-add-btnn" data-project="" data-toggle="modal" data-target="#AddTasks">
											<i class="mdi mdi-plus"></i> <?php esc_html_e( 'Add Tasks', 'employee-&-hr-management' ); ?>
										</button>
									</li>
								</ul>
							</nav>
						</div>
						<div class="all-task-list-div">
							<ul class="project-task-ul">
							</ul>
						</div>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

		<!-- Add Task Modal -->
		<div class="modal fade" id="AddTasks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-custom-lg-1 modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Task Details56', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_task_form" autocomplete="off">
	                  	<div class="form-group">
	                      <label for="task_name"><?php esc_html_e( 'Task Title', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="task_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="task_desc"><?php esc_html_e( 'Task Description', 'employee-&-hr-management' ); ?></label>
	                      <textarea class="form-control" rows="4" id="task_desc" name="task_desc" placeholder="<?php esc_html_e( 'Description....', 'employee-&-hr-management' ); ?>"></textarea>
	                    </div>
						<div class="form-group">
	                      	<label for="task_members"><?php esc_html_e( 'Assigned to', 'employee-&-hr-management' ); ?></label>
							<select name="task_members" id="task_members" class="member-select" multiple data-live-search="true">
							</select>
	                    </div>
	                    <div class="form-group">
	                      	<label for="task_priority"><?php esc_html_e( 'Priority', 'employee-&-hr-management' ); ?></label>
	                      	<select name="task_priority" id="task_priority" class="form-control">
	                      		<option value="Low"><?php esc_html_e( 'Low', 'employee-&-hr-management' ); ?></option>
	                      		<option value="Medium"><?php esc_html_e( 'Medium', 'employee-&-hr-management' ); ?></option>
	                      		<option value="High"><?php esc_html_e( 'High', 'employee-&-hr-management' ); ?></option>
	                      	</select>
						</div>
						<div class="form-group">
	                      	<label for="task_progress"><?php esc_html_e( 'Progress', 'employee-&-hr-management' ); ?></label>
	                      	<select name="task_progress" id="task_progress" class="form-control">
	                      		<option value="No Progress"><?php esc_html_e( 'No Progress', 'employee-&-hr-management' ); ?></option>
	                      		<option value="Completed"><?php esc_html_e( 'Completed', 'employee-&-hr-management' ); ?></option>
	                      		<option value="In Progress"><?php esc_html_e( 'In Progress', 'employee-&-hr-management' ); ?></option>
	                      	</select>
						</div>
						<div class="form-group">
							<label for="task_due"><?php esc_html_e( 'Due date', 'employee-&-hr-management' ); ?></label>
							<input type="text" class="form-control" id="task_due" placeholder="Format:- YYYY-MM-DD" data-toggle="datetimepicker" data-target="#task_due">
						</div>
						<input type="hidden" name="task_key" id="task_key">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_task_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

		<!-- Edit Task Modal -->
		<div class="modal fade" id="EditTasks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-custom-lg-1 modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Task Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_task_form" autocomplete="off">
	                  	<div class="form-group">
	                      <label for="edit_task_name"><?php esc_html_e( 'Task Title', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="edit_task_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_task_desc"><?php esc_html_e( 'Task Description', 'employee-&-hr-management' ); ?></label>
	                      <textarea class="form-control" rows="4" id="edit_task_desc" name="edit_task_desc" placeholder="<?php esc_html_e( 'Description....', 'employee-&-hr-management' ); ?>"></textarea>
	                    </div>
						<div class="form-group">
	                      	<label for="edit_task_members"><?php esc_html_e( 'Assigned to', 'employee-&-hr-management' ); ?></label>
							<select name="edit_task_members" id="edit_task_members" class="member-select" multiple data-live-search="true">
							</select>
	                    </div>
	                    <div class="form-group">
	                      	<label for="edit_task_priority"><?php esc_html_e( 'Priority', 'employee-&-hr-management' ); ?></label>
	                      	<select name="edit_task_priority" id="edit_task_priority" class="form-control">
	                      		<option value="Low"><?php esc_html_e( 'Low', 'employee-&-hr-management' ); ?></option>
	                      		<option value="Medium"><?php esc_html_e( 'Medium', 'employee-&-hr-management' ); ?></option>
	                      		<option value="High"><?php esc_html_e( 'High', 'employee-&-hr-management' ); ?></option>
	                      	</select>
						</div>
						<div class="form-group">
	                      	<label for="edit_task_progress"><?php esc_html_e( 'Progress', 'employee-&-hr-management' ); ?></label>
	                      	<select name="edit_task_progress" id="edit_task_progress" class="form-control">
	                      		<option value="No Progress"><?php esc_html_e( 'No Progress', 'employee-&-hr-management' ); ?></option>
	                      		<option value="Completed"><?php esc_html_e( 'Completed', 'employee-&-hr-management' ); ?></option>
	                      		<option value="In Progress"><?php esc_html_e( 'In Progress', 'employee-&-hr-management' ); ?></option>
	                      	</select>
						</div>
						<div class="form-group">
							<label for="edit_task_due"><?php esc_html_e( 'Due date', 'employee-&-hr-management' ); ?></label>
							<input type="text" class="form-control" id="edit_task_due" placeholder="Format:- YYYY-MM-DD" data-toggle="datetimepicker" data-target="#edit_task_due">
						</div>
						<input type="hidden" name="edit_task_key" id="edit_task_key">
						<input type="hidden" name="edit_project_key" id="edit_project_key">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="edit_task_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

		<!-- View Task Details Modal -->
		<div class="modal fade" id="ViewTaskDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-custom-lg modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
						<div class="page-header">
							<h3 class="page-title">
								<span class="page-title-icon bg-gradient-primary text-white mr-2">
								<i class="mdi mdi-certificate"></i>                 
								</span>
								<?php esc_html_e( 'Task Details', 'employee-&-hr-management' ); ?>
							</h3>
						</div>
						<div class="task_detail_result"></div>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

    </div>
</div>