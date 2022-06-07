$(document).ready(function() {
    var userAccounting = $('#swpt-list');
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
                data: 'software'
            },
            {
                targets: 1,
                data: 'engine'
            },
            {
                targets: 2,
                data: 'package_type'
            },
            {
                targets: 3,
                data: 'type'
            },
            {
                targets: 4,
                data: 'default_package'
            }
        ]
    });

    $(document).on('click', '.btn-update-dswpt', function () {
        var thisButton = $(this);
        var arrUpdateSwpt = {
            sw: thisButton.data('sw'),
            eg: thisButton.data('eg'),
            type: thisButton.data('type')
        }
        window.localStorage.setItem('arrUpdateSwpt',JSON.stringify(arrUpdateSwpt))
        $('#modalEditSWPT').modal('show');
    })

    $(document).on('click', '.btn-save-swpt', function () {
        var thisButton = $(this);
        var url = thisButton.data('action');
        var data = window.localStorage.getItem('arrUpdateSwpt')
        data = JSON.parse(data)
        data.default_package = $('#swpt-input').val()
        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function (data) {
                removeSpinner(thisButton);
                if (data.success) {
                    $('#modalEditSWPT').modal('hide');
                    window.location.reload()
                } else {
                    alert(data.message);
                }
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