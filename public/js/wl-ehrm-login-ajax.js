jQuery(document).ready(function () {
    'use strict';
    
    setInterval(function () {
        var currentUtcTime1 = new Date(); // This is in UTC
        var currentDateTimeCentralTimeZone1 = new Date(currentUtcTime1.toLocaleString('en-US', { timeZone: ajax_login.ehrm_timezone }));
        var hours   = currentDateTimeCentralTimeZone1.getHours();
        jQuery(".hours").html((hours < 10 ? "0" : "") + hours);
    }, 1000);
    setInterval(function () {
        var currentUtcTime2 = new Date(); // This is in UTC
        var currentDateTimeCentralTimeZone2 = new Date(currentUtcTime2.toLocaleString('en-US', { timeZone: ajax_login.ehrm_timezone }));
        var minutes = currentDateTimeCentralTimeZone2.getMinutes();
        jQuery(".min").html((minutes < 10 ? "0" : "") + minutes);
    }, 1000);
    setInterval(function () {
        var currentUtcTime3 = new Date(); // This is in UTC
        var currentDateTimeCentralTimeZone3 = new Date(currentUtcTime3.toLocaleString('en-US', { timeZone: ajax_login.ehrm_timezone }));
        var seconds = currentDateTimeCentralTimeZone3.getSeconds();
        jQuery(".sec").html((seconds < 10 ? "0" : "") + seconds);
    }, 1000);

    /* Action on Clock btns */
    jQuery(document).on('click', '.clock-action-btn', function (e) {
    	e.preventDefault();
    	var value    = jQuery( this ).attr('data-value');
    	var timezone = jQuery( this ).attr('data-timezone');
    	var nounce   = ajax_login.login_nonce;
        jQuery.ajax({
            url: ajax_login.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_front_clock_action',
                timezone: timezone,
                value: value,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error( response.message );
						location.reload();
                	} else {
                		toastr.success( response.message );
                		location.reload();
                    }					
                }                
            }			
        });
    });

    /* Submit Late reson */
    jQuery(document).on('click', '#late_reson_submit_btn', function (e) {
    	e.preventDefault();
    	var reson    = jQuery( '#late_reson_form #late_resonn' ).val();
    	var staff_id = jQuery( '#late_reson_form #staff_id' ).val();
    	var nounce   = ajax_login.login_nonce;
        jQuery.ajax({
            url: ajax_login.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_front_late_reson_action',
                staff_id: staff_id,
                reson: reson,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response == 'Late Reason Updated.!' ) {
                        toastr.success( 'Submitted.!!' );
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
    	var nounce   = ajax_login.login_nonce;
        jQuery.ajax({
            url: ajax_login.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_front_daily_report_action',
                staff_id: staff_id,
                report: report,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response == 'Daily Report Submitted.!' ) {
                        toastr.success( 'Submitted.!!' );
	                	jQuery('#DailyReport').modal('hide');
	                	jQuery('#daily_reportbtn').remove();
	                } else {
                		toastr.error( response );
                	} 
                }
            }
        });
    });
    
});