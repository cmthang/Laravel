<table class="table">
    @foreach ($userActivity as $item)
        @include('ajax._activity_item')
    @endforeach
</table>
<div class="dataTables_paginate paging_bootstrap pagination pagination-sm">
    {!! $userActivity->appends(isset($condition) ? $condition : [])->render() !!}
</div>