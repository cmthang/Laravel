$(document).ready(function() {
    $(document).on('click', '#field-depend_payment_value', function () {
        if ($(this).is(':checked')) {
            $('#depend_payment_value-wrap').removeClass('hidden');
        } else {
            $('#depend_payment_value-wrap').addClass('hidden');
        }
    });

    $(document).on('click', '#field-is_giftCode', function () {
        if ($(this).is(':checked')) {
            $('#gift_code_value-wrap').removeClass('hidden');
        } else {
            $('#gift_code_value-wrap').addClass('hidden');
        }
    });

    //Date picker
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });

    $('#field-note').wysihtml5();

    $(document).on('click', '.btn-remove-depend-value', function () {
        var confirmRemove = confirm('Are you sure to remove this?');

        if (confirmRemove) {
            $(this).parents('.depend_payment_value-item').remove();
        }

        return false;
    });

    $(document).on('click', '.btn-add-more-depend-value', function () {
        var cloneEle = $('.depend_payment_value-item:eq(0)').clone();
        cloneEle.find(':input').val('');
        var addMoreButton = cloneEle.find('.btn-add-more-depend-value');
        addMoreButton.removeClass('btn-add-more-depend-value btn-success').addClass('btn-danger btn-remove-depend-value').html('<i class="fa fa-trash-o"></i>');
        $('#depend_payment_value-wrap').append(cloneEle);

        return false;
    });
});