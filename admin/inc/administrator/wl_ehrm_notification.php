<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );

$api_data        = get_option( 'ehrm_notification_api' );
$from_name       = isset( $api_data['from_name'] ) ? sanitize_text_field( $api_data['from_name'] ) : get_bloginfo();
$logo_image      = isset( $api_data['logo'] ) ? sanitize_text_field( $api_data['logo'] ) : '';
$footer_txt      = isset( $api_data['footer'] ) ? sanitize_text_field( $api_data['footer'] ) : get_bloginfo().''.esc_html__( '  - Powered by Employee & HR Management', 'employee-&-hr-management' );
$email_optin     = isset( $api_data['email_optin'] ) ? sanitize_text_field( $api_data['email_optin'] ) : 'default';
$email_from      = isset( $api_data['email_from'] ) ? sanitize_text_field( $api_data['email_from'] ) : get_bloginfo('admin_email');
$smtp_hostname   = isset( $api_data['smtp_hostname'] ) ? sanitize_text_field( $api_data['smtp_hostname'] ) : 'smtp.gmail.com';
$smtp_port       = isset( $api_data['smtp_port'] ) ? sanitize_text_field( $api_data['smtp_port'] ) : 587;
$smtp_encription = isset( $api_data['smtp_encription'] ) ? sanitize_text_field( $api_data['smtp_encription'] ) : 'tls';
$smtp_user       = isset( $api_data['smtp_user'] ) ? sanitize_text_field( $api_data['smtp_user'] ) : '';
$smtp_passwd     = isset( $api_data['smtp_passwd'] ) ? sanitize_text_field( $api_data['smtp_passwd'] ) : '';
$sendgrid_api    = isset( $api_data['sendgrid_api'] ) ? sanitize_text_field( $api_data['sendgrid_api'] ) : '';
$sms_enable      = isset( $api_data['sms_enable'] ) ? sanitize_text_field( $api_data['sms_enable'] ) : 'no';
$sms_from_name   = isset( $api_data['sms_from_name'] ) ? sanitize_text_field( $api_data['sms_from_name'] ) : get_bloginfo();
$nexmo_api       = isset( $api_data['nexmo_api'] ) ? sanitize_text_field( $api_data['nexmo_api'] ) : '';
$nexmo_secret    = isset( $api_data['nexmo_secret'] ) ? sanitize_text_field( $api_data['nexmo_secret'] ) : '';
$sms_admin_no    = isset( $api_data['sms_admin_no'] ) ? sanitize_text_field( $api_data['sms_admin_no'] ) : '';

?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-email-variant"></i>                 
                </span>
                <?php esc_html_e( 'Configure Mail/SMS Channels', 'employee-&-hr-management' ); ?>
              </h3>
        </div>
        <div class="row calander_table_div3">
            <div class="col-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body salary_status_ul">
                        <!-- Nav pills -->
                        <ul class="nav nav-pills">
                          <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#home"><?php esc_html_e( 'Mail', 'employee-&-hr-management' ); ?></a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#menu1"><?php esc_html_e( 'SMS', 'employee-&-hr-management' ); ?></a>
                          </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="home">
                                <form action="" method="post" accept-charset="utf-8" id="mail_api_settings" class="">
                                    <h4><?php esc_html_e( 'Choose SMTP or a vendor-specific API:', 'employee-&-hr-management' ); ?></h4>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Type', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" id="email_api_optin" name="email_api_optin">
                                                        <option value="default" <?php selected( $email_optin, 'default' ); ?>><?php esc_html_e( 'Default', 'employee-&-hr-management' ); ?></option>
                                                        <option value="smtp" <?php selected( $email_optin, 'smtp' ); ?>><?php esc_html_e( 'SMTP', 'employee-&-hr-management' ); ?></option>
                                                        <option value="sendgrid" <?php selected( $email_optin, 'sendgrid' ); ?>><?php esc_html_e( 'SendGrid API', 'employee-&-hr-management' ); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Envelope From Email Address', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="email_from" name="email_from" value="<?php echo esc_attr( $email_from ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Envelope From Name', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="from_name" name="from_name" value="<?php echo esc_attr( $from_name ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Logo Image', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-7">
                                                    <input class="form-control" id="logo_image_mail" name="logo_image_mail" value="<?php echo esc_attr( $logo_image ) ?>">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="button" name="upload-btn" id="upload-btn-ehrm" class="btn btn-block btn-lg btn-gradient-primary custom-btn" value="<?php esc_html_e( 'Upload File', 'employee-&-hr-management' ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Footer Text', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <textarea class="form-control" rows="4" id="footer_txt" name="footer_txt" placeholder="<?php echo esc_attr( get_bloginfo().'  - Powered by Employee & HR Management'); ?>"><?php echo esc_html( $footer_txt ); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 checkboxes">
                                            <div class="form-group row checkboxes">
                                                <label class="col-sm-12 col-form-label"><?php esc_html_e( 'Enable Mail Notifications for:-', 'employee-&-hr-management' ); ?></label>
                                                <?php 
                                                    $email_notifications = EHRMHelperClass::get_email_notification_list();
                                                    foreach ( $email_notifications as $key => $value ) {

                                                        if ( isset( $api_data[$key] ) && $api_data[$key] == 'yes' ) {
                                                            $classs = 'form-check-success';
                                                        } else {
                                                            $classs = 'form-check-danger';
                                                        }
                                                ?>
                                                <div class="col-sm-3">
                                                    <div class="form-check <?php echo esc_attr( $classs ); ?>">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" name="<?php echo esc_attr( $key ) ?>" value="yes" <?php if ( isset( $api_data[$key] ) && $api_data[$key] == 'yes' ) { echo esc_attr( 'checked' ); } ?>>
                                                        <?php esc_html_e( $value, 'employee-&-hr-management' ); ?>
                                                        <i class="input-helper"></i></label>
                                                    </div>
                                                </div>
                                                    <?php }  ?>
                                            </div>
                                            <br>
                                                <span class="option-info-text-notifi">
                                                <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                                                <?php esc_html_e( 'Email will shoot only on enabled option or selected options.', 'employee-&-hr-management' ); ?>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <h4 class="row_disable_heding"><?php esc_html_e( 'Authentication', 'employee-&-hr-management' ); ?></h4>
                                    <div class="row smtp_row <?php if ( $email_optin != 'smtp' ) { echo esc_attr( 'visible_row_api' ); } ?>">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Outgoing Mail Server Hostname', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="email" class="form-control" id="smtp_hostname" name="smtp_hostname" value="<?php echo esc_attr( $smtp_hostname ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Outgoing Mail Server Port', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?php echo esc_attr( $smtp_port ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Encryption', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="smtp_encription" name="smtp_encription" value="<?php echo esc_attr( $smtp_encription ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Username', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="smtp_user" name="smtp_user" value="<?php echo esc_attr( $smtp_user ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Password', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="password" class="form-control" id="smtp_passwd" name="smtp_passwd" value="<?php echo esc_attr( $smtp_passwd ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="row sendgrid_row <?php if ( $email_optin != 'sendgrid' ) { echo esc_attr( 'visible_row_api' ); } ?>">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Sendgrid API Key', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="password" class="form-control" id="sendgrid_api" name="sendgrid_api" value="<?php echo esc_attr( $sendgrid_api ); ?>">
                                                </div>
                                                <span class="form-span-description">
                                                    <?php echo esc_html__( 'Create an account at ', 'employee-&-hr-management' ).''.wp_kses_post( '<a href="https://sendgrid.com/">SendGrid.com</a>' ).''. esc_html__( ' and enter an ', 'employee-&-hr-management' ).''.wp_kses_post( '<a href="https://app.sendgrid.com/settings/api_keys">API.com</a>' ).''. esc_html__( ' key below.', 'employee-&-hr-management' ); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div> 
                                </form>
                            </div>
                            <div class="tab-pane fade" id="menu1">
                                <form action="" method="post" accept-charset="utf-8" id="sms_api_form">
                                    <div class="row">
                                    <?php
                                        if ( ! empty ( $sms_enable ) && $sms_enable == 'yes' ) {
                                            $classs = 'form-check-success';
                                        } else {
                                            $classs = 'form-check-danger';
                                        }
                                    ?>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-check <?php echo esc_attr( $classs ); ?>">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input"  name="sms_enable" value="yes" <?php if ( ! empty ( $sms_enable ) && $sms_enable == 'yes' ) { echo esc_attr( 'checked' ); } ?>>
                                                <?php esc_html_e( 'Enable SMS Notification', 'employee-&-hr-management' ); ?>
                                                <i class="input-helper"></i></label>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Enter phone no. with country code like "91XXXXXXXXXX"', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="sms_admin_no" name="sms_admin_no" value="<?php echo esc_attr( $sms_admin_no ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'SMS From Name', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="sms_from_name" name="sms_from_name" value="<?php echo esc_attr( $sms_from_name ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Nexmo SMS API Key', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="nexmo_api" name="nexmo_api" value="<?php echo esc_attr( $nexmo_api ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Nexmo SMS API Secret', 'employee-&-hr-management' ); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="password" class="form-control" id="nexmo_secret" name="nexmo_secret" value="<?php echo esc_attr( $nexmo_secret ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <span class="option-info-text-notifi">
                                                <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                                                <?php echo esc_html__( 'Create an account at ', 'employee-&-hr-management' ).''.wp_kses_post( '<a href="https://www.nexmo.com/">nexmo.com</a>' ).''. esc_html__( ' and enter an ', 'employee-&-hr-management' ).''.wp_kses_post( '<a href="https://dashboard.nexmo.com/getting-started-guide">API Key</a>' ).''. esc_html__( ' below.', 'employee-&-hr-management' ); ?>
                                            </span>
                                        </div>
                                        <div class="col-lg-12 col-md-12 checkboxes">
                                            <div class="form-group row checkboxes">
                                                <label class="col-sm-12 col-form-label"><?php esc_html_e( 'Enable SMS Notifications for:-', 'employee-&-hr-management' ); ?></label>
                                                <?php 
                                                    $sms_notifications = EHRMHelperClass::get_sms_notification_list();
                                                    foreach ( $sms_notifications as $key => $value ) {

                                                        if ( isset( $api_data[$key] ) && $api_data[$key] == 'yes' ) {
                                                            $classs = 'form-check-success';
                                                        } else {
                                                            $classs = 'form-check-danger';
                                                        }
                                                ?>
                                                <div class="col-sm-3">
                                                    <div class="form-check <?php echo esc_attr( $classs ); ?>">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" name="<?php echo esc_attr( $key ) ?>" value="yes" <?php if ( isset( $api_data[$key] ) && $api_data[$key] == 'yes' ) { echo esc_attr( 'checked' ); } ?>>
                                                        <?php esc_html_e( $value, 'employee-&-hr-management' ); ?>
                                                        <i class="input-helper"></i></label>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <br>
                                                <span class="option-info-text-notifi">
                                                <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                                                <?php esc_html_e( 'SMS will go only for enable options.', 'employee-&-hr-management' ); ?>
                                                </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group row">
                                <button type="button" class="btn btn-lg btn-gradient-success mr-2" id="save_notifiaction_api_btn">
                                    <?php esc_html_e( 'Save Changes', 'employee-&-hr-management' ); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-gmail"></i>                 
                </span>
                <?php esc_html_e( 'Email Notification Templates', 'employee-&-hr-management' ); ?>
            </h3>
        </div>
        <div class="row calander_table_div4">
            <div class="col-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title noti_sub_title"><?php esc_html_e( 'Templates', 'employee-&-hr-management' ); ?></h4>
                        <div class="table-responsive">
		                  	<table class="table table-striped notification_emails_table">
		                    	<thead>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Email', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Description', 'employee-&-hr-management' ); ?></th>
			                     	</tr>
                                </thead>
                                <tbody id="notification_emails_tbody">
                                    <tr>
                                        <td>1.</td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'Employee welcome', 'employee-&-hr-management' ); ?>" data-value="employee_welcome"><?php esc_html_e( 'Employee welcome', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>Welcome email to new employees.</td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'New Leave Request', 'employee-&-hr-management' ); ?>" data-value="new_leave_request"><?php esc_html_e( 'New Leave Request', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>New leave request notification to HR Manager.</td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'Approved Leave Request', 'employee-&-hr-management' ); ?>" data-value="approved_leave_request"><?php esc_html_e( 'Approved Leave Request', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>Approved leave request notification to employee.</td>
                                    </tr>
                                    <tr>
                                        <td>4.</td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'Rejected Leave Request', 'employee-&-hr-management' ); ?>" data-value="rejected_leave_request"><?php esc_html_e( 'Rejected Leave Request', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>Rejected leave request notification to employee.</td>
                                    </tr>
                                    <tr>
                                        <td>5.</td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'New Project Assigned', 'employee-&-hr-management' ); ?>" data-value="new_project_assigned"><?php esc_html_e( 'New Project Assigned', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>New project assigned notification to employee.</td>
                                    </tr>
                                    <tr>
                                        <td>6.</td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'New Task Assigned', 'employee-&-hr-management' ); ?>" data-value="new_task_assigned"><?php esc_html_e( 'New Task Assigned', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>New task assigned notification to employee.</td>
                                    </tr>
                                    <tr>
                                        <td>7.</td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'New Comment On Task', 'employee-&-hr-management' ); ?>" data-value="new_comment_assigned"><?php esc_html_e( 'New Comment On Task', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>Alert Will Go Whenever A Comment Created On Task.</td>
                                    </tr>
                                    <tr>
                                        <td>8.</td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'New Employee Introduction Email', 'employee-&-hr-management' ); ?>" data-value="new_contact_assigned"><?php esc_html_e( 'New Employee Introduction Email', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>New Employee joining Announcement.</td>
                                    </tr>
                                    <tr>
                                        <td>9.</td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'New Notice Alert', 'employee-&-hr-management' ); ?>" data-value="new_notice_assigned"><?php esc_html_e( 'New Notice Alert', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>Alert Will Go Whenever An Notice Created.</td>
                                    </tr>
                                </tbody>
                                <tfoot>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Email', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Description', 'employee-&-hr-management' ); ?></th>
			                     	</tr>
		                    	</tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Notice Modal -->
    		<div class="modal fade" id="ShoeEmailOptions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    		  <div class="modal-dialog modal-lg modal-notify modal-info">
    		    <div class="modal-content">
    		     	<div class="card">
    	                <div class="card-body">
    	                  <h4 class="card-title"></h4>
    	                  <form class="forms-sample" method="post" id="email_modal_options">
    	                  	<div class="form-group">
    	                      <label for="email_subject"><?php esc_html_e( 'Subject', 'employee-&-hr-management' ); ?></label>
    	                      <input type="text" class="form-control" id="email_subject" placeholder="<?php esc_html_e( 'Email Subject', 'employee-&-hr-management' ); ?>">
    	                    </div>
    	                    <div class="form-group">
    	                      <label for="email_heading"><?php esc_html_e( 'Email Heading', 'employee-&-hr-management' ); ?></label>
                              <input type="text" class="form-control" id="email_heading" placeholder="<?php esc_html_e( 'Email heading....', 'employee-&-hr-management' ); ?>">
    	                    </div>
                            <div class="form-group">
    	                        <label for="notice_desc"><?php esc_html_e( 'Email Body', 'employee-&-hr-management' ); ?></label>
    	                        <?php wp_editor( '', 'email_body', $settings = array( 'editor_height' => 200, 'textarea_rows' => 20, 'drag_drop_upload' => true ) ); ?>
                            </div>
                            <div class="form-group">
    	                      <label for="tags"><?php esc_html_e( 'Template Tags', 'employee-&-hr-management' ); ?></label>
                              <p class="email_template_tags"><p>
                            </div>
                            <input type="hidden" id='email_template_tags' value="">
                            <input type="hidden" id="email_id_name" name="email_id_name" value="">
    	                    <input type="button" class="btn btn-gradient-primary mr-2" id="update_email_options" value="<?php esc_html_e( 'Save changes', 'employee-&-hr-management' ); ?>">
    	                  </form>
    	                </div>
    	            </div>
    		    </div>
    		  </div>
    		</div>

        </div>

        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-cellphone-android"></i>                 
                </span>
                <?php esc_html_e( 'SMS Notification Templates', 'employee-&-hr-management' ); ?>
            </h3>
        </div>
        <div class="row calander_table_div4">
            <div class="col-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title noti_sub_title"><?php esc_html_e( 'Templates', 'employee-&-hr-management' ); ?></h4>
                        <div class="table-responsive">
		                  	<table class="table table-striped notification_emails_table">
		                    	<thead>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'SMS', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Description', 'employee-&-hr-management' ); ?></th>
			                     	</tr>
                                </thead>
                                <tbody id="notification_emails_tbody">
                                    <tr>
                                        <td>1.</td>
                                        <td>
                                            <a href="#" class="sms_template_settings" data-name="<?php echo esc_attr_e( 'New Leave Request', 'employee-&-hr-management' ); ?>" data-value="sms_new_leave_request"><?php esc_html_e( 'New Leave Request', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>New leave request notification to HR Manager.</td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>
                                            <a href="#" class="sms_template_settings" data-name="<?php echo esc_attr_e( 'Approved Leave Request', 'employee-&-hr-management' ); ?>" data-value="sms_approved_leave_request"><?php esc_html_e( 'Approved Leave Request', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>Approved leave request notification to employee.</td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>
                                            <a href="#" class="sms_template_settings" data-name="<?php echo esc_attr_e( 'Rejected Leave Request', 'employee-&-hr-management' ); ?>" data-value="sms_rejected_leave_request"><?php esc_html_e( 'Rejected Leave Request', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>Rejected leave request notification to employee.</td>
                                    </tr>
                                    <tr>
                                        <td>4.</td>
                                        <td>
                                            <a href="#" class="sms_template_settings" data-name="<?php echo esc_attr_e( 'New Project Assigned', 'employee-&-hr-management' ); ?>" data-value="sms_new_project_assigned"><?php esc_html_e( 'New Project Assigned', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>New project assigned notification to employee.</td>
                                    </tr>
                                    <tr>
                                        <td>5.</td>
                                        <td>
                                            <a href="#" class="sms_template_settings" data-name="<?php echo esc_attr_e( 'New Task Assigned', 'employee-&-hr-management' ); ?>" data-value="sms_new_task_assigned"><?php esc_html_e( 'New Task Assigned', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>New task assigned notification to employee.</td>
                                    </tr>
                                    <tr>
                                        <td>6.</td>
                                        <td>
                                            <a href="#" class="sms_template_settings" data-name="<?php echo esc_attr_e( 'New Comment On Task', 'employee-&-hr-management' ); ?>" data-value="sms_new_comment_assigned"><?php esc_html_e( 'New Comment On Task', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>Alert Will Go Whenever A Comment Created On Task.</td>
                                    </tr>
                                    <tr>
                                        <td>7.</td>
                                        <td>
                                            <a href="#" class="sms_template_settings" data-name="<?php echo esc_attr_e( 'New Notice Alert', 'employee-&-hr-management' ); ?>" data-value="sms_new_notice_assigned"><?php esc_html_e( 'New Notice Alert', 'employee-&-hr-management' ); ?></a>
                                        </td>
                                        <td>Alert Will Go Whenever An Notice Created.</td>
                                    </tr>
                                </tbody>
                                <tfoot>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'SMS', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Description', 'employee-&-hr-management' ); ?></th>
			                     	</tr>
		                    	</tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Notice Modal -->
    		<div class="modal fade" id="showsmstemplateOptions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    		  <div class="modal-dialog modal-lg modal-notify modal-info">
    		    <div class="modal-content">
    		     	<div class="card">
    	                <div class="card-body">
    	                  <h4 class="card-title"></h4>
    	                  <form class="forms-sample" method="post" id="sms_modal_options">
                            <div class="form-group">
    	                        <label for="notice_desc"><?php esc_html_e( 'SMS Body', 'employee-&-hr-management' ); ?></label>
    	                        <?php wp_editor( '', 'sms_body', $settings = array( 'editor_height' => 200, 'textarea_rows' => 20, 'drag_drop_upload' => true ) ); ?>
                            </div>
                            <div class="form-group">
    	                      <label for="tags"><?php esc_html_e( 'Template Tags', 'employee-&-hr-management' ); ?></label>
                              <p class="sms_template_tags"><p>
                            </div>
                            <input type="hidden" id='sms_template_tags' value="">
                            <input type="hidden" id="sms_id_name" name="sms_id_name" value="">
    	                    <input type="button" class="btn btn-gradient-primary mr-2" id="update_sms_options" value="<?php esc_html_e( 'Save changes', 'employee-&-hr-management' ); ?>">
    	                  </form>
    	                </div>
    	            </div>
    		    </div>
    		  </div>
    		</div>

        </div>

    </div>
</div>