@extends('master')

@section('title')
    Support Software
@endsection

@section('content')
    <div class="row">
        <section class="col-lg-12">
            @include('include._error_message')
            <div id="box-list-data" class="box box-success">
                <div class="box-header with-border">
                    <div class="box-tools pull-right">
                        <a href="{{ route('supported-software.create') }}" class="btn btn-sm btn-flat btn-success">
                            <i class="fa fa-plus"></i> Add</a>
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
                                    <a class="btn btn-sm btn-success btn-edit-ev"
                                        href="{{ route('supported-software.edit', ['supported_software' => $item['id']]) }}"><i
                                            class="fa fa-pencil"></i></a><br>
                                    <form
                                        action="{{ route('supported-software.destroy', ['supported_software' => $item['id']]) }}"
                                        method="POST">
                                        <input type="hidden" name="_method" value="delete" />
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button class="btn btn-sm btn-danger btn-delete-ev" type="submit"><i
                                                class="fa fa-trash"></i></button>
                                    </form>
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
