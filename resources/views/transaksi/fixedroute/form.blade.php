@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('fixed.index') }}">Fixed Route</a></li>
    <li class="breadcrumb-item active">Form Pilih Toko</li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Pilih Toko</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form id="formTambah" class="form-horizontal form-label-left" method="POST" action="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
              <div class="col-md-6 col-xs-6">
               <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="toko">Toko <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" id="toko" class="form-control" placeholder="Toko" autocomplete="off" required>
                    <input type="hidden" id="tokoid" name="tokoid">
                  </div>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-xs-6">
                <div class="form-group">
                  <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tglso">Tgl. Kunjung <span class="required">*</span></label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="tglkunjung" name="tglkunjung" class="tgl form-control" placeholder="Tgl. Kunjung"  data-inputmask="'mask': 'd-m-y'" required readonly tabindex="-1" value="{{$tglkunjung}}">
                     <input type="hidden" id="idkaryawan" name="idkaryawan" class="tgl form-control" placeholder="Id Karyawan"  readonly tabindex="-1" value="{{$idkaryawan}}">
                    <input type="hidden" id="namakaryawan" name="namakaryawan" class="tgl form-control" placeholder="nama Karyawan"  readonly tabindex="-1" value="{{$karyawan->namakaryawan}}">
                  </div>
                </div>
              </div>
            </div>
          </form>
          <table id="tableDetail" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" width="1%">Pilih</th>
                <th class="text-center" width="10%">Nama Toko</th>
                <th class="text-center">Alamat</th>
                <th class="text-center">WillD</th>
                <th class="text-center">Kota</th>
              </tr>
            </thead>
            <tbody id="tbodyDetail"></tbody>
          </table>
          <div class="form-group">
            <div class="ln_solid"></div>
            <div class="col-md-12" align="right">
              @can('fixedroute.tambahrecordkunjungan')
              <button type="button" id="btnSimpan" class="btn btn-success">Simpan</button>
              @endcan
              <a href="{{route('fixed.index')}}" class="btn btn-default">Batal</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal pilih toko -->
  <div class="modal fade" id="modalToko" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Toko</h4>
        </div>
        <form class="form-horizontal" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryBarang">Masukkan kata kunci pencarian</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <input type="text" id="txtQueryToko" class="form-control" placeholder="Nama Toko">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <table class="table table-bordered table-striped tablepilih">
                  <thead>
                    <tr>
                      <th class="text-center">Toko ID</th>
                      <th class="text-center">Nama Toko</th>
                      <th class="text-center">Wilid</th>
                      <th class="text-center">Alamat</th>
                      <th class="text-center">Daerah</th>
                      <th class="text-center">Kota</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyToko" class="tbodySelect">
                    <tr class="kosong">
                      <td colspan="10" class="text-center">Tidak ada detail</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnPilihToko" class="btn btn-primary">Pilih</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end of modal pilih toko -->
@endsection

@push('scripts')
  <script type="text/javascript">
    var table1;

    $(document).ready(function(){
      $(document).ajaxComplete(function() {
        $('input[type=checkbox]').iCheck({
          checkboxClass: 'icheckbox_flat-green',
        });
      });
      $(".tgl").inputmask();
       
      reloadtabledetail();
      $('.modal').on('show.bs.modal', function(event) {
        var idx = $('.modal:visible').length;
        $(this).css('z-index', 1040 + (10 * idx));
      });
      $('.modal').on('shown.bs.modal', function(event) {
        var idx = ($('.modal:visible').length) -1; // raise backdrop after animation.
        $('.modal-backdrop').not('.stacked').css('z-index', 1039 + (10 * idx));
        $('.modal-backdrop').not('.stacked').addClass('stacked');
      });
      $(document).on('hidden.bs.modal', '.modal', function () { //fix modal's scroll
        $('.modal:visible').length && $(document.body).addClass('modal-open');
      });
      $('#modalToko').on('shown.bs.modal', function () {
        $('#txtQueryToko').focus();
      });
      $('#toko').on('keypress', function(e){
        if (e.keyCode == '13') {
          $('#modalToko').modal('show');
          $('#txtQueryToko').val($(this).val());
          search_toko($('#txtQueryToko').val());
          return false;
        }
      }).on('keydown', function(e){
        if (e.keyCode != '9') {
          $('#tokoid').val('');
          $('#alamat').val('');
          $('#kota').val('');
          $('#kecamatan').val('');
          $('#wilid').val('');
          $('#idtoko').val('');
          $('#statustoko').val('');
        }
      });
      $('#txtQueryToko').on('keypress', function(e){
        if (e.keyCode == '13') {
          search_toko($(this).val());
          return false;
        }
      });
      $('#btnPilihToko').on('click', function(){
        pilih_toko();
      });
      $('#modalToko table.tablepilih tbody').on('dblclick', 'tr', function(){
        pilih_toko();
      });
      $('#modalToko').on('keypress', function(e){
        if (e.keyCode == '13') {
          pilih_toko();
        }
      });
      $('.tbodySelect').on('click', 'tr', function(){
        $('.selected').removeClass('selected');
        $(this).addClass("selected");
      });

      $('#btnSimpan').on('click', function(){
        @cannot('fixedroute.tambahrecordkunjungan')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        sync      = true;
        var count = 0;
        var tglkunjung = ($('#tglkunjung').val());
        var idkaryawan = ($('#idkaryawan').val());

        var datadetail = [];
        $('#tableDetail').find('input[type="checkbox"]:checked:not(:disabled)').each(function () {
          count += 1;
          datadetail.push($(this).val());
        });

        //var idtoko    = datadetail;
        if (count > 0) {
          $.ajaxSetup({
            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
          });
          //cek apakah record data > 4 kunjungan pada bulan yang sama;
          $.ajax({
            type: 'POST',
            url: '{{route("fixedroute.tambahrecordkunjungan")}}',
            data: {
              tglkunjung: tglkunjung,
              idkaryawan: idkaryawan,
              idtoko    : datadetail
            },
            success: function(data){
              //jika lebih besar dari 4 tampilkan pesan
              if (data.success) {
                swal('Ups!', data.message ,'error'); 
              }else{
                window.location.href = '{{route("fixed.index")}}';
              }
            }
          });
        }
        else {
          swal('Ups!', 'Belum pilih toko yang akan dikunjungi,silahkan pilih toko terlebih dahulu.','error');
        }
        @endcannot
      });

      $('#tableDetail tbody').on('click', 'td a.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table1.row( tr );

        if ( row.child.isShown() ) {
          // This row is already open - close it
          row.child.hide();
          tr.removeClass('shown');
        }
        else {
          // Open this row
          row.child( format(row.data()) ).show();
          tr.addClass('shown');
        }
      });
    });

    function search_toko(query){
      $.ajax({
        type: 'GET',
        url: '{{route("lookup.gettoko",null)}}/' + query,
        success: function(data){
          var toko = JSON.parse(data);
          $('#tbodyToko tr').remove();
          var x = '';
          if (toko.length > 0) {
            for (var i = 0; i < toko.length; i++) {
              x += '<tr>';
              x += '<td>'+ toko[i].id +'</td>';
              x += '<td>'+ toko[i].namatoko + '<input type="hidden" class="idtoko" value="'+ toko[i].id +'"></td>';
              x += '<td>'+ toko[i].customwilayah +'</td>';
              x += '<td>'+ toko[i].alamat +'</td>';
              x += '<td>'+ toko[i].kecamatan +'</td>';
              x += '<td>'+ toko[i].kota +'</td>';
              x += '</tr>';
            }
          }else {
            x += '<tr><td colspan="10" class="text-center">Toko tidak ada detail</td></tr>';
          }
          $('#tbodyToko').append(x);
        },
        error: function(data){
          console.log(data);
        }
      });
    }

    function pilih_toko(){
      if ($('#tbodyToko').find('tr.selected td').eq(1).text() == '') {
        swal("Ups!", "Toko belum dipilih.", "error");
        return false;
      }else {
        var id = $('#tbodyToko').find('tr.selected td .idtoko').val();
        $('#toko').val($('#tbodyToko').find('tr.selected td').eq(1).text());
        $('#tokoid').val(id);
        $('#alamat').val($('#tbodyToko').find('tr.selected td').eq(3).text());

        var tglkunjung   = $('#tglkunjung').val();
        var idkaryawan   = $('#idkaryawan').val();
        var namakaryawan = $('#namakaryawan').val();
        var namatoko     = $('#toko').val();

        $.ajaxSetup({
          headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
        });
        $.ajax({
          type: 'POST',
          url : '{{route('fixedroutedetailkunjungan.cektanggalkunjungantoko')}}',
          data: { 
              tglkunjung: tglkunjung, 
              idtoko    : id,
              _token    : "{{ csrf_token() }}"},
          dataType: "json",
          success : function(data){
            if(data.success){
              $.ajax({
                type: 'POST',
                url : '{{route("fixedroute.cektokohakakses")}}',
                data: {
                    id: id,
                    tglkunjung   : tglkunjung,
                    idkaryawan   : idkaryawan,
                    namakaryawan : namakaryawan,
                    namatoko     : namatoko,
                  },
                dataType: "json",
                success: function(data){
                  if (data.success) {
                     //swal("Yes!", "Otomatis check record pada form FRpilihtoko.", "success");
                     table1.destroy();
                     var x = '<tr>';
                      x += '<td><div class="checkbox checkboxaction"><input type="checkbox" class="flat" value='+data.toko.id+' checked></div></td>';
                      x += '<td><input type="text" class="form-control" id="toko" value='+data.toko.namatoko+'> </td>';
                      x += '<td class="text-right">'+data.toko.alamat+'</td>';
                      x += '<td class="text-right">'+data.toko.customwilayah+'</td>';
                      x += '<td class="text-right">'+data.toko.kota+'</td>';
                      x += '</tr>';

                      $('#tbodyDetail').append(x);

                      $('#namatoko').val('');
                      $('#alamat').val('');
                      $('#wilid').val('');
                      $('#kota').val('');
                      reloadtabledetail();
                      $('#toko').focus();
                      return false;
                  }
                  else{
                    swal({
                      type : "warning",
                      title: "Konfirmasi",
                      text : data.message,
                      showCancelButton  : true,
                      closeOnConfirm    : true,
                      showCancelButton  : true,
                      confirmButtonColor: "#DD6B55",
                      confirmButtonText : "Yes!",
                      cancelButtonText  : "No!",
                      closeOnConfirm    : true,
                      closeOnCancel     : true
                    },
                    function(isConfirm){
                      if (isConfirm) {
                       table1.destroy();
                       var x = '<tr>';
                        x += '<td><div class="checkbox checkboxaction"><input type="checkbox" class="flat" value='+data.toko.id+' checked></div></td>';
                        x += '<td>'+data.toko.namatoko+'</td>';
                        x += '<td class="text-right">'+data.toko.alamat+'</td>';
                        x += '<td class="text-right">'+data.toko.customwilayah+'</td>';
                        x += '<td class="text-right">'+data.toko.kota+'</td>';
                        x += '</tr>';

                        $('#tbodyDetail').append(x);

                        $('#namatoko').val('');
                        $('#alamat').val('');
                        $('#wilid').val('');
                        $('#kota').val('');
                        reloadtabledetail();
                        $('#toko').focus();
                        return false; 
                      }
                    });
                  }
                },
                error: function(data){
                  console.log(data);
                }
              });
            }
            else{
              swal("Ups!", "Toko "+data.namatoko+" sudah dikunjungi pada tanggal "+data.tglkunjung+" oleh sales "+data.sales+",tidak bisa buat fixedroute dengan tanggal kunjungan sama ditoko yang sama.", "error"); 
            }
          },
          error: function(data){
            console.log(data);
          }
        });

        $('#modalToko').modal('hide');
      }
    }

    function reloadtabledetail(){
      table1 = $('#tableDetail').DataTable({
        "destroy": true,
        "searching": false,
        "ordering": false,
        "info": false,
        "paging": false,
        responsive: true,
        language: {
          "emptyTable": "Tidak ada detail",
        },
        "columns": [
          { "data": "pilih" },
          { "data": "namatoko" },
          { "data": "alamat" },
          { "data": "wilid" },
          { "data": "kota" },
          // { "data": "action"},
        ]
      });
    }
  </script>
@endpush
