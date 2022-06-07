@if ($result)
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        @php ($i = 1)
        @foreach ($result as $content)
        <li class="{{ $i == 1 ? 'active' : '' }}"><a href="#{{ $content['id'] }}" data-toggle="tab">{{ $content['title'] }}</a></li>
            @php ($i++)
        @endforeach
    </ul>
    <div class="tab-content">
        @php ($i = 1)
        @foreach ($result as $content)
        <div class="tab-pane {{ $i == 1 ? 'active' : '' }}" id="{{ $content['id'] }}">
            {!! $content['text'] !!}
        </div>
            @php ($i++)
        @endforeach
    </div>
</div>
    @else
<div class="no-data">No data.</div>
@endif