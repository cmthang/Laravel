@extends('master')

@section('title') Edit @endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/plugins/datepicker/datepicker3.css?v=') . $metadata_version }}">
<link rel="stylesheet" href="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css?v=') . $metadata_version}}">
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('/plugins/datepicker/bootstrap-datepicker.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ asset('/dist/js/coupon.edit.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <section class="col-lg-12">
        <div id="box-list-data" class="box box-success">
            {!! Form::open(array('url' => url()->current(), 'method' => 'post', 'id' => 'update-field-form')) !!}
            <div class="box-body">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-code">Code</label>
                        {!! Form::text('code', $promotionCoupon->code, array('id' => 'field-code', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label>&nbsp;</label>
                        <div class="checkbox-toggle">
                            {!! Form::checkbox('status', 1, $promotionCoupon->status, ['id' => 'field-status']); !!}
                            <label for="field-status">Status (Active/Inactive)</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-valid_date_from">From Date</label>
                        <div class="input-group" data-toggle="tooltip" data-placement="top" title="Date Range">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('valid_date_from', $promotionCoupon->valid_date_from, array('id' => 'field-valid_date_from', 'class' => 'form-control datepicker')) !!}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-valid_date_from">To Date</label>
                        <div class="input-group" data-toggle="tooltip" data-placement="top" title="Date Range">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('valid_date_to', $promotionCoupon->valid_date_to, array('id' => 'field-valid_date_to', 'class' => 'form-control datepicker')) !!}
                        </div>
                        <span class="help-block">Để trống là vô thời hạn</span>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-number_of_uses">Max Times</label>
                        {!! Form::number('number_of_uses', $promotionCoupon->number_of_uses, array('id' => 'field-number_of_uses', 'class' => 'form-control')) !!}
                        <span class="help-block">Để trống là ko xét điều kiện này</span>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-number_of_uses_per_user">Max per User</label>
                        {!! Form::number('number_of_uses_per_user', $promotionCoupon->number_of_uses_per_user, array('id' => 'field-number_of_uses_per_user', 'class' => 'form-control')) !!}
                        <span class="help-block">Để trống là ko xét điều kiện này</span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-coupon_type">Coupon Type</label>
                        {!! Form::select('coupon_type', \App\Utils\Common::getPromotionCouponType(), $promotionCoupon->coupon_type, array('id' => 'field-coupon_type', 'class' => 'form-control')) !!}
                        <span class="help-block">Nếu là discount - giảm giá, nếu là Cộng tiền thì là số tiền khách được cộng</span>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-value_type">Value Type</label>
                        {!! Form::select('value_type', \App\Utils\Common::getValueCouponType(), $promotionCoupon->value_type, array('id' => 'field-value_type', 'class' => 'form-control')) !!}
                        <span class="help-block">Nếu là % thì là discount, nếu là amount thì là số tiền được cộng</span>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" id="promotion_value-wrap">
                        <label for="field-promotion_value">Promotion Value</label>
                        {!! Form::number('promotion_value', $promotionCoupon->promotion_value, array('id' => 'field-promotion_value', 'class' => 'form-control')) !!}
                        <span class="help-block">Nếu là % thì nhỏ hơn 100, nếu là amount thì là số tiền được hưởng</span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="checkbox-toggle">
                            {!! Form::checkbox('is_giftCode', 1, $promotionCoupon->is_giftCode, ['id' => 'field-is_giftCode']); !!}
                            <label for="field-is_giftCode">Gift Code?</label>
                        </div>
                    </div>
                </div>
                <div id="gift_code_value-wrap" class="form-group @if (!$promotionCoupon->is_giftCode) hidden @endif">
                    <div class="row gift-value">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label>Gift Value</label>
                            {!! Form::number('gift_value', $promotionCoupon->gift_value, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="checkbox-toggle">
                            {!! Form::checkbox('depend_payment_value', 1, $promotionCoupon->depend_payment_value, ['id' => 'field-depend_payment_value']); !!}
                            <label for="field-depend_payment_value">Depend on Payment Value?</label>
                        </div>
                    </div>
                </div>

                <div id="depend_payment_value-wrap" class="form-group @if (!$promotionCoupon->depend_payment_value) hidden @endif">
                    @php ($dependValueArray = \App\Utils\Common::getPromotionDependValue($promotionCoupon->depend_payment_value))
                    @php ($i = 1)
                    @foreach ($dependValueArray as $item)
                    <div class="row depend_payment_value-item">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label>From</label>
                            {!! Form::number('depend[from][]', $item['from'], array('class' => 'form-control depend_payment_value_from')) !!}
                            <span class="help-block"></span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label>To</label>
                            {!! Form::number('depend[to][]', $item['to'], array('class' => 'form-control depend_payment_value_to')) !!}
                            <span class="help-block"></span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label>Value</label>
                            {!! Form::number('depend[value][]', $item['value'], array('class' => 'form-control depend_payment_value_to')) !!}
                            <span class="help-block">Nếu là % thì nhỏ hơn 100, nếu là amount thì là số tiền được hưởng</span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label">&nbsp;</label><br>
                            @if ($i == 1)
                            <a class="btn btn-success btn-add-more-depend-value"><i class="fa fa-plus"></i></a>
                            @else
                            <a class="btn btn-danger btn-remove-depend-value"><i class="fa fa-trash-o"></i></a>
                            @endif
                        </div>
                    </div>
                        @php ($i++)
                    @endforeach
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label for="field-note">Note</label>
                        {!! Form::textarea('note', $promotionCoupon->note, array('id' => 'field-note', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix no-border">
                <button type="submit" name="btnadd" id="btn-update-field" class="btn btn-primary" value="Thêm mới"><i class="fa fa-plus" aria-hidden="true"></i> {{ $promotionCoupon->id ? 'Cập nhật' : 'Thêm mới' }}</button>
                <a class="btn btn-default" href="{{ route('promotion.coupon.index') }}"><i class="fa fa-undo" aria-hidden="true"></i> Quay lại</a>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
</div>
@endsection