$(document).ready(function() {
    var black_list = $('#black-domain-list');
    black_list.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: black_list.data('action'),
            data: {}
        },
        columnDefs: [
            {
                targets: 0,
                data: 'name'
            },
            {
                targets: 1,
                data: 'actions',
                orderable: false
            }
        ]
    });

    var while_list = $('#while-domain-list');
    while_list.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: while_list.data('action'),
            data: {}
        },
        columnDefs: [
            {
                targets: 0,
                data: 'name'
            },
            {
                targets: 1,
                data: 'actions',
                orderable: false
            }
        ]
    });

    $(document).on('click', '.user-detail-header', function() {
        var iconCollapse = $(this).find('.fa');
        if (iconCollapse.hasClass('fa-angle-right')) {
            $(this).next('.user-detail-body').slideDown('slow', function() {
                iconCollapse.removeClass('fa-angle-right').addClass('fa-angle-down');
            });
        } else {
            $(this).next('.user-detail-body').slideUp('fast', function() {
                iconCollapse.removeClass('fa-angle-down').addClass('fa-angle-right');
            });
        }
    });

    $(document).on('click', '.btn-remove-while-domain', function(){
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
                window.location.reload()
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-remove-black-domain', function(){
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
                window.location.reload()
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-add-black-domain', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');
        var domain = $('#input-domain').val()
        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data:{ name: domain },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.parent().html(data.html);

                } else {
                    showNotifyMessage('danger', data.message);
                }
                window.location.reload()
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-add-while-domain', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');
        var domain = $('#input-domain').val()
        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data:{ name: domain },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.parent().html(data.html);

                } else {
                    showNotifyMessage('danger', data.message);
                }
                window.location.reload()
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