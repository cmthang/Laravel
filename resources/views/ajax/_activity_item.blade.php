<tr data-create-time="{{ $item->created_at }}">
    <td>{!! \App\Utils\JobHelper::buildContentActivity($item,$image_server) !!}</td>
</tr>