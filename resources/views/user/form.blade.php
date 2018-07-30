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
          <h2>{{isset($user) ? 'Ubah User' : 'Tambah User'}}</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          @if(session('status'))
          <div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
              {{ session('status') }}
          </div>
          @endif
          @if(isset($user) && app('Illuminate\Contracts\Auth\Access\Gate')->check('user.ubah'))
          <form id="formUser" action="{{route('user.ubah',$user->id)}}" class="form-horizontal form-label-left" method="post">
          @elseif(!isset($user) && app('Illuminate\Contracts\Auth\Access\Gate')->check('user.tambah'))
          <form id="formUser" action="{{route('user.tambah')}}" class="form-horizontal form-label-left" method="post">
          @else
          <form id="formUser" action="#" class="form-horizontal form-label-left" method="post" novalidate>
          @endif
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Username <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="username" name="username" class="form-control col-md-7 col-xs-12" placeholder="Username" required="required" value="@if(old('username') != '') {{trim(old('username'))}} @else {{isset($user) ? $user->username : ''}} @endif" {{isset($user) ? 'readonly tabindex="-1"' : ''}}>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Pengguna<span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" placeholder="Nama Pengguna" required="required" value="@if(old('name') != '') {{ old('name') }} @else {{isset($user) ? $user->name : ''}} @endif">
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Karyawan<span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="karyawan" name="karyawan" class="form-control col-md-7 col-xs-12" placeholder="Nama Karyawan" value="@if(old('karyawan') != '') {{ old('karyawan') }} @else {{isset($user) && $user->karyawan ? $user->karyawan->namakaryawan : ''}} @endif">
                <input type="hidden" id="karyawanid" name="karyawanid" value="@if(old('karyawanid') != '') {{ old('karyawanid') }} @else {{isset($user) ? $user->karyawanid : ''}} @endif">
              </div>
            </div>
            <?php if (!isset($user)): ?>
              <div class="item form-group">
                <label for="password" class="control-label col-md-3">Password <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="password" type="password" name="password" class="form-control col-md-7 col-xs-12" placeholder="Password" data-validate-length="6,8" required="required">
                </div>
              </div>
              <div class="item form-group">
                <label for="password2" class="control-label col-md-3 col-sm-3 col-xs-12">Konfirmasi Password <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="password2" type="password" name="password2" class="form-control col-md-7 col-xs-12" placeholder="Konfirmasi Password" data-validate-linked="password" required="required">
                </div>
              </div>
            <?php endif; ?>
            <div class="form-group">
              <label for="active" class="control-label col-md-3">Aktif?</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <label>
                  <input type="checkbox" id="active" name="active" class="js-switch" value="1" {{isset($user) && $user->active? 'checked' : ''}} />
                </label>
              </div>
            </div>
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-md-offset-3">
                @if(isset($user) && app('Illuminate\Contracts\Auth\Access\Gate')->check('user.tambah'))
                <button type="submit" class="btn btn-success">Simpan</button>
                @elseif(!isset($user) && app('Illuminate\Contracts\Auth\Access\Gate')->check('user.tambah'))
                <button type="submit" class="btn btn-success">Simpan</button>
                @endif
                <a href="{{route('user.index')}}" class="btn btn-primary">Batal</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).on("ready", function(){
      $("#username").val($("#username").val().trim());
      $("#name").val($("#name").val().trim());
      $("#karyawan").val($("#karyawan").val().trim());
      $("#karyawanid").val($("#karyawanid").val().trim());
    })
    lookupstaff();
    $("#karyawan").on("keyup change", function() {
      if($(this).val() == '') {
        $("#karyawanid").val("");
      }
    });
    $("#formUser").submit(function(){
      $("#username").val($("#username").val().toUpperCase());
      $("#name").val($("#name").val().toUpperCase());
    })
  </script>
@endpush
