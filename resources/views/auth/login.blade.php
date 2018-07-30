<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name')}}</title>

    <!-- Bootstrap -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('assets/css/nprogress.css') }}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{ asset('assets/css/gentelella.min.css') }}" rel="stylesheet">
    <!-- Custom Style -->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    <!-- Plugin -->
    <link href="{{asset('assets/css/sweetalert.css')}}" rel="stylesheet" type="text/css">
 
    <!-- switchery -->
    <link rel="stylesheet" href="{{asset('assets/css/switchery.min.css')}}" />

    <!-- ContextMenu -->
    <link href="{{asset('assets/css/contextMenu.theme.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/jquery.contextMenu.css')}}" rel="stylesheet" type="text/css">
    
    <!-- Custom Theme Style -->
    <link href="{{asset('assets/css/gentelella.min.css')}}" rel="stylesheet" type="text/css">

    <!-- Custom Style -->
    <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/selectize.css')}}" rel="stylesheet" type="text/css">

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

</head>

<body class="login">
<div>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form id="formLogin" method="post" action="{{ url('/login') }}">
                    {!! csrf_field() !!}
                    <h1>SAS</h1>
                    <div class="form-group has-feedback {{ $errors->has('username') ? ' has-error' : '' }}">
                        <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" id="username" autofocus>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        @if ($errors->has('username'))
                            <span class="help-block">
                                  <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        @if ($errors->has('password'))
                            <span class="help-block">
                              <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif

                    </div>
                    <div>
                        <input type="submit" class="btn btn-default submit" value="Log in">
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">

                        <div class="clearfix"></div>
                        <br />

                        <div>
                            <img src="{{asset('assets/img/sas-logo.png')}}" class="img-respomsive" style="height: 72px;">
                            <hr>
                            <p>© 2016 All Rights Reserved.</p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<!-- switchery -->
<script src="{{asset('assets/js/switchery.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('assets/js/select2.full.min.js')}}"></script>
<!-- Sweetalert -->
<script src="{{asset('assets/js/sweetalert.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var searchParams = new URLSearchParams(window.location.search);
        if(searchParams.has('message'))
        {
            swal("Permberitahuan", searchParams.get("message"), "warning");
        }

        $("#formLogin").on("submit", function(){
            $("#username").val($("#username").val().toUpperCase());
        });
    });
</script>
</body>
</html>
