<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    @if (array_key_exists('name', View::getSections()))
        <title>@yield('name') | {{ config('app.name') }}</title>
    @else
        <title>Admin | {{ config('app.name') }}</title>
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ secure_asset('/favicon.ico') }}?v={{ $metadata_version }}" rel="icon" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ secure_asset('/bootstrap/css/bootstrap.min.css') }}?v={{ $metadata_version }}">
    <link rel="stylesheet" href="{{ secure_asset('/font-awesome/css/font-awesome.min.css') }}?v={{ $metadata_version }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ secure_asset('/dist/css/skins/_all-skins.min.css') }}?v={{ $metadata_version }}">
    <link rel="stylesheet" href="{{ secure_asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}?v={{ $metadata_version }}">
    <link rel="stylesheet" href="{{ secure_asset('/plugins/datatables/dataTables.bootstrap.css') }}?v={{ $metadata_version }}">
    <link rel="stylesheet" href="{{ secure_asset('/dist/css/AdminLTE.css') }}?v={{ $metadata_version }}">
    @yield('css')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue-light sidebar-mini sidebar-collapse">
<div class="wrapper">
    @include('include.header')

    @include('include.sidebar')

    <div class="content-wrapper">
        <div id="pnl-loading" hidden>
            <div class="overlay">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>

        <section class="content-header">
            <h1>
                @yield('title')
                @yield('sub-title')
            </h1>
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                @yield('breadcrumb')
                <li class="active">@yield('title')</li>
            </ol>
        </section>

        <section class="content">
            @yield('content')
        </section>
    </div>

    @include('include._modal_common')
</div>

<script src="{{ secure_asset('/plugins/jQuery/jquery-2.2.3.min.js') }}?v={{ $metadata_version }}"></script>
<script src="{{ secure_asset('/plugins/jQueryUI/jquery-ui.min.js') }}?v={{ $metadata_version }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var app_socket_url = '{{ env('APP_SOCKET_URL') }}';
</script>
<script src="{{ secure_asset('/bootstrap/js/bootstrap.min.js') }}?v={{ $metadata_version }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.dev.js"></script>
<script src="{{ secure_asset('/plugins/sparkline/jquery.sparkline.min.js') }}?v={{ $metadata_version }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{ secure_asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}?v={{ $metadata_version }}"></script>
<script src="{{ secure_asset('/plugins/slimScroll/jquery.slimscroll.min.js') }}?v={{ $metadata_version }}"></script>
<script src="{{ secure_asset('/dist/js/bootstrap-notify.min.js') }}?v={{ $metadata_version }}"></script>
<script src="{{ secure_asset('/dist/js/app.min.js') }}?v={{ $metadata_version }}"></script>
<script src="{{ secure_asset('/plugins/datatables/jquery.dataTables.js') }}?v={{ $metadata_version }}" type="text/javascript"></script>
<script src="{{ secure_asset('/plugins/datatables/dataTables.bootstrap.js') }}?v={{ $metadata_version }}" type="text/javascript"></script>
<script src="{{ secure_asset('/plugins/jszip/jszip.min.js') }}?v={{ $metadata_version }}" type="text/javascript"></script>
<script src="{{ secure_asset('/plugins/jszip/jszip-utils.min.js') }}?v={{ $metadata_version }}" type="text/javascript"></script>
<script src="{{ secure_asset('/dist/js/common.js') }}?v={{ $metadata_version }}"></script>

@yield('js')
</body>
</html>
