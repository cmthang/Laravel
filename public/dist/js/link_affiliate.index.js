$(document).ready(function() {
    var affiliate_link_list = $('#affiliate_link_list');
    affiliate_link_list.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: affiliate_link_list.data('action'),
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
                data: 'user_root_value'
            },
            {
                targets: 3,
                data: 'user_use_aff_value'
            },
            {
                targets: 4,
                data: 'number_of_uses'
            },
            {
                targets: 5,
                data: 'number_of_uses_per_user'
            },
            {
                targets: 6,
                data: 'note',
                orderable: false
            },
            {
                targets: 7,
                data: 'created_at'
            },
            {
                targets: 8,
                data: 'actions',
                orderable: false
            }
        ]
    });

    $(document).on('click', '.btn-remove-aff-link', function () {
        var thisButton = $(this);
        var url = $(this).data('action');
        var confirmRemove = confirm('Are you sure to remove this link?');

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