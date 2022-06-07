$(document).ready(function() {
    $('#filter-daterange').daterangepicker({
        autoUpdateInput: false,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: 'YYYY/MM/DD'
        }
    }, initDateRange);

    if ($('#filter-daterange').val()) {
        var oldTime = $('#filter-daterange').val();
        var time = oldTime.split('-');

        var start = time[0];
        var end = time[1];

        initDateRange(start, end);
    }
});

function initDateRange(start, end) {
    var dateValue = start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD');
    $('#filter-daterange').val(dateValue);
}