@extends('master')

@section('title') Link Affiliate @endsection

@section('css')
@endsection

@section('js')
<script type="text/javascript" src="{{ secure_asset('/dist/js/link_affiliate.index.js?v=') . $metadata_version }}"></script>
@endsection

@section('content')
<div class="row">
    <section class="col-lg-12">
        @include('include._error_message')

        <div id="box-list-data" class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('promotion.affiliate_link.add') }}" class="btn btn-sm btn-flat btn-success"><i class="fa fa-plus"></i> Add</a>
                </div>
            </div>

            <div class="box-body table-responsive">
                @include ('promotion._affiliate_link_list')
            </div>

            <div class="box-footer clearfix text-center"></div>
        </div>
    </section>
</div>
@endsection