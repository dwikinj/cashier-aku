<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Dashboard</title>
    <link rel="shortcut icon" href="{{ asset('backend/assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/fontawesome/css/all.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('backend/assets/plugins/datatables/datatables.min.css') }}" /> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.css" />
  

    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/icons/feather/feather.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/toastr/toatr.css')}}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
    
</head>

<body class="nk-body bg-lighter npc-default has-sidebar no-touch nk-nio-theme">
    <div class="main-wrapper">
        @include('backend.body.header')

        @include('backend.body.sidebar')

        <div class="page-wrapper">
            @yield('dashboard')
        </div>
    </div>

    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/apexchart/chart-data.js') }}"></script>
    <script src="{{ asset('backend/assets/js/script.js') }}"></script>
    {{-- <script src="{{ asset('backend/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables/datatables.min.js') }}"></script> --}}
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>

    <script src="{{asset('backend/assets/plugins/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/toastr/toastr.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>

    @stack('scripts')
</body>

</html>
