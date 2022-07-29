<?php
defined( 'ABSPATH' ) or die();

/**
 *  calss for set default options
 */
class SetDeafaultOptions {

	public static function ehrm_activation_actions() {

		do_action( 'ehrm_extension_activation' );

	}

	public static function ehrm_activation_default_emails() {

		do_action( 'ehrm_default_emails_activation' );

	}

	public static function ehrm_activation_default_sms() {

		do_action( 'ehrm_default_sms_activation' );

	}
	
	public static function default_settings() {

		$all_settings = get_option( 'ehrm_settings_data' );

		if ( empty ( $all_settings ) ) {
			$ehrm_settings_data = array(
				'timezone'         => 'Asia/Kolkata',
				'date_format'      => 'F j Y',
				'time_format'      => 'g:i A',
				'monday_status'    => 'Working',
				'tuesday_status'   => 'Working',
				'wednesday_status' => 'Working',
				'thursday_status'  => 'Working',
				'friday_status'    => 'Working',
				'saturday_status'  => 'Working',
				'sunday_status'    => 'Off',
				'halfday_start'    => '',
				'halfday_end'      => '',
				'lunch_start'      => '',
				'lunch_end'        => '',
				'cur_symbol'       => '₹',
				'cur_position'     => 'Right',
				'geo_location'     => 'Yes',
				'ehrm_gmap_api'    => '',
				'salary_method'    => 'Monthly',
				'lunchtime'        => 'Include',
				'shoot_mail'       => 'Yes',
				'ip_restriction'   => 'No',
				'ip_rest_type'     => 'single',
				'restrict_ips'     => '',
				'restrict_ips2'	   => '',		
				'show_holiday'     => 'Yes',
				'show_report'      => 'Yes',
				'show_notice'      => 'Yes',
				'late_reson'       => 'Yes',
				'salary_status'    => 'Yes',
				'show_projects'    => 'Yes',
				'mail_logo'        => '',
				'office_in_sub'    => __( 'Login Alert From Employee & HR Management', 'employee-&-hr-management' ),
				'office_out_sub'   => __( 'Logout Alert From Employee & HR Management', 'employee-&-hr-management' ),
				'mail_heading'     => __( 'Staff Login/Logout Details', 'employee-&-hr-management' ),
				'user_roles'       => serialize( array( 'subscriber' ) ),
			);

			update_option( 'ehrm_settings_data', $ehrm_settings_data );
		}
	}

	public static function ehrm_allow_subscriber_uploads() {
		if ( current_user_can('subscriber') && !current_user_can('upload_files') ){
			$contributor = get_role('subscriber');
			$contributor->add_cap('upload_files');
		}
	}

	public static function ehrm_users_own_attachments( $wp_query_obj ) {

		global $current_user, $pagenow;
	
		$is_attachment_request = ($wp_query_obj->get('post_type')=='attachment');
	
		if( !$is_attachment_request )
			return;
	
		if( !is_a( $current_user, 'WP_User') )
			return;
	
		if( !in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) )
			return;
	
		if( !current_user_can('delete_pages') )
			$wp_query_obj->set('author', $current_user->ID );
	
		return;
	}

	public static function ehrm_setup_wizard_activation_hook() {
		add_option( 'wl_ehrm_setup_wizard', true );
	}

	public static function ehrm_setup_wizard_redirect() {
		if ( get_option( 'wl_ehrm_setup_wizard', false ) ) {
			delete_option( 'wl_ehrm_setup_wizard' );
			if ( ! isset( $_GET['activate-multi'] ) ) {
				wp_redirect( "index.php?page=ehrm-setup-wizard" );
			}
		}
	}

	public static function ehrm_setup_default_emails() {

		//Employee welcome
        $welcome = [
            'subject' => 'Welcome {full_name} to {company_name}',
            'heading' => 'Welcome Onboard {first_name}!',
			'body'    => 'Dear {full_name},
			<br>
			Welcome aboard as a <strong>{job_title}</strong> in our team at <strong>{company_name}</strong>! I am pleased to have you working with us. You were selected for employment due to the attributes that you displayed that appear to match the qualities I look for in an employee.
			<br>
			I’m looking forward to seeing you grow and develop into an outstanding employee that exhibits a high level of care, concern, and compassion for others. I hope that you will find your work to be rewarding, challenging, and meaningful.
			<br>
			Please take your time and review our yearly goals so that you can know what is expected and make a positive contribution. Again, I look forward to seeing you grow as a professional while enhancing the lives of the clients entrusted in your care.
			<br>
			Sincerely,
			<br>
			Manager Name
			<br>
			CEO, Company Name
			<br>',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {full_name}, {last_name}, {first_name}, {job_title}, {company_name}.'

		];
		update_option( 'ehrm_email_employee_welcome', $welcome );
		//EOD Employee welcome

		//New Leave Request
        $new_leave_request = [
            'subject' => 'New leave request received from {employee_name}',
            'heading' => 'New Leave Request',
            'body'    => 'Hello,
			<br>
			A new leave request has been received from {employee_name}.
			<br>
			<strong>Date:</strong> {date_from} to {date_to}
			<br>
			<strong>Days:</strong> {no_days}
			<br>
			<strong>Reason:</strong> {reason}
			<br>
			<br>
			Please approve/reject this leave application.
			<br>
			Thanks.',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {date_from},{date_to}, {no_days}, {reason}.'
		];

		update_option( 'ehrm_email_new_leave_request', $new_leave_request );
		//EOD New Leave Request

		//Approved Leave Request
		$approved_request = [
			'subject' => 'Your leave request has been approved',
			'heading' => 'Leave Request Approved',
			'body'    => 'Hello {employee_name},
			<br>
			Your leave request for <strong>{no_days} days</strong> from {date_from} to {date_to} has been approved.
			<br>
			Regards
			<br>
			Manager Name
			<br>
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {date_from},{date_to}, {no_days}, {reason}.'
		];
		update_option( 'ehrm_email_approved_leave_request', $approved_request );
		//EOD Approved Leave Request

		//Rejected Leave Request
		$reject_request = [
			'subject' => 'Your leave request has been rejected',
			'heading' => 'Leave Request Rejected',
			'body'    => 'Hello {employee_name},
			<br>
			Your leave request for <strong>{no_days} days</strong> from {date_from} to {date_to} has been rejected.
			<br>
			Regards
			<br>
			Manager Name
			<br>
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {date_from},{date_to}, {no_days}.'
		];
		update_option( 'ehrm_email_rejected_leave_request', $reject_request );
		//EOD Rejected Leave Request

		// New Project Assigned
		$new_project_assigned = [
			'subject' => 'New project has been assigned to you',
			'heading' => 'New project Assigned',
			'body'    => 'Hello {employee_name},
			<br>
			A new project <strong>{project_title}</strong> has been assigned to you by {created_by}.
			<br>
			Regards
			<br>
			Manager Name
			<br>
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {project_title},{created_by}.'
		];
		update_option( 'ehrm_email_new_project_assigned', $new_project_assigned );
		//EOD New Project Assigned
		
		// New Task Assigned
		$new_task_assigned = [
			'subject' => 'New task has been assigned to you',
			'heading' => 'New Task Assigned',
			'body'    => 'Hello {employee_name},
			<br>
			A new task <strong>{task_title}</strong> has been assigned to you by {created_by}, from project {project_title}.
			<br>
			Due Date: {due_date}
			<br>
			Regards
			<br>
			Manager Name
			<br>
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {project_title}, {task_title},{created_by}, {due_date}.'
		];
		update_option( 'ehrm_email_new_task_assigned', $new_task_assigned );
		//EOD New Task Assigned

		// New Comment Assigned
		$new_comment_assigned = [
			'subject' => 'New comment has been created',
			'heading' => 'New Comment Created',
			'body'    => 'Hello {employee_name},
			<br>
			A new Comment on <strong>{task_title}</strong> has been created by {created_by}, from project {project_title}.
			<br>
			Regards
			<br>
			Manager Name
			<br>
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {project_title}, {task_title}, {created_by}, {comment_text}, {task_due_date}.'
		];
		update_option( 'ehrm_email_new_comment_assigned', $new_comment_assigned );
		//EOD New Comment Assigned

		//New Notice Created
        $new_notice_request = [
            'subject' => 'New Notice has been created ( {site_name} )',
            'heading' => 'You have new notice from {site_name}',
            'body'    => 'Hello <strong>{employee_name}</strong>,
			<br>
			{notice_text}.
			<br>
			Thankyou.
			<br><br>
			Regards
			<br>
			Manager Name
			<br>
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {site_name}, {employee_name}, {notice_text}.'
		];
		update_option( 'ehrm_email_new_notice_assigned', $new_notice_request );
		//EOD New Notice Created

		// New Employee Introduction Email
		$new_contact_assigned = [
			'subject' => 'New Employee Introduction Email',
			'heading' => 'New Employee joining Announcement',
			'body'    => 'Dear Staffs,
			<br>
			I\'m happy to let you know that {employee_name} has accepted our job offer and joined the team as a quality {employee_designation}.
			<br>
			You can contact him/her by {employee_email}.
			<br>
			I appreciate you joining me in providing a warm welcome for {employee_name} with excitement.
			<br>
			Regards
			<br>
			Name of Department Manager/Boss
			<br>
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {employee_email},{employee_designation}.'
		];
		update_option( 'ehrm_email_new_contact_assigned', $new_contact_assigned );
		//EOD New Employee Introduction Email
	}

	public static function ehrm_setup_default_sms() {

		//New Leave Request
        $new_leave_request = [
            'body'    => 'Hello,
			A new leave request has been received from {employee_name}.
			Date: {date_from} to {date_to}
			Days: {no_days}
			Reason: {reason}

			Please approve/reject this leave application.

			Thanks.',
			'tags'   => 'You may use these template tags inside body and those will be replaced by original values: {employee_name}, {date_from},{date_to}, {no_days}, {reason}.'
		];
		update_option( 'ehrm_sms_new_contact_assigned', $new_leave_request );
		//EOD New Leave Request

		//Approved Leave Request
		$approved_request = [
			'body'    => 'Hello {employee_name},
			Your leave request for {no_days} days from {date_from} to {date_to} has been approved.
			Regards
			Manager Name
			Company',
			'tags'   => 'You may use these template tags inside body and those will be replaced by original values: {employee_name}, {date_from},{date_to}, {no_days}, {reason}.'
		];
		update_option( 'ehrm_sms_approved_leave_request', $approved_request );
		//EOD Approved Leave Request

		//Rejected Leave Request
		$reject_request = [
			'body'    => 'Hello {employee_name},
			Your leave request for <strong>{no_days} days</strong> from {date_from} to {date_to} has been rejected.
			Regards
			Manager Name
			Company',
			'tags'   => 'You may use these template tags inside body and those will be replaced by original values: {employee_name}, {date_from},{date_to}, {no_days}.'
		];
		update_option( 'ehrm_sms_rejected_leave_request', $reject_request );
		//EOD Rejected Leave Request

		// New Project Assigned
		$new_project_assigned = [
			'body'    => 'Hello {employee_name},
			A new project <strong>{project_title}</strong> has been assigned to you by {created_by}.
			Regards
			Manager Name
			Company',
			'tags'   => 'You may use these template tags inside body and those will be replaced by original values: {employee_name}, {project_title},{created_by}.'
		];
		update_option( 'ehrm_sms_new_project_assigned', $new_project_assigned );
		//EOD New Project Assigned
		
		// New Task Assigned
		$new_task_assigned = [
			'body'    => 'Hello {employee_name},
			A new task <strong>{task_title}</strong> has been assigned to you by {created_by}, from project {project_title}.
			Due Date: {due_date}
			Regards
			Manager Name
			Company',
			'tags'   => 'You may use these template tags inside body and those will be replaced by original values: {employee_name}, {project_title}, {task_title},{created_by}, {due_date}.'
		];
		update_option( 'ehrm_sms_new_task_assigned', $new_task_assigned );
		//EOD New Task Assigned

		// New Comment Assigned
		$new_comment_assigned = [
			'body'    => 'Hello {employee_name},
			A new Comment on <strong>{task_title}</strong> has been created by {created_by}, from project {project_title}.
			Manager Name
			Company',
			'tags'   => 'You may use these template tags inside body and those will be replaced by original values: {employee_name}, {project_title}, {task_title}, {created_by}, {comment_text}, {task_due_date}.'
		];
		update_option( 'ehrm_sms_new_comment_assigned', $new_comment_assigned );
		//EOD New Comment Assigned

		//New Notice Created
        $new_notice_request = [
			'body' => 'Hello <strong>{employee_name}</strong>,
			{notice_text}.
			Thankyou.
			Regards
			Manager Name
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {site_name}, {employee_name}, {notice_text}.'
		];
		update_option( 'ehrm_sms_new_notice_assigned', $new_notice_request );
		//EOD New Notice Created

	}

	public static function remove_items() {
		delete_option( 'wl-ehrm-key' );
		delete_option( 'wl-ehrm-valid' );
		delete_option( 'wl-ehrm-cache' );
		delete_option( 'wl-employee-and-hr-management-updation-detail' );
	}
	
}