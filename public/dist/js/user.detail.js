$(document).ready(function() {
    $(document).on('click', '.user-detail-header', function() {
        var iconCollapse = $(this).find('.fa');
        if (iconCollapse.hasClass('fa-angle-right')) {
            $(this).next('.user-detail-body').slideDown('slow', function() {
                iconCollapse.removeClass('fa-angle-right').addClass('fa-angle-down');
            });
        } else {
            $(this).next('.user-detail-body').slideUp('fast', function() {
                iconCollapse.removeClass('fa-angle-down').addClass('fa-angle-right');
            });
        }
    });

    $(document).on('click', '.btn-add-more-credits', function() {
        var thisButton = $(this);
        var addCreditWrap = $('.user-add-more-credits-wrap');

        if (addCreditWrap.css('display') === 'block') {
            addCreditWrap.slideUp('fast');
            thisButton.find('.fa').removeClass('fa-minus').addClass('fa-plus');
        } else {
            addCreditWrap.slideDown('slow');
            thisButton.find('.fa').removeClass('fa-plus').addClass('fa-minus');
        }

        return false;
    });
    $(document).on('click', '.btn-update-preview-limit', function() {
        var thisButton = $(this);
        var addCreditWrap = $('.user-update-preview-limit-wrap');

        if (addCreditWrap.css('display') === 'block') {
            addCreditWrap.slideUp('fast');
            thisButton.find('.fa').removeClass('fa-minus').addClass('fa-plus');
        } else {
            addCreditWrap.slideDown('slow');
            thisButton.find('.fa').removeClass('fa-plus').addClass('fa-minus');
        }

        return false;
    });

    $(document).on('click', '.btn-update-user-company', function() {
        var thisButton = $(this);
        var addCreditWrap = $('.user-update-company-wrap');

        if (addCreditWrap.css('display') === 'block') {
            addCreditWrap.slideUp('fast');
            thisButton.find('.fa').removeClass('fa-minus').addClass('fa-plus');
        } else {
            addCreditWrap.slideDown('slow');
            thisButton.find('.fa').removeClass('fa-plus').addClass('fa-minus');
        }

        return false;
    });

    $(document).on('click', '.btn-update-user-note', function() {
        var thisButton = $(this);
        var addCreditWrap = $('.user-update-note-wrap');

        if (addCreditWrap.css('display') === 'block') {
            addCreditWrap.slideUp('fast');
            thisButton.find('.fa').removeClass('fa-minus').addClass('fa-plus');
        } else {
            addCreditWrap.slideDown('slow');
            thisButton.find('.fa').removeClass('fa-plus').addClass('fa-minus');
        }

        return false;
    });

    $(document).on('click', '.btn-update-user-region', function() {
        var thisButton = $(this);
        var addCreditWrap = $('.user-update-region-wrap');

        if (addCreditWrap.css('display') === 'block') {
            addCreditWrap.slideUp('fast');
            thisButton.find('.fa').removeClass('fa-minus').addClass('fa-plus');
        } else {
            addCreditWrap.slideDown('slow');
            thisButton.find('.fa').removeClass('fa-plus').addClass('fa-minus');
        }

        return false;
    });

    $(document).on('click', '.btn-update-user-country-code', function() {
        var thisButton = $(this);
        var addCreditWrap = $('.user-update-country-code-wrap');

        if (addCreditWrap.css('display') === 'block') {
            addCreditWrap.slideUp('fast');
            thisButton.find('.fa').removeClass('fa-minus').addClass('fa-plus');
        } else {
            addCreditWrap.slideDown('slow');
            thisButton.find('.fa').removeClass('fa-plus').addClass('fa-minus');
        }

        return false;
    });

    $(document).on('click', '#btn-save-user-credits', function() {
        var thisButton = $(this);
        var credit = $('#user-credits-amount').val();
        var note = $('#user-credits-note').val();
        var url = thisButton.data('action');
        let addToPayment = false;
        let notification = false;
        let purchaseAmount = 0;
        let createdAt = '';

        if ($('#field-add-payment').is(':checked')) {
            addToPayment = true;
            purchaseAmount = $('#purchase-amount').val();
            createdAt = $('#payment-create-at').val();
            if ($('#field-notification-to-discord').is(':checked')) {
                notification = true;
            }
        }

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                credit: credit,
                note: note,
                addToPayment: addToPayment,
                purchaseAmount: purchaseAmount,
                createdAt: createdAt,
                notification: notification
            },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    $('.btn-add-more-credits').trigger('click');
                    window.location.reload();
                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            }
        });
    });

    $(document).on('click', '#btn-update-company', function() {
        var thisButton = $(this);
        var company = $('#user-company').val();
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                company: company
            },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    $('.btn-update-user-company').trigger('click');
                    window.location.reload();
                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            }
        });
    });
    $(document).on('click', '#btn-update-note', function() {
        var thisButton = $(this);
        var note = $('#user-note').val();
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                note: note
            },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    $('.btn-update-user-note').trigger('click');
                    window.location.reload();
                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            }
        });
    });
    $(document).on('click', '#btn-update-region', function() {
        var thisButton = $(this);
        var region = $('#select-user-region').val();
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                region: region
            },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    $('.btn-update-user-region').trigger('click');
                    window.location.reload();
                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            }
        });
    });
    $(document).on('click', '#btn-update-country-code', function() {
        var thisButton = $(this);
        var countryCode = $('#user-country-code').val();
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                country_code: countryCode
            },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    $('.btn-update-user-country-code').trigger('click');
                    window.location.reload();
                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            }
        });
    });
    $(document).on('click', '#btn-update-preview-limit', function() {
        var thisButton = $(this);
        var preview_limit = $('#user-preview-limit').val();
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                preview_limit: preview_limit
            },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    $('.btn-update-preview-limit').trigger('click');
                    window.location.reload();
                } else {
                    showNotifyMessage('danger', data.message);
                }
            },
            error: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
                showNotifyMessage('danger', 'Oops, something wrong.');
            }
        });
    });

    //For payment history
    var paymentHistory = $('#payment_history');
    var paymentHistoryDataTable = paymentHistory.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: paymentHistory.data('action'),
            data: {}
        },
        columnDefs: [
            {
                targets: 0,
                data: 'date',
                render: function (data, type, row, meta) {
                    if (data) {
                        var localTime = moment.utc(data).toDate();
                        return moment(localTime).format('YYYY-MM-DD HH:mm:ss');
                    }

                    return data;
                }
            },
            {
                targets: 1,
                orderable: false,
                data: 'order_number',
                render: function (data, type, row, meta) {
                    var html = data;

                    if (row.transaction_detail) {
                        html += ' <i class="fa fa-angle-right fa-2x btn-show-transaction-detail"></i><pre class="transaction-detail-wrap hide" style="max-width: 550px;"></pre>';
                    }

                    return html;
                }
            },
            {
                targets: 2,
                data: 'credits',
                render: function ( data, type, row, meta ) {
                    var formatter = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                    });

                    return formatter.format(data);
                }
            },
            {
                targets: 3,
                data: 'promotion_code'
            },
            {
                targets: 4,
                data: 'status'
            },
            {
                targets: 5,
                data: 'purchase_amount',
                render: function ( data, type, row, meta ) {
                    var formatter = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                    });

                    return formatter.format(data);
                }
            }
        ]
    });

    $(document).on('click', '.btn-show-transaction-detail', function () {
        var tr = $(this).closest('tr');
        var row = paymentHistoryDataTable.row(tr);

        var transactionDetail = $(this).next();
        transactionDetail.html(JSON.stringify(row.data().transaction_detail, undefined, 4));

        if ($(this).hasClass('fa-angle-down')) {
            transactionDetail.addClass('hide');
            $(this).addClass('fa-angle-right').removeClass('fa-angle-down');
        } else {
            transactionDetail.removeClass('hide');
            $(this).addClass('fa-angle-down').removeClass('fa-angle-right');
        }
    });

    $(document).on('click', '.btn-edit-user-level', function () {
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);
                $('#modalEditUserLevel .modal-body').html(data);
                $('#modalEditUserLevel').modal('show');
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-ovr-lv', function () {
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);
                $('#modalEditOvrLv .modal-body').html(data);
                $('#modalEditOvrLv').modal('show');
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-edit-user-roles', function () {
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                removeSpinner(thisButton);
                $('#modalEditUserRoles .modal-body').html(data);
                $('#modalEditUserRoles').modal('show');
            },
            error: function (data) {
                removeSpinner(thisButton);
            },
            ajaxError: function (data) {
                removeSpinner(thisButton);
            }
        });
    });

    $(document).on('click', '.btn-save-user-level', function () {
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {level: $('#select-user-level').val()},
            success: function (data) {
                removeSpinner(thisButton);
                if (data.success) {
                    $('#modalEditUserLevel').modal('hide');
                    $('.current-user-level').html(data.new_level);
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

    $(document).on('click', '.btn-save-ovr-lv', function () {
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {level: $('#select-user-level').val()},
            success: function (data) {
                removeSpinner(thisButton);
                if (data.success) {
                    $('#modalEditOvrLv').modal('hide');
                    $('.current-user-ovr-lv').html(data.new_level);
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

    $(document).on('click', '.btn-save-user-roles', function () {
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data: {roles: $('#select-user-roles').val()},
            success: function (data) {
                removeSpinner(thisButton);
                if (data.success) {
                    $('#modalEditUserRoles').modal('hide');
                    $('.current-user-roles').html(data.new_roles);
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

    $('#payment-create-at').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });

    $(document).on('change', '#field-add-payment', function() {
        let addPaymentWrapEle = $('.add-to-payment-info-wrap');

        if ($(this).is(':checked')) {
            addPaymentWrapEle.show();
        } else {
            addPaymentWrapEle.hide();
        }
    });

    $('#field-add-payment').trigger('change');

    //For payment history
    var adminAddCreditsList = $('#admin-add-credits-list');
    var adminAddCreditsListDataTable = adminAddCreditsList.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: adminAddCreditsList.data('action'),
            data: {}
        },
        columnDefs: [
            {
                targets: 0,
                data: 'name'
            },
            {
                targets: 1,
                data: 'created_at'
            },
        ]
    });

    $(document).on('click', '#btn-update-chunksize-val', function(){
        var thisButton = $(this);
        var url = thisButton.data('action');

        addSpinner(thisButton);
        $.ajax({
            type: 'POST',
            url: url,
            data:{
                column: 'chunksize_val',
                value: $("#user-chunksize-val").val()
            },
            success: function (data) {
                removeSpinner(thisButton);

                if (data.success) {
                    showNotifyMessage('success', data.message);
                    thisButton.addClass('btn-success').removeClass('btn-warning');

                } else {
                    showNotifyMessage('danger', data.message);
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