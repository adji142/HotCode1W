<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{config('app.name')}}</title>

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/sas.ico/apple-icon-57x57.png')}}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/sas.ico/apple-icon-60x60.png')}}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/sas.ico/apple-icon-72x72.png')}}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/sas.ico/apple-icon-76x76.png')}}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/sas.ico/apple-icon-114x114.png')}}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/sas.ico/apple-icon-120x120.png')}}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/sas.ico/apple-icon-144x144.png')}}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/sas.ico/apple-icon-152x152.png')}}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/sas.ico/apple-icon-180x180.png')}}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('assets/sas.ico/android-icon-192x192.png')}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/sas.ico/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/sas.ico/favicon-96x96.png')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/sas.ico/favicon-16x16.png')}}">
        <link rel="manifest" href="{{ asset('assets/sas.ico/manifest.json')}}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('assets/sas.ico/ms-icon-144x144.png')}}">
        <meta name="theme-color" content="#ffffff">

        <!-- Bootstrap -->        
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        
        <!-- Font Awesome -->
        <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

        <!-- Plugin -->
        <link href="{{asset('assets/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/scroller.dataTables.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/select.dataTables.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/sweetalert.css')}}" rel="stylesheet" type="text/css">
        
        <!-- iCheck -->
        <link href="{{asset('assets/css/green.css')}}" rel="stylesheet" type="text/css">
        
        <!-- select2 -->
        <link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" type="text/css">
        
        <!-- switchery -->
        <link rel="stylesheet" href="{{asset('assets/css/switchery.min.css')}}" />

        <!-- ContextMenu -->
        <link href="{{asset('assets/css/contextMenu.theme.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/jquery.contextMenu.css')}}" rel="stylesheet" type="text/css">
        
        <!-- Custom Theme Style -->
        <link href="{{asset('assets/css/gentelella.min.css')}}" rel="stylesheet" type="text/css">

        <!-- Custom Style -->
        <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css">

        @stack('stylesheets')

    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <!-- page content -->
                <div class="right_col" role="main" style="margin-left: 0;">
                    @yield('main_container')
                </div>
                <!-- /page content -->
            </div>
        </div>

        <div id="dataTablesSpinner" style="display: none; background-color:rgba(255, 255, 255, 0.2); position:fixed; width:100%; height:100%; top:0px; left:0px; z-index:2000;">
        {{-- <div id="dataTablesSpinner" style="display: none;"> --}}
            <div style="position: absolute; top: 50%; left: 50%; margin: -26px 0 0 -26px; color: #26B99A;">
                <i class='fa fa-spinner fa-spin fa-4x'></i>
            </div>
        </div>


        <!-- jQuery -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <!-- Bootstrap -->
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <!-- Custom Theme Scripts -->
        <script src="{{ asset('assets/js/gentelella.min.js') }}"></script>

        @stack('scripts')
    </body>
</html>