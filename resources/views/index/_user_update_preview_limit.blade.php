<div class="row user-update-preview-limit-wrap" style="display: none">
    <div class="col-md-6">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group-sm">
                    <input type="number" id="user-preview-limit" class="form-control" placeholder="Amount">
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <button type="button" id="btn-update-preview-limit" class="btn btn-sm btn-success" data-action="/ajax/update-user-preview-limit/{{ $user->id }}">Save</button>
    </div>
</div>