@extends('layouts.default')

@push('stylesheets')
    {{-- <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- jVectorMap -->
    <link href="css/maps/jquery-jvectormap-2.0.3.css" rel="stylesheet"/> --}}
@endpush

@section('main_container')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @if(session('message'))
            <div class="alert alert-{{session('message')['status']}}">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                {{ session('message')['desc'] }}
            </div>
            @endif
        </div>
    </div>
@endsection
