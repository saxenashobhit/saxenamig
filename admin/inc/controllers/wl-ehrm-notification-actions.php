<?php
defined( 'ABSPATH' ) or die();
/**
 *  Ajax Action calls for notifications menu
 */
class NotificationsAjaxAction {

	public static function set_phpmailer_details( $phpmailer ) {

		$api_data = get_option( 'ehrm_notification_api' );
	
		if ( ! empty ( $api_data['email_from'] ) ) {
			$sender_mail = $api_data['email_from'];
		} else {
			$sender_mail = get_option( 'admin_email' );
		}
	
		$phpmailer->isSMTP();
		$phpmailer->Host       = $api_data['smtp_hostname']; //gmail smtp host
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Port       = (int) $api_data['smtp_port'];
		$phpmailer->Username   = $api_data['smtp_user'];
		$phpmailer->Password   = $api_data['smtp_passwd'];
		$phpmailer->SMTPSecure = $api_data['smtp_encription'];
		$phpmailer->From       = $sender_mail;
		$phpmailer->FromName   = get_bloginfo('name');
	}
	
	public static function show_email_template_data() {
		check_ajax_referer( 'notification_ajax_nonce', 'nounce' );
		// $message = '';
		if ( ! empty ( $_POST['value'] ) ) {
			$value      = sanitize_text_field( $_POST['value'] );
			$email_data = get_option( 'ehrm_email_'.$value );
			$status     = 'success';
			$content    = $email_data;
            
		} else {

			if ( empty ( $_POST['value'] ) ) {
				$message = __( 'Data Attribute not found.!', 'employee-&-hr-management' );
			} else {
				$message = __( 'Something went wrong.!', 'employee-&-hr-management' );
			}

			$status  = 'error';
			$content = '';
		}

		$return = array(
			'status'  => $status,
			'message' => $message,
			'content' => $content,
		);

		wp_send_json( $return );
		wp_die();
	}

	public static function save_email_template_data() {
        check_ajax_referer( 'notification_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) ) {
            $subject   = sanitize_text_field( $_POST['subject'] );
			$heading   = sanitize_text_field( $_POST['heading'] );
			$name      = sanitize_text_field( $_POST['name'] );
			$tags      = sanitize_text_field( $_POST['tags'] );
            $body      = $_POST['body'];
            $email_data = get_option( 'ehrm_email_'.$name );
            
            $data = array(
				'subject' => $subject,
				'heading' => $heading,
				'body'    => $body,
				'tags'    => $tags
            );
            
            if ( update_option( 'ehrm_email_'.$name, $data ) ) {
                $status  = 'success';
				$message = __( 'Saved successfully!', 'employee-&-hr-management' );
            } else {
                $status  = 'error';
				$message = __( 'Data not saved!', 'employee-&-hr-management' );
            }

        } else {

			if ( empty ( $_POST['name'] ) ) {
				$message = __( 'Email template id not found.!', 'employee-&-hr-management' );
			} else {
				$message = __( 'Something went wrong.!', 'employee-&-hr-management' );
			}

			$status  = 'error';
		}

		$return = array(
			'status'  => $status,
			'message' => $message
		);

		wp_send_json( $return );
		wp_die();
	}

	public static function show_sms_template_data() {
		check_ajax_referer( 'notification_ajax_nonce', 'nounce' );
		if ( ! empty ( $_POST['value'] ) ) {
			$value    = sanitize_text_field( $_POST['value'] );
			$sms_data = get_option( 'ehrm_'.$value );
			$status   = 'success';
			$content  = $sms_data;
            
		} else {

			if ( empty ( $_POST['value'] ) ) {
				$message = __( 'Data Attribute not found.!', 'employee-&-hr-management' );
			} else {
				$message = __( 'Something went wrong.!', 'employee-&-hr-management' );
			}

			$status  = 'error';
			$content = '';
		}

		$return = array(
			'status'  => $status,
			'message' => $message,
			'content' => $content,
		);

		wp_send_json( $return );
		wp_die();
	}

	public static function save_sms_template_data() {
        check_ajax_referer( 'notification_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) ) {
			$name      = sanitize_text_field( $_POST['name'] );
			$tags      = sanitize_text_field( $_POST['tags'] );
            $body      = $_POST['body'];
            $email_data = get_option( 'ehrm_'.$name );
            
            $data = array(
				'body'    => $body,
				'tags'    => $tags
            );
            
            if ( update_option( 'ehrm_'.$name, $data ) ) {
                $status  = 'success';
				$message = __( 'Saved successfully!', 'employee-&-hr-management' );
            } else {
                $status  = 'error';
				$message = __( 'Data not saved!', 'employee-&-hr-management' );
            }

        } else {

			if ( empty ( $_POST['name'] ) ) {
				$message = __( 'SMS template id not found.!', 'employee-&-hr-management' );
			} else {
				$message = __( 'Something went wrong.!', 'employee-&-hr-management' );
			}

			$status  = 'error';
		}

		$return = array(
			'status'  => $status,
			'message' => $message
		);

		wp_send_json( $return );
		wp_die();
	}

	public static function save_notification_api_data() {
		check_ajax_referer( 'notification_ajax_nonce', 'nounce' );
		if ( ! empty ( $_POST['email_optin'] ) && ! empty ( $_POST['email_from'] ) && ! empty ( $_POST['from_name'] ) ) {

			$email_optin = sanitize_text_field( $_POST['email_optin'] );
			$sms_enable  = sanitize_text_field( $_POST['sms_enable'] );
			$message     = '';
			$api_data    = get_option( 'ehrm_notification_api' );

			if ( $email_optin == 'smtp' ) {
				if ( empty ( $_POST['smtp_hostname'] ) ) {
					$message = __( 'Please enter Mail Server hostname', 'employee-&-hr-management' );
				} elseif ( empty ( $_POST['smtp_port'] ) ) {
					$message = __( 'Please enter Mail Server port', 'employee-&-hr-management' );
				} elseif ( empty ( $_POST['smtp_encription'] ) ) {
					$message = __( 'Please enter encription type', 'employee-&-hr-management' );
				} elseif ( empty ( $_POST['smtp_user'] ) ) {
					$message = __( 'please enter username or email.', 'employee-&-hr-management' );
				} elseif ( empty ( $_POST['smtp_passwd'] ) ) {
					$message = __( 'Please enter password.', 'employee-&-hr-management' );
				}
			} elseif ( $email_optin == 'sendgrid' ) {
				if ( empty ( $_POST['sendgrid_api'] ) ) {
					$message = __( 'Please enter your SendGrid API key.!', 'employee-&-hr-management' );
				}
			}

			if ( $sms_enable == 'yes' ) {
				if ( empty ( $_POST['sms_from_name'] ) ) {
					$message = __( 'Please enter SMS From Name', 'employee-&-hr-management' );
				} elseif ( empty ( $_POST['nexmo_api'] ) ) {
					$message = __( 'Please enter Nexmo SMS API Key', 'employee-&-hr-management' );
				} elseif ( empty ( $_POST['nexmo_secret'] ) ) {
					$message = __( 'Please enter Nexmo SMS API Secret', 'employee-&-hr-management' );
				} elseif ( empty ( $_POST['sms_admin_no'] ) ) {
					$message = __( 'Please enter Administrator Phone no.', 'employee-&-hr-management' );
				}
			}

			if ( empty ( $message ) ) {
				$data = array(
					'email_optin'         => $email_optin,
					'email_from'          => sanitize_text_field( $_POST['email_from'] ),
					'from_name'           => sanitize_text_field( $_POST['from_name'] ),
					'email_logo'          => sanitize_text_field( $_POST['email_logo'] ),
					'footer_txt'          => sanitize_text_field( $_POST['footer_txt'] ),
					'smtp_hostname'       => sanitize_text_field( $_POST['smtp_hostname'] ),
					'smtp_port'           => sanitize_text_field( $_POST['smtp_port'] ),
					'smtp_encription'     => sanitize_text_field( $_POST['smtp_encription'] ),
					'smtp_user'           => sanitize_text_field( $_POST['smtp_user'] ),
					'smtp_passwd'         => sanitize_text_field( $_POST['smtp_passwd'] ),
					'sendgrid_api'        => sanitize_text_field( $_POST['sendgrid_api'] ),
					'sms_enable'          => sanitize_text_field( $_POST['sms_enable'] ),
					'sms_from_name'       => sanitize_text_field( $_POST['sms_from_name'] ),
					'nexmo_api'           => sanitize_text_field( $_POST['nexmo_api'] ),
					'nexmo_secret'        => sanitize_text_field( $_POST['nexmo_secret'] ),
					'sms_admin_no'        => sanitize_text_field( $_POST['sms_admin_no'] ),
					'mail_new_leave'      => sanitize_text_field( $_POST['mail_new_leave'] ),
					'mail_approv_leave'   => sanitize_text_field( $_POST['mail_approv_leave'] ),
					'mail_reject_leave'   => sanitize_text_field( $_POST['mail_reject_leave'] ),
					'mail_project_assign' => sanitize_text_field( $_POST['mail_project_assign'] ),
					'mail_task_assign'    => sanitize_text_field( $_POST['mail_task_assign'] ),
					'mail_comment_assign' => sanitize_text_field( $_POST['mail_comment_assign'] ),
					'mail_notice'         => sanitize_text_field( $_POST['mail_notice'] ),
					'mail_new_welcome'    => sanitize_text_field( $_POST['mail_new_welcome'] ),
					'introduction_mail'   => sanitize_text_field( $_POST['introduction_mail'] ),
					'sms_new_leave'       => sanitize_text_field( $_POST['sms_new_leave'] ),
					'sms_approv_leave'    => sanitize_text_field( $_POST['sms_approv_leave'] ),
					'sms_reject_leave'    => sanitize_text_field( $_POST['sms_reject_leave'] ),
					'sms_project_assign'  => sanitize_text_field( $_POST['sms_project_assign'] ),
					'sms_task_assign'     => sanitize_text_field( $_POST['sms_task_assign'] ),
					'sms_comment_assign'  => sanitize_text_field( $_POST['sms_comment_assign'] ),
					'sms_notice'          => sanitize_text_field( $_POST['sms_notice'] ),
				);

				if ( update_option( 'ehrm_notification_api', $data ) ) {
					$status  = 'success';
					$message = __( 'Data saved successfully.!', 'employee-&-hr-management' );
				} else {
					$status  = 'error';
					$message = __( 'Data not saved successfully.!', 'employee-&-hr-management' );
				}
				
			} else {
				$status  = 'error';
			}
            
		} else {

			if ( empty ( $_POST['email_optin'] ) ) {
				$message = __( 'Select Type please.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['email_from'] ) ) {
				$message = __( 'Please enter envelope email Address.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['from_name'] ) ) {
				$message = __( 'Please enter envelope email From Name.!', 'employee-&-hr-management' );
			} else {
				$message = __( 'Something went wrong.!', 'employee-&-hr-management' );
			}

			$status  = 'error';
		}

		$return = array(
			'status'  => $status,
			'message' => $message,
		);

		wp_send_json( $return );
		wp_die();
	}
}