@extends('master')

@section('title') Payment History @endsection

@section('css')
<link rel="stylesheet" href="{{ secure_asset('/plugins/datepicker/datepicker3.css?v=') . $metadata_version }}">
<link rel="stylesheet" href="{{ secure_asset('/plugins/select2/select2.css?v=') . $metadata_version }}">
@endsection

@section('js')
<script type="text/javascript" src="{{ secure_asset('/plugins/datepicker/bootstrap-datepicker.js') }}?v={{ $metadata_version }}"></script>
<script type="text/javascript" src="{{ secure_asset('/plugins/select2/select2.js') }}?v={{ $metadata_version }}"></script>
<script type="text/javascript" src="{{ secure_asset('/dist/js/payment.js') }}?v={{ $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <section class="col-lg-12">
        <div id="box-list-data" class="box box-success">
            {!! Form::open(array('url' => url()->current(), 'method' => 'get', 'id' => 'track-filter-form')) !!}
            <div class="box-body" style="display: block;">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group" data-toggle="tooltip" data-placement="top" title="User ID">
                                <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                                {!! Form::select('user_id', ['' => 'All'], $queryCondition['user_id'], array('id' => 'filter-id', 'class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group" data-toggle="tooltip" data-placement="top" title="Transaction ID">
                                <span class="input-group-addon"><i class="fa fa-credit-card-alt"></i></span>
                                {!! Form::text('trans_keyword', $queryCondition['trans_keyword'], array('id' => 'filter-trans-id', 'class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group" data-toggle="tooltip" data-placement="top" title="Status">
                                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                {!! Form::select('status', ['' => 'All', 'failed' => 'failed', 'success' => 'success'], $queryCondition['status'], array('id' => 'filter-status', 'class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group" data-toggle="tooltip" data-placement="top" title="Type">
                                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                {!! Form::select('type', ['' => 'All', 'buy_credits' => 'Buy Credits', 'admin_add' => 'Admin Add'], $queryCondition['type'], array('id' => 'filter-type', 'class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer text-center">
                <button id="btn-search" type="submit" class="btn btn-success btn-sm"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                <button id="btn-clear" type="reset" class="btn btn-warning btn-sm" onclick="window.location.href='{{ route('user.payment') }}'"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
            </div>
            {!! Form::close() !!}

            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Transaction ID</th>
                            <th>Purchase Amount</th>
                            <th>Promotion Code</th>
                            <th>Credits</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Transaction Detail</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($paymentHistories as $item)
                        <tr>
                            <td>{{ \App\Utils\Common::convertLocalTimezone($item->created_at, $local_timezone) }}</td>
                            <td>{{ sprintf('%s (%s)', $item->name, $item->email) }}</td>
                            <td>{{ \Illuminate\Support\Arr::get(json_decode($item->transaction_detail, TRUE), 'id') }}</td>
                            <td>${{ number_format(round($item->purchase_amount, 2)) }}</td>
                            <td>{{ $item->promotion_code }}</td>
                            <td>${{ number_format(round($item->credits, 2)) }}</td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->status }}</td>
                            <td>{!! $item->transaction_detail ? '<pre style="max-width: 450px; max-height: 100px;">' . json_encode(json_decode($item->transaction_detail, TRUE), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</pre>' : '' !!}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="box-tools text-center">
                    {{ $paymentHistories->appends($queryCondition)->render() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection