@extends('master')

@section('name') Scene List @endsection
@section('title') Scene List @endsection

@section('css')
@endsection

@section('js')
<script type="text/javascript" src="{{ secure_asset('/dist/js/utm.index.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <section class="col-lg-12">
        <div id="box-list-data" class="box box-success">
            <div class="box-header with-border"></div>

            <div class="box-body">
                <div class="user-detail-block table-responsive">
                    <table id="utm-list" class="table table-bordered table-hover" data-action="{{ route('ajax.utmList') }}" data-order='[[ 0, "desc" ]]' data-page-length='50'>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>IP</th>
                            <th>Country Code</th>
                            <th>Referer Link</th>
                            <th>Created At</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection