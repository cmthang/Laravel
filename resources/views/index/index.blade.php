@extends('master')

@section('title') Dashboard @endsection

@section('css')
<link rel="stylesheet" href="{{ secure_asset('/plugins/datepicker/datepicker3.css?v=') . $metadata_version }}">
<link rel="stylesheet" href="{{ secure_asset('/plugins/select2/select2.min.css?v=') . $metadata_version }}">
@endsection

@section('js')
<script type="text/javascript" src="{{ secure_asset('/plugins/datepicker/bootstrap-datepicker.js') }}?v={{ $metadata_version }}"></script>
<script type="text/javascript" src="{{ secure_asset('/plugins/select2/select2.full.min.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ secure_asset('/dist/js/index.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <div class="col-sm-8">
        <div class="row">
            <section class="col-sm-12">
                <div id="box-list-data" class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Job</h3>
                    </div>

                    <div class="box-body table-responsive">
                        @include ('job._list', ['perPage' => 10])
                    </div>

                    <div class="box-footer clearfix text-center"></div>
                </div>
            </section>

            <section class="col-sm-12">
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
        </div>
    </div>

    <section class="col-sm-4">
        <div id="box-list-data" class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Activities</h3>
            </div>

            <div class="box-body table-responsive" id="user_activity_list" role="listitem">
                @include ('ajax.activity')
            </div>

            <div class="box-footer clearfix text-center"></div>
        </div>
    </section>
</div>
@endsection