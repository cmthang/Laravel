@extends('master')

@section('name') Group Discount @endsection
@section('title') Group Discount @endsection

@section('css')
@endsection

@section('js')
<script type="text/javascript" src="{{ secure_asset('/dist/js/group_discount.index.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <section class="col-lg-12">
        <div id="box-list-data" class="box box-success">
            <div class="box-header with-border">
                <a name="btnadd" href="{{route('index.addGroupDiscount')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>'Thêm mới'</a>
            </div>

            <div class="box-body">
                <div class="user-detail-block table-responsive">
                    <table id="group-discount-list" class="table table-bordered table-hover" data-action="{{ route('ajax.getGroupDiscount') }}" data-order='[[ 2, "desc" ]]' data-page-length='50'>
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Discount cpu</th>
                            <th>Discount gpu</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

@include('index._modal_scene_analyze')
@endsection