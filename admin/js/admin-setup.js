jQuery(document).ready(function () {
	'use strict';
	
	/* Color picker */
    jQuery('.color-field').wpColorPicker();

    /** Event **/
    jQuery('#start_time').datetimepicker({
        format: 'LT',
        autoclose: true
    });
    jQuery('#end_time').datetimepicker({
        format: 'LT',
        autoclose: true
    });
    jQuery('#late_time').datetimepicker({
        format: 'LT',
        autoclose: true
	});
	
	/* Settings */
	jQuery('#halfday_start').datetimepicker({
        format: 'LT',
        autoclose: true
    });
    jQuery('#halfday_end').datetimepicker({
        format: 'LT',
        autoclose: true
    });
    jQuery('#lunch_start').datetimepicker({
        format: 'LT',
        autoclose: true
    });
    jQuery('#lunch_end').datetimepicker({
        format: 'LT',
        autoclose: true
	});
	

    /* Add more field for Depertments option */
	var i = 2;
	jQuery(".add_depart_fields").on("click", function(e){
		e.preventDefault();
	    var x = document.createElement("INPUT");
	    x.setAttribute("type", "text");
	    x.setAttribute("id", "department_name_"+ i);
	    x.setAttribute("class", "form-control department_name");
	    x.setAttribute("name", "department_name[]");
	    x.setAttribute("placeholder", "Name");
	    document.getElementById("dynamic_depart_fields").appendChild(x);
	    i++;
	});

	jQuery(".remove_depart_fields").on("click", function(e){
		e.preventDefault();
		i--;
		jQuery("#department_name_"+ i ).remove();
	});

	jQuery(".remove-department-single").on("click", function(e){
		e.preventDefault();
		jQuery(this).parent().remove();
	});

	try{
		jQuery("#footer-thankyou").empty();
		jQuery("#footer-thankyou").append('Thank you for creating with <a href="https://weblizar.com/">Weblizar</a>.');

		jQuery("#selectAll").click(function() {
			jQuery("input[type=checkbox]").prop("checked", jQuery(this).prop("checked"));
		});

		jQuery("input[type=checkbox]").click(function() {
		    if (!jQuery(this).prop("checked")) {
		        jQuery("#selectAll").prop("checked", false);
		    }
		});

	} catch(err) {}

	jQuery('.form-check-input').click(function () {
		if (jQuery(this).is(':checked')) {
			jQuery(this).parent().parent().addClass('form-check-success');
			jQuery(this).parent().parent().removeClass('form-check-danger');
		} else {
			jQuery(this).parent().parent().addClass('form-check-danger');
			jQuery(this).parent().parent().removeClass('form-check-success');
		}
    });
	
});