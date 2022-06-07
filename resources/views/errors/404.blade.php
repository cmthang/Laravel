<!DOCTYPE html>
<html>
<head>
    <title>{{trans('site.error_explain')}}.</title>
    <meta charset="utf-8" />
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Roboto', sans-serif;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
        }
        .notice {
            font-size: 36px;
            margin-bottom: 40px;
        }
        a{
            text-decoration: none;
        }
        a:hover{
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">404 Không tìm thấy trang.</div>
        <div class="notice">Vui lòng kiểm tra lại, cảm ơn.</div>
        <div class="notice"><a href="{{ route('index') }}">Quay về trang chủ</a>.</div>
    </div>
</div>
</body>
</html>
