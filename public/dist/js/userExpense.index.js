$(document).ready(function() {
    $('#change-month-user-expense').datepicker({
        format: 'yyyy-mm',
        viewMode: 'months',
        minViewMode: 'months',
        autoclose: true
    });

    var userAccounting = $('#user-expense');
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
                data: 'expense',
                render: function ( data, type, row, meta ) {
                    return convertCurrency(data);
                }
            }
        ]
    });

    $(document).on('click', '#export-user-expense', function(){
        var thisButton = $(this);
        var url = '/ajax/export-user-expense';
        var month = $("#change-month-user-expense").val()

        addSpinner(thisButton);

        url = url + "?month="+month
        var fileName = "user_expense.csv";
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