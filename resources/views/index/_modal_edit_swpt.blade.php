<div class="modal fade" id="modalEditSWPT" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Default Machine Type</h4>
            </div>

            <div class="modal-body">
                <input type="text" id="swpt-input" class="form-control" placeholder="default machine type">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-save-swpt" data-action="{{ route('ajax.updateDefaultPackage') }}" >Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>