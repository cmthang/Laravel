@extends('master')

@section('title')
    Add
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

                {!! Form::open(['url' => 'supported-software', 'method' => 'post', 'id' => 'update-field-form']) !!}

                <div class="box-body">
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <label for="field-name">Software</label>
                            <input class="form-control" type="text" name="software" placeholder="software">
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <label for="field-name"> Label </label>
                            <input class="form-control" type="text" name="lable" placeholder="lable">
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <label for="field-name"> Value </label>
                            <input class="form-control" type="text" name="value" placeholder="value">
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <label for="field-name"> order_version </label>
                            <input class="form-control" type="text" name="order_version" placeholder="order_version">
                        </div>
                    </div>
                </div>


                <div class="box-footer clearfix no-border">
                    <button type="submit" name="btnadd" id="btn-update-field fa-plus" class="btn btn-primary"
                        value="Thêm mới"><i class="fa fa-plus" aria-hideen="true"></i>
                        {{ 'Thêm mới' }}
                    </button>
                    <a class="btn btn-default" href="{{ route('supported-software.index') }}"><i class="fa fa-undo"
                            aria-hidden="true"></i>Quay Lại</a>


                </div>
                {!! Form::close() !!}
            </div>
        </section>
    </div>
@endsection
