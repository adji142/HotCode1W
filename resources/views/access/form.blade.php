@extends('layouts.default')

@push('stylesheets')
  <link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet">
@endpush

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Security</a></li>
  <li class="breadcrumb-item"><a href="{{ route('access.index') }}">Manajemen Akses User</a></li>
  <li class="breadcrumb-item active">Ubah User</li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
        @if(session('message'))
          <div class="alert alert-{{session('message')['status']}}">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
              {{ session('message')['desc'] }}
          </div>
        @endif
        <div class="x_panel">
          <div class="x_title">
            <h2>Daftar User</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li style="float: right;">
                  <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @can('access.tambah.user')
            <form action="{{route('access.tambah.user')}}" class="form-horizontal form-label-left" method="post">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="subcabang" value="{{ $subcabang->id }}">
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Pilih User</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="select2_single form-control" tabindex="-1" name="user" required>
                    <option></option>
                    @if(isset($user))
                      @foreach($user as $value)
                        <option value="{{$value->id}}">{{$value->name}}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-md-offset-2">
                  <a href="{{route('access.index')}}" class="btn btn-primary">Batal</a>
                  <button type="submit" class="btn btn-success">Simpan</button>
                </div>
              </div>
            </form>
            @endcan            
          </div>
        </div>
        <div class="x_panel">
          <div class="x_content">
            <table id="datatables" class="table table-bordered">
              <thead>
                <tr>
                  <th class="text-center" width="10%">#</th>
                  <th class="text-center">Nama</th>
                  <th class="text-center" width="10%">Pilihan</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($user) && count($user)>0)
                  @foreach ($user as $value)
                    @if($value->hasAkses($subcabang->id))
                      <tr>
                        <td class="text-right">{{$loop->iteration}}.</td>
                        <td>{{$value->name}}</td>
                        <td class="text-center">
                          @can('access.delete.user')
                          <form class="formDelete" action="{{route('access.delete.user', [$subcabang->id,$value->id])}}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                          </form>
                          @else
                          <a href="#" class="btn btn-xs btn-danger" onclick="this.blur(); swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;"><i class="fa fa-trash"></i> Hapus</a>
                          @endcan
                        </td>
                      </tr>
                    @endif
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
  <!-- Form Validation -->
  <script src="{{asset('assets/js/switchery.min.js')}}"></script>
  <script src="{{asset('assets/js/select2.full.min.js')}}"></script>

  <script>
    $(".select2_single").select2({
      placeholder: "Pilih User",
      allowClear: true
    });

    $('.multi.required').on('keyup blur', 'input', function() {f
      validator.checkField.apply($(this).siblings().last()[0]);
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

    @can('access.delete.user')
    $('.formDelete').on('submit', function(e){
      e.preventDefault();
      var sizeToken = $(this).closest('tr').find('[name="_token"]:first').val();
      var action = $(this).attr('action');

      swal({
        title: "Apakah Anda yakin?",
        text: "Hak akses user ini akan dicabut dari subcabang {{$subcabang->namasubcabang}}",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Ya, cabut hak akses!",
        confirmButtonColor: "#ec6c62",
        closeOnConfirm: false
      },
      function(){
        $.ajax({
          type: 'DELETE',
          url: action,
          headers: {'X-CSRF-TOKEN': sizeToken},
          success: function(data){
            swal({
              title: "Berhasil",
              text: "Hak akses user telah dicabut dari subcabang {{$subcabang->namasubcabang}}",
              type: "success"
            },function(){
              window.location.href = data.redirect;
            })
          },
          error: function(data){
            swal("Ups!", "Terjadi kesalahan sistem", "error");
          }
        });
      });
    });
    @endcan
  </script>
@endpush
