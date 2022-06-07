$(document).ready(function() {
    var userAccounting = $('#utm-list');
    userAccounting.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: userAccounting.data('action'),
            data: {}
        },
        columnDefs: [
            {
                targets: 0,
                data: 'id'
            },
            {
                targets: 1,
                data: 'email'
            },
            {
                targets: 2,
                data: 'ip'
            },
            {
                targets: 3,
                data: 'country_code'
            },
            {
                targets: 4,
                data: 'referrer_link'
            },
            {
                targets: 5,
                data: 'created_at',
                render: function (data, type, row, meta) {
                    return convertToLocalTime(data);
                }
            }
        ]
    });
});