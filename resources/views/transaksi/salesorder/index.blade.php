@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
    <li class="breadcrumb-item"><a href="{{ route('salesorder.index') }}">Sales Order</a></li>
@endsection

@section('main_container')
  <div class="mainmain">
    <div class="row">
      <div class="x_panel">
        <div class="x_title">
			<h2>Daftar Sales Order</h2>
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
                  <label for="tglmulai" style="margin-right: 5px;">Tgl.PiL</label>
                  <input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">

                  <label for="tglselesai">-</label>
                  <input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
                </div>
              </form>
            </div>
            <div class="col-md-6 text-right">
              @can('salesorder.tambah')
              <a href="{{route('salesorder.tambah')}}" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Sales Order</a>
              @endcan
            </div>
            <div class="col-md-6 col-12">    
              <input id="search" name="search" class="form-control" placeholder="CARI APAPUN..." type="hidden" />                    
            </div>
          </div>

          <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="1%">A</th>
                <th>Tgl. PiL</th>
                <th>No. PiL</th>
                <th>Toko</th>
                <th>Tgl. SO</th>
                <th>No. SO</th>
                <th>No. ACC Piutang</th>
                <th>Salesman</th>
                <th>Total Hrg SO ACC</th>
                <th>Ajuan Harga ke 11</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <div style="cursor: pointer; margin-top: 10px;" class="hidden">
            <p>
              <span>Toggle column <strong>Hide/Show</strong> :</span>
              <a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i>Tgl. SO</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i>No. SO</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i>Tgl. PiL</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i>No. PiL</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i>No. ACC Piutang</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a class="toggle-vis" data-column="6"><i id="eye6" class="fa fa-eye"></i>Toko</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a class="toggle-vis" data-column="7"><i id="eye7" class="fa fa-eye"></i>Salesman</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a class="toggle-vis" data-column="8"><i id="eye8" class="fa fa-eye"></i>Total Hrg SO ACC</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a class="toggle-vis" data-column="9"><i id="eye9" class="fa fa-eye"></i>Ajuan Harga ke 11</a>
            </p>
          </div>
          {{-- <hr> --}}
          <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="1%">A</th>
                <th width="40%">Barang</th>
                <th>Sat</th>
                <th>Qty SO ACC</th>
                <th>Hrg. Sat. Netto</th>
                <th>Hrg. Total</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <div style="cursor: pointer; margin-top: 10px;" class="hidden">
            <p>
              <span>Toggle column <strong>Hide/Show</strong> :</span>
              <a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i>Barang</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i>Satuan</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i>Qty SO ACC</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a class="toggle-vis-2" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i>Hrg Satuan Netto</a>&nbsp;&nbsp;|&nbsp;&nbsp;
              <a class="toggle-vis-2" data-column="5"><i id="eye-detail5" class="fa fa-eye"></i>Hrg Total</a>
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
        <form id="formKewenangan" action="{{route("salesorder.kewenangan")}}" class="form-horizontal" method="post">
          <input type="hidden" id="orderIdHapus" value="">
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
          <h4 class="modal-title" id="labelModalDetail">Penjualan</h4>
        </div>
        <form id="formDetail" class="form-horizontal" method="post">
          <input type="hidden" id="orderpenjualanid" value="">
          <input type="hidden" id="orderIdDetail" value="">
          <input type="hidden" id="tipetransaksidetail" value="">
          <input type="hidden" id="statustokodetail" value="">
          <input type="hidden" id="tokoiddetail" value="">
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 hidden-xs" for="barangid">Barang <span class="required">*</span></label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  <input type="text" id="barang" class="form-control" placeholder="Barang" autocomplete="off" required="required">
                  <input type="hidden" id="barangid">
                  <input type="hidden" id="hrgbmk">
                  <input type="hidden" id="arrhrgbmk">
                  <input type="hidden" id="riwayatorder">
                  <input type="hidden" id="qtysoacc">
                  <input type="hidden" id="disc1" value="0">
                  <input type="hidden" id="hrgdisc1">
                  <input type="hidden" id="disc2" value="0">
                  <input type="hidden" id="hrgdisc2">
                  <input type="hidden" id="ppn" value="{{$ppn}}">
                  <input type="hidden" id="hrgsatuannetto">
                  <input type="hidden" id="hrgtotalnetto">
                  <input type="hidden" id="kodebarang">
                  <input type="hidden" id="kategoriPenjualan">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-4" for="qtyso">Qty SO<span class="required">*</span></label>
              <div class="col-md-4 col-sm-4 col-xs-8">
                <div class="input-group">
                  <input type="number" id="qtyso" class="form-control hitungnetto" placeholder="Qty SO" required="required">
                </div>
              </div>
              <label class="control-label col-md-1 col-sm-1 col-xs-4" for="qtyso">Qty Gudang</label>
              <div class="col-md-4 col-sm-4 col-xs-8">
                <input type="number" id="qtystockgudang" class="form-control" value="0" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-4" for="hrgpricelist">Harga Pricelist<span class="required"> *</span></label>
              <div class="col-md-4 col-sm-4 col-xs-8">
                <div class="input-group">
                  <span class="input-group-addon" id="basic-addon1">Rp</span>
                  <input type="number" id="hrgpricelist" class="form-control hitungnetto" placeholder="Harga Pricelist" readonly>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-4" for="discsales">Disc Sales</label>
              <div class="col-md-4 col-sm-4 col-xs-8">
                <div class="input-group">
                  <input type="number" id="discsales" class="form-control hitungnetto" step="0.01" value="0" required="required">
                  <span class="input-group-addon" id="basic-addon1">%</span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrgsatuanbrutto">Harga Satuan Brutto <span class="required">*</span></label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="input-group">
                  <span class="input-group-addon" id="basic-addon1">Rp</span>
                  <input type="number" id="hrgsatuanbrutto" class="form-control hitungnetto" placeholder="Harga Brutto" required="required">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 hidden-xs" for="catatandetail">Catatan</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <textarea id="catatandetail" rows="3" class="form-control" placeholder="Catatan"></textarea>
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
  <!-- end of modal detail -->

  <!-- Data Sales order -->
  <div class="modal fade" id="modalDataDblClick" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="labelDblClick">Data Sales Order</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-xs-12">
              <div class="table-responsive">
                <table id="tblDblClick" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th class="text-center">Nama Kolom</th>
                      <th class="text-center">Nilai</th>
										</tr>
									</thead>
                  <tbody id="tblDblClickData">
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
	<!-- end of data Sales order -->
@endsection

@push('stylesheets')
<style>
  table#table1 tr[status=closed] {
    background-color: #f2ffd8;
  }
  table#table1 tr[status=closed].odd {
    background-color: #ebffc1;
  }
  table#table1 tr[status=closed].selected {
    background-color: #4c7000;
  }
  table#table1 tr[status=pulled] {
    background-color: #fff6ce;
  }
  table#table1 tr[status=pulled].odd {
    background-color: #fff4c1;
  }
  table#table1 tr[status=pulled].selected {
    background-color: #8e7700;
  }
</style>
@endpush

@push('scripts')
<script type="text/javascript">
  var sync      = false;
  var tipe_edit = null;
  var context_menu_number_state = 'hide';
  var context_menu_text_state   = 'hide';
  var last_index    = '';
  var custom_search = [
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
  ];
  var filter_number = ['<=','<','=','>','>='];
  var filter_text   = ['=','!='];
  var tipe          = ['Find','Filter'];
  var column_index  = 0;
	var table,table2,tabledatadetail,table_index,table2_index,fokus;

  {{-- @include('lookupbarang') --}}

  // Run Lookup
  lookupsupplier();
  lookupsubcabang();
  lookupbarang('bmk','riwayatorder', null, null, {
    'typebarang': "FB|FE"
  });
  lookupsalesman();
  lookupexpedisi();

  $(document).ready(function(){
    
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

    $('#modalDetail').on('shown.bs.modal', function () {
      $('#barang').focus();
    });
    $('#modalKewenangan').on('shown.bs.modal', function () {
      $('#uxserKewenangan').focus();
    });

    $(document).ajaxComplete(function() {
      $('input[type=checkbox]').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        }).on('ifClicked', function(flat) {
           $(this).trigger("onclick");
        });
    });

    table = $('#table1').DataTable({
      dom        : 'lrtp',//lrtip -> lrtp
      serverSide : true,
      stateSave  : true,
      deferRender: true,
      select: {style:'single'},
      keys: {keys: [38,40]},
      ajax       : {
        url : '{{ route("salesorder.data") }}',
        type: "POST",
				headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") },
        data: function ( d ) {
					d.custom_search = custom_search;
          d.tglmulai   = $('#tglmulai').val();
          d.tglselesai = $('#tglselesai').val();
          d.tipe_edit  = tipe_edit;
          d.issync     = sync;
          d.xsearch    = $('#search').val();
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
      "createdRow": function (row, data, index) {
        $(row).attr("status", data.color || 'default');
      },
			columns: [
        {
          "data" : "edit",
          "orderable" : false,
          render : function(data, type, row) {
            return ""+
            "<div class='btn btn-primary btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Lihat Detail' onclick='lihatDetail(this,"+(JSON.stringify(row)).split("'").join("&#39;")+")' data-message='"+row.add+"' data-tipe='header'><i class='fa fa-eye'></i></div>"+
            "<div class='btn btn-success btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Tambah Detail' onclick='tambahDetail(this,"+(JSON.stringify(row)).split("'").join("&#39;")+")' data-message='"+row.add+"'><i class='fa fa-plus'></i></div>"+
            "<div class='btn btn-danger btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Hapus' onclick='deleteOrder(this,"+(JSON.stringify(row)).split("'").join("&#39;")+")' data-message='"+row.delete+"' data-tipe='header'><i class='fa fa-trash'></i></div>"+
            "<div class='btn-group no-margin-action'><button type='button' class='btn btn-primary dropdown-toggle btn-xs no-margin-action' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>A <span class='caret'></span></button>"+
            "<ul class='dropdown-menu'>"+
            "<li><a href='#' onclick='closingSO(this,"+(JSON.stringify(row)).split("'").join("&#39;")+")' data-message='"+row.closingso+"'>Closing SO</a></li>"+
            "</ul></div>";
          }
        },
				{
          "data" : "tglpickinglist",
          "className": "menufilter numberfilter"
        },
				{
          "data" : "nopickinglist",
          "className": "menufilter textfilter"
        },
				{
          "data" : "tokonama",
          "className": "menufilter textfilter"
        },
        {
          "data" : "tglso",
          "className": "menufilter numberfilter"
        },
        {
          "data" : "noso",
          "className": "menufilter textfilter"
        },
				{
          "data" : "noaccpiutang",
          "className": "menufilter numberfilter"
        },
				{
          "data" : "salesmannama",
          "className": "menufilter textfilter"
        },
				{
          "data" : "rpaccpiutang",
          "className": "text-right menufilter numberfilter"
        },
				{
          "data" : "statusajuanhrg11",
          "className": "menufilter textfilter"
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
				tipe: {
					name: "Tipe",
					type: 'select',
					options: {1: 'Find', 2: 'Filter'},
					selected: 1
				},
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
				tipe: {
					name: "Tipe",
					type: 'select',
					options: {1: 'Find', 2: 'Filter'},
					selected: 1
				},
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

    $('.tgl, #search').change(function(){
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
      order     : [[ 1, 'asc' ]],
      rowCallback: function(row, data, index) {
        console.log(data[7]);
        if(data[7]){
          //birumuda
          $(row).addClass('abang');
        }
      },
      columns   : [
        {
          "orderable" : false,
        },
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
      ],
		});

    table.on('select', function ( e, dt, type, indexes ){
      table_index = indexes;
      fokus       = 'header';
			var rowData = table.rows( indexes ).data().toArray();
      console.log(rowData[0].id);
			$.ajaxSetup({
				headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
			});

			$.ajax({
				type: 'POST',
				data: {id: rowData[0].id},
				dataType: "json",
				url: '{{route("salesorder.detail.data")}}',
				success: function(data){
					console.log(data.node);
					table2.clear();
					for (var i = 0; i < data.node.length; i++) {
						table2.row.add([
              '<div class="btn btn-primary btn-xs no-margin-action" data-toggle="tooltip" data-placement="bottom" title="Lihat Detail" onclick="lihatDetail(this,'+data.node[i][5]+')" data-tipe="detail"><i class="fa fa-eye"></i></div>'+
              '<div class="btn btn-danger btn-xs no-margin-action" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="deleteOrder(this,'+data.node[i][5]+')" data-message="'+data.node[i][6]+'" data-tipe="detail"><i class="fa fa-trash"></i></div>',
							data.node[i][0],
							data.node[i][1],
							data.node[i][2],
							data.node[i][3],
							data.node[i][4],
              data.node[i][5],
              data.node[i][8],
						]);
					}
					table2.draw();
				},
				error: function(data){
					console.log(data);
				}
			});
		});
    table2.on('select', function ( e, dt, type, indexes ){
      fokus        = 'detail';
      table2_index = indexes;
    });
    tabledatadetail = $('#tblDblClick').DataTable({
      dom   : 'lrtp',
      paging: false,
		});

    $('#table1 tbody').on('dblclick', 'tr', function(){
			var data = table.row(this).data();

      $.ajax({
        type: 'GET',
				url: '{{route("salesorder.header")}}',
				data: {id: data.id},
				dataType: "json",
        success: function(data){
          tabledatadetail.clear();
          tabledatadetail.rows.add([
            {'0':'<div class="text-right">1.</div>', '1':'C1', '2':data.c1},
            {'0':'<div class="text-right">2.</div>', '1':'C2', '2':data.c2},
            {'0':'<div class="text-right">3.</div>', '1':'No. SO', '2':data.noso},
            {'0':'<div class="text-right">4.</div>', '1':'Tgl. SO', '2':data.tglso},
            {'0':'<div class="text-right">5.</div>', '1':'No. Picking List', '2':data.nopickinglist},
            {'0':'<div class="text-right">6.</div>', '1':'Tgl. Picking List', '2':data.tglpickinglist},
            {'0':'<div class="text-right">7.</div>', '1':'Toko', '2':data.tokonama},
            {'0':'<div class="text-right">8.</div>', '1':'Alamat', '2':data.tokoalamat},
            {'0':'<div class="text-right">9.</div>', '1':'Kota', '2':data.tokokota},
            {'0':'<div class="text-right">10.</div>', '1':'Kecamatan', '2':data.kecamatan},
            {'0':'<div class="text-right">11.</div>', '1':'WILID', '2':data.wilid},
            {'0':'<div class="text-right">12.</div>', '1':'Toko ID', '2':data.tokoid},
            {'0':'<div class="text-right">13.</div>', '1':'Status Toko', '2':data.statustoko},
            {'0':'<div class="text-right">14.</div>', '1':'Toko ID Warisan', '2':data.tokoidwarisan},
            {'0':'<div class="text-right">15.</div>', '1':'Salesman', '2':data.salesmannama},
            {'0':'<div class="text-right">16.</div>', '1':'Expedisi', '2':data.expedisinama},
            {'0':'<div class="text-right">17.</div>', '1':'Tipe Transaksi', '2':data.tipetransaksi},
            {'0':'<div class="text-right">18.</div>', '1':'Tempo Nota', '2':data.temponota},
            {'0':'<div class="text-right">19.</div>', '1':'Tempo Kirim', '2':data.tempokirim},
            {'0':'<div class="text-right">20.</div>', '1':'Tempo Salesman', '2':data.temposalesman},
            {'0':'<div class="text-right">21.</div>', '1':'No. ACC Piutang', '2':data.noaccpiutang},
            {'0':'<div class="text-right">22.</div>', '1':'Nama PKP', '2':data.namapkp},
            {'0':'<div class="text-right">23.</div>', '1':'Tgl. ACC Piutang', '2':data.tglaccpiutang},
            {'0':'<div class="text-right">24.</div>', '1':'Rp. ACC Piutang', '2':data.rpaccpiutang},
            {'0':'<div class="text-right">25.</div>', '1':'Rp. Saldo Piutang', '2':data.rpsaldopiutang},
            {'0':'<div class="text-right">26.</div>', '1':'Rp. Saldo Overdue', '2':data.rpsaldooverdue},
            {'0':'<div class="text-right">27.</div>', '1':'Rp. SO ACC Proses', '2':data.rpsoaccproses},
            {'0':'<div class="text-right">28.</div>', '1':'Rp. GIT', '2':data.rpgit},
            {'0':'<div class="text-right">29.</div>', '1':'Catatan Penjualan', '2':data.catatanpenjualan},
            {'0':'<div class="text-right">30.</div>', '1':'Catatan Pembayaran', '2':data.catatanpembayaran},
            {'0':'<div class="text-right">31.</div>', '1':'Catatan Pengiriman', '2':data.catatanpengiriman},
            {'0':'<div class="text-right">32.</div>', '1':'Print', '2':data.print},
            {'0':'<div class="text-right">33.</div>', '1':'Tgl. Print Picking List', '2':data.tglprintpickinglist},
            {'0':'<div class="text-right">34.</div>', '1':'Status Approval Overdue', '2':data.statusapprovaloverdue},
            {'0':'<div class="text-right">35.</div>', '1':'Tgl. Terima SO ke Piutang', '2':data.tglterimapilpiutang},
            {'0':'<div class="text-right">36.</div>', '1':'Status Ajuan Harga 11', '2':data.statusajuanhrg11},
            {'0':'<div class="text-right">37.</div>', '1':'Last Updated By', '2':data.lastupdatedby},
            {'0':'<div class="text-right">38.</div>', '1':'Last Updated On', '2':data.lastupdatedon},
          ]);
          tabledatadetail.draw();
          $('#labelDblClick').html('Data Sales Order');
          $('#modalDataDblClick').modal('show');
        }
      });
		});

    $('#table2 tbody').on('dblclick', 'tr', function(){
			var data = table2.row(this).data();

			$.ajax({
				type: 'POST',
				url: '{{route("salesorder.detail.detail")}}',
				data: {id: data[6]},
				dataType: "json",
				success: function(data){
          tabledatadetail.clear();
          tabledatadetail.rows.add([
						{'0':'<div class="text-right">1.</div>', '1':'Barang', '2':data.namabarang},
						{'0':'<div class="text-right">2.</div>', '1':'Satuan', '2':data.satuan},
						{'0':'<div class="text-right">3.</div>', '1':'Qty SO', '2':data.qtyso},
						{'0':'<div class="text-right">4.</div>', '1':'Qty SO ACC', '2':data.qtysoacc},
						{'0':'<div class="text-right">5.</div>', '1':'Hrg Sat Brutto', '2':data.hrgsatuanbrutto},
						{'0':'<div class="text-right">6.</div>', '1':'Disc 1', '2':data.disc1},
						{'0':'<div class="text-right">7.</div>', '1':'Hrg Setelah Disc 1', '2':data.hrgdisc1},
						{'0':'<div class="text-right">8.</div>', '1':'Disc 2', '2':data.disc2},
						{'0':'<div class="text-right">9.</div>', '1':'Hrg Setelah Disc 2', '2':data.hrgdisc2},
						{'0':'<div class="text-right">10.</div>', '1':'PPN', '2':data.ppn},
						{'0':'<div class="text-right">11.</div>', '1':'Hrg Sat Netto', '2':data.hrgsatuannetto},
						{'0':'<div class="text-right">12.</div>', '1':'Hrg Total Netto', '2':data.hrgtotalnetto},
						{'0':'<div class="text-right">13.</div>', '1':'Hrg AGR', '2':data.hrgbmk},
						{'0':'<div class="text-right">14.</div>', '1':'No ACC 11', '2':data.noacc11},
						{'0':'<div class="text-right">15.</div>', '1':'Catatan', '2':data.catatan},
						{'0':'<div class="text-right">16.</div>', '1':'Qty Stock Gudang', '2':data.qtystockgudang},
						{'0':'<div class="text-right">17.</div>', '1':'Komisi Khusus 11', '2':data.komisikhusus11},
						{'0':'<div class="text-right">18.</div>', '1':'Link Pembelian', '2':data.linkpembelian},
            {'0':'<div class="text-right">19.</div>', '1':'Last Updated By', '2':data.lastupdatedby},
						{'0':'<div class="text-right">20.</div>', '1':'Last Updated On', '2':data.lastupdatedon},
					]);
					tabledatadetail.draw();
          $('#labelDblClick').html('Data Sales Order Detail');
          $('#modalDataDblClick').modal('show');
				},
			});
		});

    $('.tbodySelect').on('click', 'tr', function(){
      $('.selected').removeClass('selected');
      $(this).addClass("selected");
    });

    // Cek Barang Order di hari yang sama dan toko sama
    $('#barangid').on('change input', function(){
      if($('#barangid').val() != '') {
        $.ajax({
          type: 'GET',
          url : '{{route("orderpenjualan.cekbarangopj")}}',
          data: {
            id     : $('#orderpenjualanid').val(),
            stockid: $('#barangid').val(),
          },
          success: function(respon){
            if(respon) {
              swal({
                  title: "Perhatian!",
                  text : "Sudah ada order <br> [ "+respon.namabarang+" ] <br> di "+respon.tglpickinglist+" : "+respon.nopickinglist+" <br> untuk toko [ "+respon.namatoko+" ]!<br><br>Anda yakin mau order dengan tanggal yg sama?",
                  type : "warning",
                  html : true,
                  showCancelButton  : true,
                  confirmButtonColor: '#DD6B55',
                  confirmButtonText : "Ya",
                  cancelButtonText  : "Tidak",
                  closeOnConfirm    : true,
                  closeOnCancel     : true
              },
              function(isConfirm) {
                  if (!isConfirm) {
                    $('#modalDetail').find('input:not(#orderpenjualanid, #tipetransaksidetail, #statustokodetail, #tokoiddetail)').val('');
                    $('#modalDetail').find('#discsales').val(discsales);
                    $('#modalDetail').find('#ppn').val(0);
                    $('#modalDetail').find('#disc1').val(0);
                    $('#modalDetail').find('#disc2').val(0);
                    $('#modalDetail').find('#qtystockgudang').val(0);
                  }
              });
            }
          }
        });
      }
    });

    $("#hrgpricelist, #discsales").on('change keyup',function() {
      var hrgpcs       = parseInt($("#hrgpricelist").val());
      var hrgpricelist = hrgpcs || 0;
      var discsales    = parseFloat($("#discsales").val());
      var hrgdisc      = hrgpricelist;

      $("#hrgpricelist").val(hrgpricelist);
      if (hrgpcs !== null && !isNaN(hrgpcs) && hrgpcs > 0) {
        if(discsales > 0) {
          hrgdisc = hrgpricelist-(hrgpricelist*discsales/100);
        }
        $('#hrgsatuanbrutto').val(parseInt(hrgdisc) || 0);
      }
    });

    $("#hrgsatuanbrutto").on('keyup',function() {
      var hrgpcs       = parseInt($("#hrgpricelist").val());
      var hrgpricelist = hrgpcs || 0;
      var hrgsatuanbrutto = parseInt($("#hrgsatuanbrutto").val());
      var discsales       = 0;

      $("#hrgpricelist").val(hrgpricelist);
      if (hrgpcs !== null && !isNaN(hrgpcs) && hrgpcs > 0) {
        discsales = ((hrgpricelist-hrgsatuanbrutto)/hrgpricelist)*100;
        $('#discsales').val((discsales || 0).toFixed(1));
      }
    });

    $('.hitungnetto').on('keyup', function(){
      $('#qtysoacc').val($('#qtyso').val());
      hitung_netto();
    });

    // $('#qtyso').on('focusout', function(){
    //   var teks = "Harga Pricelist Rp. 000";
    //   if ($('#hrgpricelist').val() !='') {
    //     teks = "Harga Pricelist Rp. "+ currencyFormat($('#hrgpricelist').val());
    //   }

    //   teks += "<br> ------";
    //   if ($('#riwayatorder').val() !='') {
    //     var data = JSON.parse($('#riwayatorder').val());
    //     teks += "<br> No Nota "+ data.nonota +"<br>Tgl. Nota "+ data.tglnota +"<br>Harga Netto Rp. "+ currencyFormat(data.hrgsatuannetto);
    //   }else{
    //     teks += "<br> No Nota -- <br>Tgl. Nota -- <br>Harga Netto Rp. 000";
    //   }

    //   swal({
    //     title: "Info",
    //     text: teks,
    //     type: "info",
    //     html: true
    //   },function(){
    //     setTimeout(function(){
    //       $('#hrgsatuanbrutto').focus();
    //     },0);
    //   });
    // });
 
    $('#formDetail').submit(function(e){
      e.preventDefault();
      @cannot('salesorder.detail.tambah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
        //=============== DIREMARKS HALIM. (REVISI / CHANGE REQUESTED) - DIGANTI DIBAWAHNYA ======////
        // tipe_edit = 'tambah';
        
        // var kdbarang = $("#modalDetail #barang").val();
        // var kdbarangSub = $("#modalDetail #kodebarang").val().substring(0,2);
        // var hrgnetto = parseInt($("#modalDetail #hrgsatuannetto").val());
        // var sttstoko = $('#modalDetail #statustokodetail').val();
        // var tipetran = $("#modalDetail #tipetransaksidetail").val().substring(0,1);
        // var pricelist = parseInt((isNaN($('#modalDetail #hrgpricelist').val()) ? 0 : $('#modalDetail #hrgpricelist').val()));
        // var kategori = parseInt((isNaN($('#modalDetail #kategoriPenjualan').val()) ? 0 : $('#modalDetail #kategoriPenjualan').val()));
        // var hrgb = 0, hrgm = 0, hrgk = 0;
        // if ($('#arrhrgbmk').val() !='') {
        //   var data = JSON.parse($('#arrhrgbmk').val());
        //   hrgb = data.hrgb;
        //   hrgm = data.hrgm;
        //   hrgk = data.hrgk;
        // }

        // if (sttstoko == 'K' && hrgnetto < hrgk && kdbarangSub == 'FE' && (kdbarang.includes('HCA') || kdbarang.includes('THM')))
        // {
        //   swal({
        //       title: "Konfirmasi!",
        //       text : "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + hrgk.toLocaleString("id-ID") + ". Anda yakin?",
        //       type : "info",
        //       showCancelButton  : true,
        //       confirmButtonColor: '#DD6B55',
        //       confirmButtonText : "Ya",
        //       cancelButtonText  : "Tidak",
        //       closeOnConfirm    : true,
        //       closeOnCancel     : true
        //   },
        //   function(isConfirm) {
        //     if (isConfirm){
        //       $("#qtyso").val(0);
        //       $("#catatandetail").val("Tolak Otomatis");
        //       checkSaveDetail();
        //     }else{
        //       $("#hrgsatuanbrutto").focus();
        //       $("#hrgsatuanbrutto").select();
        //     }
        //   });
        // }
        // else if (sttstoko == 'K' && hrgnetto < hrgk && kategori == 5 && tipetran == 'T')
        // {
        //   swal({
        //       title: "Konfirmasi!",
        //       text : "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + hrgk.toLocaleString("id-ID") + ". Anda yakin?",
        //       type : "info",
        //       showCancelButton  : true,
        //       confirmButtonColor: '#DD6B55',
        //       confirmButtonText : "Ya",
        //       cancelButtonText  : "Tidak",
        //       closeOnConfirm    : true,
        //       closeOnCancel     : true
        //   },
        //   function(isConfirm) {
        //     if (isConfirm){
        //       $("#qtyso").val(0);
        //       $("#catatandetail").val("Tolak Otomatis");
        //       checkSaveDetail();
        //     }else{
        //       $("#hrgsatuanbrutto").focus();
        //       $("#hrgsatuanbrutto").select();
        //     }
        //   });
        // }
        // else if (sttstoko == 'M' && hrgnetto < hrgm && kategori == 5 && tipetran == 'T')
        // {
        //   swal({
        //       title: "Konfirmasi!",
        //       text : "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + hrgm.toLocaleString("id-ID") + ". Anda yakin?",
        //       type : "info",
        //       showCancelButton  : true,
        //       confirmButtonColor: '#DD6B55',
        //       confirmButtonText : "Ya",
        //       cancelButtonText  : "Tidak",
        //       closeOnConfirm    : true,
        //       closeOnCancel     : true
        //   },
        //   function(isConfirm) {
        //     if (isConfirm){
        //       $("#qtyso").val(0);
        //       $("#catatandetail").val("Tolak Otomatis");
        //       checkSaveDetail();
        //     }else{
        //       $("#hrgsatuanbrutto").focus();
        //       $("#hrgsatuanbrutto").select();
        //     }
        //   });
        // }
        // else if (sttstoko == 'B' && hrgnetto < hrgb && kategori == 5 && tipetran == 'T')
        // {
        //   swal({
        //       title: "Konfirmasi!",
        //       text : "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + hrgb.toLocaleString("id-ID") + ". Anda yakin?",
        //       type : "info",
        //       showCancelButton  : true,
        //       confirmButtonColor: '#DD6B55',
        //       confirmButtonText : "Ya",
        //       cancelButtonText  : "Tidak",
        //       closeOnConfirm    : true,
        //       closeOnCancel     : true
        //   },
        //   function(isConfirm) {
        //     if (isConfirm){
        //       $("#qtyso").val(0);
        //       $("#catatandetail").val("Tolak Otomatis");
        //       checkSaveDetail();
        //     }else{
        //       $("#hrgsatuanbrutto").focus();
        //       $("#hrgsatuanbrutto").select();
        //     }
        //   });
        // }
        // else if (sttstoko == 'K' && tipetran == 'K' && kdbarangSub != 'FE' && hrgnetto < pricelist )
        // {
        //   swal({
        //       title: "Konfirmasi!",
        //       text : "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + pricelist.toLocaleString("id-ID") + ". Anda yakin?",
        //       type : "info",
        //       showCancelButton  : true,
        //       confirmButtonColor: '#DD6B55',
        //       confirmButtonText : "Ya",
        //       cancelButtonText  : "Tidak",
        //       closeOnConfirm    : true,
        //       closeOnCancel     : true
        //   },
        //   function(isConfirm) {
        //     if (isConfirm){
        //       $("#qtyso").val(0);
        //       $("#catatandetail").val("Tolak Otomatis");
        //       checkSaveDetail();
        //     }else{
        //       $("#hrgsatuanbrutto").focus();
        //       $("#hrgsatuanbrutto").select();
        //     }
        //   });
        // }
        // else
        // {
        //   if(parseInt($('#hrgsatuannetto').val()) < parseInt($('#hrgbmk').val())) {
        //     swal({
        //         title: "Perhatian!",
        //         text : "Harga satuan Netto lebih rendah dari harga AGR. Anda yakin?",
        //         type : "warning",
        //         showCancelButton  : true,
        //         confirmButtonColor: '#DD6B55',
        //         confirmButtonText : "Ya",
        //         cancelButtonText  : "Tidak",
        //         closeOnConfirm    : true,
        //         closeOnCancel     : true
        //     },
        //     function(isConfirm) {
        //         if (isConfirm) {
        //           checkSaveDetail();
        //         }else{
        //           // ajax_formdetail(false);
        //           $("#hrgsatuanbrutto").val(0);
        //           $("#hrgsatuanbrutto").focus();
        //           $("#hrgsatuanbrutto").select();
        //         }
        //     });
        //   }else{
        //     checkSaveDetail();
        //   }
        // }
        
        //=============GANTINYA DIMARI GAN..============
        tipe_edit = 'tambah';
        var hrgnetto = parseInt($("#modalDetail #hrgsatuannetto").val());
        var hrgbrutto = parseInt($("#modalDetail #hrgsatuanbrutto").val());
        var sttstoko = $('#modalDetail #statustokodetail').val();
        var tipetran = $("#modalDetail #tipetransaksidetail").val().substring(0,1);
        var pricelist = parseInt((isNaN($('#modalDetail #hrgpricelist').val()) ? 0 : $('#modalDetail #hrgpricelist').val()));
        var kategori = parseInt((isNaN($('#modalDetail #kategoriPenjualan').val()) ? 0 : $('#modalDetail #kategoriPenjualan').val()));
        var hrgb = 0, hrgm = 0, hrgk = 0;
        if ($('#arrhrgbmk').val() !='') {
          var data = JSON.parse($('#arrhrgbmk').val());
          hrgb = data.hrgb;
          hrgm = data.hrgm;
          hrgk = data.hrgk;
        }

        if ((sttstoko == "K" && tipetran == "K" && hrgnetto < hrgk) || (tipetran == "T" && hrgnetto < hrgb)){
          swal({
              title: "Konfirmasi!",
              text : "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + hrgk.toLocaleString("id-ID") + ". Tetap melanjutkan?",
              type : "info",
              showCancelButton  : true,
              confirmButtonColor: '#DD6B55',
              confirmButtonText : "Ya",
              cancelButtonText  : "Tidak",
              closeOnConfirm    : true,
              closeOnCancel     : true
          },
          function(isConfirm) {
            if (isConfirm){
              $("#qtyso").val(0);
              $("#qtysoacc").val(0);
              $("#catatandetail").val("TOLAK OTOMATIS");
              checkSaveDetail();
            }else{
              $("#hrgsatuanbrutto").val(0);
              $("#hrgsatuanbrutto").focus();
            }
          });
        }else if (sttstoko == "K" && tipetran == "T" && hrgnetto > hrgm && hrgnetto < hrgk){
          swal({
              title: "Konfirmasi!",
              text : "Harga input Rp. "+ hrgnetto.toLocaleString("id-ID") +" lebih rendah dari standar harga terendah Rp. " + hrgk.toLocaleString("id-ID") + ". Tetap melanjutkan?",
              type : "info",
              showCancelButton  : true,
              confirmButtonColor: '#DD6B55',
              confirmButtonText : "Ya",
              cancelButtonText  : "Tidak",
              closeOnConfirm    : true,
              closeOnCancel     : true
          },
          function(isConfirm) {
            if (isConfirm){
              $("#qtyso").val(0);
              $("#qtysoacc").val(0);
              $("#catatandetail").val("ACC OTOMATIS");
              checkSaveDetail();
            }else{
              $("#hrgsatuanbrutto").val(0);
              $("#hrgsatuanbrutto").focus();
            }
          });
        }else{
          if(parseInt($('#hrgsatuannetto').val()) < parseInt($('#hrgbmk').val())) {
            swal({
                title: "Perhatian!",
                text : "Harga satuan Netto lebih rendah dari harga Pricelist. Anda yakin?",
                type : "warning",
                showCancelButton  : true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText : "Ya",
                cancelButtonText  : "Tidak",
                closeOnConfirm    : true,
                closeOnCancel     : true
            },
            function(isConfirm) {
                if (isConfirm) {
                  checkSaveDetail();
                }else{
                  // ajax_formdetail(false);
                  $("#hrgsatuanbrutto").val(0);
                  $("#hrgsatuanbrutto").focus();
                }
            });
          }else{
            checkSaveDetail();
          }
        }        
      @endcannot
    });

    function checkSaveDetail(){
      $.ajax({
        type   : 'GET',
        url    : '{{route("orderpenjualan.cekbarang")}}',
        data   : {
          id     : $('#orderpenjualanid').val(),
          stockid: $('#barangid').val(),
        },
        success: function(respon){
          //check
          if(respon) {
            swal({
                title: "Perhatian!",
                text : "Barang sudah ada di Header yang sama, apakah ini barang bonus?",
                type : "warning",
                showCancelButton  : true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText : "Ya",
                cancelButtonText  : "Tidak",
                closeOnConfirm    : true,
                closeOnCancel     : true
            },
            function(isConfirm) {
                if (isConfirm) {
                  ajax_formdetail(true);
                } else {
                  ajax_formdetail(false);
                }
            });
          }else{
            ajax_formdetail(true);
          }
        }
      });
    }
    

    //di remarks halim
    //
    // $('#formDetail').submit(function(e){
    //   e.preventDefault();
      
    //   @cannot('salesorder.detail.tambah')
    //     swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    //   @else
    //   tipe_edit = 'tambah';

    //   // Cek Barang
    //   if(parseInt($('#hrgsatuannetto').val()) < parseInt($('#hrgbmk').val())) {
    //     swal({
    //         title: "Perhatian!",
    //         text : "Harga satuan Netto lebih rendah dari harga AGR. Anda yakin?",
    //         type : "warning",
    //         showCancelButton  : true,
    //         confirmButtonColor: '#DD6B55',
    //         confirmButtonText : "Ya",
    //         cancelButtonText  : "Tidak",
    //         closeOnConfirm    : true,
    //         closeOnCancel     : true
    //     },
    //     function(isConfirm) {
    //         if (isConfirm) {
    //           $.ajax({
    //             type   : 'GET',
    //             url    : '{{route("orderpenjualan.cekbarang")}}',
    //             data   : {
    //               id     : $('#orderpenjualanid').val(),
    //               stockid: $('#barangid').val(),
    //             },
    //             success: function(respon){
    //               if(respon) {
    //                 swal({
    //                     title: "Perhatian!",
    //                     text : "Barang sudah ada di Header yang sama, apakah ini barang bonus?",
    //                     type : "warning",
    //                     showCancelButton  : true,
    //                     confirmButtonColor: '#DD6B55',
    //                     confirmButtonText : "Ya",
    //                     cancelButtonText  : "Tidak",
    //                     closeOnConfirm    : true,
    //                     closeOnCancel     : true
    //                 },
    //                 function(isConfirm) {
    //                     if (isConfirm) {
    //                       ajax_formdetail(true);
    //                     } else {
    //                       ajax_formdetail(false);
    //                     }
    //                 });
    //               }else{
    //                 ajax_formdetail(true);
    //               }
    //             }
    //           });
    //         }else{
    //           ajax_formdetail(false);
    //         }
    //     });
    //   }else{
    //     $.ajax({
    //       type   : 'GET',
    //       url    : '{{route("orderpenjualan.cekbarang")}}',
    //       data   : {
    //         id     : $('#orderpenjualanid').val(),
    //         stockid: $('#barangid').val(),
    //       },
    //       success: function(respon){
    //         if(respon) {
    //           swal({
    //               title: "Perhatian!",
    //               text : "Barang sudah ada di Header yang sama, apakah ini barang bonus?",
    //               type : "warning",
    //               showCancelButton  : true,
    //               confirmButtonColor: '#DD6B55',
    //               confirmButtonText : "Ya",
    //               cancelButtonText  : "Tidak",
    //               closeOnConfirm    : true,
    //               closeOnCancel     : true
    //           },
    //           function(isConfirm) {
    //               if (isConfirm) {
    //                 ajax_formdetail(true);
    //               } else {
    //                 ajax_formdetail(false);
    //               }
    //           });
    //         }else{
    //           ajax_formdetail(true);
    //         }
    //       }
    //     });
    //   }
    //   @endcannot
    // });

    function ajax_formdetail(lanjut){
      var discsales = $("#discsales").val();
      if(lanjut == true) {
        console.log(lanjut);
        showLoading(true, () => {
          $.ajax({
            type: 'POST',
            url: '{{route("salesorder.detail.tambah")}}',
            dataType: 'json',
            data: {
              orderpenjualanid : $('#modalDetail #orderpenjualanid').val(),
              stockid          : $('#modalDetail #barangid').val(),
              qtyso            : $('#modalDetail #qtyso').val(),
              qtysoacc         : $('#modalDetail #qtysoacc').val(),
              hrgbmk           : $('#modalDetail #hrgbmk').val(),
              hrgsatuanbrutto  : $('#modalDetail #hrgsatuanbrutto').val(),
              disc1            : $('#modalDetail #disc1').val(),
              disc2            : $('#modalDetail #disc2').val(),
              ppn              : $('#modalDetail #ppn').val(),
              hrgsatuannetto   : $('#modalDetail #hrgsatuannetto').val(),
              qtystockgudang   : $('#modalDetail #qtystockgudang').val(),
              catatandetail    : $('#modalDetail #catatandetail').val(),
            },
            success: function(data){
              showLoading(false);

              if(data.cek_opjd) {
                swal('Perhatian!', data.cek_opjd, 'warning');
              }

              //$('#modalDetail').modal('hide');
              $('#modalDetail').find('input:not(#orderpenjualanid, #tipetransaksidetail, #statustokodetail, #tokoiddetail)').val('');
              $('#modalDetail').find('#discsales').val(discsales);
              $('#modalDetail').find('#ppn').val(0);
              $('#modalDetail').find('#disc1').val(0);
              $('#modalDetail').find('#disc2').val(0);
              $('#modalDetail').find('#qtystockgudang').val(0);
              $('#modalDetail').find('#catatandetail').val('');

              //window.location.reload();
              table.ajax.reload(null, true);
              tipe_edit = null;
              setTimeout(function(){
                table.row(0).select();
              },1000);
            },
            error: () => showLoading(false)
          });
        });

      }else{
        $('#modalDetail').modal('hide');
        $('#modalDetail').find('input').val('');
        $('#modalDetail').find('#discsales').val(discsales);
        $('#modalDetail').find('#ppn').val(0);
        $('#modalDetail').find('#disc1').val(0);
        $('#modalDetail').find('#disc2').val(0);
        $('#modalDetail').find('#qtystockgudang').val(0);
        $('#modalDetail').find('#catatandetail').val('');
      }
    }
   
    $('#formKewenangan').submit(function(e){
      e.preventDefault();
      tipe_edit = 'hapus';
      $.ajax({
        type: 'POST',
        url: '{{route("salesorder.kewenangan")}}',
        data: {
          username    : $('#modalKewenangan #uxserKewenangan').val().trim().toUpperCase(), 
          password    : $('#modalKewenangan #pxassKewenangan').val(),
          orderid     : $('#modalKewenangan #orderIdHapus').val(),
          tipe        : $('#modalKewenangan #tipe').val(),
          permission  : 'salesorder.hapus',
        },
        dataType: "json",
        success: function(data){
          $('#modalKewenangan #uxserKewenangan').val('').change();
          $('#modalKewenangan #pxassKewenangan').val('').change();

          if(data.success){
            $('#modalKewenangan').modal('hide');
            swal('Sukses!', 'Data berhasil dihapus','success');
            //window.location.reload();
            table.ajax.reload(null, true);
            tipe_edit = null;
            setTimeout(function(){
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

  function lihatDetail(e, data){
    var tipe = $(e).data('tipe');
    if (tipe == 'header') {
      $.ajax({
        type: 'GET',
        url: '{{route("salesorder.header")}}',
        data: {id: data.id},
        dataType: "json",
        success: function(data){
          tabledatadetail.clear();
          tabledatadetail.rows.add([
            {'0':'<div class="text-right">1.</div>', '1':'C1', '2':data.c1},
            {'0':'<div class="text-right">2.</div>', '1':'C2', '2':data.c2},
            {'0':'<div class="text-right">3.</div>', '1':'No. SO', '2':data.noso},
            {'0':'<div class="text-right">4.</div>', '1':'Tgl. SO', '2':data.tglso},
            {'0':'<div class="text-right">5.</div>', '1':'No. Picking List', '2':data.nopickinglist},
            {'0':'<div class="text-right">6.</div>', '1':'Tgl. Picking List', '2':data.tglpickinglist},
            {'0':'<div class="text-right">7.</div>', '1':'Toko', '2':data.tokonama},
            {'0':'<div class="text-right">8.</div>', '1':'Alamat', '2':data.tokoalamat},
            {'0':'<div class="text-right">9.</div>', '1':'Kota', '2':data.tokokota},
            {'0':'<div class="text-right">10.</div>', '1':'Kecamatan', '2':data.kecamatan},
            {'0':'<div class="text-right">11.</div>', '1':'WILID', '2':data.wilid},
            {'0':'<div class="text-right">12.</div>', '1':'Toko ID', '2':data.tokoid},
            {'0':'<div class="text-right">13.</div>', '1':'Status Toko', '2':data.statustoko},
            {'0':'<div class="text-right">14.</div>', '1':'Toko ID Warisan', '2':data.tokoidwarisan},
            {'0':'<div class="text-right">15.</div>', '1':'Salesman', '2':data.salesmannama},
            {'0':'<div class="text-right">16.</div>', '1':'Expedisi', '2':data.expedisinama},
            {'0':'<div class="text-right">17.</div>', '1':'Tipe Transaksi', '2':data.tipetransaksi},
            {'0':'<div class="text-right">18.</div>', '1':'Tempo Nota', '2':data.temponota},
            {'0':'<div class="text-right">19.</div>', '1':'Tempo Kirim', '2':data.tempokirim},
            {'0':'<div class="text-right">20.</div>', '1':'Tempo Salesman', '2':data.temposalesman},
            {'0':'<div class="text-right">21.</div>', '1':'No. ACC Piutang', '2':data.noaccpiutang},
            {'0':'<div class="text-right">22.</div>', '1':'Nama PKP', '2':data.namapkp},
            {'0':'<div class="text-right">23.</div>', '1':'Tgl. ACC Piutang', '2':data.tglaccpiutang},
            {'0':'<div class="text-right">24.</div>', '1':'Rp. ACC Piutang', '2':data.rpaccpiutang},
            {'0':'<div class="text-right">25.</div>', '1':'Rp. Saldo Piutang', '2':data.rpsaldopiutang},
            {'0':'<div class="text-right">26.</div>', '1':'Rp. Saldo Overdue', '2':data.rpsaldooverdue},
            {'0':'<div class="text-right">27.</div>', '1':'Rp. SO ACC Proses', '2':data.rpsoaccproses},
            {'0':'<div class="text-right">28.</div>', '1':'Rp. GIT', '2':data.rpgit},
            {'0':'<div class="text-right">29.</div>', '1':'Catatan Penjualan', '2':data.catatanpenjualan},
            {'0':'<div class="text-right">30.</div>', '1':'Catatan Pembayaran', '2':data.catatanpembayaran},
            {'0':'<div class="text-right">31.</div>', '1':'Catatan Pengiriman', '2':data.catatanpengiriman},
            {'0':'<div class="text-right">32.</div>', '1':'Print', '2':data.print},
            {'0':'<div class="text-right">33.</div>', '1':'Tgl. Print Picking List', '2':data.tglprintpickinglist},
            {'0':'<div class="text-right">34.</div>', '1':'Status Approval Overdue', '2':data.statusapprovaloverdue},
            {'0':'<div class="text-right">35.</div>', '1':'Tgl. Terima SO ke Piutang', '2':data.tglterimapilpiutang},
            {'0':'<div class="text-right">36.</div>', '1':'Status Ajuan Harga 11', '2':data.statusajuanhrg11},
            {'0':'<div class="text-right">37.</div>', '1':'Last Updated By', '2':data.lastupdatedby},
            {'0':'<div class="text-right">38.</div>', '1':'Last Updated On', '2':data.lastupdatedon},
          ]);
          tabledatadetail.draw();
          $('#labelDblClick').html('Data Sales Order');
          $('#modalDataDblClick').modal('show');
        }
      });
    }else {
      $.ajax({
        type: 'POST',
        url: '{{route("salesorder.detail.detail")}}',
        data: {id: data},
        dataType: "json",
        success: function(data){
          tabledatadetail.clear();
          tabledatadetail.rows.add([
            {'0':'<div class="text-right">1.</div>', '1':'Barang', '2':data.namabarang},
            {'0':'<div class="text-right">2.</div>', '1':'Satuan', '2':data.satuan},
            {'0':'<div class="text-right">3.</div>', '1':'Qty SO', '2':data.qtyso},
            {'0':'<div class="text-right">4.</div>', '1':'Qty SO ACC', '2':data.qtysoacc},
            {'0':'<div class="text-right">5.</div>', '1':'Hrg Sat Brutto', '2':data.hrgsatuanbrutto},
            {'0':'<div class="text-right">6.</div>', '1':'Disc 1', '2':data.disc1},
            {'0':'<div class="text-right">7.</div>', '1':'Hrg Setelah Disc 1', '2':data.hrgdisc1},
            {'0':'<div class="text-right">8.</div>', '1':'Disc 2', '2':data.disc2},
            {'0':'<div class="text-right">9.</div>', '1':'Hrg Setelah Disc 2', '2':data.hrgdisc2},
            {'0':'<div class="text-right">10.</div>', '1':'PPN', '2':data.ppn},
            {'0':'<div class="text-right">11.</div>', '1':'Hrg Sat Netto', '2':data.hrgsatuannetto},
            {'0':'<div class="text-right">12.</div>', '1':'Hrg Total Netto', '2':data.hrgtotalnetto},
            {'0':'<div class="text-right">13.</div>', '1':'Hrg Pricelist', '2':data.hrgbmk},
            {'0':'<div class="text-right">14.</div>', '1':'No ACC 11', '2':data.noacc11},
            {'0':'<div class="text-right">15.</div>', '1':'Catatan', '2':data.catatan},
            {'0':'<div class="text-right">16.</div>', '1':'Qty Stock Gudang', '2':data.qtystockgudang},
            {'0':'<div class="text-right">17.</div>', '1':'Komisi Khusus 11', '2':data.komisikhusus11},
            {'0':'<div class="text-right">18.</div>', '1':'Link Pembelian', '2':data.linkpembelian},
            {'0':'<div class="text-right">19.</div>', '1':'Last Updated By', '2':data.lastupdatedby},
            {'0':'<div class="text-right">20.</div>', '1':'Last Updated On', '2':data.lastupdatedon},
          ]);
          tabledatadetail.draw();
          $('#labelDblClick').html('Data Sales Order Detail');
          $('#modalDataDblClick').modal('show');
        },
      });
    }
  };

  function closingSO(e, data) {
    var message = $(e).data('message');
    if (message == 'closing') {
      swal({
        title: "Perhatian!",
        text : "Yakin akan closing order ini?",
        type : "warning",
        showCancelButton  : true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText : "Ya",
        cancelButtonText  : "Tidak",
        closeOnConfirm    : true,
        closeOnCancel     : true
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
            'url': "{{ route('salesorder.closingso') }}?id=" + data.id,
            'type': "GET",
            'dataType': "json",
            'success': function (h) {
              if (typeof h == 'string') swal("Ups!", h, "error");
              else if (h.result) {
                swal("Yeah!", "Sales order telah di closing", "success");
                table.ajax.reload();
              }
              else swal("Ups!", h.msg || "Gagal closing sales order", "error");
            },
            'error': function () {
              swal("Ups!", "Tidak dapat terhubung dengan server", "error");
            }
          });
        }
      });

    } else {
      swal('Ups!', message,'error');
    }
  }

  function tambahDetail(e, data){
    var message = $(e).data('message');
    if (message == 'add') {
      console.log(data);

      @cannot('salesorder.detail.tambah')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
      @else
      $('#modalDetail #orderpenjualanid').val(data.id);
      $('#modalDetail #orderIdDetail').val('');
      $('#modalDetail #tipetransaksidetail').val(data.tipetransaksi);
      $('#modalDetail #statustokodetail').val(data.statustoko);
      $('#modalDetail #tokoiddetail').val(data.tokoid);
      $('#modalDetail #kodebarang').val('');
      $('#modalDetail #barang').val('');
      $('#modalDetail #barangid').val('');
      $('#modalDetail #satuan').val('');
      $('#modalDetail #qtyso').val('');
      $('#modalDetail #qtysoacc').val('');
      $('#modalDetail #hrgsatuanbrutto').val('');
      $('#modalDetail #disc1').val(0);
      $('#modalDetail #hrgdisc1').val('');
      $('#modalDetail #disc2').val(0);
      $('#modalDetail #hrgdisc2').val('');
      $('#modalDetail #ppn').val('{{$ppn}}');
      $('#modalDetail #hrgsatuannetto').val('');
      $('#modalDetail #hrgtotalnetto').val('');
      $('#modalDetail #hrgbmk').val('');
      $('#modalDetail #arrhrgbmk').val('');
      $('#modalDetail #qtystockgudang').val(0);
      $('#modalDetail #catatandetail').val('');
      $('#modalDetail #kategoriPenjualan').val('');

      $('#labelModalDetail').text('Penjualan - Tambah Sales Order Detail');
      $('#modalDetail').modal('show');
      @endcannot
    }else {
      swal('Ups!', message,'error');
    }
  }

  function deleteOrder(e, data){
    var message = $(e).data('message');
    var tipe = $(e).data('tipe');
    if (message == 'auth') {
      var dataid;      
      if (tipe == 'header') {
        dataid = data.id;
      }else {
        dataid = data;
      }
      swal({
          title: "Perhatian!",
          text : "Anda yakin ingin menghapus data ini ?",
          type : "warning",
          showCancelButton  : true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText : "Ya",
          cancelButtonText  : "Tidak",
          closeOnConfirm    : true,
          closeOnCancel     : true
      },
      function(isConfirm) {
          if (isConfirm) {            
            tipe_edit = 'hapus';
            $.ajax({
              type: 'POST',
              url: '{{route("salesorder.hapus")}}',
              data: {
                orderid     : dataid,
                tipe        : tipe,
                permission  : 'salesorder.hapus',
              },
              dataType: "json",
              success: function(data){
                if(data.success){
                  swal('Sukses!', 'Data berhasil dihapus','success');
                  //window.location.reload();
                  table.ajax.reload(() => {
                    table.row(table_index || 0).select();
                  }, true);
                  tipe_edit = null;
                }else{
                  swal('Ups!', 'Terdapat kesalahan pada sistem.','error');
                }
              },
              error: function(data){
                console.log(data);
              }
            });
          } else {
            //
          }
      });
    }else {
      swal('Ups!',message,'error');
    }
  }

  function hitung_netto(){
    var qtyso = Number($('#qtyso').val());
    var bruto = Number($('#hrgsatuanbrutto').val());
    var disc1 = Number($('#disc1').val())/100;
    var hrgdisc1 = Math.round((1 - disc1) * bruto, 2);
    var disc2 = Number($('#disc2').val())/100;
    var hrgdisc2 = Math.round((1 - disc2) * hrgdisc1, 2);
    var ppn = Number($('#ppn').val())/100;
    var hrgsatuannetto = Math.round((1 + ppn) * hrgdisc2, 2);
    $('#hrgdisc1').val(hrgdisc1);
    $('#hrgdisc2').val(hrgdisc2);
    $('#hrgsatuannetto').val(hrgsatuannetto);
    $('#hrgtotalnetto').val(hrgsatuannetto * qtyso);
  }

  function currencyFormat(num){
    return num.toString()
     .replace(".", ",") // replace decimal point character with ,
     .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."); // use . as a separator
  }

  function clear_column(id){
    for (var i = 0; i < $('#'+id+' .form-clear').length; i++) {
      element = $('#'+id+' .form-clear')[i];
      $(element).val('');
    }
  }

</script>
@endpush
