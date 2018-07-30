@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
    <li class="breadcrumb-item"><a href="{{ route('scrab.index') }}">Scrab</a></li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
          <h2>Scrab</h2>
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
                  <label style="margin-right: 10px;">Tgl. Transaksi</label>
                  <input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
                  <label>-</label>
                  <input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
                </div>
              </form>
            </div>
            <div class="col-md-6 text-right">
              @can('scrab.tambah')
              <a href="{{route('scrab.tambah')}}" class="btn btn-success" id="skeyIns"><i class="fa fa-plus"></i> Tambah - Ins</a>
              @endcan
            </div>
          </div>

          <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="1%">A</th>
                <th>Tgl. Transaksi</th>
                <th>No. Transaksi</th>
                <th>Staf Stock</th>
                <th>Status 00</th>
                <th>Status 11</th>
                <th>Tgl Link</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <div style="cursor: pointer; margin-top: 10px;" class="hidden">
            <p>
              <span>Toggle column <strong>Hide/Show</strong> :</span>
              <a class="toggle-vis" data-column="1">
                <i id="eye1" class="fa fa-eye"></i>&nbsp;&nbsp;Tgl. Transaksi
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="2">
                &nbsp;&nbsp;<i id="eye2" class="fa fa-eye"></i>&nbsp;&nbsp;No. Transaksi
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="3">
                &nbsp;&nbsp;<i id="eye3" class="fa fa-eye"></i>&nbsp;&nbsp;Staf Stock
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="4">
                &nbsp;&nbsp;<i id="eye4" class="fa fa-eye"></i>&nbsp;&nbsp;Status 00
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="5">
                &nbsp;&nbsp;<i id="eye5" class="fa fa-eye"></i>&nbsp;&nbsp;Status 11
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis" data-column="6">
                &nbsp;&nbsp;<i id="eye6" class="fa fa-eye"></i>&nbsp;&nbsp;Tgl Link
              </a>
            </p>
          </div>
          {{-- <hr> --}}
          <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="1%">A</th>
                <th width="40%">Barang</th>
                <th>Sat</th>
                <th>Qty</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <div style="cursor: pointer; margin-top: 10px;" class="hidden">
            <p>
              <span>Toggle column <strong>Hide/Show</strong> :</span>
              <a class="toggle-vis-2" data-column="1">
                <i id="eye-detail1" class="fa fa-eye"></i>&nbsp;&nbsp;Barang
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis-2" data-column="2">
                &nbsp;&nbsp;<i id="eye-detail2" class="fa fa-eye"></i>&nbsp;&nbsp;Satuan
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis-2" data-column="3">
                &nbsp;&nbsp;<i id="eye-detail3" class="fa fa-eye"></i>&nbsp;&nbsp;Qty
              </a>
              &nbsp;&nbsp;|
              <a class="toggle-vis-2" data-column="4">
                &nbsp;&nbsp;<i id="eye-detail4" class="fa fa-eye"></i>&nbsp;&nbsp;Keterangan
              </a>
              
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Form Kewenangan -->
  <div class="modal fade" id="modalKewenangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xs" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Form Kewenangan</h4>
        </div>
        <form id="formKewenangan" action="{{route('scrab.kewenangan')}}" class="form-horizontal" method="post">
          <input type="hidden" id="scrabIdHapus" value="">
          <input type="hidden" id="tipe" value="">
          <div class="modal-body">
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="uxserKewenangan">Username</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                  <input type="text" id="uxserKewenangan" class="form-control" placeholder="Username" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="pxassKewenangan">Password</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                  <input type="password" id="pxassKewenangan" class="form-control" placeholder="Password" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Proses</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end of Form Kewenangan -->

  <!-- modal detail -->
  <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="labelModalDetail">Stock Scrab Detail</h4>
        </div>
        <form id="formDetail" class="form-horizontal" method="post">
          <input type="hidden" id="scrabid" value="">
          <input type="hidden" id="scrabIdDetail" value="">
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="stockid">Barang <span class="required">*</span></label>
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
                <input type="number" id="qty" name="qty" class="form-control hitungnetto" placeholder="Qty Order" required="required">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangandetail">Keterangan <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id="keterangandetail" rows="3" class="form-control" placeholder="Keterangan" required="required"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" id="btnSubmitDetail" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end of modal detail -->

  <!-- Data stock scrab -->
  <div class="modal fade" id="modalDataStockScrab" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Data Scrab</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-xs-12">
              <div class="table-responsive">
                <table id="StockScrab" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th class="text-center">Nama Kolom</th>
                      <th class="text-center">Nilai</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyStockScrab">
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
  <!-- end of data stock scrab -->

  <!-- Data order pembelian detail -->
  <div class="modal fade" id="modalDataStockScrabDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Data Scrab Detail</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-xs-12">
              <div class="table-responsive">
                <table id="StockScrabDetail" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th class="text-center">Nama Kolom</th>
                      <th class="text-center">Nilai</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyStockScrabDetail">
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
  <!-- end of data order pembelian detail -->
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
  var tipe_edit   = null;
  var ids     = null;
  var index   = null;
  var context_menu_number_state = 'hide';
  var context_menu_text_state = 'hide';
  var last_index = '';
  var sync = '';
  var table,table2,table_index,fokus,table2_index;
  var custom_search = [
    { text : '', filter : '='},
    { text : '', filter : '='},
    { text : '', filter : '='},
    { text : '', filter : '='},
    { text : '', filter : '='},
    { text : '', filter : '='},
    { text : '', filter : '='},
  ];
  var filter_number = ['<=','<','=','>','>='];
  var filter_text = ['=','!='];
  var tipe = ['Find','Filter'];
  var column_index = 0;

  {{-- @include('lookupbarang') --}}
  lookupbarang();

  $(document).ready(function() {
    $(".tgl").inputmask();
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

    $('#modalKewenangan').on('shown.bs.modal', function () {
      $('#uxserKewenangan').focus();
    });
    $('#modalUpdateOrder').on('shown.bs.modal', function () {
      $('#supplier').focus();
    });
    $('#modalDetail').on('shown.bs.modal', function () {
      $('#barang').focus();
    });

    table = $('#table1').DataTable({
      dom        : 'lrtp',//lrtip -> lrtp
      serverSide : true,
      stateSave  : true,
      deferRender: true,
      select: {style:'single'},
      keys: {keys: [38,40]},
      ajax : {
        url: '{{ route("scrab.header.data") }}',
        data: function ( d ) {
          d.custom_search = custom_search;
          d.tglmulai      = $('#tglmulai').val();
          d.tglselesai    = $('#tglselesai').val();
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
              return "<div class='btn btn-success btn-xs no-margin-action skeyF1' data-toggle='tooltip' data-placement='bottom' title='Tambah Detail - F1' onclick='tambahDetail(this,"+JSON.stringify(row)+")' data-message='"+row.add+"'><i class='fa fa-plus'></i></div>"+
              "<div class='btn btn-danger btn-xs no-margin-action skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus - Del' onclick='deleteScrab(this,"+JSON.stringify(row)+")' data-message='"+row.delete+"' data-tipe='header'><i class='fa fa-trash'></i></div>"+
              "<div class='btn-group no-margin-action'><button type='button' class='btn btn-primary dropdown-toggle btn-xs no-margin-action' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>A <span class='caret'></span></button>"+
              "<ul class='dropdown-menu'>"+
              "<li><a href='#' class='skeyF6' onclick='ajuanAdm(this,"+JSON.stringify(row)+")' data-message='"+row.ajuanadm+"'>Ajuan ke Ka Adm - F6</a></li>"+
              "<li><a href='#' class='skeyF7' onclick='ajuanStok(this,"+JSON.stringify(row)+")' data-message='"+row.ajuanstok+"'>Ajuan ke Stok 11 - F7</a></li>"+
              "<li><a href='#' class='skeyF8' onclick='ajuanMutasi(this,"+JSON.stringify(row)+")' data-message='"+row.ajuanmutasi+"'>Link ke Mutasi - F8</a></li>"+
              "</ul></div>";
          }
        },
        {
          "data" : "tgltransaksi",
          "className": "menufilter numberfilter"
        },
        {
          "data" : "notransaksi",
          "className": "menufilter numberfilter"
        },
        {
          "data" : "namakaryawan",
          "className": "menufilter textfilter"
        },
        {
          "data" : "status",
          "className": "menufilter textfilter"
        },
        {
          "data" : "status11",
          "className": "menufilter textfilter"
        },
        {
          "data" : "tgllink",
          "className": "menufilter numberfilter"
        },
      ]
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

    $('.tgl').change(function(){
      table.ajax.reload(null, false);
    });

    table2 = $('#table2').DataTable({
      dom     : 'lrtp',
      select: {style:'single'},
      keys: {keys: [38,40]},
      scrollY : "33vh",
      scrollX : true,
      scroller: {
        loadingIndicator: true
      },
      order  : [[ 1, 'asc' ]],
      columns: [
        { "data" : "action", "orderable" : false, },
        { "data" : "namabarang", },
        { "data" : "satuan", },
        { "data" : "qtyfinal", "className" : "text-right", },
        { "data" : "keterangan", "className" : "text-right", },
      ],
    });

    table.on('select', function ( e, dt, type, indexes ){
      var rowData = table.rows(indexes ).data().toArray();
      //console.log(rowData[0].id);  
      $.ajaxSetup({
        headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
      });

      $.ajax({
        type: 'POST',
        data: {id: rowData[0].id},
        dataType: "json",
        url: '{{route("scrab.detail.data")}}',
        success: function(data){
          table2.clear();
          for (var i = 0; i < data.node.length; i++) {
            
            table2.row.add({
              'action'    : '<div class="btn btn-danger btn-xs skeyDel" data-toggle="tooltip" data-placement="bottom" title="Hapus - Del" onclick="deleteScrab(this,'+data.node[i][4]+')" data-message="'+data.node[i][5]+'" data-tipe="detail"><i class="fa fa-trash"></i></div>',
              'namabarang': data.node[i][0],
              'satuan'    : data.node[i][1],
              'qtyfinal'  : data.node[i][2],
              'keterangan': data.node[i][3],
              'id'        : data.node[i][4],
              'DT_RowId'  : data.node[i]['DT_RowId'],
            });
          }
          table2.draw();
        },
        error: function(data){
          console.log(data);
        }
      });
    });
    var tabledatastockscrab = $('#StockScrab').DataTable({
      dom     : 'lrtp',
      paging    : false,
    });

    var tabledatastockscrabdetail = $('#StockScrabDetail').DataTable({
      dom     : 'lrtp',
      paging    : false,
    });

    $('#table1 tbody').on('dblclick', 'tr', function(){
      var data = table.row(this).data();
      console.log(data);//untuk pop up grid
      tabledatastockscrab.clear();
      tabledatastockscrab.rows.add([
        {'0':'<div class="text-right">1.</div>', '1':'Tgl. Transaksi', '2':data.tgltransaksi},
        {'0':'<div class    ="text-right">2.</div>', '1':'No. Transaksi', '2':data.notransaksi},
        {'0':'<div class    ="text-right">3.</div>', '1':'Staff Stock', '2':data.namakaryawan},
        {'0':'<div class    ="text-right">4.</div>', '1':'Staff Pemeriksa 00', '2':data.namapemeriksa},
        {'0':'<div class    ="text-right">5.</div>', '1':'Staff Pemeriksa 11', '2':data.namakaryawanacc},
        {'0':'<div class    ="text-right">6.</div>', '1':'Tgl. Ajuan Ka.Adm', '2':data.tglajuan},
        {'0':'<div class    ="text-right">7.</div>', '1':'Tgl. Status 00', '2':data.tglstatus00},
        {'0':'<div class    ="text-right">8.</div>', '1':'Status 00', '2':data.status},
        {'0':'<div class    ="text-right">9.</div>', '1':'Keterangan 00', '2':data.keterangan00},
        {'0':'<div class    ="text-right">10.</div>', '1':'Tgl. Ajuan Stok 11', '2':data.tglajuanstok11},
        {'0':'<div class    ="text-right">11.</div>', '1':'Tgl. Status  11', '2':data.tglstatus11},
        {'0':'<div class    ="text-right">12.</div>', '1':'Status 11', '2':data.status11},
        {'0':'<div class    ="text-right">13.</div>', '1':'Keterangan 11', '2':data.keterangan11},
        {'0':'<div class    ="text-right">14.</div>', '1':'Tgl. Link Mutasi 11', '2':data.tgllink},
        {'0':'<div class    ="text-right">15.</div>', '1':'Last Updated By', '2':data.lastupdatedby},
        {'0':'<div class    ="text-right">16.</div>', '1':'Last Updated On', '2':data.lastupdatedon},
      ]);
       tabledatastockscrab.draw();
       $('#modalDataStockScrab').modal('show');

    });

    $('#table2 tbody').on('dblclick', 'tr', function(){
      var data = table2.row(this).data();
      console.log(data);//untuk pop up grid

      $.ajax({
        type: 'POST',
        url: '{{route("scrab.detail.detail")}}',
        data: {id: data[5]},
        dataType: "json",
        success: function(data){
          tabledatastockscrabdetail.clear();
          tabledatastockscrabdetail.rows.add([
            {'0':'<div class="text-right">1.</div>', '1':'Barang', '2':data.namabarang},
            {'0':'<div class="text-right">2.</div>', '1':'Kode', '2':data.kodebarang},
            {'0':'<div class="text-right">3.</div>', '1':'Satuan', '2':data.satuan},
            {'0':'<div class="text-right">4.</div>', '1':'Qty', '2':data.qtyfinal},
            {'0':'<div class="text-right">5.</div>', '1':'Keterangan', '2':data.keterangan},
            {'0':'<div class="text-right">6.</div>', '1':'Last Updated By', '2':data.lastupdatedby},
            {'0':'<div class="text-right">7.</div>', '1':'Last Updated On', '2':data.lastupdatedon},
          ]);
          tabledatastockscrabdetail.draw();
          $('#modalDataStockScrabDetail').modal('show');
        },
      });
    });

    $('#formDetail').submit(function(e){
      e.preventDefault();
      @cannot('scrabdetail.tambah')
      swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
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
      //stok belum ada
      if($('#qty').val() > $('#jmlgudang').val()){
        $('#qty').focus();
        swal("Ups!", "Tidak bisa simpan record. Nilai kolom Qty "+ $('#qty').val() +" lebih besar dari stock gudang "+ $('#jmlgudang').val() +". Hubungi manager Anda ", "error");
        return false;
      }
      tipe_edit = true;
      var action = '{{route("scrabdetail.tambah")}}';
      $.ajax({
        type: 'POST',
        url: action,
        data: {
          scrabid          : $('#modalDetail #scrabid').val(),
          scrabdetailid    : $('#modalDetail #scrabIdDetail').val(),
          stockid          : $('#modalDetail #barangid').val(),
          qty              : $('#modalDetail #qty').val(),
          keterangandetail : $('#modalDetail #keterangandetail').val(),
        },
        dataType: 'json',
        success: function(data){
          console.log(data);
          $('#modalDetail').modal('hide');
            table.ajax.reload(null, true);
            tipe_edit = null;
            setTimeout(function(){
                table.row(0).deselect();
                table.row(0).select();
            },1000);

        }
      });
      @endcan
    });

    $('#formKewenangan').submit(function(e){
      e.preventDefault();
      tipe_edit = true;
      $.ajax({
        type: 'POST',
        url: '{{route("scrab.kewenangan")}}',
        data: {
          username    : $('#modalKewenangan #uxserKewenangan').val().trim().toUpperCase(), 
          password    : $('#modalKewenangan #pxassKewenangan').val(),
          scrabid     : $('#modalKewenangan #scrabIdHapus').val(),
          tipe        : $('#modalKewenangan #tipe').val(),
          permission  : 'scrab.hapus',
        },
        dataType: "json",
        success: function(data){
          $('#modalKewenangan #uxserKewenangan').val('').change();
          $('#modalKewenangan #pxassKewenangan').val('').change();

          if(data.success){
            $('#modalKewenangan').modal('hide');
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
    });
  });

  $(document).ajaxComplete(function() {
    $('input[type=checkbox]').iCheck({
      checkboxClass: 'icheckbox_flat-green',
    });
  });

  function tambahDetail(e, data){
    @cannot('scrabdetail.tambah')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    var message = $(e).data('message');
    if (message == 'add') {
      $('#modalDetail #scrabid').val(data.id);
      $('#modalDetail #scrabIdDetail').val('');
      $('#modalDetail #kodebarang').val('');
      $('#modalDetail #barang').val('');
      $('#modalDetail #barangid').val('');
      $('#modalDetail #satuan').val('');
      $('#modalDetail #qty').val('');
      $('#modalDetail #keterangandetail').val('');

      $('#labelModalDetail').text('Tambah Scrab Detail');
      $('#modalDetail').modal('show');
    }
    else {
      swal('Ups!', message,'error');
    }
    @endcan
  }
  function deleteScrab(e, data){
    @cannot('scrab.hapus')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    var message = $(e).data('message');
    var tipe = $(e).data('tipe');
    if (message == 'auth') {
      if (tipe == 'header') {
        $('#modalKewenangan #scrabIdHapus').val(data.id);
      }else {
        $('#modalKewenangan #scrabIdHapus').val(data);
      }
      $('#modalKewenangan #tipe').val(tipe);
      $('#modalKewenangan').modal('show');
    }else {
      swal('Ups!',message,'error');
    }
    @endcan
  }

  function ajuanAdm(e,data){
    @cannot('scrab.ajuanadm')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    var message = $(e).data('message');
    if (message == 'ajuanadm'){
      tipe_edit = true;
      swal({
      title: "Konfirmasi",
      text: "Apakah anda ingin mengajukan ke Ka Adm untuk no. dokumen "+ data.notransaksi +"?",
      type: "info",
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true
    }, function () {
        $.ajaxSetup({
            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
          });
        $.ajax({
            type: 'POST',
            url: '{{route("scrab.ajuanadm")}}',
            data: {
              data:data
            },
            success: function(data){
              if(data.success){
                swal("OK!", data.message, "success");
                table.ajax.reload(null, true);
                tipe_edit = null;
                setTimeout(function(){
                    table.row(0).deselect();
                    table.row(0).select();
                },1000);
              }
            }
        });
      });
    }else{
      swal('Ups!', message,'error');
    }
    @endcan
  }

  function ajuanStok(e,data){
    @cannot('scrab.ajuanstok')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    var message = $(e).data('message');
    if (message == 'ajuanstok'){
      tipe_edit = true;
      swal({
      title: "Konfirmasi",
      text: "Apakah anda ingin mengajukan ke stok 11 no. dokumen "+ data.notransaksi +"?",
      type: "info",
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true
    }, function () {
        $.ajaxSetup({
          headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
        });
        $.ajax({
          type: 'POST',
          url: '{{route("scrab.ajuanstok")}}',
          data: {
            data:data
          },
          success: function(data){
            if(data.success){
              swal("OK!", data.message, "success");
              table.ajax.reload(null, true);
              tipe_edit = null;
              setTimeout(function(){
                  table.row(0).deselect();
                  table.row(0).select();
              },1000);
            }
          }
        });
      });
    }else{
      swal('Ups!', message,'error');
    }
    @endcan
  }

  function ajuanMutasi(e,data){
    @cannot('scrab.ajuanmutasi')
    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    @else
    var message = $(e).data('message');
    if (message == 'ajuanmutasi'){
      tipe_edit = true;
      swal({
      title: "Konfirmasi",
      text: "Apakah anda ingin mengajukan ke link mutasi no. dokumen "+ data.notransaksi +"?",
      type: "info",
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true
    }, function () {
       $.ajaxSetup({
            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
          });
        $.ajax({
            type: 'POST',
            url: '{{route("scrab.ajuanmutasi")}}',
            data: {
              data:data
            },
            success: function(data){
              if(data.success){
                swal("OK!", data.message, "success");
                table.ajax.reload(null, true);
                tipe_edit = null;
                setTimeout(function(){
                    table.row(0).deselect();
                    table.row(0).select();
                },1000);
              }
            }
        });
      });
    }else{
      swal('Ups!', message,'error');
    }
    @endcan
  }

</script>
@endpush
