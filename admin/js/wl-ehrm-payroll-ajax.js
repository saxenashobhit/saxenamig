/* Pay roll Ajax Scripts */

jQuery(document).ready(function () {
    'use strict';

    jQuery('.payrol_total_div').hide();

    /** Start date and last date **/
    jQuery('#payroll_first').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
    });
    jQuery('#payroll_last').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
    });
    jQuery("#payroll_first").on("change.datetimepicker", function (e) {
        jQuery('#payroll_last').datetimepicker('minDate', e.date);
    });
    jQuery("#payroll_last").on("change.datetimepicker", function (e) {
        jQuery('#payroll_first').datetimepicker('maxDate', e.date);
    });

    /* For table */
    var table = jQuery('#payroll_table').DataTable({
        'responsive': true,
        'destroy': true,
        'order': [],
    });

    /* Add Designation details */
    jQuery(document).on('click', '#payroll_btn', function (e) {
    	e.preventDefault();
    	var first = jQuery( '#payroll_form #payroll_first' ).val();
    	var last  = jQuery( '#payroll_form #payroll_last' ).val();

    	var nounce = ajax_payroll.payroll_nonce;
        jQuery.ajax({
            url: ajax_payroll.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_payroll_action',
                first: first,
                last: last,
                nounce: nounce,
            },
            success: function ( response ) {
                if (response) {
                	if ( response.status == 'error' ) {
                		toastr.error(response.message);
                	} else {
                        toastr.success(response.message);
                        jQuery( '#payroll_tbody' ).empty();
                        jQuery( '#payroll_tbody' ).append(response.content);
                        jQuery( '#total_ammount_payrol' ).empty();
                        jQuery('#total_ammount_payrol').append(response.total);
                        jQuery('.payrol_total_div').show();
                	}   
                }
            }
        });
    });

});