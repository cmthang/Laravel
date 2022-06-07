$(document).ready(function() {
    var systemEnvList = $('#system_env_list');
    systemEnvList.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: systemEnvList.data('action'),
            data: {}
        },
        columnDefs: [
            {
                targets: 0,
                data: 'name'
            },
            {
                targets: 1,
                data: 'value'
            },
            {
                targets: 2,
                data: 'note'
            },
            {
                targets: 3,
                data: 'type'
            },
            {
                targets: 4,
                data: 'action',
                orderable: false
            }
        ]
    });

    $(document).on('click', '.btn-edit-cse', function () {
        var thisButton = $(this);
        var id = $(this).data('id');

        window.location.href = '/system-env/edit/'+id
    })
});