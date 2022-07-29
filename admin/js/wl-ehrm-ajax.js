/* Admin End Ajax Scripts */
jQuery(document).ready(function () {
    'use strict';
    
    /** For getting ip via jquery **/
    jQuery( "#get_ip_btn" ).on( "click", function(){
        var ip_address = ajax_backend.restrict_ip;
        jQuery( "#restrict_ips" ).val( ip_address );
    }); 
    jQuery( "#get_ip_remove" ).on( "click", function(){
        var ip_address = " ";
        jQuery( "#restrict_ips" ).val( ip_address );
    });

    /***-----------------------------------------------------------Designation-----------------------------------------------------------***/

    /* Add Department details */
    jQuery(document).on('click', '#add_department_btn', function (e) {
        e.preventDefault();

        var deparment        = [];
        var department_descp = [];
        var deparment_head   = [];
        jQuery( 'input[name^=department_name]' ).each( function(){
            deparment.push( jQuery( this ).val() );
        });
        jQuery( 'input[name^=department_description]' ).each( function(){
            department_descp.push( jQuery( this ).val() );
        });
        jQuery( 'input[name^=department_head]' ).each( function(){
            deparment_head.push( jQuery( this ).val() );
        });

        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_add_department_action',
                deparment: deparment,
                department_descp: department_descp,
                department_head: deparment_head,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        //location.reload();
                    }   
                }
            }
        });
    });

	/* Add Designation details */
    jQuery(document).on('click', '#add_designation_btn', function (e) {
    	e.preventDefault();
    	var deparment = jQuery( '#add_designation_form #staff_department' ).val();
    	var name      = jQuery( '#add_designation_form #designation_name' ).val();
    	var color     = jQuery( '#add_designation_form #designation_color' ).val();
    	var status    = jQuery( '#add_designation_form #designation_status' ).val();

    	var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_add_designation_action',
                deparment: deparment,
                name: name,
                color: color,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response.status == 'error' ) {
                		toastr.error(response.message);
                	} else {
                        toastr.success(response.message);
                        jQuery( '#AddDesignation' ).modal('hide');
                        jQuery( '#designation_tbody' ).empty();
                        jQuery( '#designation_tbody' ).append(response.content);
                        jQuery( '#add_designation_form #staff_department' ).val(' ');
                        jQuery( '#add_designation_form #designation_name' ).val(' ');
                        jQuery( '#add_designation_form #designation_status' ).val(' ');
                        jQuery( '#add_designation_form .wp-color-result').css('background-color', 'none');
                        jQuery( '#add_designation_form #designation_color' ).val(' ');
                	}   
                }
            }
        });
    });

    /* Edit Designation details */
    jQuery(document).on('click', '.designation-edit-a', function (e) {
    	e.preventDefault();
    	var key    = jQuery(this).attr('data-designation');
    	var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_edit_designation_action',
                key: key,
                nounce: nounce,
            },
            success: function ( response ) {
                console.log("yahoo");
                if ( response ) {
                	if ( response == 'Something went wrong.!' ) {
                		toastr.error(response);
                	} else {                       
                        jQuery( '#EditDesignation' ).modal('show');
                        jQuery( '#edit_designation_form #edit_staff_department' ).val(response.department_id).change();
                        jQuery( '#edit_designation_form #edit_designation_name' ).val(response.designation_name);
                        jQuery( '#edit_designation_form #edit_designation_color' ).val(response.designation_color);
                        jQuery( '#edit_designation_form .wp-color-result').css('background-color', response.designation_color);                     
                        jQuery( '#edit_designation_form #edit_designation_status' ).val(response.designation_status).change();
                        jQuery( '#edit_designation_form #designation_key' ).val(key);
                	}   
                }
            }
        });
    });

    /* Update Designation details */
    jQuery(document).on('click', '#edit_designation_btn', function (e) {
        e.preventDefault();
        var key       = jQuery( '#edit_designation_form #designation_key').val();
        var deparment = jQuery( '#edit_designation_form #edit_staff_department' ).val();
        var name      = jQuery( '#edit_designation_form #edit_designation_name' ).val();
        var color     = jQuery( '#edit_designation_form #edit_designation_color' ).val();
        var status    = jQuery( '#edit_designation_form #edit_designation_status' ).val();

        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_update_designation_action',
                deparment: deparment,
                name: name,
                key: key,
                color: color,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery( '#EditDesignation' ).modal('hide');
                        location.reload();
                        // jQuery( '#designation_tbody' ).empty();
                        // jQuery( '#designation_tbody' ).append(response.content);
                    }   
                }
            }
        });
    });

    /* Delete Designation details */
    jQuery(document).on('click', '.designation-delete-a', function (e) {
        e.preventDefault();
        var key    = jQuery(this).attr('data-designation');
        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_delete_designation_action',
                key: key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#designation_tbody').empty();
                        jQuery('#designation_tbody').append(response.content);
                    }   
                }
            }
        });
    });

    /***-----------------------------------------------------------Event-----------------------------------------------------------***/
    /* Add Events details */
    jQuery(document).on('click', '#add_event_btn', function (e) {
        e.preventDefault();
        var name    = jQuery('#add_event_form #event_name').val();
        var desc    = jQuery('#add_event_form #event_desc').val();
        var date    = jQuery('#add_event_form #event_date').val();
        var time    = jQuery('#add_event_form #event_time').val();
        var status  = jQuery('#add_event_form #event_status').val();
        var nounce  = ajax_backend.backend_nonce;

        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_add_event_action',
                name: name,
                desc: desc,
                date: date,
                time: time,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#AddEvents').modal('hide');
                        jQuery('#event_tbody').empty();
                        jQuery('#event_tbody').append(response.content);
                        jQuery('#add_event_form #event_name').val(' ');
                        jQuery('#add_event_form #event_desc').val(' ');
                        jQuery('#add_event_form #event_date').val(' ');
                        jQuery('#add_event_form #event_time').val(' ');
                    }   
                }
            }
        });
    });

    /* Edit Event details */
    jQuery(document).on('click', '.event-edit-a', function (e) {
        e.preventDefault();
        var key    = jQuery(this).attr('data-event');
        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_edit_event_action',
                key: key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response == 'Something went wrong.!' ) {
                        toastr.error(response);
                    } else {                      
                        jQuery( '#EditEvent').modal('show');
                        jQuery( '#edit_event_form #edit_event_name' ).val(response.name);
                        jQuery( '#edit_event_form #edit_event_desc' ).val(response.desc);
                        jQuery( '#edit_event_form #edit_event_date' ).val(response.date);                    
                        jQuery( '#edit_event_form #edit_event_time' ).val(response.time);
                        jQuery( '#edit_event_form #edit_event_status' ).val(response.status);
                        jQuery( '#edit_event_form #event_key' ).val(key);
                    }   
                }
            }
        });
    });

    /* Update Event details */
    jQuery(document).on('click', '#edit_event_btn', function (e) {
        e.preventDefault();
        var key    = jQuery( '#edit_event_form #event_key').val();
        var name   = jQuery( '#edit_event_form #edit_event_name' ).val();
        var desc   = jQuery( '#edit_event_form #edit_event_desc' ).val();
        var date   = jQuery( '#edit_event_form #edit_event_date' ).val();
        var time   = jQuery( '#edit_event_form #edit_event_time' ).val();
        var status = jQuery( '#edit_event_form #edit_event_status' ).val();

        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_update_event_action',
                desc: desc,
                name: name,
                key: key,
                date: date,
                time: time,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#EditEvent').modal('hide');
                        jQuery('#event_tbody').empty();
                        jQuery('#event_tbody').append(response.content);
                    }   
                }
            }
        });
    });

    /* Delete Events details */
    jQuery(document).on('click', '.event-delete-a', function (e) {
        e.preventDefault();
        var key    = jQuery(this).attr('data-event');
        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_delete_event_action',
                key: key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#event_tbody').empty();
                        jQuery('#event_tbody').append(response.content);
                    }   
                }
            }
        });
    });

    /***-----------------------------------------------------------Holiday-----------------------------------------------------------***/
    /* Add Holidays details */
    jQuery(document).on('click', '#add_holiday_btn', function (e) {
        e.preventDefault();
        var name   = jQuery('#add_holiday_form #holiday_name').val();
        var start  = jQuery('#add_holiday_form #holiday_start').val();
        var to     = jQuery('#add_holiday_form #holiday_to').val();
        var status = jQuery('#add_holiday_form #holiday_status').val();
        var nounce = ajax_backend.backend_nonce;

        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_add_holiday_action',
                name: name,
                start: start,
                to: to,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#AddHolidays').modal('hide');
                        jQuery('#holiday_tbody').empty();
                        jQuery('#holiday_tbody').append(response.content);
                        jQuery('#add_holiday_form #holiday_name').val(' ');
                        jQuery('#add_holiday_form #holiday_start').val(' ');
                        jQuery('#add_holiday_form #holiday_to').val(' ');
                    }   
                }
            }
        });
    });

    /* Edit Holiday details */
    jQuery(document).on('click', '.holiday-edit-a', function (e) {
        e.preventDefault();
        var key    = jQuery(this).attr('data-holiday');
        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_edit_holiday_action',
                key: key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response == 'Something went wrong.!' ) {
                        toastr.error(response);
                    } else {
                        jQuery( '#EditHoliday').modal('show');
                        jQuery( '#edit_holiday_form #edit_holiday_name' ).val(response.name);
                        jQuery( '#edit_holiday_form #edit_holiday_start' ).val(response.start);
                        jQuery( '#edit_holiday_form #edit_holiday_to' ).val(response.to);                    
                        jQuery( '#edit_holiday_form #edit_holiday_status' ).val(response.status);
                        jQuery( '#edit_holiday_form #holiday_key' ).val(key);
                    }   
                }
            }
        });
    });

    /* Update Holidays details */
    jQuery(document).on('click', '#edit_holiday_btn', function (e) {
        e.preventDefault();
        var key    = jQuery( '#edit_holiday_form #holiday_key').val();
        var name   = jQuery( '#edit_holiday_form #edit_holiday_name' ).val();
        var start  = jQuery( '#edit_holiday_form #edit_holiday_start' ).val();
        var to     = jQuery( '#edit_holiday_form #edit_holiday_to' ).val();
        var status = jQuery( '#edit_holiday_form #edit_holiday_status' ).val();

        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_update_holiday_action',
                name: name,
                start: start,
                to: to,
                key: key,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#EditHoliday').modal('hide');
                        jQuery('#holiday_tbody').empty();
                        jQuery('#holiday_tbody').append(response.content);
                    }   
                }
            }
        });
    });

    /* Delete Holidays details */
    jQuery(document).on('click', '.holiday-delete-a', function (e) {
        e.preventDefault();
        var key    = jQuery(this).attr('data-holiday');
        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_delete_holiday_action',
                key: key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#holiday_tbody').empty();
                        jQuery('#holiday_tbody').append(response.content);
                    }   
                }
            }
        });
    });

    /** ----------------------------------------Notices---------------------------------------- **/
    /* Add notice details */
    jQuery(document).on('click', '#add_notice_btn', function (e) {
        e.preventDefault();
        var name    = jQuery('#add_notice_form #notice_name').val();
        var desc    = jQuery('#add_notice_form #notice_desc').val();
        var status  = jQuery('#add_notice_form #notice_status').val();
        var nounce  = ajax_backend.backend_nonce;

        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_add_notice_action',
                name: name,
                desc: desc,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#AddNotices').modal('hide');
                        jQuery('#notice_tbody').empty();
                        jQuery('#notice_tbody').append(response.content);
                        jQuery('#add_notice_form #notice_name').val(' ');
                        jQuery('#add_notice_form #notice_desc').val(' ');
                    }   
                }
            }
        });
    });

    /* Edit notice details */
    jQuery(document).on('click', '.notice-edit-a', function (e) {
        e.preventDefault();
        var key    = jQuery(this).attr('data-notice');
        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_edit_notice_action',
                key: key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response == 'Something went wrong.!' ) {
                        toastr.error(response);
                    } else { 
                        jQuery( '#EditNotice').modal('show');
                        jQuery( '#edit_notice_form #edit_notice_name' ).val(response.name);
                        jQuery( '#edit_notice_form #edit_notice_desc' ).val(response.desc);
                        jQuery( '#edit_notice_form #edit_notice_status' ).val(response.status);
                        jQuery( '#edit_notice_form #notice_key' ).val(key);
                    }   
                }
            }
        });
    });

    /* Update notice details */
    jQuery(document).on('click', '#edit_notice_btn', function (e) {
        e.preventDefault();
        var key    = jQuery( '#edit_notice_form #notice_key').val();
        var name   = jQuery( '#edit_notice_form #edit_notice_name' ).val();
        var desc   = jQuery( '#edit_notice_form #edit_notice_desc' ).val();
        var status = jQuery( '#edit_notice_form #edit_notice_status' ).val();

        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_update_notice_action',
                desc: desc,
                name: name,
                key: key,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#EditNotice').modal('hide');
                        jQuery('#notice_tbody').empty();
                        jQuery('#notice_tbody').append(response.content);
                    }   
                }
            }
        });
    });

    /* Delete Notice details */
    jQuery(document).on('click', '.notice-delete-a', function (e) {
        e.preventDefault();
        var key    = jQuery(this).attr('data-notice');
        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_delete_notice_action',
                key: key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#notice_tbody').empty();
                        jQuery('#notice_tbody').append(response.content);
                    }   
                }
            }
        });
    });

    /** -----------------------------------------------------------Shift--------------------------------------------------------- **/
    /* Add Shift details */
    jQuery(document).on('click', '#add_shift_btn', function () {
        var name   = jQuery( '#add_shift_form #shift_name' ).val();
        var start  = jQuery( '#add_shift_form #shift_start' ).val();
        var end    = jQuery( '#add_shift_form #shift_end' ).val();
        var late   = jQuery( '#add_shift_form #shift_late' ).val();
        var status = jQuery( '#add_shift_form #shift_status' ).val();

        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_add_shift_action',
                name: name,
                start: start,
                end: end,
                late: late,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);                      
                        jQuery('#AddShift').modal('hide');
                        jQuery('#shift_tbody').empty();
                        jQuery('#shift_tbody').append(response.content);
                        jQuery( '#add_shift_form #shift_name' ).val('');
                        jQuery( '#add_shift_form #shift_start' ).val('');
                        jQuery( '#add_shift_form #shift_end' ).val('');
                        jQuery( '#add_shift_form #shift_late' ).val('');
                        jQuery( '#add_shift_form #shift_status' ).val('');
                    }   
                }
            }
        });
    });

    /* Delete Shift details */
    jQuery(document).on('click', '.shift-delete-a', function () {
        var shift_key = jQuery(this).attr('data-shift');
        var nounce    = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_delete_shift_action',
                shift_key: shift_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#shift_tbody').empty();
                        jQuery('#shift_tbody').append(response.content);  
                    }   
                }
            }
        });
    });

    /* Edit Shift details */
    jQuery(document).on('click', '.shift-edit-a', function () {
        var shift_key = jQuery(this).attr('data-shift');
        var nounce    = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_edit_shift_action',
                shift_key: shift_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response == 'Something went wrong.!' ) {
                        toastr.error(response);
                    } else {
                        jQuery('#EditShift').modal('show');
                        jQuery('#edit_shift_name').val(response.name);
                        jQuery('#edit_shift_start').val(response.start);
                        jQuery('#edit_shift_end').val(response.end);
                        jQuery('#edit_shift_late').val(response.late);
                        jQuery('#edit_shift_select').val(response.select);
                        jQuery('#shift_key').val(shift_key);
                    }   
                }
            }
        });
    });

    /* Update Shift details */
    jQuery(document).on('click', '#edit_shift_btn', function () {
        var name      = jQuery( '#edit_shift_form #edit_shift_name' ).val();
        var start     = jQuery( '#edit_shift_form #edit_shift_start' ).val();
        var end       = jQuery( '#edit_shift_form #edit_shift_end' ).val();
        var late      = jQuery( '#edit_shift_form #edit_shift_late' ).val();
        var status    = jQuery( '#edit_shift_form #edit_shift_status' ).val();
        var shift_key = jQuery( '#edit_shift_form #shift_key' ).val();
        var nounce    = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_update_shift_action',
                name: name,
                start: start,
                end: end,
                late: late,
                status: status,
                shift_key: shift_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#EditShift').modal('hide');
                        jQuery('#shift_tbody').empty();
                        // jQuery('#shift_tbody').append(response.content);
                        location.reload();
                    }   
                }
            }
        });
    });

    /** -----------------------------------------------------------Staff--------------------------------------------------------- **/
    /* Fetch user details */
    jQuery(document).on('change keyup keydown', '#select_user_id', function () {
        var staff_key = jQuery(this).val();
        var nounce    = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_fetch_staff_action',
                staff_key: staff_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response == 'No data' || response == 'Something went wrong.!' ) {
                        toastr.error(response);
                    } else {
                        jQuery('#add_staff_form #user_name').val(response.user_login);
                        jQuery('#add_staff_form #first_name').val(response.first_name);
                        jQuery('#add_staff_form #last_name').val(response.last_name);
                        jQuery('#add_staff_form #staff_email').val(response.user_email);
                    }   
                }
            }
        });
    });

    /* Add Staff details */
    jQuery(document).on('click', '#add_staff_btn', function () {

        var leave_name  = [];
        var leave_value = [];
        var name        = jQuery( '#add_staff_form #user_name' ).val();
        var first       = jQuery( '#add_staff_form #first_name' ).val();
        var last        = jQuery( '#add_staff_form #last_name' ).val();
        var email       = jQuery( '#add_staff_form #staff_email' ).val();
        var phone       = jQuery( '#add_staff_form #staff_phone' ).val();
        var shift       = jQuery( '#add_staff_form #user_shift' ).val();
        var designation = jQuery( '#add_staff_form #user_designation' ).val();
        var pay_type    = jQuery( '#add_staff_form #pay_type' ).val();
        var salary      = jQuery( '#add_staff_form #staff_salary' ).val();
        var status      = jQuery( '#add_staff_form #staff_status' ).val();
        var staff       = jQuery('#add_staff_form #select_user_id').val();
        var location    = jQuery( '#add_staff_form #staff_location' ).val();

        jQuery( 'input[name^=leave_name]' ).each( function(){
            leave_name.push( jQuery( this ).val() );
        });
        
        jQuery( 'input[name^=leave_value]' ).each( function(){
            leave_value.push( jQuery( this ).val() );
        });

        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_add_staff_action',
                name: name,
                first: first,
                last: last,
                email: email,
                phone: phone,
                shift: shift,
                staff: staff,
                designation: designation,
                pay_type: pay_type,
                salary: salary,
                status: status,
                leave_value: leave_value,
                leave_name: leave_name,
                nounce: nounce,
                location: location,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);                 
                    } else {
                        toastr.success(response.message);            
                        jQuery( '#AddStaff' ).modal( 'hide' );
                        jQuery( '#staff_tbody' ).empty();
                        jQuery( '#staff_tbody' ).append( response.content );
                        jQuery( '#add_staff_form #user_name' ).val('');
                        jQuery( '#add_staff_form #first_name' ).val(' ');
                        jQuery( '#add_staff_form #last_name' ).val(' ');
                        jQuery( '#add_staff_form #staff_email' ).val(' ');
                        jQuery( '#add_staff_form #staff_phone' ).val(' ');
                        jQuery( '#add_staff_form #user_shift' ).val(' ');
                        jQuery( '#add_staff_form #user_designation' ).val(' ');
                        jQuery( '#add_staff_form #pay_type' ).val(' ');
                        jQuery( '#add_staff_form #staff_salary' ).val(' ');
                        jQuery( '#add_staff_form #staff_location' ).val(' ');
                    }   
                }
            }
        });
    });

    /* Edit Staff details */
    jQuery(document).on('click', '.staff-edit-a', function () {
        var staff_key = jQuery(this).attr('data-staff');
        var nounce    = ajax_backend.backend_nonce;
        var names     = '';
        var emails    = '';
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_edit_staff_action',
                staff_key: staff_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response == 'Something went wrong.!' ) {
                        toastr.error(response);
                    } else {
                        jQuery('#EditStaff').modal('show');
                        jQuery('#edit_staff_form #select_user_id').val(response.ID);
                        jQuery('#edit_staff_form #user_id_ct').val(response.id);
                        jQuery('#edit_staff_form #user_name').val(response.username);
                        jQuery('#edit_staff_form #first_name').val(response.first_name);
                        jQuery('#edit_staff_form #last_name').val(response.last_name);
                        jQuery('#edit_staff_form #staff_email').val(response.email);
                        jQuery('#edit_staff_form #staff_phone').val(response.phone);
                        jQuery('#edit_staff_form #user_shift').val(response.shift_id);
                        jQuery('#edit_staff_form #user_designation').val(response.desig_id);
                        jQuery('#edit_staff_form #pay_type').val(response.pay_type);
                        jQuery('#edit_staff_form #staff_salary').val(response.salary);
                        jQuery('#edit_staff_form #staff_status').val(response.status);
                        jQuery('#edit_staff_form #staff_key').val(staff_key);
                        jQuery('#edit_staff_form #edit_staff_location').val(response.locations);
                        jQuery( '#edit_leave_name_-1' ).remove();
                        jQuery( '#edit_leave_value_-1' ).remove();
                        jQuery( "#edit_dynamic_leave_fields" ).empty();

                        names  = JSON.parse( response.leave_name );
                        emails = JSON.parse( response.leave_value );
         
                        for (var i = 0; i < names.length; i++) {
                            var l = document.createElement("INPUT");
                            l.setAttribute("type", "text");
                            l.setAttribute("id", "edit_leave_name_"+ j);
                            l.setAttribute("class", "form-control edit_leave_name");
                            l.setAttribute("name", "edit_leave_name[]");
                            l.setAttribute("placeholder", "Name");
                            l.setAttribute( "value", names[i] );
                            document.getElementById("edit_dynamic_leave_fields").appendChild(l);

                            var m = document.createElement("INPUT");
                            m.setAttribute("type", "text");
                            m.setAttribute("id", "edit_leave_value_"+ j);
                            m.setAttribute("class", "form-control edit_leave_value");
                            m.setAttribute("name", "edit_leave_value[]");
                            m.setAttribute("placeholder", "Leaves");
                            m.setAttribute( "value", emails[i] );
                            document.getElementById("edit_dynamic_leave_fields").appendChild(m);
                        }

                        var j = names.length;
                        jQuery(".edit_add_leave_fields").on("click", function(e){
                            e.preventDefault();
                            var l = document.createElement("INPUT");
                            l.setAttribute("type", "text");
                            l.setAttribute("id", "edit_leave_name_"+ j);
                            l.setAttribute("class", "form-control edit_leave_name");
                            l.setAttribute("name", "edit_leave_name[]");
                            l.setAttribute("placeholder", "Name");
                            document.getElementById("edit_dynamic_leave_fields").appendChild(l);

                            var m = document.createElement("INPUT");
                            m.setAttribute("type", "text");
                            m.setAttribute("id", "edit_leave_value_"+ j);
                            m.setAttribute("class", "form-control edit_leave_value");
                            m.setAttribute("name", "edit_leave_value[]");
                            m.setAttribute("placeholder", "Leaves");
                            document.getElementById("edit_dynamic_leave_fields").appendChild(m);
                            j++;
                        });

                        jQuery(".edit_remove_leave_fields").on("click", function(e){
                            e.preventDefault();
                            j--;
                            jQuery("#edit_leave_name_"+ j ).remove();
                            jQuery("#edit_leave_value_"+ j ).remove();
                        });
                    }   
                }
            }
        });
    });

    /* Update Staff details */
    jQuery(document).on('click', '#edit_staff_btn', function () {

        var leave_name  = [];
        var leave_value = [];
        var name        = jQuery( '#edit_staff_form #user_name' ).val();
        var first       = jQuery( '#edit_staff_form #first_name' ).val();
        var last        = jQuery( '#edit_staff_form #last_name' ).val();
        var email       = jQuery( '#edit_staff_form #staff_email' ).val();
        var phone       = jQuery( '#edit_staff_form #staff_phone' ).val();
        var shift       = jQuery( '#edit_staff_form #user_shift' ).val();
        var designation = jQuery( '#edit_staff_form #user_designation' ).val();
        var pay_type    = jQuery( '#edit_staff_form #pay_type' ).val();
        var salary      = jQuery( '#edit_staff_form #staff_salary' ).val();
        var status      = jQuery( '#edit_staff_form #staff_status' ).val();
        var staff       = jQuery( '#edit_staff_form #select_user_id' ).val();
        var staff_key   = jQuery('#edit_staff_form #staff_key').val();
        var user_id_ct  = jQuery('#edit_staff_form #user_id_ct').val();
        var location    = jQuery( '#edit_staff_form #edit_staff_location' ).val();

        jQuery( 'input[name^=edit_leave_name]' ).each( function(){
            leave_name.push( jQuery( this ).val() );
        });
        jQuery( 'input[name^=edit_leave_value]' ).each( function(){
            leave_value.push( jQuery( this ).val() );
        });

        var nounce = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_update_staff_action',
                name: name,
                first: first,
                last: last,
                email: email,
                phone: phone,
                shift: shift,
                staff: staff,
                designation: designation,
                pay_type: pay_type,
                salary: salary,
                status: status,
                leave_value: leave_value,
                leave_name: leave_name,
                nounce: nounce,
                location: location,
                staff_key: staff_key,
                user_id_ct: user_id_ct,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);                 
                    } else {
                        toastr.success(response.message); 
                        jQuery('#EditStaff').modal('hide');
                        jQuery('#staff_tbody').empty();
                        jQuery('#staff_tbody').append(response.content); 
                    }   
                }
            }
        });
    });

    /* Delete Staff details */
    jQuery(document).on('click', '.staff-delete-a', function () {
        var staff_key = jQuery(this).attr('data-staff');
        var nounce    = ajax_backend.backend_nonce;
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_delete_staff_action',
                staff_key: staff_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);                 
                    } else {
                        toastr.success(response.message); 
                        jQuery('#staff_tbody').empty();
                        jQuery('#staff_tbody').append(response.content);
                    }   
                }
            }
        });
    });

    /** -----------------------------------------------------------Leave Requests--------------------------------------------------------- **/
    /* Add Requests details */
    jQuery(document).on('click', '#add_request_btn', function () {
        var name       = jQuery( '#add_request_form #request_name' ).val();
        var desc       = jQuery( '#add_request_form #notice_desc' ).val();
        var start      = jQuery( '#add_request_form #holiday_start' ).val();
        var to         = jQuery( '#add_request_form #holiday_to' ).val();
        var staff_id   = jQuery( '#add_request_form #staff_id' ).val();
        var staff_name = jQuery( '#add_request_form #staff_name' ).val();
        var status     = jQuery( '#add_request_form #request_status' ).val();
        var nounce     = ajax_backend.backend_nonce;
        
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_add_req_action',
                name: name,
                desc: desc,
                start: start,
                to: to,
                staff_id: staff_id,
                staff_name: staff_name,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response == 'Request not added' || response == 'Something went wrong.!' ) {
                        toastr.error(response);
                    } else { 
                        jQuery( '#AddRequests' ).modal('hide');
                        jQuery( '#request_tbody' ).empty();
                        jQuery( '#request_tbody' ).append(response);
                        jQuery( '#add_request_form #request_name' ).val(' ');
                        jQuery( '#add_request_form #notice_desc' ).val(' ');
                        jQuery( '#add_request_form #holiday_start' ).val(' ');
                        jQuery( '#add_request_form #holiday_to' ).val(' ');
                    }   
                }
            }
        });
    });

    /* Edit request */
    jQuery(document).on('click', '.request-edit-a', function () {
        var request_key = jQuery(this).attr('data-request');
        var nounce      = ajax_backend.backend_nonce;
        
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_edit_req_action',
                request_key: request_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response == 'Something went wrong.!' ) {
                        toastr.error(response);
                    } else { 
                        jQuery( '#EditRequests' ).modal('show');
                        jQuery( '#edit_request_form #edit_request_name' ).val(response.name);
                        jQuery( '#edit_request_form #edit_notice_desc' ).val(response.desc);
                        jQuery( '#edit_request_form #edit_holiday_start' ).val(response.start);
                        jQuery( '#edit_request_form #edit_holiday_to' ).val(response.to);
                        jQuery( '#edit_request_form #request_key' ).val(request_key);
                        jQuery( '#edit_request_form #edit_staff_id' ).val(response.s_id);
                        jQuery( '#edit_request_form #edit_staff_name' ).val(response.s_name);
                        jQuery( '#edit_request_form #edit_request_status' ).val(response.status);
                    }   
                }
            }
        });
    });

    /* Update Requests details */
    jQuery(document).on('click', '#edit_request_btn', function () {
        var key        = jQuery( '#edit_request_form #request_key' ).val();
        var name       = jQuery( '#edit_request_form #edit_request_name' ).val();
        var desc       = jQuery( '#edit_request_form #edit_notice_desc' ).val();
        var start      = jQuery( '#edit_request_form #edit_holiday_start' ).val();
        var to         = jQuery( '#edit_request_form #edit_holiday_to' ).val();
        var staff_id   = jQuery( '#edit_request_form #edit_staff_id' ).val();
        var staff_name = jQuery( '#edit_request_form #edit_staff_name' ).val();
        var status     = jQuery( '#edit_request_form #edit_request_status' ).val();
        var nounce     = ajax_backend.backend_nonce;
        
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_update_req_action',
                key: key,
                name: name,
                desc: desc,
                start: start,
                to: to,
                staff_id: staff_id,
                staff_name: staff_name,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response == 'Request not updated' || response == 'Something went wrong.!' ) {
                        toastr.error(response);
                    } else { 
                        jQuery( '#EditRequests' ).modal('hide');
                        jQuery( '#request_tbody' ).empty();
                        jQuery( '#request_tbody' ).append(response);
                    }   
                }
            }
        });
    });

    /* Delete request */
    jQuery(document).on('click', '.request-delete-a', function () {
        var request_key = jQuery(this).attr('data-request');
        var nounce      = ajax_backend.backend_nonce;
        
        jQuery.ajax({
            url: ajax_backend.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_delete_req_action',
                request_key: request_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response == 'Request not deleted' || response == 'Something went wrong.!' ) {
                        toastr.error(response);
                    } else { 
                        jQuery( '#request_tbody' ).empty();
                        jQuery( '#request_tbody' ).append(response);
                    }   
                }
            }
        });
    });
	
	 jQuery(document).on('click', '.clock-action-btn', function (e) {
		 setTimeout(function(){ 
			location.reload();
		 }, 3000);
	 });

});