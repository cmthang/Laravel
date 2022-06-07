@extends('master')

@section('title') Image Servers @endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css?v=') . $metadata_version }}">
<link rel="stylesheet" href="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css?v=') . $metadata_version}}">
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ asset('/dist/js/imageServers.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <section class="col-lg-12">
        <div id="box-list-data1" class="box box-success">
            {!! Form::open(array('url' => url()->current(), 'method' => 'post', 'id' => 'update-field-form')) !!}
            <div class="box-body">
                <div class="form-group row">
                    <table class="table-image-servers" style="margin-left: 20px;">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Available</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listZone as $item)
                            <tr>
                                <td style="width:50px">
                                    @if ($item['available'] === 0)
                                        {!! Form::checkbox('zones', $item['zone'], $item['available'],['class' => 'checkbox-select-image-server']) !!}
                                    @else
                                        <i style="color: #0175ff;" class="fa fa-check-square-o" aria-hidden="true"></i>
                                    @endif
                                </td>
                                <td style="width:200px">{{$item['zone']}}</td>
                                <td style="width:100px">
                                    @if ($item['available'] === 1)
                                        <i style='color: green;' class="fa fa-circle" aria-hidden="true"></i>
                                    @else
                                        <i class="fa fa-circle-o" aria-hidden="true"></i>
                                    @endif
                                </td>
                                <td>
                                    @if ($item['available'] === 1)
                                        <a class="btn btn-xs btn-danger btn-remove-image-server" data-zone="{{$item['zone']}}" data-id="{{$item['fs_id']}}"><i class="fa fa-trash-o"></i> Delete</a>
                                    @else
                                        <a class="btn btn-xs btn-success btn-add-image-server" data-zone="{{$item['zone']}}">+ Add</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <label for="field-size-storage">Size Storage</label>
                        {!! Form::number('size_storage', 1200, array('id' => 'field-size-storage', 'class' => 'form-control')) !!}
                    </div>
                </div>
                
            </div>
            <div class="box-footer clearfix no-border">
                <a id="btn-add-mutiple-image-server" class="btn btn-primary" value="Thêm mới"><i class="fa fa-plus" aria-hidden="true"></i>Cập nhật</a>
                <a class="btn btn-default" href="{{ route('promotion.link_affiliate.index') }}"><i class="fa fa-undo" aria-hidden="true"></i> Quay lại</a>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
    <section class="col-lg-12">
        <h1 style="font-size: 24px;">Copy Image</h1>
        <div id="box-list-data2" class="box box-success">
            {!! Form::open(array('url' => url()->current(), 'method' => 'post', 'id' => 'update-field-form')) !!}
            <div class="box-body">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-title">Software</label>
                        {!! Form::select('software', $software, '3dsmax', array('id' => 'field-title','data-action' => route('ajax.getSoftwareVersion'), 'class' => 'form-control software')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-title">Software Version</label>
                        {!! Form::select('software_version', $software_version['3dsmax'], 2018, array('id' => 'field-title','data-action' => route('ajax.getEngineVersion'), 'class' => 'form-control software_version')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-title">Engine Version</label>
                        {!! Form::select('engine_version', $engine_version['3dsmax'][2018], 'base', array('id' => 'field-title', 'class' => 'form-control engine_version')) !!}
                    </div>
                    <div style="display:none" class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label for="field-title">Zone Available</label>
                        {!! Form::select('endpoint', $endpoint, 'none', array('id' => 'field-title', 'class' => 'form-control endpoint')) !!}
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix no-border">
                <a type="submit" name="btnadd" id="btn-copy-image-server" data-action="{{route('ajax.copyImageServer')}}" class="btn btn-primary" value="Thêm mới"><i class="fa fa-plus" aria-hidden="true"></i>Cập nhật</a>
                <a class="btn btn-default" href="{{ route('promotion.link_affiliate.index') }}"><i class="fa fa-undo" aria-hidden="true"></i> Quay lại</a>
            </div>
            {!! Form::close() !!}
        </div>
    </section>

    <section class="col-lg-12">
        <h1 style="font-size: 24px;">Region</h1>
        <div id="box-list-data2" class="box box-success">
            <div class="box-body">
                <div class="form-group row">
                    <table class="table-image-servers" style="margin-left: 20px;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($region as $item)
                            <tr>
                                <td style="width:200px">{{$item['name']}}</td>
                                <td style="width:200px">{{$item['status']}}</td>
                                <td>
                                    @if ($item['status'] === 'ready')
                                        <a class="btn btn-xs btn-danger btn-update-status-region" data-region="{{$item['name']}}" data-status="stop" >Stop</a>
                                    @else
                                        <a class="btn btn-xs btn-success btn-update-status-region" data-region="{{$item['name']}}" data-status="start" >Start</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </section>

    <section class="col-lg-12">
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