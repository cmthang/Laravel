<div class="row user-add-more-credits-wrap" style="display: none">
    <div class="col-md-10">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group-sm">
                    <input type="number" id="user-credits-amount" class="form-control" placeholder="Amount">
                </div>
            </div>
            <div class="col-sm-8">
                <div class="form-group-sm">
                    <input type="text" id="user-credits-note" class="form-control" placeholder="Note">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="checkbox-toggle">
                    {!! Form::checkbox('active', 1, NULL, ['id' => 'field-add-payment']); !!}
                    <label for="field-add-payment">Add to Payment?</label>
                </div>
            </div>
            <div class="col-sm-8 add-to-payment-info-wrap" style="display: none;">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group-sm">
                            <input type="number" id="purchase-amount" class="form-control" placeholder="Purchase Amount">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group-sm">
                            <input type="text" id="payment-create-at" class="form-control" placeholder="Create At">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="checkbox-toggle">
                            {!! Form::checkbox('active', 1, NULL, ['id' => 'field-notification-to-discord']); !!}
                            <label for="field-notification-to-discord">Notification to discord?</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <button type="button" id="btn-save-user-credits" class="btn btn-sm btn-success" data-action="/ajax/addCredits?user_id={{ $user->id }}">Save</button>
    </div>
</div>