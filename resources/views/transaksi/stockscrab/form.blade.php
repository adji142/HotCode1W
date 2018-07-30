@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</a></li>
    <li class="breadcrumb-item"><a href="{{ route('scrab.index') }}">Scrab</a></li>
    <li class="breadcrumb-item active">Tambah Data</li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Tambah Scrab</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          @can('scrab.tambah')
          <form id="formTambah" class="form-horizontal form-label-left" method="POST" action="{{route('scrab.tambah')}}">
          @else
          <form class="form-horizontal form-label-left" method="POST" action="#">
          @endcan
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-6 col-xs-12">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglorder">Tgl. Transaksi</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="tgltransaksi" name="tgltransaksi" class="form-control col-md-7 col-xs-12" readonly tabindex="-1">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="noorder">No. Transaksi</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="notransaksi" name="notransaksi" class="form-control col-md-7 col-xs-12" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier">Staff Stock <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" id="karyawan" class="form-control col-md-7 col-xs-12" autofocus placeholder="Staff Stock" autocomplete="off" required="required" value="">
                  <input type="hidden" id="karyawanid" name="karyawanid" value="">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier">Staff Pemeriksa 00 <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" id="pemeriksa" class="form-control col-md-7 col-xs-12" autofocus placeholder="Staff Pemeriksa 00" autocomplete="off" required="required" value="">
                  <input type="hidden" id="pemeriksaid" name="pemeriksaid" value="">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <textarea id="keterangan" name="keterangan" rows="3" class="form-control" placeholder="Keterangan"></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              @can('scrab.tambah')
              <button type="submit" id="btnSimpan" class="btn btn-success">Simpan</button>
              @endcan
              <button type="button" id="btnTambah" class="btn btn-default" data-toggle="modal" data-target="#modalDetail"><i class="fa fa-plus"></i> Tambah Detail</button>
            </div>
          </form>
          <table id="tableDetail" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" width="40%">Barang</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Qty</th>
                <th class="text-center">Keterangan</th>
              </tr>
            </thead>
            <tbody id="tbodyDetail"></tbody>
          </table>
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6">
              <a href="{{route('scrab.index')}}" class="btn btn-primary">Kembali</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- modal tambah detail -->
  <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Tambah Scrab Detail</h4>
        </div>
        <form id="formDetail" class="form-horizontal" method="post">
          <input type="hidden" id="scrabid" value="">
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barangid">Barang <span class="required">*</span></label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" id="barang" class="form-control" placeholder="Barang" autocomplete="off" required="required">
                <input type="hidden" id="barangid">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Kode Barang / Satuan</label>
              <div class="form-group row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="kodebarang" class="form-control" readonly tabindex="-1">
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" id="satuan" class="form-control" readonly tabindex="-1">
                  <input type="hidden" id="jmlgudang" class="form-control" readonly tabindex="-1">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtyorder">Qty <span class="required">*</span></label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="qty" class="form-control" placeholder="Qty" required="required">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangandetail">Keterangan <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id="keterangandetail"  rows="3" class="form-control" placeholder="Keterangan" required="required"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end of modal tambah detail -->
@endsection

@push('scripts')
  <!-- Form Validation -->
  <!-- <script src="{{asset('assets/js/validator.js')}}"></script> -->

  <script type="text/javascript">
    var table1,fokus;
    tipe_search = null;

    {{-- @include('lookupbarang') --}}
    lookupstaff();
    lookupbarang();

    $(document).ready(function(){
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

      $('#modalDetail').on('shown.bs.modal', function () {
        $('#barang').focus();
      });

      $("#karyawanid,#pemeriksaid").on("change",function(){
        if ($('#karyawanid').val()==$('#pemeriksaid').val()) {
          $('#karyawan').focus();
          swal("Ups!", "Staff Stock dan Pemeriksa tidak boleh SAMA!.", "error");
        }
      });

      $('#formTambah').submit(function(e){
        e.preventDefault();
        if ($('#karyawanid').val()=='') {
          $('#karyawan').focus();
          swal("Ups!", "Staff Stock belum dipilih.", "error");
          return false;
        }
        if ($('#pemeriksaid').val()=='') {
          $('#pemeriksa').focus();
          swal("Ups!", "Pemeriksa belum dipilih.", "error");
          return false;
        }
        if ($('#karyawanid').val()==$('#pemeriksaid').val()) {
          $('#karyawan').focus();
          swal("Ups!", "Staff Stock dan Pemeriksa tidak boleh SAMA!.", "error");
          return false;
        }
        $.ajax({
          type: 'POST',
          url: '{{route("scrab.tambah")}}',
          headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
          data: {
    				subcabang   : "{{session('subcabang')}}",
    				karyawanid  : $('#karyawanid').val(),
            pemeriksaid : $('#pemeriksaid').val(),
            keterangan  : $('#keterangan').val(),
    			},
          dataType: "json",
          success: function(data){
            console.log(data.tgltransaksi);
            $('#notransaksi').val(data.notransaksi);
            $('#tgltransaksi').val(data.tgltransaksi);
            $('#btnSimpan').hide();
            $('#karyawan').attr('disabled',true);
            $('#pemeriksa').attr('disabled',true);
            $('#modalDetail #scrabid').val(data.scrabid);
            $('#modalDetail').modal('show');
          },
          error: function(data){
            console.log(data);
          }
        });
      });

      $('#btnTambah').on('click', function(){
        if ($('#scrabid').val()=='') {
          swal("Ups!", "Simpan Scrab terlebih dulu.", "error");
          return false;
        }else {
          return true;
        }
      });

      $('.hitungnetto').on('keyup', function(){
        hitung_netto();
      });

      $('#formDetail').on('submit', function(e){
        e.preventDefault();
        if ($('#qty').val()=='' || $('#qty').val()==0 ) {
          $('#qty').focus();
          swal("Ups!", "Tidak bisa simpan record. Nilai kolom Qty tidak boleh 0/kosong. Silahkan diisi atau batalkan input", "error");
          return false;
        }
        if ($('#keterangandetail').val()=='') {
          $('#keterangandetail').focus();
          swal("Ups!", "Tidak bisa simpan record. Nilai Kolom keterangan harus diisi", "error");
          return false;
        }
        // stok belum ada
        if($('#qty').val() > $('#jmlgudang').val()){
          $('#qty').focus();
          swal("Ups!", "Tidak bisa simpan record. Nilai kolom Qty "+ $('#qty').val() +" lebih besar dari stock gudang "+ $('#jmlgudang').val() +". Hubungi manager Anda ", "error");
          return false;
        }
        //console.log();
        $.ajax({
          type: 'POST',
          url: '{{route("scrabdetail.tambah")}}',
          headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
          data: {
            scrabid         : $('#scrabid').val(),
            stockid         : $('#barangid').val(),
            qty             : $('#qty').val(),
            keterangandetail: $('#keterangandetail').val()
          },
          success: function(data){
            console.log(data);
            table1.destroy();
            var x = '<tr>';
            x += '<td>'+ $('#barang').val() +'</td>';
            x += '<td>'+ $('#satuan').val() +'</td>';
            x += '<td class="text-right">'+ $('#qty').val() + '</td>';
            x += '<td>'+ $('#keterangandetail').val() +'</td>';
            x += '</tr>';

            $('#tbodyDetail').append(x);
            $('#kodebarang').val('');
            $('#barang').val('');
            $('#barangid').val('');
            $('#satuan').val('');
            $('#qty').val('');
            $('#keterangandetail').val('');
            reloadtabledetail();
            $('#barang').focus();
            return false;
          },
          error: function(data){
            console.log(data);
          }
        });
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
    function reloadtabledetail(){
      table1 = $('#tableDetail').DataTable({
        "searching": false,
        "ordering": false,
        "info": false,
        "paging": false,
        responsive: true,
        language: {
          "emptyTable": "Tidak ada detail",
        },
        "columns": [
          { "data": "barang" },
          { "data": "satuan" },
          { "data": "qty" },
          { "data": "keterangandetail" }
        ]
      });
    }
  </script>
@endpush
