$(document).ready(function() {
    var couponList = $('#coupon_list');
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
                data: 'code'
            },
            {
                targets: 1,
                data: 'status'
            },
            {
                targets: 2,
                data: 'coupon_type'
            },
            {
                targets: 3,
                data: 'valid_date_from'
            },
            {
                targets: 4,
                data: 'valid_date_to'
            },
            {
                targets: 5,
                data: 'value_type'
            },
            {
                targets: 6,
                data: 'promotion_value',
                render: function ( data, type, row, meta ) {
                    return convertCurrency(data);
                }
            },
            {
                targets: 7,
                data: 'depend_payment_value',
                orderable: false
            },
            {
                targets: 8,
                data: 'created_at',
                render: function (data, type, row, meta) {
                    return convertToLocalTime(data);
                }
            },
            {
                targets: 9,
                data: 'actions',
                orderable: false
            }
        ]
    });

    $(document).on('click', '.btn-remove-coupon', function () {
        var thisButton = $(this);
        var url = $(this).data('action');
        var confirmRemove = confirm('Are you sure to remove this Coupon?');

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