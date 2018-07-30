@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
  	<li class="breadcrumb-item"><a href="{{ route('accpiutang.index') }}">Acc Piutang</a></li>
@endsection

@section('main_container')
	<div class="">
		<div class="row">
			<div class="x_panel">
				<div class="x_title">
					<h2>Acc Piutang</h2>
					<ul class="nav navbar-right panel_toolbox">
						<li style="float: right;">
							<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div class="row">
						<div class="col-md-12">
							<form class="form-inline">
				                <div class="form-group">
				                	<label style="margin-right: 10px;">Tgl. Picking List</label>
									<input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
									<label>-</label>
									<input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
				                </div>
			                </form>
						</div>
					</div>
					<table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="1%">A</th>
							    <th>Tgl. Picking List</th>
							    <th>No. Picking List</th>
							    <th>Toko</th>
							    <th>Tgl. Trm. Picking List</th>
							    <th>No. ACC Piutang</th>
							    <th>Rp. ACC</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
						<p>
							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
							<a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Tgl. Picking List</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> No. Picking List</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Toko</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Tgl. Trm. Picking List</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> No. ACC Piutang</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="6"><i id="eye6" class="fa fa-eye"></i> Rp. ACC</a>
						</p>
					</div>
				</div>
				<!-- reni -->
				<div class="x_content">
					<table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
						<thead>
							<tr>
							    <th>Tgl. Request</th>
							    <th>No. Request</th>
								<th>Modul</th>
								<th>Tgl. Aprrove</th>
								<th>Status</th>
								<th>Keterangan Approve</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
						<p>
							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
							<a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Tgl. Picking List</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> No. Picking List</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Toko</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Tgl. Trm. Picking List</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> No. ACC Piutang</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="6"><i id="eye6" class="fa fa-eye"></i> Rp. ACC</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Data double click -->
	<div class="modal fade" id="modalDoubleClickPenjualan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12">
							<div class="table-responsive">
								<table id="doubleclickData" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th class="text-center">No</th>
											<th class="text-center">Nama Kolom</th>
											<th class="text-center">Nilai</th>
										</tr>
									</thead>
									<tbody></tbody>
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
	<!-- end of data double click -->

	<!-- Modal Form Update Pkp -->
	<div class="modal fade" id="modalUpdatePkp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
		<div class="modal-dialog modal-xs" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Update Catatan PKP</h4>
					</div>
					<form id="formUpdatePkp" class="form-horizontal" method="post">
						<input type="hidden" id="id_orderpenjualan" name="id_orderpenjualan" value="">
						<input type="hidden" id="about" value="">
						<div class="modal-body">
							<div class="row">
								<div class="form-group">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<label class="col-md-12 col-sm-12 col-xs-12" for="catatanpkp">Catatan PKP : </label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<textarea id="catatanpkp" name="catatanpkp" class="form-control" placeholder="Catatan PKP"></textarea>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" form="formUpdatePkp" value="Submit">Submit</button>
						{{--  <button type="button" id="btnSubmitUpdatePkp" class="btn btn-primary">Simpan</button>  --}}
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
					</div>
			</div>
		</div>	
	</div>
		<!-- end of update pkp modal -->

	<!-- ACC PIUTANG MODAL -->
	<div class="modal fade" id="modalAccPiutang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Penjualan - Isi ACC Piutang</h4>
				</div>
				<form class="form-horizontal" method="post">
					<input type="hidden" id="orderPenjualanId" class="form-clear" value="">
					<input type="hidden" id="nominalPil" class="form-clear" value="">
					<input type="hidden" id="nominalBawah" class="form-clear" value="">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-3">Plafon</label>
							<input type="hidden" id="plafon" class="form-control form-clear text-right" placeholder="Plafon" readonly tabindex="-1">
							<div class="col-sm-3">
								<div class="input-group">
									<span class="input-group-addon">Rp</span>
									<input type="text" id="plafonRekayasa" class="form-control form-clear text-right" readonly>
								</div>
							</div>
							<p class="form-control-static">Batas kepercayaan kredit</p>
						</div>
						<div class="form-group">
							<label class="col-sm-3">Piutang Nota Berjalan</label>
							<div class="col-sm-3">
								<div class="input-group">
									<span class="input-group-addon">Rp</span>
									<input type="text" id="piutangNotaBerjalan" class="form-control form-clear text-right" placeholder="Piutang Nota Berjalan" readonly tabindex="-1">
								</div>
							</div>
							<p class="form-control-static">Saldo piutang</p>
						</div>
						<div class="form-group">
							<label class="col-sm-3">GIT Nota Jual</label>
							<div class="col-sm-3">
								<input type="text" id="gitNotaJual" class="form-control form-clear text-right" placeholder="GIT Nota Jual" readonly tabindex="-1">
							</div>
							<p class="form-control-static">Nota yang belum diterima toko</p>
						</div>
						<div class="form-group">
							<label class="col-sm-3">PiL dalam proses</label>
							<div class="col-sm-3">
								<div class="input-group">
									<span class="input-group-addon">Rp</span>
									<input type="text" id="pilDalamProses" class="form-control form-clear text-right" placeholder="PiL dalam proses" readonly tabindex="-1">
								</div>
							</div>
							<p class="form-control-static">PiL Sudah ACC dan belum jadi Proforma Invoice</p>
						</div>
						<div class="form-group">
							<label class="col-sm-3">Sisa Plafon</label>
							<div class="col-sm-3">
								<div class="input-group">
									<span class="input-group-addon">Rp</span>
									<input type="text" id="sisaPlafon" class="form-control form-clear text-right" placeholder="Sisa Plafon" readonly tabindex="-1">
								</div>
							</div>
							<p class="form-control-static">Plafon - Piutang nota berjalan - GIT Nota jual - PiL dalam proses</p>
						</div>
						<div class="form-group">
							<label class="col-sm-3">Picking List</label>
							<div class="col-sm-3">
								<div class="input-group">
									<span class="input-group-addon">Rp</span>
									<input type="text" id="pickingList" class="form-control form-clear text-right" placeholder="Picking List" readonly tabindex="-1">
								</div>
							</div>
							<p class="form-control-static">Picking List yang diajukan ACC</p>
						</div>
						<div class="form-group">
							<label class="col-sm-3">Overdue</label>
							<div class="col-sm-3">
								<input type="text" id="overdue" class="form-control form-clear text-right" placeholder="Overdue" readonly tabindex="-1">
							</div>
							<p class="form-control-static">Piutang lewat tempo</p>
						</div>
						<div class="form-group">
							<div class="col-sm-12">
								<h1 id="keterangan" style="color: red">KETERANGAN</h1>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3">Pilihan ACC Piutang</label>
							<div class="col-sm-3">
								<div class="radio">
									<label>
										<input type="radio" name="kettipetransaksi" id="pilihanAccPiutangFull" value="1"> Full
									</label>
									<label>
										<input type="radio" name="kettipetransaksi" id="pilihanAccPiutangSebagian" value="2"> Sebagian
									</label>
									<label>
										<input type="radio" name="kettipetransaksi" id="pilihanAccPiutangTolak" value="3"> Tolak
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3">Nom. SO</label>
							<div class="col-sm-3">
								<input type="text" id="nomAcc" class="form-control form-clear text-right" placeholder="Nom. SO" readonly tabindex="-1">
							</div>
							<label class="col-sm-3">Tgl. ACC</label>
							<div class="col-sm-3">
								<input type="text" id="tglAcc" class="form-control form-clear" placeholder="Tgl. ACC" readonly tabindex="-1">
							</div>
						</div>
						<div class="form-group col-sm-12">
							
							<label class="col-sm-3">No. ACC</label>
							<div class="col-sm-3">
								<input type="text" id="noAcc" class="form-control form-clear" placeholder="No. ACC" readonly tabindex="-1">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3">Nom. ACC</label>
							<div class="col-sm-3">
								<div class="input-group">
									<span class="input-group-addon">Rp</span>
									<input type="text" id="rpAcc" class="form-control form-clear text-right" placeholder="Nom. ACC">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3">PKP</label>
							<div class="col-sm-9">
								<input type="text" id="pkp" class="form-control form-clear" placeholder="PKP" readonly tabindex="-1">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3">Catatan</label>
							<div class="col-sm-9">
								<input type="text" id="catatan" class="form-control form-clear" placeholder="Catatan">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSubmitAccPiutang" class="btn btn-primary">Simpan</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- END OF ACC PIUTANG MODAL -->

	<!-- Form Kewenangan -->
	<div class="modal fade" id="modalKewenangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
		<div class="modal-dialog modal-xs" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Form Kewenangan</h4>
				</div>
				<form class="form-horizontal" method="post">
					<input type="hidden" id="opjId" value="">
					<input type="hidden" id="permission" value="">
					<input type="hidden" id="about" value="">
					<div class="modal-body">
						<div class="row">
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="uxserKewenangan">Username</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<input type="text" id="uxserKewenangan" class="form-control" placeholder="Username">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="pxassKewenangan">Password</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<input type="password" id="pxassKewenangan" class="form-control" placeholder="Password">
								</div>
							</div>
						</div>
					</div>
				</form>
				<div class="modal-footer">
					<button type="button" id="btnSubmitKewenangan" class="btn btn-primary">Proses</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
<script type="text/javascript">
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
	var last_index = '';
	var context_menu_number_state = 'hide';
	var context_menu_text_state = 'hide';
	var tipe_edit = null;
	var table,table_index,fokus, table2;

	$(document).ready(function(){
		$(".tgl").inputmask();
		table = $('#table1').DataTable({
			dom 		: 'lrtp',//lrtip -> lrtp
			serverSide	: true,
			stateSave	: true,
			deferRender : true,
            select: {style:'single'},
            keys: {keys: [38,40]},
			ajax 		: {
				url			: '{{ route("accpiutang.data") }}',
				data 		: function ( d ) {
					d.custom_search = custom_search;
					d.tglmulai 		= $('#tglmulai').val();
					d.tglselesai 	= $('#tglselesai').val();
					d.tipe_edit     = tipe_edit;
				},
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
			rowCallback: function(row, data, index) {
				// console.log(data);
			},
			columns		: [
				{
					"data" : "action",
					"orderable" : false,
			    	render : function(data, type, row) {
                        
                        if (row.tglterimapilpiutang != null){
                            return "<div class='btn btn-xs btn-danger no-margin-action skeyF1' data-toggle='tooltip' data-placement='bottom' title='Batal Pickling List - F1' onclick='terima_picking(this,"+row.id+")' data-message='"+row.terima+"' data-tipe='header'><i class='fa fa-times'></i></div>"+
                            "<div class='btn btn-xs btn-warning no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='Isi No. ACC - F2' onclick='isi_acc(this,"+row.id+")' data-message='"+row.isi_acc+"' data-tipe='header'><i class='fa fa-pencil'></i></div>"+
                            "<div class='btn btn-xs btn-primary no-margin-action skeyF3' data-toggle='tooltip' data-placement='bottom' title='Update PKP - F3' onclick='update_pkp(this,"+row.id+")' data-message='' data-tipe='header'><i class='fa fa-pencil'></i>PKP</div>";
                        }else{
                            return "<div class='btn btn-xs btn-success no-margin-action skeyF1' data-toggle='tooltip' data-placement='bottom' title='Terima Pickling List - F1' onclick='terima_picking(this,"+row.id+")' data-message='"+row.terima+"' data-tipe='header'><i class='fa fa-check'></i></div>"+
                            "<div class='btn btn-xs btn-warning no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='Isi No. ACC - F2' onclick='isi_acc(this,"+row.id+")' data-message='"+row.isi_acc+"' data-tipe='header'><i class='fa fa-pencil'></i></div>"+
                            "<div class='btn btn-xs btn-primary no-margin-action skeyF3' data-toggle='tooltip' data-placement='bottom' title='Update PKP - F3' onclick='update_pkp(this,"+row.id+")' data-message='' data-tipe='header'><i class='fa fa-pencil'></i>PKP</div>";
                        }
			    	}
				},
			    {
			    	"data" : "tglpickinglist",
			    	"className" : "menufilter numberfilter"
			    },
			    {
			    	"data" : "nopickinglist",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "namatoko",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "tglterimapilpiutang",
			    	"className" : "menufilter numberfilter"
			    },
			    {
			    	"data" : "noaccpiutang",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "rpaccpiutang",
			    	"className" : "menufilter numberfilter text-right"
			    },
			]
		});

		var tabledoubleclick = $('#doubleclickData').DataTable({
			dom 		: 'lrtp',
			paging		: false,
			columns 	: [
				{
					"className" : "text-right"
				},
				null,
				null,
			],
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

		$('a.toggle-vis').on( 'click', function (e) {
			e.preventDefault();

			// Get the column API object
			var column = table.column( $(this).attr('data-column') );

			// Toggle the visibility
			column.visible( ! column.visible() );
			$('#eye'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
		} );

		$(document.body).on("keydown", function(e){
			ele = document.activeElement;
			if(e.keyCode == 13){
				if(context_menu_number_state == 'show'){
					$(".context-menu-list.numberfilter").contextMenu("hide");
					table.ajax.reload(null, true);
				}else if(context_menu_text_state == 'show'){
					$(".context-menu-list.textfilter").contextMenu("hide");
					table.ajax.reload(null, true);
				}

				if($('#modalAccPiutang').is(':visible')){
					status_isiacc();
					return false;
				}

				if($('#modalKewenangan').is(':visible')){
					submit_kewenangan();
					return false;
				}
			}
		});

		$('#btnSubmitKewenangan').click(function(){
	    	submit_kewenangan();
	    });
	    $('#btnSubmitAccPiutang').click(function(){
	    	status_isiacc();
	    });

		$("#formUpdatePkp").on( "submit", function( event ) {
			event.preventDefault();
			var form_data = $("#formUpdatePkp").serialize();
			var catatanpkp_value = $('#catatanpkp').val();
			
			if(catatanpkp_value){
				if(catatanpkp_value.length <= 0){
					swal('Ups!', 'Input masih kosong','error');
					return false;
				}
			}else{
				swal('Ups!', 'Type input tidak sesuai','error');
				return false;
			}

			$.ajaxSetup({
				headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
			});
			$.ajax({
				type: 'POST',
				data: form_data,
				dataType: "json",
				url: '{{route("accpiutang.update.pkp")}}',
				success: function(data){
					if(data.status == 'success'){
						swal('Update Berhasil!', "Catatan PKP dengan No. Picking List "+data.data.nopickinglist+" berhasil diupdate",'success');
						$('#modalUpdatePkp').modal('toggle');
						$('#catatanpkp').val('');
					}else{
						swal('Ups!', data.message,'error');
					}
				},
				error: function(data){
					console.log(data);
				}
			});   
		});

		

		$('#table1 tbody').on('dblclick', 'tr', function(){
			var data = table.row(this).data();
			$.ajax({
	        	type: 'GET',
				url: '{{route("orderpenjualan.header")}}',
				data: {id: data.id},
				dataType: "json",
	        	success: function(data){
	          		tabledoubleclick.clear();
	          		tabledoubleclick.rows.add([
			            {'0':'1.', '1':'C1', '2':data.c1},
			            {'0':'2.', '1':'C2', '2':data.c2},
			            {'0':'3.', '1':'No. SO', '2':data.noso},
			            {'0':'4.', '1':'Tgl. SO', '2':data.tglso},
			            {'0':'5.', '1':'No. Picking List', '2':data.nopickinglist},
			            {'0':'6.', '1':'Tgl. Picking List', '2':data.tglpickinglist},
			            {'0':'7.', '1':'Toko', '2':data.tokonama},
			            {'0':'8.', '1':'Alamat', '2':data.tokoalamat},
			            {'0':'9.', '1':'Kota', '2':data.tokokota},
			            {'0':'10.', '1':'Kecamatan', '2':data.kecamatan},
			            {'0':'11.', '1':'WILID', '2':data.wilid},
			            {'0':'12.', '1':'Toko ID', '2':data.idtoko},
			            {'0':'13.', '1':'Status Toko', '2':data.statustoko},
			            {'0':'14.', '1':'Salesman', '2':data.salesmannama},
			            {'0':'15.', '1':'Tipe Transaksi', '2':data.tipetransaksi},
			            {'0':'16.', '1':'Tempo Nota', '2':data.temponota},
			            {'0':'17.', '1':'Tempo Kirim', '2':data.tempokirim},
			            {'0':'18.', '1':'Tempo Salesman', '2':data.temposalesman},
			            {'0':'19.', '1':'No. ACC Piutang', '2':data.noaccpiutang},
			            {'0':'20.', '1':'Nama PKP', '2':data.namapkp},
			            {'0':'21.', '1':'Tgl. ACC Piutang', '2':data.tglaccpiutang},
			            {'0':'22.', '1':'Rp. ACC Piutang', '2':data.rpaccpiutang},
			            {'0':'23.', '1':'Rp. Saldo Piutang', '2':data.rpsaldopiutang},
			            {'0':'24.', '1':'Rp. Saldo Overdue', '2':data.rpsaldooverdue},
			            {'0':'25.', '1':'Rp. SO ACC Proses', '2':data.rpsoaccproses},
			            {'0':'26.', '1':'Rp. GIT', '2':data.rpgit},
			            {'0':'27.', '1':'Catatan Penjualan', '2':data.catatanpenjualan},
			            {'0':'28.', '1':'Catatan Pembayaran', '2':data.catatanpembayaran},
			            {'0':'29.', '1':'Catatan Pengiriman', '2':data.catatanpengiriman},
			            {'0':'30.', '1':'Print', '2':data.print},
			            {'0':'31.', '1':'Tgl. Print Picking List', '2':data.tglprintpickinglist},
			            {'0':'32.', '1':'Status Approval Overdue', '2':data.statusapprovaloverdue},
			            {'0':'33.', '1':'Tgl. Terima SO ke Piutang', '2':data.tglterimapilpiutang},
			            {'0':'34.', '1':'Status Ajuan Harga 11', '2':data.statusajuanhrg11},
			            {'0':'35.', '1':'Last Updated By', '2':data.lastupdatedby},
			            {'0':'36.', '1':'Last Updated On', '2':data.lastupdatedon},
	          		]);
	          		tabledoubleclick.draw();
	          		$('#modalDoubleClickPenjualan #myModalLabel').html('Data Order Penjualan');
	          		$('#modalDoubleClickPenjualan').modal('show');
	        	}
	      	});
		});

		table2 = $('#table2').DataTable({
            dom         : 'lrtp',
            select         : true,
            scrollY     : 300,
            // scrollY     : 312,
            scrollX     : true,
			select: {style:'single'},
            scroller     : {
                loadingIndicator: true
            },
            //order         : [[ 2, 'asc' ]],
            rowCallback: function(row, data, index) {
                 //console.log(data);
            },
            columns     : [
                {
					"data" : "createdon",
					"className" : "menufilter textfilter",
				},
				{
					"data" : "norequest",
					"className" : "menufilter textfilter",
				},
				{
					"data" : "modulename",
					"className" : "menufilter textfilter"
				},
				{
					"data" : "lastupdatedon",
					"className" : "menufilter textfilter"
				},
				{
					"data" : "status",
					"className" : "menufilter textfilter"
				},
				{
					"data" : "keterangan",
					"className" : "menufilter textfilter"
				}
            ]
        });

		table.on('select', function ( e, dt, type, indexes ){
            var rowData = table.rows(indexes).data().toArray();
            var id = rowData[0].id

            table2.clear();
            $.ajax({
                type : 'GET',
                url : '{{ route("accpiutang.dataAppr") }}',
                data : {id : id},
                success : function(data){
                    var data_parse = JSON.parse(data);
                    table2.clear();
                    table2.rows.add(data_parse);
                    table2.draw();
                    setTimeout(function(){
                        table2.columns.adjust();
                        if(data_parse.length > 0) {
                            table2.cell(':eq(0)').focus();
                        }
                    }, 100);
                },
                error: function(data){
                    table2.clear();
                    table2.columns.adjust();
                }
            });
		});
	});

	$('.tgl').change(function(){
		table.draw();
		table2.draw();
	});

	$('input[name="kettipetransaksi"]').on('click', function(e) {
		$.ajaxSetup({
			headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
		});

		$.ajax({
			type: 'POST',
			data: {
				tipe : $('#modalAccPiutang input[name=kettipetransaksi]:checked').val(),
				id   : $('#orderPenjualanId').val(),
			},
			dataType: "json",
			url     : '{{route("accpiutang.changepilihanacc")}}',
			success : function(data){
				if(data.tipe == 1){
					toogle_column('modalAccPiutang',['rpAcc','catatan'], false, 'true');
					$('#modalAccPiutang #noAcc').val(data.nomor);
					$('#modalAccPiutang #rpAcc').val(parseInt(data.nominal).toLocaleString("id-ID"));
					$('#modalAccPiutang #nominalBawah').val(data.nominal_bawah);
				}else if(data.tipe == 2){
					toogle_column('modalAccPiutang',['rpAcc','catatan'], true);
					$('#modalAccPiutang #noAcc').val(data.nomor);
					$('#modalAccPiutang #rpAcc').val(parseInt(data.nominal).toLocaleString("id-ID"));
					$('#modalAccPiutang #nominalBawah').val(data.nominal_bawah);
				}else if(data.tipe == 3){
					toogle_column('modalAccPiutang',['rpAcc','catatan']);
					toogle_column('modalAccPiutang',['catatan'], true);
					$('#modalAccPiutang #noAcc').val(data.nomor);
					$('#modalAccPiutang #rpAcc').val(parseInt(data.nominal).toLocaleString("id-ID"));
					$('#modalAccPiutang #nominalBawah').val(data.nominal_bawah);
				}
			},
			error: function(data){
				console.log(data);
			}
		});
	});	

	function terima_picking(e, data){
		var message = $(e).data('message');
		if(message == 'konfirmasi'){
			@cannot('accpiutang.tglterimapil.delete')
				swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			tipe_edit = true;
			swal({
				title: "Ups!",
				text: "Mau Hapus Tanggal terima Picking List?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes",
				cancelButtonText: "No",
				closeOnConfirm: false
			},
			function(){
				$.ajax({
					type     : 'GET',
					url      : '{{ route("accpiutang.tglterimapil.delete") }}',
					data     : {id: data},
					dataType : "json",
					success  : function(data){
						swal("Deleted!", "Tanggal terima Picking List telah dihapus.", "success");
						table.ajax.reload(null, true);
						tipe_edit = null;
						setTimeout(function(){
							table.row(0).select();
						},1000);
					}
				});
			});
			@endcannot
		}else if(message == 'terima'){
			@cannot('accpiutang.tglterimapil.terima')
				swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			tipe_edit = true;
			$.ajax({
				type     : 'GET',
				url      : '{{ route("accpiutang.tglterimapil.terima") }}',
				data     : {id: data},
				dataType : "json",
				success  : function(data){
					swal("Accepted!", "Picking List diterima.", "success");
					table.ajax.reload(null, true);
					tipe_edit = null;
					setTimeout(function(){
						table.row(0).select();
					},1000);
				}
			});
			@endcannot
		}else{
			swal('Ups!', message,'error');
		}
	}

	function update_pkp(e,id){
		//$('#modalUpdatePkp').modal('show');
		//$('#id_orderpenjualan').val(id);
		//$('#catatanpkp').val(catatanpkp);
		
		$.ajax({
			type     : 'GET',
			url      : '{{ route("accpiutang.pkp.show") }}',
			data     : {id: id},
			dataType : "json",
			success  : function(data){
				$('#modalUpdatePkp').modal('show');
				$('#id_orderpenjualan').val(id);
				$('#catatanpkp').val(data.catatanpkp);
			}
		});
	}

	function isi_acc(e, data){
		@cannot('accpiutang.acc.isi')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		var message = $(e).data('message');
		if(message == 'isi'){
			open_modalAccPiutang(data);
		}else if(message == 'auth'){
    		$('#modalKewenangan #opjId').val(data);
    		$('#modalKewenangan #permission').val('accpiutang.acc.isi');
    		$('#modalKewenangan #about').val('isiacc');
    		$('#modalKewenangan').modal('show');
    	}else{
			swal('Ups!', message,'error');
		}
		@endcannot
	}

	function open_modalAccPiutang(orderid){
		console.log(orderid);
		$.ajax({
			type     : 'GET',
			url      : '{{ route("accpiutang.approverequest") }}',
			data     : {id: orderid},
			dataType : "json",
			success  : function(apm){
				if (apm.success == true){
					if (apm.message != ''){
						swal('Ups!', apm.message ,'info');
					}else{
						
						table.draw();
						table2.draw();
                        swal('OK!', 'Approval request berhasil .','success');
					}
				}else{
					$.ajax({
						type     : 'GET',
						url      : '{{ route("accpiutang.acc.getdata") }}',
						data     : {id: orderid},
						dataType : "json",
						success  : function(data){
							clear_column('modalAccPiutang');
							toogle_column('modalAccPiutang',['rpAcc','catatan']);
							toogle_radio('modalAccPiutang','kettipetransaksi');
							var locale_pickinglist    = parseInt(data.pickinglist).toLocaleString("id-ID");
							var locale_plafon         = parseInt(data.plafon).toLocaleString("id-ID");
							var locale_saldopiutang   = parseInt(data.saldopiutang).toLocaleString("id-ID");
							var locale_git            = parseInt(data.git).toLocaleString("id-ID");
							var locale_pildalamproses = parseInt(data.pildalamproses).toLocaleString("id-ID");
							var locale_sisaplafon     = parseInt(data.sisaplafon).toLocaleString("id-ID");
							var locale_pickinglist    = parseInt(data.pickinglist).toLocaleString("id-ID");
							
							$('#modalAccPiutang #orderPenjualanId').val(data.id);
							$('#modalAccPiutang #nominalPil').val(locale_pickinglist);
							$('#modalAccPiutang #plafon').val(locale_plafon);
							$('#modalAccPiutang #piutangNotaBerjalan').val(locale_saldopiutang);
							$('#modalAccPiutang #gitNotaJual').val(locale_git);
							$('#modalAccPiutang #pilDalamProses').val(locale_pildalamproses);
							$('#modalAccPiutang #sisaPlafon').val(locale_sisaplafon);
							$('#modalAccPiutang #pickingList').val(locale_pickinglist);
							$('#modalAccPiutang #overdue').val(data.overdue);
							$('#modalAccPiutang #keterangan').html(data.keterangan);
							$('#modalAccPiutang #tglAcc').val(data.tglacc);
							$('#modalAccPiutang #pkp').val(data.pkp);
							if(data.totalacc == null){
								$('#modalAccPiutang #nomAcc').val(0);
							}else{
								$('#modalAccPiutang #nomAcc').val(parseInt(data.totalacc).toLocaleString("id-ID"));
							}
							
							$('#modalAccPiutang #plafonRekayasa').val(1);
							$('#modalAccPiutang').modal('show');

							var popup1  = window.open("about:blank", "_blank");
							popup1.location = '{{route("accpiutang.rekappembayarantoko")}}'+'?id='+data.id;
						}
					});
				}
			},
			error: function(data){
				console.log(data);
			}
		});
		
		// var AMPvalidation = approverequest(data);
		// if (AMPvalidation == true){
		// 	swal('Ups!', 'Isi acc ditolak. Transaksi dalam kondisi approval request.','error');
		// }else{
		// 	$.ajax({
		// 		type     : 'GET',
		// 		url      : '{{ route("accpiutang.acc.getdata") }}',
		// 		data     : {id: data},
		// 		dataType : "json",
		// 		success  : function(data){
		// 			clear_column('modalAccPiutang');
		// 			toogle_column('modalAccPiutang',['rpAcc','catatan']);
		// 			toogle_radio('modalAccPiutang','kettipetransaksi');
		// 			var locale_pickinglist    = parseInt(data.pickinglist).toLocaleString("id-ID");
		// 			var locale_plafon         = parseInt(data.plafon).toLocaleString("id-ID");
		// 			var locale_saldopiutang   = parseInt(data.saldopiutang).toLocaleString("id-ID");
		// 			var locale_git            = parseInt(data.git).toLocaleString("id-ID");
		// 			var locale_pildalamproses = parseInt(data.pildalamproses).toLocaleString("id-ID");
		// 			var locale_sisaplafon     = parseInt(data.sisaplafon).toLocaleString("id-ID");
		// 			var locale_pickinglist    = parseInt(data.pickinglist).toLocaleString("id-ID");
					


		// 			$('#modalAccPiutang #orderPenjualanId').val(data.id);
		// 			$('#modalAccPiutang #nominalPil').val(locale_pickinglist);
		// 			$('#modalAccPiutang #plafon').val(locale_plafon);
		// 			$('#modalAccPiutang #piutangNotaBerjalan').val(locale_saldopiutang);
		// 			$('#modalAccPiutang #gitNotaJual').val(locale_git);
		// 			$('#modalAccPiutang #pilDalamProses').val(locale_pildalamproses);
		// 			$('#modalAccPiutang #sisaPlafon').val(locale_sisaplafon);
		// 			$('#modalAccPiutang #pickingList').val(locale_pickinglist);
		// 			$('#modalAccPiutang #overdue').val(data.overdue);
		// 			$('#modalAccPiutang #keterangan').html(data.keterangan);
		// 			$('#modalAccPiutang #tglAcc').val(data.tglacc);
		// 			$('#modalAccPiutang #pkp').val(data.pkp);
		// 			if(data.totalacc == null){
		// 				$('#modalAccPiutang #nomAcc').val(0);
		// 			}else{
		// 				$('#modalAccPiutang #nomAcc').val(parseInt(data.totalacc).toLocaleString("id-ID"));
		// 			}
					
		// 			$('#modalAccPiutang #plafonRekayasa').val(1);
		// 			$('#modalAccPiutang').modal('show');

		// 			var popup1  = window.open("about:blank", "_blank");
		// 			popup1.location = '{{route("accpiutang.rekappembayarantoko")}}'+'?id='+data.id;
		// 		}
		// 	});
		// }
		
	}

	function status_isiacc(){
		
		pilihanacc = $('#modalAccPiutang input[name=kettipetransaksi]:checked').val();
		if(pilihanacc){
			if($('#modalAccPiutang #rpAcc').val()){
				if(pilihanacc != 1){
					if($('#modalAccPiutang #catatan').val()){
						submit_isiacc();
					}else{
						swal('Ups!', 'Tidak bisa simpan record. Harus isi textbox catatan jika ACC Piutang Sebagaian/ Tolak.','error');
					}
				}else{
					submit_isiacc();
				}
			}else{
				swal('Ups!', 'Tidak bisa simpan record. TextBox Rp. ACC kasih kosong.','error');
			}
		}else{
			swal('Ups!', 'Tidak bisa simpan record. Pilihan ACC Piutang belum terisi.','error');
		}
	}

	function submit_isiacc(){
		@cannot('accpiutang.acc.isi')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		
		var jnsacc = $('#modalAccPiutang input[name=kettipetransaksi]:checked').val();
		var nomacc = parseFloat($('#modalAccPiutang #rpAcc').val().replace(/\./g,""));
		var nomplafon = parseFloat($('#modalAccPiutang #sisaPlafon').val().replace(/\./g,""));
		var nompil = parseFloat($('#modalAccPiutang #pickingList').val().replace(/\./g,""));
		
		if (jnsacc != 3){
			if (nomacc > nomplafon){
				swal('Ups!', 'Tidak bisa proses ACC Piutang. Sisa plafon piutang Rp. '+ parseFloat(nomplafon).toLocaleString("id-ID") +', lebih kecil dari nom. SO Rp '+ parseFloat(nomacc).toLocaleString("id-ID") +'. Ajukan plafon tambahan untuk bisa proses ACC piutang SO.' ,'error');
				return false;
			}
		}

		if (jnsacc == 2){
			if (nomacc > nompil){
				swal("Ups!", "Tidak bisa proses ACC piutang. Nom Acc tidak boleh lebih besar dari Picking List yang diajukan.","error");
				return false;
			}
		}

		tipe_edit = true;
		$.ajaxSetup({
			headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
		});

		acc = {
			rpsaldopiutang    : $('#modalAccPiutang #piutangNotaBerjalan').val().replace(/\./g,""),
			rpgit             : $('#modalAccPiutang #gitNotaJual').val().replace(/\./g,""),
			rpsoaccprocess    : $('#modalAccPiutang #pilDalamProses').val().replace(/\./g,""),
			rpsaldooverdue    : $('#modalAccPiutang #overdue').val().replace(/\./g,""),
			tglaccpiutang     : $('#modalAccPiutang #tglAcc').val(),
			noaccpiutang      : $('#modalAccPiutang #noAcc').val(),
			rpaccpiutang      : $('#modalAccPiutang #rpAcc').val().replace(/\./g,""),
			karyawanidpkp     : '{{ auth()->user()->id }}',
			catatanpembayaran : $('#modalAccPiutang #keterangan').val(),
		}

		nominalacc = $('#modalAccPiutang #rpAcc').val().replace(/\./g,"");
		nominalpil = $('#modalAccPiutang #nominalPil').val().replace(/\./g,"");
		nominalbwh = $('#modalAccPiutang #nominalBawah').val().replace(/\./g,"");
		pilihanacc = $('#modalAccPiutang input[name=kettipetransaksi]:checked').val();
		if(pilihanacc == 2){
			if(nominalacc > nominalbwh && parseInt(nominalacc) < parseInt(nominalpil)){
				$.ajax({
					type: 'POST',
					data: {
						id  : $('#orderPenjualanId').val(),
						acc : acc,
					},
					dataType: "json",
					url: '{{route("accpiutang.acc.isi")}}',
					success: function(data){
						$('#modalAccPiutang').modal('hide');
						table.ajax.reload(null, true);
						tipe_edit = null;
						setTimeout(function(){
							table.row(0).select();
						},1000);
					},
					error: function(data){
						console.log(data);
					}
				});
			}else{
				swal('Ups!', 'Tidak bisa simpan record. TextBox Rp. ACC harus > '+nominalbwh+' dan < '+nominalpil,'error');
			}
		}else{
			$.ajax({
				type: 'POST',
				data: {
					id  : $('#orderPenjualanId').val(),
					acc : acc,
				},
				dataType: "json",
				url: '{{route("accpiutang.acc.isi")}}',
				success: function(data){
					$('#modalAccPiutang').modal('hide');
					table.ajax.reload(null, true);
					tipe_edit = null;
					setTimeout(function(){
						table.row(0).select();
					},1000);
				},
				error: function(data){
					console.log(data);
				}
			});
		}
		@endcannot
	}

	function submit_kewenangan(){
		$.ajaxSetup({
			headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
		});

    	$.ajax({
			type: 'POST',
			url: '{{route("accpiutang.kewenangan")}}', 
			data: {
				username   : $('#modalKewenangan #uxserKewenangan').val().trim().toUpperCase(),
				password   : $('#modalKewenangan #pxassKewenangan').val(),
				opjid      : $('#modalKewenangan #opjId').val(),
				permission : $('#modalKewenangan #permission').val(),
				about      : $('#modalKewenangan #about').val(),
			},
			dataType: "json",
			success: function(data){
				$('#modalKewenangan #uxserKewenangan').val('').change();
				$('#modalKewenangan #pxassKewenangan').val('').change();

				if(data.success){
					$('#modalKewenangan').modal('hide');
					if(data.message == 'isi'){
						open_modalAccPiutang(data.id);
					}else{
						swal('Ups!', data.message,'error');
					}
				}else{
					swal('Ups!', 'Data yang Anda inputkan tidak tepat','error');
				}
			},
			error: function(data){
				console.log(data);
			}
		});
    }

	function clear_column(modal){
    	for (var i = 0; i < $('#'+modal+' .form-clear').length; i++) {
    		element = $('#'+modal+' .form-clear')[i];
    		$(element).val('');
    	}
    }

	function toogle_column(modal, column, enable, empty){
		if(enable){
			for (var i = 0; i < column.length; i++) {
				$('#'+modal+' #'+column[i]).removeAttr("readonly");
				$('#'+modal+' #'+column[i]).attr("tabindex",i+1);
			}
		}else{
			for (var i = 0; i < column.length; i++) {
				$('#'+modal+' #'+column[i]).attr("readonly","true");
				$('#'+modal+' #'+column[i]).attr("tabindex",'-1');
				if(empty){
					$('#'+modal+' #'+column[i]).val('');
				}
			}
		}
	}

	function toogle_radio(modal, radio){
		radio = $('#'+modal+' input[name='+radio+']');
		for (var i = 0; i < radio.length; i++) {
			$(radio[i]).prop('checked', false);
		}
	}
</script>
@endpush