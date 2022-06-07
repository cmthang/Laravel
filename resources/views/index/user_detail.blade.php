@extends('master')

@section('title') User Detail @endsection

@section('css')
<link rel="stylesheet" href="{{ secure_asset('/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css?v=') . $metadata_version }}">
<link rel="stylesheet" href="{{ secure_asset('/dist/css/user.detail.css?v=') . $metadata_version }}">
@endsection

@section('js')
<script type="text/javascript" src="{{ secure_asset('/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}?v={{ $metadata_version }}"></script>
<script type="text/javascript" src="{{ secure_asset('/dist/js/user.detail.js?v=') . $metadata_version }}"></script>
<script type="text/javascript" src="{{ secure_asset('/dist/js/scene.index.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    @include('include._error_message')

    <section class="col-lg-12">
        <div id="box-list-data" class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Information</h3>
                <div class="box-tools pull-right">
                    {{ \App\Utils\Common::getStatus($user->active) }}
                    @if (\App\Utils\Common::checkUserRoleEnv('ROLE_EDIT_LEVEL'))
                        @if ($user->active == \App\Utils\Constant::STATUS_ACTIVE)
                            <a class="btn btn-xs btn-danger btn-active-user" title="Deactive User" data-action="{{ route('ajax.user.active', ['id' => $user->id, 'type' => 'inactive']) }}"><i class="fa fa-refresh"></i></a>
                        @else
                            <a class="btn btn-xs btn-success btn-active-user" title="Active User" data-action="{{ route('ajax.user.active', ['id' => $user->id, 'type' => 'active']) }}"><i class="fa fa-refresh"></i></a>
                        @endif
                    @endif
                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-lg-6">
                        <b>ID:</b>{{$user->id}}<br>
                        <b>Name:</b> {{ $user->name }}  <br>
                        <b>Email:</b> {{ $user->email }}<br>
                        <b>Roles:</b> <span class="current-user-roles">{{ ($user->roles == 0) ? 'User' : (($user->roles == 2) ? 'Admin' : 'Super Admin') }}</span> @if (\App\Utils\Common::checkUserRoleEnv('ROLE_EDIT_LEVEL'))<a class="btn btn-xs btn-success btn-edit-user-roles" href="javascript:void(0)" data-action="{{ route('ajax.editUserRoles', ['id' => $user->id]) }}"><i class="fa fa-pencil"></i></a> @endif<br><br>
                        <b>Level:</b> <span class="current-user-level">{{ $user->level }}</span> @if (\App\Utils\Common::checkUserRoleEnv('ROLE_EDIT_LEVEL'))<a class="btn btn-xs btn-success btn-edit-user-level" href="javascript:void(0)" data-action="{{ route('ajax.editUserLevel', ['id' => $user->id]) }}"><i class="fa fa-pencil"></i></a> @endif<br><br>
                        <b>Override Level:</b> <span class="current-user-ovr-lv">{{ $user->ovr_lv }}</span> 
                        @if (\App\Utils\Common::checkUserRoleEnv('ROLE_EDIT_LEVEL'))
                        <a class="btn btn-xs btn-success btn-ovr-lv" href="javascript:void(0)" data-action="{{ route('ajax.editUserLevel', ['id' => $user->id]) }}"><i class="fa fa-pencil"></i></a> 
                        @endif<br><br>
                        <div style="display:flex;margin-top:5px">
                            <b>Mark as old user:</b>
                            <div class="checkbox-toggle">
                                {!! Form::checkbox('old_user', 1, $user->old_user, ['id' => 'field-is-old-user','class' => 'btn-update-user-is-old-user','data-action' => route('ajax.user.markOldUser', ['id' => $user->id])]) !!}
                                <label for="field-is-old-user">False || True</label>
                            </div>
                        </div>

                        <div style="display:flex;margin-top:5px">
                            <b>Need image server:</b>
                            <div class="checkbox-toggle">
                                {!! Form::checkbox('need_image_server', 1, $user->need_image_server, ['id' => 'field-need-imgsv','class' => 'btn-update-user-need-image-server','data-action' => route('updateUserStatusColumn', ['id' => $user->id])]) !!}
                                <label for="field-need-imgsv">False || True</label>
                            </div>
                        </div>

                        <b>Credits:</b> ${{ \App\Utils\Common::convertCurrency($user->credits) }} 
                        @if (\App\Utils\Common::checkUserRoleEnv('ROLE_EDIT_LEVEL'))
                            <a class="btn btn-xs btn-success btn-add-more-credits"><i class="fa fa-plus"></i></a> <br>
                            @include('index._user_add_credits')
                        @endif
                        <br>
                        <b>Preview limit:</b> {{ $user->preview_limit }} 
                        @if (\App\Utils\Common::checkUserRoleEnv('ROLE_EDIT_LEVEL'))
                            <a class="btn btn-xs btn-success btn-update-preview-limit"><i class="fa fa-plus"></i></a> <br>
                            @include('index._user_update_preview_limit')
                        @endif
                        <b>Total Purchase:</b> ${{ \App\Utils\Common::convertCurrency($totalPurchase) }} <br>
                        <b>Rendered Jobs:</b> {{ $totalJob }} <br>
                        <b>Spent Credits:</b> ${{ \App\Utils\Common::convertCurrency($totalCost) }} <br>
                        <b>Notify reload uploader tool:</b> <a class="btn-success btn-notify-reload-app" data-action="{{ route('ajax.notifyReloadDesktopApp', ['id' => $user->id]) }}"><i class="fa fa-refresh"></i></a><br><br>
                        <b>Force sync asset:</b> <a class="btn-success btn-force-sync-asset" data-action="{{ route('ajax.forceSyncUserAccess', ['user_email' => $user->email]) }}"><i class="fa fa-refresh"></i></a><br><br>
                    </div>
                    <div class="col-lg-6">
                        <b>Registered at:</b> {{ \App\Utils\Common::convertLocalTimezone($user->created_at, $local_timezone) }}<br>
                        <b>Last activity:</b> N/a<br>
                        <b>Country Code:</b> {{$user->country_code}}
                        @if (\App\Utils\Common::checkUserRoleEnv('ROLE_EDIT_LEVEL'))
                            <a class="btn btn-xs btn-success btn-update-user-country-code"><i class="fa fa-pencil"></i></a> <br>
                            @include('index._user_update_country_code')
                        @endif
                        <b>Send mail get feedback:</b> <a class="{{ 'btn btn-xs '.(($user->send_feedback_email == 0) ? 'btn-warning' : 'btn-success').' btn-send-mail-get-feedback '}}" title="Get Feedback" data-action="{{ route('ajax.user.get-feedback', ['id' => $user->id]) }}"><i class="fa fa-envelope-o"></i></a><br><br>
                        <b>Mark User As Hacker:</b> <a class="{{ 'btn btn-xs '.(($user->hacker == 1) ? 'btn-success' : 'btn-warning').' btn-mark-user-as-hacker '}}" title="Mark User As Hacker" data-action="{{ route('ajax.user.mark-user-as-hacker', ['id' => $user->id]) }}"><i class="{{ 'fa '.(($user->hacker == 0) ? 'fa-toggle-off' : 'fa-toggle-on')}}"></i></a><br>
                        <b>Request more infomation:</b> <a class="{{ 'btn btn-xs '.(($user->request_more_infomation == 1) ? 'btn-success' : 'btn-warning').' btn-request-more-infomation '}}" title="btn-request-more-infomation" data-action="{{ route('ajax.requestMoreInfomation', ['id' => $user->id]) }}"><i class="fa fa-info-circle"></i></a><br>
                        <div style="display:flex;margin-top:5px">
                            <b>Update user multi az:</b>
                            <div class="checkbox-toggle">
                                {!! Form::checkbox('multi_az', 1, $user->multi_az, ['id' => 'field-active','class' => 'btn-update-user-multiaz','data-action' => route('ajax.updateUserMultiAz', ['id' => $user->id])]) !!}
                                <label for="field-active">False || True</label>
                            </div>
                        </div>

                        <div style="display:flex;margin-top:5px">
                            <b>Update auto sync asset:</b>
                            <div class="checkbox-toggle">
                                {!! Form::checkbox('auto_sync_asset', 1, $user->auto_sync_asset, ['id' => 'field-active-auto-sync-asset','class' => 'btn-update-auto-sync-asset','data-action' => route('ajax.updateAutoSyncAsset', ['id' => $user->id])]) !!}
                                <label for="field-active-auto-sync-asset">ON || OFF</label>
                            </div>
                        </div>
                        <div style="display:flex;margin-top:5px">
                            <b>Update user is student:</b>
                            <div class="checkbox-toggle">
                                {!! Form::checkbox('is_student', 1, $user->is_student, ['id' => 'field-is-student','class' => 'btn-update-user-is-student','data-action' => route('ajax.updateUserStudent', ['id' => $user->id])]) !!}
                                <label for="field-is-student">False || True</label>
                            </div>
                        </div>
                        <div style="display:flex;margin-top:5px">
                            <b>Update user download asset:</b>
                            <div class="checkbox-toggle">
                                {!! Form::checkbox('download_dataset', 1, $user->download_dataset, ['id' => 'field-download-dataset','class' => 'btn-update-user-download-dataset','data-action' => route('ajax.updateUserDownloadDataset', ['id' => $user->id])]) !!}
                                <label for="field-download-dataset">False || True</label>
                            </div>
                        </div>
                        <div style="display:flex;margin-top:5px">
                            <b>Enable Chunksize:</b>
                            <div class="checkbox-toggle">
                                {!! Form::checkbox('chunksize', 0, $user->chunksize, ['id' => 'field-enable-chunksize','class' => 'btn-enable-user-chunksize','data-action' => route('ajax.adminUpdateUserConfig', ['id' => $user->id])]) !!}
                                <label for="field-enable-chunksize">False || True</label>
                            </div>
                        </div>
                        @if (\App\Utils\Common::checkUserRoleEnv('ROLE_EDIT_LEVEL') && $user->chunksize == 1)
                        <b>Chunksize value:</b>
                        <div class="row user-update-chunksize-val-wrap">
                            <div class="col-sm-4">
                                <div class="form-group-sm">
                                    <input type="number" id="user-chunksize-val" value="{{$user->chunksize_val}}" class="form-control" placeholder="Chunksize value">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="btn-update-chunksize-val" class="btn btn-sm btn-success" data-action="{{route('ajax.adminUpdateUserConfig', ['id' => $user->id])}}">Save</button>
                            </div>
                        </div>
                        @endif
                        <div style="display:flex;margin-top:5px">
                            <b>Error checking:</b>
                            <div class="checkbox-toggle">
                                {!! Form::checkbox('chunksize', 0, $user->error_checking, ['id' => 'field-enable-error-checking','class' => 'btn-enable-error-checking','data-action' => route('ajax.adminUpdateUserConfig', ['id' => $user->id])]) !!}
                                <label for="field-enable-error-checking">False || True</label>
                            </div>
                        </div>
                        <b>Company:</b> {{ $user->company }} 
                        @if (\App\Utils\Common::checkUserRoleEnv('ROLE_EDIT_LEVEL'))
                            <a class="btn btn-xs btn-success btn-update-user-company"><i class="fa fa-pencil"></i></a> <br>
                            @include('index._user_update_company')
                        @endif
                        <b>Note:</b> {{ $user->note }} 
                        @if (\App\Utils\Common::checkUserRoleEnv('ROLE_EDIT_LEVEL'))
                            <a class="btn btn-xs btn-success btn-update-user-note"><i class="fa fa-pencil"></i></a> <br>
                            @include('index._user_update_note')
                        @endif
                        <b>Region:</b> {{ $user->region }} 
                        @if (\App\Utils\Common::checkUserRoleEnv('ROLE_EDIT_LEVEL'))
                            <a class="btn btn-xs btn-success btn-update-user-region"><i class="fa fa-pencil"></i></a> <br>
                            @include('index._user_update_region')
                        @endif
                    </div>
                </div>
                
                <div class="user-detail-block">
                    <a class="user-detail-header" data-ref="">
                        <i class="fa fa-angle-right fa-2x"></i> Scene Analyze
                    </a>
                    <div class="user-detail-body table-responsive">
                        <table id="scene-list" class="table table-bordered table-hover" data-action="{{ '/ajax/scene?user_email=' . $user->email }}" data-order='[[ 2, "desc" ]]' data-page-length='50'>
                            <thead>
                            <tr>
                                <th>Scene Path</th>
                                <th>Status</th>
                                <th>History</th>
                                <th>Created At</th>
                                <th>Completed At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="user-detail-block">
                    <a class="user-detail-header" data-ref="">
                        <i class="fa fa-angle-down fa-2x"></i> Render Jobs
                    </a>
                    <div class="user-detail-body table-responsive" style="display: block;">
                        @include('job._list', ['url' => '/ajax/jobList?user_id=' . $user->id])
                    </div>
                </div>

                <div class="user-detail-block">
                    <a class="user-detail-header" data-ref="">
                        <i class="fa fa-angle-right fa-2x"></i> Admin added credits
                    </a>
                    <div class="user-detail-body table-responsive">
                        <table id="admin-add-credits-list" class="table table-bordered table-hover" data-action="{{ '/ajax/list_admin_add_credits?user_id=' . $user->id }}" data-page-length='50'>
                            <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Created at</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="user-detail-block">
                    <a class="user-detail-header" data-ref="">
                        <i class="fa fa-angle-right fa-2x"></i> Payments
                    </a>
                    <div class="user-detail-body table-responsive">
                        @include('user._payment_history', ['url' => '/ajax/paymentHistory?user_id=' . $user->id])
                    </div>
                </div>

                <div class="user-detail-block">
                    <a class="user-detail-header" data-ref="">
                        <i class="fa fa-angle-right fa-2x"></i> Activity Log
                    </a>
                    <div class="user-detail-body table-responsive" id="user_activity_list">
                        @include ('ajax.activity', ['condition' => ['user_id' => $user->id], 'image_server' => $image_server])
                    </div>
                </div>
            </div>

{{--            <div class="box-footer clearfix text-center"></div>--}}
        </div>
    </section>
</div>

@include('index._modal_edit_user_level')
@include('index._modal_ovr_lv')
@include('index._modal_edit_user_roles')
@include('index._modal_scene_analyze')
@endsection