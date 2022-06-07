<table id="job_list" data-has-role="{{ \App\Utils\Common::checkUserRoleEnv('ROLE_BROWSE_OUTPUT') }}" class="table table-bordered table-hover" data-action="{{ isset($url) ? $url : '/ajax/jobList' }}" data-order='[[ 6, "desc" ]]' data-page-length='{{ isset($perPage) ? $perPage : 25 }}'>
    <thead>
    <tr>
        <th></th>
        <th>Job</th>
        <th>Software</th>
        <th>Render Engine</th>
        <th>Package</th>
        <th>Machine Type</th>
        <th>Submitted at</th>
        <th>Start Render at</th>
        <th>Completed at</th>
        <th>Render By</th>
        <th>Progress</th>
        <th>Status</th>
        <th>Cost</th>
        <th>Updated At</th>
        <th>Region</th>
        <th>Err</th>
        <th></th>
    </tr>
    </thead>
</table>

@include('job._modal_browse_output')