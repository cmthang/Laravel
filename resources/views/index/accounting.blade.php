@extends('master')

@section('title') Accounting @endsection

@section('css')
<link rel="stylesheet" href="{{ secure_asset('/plugins/datepicker/datepicker3.css?v=') . $metadata_version }}">
@endsection

@section('js')
<script type="text/javascript" src="{{ secure_asset('/plugins/datepicker/bootstrap-datepicker.js') }}?v={{ $metadata_version }}"></script>
<script type="text/javascript" src="{{ secure_asset('/dist/js/accounting.index.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <section class="col-lg-12">
        <div id="box-list-data" class="box box-success">
            <div class="box-header with-border">
                {!! Form::open(array('url' => url()->current(), 'method' => 'get', 'id' => 'accounting-filter-form', 'class' => 'form-inline')) !!}
                <div class="form-group">
                    <label class="control-label" for="">Month</label>
                    <div class="input-group input-group-sm">
                        <input type="text" readonly="readonly" name="month" value="{{ $month }}" id="change-month-accounting" class="form-control">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success">Filter</button>
                    <button id="export-accounting" class="btn btn-sm btn-success">Export</button>
                    <b>Total Amount:</b> ${{ \App\Utils\Common::convertCurrency($totalAmount) }}
                    <!-- <b>Total Expense:</b> ${{ \App\Utils\Common::convertCurrency($totalExpense) }} -->
                </div>
                {!! Form::close() !!}
            </div>

            <div class="box-body">
                <div class="user-detail-block table-responsive">
                    <table id="user-accounting" class="table table-bordered table-hover" data-action="{{ '/ajax/accounting?month=' . $month }}" data-order='[[ 3, "desc" ]]' data-page-length='25'>
                        <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Email</th>
                            <th>Remain Credits</th>
                            <th>Purchase Amount</th>
                            <th>Total Credits</th>
                            <th>Expense</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection