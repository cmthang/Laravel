@extends('master')

@section('title')
    Support Software
@endsection

@section('css')
@endsection

@section('js')
    <script type="text/javascript" src="{{ secure_asset('/dist/js/support_software.index.js?v=') . $metadata_version }}">
    </script>
@endsection

@section('content')
    <div class="row">
        <section class="col-lg-12">
            @include('include._error_message')

            <div id="box-list-data" class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">@yield('title')</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('supported-software.create') }}" class="btn btn-sm btn-flat btn-success"><i
                                class="fa fa-plus"></i> Add</a>
                    </div>
                </div>

                <div class="box-body table-responsive">

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Software</th>
                                <th>Label</th>
                                <th>Value</th>
                                <th>Order_Version</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item['software'] }}</td>
                                <td>{{ $item['lable'] }}</td>
                                <td>{{ $item['value'] }}</td>
                                <td>{{ $item['order_version'] }}</td>
                                <td>
                                    <a href="{{ route('supported-software.edit', ['supported_software' => $item['id']]) }}">Edit</a> <br>
                                    <a href="/supported_software/{{ $item['id'] }}">Delete</a>
                                <td>
                            </tr>
                        @endforeach

                    </table>

                </div>

                <div class="box-footer clearfix text-center"></div>
            </div>
        </section>
    </div>
@endsection
