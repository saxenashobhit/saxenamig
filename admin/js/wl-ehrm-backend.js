/* Admin End Scripts */
jQuery(document).ready(function () {
	'use strict';

	/* Color picker */
	jQuery('.color-field').wpColorPicker();

	/* Popover js */
	jQuery('[data-toggle="tooltip"]').tooltip();

	/* Add more field for location Recipient emails */
	var ii = 2;
	jQuery(".add_name_fields").on("click", function(e){
		e.preventDefault();
	    var x = document.createElement("INPUT");
	    x.setAttribute("type", "text");
	    x.setAttribute("id", "recipt_name_"+ ii);
	    x.setAttribute("class", "form-control recipt_name");
	    x.setAttribute("name", "recipt_name[]");
	    x.setAttribute("placeholder", "Name");
	    document.getElementById("dynamic_email_fields").appendChild(x);

	    var y = document.createElement("INPUT");
	    y.setAttribute("type", "email");
	    y.setAttribute("id", "recipt_email_"+ ii);
	    y.setAttribute("class", "form-control recipt_email");
	    y.setAttribute("name", "recipt_email[]");
	    y.setAttribute("placeholder", "Email");
	    document.getElementById("dynamic_email_fields").appendChild(y);
	    ii++;
	});

	jQuery(".remove_email_fields").on("click", function(e){
		e.preventDefault();
		ii--;
		jQuery("#recipt_name_"+ ii ).remove();
		jQuery("#recipt_email_"+ ii ).remove();
	});

	/* Add more field for Leaves option */
	var i = 2;
	jQuery(".add_leave_fields").on("click", function(e){
		e.preventDefault();
	    var x = document.createElement("INPUT");
	    x.setAttribute("type", "text");
	    x.setAttribute("id", "leave_name_"+ i);
	    x.setAttribute("class", "form-control leave_name");
	    x.setAttribute("name", "leave_name[]");
	    x.setAttribute("placeholder", "Name");
	    document.getElementById("dynamic_leave_fields").appendChild(x);

	    var y = document.createElement("INPUT");
	    y.setAttribute("type", "text");
	    y.setAttribute("id", "leave_value_"+ i);
	    y.setAttribute("class", "form-control leave_value");
	    y.setAttribute("name", "leave_value[]");
	    y.setAttribute("placeholder", "Leaves");
	    document.getElementById("dynamic_leave_fields").appendChild(y);
	    i++;
	});

	jQuery(".remove_leave_fields").on("click", function(e){
		e.preventDefault();
		i--;
		jQuery("#leave_name_"+ i ).remove();
		jQuery("#leave_value_"+ i ).remove();
	});

	/* Add more field for Depertments option */
	var i = 2;
	jQuery(".add_depart_fields").on("click", function(e){
		e.preventDefault();
	    var x = document.createElement("INPUT");
	    var y = document.createElement("INPUT");
	    x.setAttribute("type", "text");
	    x.setAttribute("id", "department_name_"+ i);
	    x.setAttribute("class", "form-control department_name");
	    x.setAttribute("name", "department_name[]");
	    x.setAttribute("placeholder", "Name");

		x.setAttribute("type", "text");
	    x.setAttribute("id", "department_description_"+ i);
	    x.setAttribute("class", "form-control department_description");
	    x.setAttribute("name", "department_description[]");
	    x.setAttribute("placeholder", "Department Description");		
			
		//Dept head
		y.setAttribute("type", "text");
	    y.setAttribute("id", "department_head_"+ i);
	    y.setAttribute("class", "form-control department_head");
	    y.setAttribute("name", "department_head[]");
	    y.setAttribute("placeholder", "Department Head");

	    document.getElementById("dynamic_depart_fields").appendChild(x);
	    document.getElementById("dynamic_depart_fields").appendChild(y);
	    i++;
	});

	jQuery(".remove_depart_fields").on("click", function(e){
		e.preventDefault();
		i--;
		jQuery("#department_name_"+ i ).remove();
		jQuery("#department_description_"+ i ).remove();
		jQuery("#department_head_"+ i ).remove();
	});

	jQuery(".remove-department-single").on("click", function(e){
		e.preventDefault();
		jQuery(this).parent().remove();
	});

});