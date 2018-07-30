@extends('layouts.default')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Security</a></li>
  <li class="breadcrumb-item"><a href="{{ route('access.index') }}">Manajemen Akses User</a></li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
    <div class="x_panel">
      <div class="x_title">
        <h2>Daftar Sub Cabang</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float: right;">
              <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <table id="datatables" class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Kode Sub Cabang</th>
              <th class="text-center">Nama Sub Cabang</th>
              <th class="text-center">Init Sub Cabang</th>
              <th class="text-center">Aktif?</th>
              <th class="text-center">Update Terakhir</th>
              <th class="text-center">Pilihan</th>
            </tr>
          </thead>
          <tbody>
            @if(isset($subcabang) && count($subcabang)>0)
              @foreach ($subcabang as $value)
                <tr>
                  <td class="text-right">{{$loop->iteration}}.</td>
                  <td>{{$value->kodesubcabang}}</td>
                  <td>{{$value->namasubcabang}}</td>
                  <td>{{$value->initsubcabang}}</td>
                  @if($value->Aktif==1)
                    <td class="text-center text-success"><i class="fa fa-check"></i></td>
                  @else
                    <td class="text-center text-danger"><i class="fa fa-close"></i></td>
                  @endif
                  <td>{{$value->LastUpdatedOn? $value->LastUpdatedOn : $value->CreatedOn}}</td>
                  <td class="text-center">
                    @can('access.ubah')
                    <a href="{{route('access.ubah',$value->id)}}" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Ubah</a>
                    @else
                    <a href="#" class="btn btn-xs btn-warning" onclick="this.blur(); swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;"><i class="fa fa-pencil"></i> Ubah</a>
                    @endcan
                  </td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
    </div>

    </div>
  </div>
@endsection

@push('scripts')
  <script type="text/javascript">
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
