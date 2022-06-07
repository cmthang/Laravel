$(document).ready(function() {
    $('#change-month-accounting').datepicker({
        format: 'yyyy-mm',
        viewMode: 'months',
        minViewMode: 'months',
        autoclose: true
    });

    var userAccounting = $('#user-accounting');
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
                data: 'user_id'
            },
            {
                targets: 1,
                // orderable: false,
                data: 'email',
            },
            {
                targets: 2,
                data: 'remain_credits',
                render: function ( data, type, row, meta ) {
                    return convertCurrency(data);
                }
            },
            {
                targets: 3,
                data: 'total_purchase',
                render: function ( data, type, row, meta ) {
                    return convertCurrency(data);
                }
            },
            {
                targets: 4,
                data: 'total_credits',
                render: function ( data, type, row, meta ) {
                    return convertCurrency(data);
                }
            },
            {
                targets: 5,
                data: 'expense',
                render: function ( data, type, row, meta ) {
                    return convertCurrency(data);
                }
            }
        ]
    });

    $(document).on('click', '#export-accounting', function(){
        var thisButton = $(this);
        var url = '/ajax/export-accounting';
        var month = $("#change-month-accounting").val()

        addSpinner(thisButton);

        url = url + "?month="+month
        var fileName = "accounting.csv";
        var element = document.createElement('a');
        element.setAttribute('href', url);
        element.setAttribute('download', fileName);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
        removeSpinner(thisButton);
    });
});