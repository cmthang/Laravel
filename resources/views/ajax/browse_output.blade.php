@if (count($listObjects) > 0)
<table class="table table-bordered table-striped">
    @foreach ($listObjects as $item)
    <tr>
        @php($keyTmp = explode('/', $item['Key']))
        <td>{{ str_replace($keyTmp[0], '', $item['Key']) }}</td>
        <td>{{ \App\Utils\Common::convertToReadableSize($item['Size']) }}</td>
        <td><a href="/ajax/downloadOutput?key={{ urlencode($item['Key']) }}&email={{ $keyTmp[0] }}" class="btn-download-output" target="_blank"><i class="fa fa-cloud-download"></i></a></td>
    </tr>
    @endforeach
</table>
    @else
<div class="no-data">No data.</div>
@endif