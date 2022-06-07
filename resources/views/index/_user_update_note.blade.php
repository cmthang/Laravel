<div class="row user-update-note-wrap" style="display: none">
    <div class="col-md-10">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group-sm">
                    <input type="text" id="user-note" class="form-control" placeholder="Note">
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <button type="button" id="btn-update-note" class="btn btn-sm btn-success" data-action="/ajax/update-user-note/{{ $user->id }}">Save</button>
    </div>
</div>