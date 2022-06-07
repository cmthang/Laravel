@extends('master')

@section('title') Edit @endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css?v=') . $metadata_version }}">
<link rel="stylesheet" href="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css?v=') . $metadata_version}}">
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ asset('/dist/js/gift.edit.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <section class="col-lg-12">
        <div id="box-list-data" class="box box-success">
            {!! Form::open(array('url' => url()->current(), 'method' => 'post', 'id' => 'update-field-form')) !!}
            <div class="box-body">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <label for="field-title">Code</label>
                        {!! Form::text('code', $linkAffiliate->code, array('id' => 'field-code', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-gift_code">Number of use</label>
                        {!! Form::text('number_of_uses', $linkAffiliate->number_of_uses, array('id' => 'field-number-of-use', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-gift_code">Number of uses per user</label>
                        {!! Form::text('number_of_uses_per_user', $linkAffiliate->number_of_uses_per_user, array('id' => 'field-number-of-uses-per-user', 'class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label>&nbsp;</label>
                        <div class="checkbox-toggle">
                            {!! Form::checkbox('status', 1, $linkAffiliate->status, ['id' => 'field-active']); !!}
                            <label for="field-active">Active/Inactive</label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label for="field-value">Value of root user</label>
                                {!! Form::text('user_root_value', $linkAffiliate->user_root_value, array('id' => 'field-value-root', 'class' => 'form-control')) !!}
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label for="field-value">Value of user use link</label>
                                {!! Form::text('user_use_aff_value', $linkAffiliate->user_use_aff_value, array('id' => 'field-value-ulink', 'class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label for="field-note">Note</label>
                        {!! Form::textarea('note', $linkAffiliate->note, array('id' => 'field-note', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix no-border">
                <button type="submit" name="btnadd" id="btn-update-field" class="btn btn-primary" value="Thêm mới"><i class="fa fa-plus" aria-hidden="true"></i> {{ $linkAffiliate->id ? 'Cập nhật' : 'Thêm mới' }}</button>
                <a class="btn btn-default" href="{{ route('promotion.link_affiliate.index') }}"><i class="fa fa-undo" aria-hidden="true"></i> Quay lại</a>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
</div>
@endsection