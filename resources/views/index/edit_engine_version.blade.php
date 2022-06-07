@extends('master')

@section('title') Edit @endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css?v=') . $metadata_version}}">
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ asset('/dist/js/engine_version.index.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')
    <!-- 'software',
    'engine',
    'engine_version',
    'software_version',
    'default_version',
    'selected_vesion' -->
    <section class="col-lg-12">
        <div id="box-list-data" class="box box-success">
            {!! Form::open(array('url' => url()->current(), 
            'method' => 'post', 'id' => 'update-field-form')) !!}
            <div class="box-body">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <label for="field-name">Software</label>
                        {!! Form::text('software', 
                        $engine_version->software, array('id' 
                        => 'field-software', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <label for="field-name">Engine</label>
                        {!! Form::text('engine', $engine_version->engine, array('id' => 'field-engine', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <label for="field-name">List option engine version</label>
                        {!! Form::text('engine_version', $engine_version->engine_version, array('id' => 'field-engine-version', 'class' => 'form-control')) !!}
                        <p>(cú pháp: {'display': 'value',...} )</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <label for="field-name">Software version</label>
                        {!! Form::text('software_version', $engine_version->software_version, array('id' => 'field-software-version', 'class' => 'form-control')) !!}
                        <p>( default: all )</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <label for="field-name">Engine version</label>
                        {!! Form::text('default_version', $engine_version->default_version, array('id' => 'field-default-version', 'class' => 'form-control')) !!}
                        <p>( default: all )</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <label for="field-name">Default selected engine version</label>
                        {!! Form::text('selected_vesion', $engine_version->selected_vesion, array('id' => 'field-selected-vesion', 'class' => 'form-control')) !!}
                        <p>( Nhập value mặc định )</p>
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix no-border">
                <button type="submit" name="btnadd" id="btn-update-field" class="btn btn-primary" value="Thêm mới"><i class="fa fa-plus" aria-hidden="true"></i> {{ $engine_version->id ? 'Cập nhật' : 'Thêm mới' }}</button>
                <a class="btn btn-default" href="{{ route('engineVersion') }}"><i class="fa fa-undo" aria-hidden="true"></i> Quay lại</a>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
</div>
@endsection