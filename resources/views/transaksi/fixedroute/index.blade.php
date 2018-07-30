@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
    <li class="breadcrumb-item"><a href="{{ route('scrab.index') }}">Fixed Route</a></li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Fixed Route</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li style="float: right;">
              <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="row">
            <div class="col-md-6">
              <form class="form-inline">
                <div class="form-group">
                  <label style="margin-right: 10px;">Bulan</label>
                  <select class="select2_single form-control bulan" tabindex="-1" name="bulan" id="bulan">
                      @foreach($bulan as $k => $v)
                          <option value="{{$k}}" {{ $bulanselected == $k ? 'selected' : '' }}>{{$v}}</option>
                      @endforeach
                  </select>
                </div>
              </form>
            </div>
            {{-- <div class="col-md-6 text-right">
              <p>
                <a href="" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Luar Rencana</a>
                <a href="" class="btn btn-primary"><i class="fa fa-print"></i> Cetak</a>
                <a href="" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Per Tanggal</a>
              </p>
            </div> --}}
          </div>
          <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="6%">Action</th>
                <th>Sales ID</th>
                <th>Nama Sales</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <div style="cursor: pointer; margin-top: 10px;" class="hidden">
            <p>
              <span>Toggle column <strong>Hide/Show</strong> :</span>
              <a class="toggle-vis" data-column="1">
                <i id="eye1" class="fa fa-eye"></i>&nbsp;&nbsp;Sales ID
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="2">
                &nbsp;&nbsp;<i id="eye2" class="fa fa-eye"></i>&nbsp;&nbsp;Nama Sales
              </a>
            </p>
          </div>
          {{-- <hr> --}}
          <ul class="nav nav-tabs" role="tablist" id="tableSwitchMbuhOpo">
            <li class="active"><a data-toggle="tab" href="#toko">Berdasar Toko</a></li>
            <li><a data-toggle="tab" href="#tglkunjung">Berdasar Tanggal Kunjung</a></li>
          </ul>

          <div class="tab-content">
            <div role="tabpanel" id="toko" class="tab-pane active">
               <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="1%">A</th>
                    <th width="40%">Nama Toko</th>
                    <th width="10%">Wil ID</th>
                    <th>Kota</th>
                    <th>Tgl.Kunjungan</th>
                    <th>Tgl.Kunjungan</th>
                    <th>Tgl.Kunjungan</th>
                    <th>Tgl.Kunjungan</th>
                    <th>Tgl.Kunjungan</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                <p>
                  <span>Toggle column <strong>Hide/Show</strong> :</span>
                  <a class="toggle-vis-2" data-column="1">
                    <i id="eye-detail1" class="fa fa-eye"></i>&nbsp;&nbsp;Nama Toko
                  </a>
                  &nbsp;&nbsp;|
                  <a class="toggle-vis-2" data-column="2">
                    &nbsp;&nbsp;<i id="eye-detail2" class="fa fa-eye"></i>&nbsp;&nbsp;Wil ID
                  </a>
                  &nbsp;&nbsp;|
                  <a class="toggle-vis-2" data-column="3">
                    &nbsp;&nbsp;<i id="eye-detail3" class="fa fa-eye"></i>&nbsp;&nbsp;Kota
                  </a>
                  
                </p>
              </div>
            </div>
            <div role="tabpanel" id="tglkunjung" class="tab-pane">
              <table id="table3" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="1%">A</th>
                    <th>Tgl Input</th>
                    <th>Tgl Kunjungan</th>
                    <th>Nama Toko</th>
                    <th>WillID</th>
                    <th>Kota</th>
                    <th>Ket.Realisasi</th>
                    <th>Ket.Tambahan</th>
                    <th>Omset Rata2</th>
                    <th>Saldo Piutang</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                <p>
                  <span>Toggle column <strong>Hide/Show</strong> :</span>
                  <a class="toggle-vis-3" data-column="1">
                    <i id="eye-detailkunjungan1" class="fa fa-eye"></i>&nbsp;&nbsp;Tgl Input
                  </a>
                  &nbsp;&nbsp;|
                  <a class="toggle-vis-3" data-column="2">
                    &nbsp;&nbsp;<i id="eye-detailkunjungan2" class="fa fa-eye"></i>&nbsp;&nbsp;Tgl Kunjungan
                  </a>
                  &nbsp;&nbsp;|
                  <a class="toggle-vis-3" data-column="3">
                    &nbsp;&nbsp;<i id="eye-detailkunjungan3" class="fa fa-eye"></i>&nbsp;&nbsp;Nama Toko
                  </a>
                  &nbsp;&nbsp;|
                  <a class="toggle-vis-3" data-column="4">
                    &nbsp;&nbsp;<i id="eye-detailkunjungan4" class="fa fa-eye"></i>&nbsp;&nbsp;WillID
                  </a>
                   &nbsp;&nbsp;|
                  <a class="toggle-vis-3" data-column="5">
                    &nbsp;&nbsp;<i id="eye-detailkunjungan5" class="fa fa-eye"></i>&nbsp;&nbsp;Kota
                  </a>
                  <a class="toggle-vis-3" data-column="6">
                    &nbsp;&nbsp;<i id="eye-detailkunjungan6" class="fa fa-eye"></i>&nbsp;&nbsp;Ket.Realisasi
                  </a>
                  <a class="toggle-vis-3" data-column="7">
                    &nbsp;&nbsp;<i id="eye-detailkunjungan7" class="fa fa-eye"></i>&nbsp;&nbsp;Ket.Tambahan
                  </a>
                  <a class="toggle-vis-3" data-column="8">
                    &nbsp;&nbsp;<i id="eye-detailkunjungan8" class="fa fa-eye"></i>&nbsp;&nbsp;Omset Rata2
                  </a>
                  <a class="toggle-vis-3" data-column="9">
                    &nbsp;&nbsp;<i id="eye-detailkunjungan9" class="fa fa-eye"></i>&nbsp;&nbsp;Saldo Piutang
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Data Fixed Route -->
  <div class="modal fade" id="modalDataFixedRoute" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Data Fixed Route</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-xs-12">
              <div class="table-responsive">
                <table id="FixedRoute" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th class="text-center">Nama Kolom</th>
                      <th class="text-center">Nilai</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyFixedRoute">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  <!-- end of data fixed route -->

  <!-- Data Fixed Route Toko -->
  <div class="modal fade" id="modalDataFixedRouteToko" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Data Fixed Route Toko</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-xs-12">
              <div class="table-responsive">
                <table id="FixedRouteToko" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th class="text-center">Nama Kolom</th>
                      <th class="text-center">Nilai</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyFixedRouteToko">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  <!-- end of Data Fixed Route Toko -->
  <!-- Data Berdasarkan Tanggal Kunjungan -->
  <div class="modal fade" id="modalDataFixedRouteKunjungan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Data Berdasarkan Tanggal Kunjungan</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-xs-12">
              <div class="table-responsive">
                <table id="FixedRouteKunjungan" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th class="text-center">Nama Kolom</th>
                      <th class="text-center">Nilai</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyFixedRouteKunjungan">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  <!-- end of Data Berdasarkan Tanggal Kunjungan -->
  <!-- Data Fixed Route Tambah Tanggal -->
  <div class="modal fade" id="modalTanggal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xs" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalTanggal">#</h4>
        </div>
        <form id="submitTanggal" class="form-horizontal" method="post">
          <input type="hidden" id="namakaryawan" name="namakaryawan" class="form-control">
          <input type="hidden" id="idkaryawan" name="idkaryawan" class="form-control">
          <div class="modal-body">
            <div class="row">
              <div class="col-xs-12">
                  <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                      <input type="text" id="tglkunjung" name="tglkunjung" class="tgl form-control" placeholder="tgl kunjung" data-inputmask="'mask': 'd-m-y'"  value="{{ Carbon\Carbon::now()->format("d-m-Y") }}">
                      <input type="hidden" id="tglserver" name="tglserver" class="form-control"  value="{{ Carbon\Carbon::now()->format("d-m-Y") }}">
                    </div>
                  </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success" id="">Yes</button>
          </div>
      </form>
      </div>
    </div>
  </div>
  <!-- end of Data Fixed Route tambah tanggal -->
  <!-- Data Fixed Route Tambah Tanggal -->
  <div class="modal fade" id="modalInputLuarRencana" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xs" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalInputLuarRencana">#</h4>
        </div>
        <form id="submitLuarRencana" class="form-horizontal" method="post">
          <input type="hidden" id="namakaryawan" name="namakaryawan" class="form-control">
          <input type="hidden" id="idkaryawan" name="idkaryawan" class="form-control">
          <div class="modal-body">
            <div class="row">
              <div class="col-xs-12">
                  <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                         <input type="text" id="tglkunjung" name="tglkunjung" class="tgl form-control search" placeholder="tgl kunjung" data-inputmask="'mask': 'd-m-y'"  value="{{date('d-m-Y')}}">
                          <input type="hidden" id="tglserver" name="tglserver" class="tgl form-control search" placeholder="tgl Server" data-inputmask="'mask': 'd-m-y'" value="{{date('d-m-y')}}">
                    </div>
                  </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success" id="">Yes</button>
          </div>
      </form>
      </div>
    </div>
  </div>
  <!-- end of Data Fixed Route tambah tanggal -->
  <!-- modal update -->
  <div class="modal fade" id="modalUpdateTanggalKunjungan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Fixed Route - Edit Data Berdasarkan Tanggal Kunjungan </h4>
        </div>
        <form class="form-horizontal" id="formUpdateKunjungan" method="post">
          <input type="hidden" id="idkunjungan" value="">
          <input type="hidden" id="tipe" value="">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="namatoko">Toko</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="namatoko" class="form-control" readonly tabindex="-1">
                    </div>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="namasales">Nama Sales</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="namasales" class="form-control" readonly tabindex="-1">
                    </div>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="alamat" class="form-control" readonly tabindex="-1">
                    </div>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kodesales">Kode Sales</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="kodesales" class="form-control" readonly tabindex="-1">
                    </div>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kota">Kota</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="kota" class="form-control" readonly tabindex="-1">
                    </div>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglkunjungan">Tanggal Kunjungan </label>
                  <div class="col-md-7 col-sm-7 col-xs-12">
                     <input type="text" id="tglkunjung" name="tglkunjung" class="tgl form-control search" placeholder="tgl kunjung" data-inputmask="'mask': 'd-m-y'">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="realisasi">Realisasi</label>
                  <div class="col-md-7 col-sm-7 col-xs-12">
                    <select class="form-control" name="realisasi" id="realisasi" required="">
                      <option value="">PILIH REALISASI</option>
                      @foreach($realisasi as $r)
                       <option value="{{$r->realisasi}}">{{$r->realisasi}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kettambahan">Keterangan Tambahan </label>
                  <div class="col-md-7 col-sm-7 col-xs-12">
                    <input type="text" id="kettambahan" class="form-control" tabindex="-1">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" id="" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end of modal update -->
  
@endsection

@push('scripts')
<!-- Select2 -->
<script>
  $(document).ready(function() {
      $(".select2_tipe").select2({
        placeholder: "Pilih tipe",
         width: '100px',
        allowClear: true
      });
      $(".select2_filter").select2({
        placeholder: "Pilih filter",
        width: '100px',
        allowClear: true
      });

      $(".select2_multiple").select2({
          maximumSelectionLength: 3,
          placeholder: "With Max Selection limit 3",
          allowClear: true
        });
  });
</script>
<!-- /Select2 -->
<script type="text/javascript">
  var table1;
  var tipe_edit   = null;
  var ids     = null;
  var index   = null;
  var context_menu_number_state = 'hide';
  var context_menu_text_state = 'hide';
  var last_index = '';
  var sync = '';
  var custom_search = [
    {
      text   : '',
      filter : '='
    },
    {
      text   : '',
      filter : '='
    },
    {
      text   : '',
      filter : '='
    },
  ];
  var filter_number = ['<=','<','=','>','>='];
  var filter_text = ['=','!='];
  var tipe = ['Find','Filter'];
  var column_index = 0;
  var table,
  table2,
  table3,
  bulanku,
  namasales,
  kodesales,
  table_index,
  table2_index,
  table3_index,
  fokus,
  tabledatafixedroute,
  tabledatafixedroutetoko,
  tabledatafixedroutekunjungan;

  $(document).ready(function() {
    bulanku= $('#bulan').val();
    $(".tgl").inputmask();
    // reloadtabledetail();
    //  reloadtabledetailluarrencana();
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
    $('#tableSwitchMbuhOpo').on('shown.bs.tab', function () {
      table2.columns.adjust().draw();
      table3.columns.adjust().draw();
    });
   
    $('#tokoku').on('keypress', function(e){
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
      $('#modalToko').on('keypress', function(e){
        if (e.keyCode == '13') {
          pilih_toko();
        }
      });
        $('.tbodySelect').on('click', 'tr', function(){
        $('.selected').removeClass('selected');
        $(this).addClass("selected");
      });
    table = $('#table1').DataTable({
      dom        : 'lrtp',//lrtip -> lrtp
      serverSide : true,
      stateSave  : true,
      deferRender: true,
      select: {style:'single'},
      keys: {keys: [38,40]},
      ajax : {
        url: '{{ route("fixed.data") }}',
        data: function ( d ) {
          d.custom_search = custom_search;
          d.tipe_edit     = tipe_edit;
        }
      },
      scrollY : 130,
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
        {
          "data" : "edit",
          "orderable" : false,
          render : function(data, type, row) {
              // return "<div class='btn btn-success btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Tambah Per Tanggal' onclick='tambahTanggal(this,"+JSON.stringify(row)+")' data-message='"+row.pilihtoko+"'><i class='fa fa-plus'></i></div>"+
              //  "<div class='btn btn-danger btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Cetak' onclick='cetakData(this,"+JSON.stringify(row)+")' data-message='"+row.cetak+"' data-tipe='header'><i class='fa fa-print'></i></div>"+
              // "<div class='btn btn-primary btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Tambah Luar Rencana' onclick='tambahLuarRencana(this,"+JSON.stringify(row)+")' data-message='"+row.luarrencana+"' data-tipe='header'><i class='fa fa-plus'></i></div>";
              return "<div class='btn-group no-margin-action'><button type='button' class='btn btn-primary dropdown-toggle btn-xs no-margin-action' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>A <span class='caret'></span></button>"+
              "<ul class='dropdown-menu'>"+
              "<li><a href='#' onclick='tambahTanggal(this,"+JSON.stringify(row)+")' data-message='"+row.pilihtoko+"'>Tambah Per Tanggal</a></li>"+
              "<li><a href='#'onclick='cetakData(this,"+JSON.stringify(row)+")' data-message='"+row.cetak+"' >Cetak</a></li>"+
              "<li><a href='#' onclick='tambahLuarRencana(this,"+JSON.stringify(row)+")' data-message='"+row.luarrencana+"'>Tambah Luar Rencana</a></li>"+
              "</ul></div>";
              
          }
        },
        {
          "data" : "kodesales",
          "className": "menufilter numberfilter"
        },
        {
          "data" : "namakaryawan",
          "className": "menufilter textfilter"
        },
      ]
    });

    $.contextMenu({
      selector: '#table1 tbody td.dataTables_empty',
      className: 'numberfilter',
      items: {
        name: {
          name: "Text",
          type: 'text',
          value: "",
          events: {
            keyup: function(e) {
              // add some fancy key handling here?
              // window.console && console.log('key: '+ e.keyCode);
            }
          }
        },
        // tipe: {
        //   name: "Tipe",
        //   type: 'select',
        //   options: {1: 'Find', 2: 'Filter'},
        //   selected: 1
        // },

        filter: {
          name: "Filter",
          type: 'select',
          options: {1: '<=', 2: '<', 3: '=', 4: '>', 5: '>='},
          selected: 3
        },
        key: {
          name: "Cancel",
          callback: $.noop
        }
      },
      events: {
        show: function(opt) {
          context_menu_number_state = 'show';
          $(document).off('focusin.modal');
          setTimeout(function(){
              $('.context-menu-list.numberfilter input[name="context-menu-input-name"]').focus();
          }, 10);
        },
        hide: function(opt) {
          // this is the trigger element
          var $this = this;
          // export states to data store
          var contextData = $.contextMenu.getInputValues(opt, $this.data());
          console.log('number');
          console.log(contextData);
          context_menu_number_state = 'hide';
          column_index = table.column(this).index();
          if(column_index != undefined) {
            last_index = column_index;
          }else {
            column_index = last_index;
          }
          custom_search[column_index].filter = filter_number[contextData.filter-1];
          custom_search[column_index].text = contextData.name;
          // table.columns(table.column(this).index()).search( contextData.name ).draw();
          // this basically dumps the input commands' values to an object
          // like {name: "foo", yesno: true, radio: "3", &hellip;}
        },
      }
    });

    $.contextMenu({
      selector: '#table1 tbody td.menufilter.numberfilter',
      className: 'numberfilter',
      items: {
        name: {
          name: "Text",
          type: 'text',
          value: "",
          events: {
            keyup: function(e) {
              // add some fancy key handling here?
              // window.console && console.log('key: '+ e.keyCode);
            }
          }
        },
        // tipe: {
        //   name: "Tipe",
        //   type: 'select',
        //   options: {1: 'Find', 2: 'Filter'},
        //   selected: 1
        // },

        filter: {
          name: "Filter",
          type: 'select',
          options: {1: '<=', 2: '<', 3: '=', 4: '>', 5: '>='},
          selected: 3
        },
        key: {
          name: "Cancel",
          callback: $.noop
        }
      },
      events: {
        show: function(opt) {
          context_menu_number_state = 'show';
          $(document).off('focusin.modal');
          setTimeout(function(){
              $('.context-menu-list.numberfilter input[name="context-menu-input-name"]').focus();
          }, 10);
        },
        hide: function(opt) {
          // this is the trigger element
          var $this = this;
          // export states to data store
          var contextData = $.contextMenu.getInputValues(opt, $this.data());
          console.log('number');
          console.log(contextData);
          context_menu_number_state = 'hide';
          column_index = table.column(this).index();
          if(column_index != undefined) {
            last_index = column_index;
          }else {
            column_index = last_index;
          }
          custom_search[column_index].filter = filter_number[contextData.filter-1];
          custom_search[column_index].text = contextData.name;
          // table.columns(table.column(this).index()).search( contextData.name ).draw();
          // this basically dumps the input commands' values to an object
          // like {name: "foo", yesno: true, radio: "3", &hellip;}
        },
      }
    });

    $.contextMenu({
      selector: '#table1 tbody td.menufilter.textfilter',
      className: 'textfilter',
      items: {
        name: {
          name: "Text",
          type: 'text',
          value: "",
          events: {
            keyup: function(e) {
              // add some fancy key handling here?
              // window.console && console.log('key: '+ e.keyCode);
            }
          }
        },
        // tipe: {
        //   name: "Tipe",
        //   type: 'select',
        //   options: {1: 'Find', 2: 'Filter'},
        //   selected: 1
        // },

        filter: {
          name: "Filter",
          type: 'select',
          options: {1: '=', 2: '!='},
          selected: 1
        },
        key: {
          name: "Cancel",
          callback: $.noop
        }
      },
      events: {
        show: function(opt) {
          context_menu_text_state = 'show';
          $(document).off('focusin.modal');
          setTimeout(function(){
              $('.context-menu-list.textfilter input[name="context-menu-input-name"]').focus();
          }, 10);
        },
        hide: function(opt) {
          // this is the trigger element
          var $this = this;
          // export states to data store
          var contextData = $.contextMenu.getInputValues(opt, $this.data());
          console.log('text');
          console.log(contextData);
          context_menu_text_state = 'hide';
          column_index = table.column(this).index();
          if(column_index != undefined) {
            last_index = column_index;
          }else {
            column_index = last_index;
          }
          custom_search[column_index].filter = filter_text[contextData.filter-1];
          custom_search[column_index].text = contextData.name;
          // table.columns(table.column(this).index()).search( contextData.name ).draw();
          // this basically dumps the input commands' values to an object
          // like {name: "foo", yesno: true, radio: "3", &hellip;}
        },
      }
    });

    $(document.body).on("keydown", function(e){
      // console.log("key:", e.keyCode);
      if(e.keyCode == 13){
        if(context_menu_number_state == 'show'){
          $(".context-menu-list.numberfilter").contextMenu("hide");
          table.ajax.reload(null, false);
        }else if(context_menu_text_state == 'show'){
          $(".context-menu-list.textfilter").contextMenu("hide");
          table.ajax.reload(null, false);
        }
      }
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
     $('#tableDetailLuarRencana tbody').on('click', 'td a.details-control', function () {
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
    $('.bulan').change(function(){
       bulanku= $('#bulan').val();
       table.rows(table_index).deselect();
       table.rows(table_index).select();
       console.log(bulanku);
    });
    table2 = $('#table2').DataTable({
      dom     : 'lrtp',
      select: {style:'single'},
      keys: {keys: [38,40]},
      scrollY : 130,
      scrollX : true,
      scroller: {
        loadingIndicator: true
      },
      order     : [[ 1, 'asc' ]],
      columns   : [
        {
          "orderable" : false,
        },
        null,
        null,
        null,
        {
          "className" : "text-right",
        },
        {
          "className" : "text-right",
        },
        {
          "className" : "text-right",
        },
        {
          "className" : "text-right",
        },
        {
          "className" : "text-right",
        },
      ],
    });

    table3 = $('#table3').DataTable({
      dom     : 'lrtp',
      select: {style:'single'},
      keys: {keys: [38,40]},
      scrollY : "33vh",
      scrollX : true,
      scroller: {
        loadingIndicator: true
      },
      
      order     : [[ 1, 'asc' ]],
      columns   : [
        {
          "orderable" : false,
        },
        {
          "className" : "text-right",
        },
        {
          "className" : "text-right",
        },
        null,
        null,
        null,
        null,
        null,
        {
          "className" : "text-right",
        },
        {
          "className" : "text-right",
        },
      ],
    });

    $('a.toggle-vis').on( 'click', function (e) {
      e.preventDefault();

      // Get the column API object
      var column = table.column( $(this).attr('data-column') );

      // Toggle the visibility
      column.visible( ! column.visible() );
      $('#eye'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
    });

    $('a.toggle-vis-2').on( 'click', function (e) {
      e.preventDefault();

      // Get the column API object
      var column = table2.column( $(this).attr('data-column') );

      // Toggle the visibility
      column.visible( ! column.visible() );
      $('#eye-detail'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
    });
     $('a.toggle-vis-3').on( 'click', function (e) {
      e.preventDefault();

      // Get the column API object
      var column = table3.column( $(this).attr('data-column') );

      // Toggle the visibility
      column.visible( ! column.visible() );
      $('#eye-detailkunjungan'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
    });

    table.on('select', function ( e, dt, type, indexes ){
      var rowData = table.rows(indexes ).data().toArray(); 
      console.log();
      $.ajaxSetup({
        headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
      });
       $.ajax({
        type: 'POST',
        data: {
            id   : rowData[0].id,
            nama : rowData[0].namakaryawan,
            kode : rowData[0].kodesales,
            bulan: bulanku,
            },
        dataType: "json",
        url: '{{route("fixedroutedetail.data")}}',
        success: function(data,row){
          table2.clear();
          for (var i = 0; i < data.node.length; i++) {
              x = '<div class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="Tambah" onclick="tambahData(this,'+data.node[i][3]+','+data.node[i][9]+')" data-tipe="detail"><i class="fa fa-plus"></i></div>';
              x += '<div class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="edit" onclick="editData(this,'+data.node[i][3]+','+data.node[i][9]+')" data-tipe="detail"><i class="fa fa-pencil"></i></div>'
            table2.row.add([
              x,
              data.node[i][0],
              data.node[i][1],
              data.node[i][2],
              data.node[i][4],
              data.node[i][5],
              data.node[i][6],
              data.node[i][7],
              data.node[i][8],
              data.node[i][3],
              data.node[i][9],
            ]);
          }
          table2.draw();
        },
        error: function(data){
          console.log(data);
        }
      });
      $.ajax({
        type: 'POST',
        data: {
          id: rowData[0].id,
          bulan : bulanku,
        },
        dataType: "json",
        url: '{{route("fixedroutedetail.kunjungan")}}',
        success: function(data){
          table3.clear();
          for (var i = 0; i < data.node.length; i++) {
              x = '<div class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="deleteKunjungan(this,'+data.node[i][10]+')" data-message="'+data.node[i][11]+'" data-tipe="hapus"><i class="fa fa-trash"></i></div>';
              x += '<div class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="edit" onclick="editKunjungan(this,'+data.node[i][10]+')" data-message="'+data.node[i][12]+'" data-tipe="edit"><i class="fa fa-pencil"></i></div>'
             
            
            table3.row.add([
              x,//ubah data ini dengan tag html button lookup di server side
              data.node[i][0],
              data.node[i][1],
              data.node[i][2],
              data.node[i][3],
              data.node[i][4],
              data.node[i][5],
              data.node[i][6],
              data.node[i][7],
              data.node[i][8],
              data.node[i][10],
            ]);
          }
          table3.draw();
        },
        error: function(data){
          console.log(data);
        }
      });
    });

    tabledatafixedroute = $('#FixedRoute').DataTable({
      dom     : 'lrtp',
      paging    : false,
    });

    tabledatafixedroutetoko = $('#FixedRouteToko').DataTable({
      dom     : 'lrtp',
      paging    : false,
    });

    tabledatafixedroutekunjungan = $('#FixedRouteKunjungan').DataTable({
      dom     : 'lrtp',
      paging    : false,
    });

    $('#table1 tbody').on('dblclick', 'tr', function(){
      var data = table.row(this).data();
      console.log(data);//untuk pop up grid
      tabledatafixedroute.clear();
      tabledatafixedroute.rows.add([
        {'0':'<div class="text-right">1.</div>', '1':'ID Karyawan', '2':data.id},
        {'0':'<div class    ="text-right">2.</div>', '1':'Kode Sales', '2':data.kodesales},
        {'0':'<div class    ="text-right">3.</div>', '1':'Nama Sales', '2':data.namakaryawan},
        {'0':'<div class    ="text-right">4.</div>', '1':'NIK', '2':data.nikhrd},
      ]);
       tabledatafixedroute.draw();
       $('#modalDataFixedRoute').modal('show');

    });

    $('#table2 tbody').on('dblclick', 'tr', function(){
      var data = table2.row(this).data();
      bulan = $('#bulan').val();
      console.log(data);//untuk pop up grid
      $.ajax({
        type: 'POST',
        url: '{{route("fixedroutedetail.detail")}}',
        data: {
          id: data[9],
          bulan : bulan,
          idkaryawan : data[10],
        },
        dataType: "json",
        success: function(data){
          for (var i = 0; i < data.node.length; i++) {
           tabledatafixedroutetoko.clear();
           tabledatafixedroutetoko.rows.add([
            {'0':'<div class="text-right">1.</div>', '1':'Nama Toko', '2':data.node[i][0]},
            {'0':'<div class="text-right">2.</div>', '1':'Alamat Toko', '2':data.node[i][1]},
            {'0':'<div class="text-right">3.</div>', '1':'Kecamatan', '2':data.node[i][2]},
            {'0':'<div class="text-right">4.</div>', '1':'Kota/Kab', '2':data.node[i][3]},
            {'0':'<div class="text-right">5.</div>', '1':'WillID', '2':data.node[i][4]},
            {'0':'<div class="text-right">6.</div>', '1':'Tanggal Input 1', '2':data.node[i][7]},
            {'0':'<div class="text-right">7.</div>', '1':'Tanggal Kunjungan 1', '2':data.node[i][12]},
            {'0':'<div class="text-right">8.</div>', '1':'Keterangan Realisasi Kunjungan 1', '2':data.node[i][17]},
            {'0':'<div class="text-right">9.</div>', '1':'Keterangan Tambahan 1', '2':data.node[i][22]},
            {'0':'<div class="text-right">10.</div>', '1':'Tanggal Input 2', '2':data.node[i][8]},
            {'0':'<div class="text-right">11.</div>', '1':'Tanggal Kunjungan 2', '2':data.node[i][13]},
            {'0':'<div class="text-right">12.</div>', '1':'Keterangan Realisasi Kunjungan 2', '2':data.node[i][18]},
            {'0':'<div class="text-right">13.</div>', '1':'Keterangan Tambahan 2', '2':data.node[i][23]},
            {'0':'<div class="text-right">14.</div>', '1':'Tanggal Input 3', '2':data.node[i][9]},
            {'0':'<div class="text-right">15.</div>', '1':'Tanggal Kunjungan 3', '2':data.node[i][14]},
            {'0':'<div class="text-right">16.</div>', '1':'Keterangan Realisasi Kunjungan 3', '2':data.node[i][19]},
            {'0':'<div class="text-right">17.</div>', '1':'Keterangan Tambahan 3', '2':data.node[i][24]},
            {'0':'<div class="text-right">18.</div>', '1':'Tanggal Input 4', '2':data.node[i][10]},
            {'0':'<div class="text-right">19.</div>', '1':'Tanggal Kunjungan 4', '2':data.node[i][15]},
            {'0':'<div class="text-right">20.</div>', '1':'Keterangan Realisasi Kunjungan 4', '2':data.node[i][20]},
            {'0':'<div class="text-right">21.</div>', '1':'Keterangan Tambahan 4', '2':data.node[i][25]},
            {'0':'<div class="text-right">22.</div>', '1':'Tanggal Input 5', '2':data.node[i][11]},
            {'0':'<div class="text-right">23.</div>', '1':'Tanggal Kunjungan 5', '2':data.node[i][16]},
            {'0':'<div class="text-right">24.</div>', '1':'Keterangan Realisasi Kunjungan 5', '2':data.node[i][21]},
            {'0':'<div class="text-right">25.</div>', '1':'Keterangan Tambahan 5', '2':data.node[i][26]},
            {'0':'<div class="text-right">26.</div>', '1':'Omset Rata-Rata', '2':data.node[i][27]},
            {'0':'<div class="text-right">27.</div>', '1':'Saldo Piutang', '2':data.node[i][28]},
          ]);
          tabledatafixedroutetoko.draw();
          }
          $('#modalDataFixedRouteToko').modal('show');
        },
      });
    });
    $('#table3 tbody').on('dblclick', 'tr', function(){
      var data = table3.row(this).data();
      console.log(data);//untuk pop up grid

      $.ajax({
        type: 'POST',
        url: '{{route("fixedroutedetailkunjungan.detail")}}',
        data: {id: data[10]},
        dataType: "json",
        success: function(data){
          tabledatafixedroutekunjungan.clear();
          tabledatafixedroutekunjungan.rows.add([

            {'0':'<div class="text-right">1.</div>', '1':'Nama Toko', '2':data.namatoko},
            {'0':'<div class="text-right">2.</div>', '1':'Alamat Toko', '2':data.alamat},
            {'0':'<div class="text-right">3.</div>', '1':'Kecamatan', '2':data.kecamatan},
            {'0':'<div class="text-right">4.</div>', '1':'Kota/Kab', '2':data.kota},
            {'0':'<div class="text-right">5.</div>', '1':'WillID', '2':data.customwilayah},
            {'0':'<div class="text-right">6.</div>', '1':'Tanggal Input', '2':data.tglinput},
            {'0':'<div class="text-right">7.</div>', '1':'Tanggal Kunjungan', '2':data.tglkunjung},
            {'0':'<div class="text-right">8.</div>', '1':'Keterangan Realisasi Kunjungan', '2':data.keteranganrealisasi},
            {'0':'<div class="text-right">9.</div>', '1':'Keterangan Tambahan', '2':data.keterangantambahan},
            {'0':'<div class="text-right">10.</div>', '1':'Omset Rata-Rata', '2':data.omsetavg},
            {'0':'<div class="text-right">11.</div>', '1':'Saldo Piutang', '2':data.saldopiutang},
          ]);
          tabledatafixedroutekunjungan.draw();
          $('#modalDataFixedRouteKunjungan').modal('show');
        },
      });
    });
   
  });
    

  $(document).ajaxComplete(function() {
    $('input[type=checkbox]').iCheck({
      checkboxClass: 'icheckbox_flat-green',
    });
  });

  function cetakData(e, data){
    swal("Ups!", "Menunggu Format Cetakan", "error");
  }

  function tambahTanggal(e, data){
    @cannot('fixedroute.tambahrecordkunjungan')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    var message = $(e).data('message');
    $('#myModalTanggal').text('Sales '+ data.namakaryawan +' akan berkunjung pada Tanggal:');
    $('#modalTanggal #namakaryawan').val(data.namakaryawan);
    $('#modalTanggal #idkaryawan').val(data.id);
    $('#modalTanggal').modal('show');
    @endcannot
  }

  $('#submitTanggal').submit(function(e){
    @cannot('fixedroute.tambahrecordkunjungan')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
      e.preventDefault();
      var tglkunjung    =$('#modalTanggal #tglkunjung').val();
      var tglserver     =$('#modalTanggal #tglserver').val();
      var idkaryawan    =$('#modalTanggal #idkaryawan').val();
      var namakaryawan  =$('#modalTanggal #namakaryawan').val();

      if (tglkunjung=='') {
        $('#modalTanggal #tglkunjung').focus();
        swal("Ups!", "Tanggal Input harus diisi", "error");
        return false;
      }
      else if (tglkunjung < tglserver) {
        $('#modalTanggal #tglkunjung').focus();
        swal("Ups!", "Tanggal tidak boleh kurang dari tanggal sekarang!", "error");
        return false;
      }
      else{
        $('#modalTanggal').modal('hide');
        window.location.href = '{{route("fixedroute.tambahrecordkunjungan")}}/' + tglkunjung + '/' +idkaryawan;
      }
    @endcannot
  });

  $('#formUpdateKunjungan').submit(function(e){
      e.preventDefault();
      @cannot('fixedroute.ubah')
      swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      console.log(e);
      var data = {
        idkunjungan : $('#modalUpdateTanggalKunjungan #idkunjungan').val(),
        tglkunjung  : $('#modalUpdateTanggalKunjungan #tglkunjung').val(),
        realisasi   : $('#modalUpdateTanggalKunjungan #realisasi').val(),
        kettambahan : $('#modalUpdateTanggalKunjungan #kettambahan').val(),
        tipe        : $('#modalUpdateTanggalKunjungan #tipe').val(),
      };
      $.ajax({
        type    : 'POST',
        url     : '{{route("fixedroute.ubah")}}',
        data    : {data: data},
        dataType: "json",
        success: function(data){
          if (data.success) {
            table.ajax.reload(null, true);
            tipe_edit = null;
            setTimeout(function(){
                table.row(0).deselect();
                table.row(0).select();
            },1000);
          }
          else{
             swal("Ups!", data.message, "error");
          }
          $('#modalUpdateTanggalKunjungan').modal('hide');
           
        },
      });
      @endcannot
    });

  function tambahLuarRencana(e, data){
    @cannot('fixedroute.tambahrecordkunjunganluarrencana')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    var message = $(e).data('message');
    $('#myModalInputLuarRencana').text('Sales '+ data.namakaryawan +' akan berkunjung pada Tanggal:');
    $('#modalInputLuarRencana #namakaryawan').val(data.namakaryawan);
    $('#modalInputLuarRencana #idkaryawan').val(data.id);
    $('#modalInputLuarRencana').modal('show');
    @endcannot
  }

  $('#submitLuarRencana').submit(function(e){
    @cannot('fixedroute.tambahrecordkunjunganluarrencana')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
      e.preventDefault();
      var tglkunjung    =$('#modalInputLuarRencana #tglkunjung').val();
      var tglserver     =$('#modalInputLuarRencana #tglserver').val();
      var idkaryawan    =$('#modalInputLuarRencana #idkaryawan').val();
      var namakaryawan  =$('#modalTanggal #namakaryawan').val();
      if (tglkunjung=='') {
          $('#modalInputLuarRencana #tglkunjung').focus();
          swal("Ups!", "Tanggal Input harus diisi", "error");
          return false;
      }
      else if (tglkunjung < tglserver) {
          $('#modalInputLuarRencana #tglkunjung').focus();
          swal("Ups!", "Tanggal tidak boleh kurang dari tanggal sekarang!", "error");
          return false;
      }
      else{
        $('#modalInputLuarRencana').modal('hide');
        window.location.href = '{{route("fixedroute.tambahrecordkunjunganluarrencana", [null,null])}}/' + tglkunjung + '/' +idkaryawan;
      }
      @endcannot
  });

  function editKunjungan(e, data){
    @cannot('fixedroute.ubah')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    var message = $(e).data('message');
    console.log(data);
    if(message == 'edit') {
      var id = data;
      $.ajax({
        type: 'POST',
        url: '{{route("fixedroutedetailkunjungan.detail")}}',
        data: {id: id},
        dataType: "json",
        success: function(data){
          $('#modalUpdateTanggalKunjungan #idkunjungan').val(id);
          $('#modalUpdateTanggalKunjungan #toko').val(id);
          $('#modalUpdateTanggalKunjungan #namatoko').val(data.namatoko);
          $('#modalUpdateTanggalKunjungan #namasales').val(data.namasales);
          $('#modalUpdateTanggalKunjungan #kodesales').val(data.kodesales);
          $('#modalUpdateTanggalKunjungan #alamat').val(data.alamat);
          $('#modalUpdateTanggalKunjungan #kota').val(data.kota);
          $('#modalUpdateTanggalKunjungan #tglkunjung').val(data.tglkunjung);
          $('#modalUpdateTanggalKunjungan #realisasi').val(data.keteranganrealisasi).prop('selected', true);
          $('#modalUpdateTanggalKunjungan #realisasi').attr('disabled',true);
          $('#modalUpdateTanggalKunjungan #kettambahan').val(data.keterangantambahan);  
          $('#modalUpdateTanggalKunjungan #kettambahan').attr('disabled',true);
          $('#modalUpdateTanggalKunjungan #tipe').val('edit'); 
        },
        error: function(data){
          console.log(data);
        }
      });
      $('#modalUpdateTanggalKunjungan').modal('show');
    }else if(message == 'editdisable'){
      var id = data;
      $.ajax({
        type: 'POST',
        url: '{{route("fixedroutedetailkunjungan.detail")}}',
        data: {
          id: id,
        },
        dataType: "json",
        success: function(data){
          $('#modalUpdateTanggalKunjungan #idkunjungan').val(id);
          $('#modalUpdateTanggalKunjungan #toko').val(id);
          $('#modalUpdateTanggalKunjungan #namatoko').val(data.namatoko);
          $('#modalUpdateTanggalKunjungan #namasales').val(data.namasales);
          $('#modalUpdateTanggalKunjungan #kodesales').val(data.kodesales);
          $('#modalUpdateTanggalKunjungan #alamat').val(data.alamat);
          $('#modalUpdateTanggalKunjungan #kota').val(data.kota);
          $('#modalUpdateTanggalKunjungan #tglkunjung').val(data.tglkunjung);
          $('#modalUpdateTanggalKunjungan #tglkunjung').attr('disabled',true);
          $('#modalUpdateTanggalKunjungan #realisasi').val(data.keteranganrealisasi).prop('selected', true);
          $('#modalUpdateTanggalKunjungan #kettambahan').val(data.keterangantambahan);
          $('#modalUpdateTanggalKunjungan #tipe').val('editdisable'); 
        },
        error: function(data){
          console.log(data);
        }
      });

        $('#modalUpdateTanggalKunjungan').modal('show');
    }else{
      swal('Ups!', message,'error');
    }
    @endcannot
  }

  function deleteKunjungan(e, data){
    @cannot('fixedroutedetailkunjungan.hapus')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    var message = $(e).data('message');
    if (message == 'delete') {
      tipe_edit = true;
      $.ajax({
        type: 'POST',
        url: '{{route("fixedroutedetailkunjungan.hapus")}}',
        data: {
          kunjunganid : data,
        },
        dataType: "json",
        success: function(data){
          console.log(data);
          if(data.success){
            swal('Sukses!', 'Data berhasil dihapus','success');
            table.ajax.reload(null, true);
            tipe_edit = null;
            setTimeout(function(){
                table.row(0).deselect();
                table.row(0).select();
            },1000);
          }else{
            swal('Ups!', 'Terdapat kesalahan pada sistem.','error');
          }
        },
        error: function(data){
          console.log(data);
        }
      });
    }else {
      swal('Ups!',message,'error');
    }
    @endcannot
  } 

  function tambahData(e,data,idkaryawan){
    @cannot('fixedroute.tambahtglkunjungan')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    idkaryawan = idkaryawan;
    bulan  = bulanku;
    idtoko = data;
    $.ajax({
      type: 'POST',
      url : '{{route("fixedroute.getjumlahkunjungan")}}',
      data: {
        id    : data,
        bulan : bulanku,
      },
      dataType: "json",
      success: function(data){
        console.log(data.success);
        if(data.success){
          swal('Ups!', data.message,'error');
        }else{
          window.location.href = '{{route("fixedroute.tambahtglkunjungan")}}/' + idtoko + '/' +idkaryawan + '/' +bulan;
        }
      },
      error: function(data){
        console.log(data);
      }
    });
    @endcannot
  }

  function editData(e,data,idkaryawan){
    @cannot('fixedroute.updatepertoko')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    idkaryawan = idkaryawan;
    bulan      = bulanku;
    idtoko     = data;
    window.location.href = '{{route("fixedroute.updatepertoko")}}/' + idtoko + '/' +idkaryawan + '/' +bulan;
    @endcannot
  } 
</script>
@endpush
