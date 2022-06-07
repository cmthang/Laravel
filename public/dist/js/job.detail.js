$(document).ready(function() {
    $('#task-render-detail-list').DataTable();

    $(document).on('click', '.btn-get-job-detail-log', function () {
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    $('#modalGetLogTask .modal-body').html('<pre style="font-size: 10px;">' + data.task_log + '</pre>');
                    $('#modalGetLogTask').modal('show');
                } else {
                    showNotifyMessage('danger', data.message);
                }
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

    $(document).on('click', '.btn-change-amount-job', function() {
        var thisButton = $(this);
        var amountJobWrap = $('.update-amount-job-wrap');

        if (amountJobWrap.css('display') === 'block') {
            amountJobWrap.slideUp('fast');
            thisButton.find('.fa').removeClass('fa-minus').addClass('fa-plus');
        } else {
            amountJobWrap.slideDown('slow');
            thisButton.find('.fa').removeClass('fa-plus').addClass('fa-minus');
        }

        return false;
    });

    $(document).on('click', '.btn-update-time-render-job', function() {
        var thisButton = $(this);
        var amountJobWrap = $('.update-time-render-job-wrap');

        if (amountJobWrap.css('display') === 'block') {
            amountJobWrap.slideUp('fast');
            thisButton.find('.fa').removeClass('fa-minus').addClass('fa-plus');
        } else {
            amountJobWrap.slideDown('slow');
            thisButton.find('.fa').removeClass('fa-plus').addClass('fa-minus');
        }

        return false;
    });

    $(document).on('click', '.btn-update-machine-type', function() {
        var thisButton = $(this);
        var amountJobWrap = $('.update-machine-type-wrap');

        if (amountJobWrap.css('display') === 'block') {
            amountJobWrap.slideUp('fast');
            thisButton.find('.fa').removeClass('fa-minus').addClass('fa-plus');
        } else {
            amountJobWrap.slideDown('slow');
            thisButton.find('.fa').removeClass('fa-plus').addClass('fa-minus');
        }

        return false;
    });

    $(document).on('click', '#btn-save-amount-job', function() {
        var thisButton = $(this);
        var amount = $('#input-job-amount').val();
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'GET',
            url: url,
            data: {amount: amount},
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    window.location.reload();
                } else {
                    showNotifyMessage('danger', data.message);
                }
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
    $(document).on('click', '#btn-save-time-render-job', function() {
        var thisButton = $(this);
        var time = $('#input-time-render-job').val();
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {time: time},
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    window.location.reload();
                } else {
                    showNotifyMessage('danger', data.message);
                }
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

    $(document).on('click', '#btn-save-machine-type', function() {
        var thisButton = $(this);
        var package_type = $('#input-job-package-type').val();
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {job_package_type: package_type},
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    window.location.reload();
                } else {
                    showNotifyMessage('danger', data.message);
                }
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

    $(document).on('click', '.btn-force-sync-output', function() {
        var thisButton = $(this);
        var url = thisButton.data('action');
        var email = thisButton.data('email');
        var job_id = thisButton.data('id');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {email: email,job_id: job_id},
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    window.location.reload();
                } else {
                    showNotifyMessage('danger', data.message);
                }
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

    $(document).on('click', '.btn-update-job-cost', function() {
        var thisButton = $(this);
        var addCreditWrap = $('.update-job-cost-wrap');

        if (addCreditWrap.css('display') === 'block') {
            addCreditWrap.slideUp('fast');
            thisButton.find('.fa').removeClass('fa-minus').addClass('fa-plus');
        } else {
            addCreditWrap.slideDown('slow');
            thisButton.find('.fa').removeClass('fa-plus').addClass('fa-minus');
        }

        return false;
    });

    $(document).on('click', '#btn-submit-update-job-cost', function() {
        var thisButton = $(this);
        var cost = $('#job-cost').val();
        var note = $('#job-cost-note').val();
        var url = thisButton.data('action');
        var id = thisButton.data('id');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                cost: cost,
                note: note,
                job_id: id,
            },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    $('.btn-update-job-cost').trigger('click');
                    window.location.reload();
                } else {
                    showNotifyMessage('danger', data.message);
                }
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
});