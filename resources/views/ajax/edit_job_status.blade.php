<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Edit status <strong>{{ $job->scene_name . ' - ID: ' . $job->id }}</strong></h4>
</div>

<div class="modal-body">
    <div id="edit-job-message"></div>
    <div class="form-group">
        <label class="control-label" for="select-job-status">Select Status</label>
        {!! Form::select('edit_job_status', $jobStatus, $job->status, array('id' => 'select-job-status', 'class' => 'form-control')) !!}
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-success btn-save-job-status" data-action="{{ route('ajax.job.status', ['id' => $job->id]) }}" data-id="{{ $job->id }}">Save</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>