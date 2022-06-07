$(document).ready(function() {
    var engineVersionList = $('#engine_version_list');
    engineVersionList.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: engineVersionList.data('action'),
            data: {}
        },
        columnDefs: [
            {
                targets: 0,
                data: 'software'
            },
            {
                targets: 1,
                data: 'engine'
            },
            {
                targets: 2,
                data: 'engine_version'
            },
            {
                targets: 3,
                data: 'software_version'
            },
            {
                targets: 4,
                data: 'default_version'
            },
            {
                targets: 5,
                data: 'selected_vesion'
            },
            {
                targets: 6,
                data: 'action',
                orderable: false
            }
        ]
    });


    $(document).on('click', '.btn-edit-ev', function () {
        var thisButton = $(this);
        var id = $(this).data('id');

        window.location.href = '/engine-version/edit/'+id
    })

    $(document).on('click', '.btn-delete-ev', function () {
        var thisButton = $(this);
        var id = $(this).data('id');
        var url = '/ajax/delete-engine-version';

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                id: id
            },
            success: function (data) {
                removeSpinner(thisButton);
                window.location.reload();
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    })
});
