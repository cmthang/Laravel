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
                        <label for="field-title">Title</label>
                        {!! Form::text('title', $promotionGift->title, array('id' => 'field-title', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label for="field-subtitle">Subtitle</label>
                                {!! Form::text('subtitle', $promotionGift->subtitle, array('id' => 'field-subtitle', 'class' => 'form-control')) !!}
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label for="field-type">Type</label>
                                {!! Form::select('type', \App\Utils\Common::getGiftTypes(), $promotionGift->type, array('id' => 'field-type', 'class' => 'form-control')) !!}
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label for="field-promotion_code">Coupon Code</label>
                                {!! Form::text('promotion_code', $promotionGift->promotion_code, array('id' => 'field-promotion_code', 'class' => 'form-control')) !!}
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label for="field-value">Value</label>
                                {!! Form::text('value', $promotionGift->value, array('id' => 'field-value', 'class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <label>&nbsp;</label>
                        <div class="checkbox-toggle">
                            {!! Form::checkbox('active', 1, $promotionGift->active, ['id' => 'field-active']); !!}
                            <label for="field-active">Active/Inactive</label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label for="field-valid_date_from">Validate From</label>
                                <div class="input-group" data-toggle="tooltip" data-placement="top" title="Date Range">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {!! Form::text('valid_date_from', $promotionGift->valid_date_from, array('id' => 'field-valid_date_from', 'class' => 'form-control datepicker')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label for="field-valid_date_to">Validate To</label>
                                <div class="input-group" data-toggle="tooltip" data-placement="top" title="Date Range">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {!! Form::text('valid_date_to', $promotionGift->valid_date_to, array('id' => 'field-valid_date_to', 'class' => 'form-control datepicker')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label for="field-gift_code">Gift Code</label>
                                {!! Form::text('gift_code', $promotionGift->gift_code, array('id' => 'field-gift_code', 'class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <label for="field-condition">Condition</label>
                        {!! Form::select('condition', \App\Utils\Common::getGiftConditions(), $giftConditionSetting['type'], array('id' => 'field-condition', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                        <div id="field-condition-setting-wrap" class="row">
                            @include('promotion.edit_gift_condition', ['type' => $giftConditionSetting['type']])
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label for="field-description">Description</label>
                        {!! Form::textarea('description', $promotionGift->description, array('id' => 'field-description', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix no-border">
                <button type="submit" name="btnadd" id="btn-update-field" class="btn btn-primary" value="Thêm mới"><i class="fa fa-plus" aria-hidden="true"></i> {{ $promotionGift->id ? 'Cập nhật' : 'Thêm mới' }}</button>
                <input type="hidden" id="gift-condition-setting" value="{{ json_encode($giftConditionSetting) }}">
                <a class="btn btn-default" href="{{ route('promotion.gift.index') }}"><i class="fa fa-undo" aria-hidden="true"></i> Quay lại</a>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
</div>
@endsection