$(document).ready(function() {
    //Date picker
    $('.datepicker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });

    $('#field-description').wysihtml5();

    $(document).on('change', '#field-condition', function () {
        var thisButton = $(this);
        var conditionValue = $(this).val();

        addSpinner(thisButton);
        $.ajax({
            type: 'GET',
            url: '/promotion/gift/buildCondition',
            data: {type: conditionValue, value: $('#gift-condition-setting').val()},
            success: function (data) {
                removeSpinner(thisButton);

                $('#field-condition-setting-wrap').html(data);
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
