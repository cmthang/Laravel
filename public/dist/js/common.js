$(document).ready(function() {
    //Date picker
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
    //Setup for notification
    if (sessionStorage.getItem('read_flag') === 'false') {
        $('#header-notif-list .mark-notif-unread').addClass('label-notif-unread').show();
    }
    socket = io(app_socket_url + ':6002');
    socket.on('channel-name:App\\Events\\MessagePushed', function (data) {
        console.log(data);
        var pathname = window.location.pathname; 
        if (data.html && pathname != '/imageServers') {
            $('#user_activity_list[role="listitem"] tr').each(function(){
                var itemTime = $(this).data('create-time');
                var localTime = convertToLocalTime(itemTime);
                var timeElapsedString = moment(localTime).fromNow(true);
                $(this).find('.create-time-item').html('(' + timeElapsedString + ' ago)');
            });

            $('#user_activity_list[role="listitem"] tbody').prepend(data.html);
            $('#user_activity_list[role="listitem"] tr:last-child').remove();

            sessionStorage.setItem('read_flag', false);
            $('#header-notif-list .mark-notif-unread').addClass('label-notif-unread').show();

            $.notify(data.message, {
                // settings
                type: 'success',
                placement: {
                    from: 'bottom',
                    align: 'right'
                },
                offset: {
                    x: 20, y:20
                },
                newest_on_top: true
            });
        }
    });
    $(document).on('click', '#header-notif-list', function () {
        sessionStorage.setItem('read_flag', true);
        $('#header-notif-list .mark-notif-unread').removeClass('label-notif-unread').hide();

        return false;
    });

    //For other elements
    var userListEle = $('#user_list');
    userListEle.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: userListEle.data('action'),
            data: {}
        },
        columnDefs: [
            {
                targets: 0,
                data: 'user_name'
            },
            {
                targets: 1,
                data: 'level'
            },
            {
                targets: 2,
                data: 'credits',
                render: function ( data, type, row, meta ) {
                    return convertCurrency(data);
                }
            },
            {
                targets: 3,
                data: 'total_credit',
                render: function ( data, type, row, meta ) {
                    return convertCurrency(data);
                }
            },
            {
                targets: 4,
                data: 'total_job'
            },
            {
                targets: 5,
                orderable: false,
                data: 'work_space_size'
            },
            {
                targets: 6,
                data: 'send_mail_get_feedback'
            },
            {
                targets: 7,
                data: 'request_more_infomation'
            },
            {
                targets: 8,
                data: {
                    _: 'active.display',
                    sort: 'active.value'
                }
            },
            {
                targets: 9,
                data: 'created_at',
                render: function (data, type, row, meta) {
                    return convertToLocalTime(data);
                }
            },
            {
                targets: 10,
                data: 'hacker'
            },
            {
                targets: 11,
                data: 'country_code'
            },
            {
                targets: 12,
                data: 'company'
            },
            {
                targets: 13,
                data: 'note'
            },
            {
                targets: 14,
                data: 'region'
            },
            {
                targets: 15,
                data: 'need_image_server'
            },
            {
                targets: 16,
                data: 'utm_link'
            }
        ]
    });

    //For job list
    var jobDataTable = $('#job_list').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $('#job_list').data('action'),
            data: {
                'filter-id': $('#filter-id').val(),
                'filter-daterange': $('#filter-daterange').val(),
                'filter-email': $('#filter-email').val(),
                'filter-status': $('#filter-status').val(),
                'filter-output-status': $('#filter-output-status').val(),
                'filter-scene-name': $('#filter-scene-name').val(),
                'filter-render-engine': $('#filter-render-engine').val(),
                'filter-software': $('#filter-software').val()
            }
        },
        columnDefs: [
            {
                className: 'details-control',
                targets: 0,
                orderable: false,
                searchable: false,
                data: null,
                render: function ( data, type, row, meta ) {
                    return '<a href="javascript:void(0)"><i class="fa fa-angle-right fa-2x"></i></a>';
                }
            },
            {
                targets: 1,
                data: 'job'
            },
            {
                targets: 2,
                orderable: false,
                data: 'software'
            },
            {
                targets: 3,
                orderable: false,
                data: 'engine'
            },
            {
                targets: 4,
                orderable: false,
                data: 'package'
            },
            {
                targets: 5,
                orderable: false,
                data: 'machine_type'
            },
            {
                targets: 6,
                data: 'start_time',
                render: function (data, type, row, meta) {
                    return convertToLocalTime(data);
                }
            },
            {
                targets: 7,
                data: 'start_render_time',
                render: function (data, type, row, meta) {
                    return convertToLocalTime(data);
                }
            },
            {
                targets: 8,
                data: 'complete_time',
                render: function (data, type, row, meta) {
                    return convertToLocalTime(data);
                }
            },
            {
                targets: 9,
                orderable: false,
                data: 'render_by'
            },
            {
                targets: 10,
                data:
                    {
                        _: 'progress.display',
                        sort: 'progress.value'
                    }
            },
            {
                targets: 11,
                data:
                    {
                        _: 'status.display',
                        sort: 'status.value'
                    }
            },
            {
                targets: 12,
                data: 'cost'
            },
            {
                targets: 13,
                data: 'updated_at',
                render: function (data, type, row, meta) {
                    return convertToLocalTime(data);
                }
            },
            {
                targets: 14,
                data: 'region'
            },
            {
                targets: 15,
                data: 'err'
            },
            {
                targets: 16,
                data: 'browse',
                orderable: false,
                visible: $('#job_list').data('has-role'),
                render: function (data, type, row, meta) {
                    return '<a class="btn btn-xs btn-success btn-browse-output" data-email="'+data['email']+'" data-job-id="'+data['job_id']+'">Browse Output</a>';
                }
            }
        ]
    });

    $('#job_list tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = jobDataTable.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            $(this).find('.fa').addClass('fa-angle-right').removeClass('fa-angle-down');
        } else {
            var jobDetails = jQuery.parseJSON(row.data().raw_detail);

            row.child(format(jobDetails)).show();
            tr.addClass('shown');
            $(this).find('.fa').addClass('fa-angle-down').removeClass('fa-angle-right');
        }
    });

    $(document).on('click', '#user_activity_list .pagination a', function () {
        var thisButton = $(this);
        var url = thisButton.attr('href');
        url = url.split('?');
        url = '/ajax/activity?' + url[1];

        addSpinner(thisButton);
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                $('#user_activity_list').html(data);
            },
            error: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            }
        });

        return false;
    });

    $(document).on('click', '.btn-browse-output', function () {
        var thisButton = $(this);
        var email = thisButton.data('email');
        var jobId = thisButton.data('job-id');

        addSpinner(thisButton);
        $.ajax({
            type: 'GET',
            url: '/ajax/browseOutput',
            data: {user_email: email, job_id: jobId},
            success: function (data) {
                removeSpinner(thisButton);

                $('#addBrowseOutput .modal-body').html(data);
                $('#addBrowseOutput').modal('show');
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });

        return false;
    });

    $(document).on('click', '.view-large-preview-image', function () {
        var html = '<img src="'+$(this).attr('src')+'" style="max-width: 100%;margin: auto;display: block;">';
        $('#addBrowseOutput .modal-body').html(html);
        $('#addBrowseOutput').modal('show');
    });

    $(document).on('click', '.btn-view-scene-analyze', function() {
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'GET',
            url: url,
            data: {folder_id:thisButton.data('id'), email: thisButton.data('email'), fName: thisButton.data('fname'), fPath: thisButton.data('fpath')},
            success: function (data) {
                removeSpinner(thisButton);

                $('#modalSceneViewAnalyze .modal-body').html(data);
                $('#modalSceneViewAnalyze').modal('show');
            },
            error: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            }
        });
    });

    $(document).on('click', '.btn-edit-job-status', function () {
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                $('#modalSystemCommon .modal-content').html(data);
                $('#modalSystemCommon').modal('show');
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-save-job-status', function () {
        var thisButton = $(this);
        var url = thisButton.data('action');
        var jobId = thisButton.data('id');
        $('#edit-job-message').html('');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {status: $('#select-job-status').val()},
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    $('.btn-edit-job-status-' +jobId).parent().html(data.html);
                    $('#modalSystemCommon').modal('hide');
                } else {
                    $('#edit-job-message').html('<p class="alert alert-danger">'+data.message+'</p>');
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-active-user', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.parent().html(data.html);

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });
    $(document).on('click', '.btn-send-mail-get-feedback', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    //btn-notify-reload-app 
    $(document).on('click', '.btn-notify-reload-app', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-force-sync-asset', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-request-more-infomation', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-update-user-multiaz', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });
    $(document).on('click', '.btn-update-auto-sync-asset', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });
    $(document).on('click', '.btn-update-user-is-student', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });
    $(document).on('click', '.btn-update-user-download-dataset', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });
    $(document).on('click', '.btn-enable-user-chunksize', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data:{
                column: 'chunksize',
                value: $("#field-enable-chunksize").prop('checked')
            },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });
    $(document).on('click', '.btn-mark-user-as-hacker', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');
        var active = $('select[name=software] option').filter(':selected').val()
        var hacker = $('select[name=software_version] option').filter(':selected').val()
        var engine_version = $('select[name=engine_version] option').filter(':selected').val()
        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-update-user-is-old-user', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-update-user-need-image-server', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data:{
                column: 'need_image_server'
            },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '#btn-export-user', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');
        var active = $("input[name='active']").prop("checked") ? 1 : 0
        var hacker = $("input[name='hacker']").prop("checked") ? 1 : 0
        var register_from = $("input[name='valid_date_register_from']").val()
        var register_to = $("input[name='valid_date_register_to']").val()
        var last_activity_from = $("input[name='valid_date_last_activity_from']").val()
        var last_activity_to = $("input[name='valid_date_last_activity_to']").val()
        var total_payment_from = $("input[name='total_payment_from']").val()
        var total_payment_to = $("input[name='total_payment_to']").val()
        var total_job_from = $("input[name='total_job_from']").val()
        var total_job_to = $("input[name='total_job_to']").val();

        addSpinner(thisButton);

        url = url + "?active="+active+"&hacker="+hacker+"&register_from="+register_from+"&register_to="+register_to+"&last_activity_from="+last_activity_from+"&last_activity_to="+last_activity_to+"&total_payment_from="+total_payment_from+"&total_payment_to="+total_payment_to+"&total_job_from="+total_job_from+"&total_job_to="+total_job_to
        console.log(url)
        var fileName = "users.csv";
        var element = document.createElement('a');
        element.setAttribute('href', url);
        element.setAttribute('download', fileName);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
        removeSpinner(thisButton);
    });
    $(document).on('click', '#btn-export-job', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');
        var filter_user_id = $("input[name='filter-user-id']").val()
        var filter_id = $("input[name='filter-id']").val()
        var filter_daterange = $("input[name='filter-daterange']").val()
        var filter_time_zone = $("input[name='filter-time-zone']").val()
        var filter_scene_name = $("input[name='filter-scene-name']").val()
        var filter_email = $("input[name='filter-email']").val()
        var filter_status = $('select[name=filter-status] option').filter(':selected').val()
        var filter_output_status = $('select[name=filter-output-status] option').filter(':selected').val()

        addSpinner(thisButton);

        url = url + "?filter_user_id="+filter_user_id+"&filter_id="+filter_id+"&filter_daterange="+filter_daterange+"&filter_time_zone="+filter_time_zone+"&filter_scene_name="+filter_scene_name+"&filter_email="+filter_email+"&filter_status="+filter_status+"&filter_output_status="+filter_output_status
        console.log(url);
        var fileName = "jobs.csv";
        var element = document.createElement('a');
        element.setAttribute('href', url);
        element.setAttribute('download', fileName);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
        removeSpinner(thisButton);
    });

    $(document).on('click', '.btn-enable-error-checking', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data:{
                column: 'error_checking',
                value: $("#field-enable-error-checking").prop('checked')
            },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });
});

function format(jobDetails) {
    var jobParams = jQuery.parseJSON(jobDetails.params);

    var html = '<div class="row">';
    html += '<div class="col-sm-3">' +
                'File name: <b>'+jobParams['job_detail']['filename']+'</b><br>' +
                'Full path: <b>'+jobParams['job_detail']['filepath']+jobParams['job_detail']['filename']+'</b><br>' +
                'Render by: <b>'+jobParams['render_by']+'</b><br>' +
                getJobSelectedLayer(jobDetails) +
            '</div>' +
            '<div class="col-sm-3">' +
                'Software version: <b>'+jobParams['software'] +' ' + jobParams['version'] + '</b><br>' +
                'Frame jump: <b>'+jobParams['step_frame']+'</b><br>' +
                'Samples: <b>'+jobParams['samples']+'</b>' +
            '</div>' +
            '<div class="col-sm-3">' +
                'Render engine: <b>'+jobParams['engine']+'</b><br>' +
                'Selected scene: <b>'+jobParams['scene_name']+'</b><br>' +
                'Output format: <b>'+jobParams['file_format']+'</b><br>' +
                'Enable Camera: <b>'+jobParams['job_detail']['enable_custom_camera']+'</b><br>' +
                'Camera: <b>'+jobParams['camera']+'</b>' +
            '</div>' +
            '<div class="col-sm-3">' +
                'Frames: <b>'+jobParams['job_detail']['frames']+'</b><br>' +
                'Percentage: <b>'+jobParams['percentage']+'</b><br>' +
                'Resolution (W-H): <b>'+jobParams['width'] + '-' +jobParams['height']+'</b>' +
            '</div>';

    if (jobDetails['render_preview']) {
        html += '<div class="col-sm-12">' +
                    '<div class="row">' +
                        '<div class="col-sm-6">' +
                            'Cost Estimation: <b>$' + jobDetails['cost_estimation'] + '</b><br>' +
                            'Estimated Time: <b>' + jobDetails['time_estimation'] + '</b><br>' +
                            'Total Frame: <b>' + jobParams['total_frame'] + '</b>' +
                        '</div>';

        if ($('#job_list').data('has-role') && jobDetails['url_output']) {
            html += '<div class="col-sm-6">' +
                        'Preview Image: <img src="'+jobDetails['url_output']+'" class="img-responsive view-large-preview-image" />' +
                    '</div>';
        }

        html += '</div>' +
                '</div>';
    }

    html += '</div>';

    return html;
}

function convertCurrency(amount) {
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });

    return formatter.format(amount);
}

function convertToLocalTime(time) {
    if (time) {
        var localTime1 = moment.utc(time).toDate();

        return moment(localTime1).format('YYYY-MM-DD HH:mm:ss');
    }

    return time;
}

function checkphonenumber(number) {
    var matches = number.match(/^(012[0-9]|016[2-9]|0186|0188|0199|09[0-4]|09[6-9]|086|088|089)[0-9]{7}$/);
    var carriers_number = {
        '096': 'Viettel',
        '097': 'Viettel',
        '098': 'Viettel',
        '0162': 'Viettel',
        '0163': 'Viettel',
        '0164': 'Viettel',
        '0165': 'Viettel',
        '0166': 'Viettel',
        '0167': 'Viettel',
        '0168': 'Viettel',
        '0169': 'Viettel',
        '086': 'Viettel',

        '090': 'Mobifone',
        '093': 'Mobifone',
        '0120': 'Mobifone',
        '0121': 'Mobifone',
        '0122': 'Mobifone',
        '0126': 'Mobifone',
        '0128': 'Mobifone',
        '089': 'Mobifone',

        '091': 'Vinaphone',
        '094': 'Vinaphone',
        '0123': 'Vinaphone',
        '0124': 'Vinaphone',
        '0125': 'Vinaphone',
        '0127': 'Vinaphone',
        '0129': 'Vinaphone',
        '088': 'Vinaphone',

        '0993': 'Gmobile',
        '0994': 'Gmobile',
        '0995': 'Gmobile',
        '0996': 'Gmobile',
        '0997': 'Gmobile',
        '0199': 'Gmobile',

        '092': 'Vietnamobile',
        '0186': 'Vietnamobile',
        '0188': 'Vietnamobile',

        '0992': 'VSAT',

        '0998': 'Indochina Telecom',
        '0999': 'Indochina Telecom',
        //'095'  : 'SFone'
    };
    //console.log(carriers_number);
    if (matches == null) {
        //console.log('false');
        return false;
    } else {
        // if(matches[1].length==2){
        //  var number_head = number.substr(0, 3);
        //  console.log(carriers_number[number_head]);
        // }
        var key = Object.keys(carriers_number);
        //console.log(matches);
        var val = matches[1];
        for (var i in key) {
            //console.log(val);
            if (key[i].match(val) != null) {
                //console.log('true');
                //console.log(carriers_number[key[i]]);
                return carriers_number[key[i]];
            }
        }
    }
}

function addSpinner(ele, position) {
    if (position === undefined) {
        position = 'inner';
    }

    if (position == 'inner') {
        ele.append(' <i class="fa fa-spinner fa-spin"></i>');
    } else if (position == 'after') {
        ele.after('<i class="fa fa-spinner fa-spin"></i>');
    } else if (position == 'absolute') {
        ele.css('position', 'relative');
        ele.after('<i class="fa fa-spinner fa-spin spin-absolute"></i>');
    }
}

function removeSpinner(ele, position) {
    if (position === undefined) {
        position = 'inner';
    }

    if (position == 'inner') {
        ele.find('.fa-spin').remove();
    } else if (position == 'after' || position == 'absolute') {
        ele.next().remove();
    }
}

function showNotifyMessage(type, message, icon) {
    if (icon === undefined) {
        icon = 'fa fa-check';
    }

    $.notify({
        // options
        icon: icon,
        //title: 'Bootstrap notify',
        //url: '',
        //target: '_blank',
        message: message
    }, {
        // settings
        //showProgressbar: true,
        type: type,
        newest_on_top: true
    });
}

function getJobSelectedLayer(jobDetails) {
    var selectedLayerText = '';
    var jobParams = jQuery.parseJSON(jobDetails['params']);

    if (jobParams['software'] == 'blender') {
        selectedLayerText = 'Selected Layer: <b>' + jobDetails['scene_name'] + '</b>';
    } else if (jobParams['software'] == 'maya') {
        selectedLayerText = 'Selected Layer: <b>' + jobParams['job_detail']['render_layer'] + '</b>';
    } else if (jobParams['software'] == 'houdini') {
        selectedLayerText = 'Selected Render Node: <b>' + jobParams['job_detail']['render_node'] + '</b>';
    }

    return selectedLayerText;
}