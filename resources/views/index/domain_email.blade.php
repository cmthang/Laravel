@extends('master')

@section('title') Domain Email @endsection

@section('css')
<link rel="stylesheet" href="{{ secure_asset('/dist/css/user.detail.css?v=') . $metadata_version }}">
@endsection

@section('js')
<script type="text/javascript" src="{{ secure_asset('/dist/js/domain.index.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <section class="col-lg-12">
        <div id="box-list-data" class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Add</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="input-group" data-toggle="tooltip" data-placement="top" title="Add new domain">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                {!! Form::text('domain', '', array('id' => 'input-domain', 'class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary btn-add-while-domain" data-action="{{ '/ajax/add-while-domain' }}" >Add To While List</button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary btn-add-black-domain" data-action="{{ '/ajax/add-black-domain' }}">Add To Black List</button>
                    </div>
                </div>

                <div class="user-detail-block">
                    <a class="user-detail-header" data-ref="">
                        <i class="fa fa-angle-right fa-2x"></i> Black List
                    </a>
                    <div class="user-detail-body table-responsive">
                        <table id="black-domain-list" class="table table-bordered table-hover" data-action="{{ '/ajax/black-domain' }}" data-order='[[ 0, "desc" ]]' data-page-length='50'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="user-detail-block">
                    <a class="user-detail-header" data-ref="">
                        <i class="fa fa-angle-right fa-2x"></i> While List
                    </a>
                    <div class="user-detail-body table-responsive">
                        <table id="while-domain-list" class="table table-bordered table-hover" data-action="{{ '/ajax/while-domain' }}" data-order='[[ 0, "desc" ]]' data-page-length='50'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection