/* Admin Dashboard End Scripts */
jQuery(document).ready(function () {
    'use strict';

    /* Datatable script */
	var table = jQuery('#admin_dash_table').DataTable({
		'responsive': true,
		'destroy': true,
		'order': []
	});

    /** Staff Login/Logout action from Admin Dashboard **/
    /* Login */
    jQuery(document).on('click', '#dashboard_login, #dashboard_logout', function (e) {
    	e.preventDefault();
    	var value     = jQuery( this ).attr('data-value');
    	var staff_key = jQuery( this ).attr('data-staff');
    	var timezone  = jQuery( this ).attr('data-timezone');
    	var nounce    = ajax_admin.admin_nonce;
        jQuery.ajax({
            url: ajax_admin.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_login_dash_action',
                timezone: timezone,
                staff_key: staff_key,
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
                }
            }
        });
    });

    /**
     * pay_type change
     */
    jQuery(document).on('change', '#pay_type', function(e){
        e.preventDefault();        
        let pay_type     = jQuery(this).val();
        let salary_input = jQuery('#salary_block');
        if( pay_type == 'project' ) {
            salary_input.hide('1000');
        } else if( pay_type == 'salary' ) {
            salary_input.show('1000');
        }
    });
});