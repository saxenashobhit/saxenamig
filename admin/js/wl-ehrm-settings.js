jQuery(document).ready(function () {
    'use strict';

    /* Upload Logo for mail html  */
    jQuery(document).on( 'click', '#upload_logo', function (e) {
        e.preventDefault();
        var button = this;
        var image  = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open().on('select', function (e) {
            var uploaded_image = image.state().get('selection').first();
            var location_image = uploaded_image.toJSON().url;
            jQuery('#mail_logo').val(location_image);
        });
    });

    /* Save options */
    function save(selector, form = null) {
        jQuery(form).ajaxForm({
            success: function (response) {
                jQuery(selector).prop('disabled', false);
                if (response.success) {
                    jQuery('span.text-danger').remove();
                    jQuery(".is-valid").removeClass("is-valid");
                    jQuery(".is-invalid").removeClass("is-invalid");
                    toastr.success(response.data.message);
                    // jQuery(form)[0].reset();
                } else {
                    jQuery('span.text-danger').remove();
                    if (response.data && jQuery.isPlainObject(response.data)) {
                        jQuery(form + ' :input').each(function () {
                            var input = this;
                            jQuery(input).removeClass('is-valid');
                            jQuery(input).removeClass('is-invalid');
                            if (response.data[input.name]) {
                                var errorSpan = '<span class="text-danger">' + response.data[input.name] + '</span>';
                                jQuery(input).addClass('is-invalid');
                                jQuery(errorSpan).insertAfter(input);
                            } else {
                                jQuery(input).addClass('is-valid');
                            }
                        });
                    } else {
                        var errorSpan = '<span class="text-danger">' + response.data + '<hr></span>';
                        jQuery(errorSpan).insertBefore(form);
                    }
                }
            },
            error: function (response) {
                jQuery(selector).prop('disabled', false);
                toastr.error(response.statusText);
            }
        });
    }

    /* Save Plugin Settings */
    save( '#save-settings-btn', '#ehrm-settings-form' );

});