<div class="row user-update-region-wrap" style="display: none">
    <div class="col-md-10">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group-sm">
                    <select id="select-user-region" class="form-control" name="region">
                        <option value="">--</option>
                        <option value="us-east-1">us-east-1</option>
                        <option value="us-east-2">us-east-2</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <button type="button" id="btn-update-region" class="btn btn-sm btn-success" data-action="/ajax/update-user-region/{{ $user->id }}">Save</button>
    </div>
</div>