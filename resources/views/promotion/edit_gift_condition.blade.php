@if ($type == 'all')
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label for="field-title">Title</label>
        {!! Form::select('conditions[all]', ['true' => 'True', 'false' => 'False'], $giftConditionSetting['value'], array('id' => 'field-title', 'class' => 'form-control')) !!}
    </div>
@elseif ($type == 'user_level')
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label for="field-title">User Level</label>
        {!! Form::select('conditions[user_level]', \App\Utils\Common::getUserLevel(), $giftConditionSetting['value'], array('id' => 'field-title', 'class' => 'form-control')) !!}
    </div>
@elseif ($type == 'user_credits')
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label for="field-title">User Credits</label>
        {!! Form::text('conditions[user_credits]', isset($giftConditionSetting['value']['user_credits']) ? $giftConditionSetting['value']['user_credits'] : '', array('id' => 'field-title', 'class' => 'form-control')) !!}
    </div>
@elseif ($type == 'user_spend_credits')
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label for="field-title">User Spend Credits</label>
        {!! Form::text('conditions[user_spend_credits]', isset($giftConditionSetting['value']['user_spend_credits']) ? $giftConditionSetting['value']['user_spend_credits'] : '', array('id' => 'field-title', 'class' => 'form-control')) !!}
    </div>
@elseif ($type == 'render_job')
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label for="field-title">Amount Render Job</label>
        {!! Form::number('conditions[render_job][amount_render_job]', isset($giftConditionSetting['value']['amount_render_job']) ? $giftConditionSetting['value']['amount_render_job'] : '', array('id' => 'field-title', 'class' => 'form-control')) !!}
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label for="field-title">Status Render Job</label>
        {!! Form::text('conditions[render_job][status_render_job]', isset($giftConditionSetting['value']['status_render_job']) ? $giftConditionSetting['value']['status_render_job'] : '', array('id' => 'field-title', 'class' => 'form-control')) !!}
    </div>
@elseif ($type == 'payment')
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label for="field-title">Amount Payment</label>
        {!! Form::text('conditions[payment][amount_payment]', isset($giftConditionSetting['value']['amount_payment']) ? $giftConditionSetting['value']['amount_render_job'] : '', array('id' => 'field-title', 'class' => 'form-control')) !!}
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label for="field-title">Total Payment Dolar</label>
        {!! Form::text('conditions[payment][total_payment_dolar]', isset($giftConditionSetting['value']['total_payment_dolar']) ? $giftConditionSetting['value']['status_render_job'] : '', array('id' => 'field-title', 'class' => 'form-control')) !!}
    </div>
@endif

@if ($type == 'user_credits' || $type == 'user_spend_credits' || $type == 'render_job' || $type == 'payment')
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label for="field-title">Start Date</label>
        {!! Form::text('conditions[period][start_date]', isset($giftConditionSetting['value']['start_date']) ? $giftConditionSetting['value']['amount_render_job'] : '', array('id' => 'field-title', 'class' => 'form-control period')) !!}
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label for="field-title">End Date</label>
        {!! Form::text('conditions[period][end_date]', isset($giftConditionSetting['value']['end_date']) ? $giftConditionSetting['value']['status_render_job'] : '', array('id' => 'field-title', 'class' => 'form-control')) !!}
    </div>
@endif
