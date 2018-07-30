@extends('layouts.default')

@push('stylesheets')
  <link href="{{asset('assets/css/switchery.min.css')}}" rel="stylesheet">
@endpush

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Security</a></li>
  <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Manajemen User</a></li>
  <li class="breadcrumb-item active">{{isset($user) ? 'Ubah User' : 'Tambah User'}}</li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Ubah Password</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          @if(session('status'))
          <div class="alert alert-{{ session('status')[0] }}">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
              {{ session('status')[1] }}
          </div>
          @endif
          @can('setting.changepassword')
          <form action="{{route('setting.changepassword')}}" class="form-horizontal form-label-left" method="post">
          @else
          <form action="#" class="form-horizontal form-label-left" method="post" novalidate>
          @endcan
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="item form-group {{ $errors->has('now_password') ? ' has-error' : '' }}">
              <label for="now_password" class="control-label col-md-3">Password Sekarang <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="now_password" type="password" name="now_password" value="{{ old('now_password') }}" class="form-control col-md-7 col-xs-12" placeholder="Password Sekarang" data-validate-length="6,8" required="required">
              </div>
              @if ($errors->has('now_password'))
                <span class="help-block"><strong>{{ $errors->first('now_password') }}</strong></span>
              @endif
            </div>
            <div class="item form-group {{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password" class="control-label col-md-3">Password <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="password" type="password" name="password" value="{{ old('password') }}" class="form-control col-md-7 col-xs-12" placeholder="Password" data-validate-length="6,8" required="required">
              </div>
              @if ($errors->has('password'))
                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
              @endif
            </div>
            <div class="item form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
              <label for="password_confirmation" class="control-label col-md-3 col-sm-3 col-xs-12">Konfirmasi Password <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="password_confirmation" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control col-md-7 col-xs-12" placeholder="Konfirmasi Password" data-validate-linked="password" required="required">
              </div>
              @if ($errors->has('password_confirmation'))
                <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
              @endif
            </div>
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-md-offset-3">
                <a href="{{route('user.index')}}" class="btn btn-primary">Batal</a>
                @can('setting.changepassword')
                <button type="submit" class="btn btn-success">Simpan</button>
                @endcan
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection