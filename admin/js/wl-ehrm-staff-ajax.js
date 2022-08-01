/* Staff Admin End Ajax Scripts */
jQuery(document).ready(function () {
	'use strict';

	var table = jQuery('#task_staff_table').DataTable({
        'responsive': true,
        'destroy': true,
        'order': [],
    });

    /** Staff dashboard clock **/
    setInterval(function () {
        var currentUtcTime1 = new Date(); // This is in UTC
        var currentDateTimeCentralTimeZone1 = new Date(currentUtcTime1.toLocaleString('en-US', { timeZone: ajax_staff.ehrm_timezone }));
        var hours   = currentDateTimeCentralTimeZone1.getHours();
        jQuery(".hours").html((hours < 10 ? "0" : "") + hours);
    }, 1000);
    setInterval(function () {
        var currentUtcTime2 = new Date(); // This is in UTC
        var currentDateTimeCentralTimeZone2 = new Date(currentUtcTime2.toLocaleString('en-US', { timeZone: ajax_staff.ehrm_timezone }));
        var minutes = currentDateTimeCentralTimeZone2.getMinutes();
        jQuery(".min").html((minutes < 10 ? "0" : "") + minutes);
    }, 1000);
    setInterval(function () {
        var currentUtcTime3 = new Date(); // This is in UTC
        var currentDateTimeCentralTimeZone3 = new Date(currentUtcTime3.toLocaleString('en-US', { timeZone: ajax_staff.ehrm_timezone }));
        var seconds = currentDateTimeCentralTimeZone3.getSeconds();
        jQuery(".sec").html((seconds < 10 ? "0" : "") + seconds);
    }, 1000);

	/* Action on Clock btns */
    jQuery('body').on('click', '.clock-action-btn', function (e) {
    	e.preventDefault();		
    	var value    = jQuery( this ).attr( 'data-value' );
    	var timezone = jQuery( this ).attr( 'data-timezone' );
		var nounce = ajax_staff.staff_nonce;
        jQuery('.clock-action-btn').prop('disabled', true);
        jQuery.ajax({
            url: ajax_staff.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_clock_action',
                timezone: timezone,
                value: value,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
					if (response.status == 'success') {
						toastr.success( response.message );
                		location.reload();
                	} else {
                		toastr.error( response.message );
					}
					//location.reload();
                }
               // location.reload();
            }
        });
		// setTimeout(function(){
		// 	location.reload();
		// }, 3000);
	});

    /* Submit Late reson */
    jQuery(document).on('click', '#late_reson_submit_btn', function (e) {
    	e.preventDefault();
    	var reson    = jQuery( '#late_reson_form #late_resonn' ).val();
    	var staff_id = jQuery( '#late_reson_form #staff_id' ).val();
    	var nounce   = ajax_staff.staff_nonce;
        jQuery.ajax({
            url: ajax_staff.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_late_reson_action',
                staff_id: staff_id,
                reson: reson,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if (response == 'Updated') {
                        toastr.success( response );
                		jQuery('#LateReson').modal('hide');
                		jQuery('#late_reson_btn').remove();
                	} else {
                		toastr.error( response );
                	}           	
                }
            }
        });
    });

    /* Submit Daily Report */
    jQuery(document).on('click', '#daily_report_btn', function (e) {
    	e.preventDefault();
    	var report   = jQuery( '#daily_report_form #daily_report' ).val();
    	var staff_id = jQuery( '#daily_report_form #user_id' ).val();
    	var nounce   = ajax_staff.staff_nonce;
        jQuery.ajax({
            url: ajax_staff.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_daily_report_action',
                staff_id: staff_id,
                report: report,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if (response == 'Updated') {
                        toastr.success( response );
	                	jQuery('#DailyReport').modal('hide');
	                	jQuery('#daily_reportbtn').remove();
	                } else {
                		toastr.error( response );
                	} 
                }
            }
        });
    });
	
	/* To create breakin intance */
    jQuery(document).on('click', '.whrm-breakin-btn', function () {		
        var counter = jQuery(this).attr('data-counter');
        var nounce  = ajax_staff.staff_nonce;
        jQuery.ajax({
            url: ajax_staff.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_staff_break_action',
                counter: counter,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    location.reload();
                }
            }
        });
    });
	
	/* To create breakout intance */
    jQuery(document).on('click', '.whrm-breakout-btn', function () {
        var counter = jQuery(this).attr('data-counter');
        var nounce  = ajax_staff.staff_nonce;
		//alert(nounce);
        jQuery('.whrm-breakout-btn').prop('disabled', true);
        jQuery.ajax({
            url: ajax_staff.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_add_breakout_action',
                counter: counter,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    location.reload();
                }
            }
        });
    });

});