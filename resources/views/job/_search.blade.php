{!! Form::open(array('url' => url()->current(), 'method' => 'get', 'id' => 'track-filter-form')) !!}
    <div class="box-body" style="display: block;">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group" data-toggle="tooltip" data-placement="top" title="User ID">
                        <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                        {!! Form::text('filter-user-id', '', array('id' => 'filter-user-id', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group" data-toggle="tooltip" data-placement="top" title="Job ID">
                        <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                        {!! Form::text('filter-id', $condition['filter-id'], array('id' => 'filter-id', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group" data-toggle="tooltip" data-placement="top" title="Date Range">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        {!! Form::text('filter-daterange', $condition['filter-daterange'], array('id' => 'filter-daterange', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group" data-toggle="tooltip" data-placement="top" title="Time Zone">
                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        {!! Form::text('filter-time-zone', '', array('id' => 'filter-time-zone', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group" data-toggle="tooltip" data-placement="top" title="Scene Name">
                        <span class="input-group-addon"><i class="fa fa-wrench"></i></span>
                        {!! Form::text('filter-scene-name', $condition['filter-scene-name'], array('id' => 'filter-scene-name', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group" data-toggle="tooltip" data-placement="top" title="Render engine">
                        <span class="input-group-addon"><i class="fa fa-cogs"></i></span>
                        {!! Form::text('filter-render-engine', $condition['filter-render-engine'], array('id' => 'filter-render-engine', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group" data-toggle="tooltip" data-placement="top" title="Software">
                        <span class="input-group-addon"><i class="fa fa-cog"></i></span>
                        {!! Form::text('filter-software', $condition['filter-software'], array('id' => 'filter-software', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group" data-toggle="tooltip" data-placement="top" title="Email">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        {!! Form::text('filter-email', $condition['filter-email'], array('id' => 'filter-email', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group" data-toggle="tooltip" data-placement="top" title="Status">
                        <span class="input-group-addon"><i class="fa fa-check"></i></span>
                        {!! Form::select('filter-status', \App\Utils\Common::getJobStatus(TRUE), $condition['filter-status'], array('id' => 'filter-status', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group" data-toggle="tooltip" data-placement="top" title="Output status">
                        <span class="input-group-addon"><i class="fa fa-indent"></i></span>
                        {!! Form::select('filter-output-status', \App\Utils\Common::getJobOutputStatus(TRUE), $condition['filter-output-status'], array('id' => 'filter-output-status', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box-footer text-center">
        <button id="btn-search" type="submit" class="btn btn-success btn-sm"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
        <button id="btn-clear" type="reset" class="btn btn-warning btn-sm" onclick="window.location.href='{{ route('job.index') }}'"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
        <a id="btn-export-job" data-action="{{route('ajax.exportJobs')}}" class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Export</a>
    </div>
{!! Form::close() !!}