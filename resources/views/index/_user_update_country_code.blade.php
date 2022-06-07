<div class="row user-update-country-code-wrap" style="display: none">
    <div class="col-md-10">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group-sm">
                    <input type="text" id="user-country-code" class="form-control" placeholder="Country code">
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <button type="button" id="btn-update-country-code" class="btn btn-sm btn-success" data-action="/ajax/update-user-country-code/{{ $user->id }}">Save</button>
    </div>
</div>