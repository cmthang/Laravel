@extends('master')

@section('name') Scene Detail @endsection
@section('title') Scene Detail @endsection

@section('css')
@endsection

@section('js')
@endsection

@section('content')
<div class="row">
    <section class="col-lg-12">
        <div id="box-list-data" class="box box-success">
            <div class="box-header with-border"></div>

            <div class="box-body">
                @if ($sceneDetail->status == 'success')
                {!! sprintf('<a href="javascript:void(0)" class="btn btn-sm btn-info btn-view-scene-analyze" data-action="%s" data-email="%s" data-fname="%s" data-fpath="%s"><i class="fa fa-info-circle"></i> Info</a>', route('scene.analyze', ['id' => $sceneDetail->id]), $sceneDetail->user_email, $sceneDetail->file_name, $sceneDetail->file_path) !!}
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <strong>ID:</strong> {{ $sceneDetail->id }} <br>
                        <strong>Email:</strong> {{ $sceneDetail->user_email }} <br>
                        <strong>File Name:</strong> {{ $sceneDetail->file_name }} <br>
                        <strong>File Path:</strong> {{ $sceneDetail->file_path }} <br>
                        <strong>Status:</strong> {{ $sceneDetail->status }} <br>
                    </div>
                    <div class="col-md-6">
                        <strong>Output Folder:</strong> {{ $sceneDetail->output_folder }} <br>
                        <strong>History:</strong>{!! \App\Utils\Common::renderHistoryAnalyze($sceneDetail->history,$sceneDetail->user_email,$sceneDetail->file_name,$sceneDetail->file_path) !!}<br>
                        <strong>Run In Background:</strong> {{ $sceneDetail->run_in_background }} <br>
                        <strong>Project Path:</strong> {{ $sceneDetail->project_path }} <br>
                        <strong>Created At:</strong> {{ \App\Utils\Common::convertLocalTimezone($sceneDetail->created_at, $local_timezone) }} <br>
                        <strong>Completed At:</strong> {{ \App\Utils\Common::convertLocalTimezone($sceneDetail->created_at, $local_timezone) }} <br>
                    </div>
                    <div class="col-md-12">
                        <strong>Scene Info:</strong><br>
                        <pre>{{ json_encode(json_decode($sceneDetail->scene_info, TRUE), JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include('index._modal_scene_analyze')
@endsection