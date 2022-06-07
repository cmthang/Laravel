$(document).ready(function() {
    //Date picker

    $(document).on('change', '#export_day', function () {
        var value = $(this).val();
        $("#btn-export-n-user").attr('href', '/ajax/export-users/last-activity?about-days='+value);
    });
});
