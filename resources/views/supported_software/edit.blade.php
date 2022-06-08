@extends('master')

@section('title')
    Edit
@endsection

@section('css')
    <link rel="stylesheet"
        href="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css?v=') . $metadata_version }}">
@endsection

@section('js')
    <script type="text/javascript"
        src="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
    <div class="row">


        @include('include._error_message')
        <section class="col-lg-12">
            <div id="box-list-data" class="box box-success">

                {!! Form::open(['url' => 'supported-software/' . $support_software->id, 'method' => 'put', 'id' => 'update-field-form']) !!}



                <div class="box-body">
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <label for="field-name">Software</label>
                            {!! Form::text('software', $support_software->software, null, ['id' => 'field-software', 'class' => 'form-control']) !!}
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <label for="field-name"> Label </label>
                            {!! Form::text('lable', $support_software->lable, ['id' => 'field-software', 'class' => 'form-control']) !!}
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <label for="field-name"> Value </label>
                            {!! Form::text('value', $support_software->value, ['id' => 'field-software', 'class' => 'form-control']) !!}
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <label for="field-name"> order_version </label>
                            {!! Form::text('order_version', $support_software->order_version, ['id' => 'field-software', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>


                <div class="box-footer clearfix no-border">
                    <button type="submit" name="btnadd" id="btn-update-field fa-plus" class="btn btn-primary"
                        value="Thêm mới"><i class="fa fa-plus" aria-hideen="true"></i>
                        {{ 'Cập nhật' }}
                    </button>
                    <a class="btn btn-default" href="{{ route('supported-software.index') }}"><i class="fa fa-undo"
                            aria-hidden="true"></i>Quay Lại</a>


                </div>
                {!! Form::close() !!}
            </div>
        </section>
    </div>
@endsection
