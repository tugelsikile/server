<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="{{ asset('theme/adminlte/img/favicon.ico') }}" rel="icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('theme/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/adminlte/css/adminlte.min.css') }}">
    @yield('link')
</head>
<body class="hold-transition text-sm sidebar-mini">

    <div id="main-container" class="wrapper">

    </div>

    <script src="{{ asset('theme/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('theme/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('theme/adminlte/js/adminlte.min.js') }}"></script>
    @yield('script')
</body>
</html>
