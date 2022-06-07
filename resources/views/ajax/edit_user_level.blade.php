<div class="form-group">
    <label class="control-label">Select Level</label>
    {!! Form::select('level', $userLevelArray, $editUser->level, array('id' => 'select-user-level', 'class' => 'form-control')) !!}
</div>