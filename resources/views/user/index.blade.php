@extends('layouts.default')

@push('stylesheets')
@endpush

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Security</a></li>
  <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Manajemen User</a></li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
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
          <p><a href="{{route('user.tambah')}}" class="btn btn-success" id="skeyIns"><i class="fa fa-plus"></i> Tambah User - Ins</a></p>
          <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="1%">A</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Nama Karyawan</th>
                <th>Aktif</th>
                <th>Update Terakhir</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <div style="cursor: pointer; margin-top: 10px;" class="hidden">
            <p>
              <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
              <a style="padding:0 5px;" class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Username</a> | 
              <a style="padding:0 5px;" class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Nama</a> | 
              <a style="padding:0 5px;" class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Nama Karyawan</a> | 
              <a style="padding:0 5px;" class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Aktif</a> | 
              <a style="padding:0 5px;" class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> Update Terakhir</a>
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@push('scripts')
  <script type="text/javascript">
    var table, table_index;

    $(document).ready(function(){
      $('a.toggle-vis').on( 'click', function (e) {
          e.preventDefault();
          var col = $(this).attr('data-column');
          var column = table.column(col);
          column.visible( ! column.visible() );
          $('#eye'+col).toggleClass('fa-eye-slash');
      } );

      table = $('#table1').DataTable({
          dom        : 'lrtp',//lrtip -> lrtp
          serverSide : true,
          stateSave  : true,
          deferRender: true,
          select: {style:'single'},
          keys: {keys: [38,40]},
          ajax       : {
              url : '{{ route("user.data") }}',
          },
          order   : [[ 1, 'asc' ]],
          scrollY : "33vh",
          scrollX : true,
          scroller: {
              loadingIndicator: true
          },
          stateLoadParams: function (settings, data) {
              for (var i = 0; i < data.columns.length; i++) {
                  data.columns[i].search.search = "";
              }
          },
          columns: [
              {"data": "action", "orderable": false,},
              {"data": "username"},
              {"data": "name"},
              {"data": "namakaryawan"},
              {"data": "active"},
              {"data": "updated_at"},
          ]
      });
    });

    function hapus(e) {
      @can('user.hapus')
      var data = table.row($(e).parents('tr')).data();
      swal({
        title: "Apakah Anda yakin?",
        text : "Data akan terhapus!",
        type : "warning",
        showCancelButton  : true,
        confirmButtonClass: "btn-danger",
        confirmButtonText : "Ya, hapus data!",
        confirmButtonColor: "#ec6c62",
        closeOnConfirm    : false
      },
      function(){
        $.ajax({
          type   : 'POST',
          url    : '{{route("user.hapus")}}',
          data   : {id:data.id,_token:"{{csrf_token()}}"},
          success: function(data){
            if(data){
              swal({
                title: "Sukses",
                text: "Data berhasil dihapus.",
                type: "success"
              },function(){
                table.ajax.reload(null, false);
              });
            }else{
              swal("Ups!", "Terjadi kesalahan pada sistem.", "error");
            }
          },
          error: function(data){
            console.log(data);
            swal("Ups!", "Terjadi kesalahan pada sistem.", "error");
          }
        });
      });
    @else
      swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;
    @endcan
    }
  </script>
@endpush
