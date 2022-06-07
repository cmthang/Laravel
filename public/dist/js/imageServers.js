$(document).ready(function() {
    $(document).on('click', '.btn-add-image-server', function() {
        var thisButton = $(this);
        var zones = [];
        var url = '/ajax/add-image-servers';
        zones.push(thisButton.data('zone'));
        var size_storage = $("input[name='size_storage']").val()
        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                zones: zones,
                size_storage: size_storage
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
    });

    $(document).on('click', '.btn-remove-image-server', function() {
        var thisButton = $(this);
        var url = '/ajax/delete-image-servers';
        var id = thisButton.data('id');
        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {id: id},
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
    });

    $(document).on('click', '#btn-add-mutiple-image-server', function() {
        var thisButton = $(this);
        var zones = [];
        var checkbox_checker =  $('.checkbox-select-image-server:checked');
        checkbox_checker.each(function(){
            zones.push($(this).val())
        })
        var url = '/ajax/add-image-servers';
        var size_storage = $("input[name='size_storage']").val()
        zones.push(thisButton.data('zone'));
        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                zones: zones,
                size_storage: size_storage
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
    });

    $(document).on('change', '.software', function () {
        var thisSelect = $(this);
        var url = $(this).data('action');
        var software = $('select[name=software] option').filter(':selected').val()
        $.ajax({
            type: 'GET',
            url: url,
            data: { 
                software: software
            },
            success: function (data) {
                $(".software_version").html('');
                $.each(data.software_version, function(key,val){
                    $(".software_version").append('<option value="'+ key +'">'+ val +'</option>')
                })
                $(".engine_version").html('');
                $.each(data.engine_version, function(key,val){
                    $(".engine_version").append('<option value="'+ key +'">'+ val +'</option>')
                })
            },
            error: function (data) {
                // removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                // removeSpinner(thisButton);
            }
        });
    });

    $(document).on('change', '.software_version', function () {
        var thisSelect = $(this);
        var url = $(this).data('action');
        var software = $('select[name=software] option').filter(':selected').val()
        var software_version = $('select[name=software_version] option').filter(':selected').val()
        $.ajax({
            type: 'GET',
            url: url,
            data: { 
                software: software,
                software_version: software_version
            },
            success: function (data) {
                $(".engine_version").html('');
                $.each(data.engine_version, function(key,val){
                    $(".engine_version").append('<option value="'+ key +'">'+ val +'</option>')
                })
            },
            error: function (data) {
                // removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                // removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '#btn-copy-image-server', function() {
        var thisButton = $(this);
        var url = $(this).data('action');
        var software = $('select[name=software] option').filter(':selected').val()
        var software_version = $('select[name=software_version] option').filter(':selected').val()
        var engine_version = $('select[name=engine_version] option').filter(':selected').val()
        var zone_availiable = $('select[name=endpoint] option')
        var arr_endpoint_availiable = []
        var arr_zone_availiable = []
        if(zone_availiable.length > 1){
            zone_availiable.each(function(){
                if($(this).val() != 'none'){
                    arr_endpoint_availiable.push($(this).val())
                    arr_zone_availiable.push($(this).text())
                }
            })
        }
        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                software: software,
                software_version: software_version,
                engine_version: engine_version,
                endpoints: arr_endpoint_availiable,
                zones: arr_zone_availiable
            },
            success: function (data) {
                removeSpinner(thisButton);
                // window.location.reload();
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-update-status-region', function() {
        var thisButton = $(this);
        var url = '/ajax/update-status-region';
        var region = thisButton.data('region');
        var status = thisButton.data('status');
        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {region: region,status: status},
            success: function (data) {
                removeSpinner(thisButton);

                // window.location.reload();
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