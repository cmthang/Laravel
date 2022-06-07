<table id="payment_history" class="table table-bordered table-hover" data-action="{{ isset($url) ? $url : '/ajax/paymentHistory' }}" data-order='[[ 0, "desc" ]]' data-page-length='{{ isset($perPage) ? $perPage : 50 }}'>
    <thead>
    <tr>
        <th>Date</th>
        <th>Transaction ID</th>
        <th>Credits</th>
        <th>Promotion Code</th>
        <th>Status</th>
        <th>Purchase Amount</th>
    </tr>
    </thead>
</table>