/* Admin End Scripts */
jQuery(document).ready(function () {
	'use strict';
	try {
		jQuery(function() {

			jQuery('input[name="holiday_start"]').daterangepicker({
			    autoUpdateInput: false,
			    locale: {
			        cancelLabel: 'Clear'
			    }
			});

		    jQuery('input[name="holiday_start"]').on('apply.daterangepicker', function(ev, picker) {
		      	jQuery(this).val( picker.startDate.format('YYYY-MM-DD') );
		      	jQuery( '#holiday_to' ).val( picker.endDate.format('YYYY-MM-DD') );
		    });

		    jQuery('input[name="holiday_start"]').on('cancel.daterangepicker', function(ev, picker) {
		      	jQuery(this).val('');
		    });

		});
	} catch(err) {}

	try {
		jQuery(function() {

			jQuery('input[name="edit_holiday_start"]').daterangepicker({
			    autoUpdateInput: false,
			    locale: {
			        cancelLabel: 'Clear'
			    }
			});

		    jQuery('input[name="edit_holiday_start"]').on('apply.daterangepicker', function(ev, picker) {
		      	jQuery(this).val( picker.startDate.format('YYYY-MM-DD') );
		      	jQuery( '#edit_holiday_to' ).val( picker.endDate.format('YYYY-MM-DD') );
		    });

		    jQuery('input[name="edit_holiday_start"]').on('cancel.daterangepicker', function(ev, picker) {
		      	jQuery(this).val('');
		    });

		});
	} catch(err) {}
	
});