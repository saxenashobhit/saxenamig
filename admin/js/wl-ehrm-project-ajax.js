/* Project End Scripts */
jQuery(document).ready(function () {
    'use strict';

    var table = jQuery('#project_table').DataTable({
        'responsive': true,
        'destroy': true,
        'order': [],
    });

    /* For Project Description field */
    CKEDITOR.replace('project_desc');
    CKEDITOR.replace('edit_project_desc');
    CKEDITOR.replace('task_desc');
    CKEDITOR.replace( 'edit_task_desc' );
    
    /* For Project Members field */
    jQuery('#project_members').multiselect(); 
    //jQuery('#task_members').multiselect();

    /* For Tokenfields ( Project Tags field ) */
    jQuery('#project_tags').tokenfield();
    jQuery('#edit_project_tags').tokenfield();

    /* For Due date filed */
    jQuery('#task_due').datetimepicker({
        format: 'LT',
        format: 'YYYY-MM-DD',
        autoclose: true
    });
 
    /* For save project details */
    jQuery(document).on('click', '#add_project_btn', function () {
        var name         = jQuery( '#add_project_form #project_name' ).val();
        var desc         = CKEDITOR.instances['project_desc'].getData();
        var member       = jQuery( '#add_project_form #project_members' ).val();
        var tags         = jQuery( '#add_project_form #project_cost' ).val();
        var project_cost = jQuery( '#add_project_form #project_cost' ).val();
        var status = jQuery( '#add_project_form #project_status' ).val();
        var nounce = ajax_project.project_nonce;

        if ( name == undefined || name.length == 0 ) {
            toastr.error('Please enter Project name.!');
            return false;
        }
        if ( member == undefined || member.length == 0 ) {
            toastr.error('Please Select members.!');
            return false;
        }

        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_add_project_ajax',
                name: name,
                desc: desc,
                member: member,
                tags: tags,
                project_cost: project_cost,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response.status == 'error' ) {
                    toastr.error(response.message);
                } else {
                    toastr.success(response.message);
                    jQuery( '#AddProjects' ).modal( 'hide' );
                    jQuery( '#report_tbody' ).empty();
                    jQuery( '#report_tbody' ).append( response.content );
                    jQuery( '#add_project_form #project_name' ).val(' ');
                    CKEDITOR.instances['project_desc'].setData(' ');
                    jQuery( '#add_project_form #project_members' ).val(' ');
                    jQuery( '#add_project_form #project_tags' ).val(' ');
                    jQuery( '#add_project_form #project_cost' ).val(' ');
                }
            }
        });
    });

    /* For edit project details */
    jQuery(document).on( 'click', '.project-edit-a', function () {
        var key    = jQuery( this ).attr( 'data-project' );
        var nounce = ajax_project.project_nonce;
        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_edit_project_ajax',
                key: key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    jQuery( '#EditProjects' ).modal( 'show' );
                    jQuery( '#edit_project_members' ).multiselect();
                    jQuery( '#edit_project_tags' ).tokenfield( 'setTokens', '' );
                    jQuery( '#edit_project_tags' ).tokenfield( 'setTokens', response.tags );
                    jQuery( '#edit_project_form #edit_project_name' ).val( response.name );
                    jQuery( '#edit_project_form #edit_project_status' ).val( response.status );   
                    jQuery( '#edit_project_form #edit_project_cost' ).val( response.project_cost );   
                    jQuery( '#edit_project_form #project_key' ).val( key );   
                    CKEDITOR.instances['edit_project_desc'].setData( response.desc );
                    
                    var valArr = response.members;
                    var i = 0, size = valArr.length;
                    for( i; i < size; i++ ){
                        jQuery( '#edit_project_members' ).multiselect( 'select', valArr[i] );
                    }            
                }
            }
        });
    });

    /* For Update project details */
    jQuery(document).on('click', '#edit_project_btn', function () {
        var key          = jQuery( '#edit_project_form #project_key' ).val();
        var name         = jQuery( '#edit_project_form #edit_project_name' ).val();
        var desc         = CKEDITOR.instances['edit_project_desc'].getData();
        var member       = jQuery( '#edit_project_form #edit_project_members' ).val();
        var tags         = jQuery( '#edit_project_form #edit_project_tags' ).val();
        var project_cost = jQuery( '#edit_project_form #edit_project_cost' ).val();
        var status = jQuery( '#edit_project_form #edit_project_status' ).val();
        var nounce = ajax_project.project_nonce;

        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_update_project_ajax',
                name: name,
                key: key,
                desc: desc,
                member: member,
                tags: tags,
                project_cost: project_cost,
                status: status,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response.status == 'error' ) {
                    toastr.error(response.message);
                } else {
                    toastr.success(response.message);
                    jQuery( '#EditProjects' ).modal( 'hide' );
                    jQuery( '#report_tbody' ).empty();
                    jQuery( '#report_tbody' ).append( response.content );
                }
            }
        });
    });

    /* For delete project details */
    jQuery(document).on( 'click', '.project-delete-a', function () {
        var key    = jQuery( this ).attr( 'data-project' );
        var nounce = ajax_project.project_nonce;
        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_delete_project_ajax',
                key: key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response.status == 'error' ) {
                    toastr.error(response.message);
                } else {
                    toastr.success(response.message);
                    jQuery( '#report_tbody' ).empty();
                    jQuery( '#report_tbody' ).append( response.content );
                }
            }
        });
    });

    /* For Project view */
    jQuery(document).on( 'click', '.project-view-a', function () {
        var key    = jQuery( this ).attr( 'data-project' );
        var nounce = ajax_project.project_nonce;

        jQuery( '#task_members' ).empty();
        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_view_all_tasks_ajax',
                key: key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response == 'Something went wrong.!' ) {
                    toastr.error( response );
                } else {
                    jQuery( '.task-add-btnn' ).attr( "data-project", key );
                    jQuery( '.project-task-ul' ).empty();
                    jQuery( '.project-task-ul' ).append( response.tasks );
                    jQuery( '#task_members' ).append( response.members );
                    jQuery( '#task_members' ).multiselect();
                    jQuery( '#add_task_form #task_key' ).val(key);
                    jQuery( '#ViewProjects' ).modal( 'show' );
                }
            }
        });
    });

    /* Add Task */
    jQuery(document).on('click', '#add_task_btn', function () {
        var key      = jQuery('#add_task_form #task_key').val();
        var name     = jQuery('#add_task_form #task_name').val();
        var desc     = CKEDITOR.instances['task_desc'].getData();
        var due_strt = jQuery('#add_task_form #task_due').val();
        var priority = jQuery('#add_task_form #task_priority').val();
        var assign   = jQuery('#add_task_form #task_members').val();
        var progress = jQuery('#add_task_form #task_progress').val();
        var nounce   = ajax_project.project_nonce;
        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_add_task_ajax',
                key: key,
                name: name,
                desc: desc,
                due_strt: due_strt,
                priority: priority,
                assign: assign,
                progress: progress,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery( '#AddTasks' ).modal('hide');
                        jQuery( '.project-task-ul' ).empty();
                        jQuery( '.project-task-ul' ).append( response.content );
                        jQuery('#add_task_form #task_name').val(' ');
                        CKEDITOR.instances['task_desc'].setData(' ');
                        jQuery('#add_task_form #task_members').val(' ');
                        jQuery('#add_task_form #task_due').val(' ');

                    }
                }
            }
        });
    });

    /* For edit Task details */
    jQuery(document).on('click', '.task__edit-btn', function () {
        var task_key    = jQuery(this).attr('data-task');
        var project_key = jQuery(this).attr('data-project');
        var nounce      = ajax_project.project_nonce;

        jQuery( '#edit_task_members' ).empty();

        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_edit_task_ajax',
                task_key: task_key,
                project_key: project_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response.status == 'error' ) {
                    toastr.error( response.message );
                } else {
                    jQuery( '#EditTasks' ).modal( 'show' );
                    jQuery( '#edit_task_form #edit_task_name' ).val( response.data.name );
                    jQuery( '#edit_task_form #edit_task_priority' ).val( response.data.priority );   
                    jQuery( '#edit_task_form #edit_task_progress' ).val( response.data.progress );   
                    jQuery( '#edit_task_form #edit_task_due' ).val( response.data.due_start );
                    jQuery( '#edit_task_form #edit_task_key' ).val( task_key );
                    jQuery( '#edit_task_form #edit_project_key' ).val( project_key );
                    CKEDITOR.instances['edit_task_desc'].setData( response.data.desc );
                    
                    jQuery( '#edit_task_form #edit_task_members' ).append( response.members );
                    jQuery( '#edit_task_members' ).multiselect();

                    var valArr = response.data.assign;
                    var i = 0, size = valArr.length;
                    for( i; i < size; i++ ){
                        jQuery( '#edit_task_members' ).multiselect( 'select', valArr[i] );
                    }

                    jQuery('#edit_task_due').datetimepicker({
                        format: 'LT',
                        format: 'YYYY-MM-DD',
                        autoclose: true
                    });

                }
            }
        });
    });

    /* Update Task */
    jQuery(document).on('click', '#edit_task_btn', function () {
        var task_key = jQuery('#edit_task_form #edit_task_key').val();
        var proj_key = jQuery('#edit_task_form #edit_project_key').val();
        var name     = jQuery('#edit_task_form #edit_task_name').val();
        var desc     = CKEDITOR.instances['edit_task_desc'].getData();
        var due_strt = jQuery('#edit_task_form #edit_task_due').val();
        var priority = jQuery('#edit_task_form #edit_task_priority').val();
        var assign   = jQuery('#edit_task_form #edit_task_members').val();
        var progress = jQuery('#edit_task_form #edit_task_progress').val();
        var nounce   = ajax_project.project_nonce;
        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_update_task_ajax',
                task_key: task_key,
                proj_key: proj_key,
                name: name,
                desc: desc,
                due_strt: due_strt,
                priority: priority,
                assign: assign,
                progress: progress,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    if ( response.status == 'error' ) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery( '#EditTasks' ).modal('hide');
                        jQuery( '.project-task-ul' ).empty();
                        jQuery( '.project-task-ul' ).append( response.content );
                    }
                }
            }
        });
    });

    /* For delete task details */
    jQuery(document).on( 'click', '.task__delete-btn', function () {
        var proj_key    = jQuery( this ).attr( 'data-project' );
        var task_key    = jQuery( this ).attr( 'data-task' );
        var nounce = ajax_project.project_nonce;
        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_delete_task_ajax',
                proj_key: proj_key,
                task_key: task_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response.status == 'error' ) {
                    toastr.error(response.message);
                } else {
                    toastr.success(response.message);
                    jQuery( '.project-task-ul' ).empty();
                    jQuery( '.project-task-ul' ).append( response.content );
                }
            }
        });
    });

    /* For View task details */
    jQuery(document).on( 'click', '.view_task_detail ', function () {
        var proj_key    = jQuery( this ).attr( 'data-project' );
        var task_key    = jQuery( this ).attr( 'data-task' );
        var nounce      = ajax_project.project_nonce;
        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_view_task_ajax',
                proj_key: proj_key,
                task_key: task_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response == 'Something went wrong.!' ) {
                    toastr.error( response );
                } else {
                    jQuery( '#ViewTaskDetails' ).modal( 'show' );
                    jQuery( '.task_detail_result' ).empty();
                    jQuery( '.task_detail_result' ).append(response);
                    CKEDITOR.replace('task_comment_desc');
                    jQuery( '#edit_comment_task_btn' ).hide();
                    jQuery( '#close_comment_btn' ).hide();
                }
            }
        });
    });

    /** Upload media files in comment **/
    jQuery(document).on( 'click', '#upload-btn-ehrm', function (e) {
        e.preventDefault();
        jQuery('body').addClass('modal-open');
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: true
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').map(
              function( attachment ) {
                    attachment.toJSON();
                    return attachment;
            });
            for (var i = 0; i < uploaded_image.length; i++) {
               jQuery('#myplugin-placeholder_ehrm').append(
                    '<div class="myplugin-image-previeww"><img src="' + 
                    uploaded_image[i].attributes.url + '" >'
                    );
                jQuery('#myplugin-placeholder_ehrm').append(
                    '<input id="image_url_ehrm-'+ i +'" type="hidden" name="myplugin_attachment_url"  value="' + uploaded_image[i].attributes.url + '"></div>' 
                    );
            }
            jQuery( '#image_length_ehrm' ).val( uploaded_image.length );
            jQuery( 'body' ).addClass( 'modal-open' );
        });
    });

    /** Add Comments **/
    jQuery(document).on( 'click', '#add_comment_task_btn', function () {
        var task_key = jQuery('#add_comment_task #comment_task_key').val();
        var proj_key = jQuery('#add_comment_task #comment_project_key').val();
        var comment  = CKEDITOR.instances['task_comment_desc'].getData();
        var nounce = ajax_project.project_nonce;
        
        var image_length_ehrm = jQuery('input#image_length_ehrm').val();
        var media = [];
        for ( var i = 0; i < image_length_ehrm; i++ ) {
          media[i] = jQuery('input#image_url_ehrm-'+ i).val();
        }

        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_add_comment_ajax',
                proj_key: proj_key,
                task_key: task_key,
                comment: comment,
                media: media,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response.status == 'error' ) {
                    toastr.error(response.message);
                } else {
                    toastr.success(response.message);
                    CKEDITOR.instances['task_comment_desc'].setData(' ');
                    jQuery( '.task_comment_list' ).empty();
                    jQuery('.task_comment_list').append(response.content);
                    jQuery( '#myplugin-placeholder_ehrm' ).empty();
                }
            }
        });
    });

    /** Edit Comments **/
    jQuery(document).on( 'click', '.comment-edit-btn', function () {
        var proj_key    = jQuery( this ).attr( 'data-project' );
        var task_key    = jQuery( this ).attr( 'data-task' );
        var comment_key = jQuery( this ).attr( 'data-comment' );
        var nounce      = ajax_project.project_nonce;
        
        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_edit_comment_ajax',
                proj_key: proj_key,
                task_key: task_key,
                comment_key: comment_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response == 'Something went wrong.!' ) {
                    toastr.error( response );
                } else {
                    CKEDITOR.instances['task_comment_desc'].setData(response.comment);
                    jQuery( '#edit_comment_task_btn' ).show();
                    jQuery( '#close_comment_btn' ).show();
                    jQuery( '#add_comment_task_btn' ).hide();
                    jQuery( '#comment_coment_key' ).val(comment_key);
                    
                    var media_files = response.media;

                    if ( media_files != undefined || media_files.length != 0 ) {
                        var media_length = media_files.length;
                        for ( var i = 0; i < media_length; i++ ) {
                            jQuery('#myplugin-placeholder_ehrm').append(
                                 '<div class="myplugin-image-previeww"><img src="' + 
                                 response.media[i] + '" >'
                        );
                            jQuery('#myplugin-placeholder_ehrm').append(
                                 '<input id="image_url_ehrm-'+ i +'" type="hidden" name="myplugin_attachment_url"  value="' + response.media[i] + '"></div>' 
                            );
                        }
                    }
                }
            }
        });
    });

    /** Update Comments **/
    jQuery(document).on( 'click', '#edit_comment_task_btn', function () {
        var task_key   = jQuery( '#add_comment_task #comment_task_key' ).val();
        var proj_key   = jQuery( '#add_comment_task #comment_project_key' ).val();
        var coment_key = jQuery( '#add_comment_task #comment_coment_key' ).val();
        var comment    = CKEDITOR.instances['task_comment_desc'].getData();
        var nounce     = ajax_project.project_nonce;
        var media      = [];
        
        jQuery( '#myplugin-placeholder_ehrm input' ).each(function() {
          var input = jQuery(this).val();
          media.push(input);
        });

        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_update_comment_ajax',
                proj_key: proj_key,
                task_key: task_key,
                coment_key: coment_key,
                comment: comment,
                media: media,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response.status == 'error' ) {
                    toastr.error(response.message);
                } else {
                    toastr.success(response.message);
                    CKEDITOR.instances['task_comment_desc'].setData(' ');
                    jQuery( '.task_comment_list' ).empty();
                    jQuery( '.task_comment_list' ).append(response.content);
                    jQuery( '#myplugin-placeholder_ehrm' ).empty();
                    jQuery( '#edit_comment_task_btn' ).hide();
                    jQuery( '#close_comment_btn' ).hide();
                    jQuery( '#add_comment_task_btn' ).show();
                    jQuery( '#comment_coment_key' ).val(' ');
                }
            }
        });
    });

    /** Delete Comments **/
    jQuery(document).on( 'click', '.comment-delete-btn', function () {
        var proj_key    = jQuery( this ).attr( 'data-project' );
        var task_key    = jQuery( this ).attr( 'data-task' );
        var comment_key = jQuery(this).attr('data-comment');
        var nounce      = ajax_project.project_nonce;
        
        jQuery.ajax({
            url: ajax_project.ajax_url,
            type: 'POST',
            data: {
                action: 'ehrm_delete_comment_ajax',
                proj_key: proj_key,
                task_key: task_key,
                comment_key: comment_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response.status == 'error' ) {
                    toastr.error(response.message);
                } else {
                    toastr.success(response.message);
                    jQuery( '.task_comment_list' ).empty();
                    jQuery( '.task_comment_list' ).append(response.content);
                }
            }
        });
    });

    /** Close comment edit box **/
    jQuery(document).on( 'click', '#close_comment_btn', function () {
        var task_key   = jQuery( '#add_comment_task #comment_task_key' ).val();
        var proj_key   = jQuery( '#add_comment_task #comment_project_key' ).val();
        var coment_key = jQuery( '#add_comment_task #comment_coment_key' ).val();

        CKEDITOR.instances['task_comment_desc'].setData(' ');
        jQuery( '#edit_comment_task_btn' ).hide();
        jQuery( '#close_comment_btn' ).hide();
        jQuery( '#add_comment_task_btn' ).show();

    });

});