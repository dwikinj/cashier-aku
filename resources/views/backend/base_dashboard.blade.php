<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Admin - Dashboard</title>
    <link rel="shortcut icon" href="{{asset('backend/assets/img/favicon.png')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/fontawesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/css/style.css')}}">
</head>

<body class="nk-body bg-lighter npc-default has-sidebar no-touch nk-nio-theme">
    <div class="main-wrapper">
        @include('backend.body.header')

       @include('backend.body.sidebar')

        <div class="page-wrapper">
            @yield('content')
        </div>
    </div>

    <script src="{{asset('backend/assets/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/feather.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/apexchart/apexcharts.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/apexchart/chart-data.js')}}"></script>
    <script src="{{asset('backend/assets/js/script.js')}}"></script>
</body>

</html>