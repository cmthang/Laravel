$(document).ready(function() {
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });

    var black_list = $('#group-discount-list');
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
                data: 'discount_cpu'
            },
            {
                targets: 2,
                data: 'discount_gpu'
            },
            {
                targets: 3,
                data: 'active'
            },
            {
                targets: 4,
                data: 'actions',
                orderable: false
            }
        ]
    });

    $(document).on('click', '.btn-remove-group-discount', function(){
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

    $(document).on('click', '#btn-update-group-discount', function(){
        var thisButton = $(this);
        var url = '/ajax/update-group-discount';

        var id = $('#id_group_discount').val();
        var name = $('#group_discount_name').val();
        var discount_cpu = $('#field_discount_cpu').val();
        var discount_gpu = $('#field_discount_gpu').val();
        var date_from = $('#field_date_from').val();
        var date_to = $('#field_date_to').val();
        var active = $('#group_discount_active').prop('checked');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data:{
                id: id,
                name: name,
                discount_cpu: discount_cpu,
                discount_gpu: discount_gpu,
                date_from: date_from,
                date_to: date_to,
                active: active
            },
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