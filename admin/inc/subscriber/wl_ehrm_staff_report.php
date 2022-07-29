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
                <?php esc_html_e( 'Reports', 'employee-&-hr-management' ); ?>
            </h3>
            <nav aria-label="breadcrumb" class="report">
                <form method="post" id="report_form" action="">
                    <ul class="breadcrumb">
                        <input type="hidden" name="report_staff_id" id="report_staff_id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />
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
                        <li class="breadcrumb-item active" aria-current="page">
                            <button type="button" class="btn btn-block btn-lg btn-gradient-primary custom-btn" id="report_form_btn">
                                <i class="mdi mdi-note-text"></i> <?php esc_html_e( 'Generate Report', 'employee-&-hr-management' ); ?>
                            </button>
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
                                <button class="btn btn-sm btn-gradient-success custom-btn" id="btn-show-all-children" type="button"><?php esc_html_e( 'Expand All', 'employee-&-hr-management' ); ?></button>
                                <button class="btn btn-sm btn-gradient-danger custom-btn" id="btn-hide-all-children" type="button"><?php esc_html_e( 'Collapse All', 'employee-&-hr-management' ); ?></button>
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
                    <h4 class="card-title"><?php esc_html_e( 'Download report', 'employee-&-hr-management' ); ?></h4>
                        <form class="forms-sample" method="post" id="csv_download_form">
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
        
    </div>
</div>