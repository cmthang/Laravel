<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>3S Cloud Admin | Login</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link href="{{ secure_asset('/favicon.ico') }}" rel="icon" />
    <link rel="stylesheet" href="{{ secure_asset('/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ secure_asset('/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('/plugins/iCheck/square/blue.css') }}">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="/"><b>3S Cloud</b> Admin</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            @if ($errors->login->first('email'))
                <div class="errors" style="color:#f00">* {{ $errors->login->first('email') }}</div>
            @endif
            @if ($errors->login->first('password'))
                <div class="errors" style="color:#f00">* {{ $errors->login->first('password') }}</div>
            @endif
            @if (session('wronguser'))
                <div class="errors" style="color:#f00">* {{ session('wrong_email') }}</div>
            @endif

            <form method="post" action="{{ str_replace('http://', 'http://', \Request::fullUrl()) }}"> 
            <form method="post" action="{{ str_replace('https://', 'http://', \Request::fullUrl()) }}">
                {{ csrf_field() }}
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                    </div>
                    <div class="col-xs-4">
                        <input type="hidden" id="input-user-timezone" class="form-control" name="timezone" placeholder="Password">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" name="signinsubmit" value="1">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ secure_asset('/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{ secure_asset('/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ secure_asset('/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            $('#input-user-timezone').val(moment().utcOffset() / 60);
        });
    </script>
</body>
</html>
