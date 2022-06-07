@extends('master')

@section('title') Edit @endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css?v=') . $metadata_version }}">
<link rel="stylesheet" href="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css?v=') . $metadata_version}}">
<link rel="stylesheet" href="{{ asset('/plugins/datepicker/datepicker3.css?v=') . $metadata_version }}">
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ asset('/dist/js/group_discount.index.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <section class="col-lg-12">
        <div id="box-list-data" class="box box-success">
            <div class="box-body">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="group_discount_name">Name</label>
                        {!! Form::text('name', $group_discount->name, array('id' => 'group_discount_name', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label>&nbsp;</label>
                        <div class="checkbox-toggle">
                            {!! Form::checkbox('active', 1, $group_discount->active, ['id' => 'group_discount_active']); !!}
                            <label for="group_discount_active">Active/Inactive</label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field_date_from">Validate From</label>
                        <div class="input-group" data-toggle="tooltip" data-placement="top" title="Date Range">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('date_from', $group_discount->date_from, array('id' => 'field_date_from', 'class' => 'form-control datepicker')) !!}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field_date_to">Validate To</label>
                        <div class="input-group" data-toggle="tooltip" data-placement="top" title="Date Range">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('date_to', $group_discount->date_to, array('id' => 'field_date_to', 'class' => 'form-control datepicker')) !!}
                        </div>
                    </div>
                    
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field_discount_cpu">Discount cpu</label>
                        {!! Form::number('discount_cpu', $group_discount->discount_cpu, array('id' => 'field_discount_cpu', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field_discount_gpu">Discount gpu</label>
                        {!! Form::number('discount_gpu', $group_discount->discount_gpu, array('id' => 'field_discount_gpu', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix no-border">
                <button name="btnadd" id="btn-update-group-discount" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> {{ $group_discount->id ? 'Cập nhật' : 'Thêm mới' }}</button>
                <input type="hidden" id="id_group_discount" value="{{ $group_discount->id }}">
                <a class="btn btn-default" href="{{ route('groupDiscount') }}"><i class="fa fa-undo" aria-hidden="true"></i> Quay lại</a>
            </div>
        </div>
    </section>
</div>
@endsection