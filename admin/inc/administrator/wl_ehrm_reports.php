<?php
defined('ABSPATH') or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );
$staffs = EHRMHelperClass::ehrm_get_staffs_list();
$months = EHRMHelperClass::ehrm_month_filter();
?>
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-note-text"></i>
                </span>
                <?php esc_html_e('Reports', 'employee-&-hr-management' ); ?>
            </h3>
            <nav aria-label="breadcrumb" class="report">
                <form method="post" id="report_form" action="" autocomplete="off">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page">
                            <select class="form-control" id="report_staff_id" name="report_staff_id">
                                <option value=""><?php esc_html_e('Select Staff member', 'employee-&-hr-management' ); ?></option>
                                <?php foreach ( $staffs as $key => $staff ) { ?>
                                    <option value="<?php echo esc_attr( $staff['ID'] ); ?>"><?php echo esc_html( $staff['fullname'] ); ?></option>
                                <?php } ?>
                            </select>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <select class="form-control" id="report_month" name="report_month">
                                <optgroup label="Select Any Filter ( individual Months )">
                                    <?php foreach ( $months as $key => $month ) { ?>
                                        <option value="<?php echo esc_attr( $key + 1 ); ?>"><?php esc_html_e( $month, 'employee-&-hr-management' ); ?></option>
                                    <?php } ?>
                                </optgroup>
				                <optgroup label="Select Any Filter ( Combine Months )">
                                    <option value="14"><?php esc_html_e( 'Previous Three Month', 'employee-&-hr-management' );?></option>
                                    <option value="15"><?php esc_html_e( 'Previous Six Month', 'employee-&-hr-management' );?></option>
                                    <option value="16"><?php esc_html_e( 'Previous Nine Month', 'employee-&-hr-management' );?></option>
                                    <option value="17"><?php esc_html_e( 'Previous One Year', 'employee-&-hr-management' );?></option>
                                </optgroup>
                            </select>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <select class="form-control" id="report_type" name="report_type">
                                <option value="all"><?php esc_html_e( 'All Days', 'employee-&-hr-management' ); ?></option>
                                <option value="attend"><?php esc_html_e( 'Only Attend days', 'employee-&-hr-management' ); ?></option>
                                <option value="absent"><?php esc_html_e( 'Only Absent Days', 'employee-&-hr-management' ); ?></option>
                            </select>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <button type="button" class="btn btn-block btn-lg btn-gradient-primary custom-btn" id="report_form_btn">
                                <i class="mdi mdi-note-text"></i> <?php esc_html_e('Generate Report', 'employee-&-hr-management'); ?>
                            </button>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <button type="button" class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-toggle="modal" data-target="#ImportReport">
                                <i class="mdi mdi-file-import"></i> <?php esc_html_e( 'Import', 'employee-&-hr-management' ); ?>
                            </button>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="<?php echo esc_url( WL_EHRM_PLUGIN_URL . 'sample/sample.zip' ); ?>" class="btn btn-block btn-lg btn-gradient-primary custom-btn" download>
                                <i class="mdi mdi-download"></i> <?php esc_html_e( 'Download Sample File', 'employee-&-hr-management' ); ?>
                            </a>
                        </li>
                    </ul>
                </form>
            </nav>
        </div>
        <div class="row report_row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card table_card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="table_expand_row">
                                <button class="btn btn-sm btn-gradient-success custom-btn" id="btn-show-all-children" type="button">
                                    <?php esc_html_e( 'Expand All', 'employee-&-hr-management' ); ?>
                                </button>
                                <button class="btn btn-sm btn-gradient-danger custom-btn" id="btn-hide-all-children" type="button">
                                    <?php esc_html_e( 'Collapse All', 'employee-&-hr-management' ); ?>
                                </button>
                            </div>
                            <table id="report_table" class="table table-striped report_table" cellspacing="0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e( 'Date', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Day', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Office In', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Office Out', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Working Hours', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'IP', 'employee-&-hr-management' ); ?></th>
                                        <th class="none"><?php esc_html_e( 'Other Details', 'employee-&-hr-management' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="report_tbody">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php esc_html_e( 'No data', 'employee-&-hr-management' ); ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th><?php esc_html_e( 'Date', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Day', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Office In', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Office Out', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Working Hours', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'IP', 'employee-&-hr-management' ); ?></th>
                                        <th class="none"><?php esc_html_e( 'Other Details', 'employee-&-hr-management' ); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="download_report_csv">
            <div class="col-lg-6 grid-margin stretch-card" id="report_salary_result"></div>
            <div class="col-lg-6 grid-margin stretch-card" id="csv_form_div">
                <div class="card table_card">
                    <div class="card-body salary_status_ul">
                    <h4 class="card-title"><?php esc_html_e( 'Download CSV report', 'employee-&-hr-management' ); ?></h4>
                        <form class="forms-sample" method="post" id="csv_download_form" autocomplete="off">
                            <div class="form-group">
                                <label for="download_strt"><?php esc_html_e( 'From', 'employee-&-hr-management' ); ?></label>
                                <input type="text" class="form-control datetimepicker-input" placeholder="<?php esc_html_e( 'Select date, Format:- YYYY-MM-DD', 'employee-&-hr-management' ); ?>" name="download_strt" id="download_strt" data-toggle="datetimepicker" data-target="#download_strt"/>
                            </div>
                            <div class="form-group">
                                <label for="download_to"><?php esc_html_e( 'To', 'employee-&-hr-management' ); ?></label>
                                <input type="text" class="form-control datetimepicker-input" placeholder="<?php esc_html_e( 'Select date, Format:- YYYY-MM-DD', 'employee-&-hr-management' ); ?>" name="download_to" id="download_to" data-toggle="datetimepicker" data-target="#download_to"/>
                            </div>
                            <input type="hidden" value="" name="csv_user_id" id="csv_user_id">
                            <input type="hidden" value="" name="csv_report_type" id="csv_report_type">
                            <input type="hidden" value="" name="csv_report_month" id="csv_report_month">
                            <input type="hidden" name="report_action" value="export_settings" />
                            <?php wp_nonce_field( 'report_export_nonce', 'report_export_nonce' ); ?>
                            <input type="submit" class="btn btn-gradient-primary mr-2" name="csv_download_btn" id="csv_download_btn" value="<?php esc_html_e( 'Download', 'employee-&-hr-management' ); ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-file-export"></i>
                </span>
                <?php esc_html_e( 'Advance Report ( Export )', 'employee-&-hr-management' ); ?>
            </h3>
        </div>
        <div class="row report_row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card table_card">
                    <div class="card-body"> 
                        <form class="forms-sample" method="post" id="adv_export_form" autocomplete="off">
                            <div class="row">
                                <div class="col-lg-4">
                                    <h4 class="card-title"><?php esc_html_e( 'Export Date', 'employee-&-hr-management' ); ?></h4>
                                    <div class="form-group">
                                        <label for="adv_export_strt"><?php esc_html_e( 'From', 'employee-&-hr-management' ); ?></label>
                                        <input type="text" class="form-control datetimepicker-input" placeholder="<?php esc_html_e( 'Select date, Format:- YYYY-MM-DD', 'employee-&-hr-management' ); ?>" name="adv_export_strt" id="adv_export_strt" data-toggle="datetimepicker" data-target="#adv_export_strt"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="adv_export_to"><?php esc_html_e( 'To', 'employee-&-hr-management' ); ?></label>
                                        <input type="text" class="form-control datetimepicker-input" placeholder="<?php esc_html_e( 'Select date, Format:- YYYY-MM-DD', 'employee-&-hr-management' ); ?>" name="adv_export_to" id="adv_export_to" data-toggle="datetimepicker" data-target="#adv_export_to"/>
                                    </div>
                                    <h4 class="card-title"><?php esc_html_e( 'For', 'employee-&-hr-management' ); ?></h4>
                                    <div class="form-group">
                                        <label for="adv_export_staffs"><?php esc_html_e( 'Select Employees', 'employee-&-hr-management' ); ?></label>
                                        <select class="form-control member-select" id="adv_export_staffs" name="adv_export_staffs" multiple data-live-search="true">
                                            <?php foreach ( $staffs as $key => $staff ) { ?>
                                                <option value="<?php echo esc_attr( trim( $staff['ID'] ) ); ?>"><?php echo esc_html( $staff['fullname'] ); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <h4 class="card-title"><?php esc_html_e( 'Columns', 'employee-&-hr-management' ); ?></h4>
                                    <label for="export_column" class="export_column_label"><?php esc_html_e( 'Select Columns to Display', 'employee-&-hr-management' ); ?></label>
                                    <div class="form-group row export_column_div">
                                        <div class="col-sm-3">
                                            <div class="form-check form-check-success">
                                              <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" id="selectAll" name="selectAll"  checked>
                                                <?php esc_html_e( 'Select All', 'employee-&-hr-management' ); ?>
                                                <i class="input-helper"></i></label>
                                            </div>
                                        </div>
                                        <?php foreach ( EHRMHelperClass::get_column_array() as $key => $value ) { ?>
                                        <div class="col-sm-3">
                                            <div class="form-check form-check-success">
                                              <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input export_column" name="export_column[]" value="<?php echo esc_attr( trim( $value ) ); ?>" checked>
                                                <?php esc_html_e( $value, 'employee-&-hr-management' ); ?>
                                                <i class="input-helper"></i></label>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="advance_export_action" value="export_settings" />
                            <?php wp_nonce_field( 'advance_export_nonce', 'advance_export_nonce' );; ?>
                            <input type="submit" class="btn btn-gradient-primary mr-2" name="adv_export_form_btn" id="adv_export_form_btn" value="<?php esc_html_e( 'Export', 'employee-&-hr-management' ); ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export report Modal -->
        <div class="modal fade" id="ExpoerReportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-notify modal-center modal-lg export-modal-custom">
            <div class="modal-content">
                <div class="card">
                    <div class="card-body">
                        <div class="exportreportdata">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Report Modal -->
		<div class="modal fade" id="EditReports" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Report Details', 'employee-&-hr-management' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_report_form" autocomplete="off">
	                  	<div class="form-group">
	                      <label for="edit_name"><?php esc_html_e( 'Staff Name', 'employee-&-hr-management' ); ?></label>
	                      <input type="text" class="form-control" id="edit_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>" readonly>
	                    </div>
	                    <div class="form-group">
                          <label for="edit_date"><?php esc_html_e( 'Date', 'employee-&-hr-management' ); ?></label>
                          <input type="text" class="form-control" id="edit_date" placeholder="<?php esc_html_e( 'Date', 'employee-&-hr-management' ); ?>" readonly>
                        </div>
                        <div class="form-group">
                  			<label><?php esc_html_e( 'Office IN', 'employee-&-hr-management' ); ?></label>
				        	<input type="text" class="form-control datetimepicker-input" placeholder="<?php esc_html_e( 'Format:- 10:00 AM', 'employee-&-hr-management' ); ?>" id="edit_office_in" data-toggle="datetimepicker" data-target="#edit_office_in"/>
                        </div>
                        <div class="form-group">
                  			<label><?php esc_html_e( 'Office OUT', 'employee-&-hr-management' ); ?></label>
				        	<input type="text" class="form-control datetimepicker-input" placeholder="<?php esc_html_e( 'Format:- 10:00 AM', 'employee-&-hr-management' ); ?>" id="edit_office_out" data-toggle="datetimepicker" data-target="#edit_office_out"/>
                        </div>
                        <div class="form-group">
                  			<label><?php esc_html_e( 'Lunch IN', 'employee-&-hr-management' ); ?></label>
				        	<input type="text" class="form-control datetimepicker-input" placeholder="<?php esc_html_e( 'Format:- 10:00 AM', 'employee-&-hr-management' ); ?>" id="edit_lunch_in" data-toggle="datetimepicker" data-target="#edit_lunch_in"/>
                        </div>
                        <div class="form-group">
                  			<label><?php esc_html_e( 'Lunch OUT', 'employee-&-hr-management' ); ?></label>
				        	<input type="text" class="form-control datetimepicker-input" placeholder="<?php esc_html_e( 'Format:- 10:00 AM', 'employee-&-hr-management' ); ?>" id="edit_lunch_out" data-toggle="datetimepicker" data-target="#edit_lunch_out"/>
                  		</div>
                        <div class="form-group">
	                      	<label for="edit_report_punctual"><?php esc_html_e( 'Punctuality', 'employee-&-hr-management' ); ?></label>
	                      	<select name="edit_report_punctual" id="edit_report_punctual" class="form-control">
	                      		<option value="Late"><?php esc_html_e( 'Late', 'employee-&-hr-management' ); ?></option>
	                      		<option value="On Time"><?php esc_html_e( 'On Time', 'employee-&-hr-management' ); ?></option>
	                      	</select>
                        </div>
                        <div class="form-group">
                  			<label><?php esc_html_e( 'Working Hours', 'employee-&-hr-management' ); ?></label>
				        	<input type="text" class="form-control"  id="edit_working_hours" />
                  		</div>
                        <div class="form-group">
                          <label for="edit_late"><?php esc_html_e( 'Late Reason', 'employee-&-hr-management' ); ?></label>
	                      <textarea class="form-control" rows="4" id="edit_late" name="edit_late" placeholder="<?php esc_html_e( 'Description....', 'employee-&-hr-management' ); ?>"></textarea>
                        </div>
                        <div class="form-group">
                          <label for="edit_work"><?php esc_html_e( 'Daily Work Report', 'employee-&-hr-management' ); ?></label>
	                      <textarea class="form-control" rows="4" id="edit_work" name="edit_work" placeholder="<?php esc_html_e( 'Description....', 'employee-&-hr-management' ); ?>"></textarea>
                        </div>
                        <input type="hidden" name="report_key" id="report_key" value="">
                        <input type="hidden" name="edit_staff_id" id="edit_staff_id" value="">
                        <input type="button" class="btn btn-gradient-primary mr-2" id="edit_report_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
        </div>

        <!-- Import Report Modal -->
        <div class="modal fade" id="ImportReport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-notify modal-info">
            <div class="modal-content">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php esc_html_e( 'Import Employee Report', 'employee-&-hr-management' ); ?></h4>
                          <form class="md-form" method="post" enctype="multipart/form-data" autocomplete="off">
                            <div class="form-group">
                                <label for="holiday_status"><?php esc_html_e( 'Select Employee', 'employee-&-hr-management' ); ?></label>
                                <select class="form-control" id="import_staffid" name="import_staffid">
                                    <option value=""><?php esc_html_e( 'Select Staff member', 'employee-&-hr-management' ); ?></option>
                                    <?php foreach ( $staffs as $key => $staff ) { ?>
                                        <option value="<?php echo esc_attr( $staff['ID'] ); ?>"><?php echo esc_html( $staff['fullname'] ); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="holiday_status"><?php esc_html_e( 'CSV File', 'employee-&-hr-management' ); ?></label>
                                <div class="file-field export">
                                    <a class="btn-floating purple-gradient mt-0 float-left">
                                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                        <input type="file" name="import_file" required>
                                    </a>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="hidden" placeholder="Upload your file">
                                        <input type="hidden" name="report_action" value="import_settings" />
                                        <?php wp_nonce_field( 'report_import_nonce', 'report_import_nonce' ); ?>
                                        <?php submit_button( __( 'Import' ), 'secondary', 'submit_import', false ); ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
          </div>
        </div>
        
    </div>
</div>
