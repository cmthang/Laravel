@extends('master')

@section('title') Job @endsection

@section('css')
<link rel="stylesheet" href="{{ secure_asset('/plugins/select2/select2.min.css?v=') . $metadata_version }}">
<link rel="stylesheet" href="{{ secure_asset('/plugins/daterangepicker/daterangepicker.css?v=') . $metadata_version }}">
@endsection

@section('js')
<script type="text/javascript" src="{{ secure_asset('/plugins/daterangepicker/daterangepicker.js') }}?v={{ $metadata_version }}"></script>
<script type="text/javascript" src="{{ secure_asset('/plugins/select2/select2.full.min.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ secure_asset('/dist/js/job.index.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
    <div class="row">
        @include('include._error_message')

        <section class="col-lg-12">
            <div id="box-list-data" class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">@yield('title')</h3>
                </div>

                @include('job._search')

                <div class="box-body table-responsive">
                    @include ('job._list')
                </div>

                <div class="box-footer clearfix text-center"></div>
            </div>
        </section>
    </div>
@endsection