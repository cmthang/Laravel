@extends('master')

@section('title') Job Details @endsection
@section('sub-title')
<small>#{{ $renderJob->id }}</small>
@endsection

@section('breadcrumb')
<li><a href="{{ route('job.index') }}">Job</a></li>
@endsection

@section('css')
@endsection

@section('js')
<script type="text/javascript" src="{{ secure_asset('/dist/js/job.detail.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <section class="invoice">
        <div class="row job-detail-content">
            <div class="col-sm-3">
                Email: <a href="{{'/user-detail?email='.$userData['email']}}"> {{ $userData['email'] }}</a><br>
                File name: <b> {{ $renderJobParams['job_detail']['filename'] }}</b><br>
                Full path: <b> {{ $renderJobParams['job_detail']['filepath'] . $renderJobParams['job_detail']['filename'] }}</b><br>
                Render by: <b> {{ $renderJobParams['render_by'] }}</b><br>
                {!! \App\Utils\Common::getJobSelectedLayer($renderJob) !!}<br>
                Status: <span class="job-detail-status-wrap">{!! \App\Utils\JobHelper::getJobStatus($renderJob) !!}</span><br>
                Progress: <span class="job-detail-progress-wrap"> {!! \App\Utils\JobHelper::getJobProgress($renderJob) !!}</span>
            </div>
            <div class="col-sm-3">
                Software version: <b> {{ $renderJobParams['software'] }} {{ $renderJobParams['version'] }}</b><br>
                Frame jump: <b> {{ isset($renderJobParams['step_frame']) ? $renderJobParams['step_frame'] : '' }}</b><br>
                Samples: <b> {{ isset($renderJobParams['samples']) ? $renderJobParams['samples'] : '' }}</b><br>
                @if (\App\Utils\Common::checkUserRoleEnv('ROLE_EDIT_JOB_AMOUNT'))
                Change Amount: <a class="btn btn-xs btn-success btn-change-amount-job"><i class="fa fa-plus"></i></a><br>
                <div class="row update-amount-job-wrap" style="display: none;">
                    <div class="col-sm-6">
                        <div class="form-group-sm">
                            <input type="number" id="input-job-amount" class="form-control" placeholder="Amount">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <button type="button" id="btn-save-amount-job" class="btn btn-sm btn-success" data-action="{{ route('ajax.job.amount', ['id' => $renderJob->id]) }}">Save</button>
                    </div>
                </div>
                Update time render: <a class="btn btn-xs btn-success btn-update-time-render-job"><i class="fa fa-plus"></i></a><br>
                <div class="row update-time-render-job-wrap" style="display: none;">
                    <div class="col-sm-6">
                        <div class="form-group-sm">
                            <input type="number" id="input-time-render-job" class="form-control" placeholder="time">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <button type="button" id="btn-save-time-render-job" class="btn btn-sm btn-success" data-action="{{ route('ajax.job.timeRender', ['id' => $renderJob->id]) }}">Save</button>
                    </div>
                </div>
                Update machine type: {{$renderJobParams['job_detail']['job_package_type']}} <a class="btn btn-xs btn-success btn-update-machine-type"><i class="fa fa-plus"></i></a><br>
                <div class="row update-machine-type-wrap" style="display: none;">
                    <div class="col-sm-6">
                        <div class="form-group-sm">
                            <input type="text" id="input-job-package-type" class="form-control" placeholder="machine type">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <button type="button" id="btn-save-machine-type" class="btn btn-sm btn-success" data-action="{{ route('ajax.updateJobMachineType', ['id' => $renderJob->id]) }}">Save</button>
                    </div>
                </div>
                @endif
                <b>Force sync output:</b> <a class="btn-success btn-force-sync-output" data-id="{{$renderJob->id}}" data-email="{{$userData['email']}}" data-action="{{ route('ajax.forceSyncUserOutput')}}"><i class="fa fa-refresh"></i></a><br><br>
            </div>
            <div class="col-sm-3">
                Render engine: <b> {{ isset($renderJobParams['engine']) ? $renderJobParams['engine'] : '' }}</b><br>
                Selected scene: <b> {{ isset($renderJobParams['scene_name']) ? $renderJobParams['scene_name'] : '' }}</b><br>
                Output format: <b>{{ isset($renderJobParams['file_format']) ? $renderJobParams['file_format'] : '' }}</b><br>
                Enable Camera: <b>{{ isset($renderJobParams['job_detail']['enable_custom_camera']) ? $renderJobParams['job_detail']['enable_custom_camera'] : '' }}</b><br>
                Camera: <b>{{ isset($renderJobParams['camera']) ? $renderJobParams['camera'] : '' }}</b><br>
            </div>
            <div class="col-sm-3">
                Frames: <b>{{ $renderJobParams['job_detail']['frames'] }}</b><br>
                Percentage: <b>{{ isset($renderJobParams['percentage']) ? $renderJobParams['percentage'] : '' }}</b><br>
                Resolution (W-H): <b>{{ isset($renderJobParams['width']) ? $renderJobParams['width'] : '' }} - {{ isset($renderJobParams['height']) ? $renderJobParams['height'] : '' }}</b><br>
                <a class="btn btn-xs btn-success btn-browse-output" data-email="{{$userData['email']}}" data-job-id="{{$renderJob->id}}">Browse Output</a>
            </div>

            @if ($renderJob->render_preview)
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6">
                        Cost Estimation: <b>${{ $renderJob->cost_estimation }}</b><br>
                        Estimated Time: <b>{{ $renderJob->time_estimation }}</b><br>
                        Total Frame: <b>{{ $renderJobParams['total_frame'] }}</b>
                    </div>
                    <div class="col-sm-6">
                        Preview Image: <img src="{{ $renderJob->preview_key }}" class="img-responsive" />
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div style="padding-left: 15px;" class="row">Cost: <b>${{ round($renderJob->cost, 2) }}</b> <a class="btn btn-xs btn-success btn-update-job-cost"><i class="fa fa-plus"></i></a><br>
                @include('job._update_job_cost')</div>
        <br>
        Params User Submit
        <pre>{{json_encode(json_decode($renderJob->param_user_submit, TRUE), JSON_PRETTY_PRINT)}}</pre>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8">{!! \App\Utils\Common::getRenderTaskStt($renderTaskStt) !!}</div>
            <div class="col-xs-12 table-responsive">
                <table id="task-render-detail-list" class="table table-striped table-hover" data-order='[[ 0, "asc" ]]' data-page-length="25">
                    <thead>
                    <tr>
                        <th>Task ID</th>
                        <th>Frame</th>
                        <th>Error</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Start Time</th>
                        <th>Completed Time</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($renderTaskDetails)
                        @foreach ($renderTaskDetails as $item)
                            <tr>
                                <td>{{ $item['task_id'] }}</td>
                                <td>{{ $item['frames'] }}</td>
                                <td>{{ $item['error'] }}</td>
                                <td>{{ $item['status'] }}</td>
                                <td>{{ $item['progress'] }}</td>
                                <td>{{ \App\Utils\Common::convertLocalTimezone($item['time_start'], $local_timezone) }}</td>
                                <td>{{ \App\Utils\Common::convertLocalTimezone($item['time_complete'], $local_timezone) }}</td>
                                <td><a href="javascript:void(0)" class="btn btn-xs btn-success btn-get-job-detail-log" data-action="{{ route('ajax.getLogTask', ['id' => $renderJob->id, 'email' => $renderJobParams['user_email'], 'taskId' => $item['task_id']]) }}">Get Log</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@include('job._modal_get_log_task')
@include('job._modal_browse_output')
@endsection
