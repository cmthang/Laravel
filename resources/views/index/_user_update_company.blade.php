<div class="row user-update-company-wrap" style="display: none">
    <div class="col-md-10">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group-sm">
                    <input type="text" id="user-company" class="form-control" placeholder="Company">
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <button type="button" id="btn-update-company" class="btn btn-sm btn-success" data-action="/ajax/update-user-company/{{ $user->id }}">Save</button>
    </div>
</div>