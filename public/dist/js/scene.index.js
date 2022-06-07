$(document).ready(function() {
    var userAccounting = $('#scene-list');
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
                data: 'scene_name'
            },
            {
                targets: 1,
                data: 'status'
            },
            {
                targets: 2,
                data: 'history',
                orderable: false
            },
            {
                targets: 3,
                // orderable: false,
                data: 'created_at',
                render: function (data, type, row, meta) {
                    return convertToLocalTime(data);
                }
            },
            {
                targets: 4,
                data: 'completed_at',
                render: function (data, type, row, meta) {
                    return convertToLocalTime(data);
                }
            },
            {
                targets: 5,
                data: 'actions',
                orderable: false
            }
        ]
    });
});