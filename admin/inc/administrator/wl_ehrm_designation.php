<?php
defined( 'ABSPATH' ) or die();
require_once WL_EHRM_PLUGIN_DIR_PATH . 'includes/constants.php';
require_once WL_EHRM_PLUGIN_DIR_PATH . 'includes/EHRM_Helper.php';

$all_designations = get_option( 'ehrm_designations_data' );
$all_departments  = get_option( 'ehrm_departments_data' );

global $wpdb;
$department = EHRM_Helper::department_fetch_query();
$department_count = count($department);

//Designation
$all_designations  = EHRM_Helper::fetch_designation();
$designation_count = count($all_designations);

// echo "<pre>";
// var_dump( $department );
// echo "</pre>";
// for( $i=0; $i<$department_count; $i++ ) {
// 	echo "id" . $department[$i]->id . "Deaprtment Name" . $department[$i]->title . "<br>";
// }
?>

<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          	<i class="mdi mdi-barcode-scan"></i>                 
	        	</span>
	        	<?php esc_html_e( 'Designations', 'employee-&-hr-management' ); ?>
	      	</h3>
	      	<nav aria-label="breadcrumb">
	        	<ul class="breadcrumb">
	        		<li class="breadcrumb-item active" aria-current="page">
	            	<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-toggle="modal" data-target="#AddDepartment">
	            		<i class="mdi mdi-plus"></i> <?php esc_html_e( 'Add Department', 'employee-&-hr-management' ); ?></button>
	          	</li>
	          	<li class="breadcrumb-item active" aria-current="page">
	            	<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-toggle="modal" data-target="#AddDesignation">
	            		<i class="mdi mdi-plus"></i> <?php esc_html_e( 'Add Designation', 'employee-&-hr-management' ); ?></button>
	          	</li>
	        	</ul>
	      	</nav>
	    </div>
	    <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              	<div class="card table_card">
                	<div class="card-body">
                		<div class="table-responsive">
		                  	<h4 class="card-title"><?php esc_html_e( 'Designations', 'employee-&-hr-management' ); ?></h4>
		                  	<table class="table table-striped designations_table">
		                    	<thead>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
				                        <th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
				                        <th><?php esc_html_e( 'Department', 'employee-&-hr-management' ); ?></th>
				                        <th><?php esc_html_e( 'Color', 'employee-&-hr-management' ); ?></th>
				                        <th><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></th>
				                        <th><?php esc_html_e( 'Action', 'employee-&-hr-management' ); ?></th>
			                     	</tr>
		                    	</thead>
		                    	<tbody id="designation_tbody">
				                    <?php if ( $designation_count > 0 ) {
		                        		$sno = 1;
		                        		// foreach ( $all_designations as $key => $designation ) {
										for($j = 0; $j < $designation_count; $j++) {
		                        	?>
			                        <tr>
			                        	<td><?php echo esc_html( $sno ); ?>.</td>
			                          	<td><?php echo esc_html( $all_designations[$j]->name ); ?></td>
			                          	<td><?php echo esc_html( $all_designations[$j]->title ); ?></td>
			                          	<td>
			                          		<label class="badge" style="background-color:<?php echo esc_attr( $all_designations[$j]->color ); ?>;">
			                          			<?php echo esc_attr( $all_designations[$j]->color ); ?>
			                          		</label>
			                          	</td>
			                          	<!-- <td><?php //echo esc_html( $all_designations[$j]->status ); ?></td> -->
			                          	<td><?php echo EHRM_Helper::print_status( $all_designations[$j]->status ); ?></td>
			                          	<td class="designation-action-tools">
			                          		<ul class="designation-action-tools-ul">
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a designation-edit-a" data-designation="<?php echo esc_attr( $all_designations[$j]->id ); ?>">
			                          					<i class="mdi mdi-grease-pencil"></i>99
			                          				</a>
			                          			</li>
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a designation-delete-a" data-designation="<?php echo esc_attr( $all_designations[$j]->id ); ?>">
			                          					<i class="mdi mdi-window-close"></i>
			                          				</a>
			                          			</li>
			                          		</ul>
			                          	</td>
			                        </tr>
				                    <?php $sno++; } } else { ?>
				                    <tr>
				                    	<td><?php esc_html_e( 'No Designations added yet.!', 'employee-&-hr-management' ); ?></td>
				                    </tr>
				                    <?php } ?>
		                    	</tbody>
		                    	<tfoot>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
				                        <th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
				                        <th><?php esc_html_e( 'Department', 'employee-&-hr-management' ); ?></th>
				                        <th><?php esc_html_e( 'Color', 'employee-&-hr-management' ); ?></th>
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
		<div class="modal fade" id="AddDepartment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Manage Departments', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_department_form">

	                  	<div class="form-group dynamic_input_js dynamic_department">
							<label for="location_name"><?php esc_html_e( 'Department Name', 'employee-&-hr-management') ; ?></label>
							<br>
							<input type="text" id="department_name_-1" class="form-control department_name" name="department_name[]" placeholder="<?php esc_html_e( 'Department Name', 'employee-&-hr-management' ); ?>">
							<input type="text" id="department_description_-1" class="form-control department_description" name="department_description[]" placeholder="<?php esc_html_e( 'Department Description', 'employee-&-hr-management' ); ?>">
							<input type="text" id="department_head_-1" class="form-control department_head" name="department_head[]" placeholder="<?php esc_html_e( 'Department Head', 'employee-&-hr-management' ); ?>">
							<div id="dynamic_depart_fields" class="dynamic_input_js dynamic_department"></div>
							<br>
							<button class="btn btn-success btn-sm add_depart_fields"><?php esc_html_e( 'Add More', 'employee-&-hr-management' ); ?></button>
							<button class="btn btn-danger btn-sm remove_depart_fields"><?php esc_html_e( 'Remove', 'employee-&-hr-management' ); ?></button>
						</div>

	                    <div class="all-departments-list form-group">
	                    	<?php if ( ! empty ( $all_departments ) ) {
	                    		foreach ( $all_departments as $depat_key => $department ) {
	                    			echo '<div class="single-department-div">
	                    					<input type="text" name="department_name" value="'.esc_html__( $department, 'employee-&-hr-management' ).'"">
	                    					<a href="#" class="remove-department-single designation-action-tools-a"><i class="mdi mdi-window-close"></i></a>
	                    				  </div>';
	                    		}
	                    	} else {
	                    		echo '<p>'.esc_html__( 'No Department found.!', 'employee-&-hr-management' ).'<p\>';
	                    	} 
	                    	?>
	                    </div>
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_department_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>


        <!-- Add Description Modal -->
		<div class="modal fade" id="AddDesignation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">

		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Designation Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_designation_form">
	                  	<div class="form-group">
	                      	<label for="staff_department"><?php esc_html_e( 'Department', 'employee-&-hr-management' ); ?></label>
	                      	<select name="staff_department" id="staff_department" class="form-control">
	                      		<option value=""><?php esc_html_e( 'Select Department', 'employee-&-hr-management' ); ?></option>	                      		
								  <?php 
								 	for( $i=0; $i<$department_count; $i++ ) {
										 ?>
										 <option value="<?php echo $department[$i]->id; ?>"><?php echo $department[$i]->title; ?></option>
										 <?php
									} 
								  ?>
	                      	</select>
	                    </div>
	                    <div class="form-group">
	                      <label for="designation_name"><?php esc_html_e( 'Designation Name', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="designation_name" placeholder="<?php esc_html_e( 'Designation Type', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="designation_color"><?php esc_html_e( 'Designation Color', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control color-field" id="designation_color" placeholder="#ffffff">
	                    </div>
	                    <div class="form-group">
	                      	<label for="designation_status"><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></label>
	                      	<select name="staff_department" id="designation_status" class="form-control">
	                      		<option value="1"><?php esc_html_e( 'Active', 'employee-&-hr-management' ); ?></option>
	                      		<option value="0"><?php esc_html_e( 'Inactive', 'employee-&-hr-management' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_designation_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>

		    </div>
		  </div>
		</div>

		<!-- Edit designation Modal -->
		<div class="modal fade" id="EditDesignation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Designation Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_designation_form">
	                  	<div class="form-group">
	                      	<label for="edit_staff_department"><?php esc_html_e( 'Department', 'employee-&-hr-management' ); ?></label>
	                      	<select name="edit_staff_department" id="edit_staff_department" class="form-control">
	                      		<option value=""><?php esc_html_e( 'Select Department', 'employee-&-hr-management' ); ?></option>	                      		
								  <?php 
								 	for( $i=0; $i<$department_count; $i++ ) {
										 ?>
										 <option value="<?php echo $department[$i]->id; ?>"><?php echo $department[$i]->title; ?></option>
										 <?php
									} 
								  ?>
	                      	</select>
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_designation_name"><?php esc_html_e( 'Designation Name', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="edit_designation_name" placeholder="<?php esc_html_e( 'Designation Type', 'employee-&-hr-management' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_designation_color"><?php esc_html_e( 'Designation Color', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control color-field" id="edit_designation_color" placeholder="#ffffff">
	                    </div>
	                    <div class="form-group">
	                      	<label for="edit_designation_status"><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></label>
	                      	<select name="edit_designation_status" id="edit_designation_status" class="form-control">
	                      		<option value="1"><?php esc_html_e( 'Active', 'employee-&-hr-management' ); ?></option>
	                      		<option value="0"><?php esc_html_e( 'Inactive', 'employee-&-hr-management' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="hidden" name="designation_key" id="designation_key">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="edit_designation_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>

		    </div>
		  </div>
		</div>

	</div>
</div>