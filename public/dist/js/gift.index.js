$(document).ready(function() {
    var couponList = $('#gift_list');
    couponList.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: couponList.data('action'),
            data: {}
        },
        columnDefs: [
            {
                targets: 0,
                data: 'promotion_code'
            },
            {
                targets: 1,
                data: 'title'
            },
            {
                targets: 2,
                data: 'subtitle'
            },
            {
                targets: 3,
                data: 'value'
            },
            {
                targets: 4,
                data: 'type'
            },
            {
                targets: 5,
                data: 'active'
            },
            {
                targets: 6,
                data: 'valid_date_from'
            },
            {
                targets: 7,
                data: 'valid_date_to'
            },
            {
                targets: 8,
                data: 'condition',
                orderable: false
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
                data: 'actions',
                orderable: false
            }
        ]
    });

    $(document).on('click', '.btn-remove-gift', function () {
        var thisButton = $(this);
        var url = $(this).data('action');
        var confirmRemove = confirm('Are you sure to remove this Gift?');

        if (confirmRemove) {
            addSpinner(thisButton);
            $.ajax({
                type: 'GET',
                url: url,
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
                },
                ajaxError: function (data) {
                    removeSpinner(thisButton);
                }
            });
        }
    });
});