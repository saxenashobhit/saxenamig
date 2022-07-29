jQuery(document).ready(function() {
    'use strict';

    /** Event **/
    jQuery('#event_date').datetimepicker({
        format: 'LT',
        format: 'YYYY-MM-DD',
        autoclose: true
    });
    jQuery('#event_time').datetimepicker({
        format: 'LT',
        autoclose: true
    });
    jQuery('#edit_event_date').datetimepicker({
        format: 'LT',
        format: 'YYYY-MM-DD',
        autoclose: true
    });
    jQuery('#edit_event_time').datetimepicker({
        format: 'LT',
        autoclose: true
    });

    /* Shift */
    jQuery('#shift_start').datetimepicker({
        format: 'LT',
        autoclose: true
    });
    jQuery('#shift_end').datetimepicker({
        format: 'LT',
        autoclose: true
    });
    jQuery('#shift_late').datetimepicker({
        format: 'LT',
        autoclose: true
    });
    jQuery('#edit_shift_start').datetimepicker({
        format: 'LT',
        autoclose: true
    });
    jQuery('#edit_shift_end').datetimepicker({
        format: 'LT',
        autoclose: true
    });
    jQuery('#edit_shift_late').datetimepicker({
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

});