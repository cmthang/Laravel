@extends('master')

@section('title') Edit @endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css?v=') . $metadata_version}}">
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ asset('/dist/js/custom_system_env.index.js?v=') . $metadata_version }}"></script>
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
                        <label for="field-name">Name</label>
                        {!! Form::text('name', $system_env->name, array('id' => 'field-name', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-value">Value</label>
                        {!! Form::text('value', $system_env->value, array('id' => 'field-value', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-note">Note</label>
                        {!! Form::text('note', $system_env->note, array('id' => 'field-note', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-type">Type</label>
                        {!! Form::select('type',['string' => 'string','number' => 'number','boolean' => 'boolean'], $system_env->type, array('id' => 'field-type', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix no-border">
                <button type="submit" name="btnadd" id="btn-update-field" class="btn btn-primary" value="Thêm mới"><i class="fa fa-plus" aria-hidden="true"></i> {{ $system_env->id ? 'Cập nhật' : 'Thêm mới' }}</button>
                <a class="btn btn-default" href="{{ route('systemEnv') }}"><i class="fa fa-undo" aria-hidden="true"></i> Quay lại</a>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
</div>
@endsection