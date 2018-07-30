@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Security</a></li>
    <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Manajemen Roles</a></li>
    <li class="breadcrumb-item active">Daftar User</li>
@endsection

@section('main_container')
<div class="">
    <div class="row">
        @if(session('message'))
        <div class="alert alert-{{session('message')['status']}}">
        {{ session('message')['desc'] }}
        </div>
        @endif
        <div class="x_panel">
            <div class="x_title">
                <h2>Ubah : {{ ($dataSet) ? $dataSet->name : ''}}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @can('role.user')
                <form class="form-horizontal form-label-left" method="POST" action="{{ route('role.user',$dataSet->id) }}">
                @else
                <form class="form-horizontal form-label-left" method="POST" action="#">
                @endcan
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Pilih User</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="select2_single form-control" tabindex="-1" name="tambah_user" required>
                                <option></option>
                                @foreach($userObj as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                       <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                            <a href="{{route('role.index')}}" class="btn btn-primary">Batal</a>
                            @can('role.user')
                            <button type="submit" class="btn btn-success">Simpan</button>
                            @endcan
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="x_panel">
            <div class="x_content">
                <table id="datatables" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataSet->user as $row)
                            <tr>
                                <td class="text-right">{{$loop->iteration}}.</td>
                                <td>{{$row->name}}</td>
                                <td class="text-center">
                                    @can('role.user')
                                    <form class="formDelete" action="{{ route('role.user', $dataSet->id) }}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="hapus_user" value="{{ $row->id }}">
                                        <div class="btn-group  btn-group-sm">
                                            <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                                        </div>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection

@push('stylesheets')
  <link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet">
@endpush

@push('scripts')
  <!-- Form Validation -->
  <script src="{{asset('assets/js/select2.full.min.js')}}"></script>
  <script src="https://cdn.datatables.net/t/bs/dt-1.10.11,fh-3.1.1,r-2.0.2/datatables.min.js"></script>
  <script>
    $(".select2_single").select2({
      placeholder: "Pilih User",
      allowClear: true
    });

    $(document).ready(function(){
      $('#datatables').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        language: {
        search: "_INPUT_",
        searchPlaceholder: "Cari data",
        }
      });
    });
  </script>
@endpush