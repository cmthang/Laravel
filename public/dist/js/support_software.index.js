$(document).ready(function (){

    var supportSoftwareList = $('#support_software_list');
    supportSoftwareList.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: supportSoftwareList.data('action'),
            data: {}
        },
        columnDefs: [
            {
                targets: 0,
                data: 'software'
            },
            {
                targets: 1,
                data: 'lable'
            },
            {
                targets: 2,
                data: 'value'
            },
            {
                targets: 3,
                data: 'order_version'
            },
            {
                targets: 4,
                data: 'action',
                orderable: false
            }
        ]


    }); 


    $(document).on('click', '.btn-edit-ev', function() {

        var thisButton = $(this);

        var id = $(this).data('id');

        window.location.href = '/supported-software/' + id +'/edit';

    })

    $(document).on('click', '.btn-delete-ev', function() {

        var thisButton = $(this);

        var id = $(this).data('id');
            
        var url = '/supported-software/' + id;

        addSpinner(thisButton);

        $.ajax({
            type: 'DELETE',
            url: url,
            data: {
                id: id
            },
            
            success: function (data){
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