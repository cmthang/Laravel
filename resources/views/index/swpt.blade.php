@extends('master')

@section('name') Machine Type @endsection
@section('title') Machine Type @endsection

@section('css')
@endsection

@section('js')
<script type="text/javascript" src="{{ secure_asset('/dist/js/swpt.index.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <section class="col-lg-12">
        <div id="box-list-data" class="box box-success">
            <div class="box-header with-border"></div>

            <div class="box-body">
                <div class="user-detail-block table-responsive">
                    <table id="swpt-list" class="table table-bordered table-hover" data-action="{{ route('ajax.ListSoftwarePackageType') }}" data-order='[[ 0, "desc" ]]' data-page-length='50'>
                        <thead>
                        <tr>
                            <th>software</th>
                            <th>engine</th>
                            <th>package_type</th>
                            <th>type</th>
                            <th>default_package</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@include('index._modal_edit_swpt')
@endsection