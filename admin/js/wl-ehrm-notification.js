/* Admin End Scripts */
jQuery(document).ready(function () {
    'use strict';

    // tinymce.init({
    //     selector: 'textarea#email_body'
    // });
    tinymce.init({
        selector: 'textarea.wp-editor-area'
    });

    /* Upload Logo for mail html  */
    jQuery(document).on( 'click', '#upload-btn-ehrm', function (e) {
        e.preventDefault();
        var button = this;
        var image  = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open().on('select', function (e) {
            var uploaded_image = image.state().get('selection').first();
            var location_image = uploaded_image.toJSON().url;
            jQuery('#logo_image_mail').val(location_image);
        });
    });

    /***----------------------------------------------------------- Notification panel actions -----------------------------------------------------------***/

    jQuery(document).on('click', '.email_template_settings', function (e) {
        e.preventDefault();
        var value = jQuery( this ).attr('data-value');
        var name  = jQuery( this ).attr('data-name');

    	var nounce = ajax_notification.notification_nonce;
        jQuery.ajax({
            url: ajax_notification.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_email_options_data',
                value: value,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response.status == 'error' ) {
                		toastr.error(response.message);
                    } else {       
                        jQuery('#ShoeEmailOptions').modal('show');
                        // console.log(response.content.tags);
                        jQuery('#email_modal_options #email_subject').val(response.content.subject);
                        jQuery('#email_modal_options #email_heading').val(response.content.heading);
                        jQuery('#email_modal_options #email_id_name').val(value);
                        jQuery('#email_modal_options #email_template_tags').val(response.content.tags);
                        jQuery('.email_template_tags').text(response.content.tags);
                        jQuery('#ShoeEmailOptions h4').text(name);
                        tinyMCE.get('email_body').setContent(response.content.body);
                	}   
                }
            }
        });
    });

    jQuery(document).on('click', '.sms_template_settings', function (e) {
        e.preventDefault();
        var value = jQuery( this ).attr('data-value');
        var name  = jQuery( this ).attr('data-name');

    	var nounce = ajax_notification.notification_nonce;
        jQuery.ajax({
            url: ajax_notification.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_sms_options_data',
                value: value,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response.status == 'error' ) {
                		toastr.error(response.message);
                    } else {       
                        jQuery('#showsmstemplateOptions').modal('show');
                        jQuery('#sms_modal_options #sms_id_name').val(value);
                        jQuery('#sms_modal_options #sms_template_tags').val(response.content.tags);
                        jQuery('.sms_template_tags').text(response.content.tags);
                        jQuery('#showsmstemplateOptions h4').text(name);
                        // tinyMCE.get('sms_body').setContent(response.content.body);
                	}   
                }
            }
        });
    });

    jQuery(document).on('click', '#update_email_options', function (e) {
    	e.preventDefault();
    	var subject = jQuery( '#email_modal_options #email_subject' ).val();
    	var heading = jQuery( '#email_modal_options #email_heading' ).val();
    	var name    = jQuery( '#email_modal_options #email_id_name' ).val();
    	var tags    = jQuery( '#email_modal_options #email_template_tags' ).val();
    	var body    = tinyMCE.get('email_body').getContent();
        
    	var nounce  = ajax_notification.notification_nonce;
        jQuery.ajax({
            url: ajax_notification.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_save_email_options_ajax',
                subject: subject,
                heading: heading,
                body: body,
                name: name,
                tags: tags,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response.status == 'error' ) {
                		toastr.error(response.message);
                	} else {
                        toastr.success(response.message);
                        jQuery('#ShoeEmailOptions').modal('hide');
                	}   
                }
            }
        });
    });

    jQuery(document).on('click', '#update_sms_options', function (e) {
    	e.preventDefault();
    	var name = jQuery( '#sms_modal_options #sms_id_name' ).val();
    	var tags = jQuery( '#sms_modal_options #sms_template_tags' ).val();
    	var body = tinyMCE.get('sms_body').getContent();

    	var nounce = ajax_notification.notification_nonce;
        jQuery.ajax({
            url: ajax_notification.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_save_sms_options_ajax',
                body: body,
                name: name,
                tags: tags,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response.status == 'error' ) {
                		toastr.error(response.message);
                	} else {
                        toastr.success(response.message);
                        jQuery('#showsmstemplateOptions').modal('hide');
                	}   
                }
            }
        });
    });

    jQuery(document).on( 'select keyup change', '#mail_api_settings #email_api_optin', function (e) {
        e.preventDefault();
        var value = jQuery(this).val();
        if ( value == 'smtp' ) {
            jQuery('.smtp_row').css( 'display', 'flex' );
            jQuery('.sendgrid_row').hide();
        } else if ( value == 'sendgrid' ) {
            jQuery('.smtp_row').hide();
            jQuery('.sendgrid_row').css( 'display', 'flex' );
        } else {
            jQuery('.smtp_row').hide();
            jQuery('.sendgrid_row').hide();
        }
    });

    jQuery(document).on('click', '#save_notifiaction_api_btn', function (e) {
        e.preventDefault();

        var email_optin         = jQuery('#mail_api_settings #email_api_optin').val();
        var email_from          = jQuery('#mail_api_settings #email_from').val();
        var smtp_hostname       = jQuery('#mail_api_settings #smtp_hostname').val();
        var smtp_port           = jQuery('#mail_api_settings #smtp_port').val();
        var smtp_encription     = jQuery('#mail_api_settings #smtp_encription').val();
        var smtp_user           = jQuery('#mail_api_settings #smtp_user').val();
        var smtp_passwd         = jQuery('#mail_api_settings #smtp_passwd').val();
        var sendgrid_api        = jQuery('#mail_api_settings #sendgrid_api').val();
        var from_name           = jQuery('#mail_api_settings #from_name').val();
        var email_logo          = jQuery('#mail_api_settings #logo_image_mail').val();
        var footer_txt          = jQuery('#mail_api_settings #footer_txt').val();
        
        var sms_enable          = jQuery('input[name="sms_enable"]:checked').val();
        var sms_from_name       = jQuery('#sms_api_form #sms_from_name').val();
        var nexmo_api           = jQuery('#sms_api_form #nexmo_api').val();
        var nexmo_secret        = jQuery('#sms_api_form #nexmo_secret').val();
        var sms_admin_no        = jQuery('#sms_api_form #sms_admin_no').val();

        var mail_new_leave      = jQuery('input[name="mail_new_leave"]:checked').val();
        var mail_approv_leave   = jQuery('input[name="mail_approv_leave"]:checked').val();
        var mail_reject_leave   = jQuery('input[name="mail_reject_leave"]:checked').val();
        var mail_project_assign = jQuery('input[name="mail_project_assign"]:checked').val();
        var mail_task_assign    = jQuery('input[name="mail_task_assign"]:checked').val();
        var mail_comment_assign = jQuery('input[name="mail_comment_assign"]:checked').val();
        var mail_notice         = jQuery('input[name="mail_notice"]:checked').val();
        var mail_new_welcome    = jQuery('input[name="mail_new_welcome"]:checked').val();
        var introduction_mail   = jQuery('input[name="introduction_mail"]:checked').val();
        
        var sms_new_leave      = jQuery('input[name="sms_new_leave"]:checked').val();
        var sms_approv_leave   = jQuery('input[name="sms_approv_leave"]:checked').val();
        var sms_reject_leave   = jQuery('input[name="sms_reject_leave"]:checked').val();
        var sms_project_assign = jQuery('input[name="sms_project_assign"]:checked').val();
        var sms_task_assign    = jQuery('input[name="sms_task_assign"]:checked').val();
        var sms_comment_assign = jQuery('input[name="sms_comment_assign"]:checked').val();
        var sms_notice         = jQuery('input[name="sms_notice"]:checked').val();


        var nounce          = ajax_notification.notification_nonce;
        
        jQuery.ajax({
            url: ajax_notification.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_save_noti_api_ajax',
                email_optin: email_optin,
                email_from: email_from,
                smtp_hostname: smtp_hostname,
                smtp_port: smtp_port,
                smtp_encription: smtp_encription,
                smtp_user: smtp_user,
                smtp_passwd: smtp_passwd,
                sendgrid_api: sendgrid_api,
                from_name: from_name,
                email_logo: email_logo,
                footer_txt: footer_txt,
                sms_enable: sms_enable,
                sms_from_name: sms_from_name,
                nexmo_api: nexmo_api,
                nexmo_secret: nexmo_secret,
                sms_admin_no: sms_admin_no,
                mail_new_leave: mail_new_leave,
                mail_approv_leave: mail_approv_leave,
                mail_reject_leave: mail_reject_leave,
                mail_project_assign: mail_project_assign,
                mail_task_assign: mail_task_assign,
                mail_comment_assign: mail_comment_assign,
                mail_notice: mail_notice,
                mail_new_welcome: mail_new_welcome,
                introduction_mail: introduction_mail,
                sms_new_leave: sms_new_leave,
                sms_approv_leave: sms_approv_leave,
                sms_reject_leave: sms_reject_leave,
                sms_project_assign: sms_project_assign,
                sms_task_assign: sms_task_assign,
                sms_comment_assign: sms_comment_assign,
                sms_notice: sms_notice,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response.status == 'error' ) {
                		toastr.error(response.message);
                	} else {
                        toastr.success(response.message);
                	}   
                }
            }
        });

    });

});