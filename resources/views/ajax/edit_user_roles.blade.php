<div class="form-group">
    <label class="control-label">Select Roles</label>
    {!! Form::select('roles', $userRolesArray, $editUser->roles, array('id' => 'select-user-roles', 'class' => 'form-control')) !!}
</div>