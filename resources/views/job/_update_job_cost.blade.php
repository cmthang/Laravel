<div class="row update-job-cost-wrap" style="display: none">
    <div class="col-md-6">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group-sm">
                    <input type="number" id="job-cost" class="form-control" placeholder="Amount">
                </div>
            </div>
            <div class="col-sm-8">
                <div class="form-group-sm">
                    <input type="text" id="job-cost-note" class="form-control" placeholder="Note">
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <button type="button" id="btn-submit-update-job-cost" class="btn btn-sm btn-success" data-id="{{$renderJob->id}}" data-action="/ajax/update-job-cost">Save</button>
    </div>
</div>