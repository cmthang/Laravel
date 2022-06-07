@extends('master')

@section('title') User @endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/plugins/datepicker/datepicker3.css?v=') . $metadata_version }}">
<link rel="stylesheet" href="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css?v=') . $metadata_version}}">
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('/plugins/datepicker/bootstrap-datepicker.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ secure_asset('/dist/js/export.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
    <div class="row">
        @include('include._error_message')

        <section class="col-lg-12">
            <div id="box-list-data" class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">User</h3>
                </div>

                <div class="box-body table-responsive">
                    @include ('include._user_list')
                </div>

                <div class="box-footer clearfix text-center"></div>
            </div>
        </section>

        <section class="col-lg-12">
            <div id="box-list-data" class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Export</h3>
                </div>

                <div class="box-body">
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label>&nbsp;</label>
                            <div class="checkbox-toggle">
                                {!! Form::checkbox('active', 0, 0, ['id' => 'field-active']); !!}
                                <label for="field-active">Active/Inactive</label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label>&nbsp;</label>
                            <div class="checkbox-toggle">
                                {!! Form::checkbox('hacker', 0, 0, ['id' => 'field-hacker']); !!}
                                <label for="field-hacker">Hacker/Not Hacker</label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label for="field-valid_date_register_from">Register From</label>
                            <div class="input-group" data-toggle="tooltip" data-placement="top" title="Date Range">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {!! Form::text('valid_date_register_from', '', array('id' => 'field-valid_date_register_from', 'class' => 'form-control datepicker')) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label for="field-valid_date_register_to">Register To</label>
                            <div class="input-group" data-toggle="tooltip" data-placement="top" title="Date Range">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {!! Form::text('valid_date_register_to', '', array('id' => 'field-valid_date_register_to', 'class' => 'form-control datepicker')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label for="field-payment_from">Payment from</label>
                            {!! Form::number('total_payment_from', 0, array('id' => 'field-payment_from', 'class' => 'form-control')) !!}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label for="field-payment_to">Payment to</label>
                            {!! Form::number('total_payment_to', 0, array('id' => 'field-payment_to', 'class' => 'form-control')) !!}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label for="field-total_job_from">Total job from</label>
                            {!! Form::number('total_job_from', 0, array('id' => 'field-total_job_from', 'class' => 'form-control')) !!}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label for="field-total_job_to">Total job to</label>
                            {!! Form::number('total_job_to', 0, array('id' => 'field-total_job_to', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label for="field-valid_date_last_activity_from">Last Activity From</label>
                            <div class="input-group" data-toggle="tooltip" data-placement="top" title="Date Range">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {!! Form::text('valid_date_last_activity_from', '', array('id' => 'field-valid_date_last_activity_from', 'class' => 'form-control datepicker')) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label for="field-valid_date_last_activity_to">Last Activity To</label>
                            <div class="input-group" data-toggle="tooltip" data-placement="top" title="Date Range">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {!! Form::text('valid_date_last_activity_to', '', array('id' => 'field-valid_date_last_activity_to', 'class' => 'form-control datepicker')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer clearfix no-border">
                    <a name="btnadd" id="btn-export-user" data-action="{{route('ajax.exportUsers')}}" class="btn btn-primary" value="Thêm mới"><i class="fa fa-plus" aria-hidden="true"></i> Export</a>
                </div>
                <div class="box-body" style="border-top: 1px solid;">
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
                            <label for="field-payment_from">Days</label>
                            {!! Form::number('export_day', 0, array('id' => 'export_day', 'class' => 'form-control')) !!}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style="margin-top: 25px;">
                            <a name="btnadd" href="/ajax/export-users/last-activity?about-days=30" id="btn-export-n-user" class="btn btn-primary" value="Thêm mới"><i class="fa fa-plus" aria-hidden="true"></i>Export</a>
                            <a>Get a list of users who have no activity for about n days</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection