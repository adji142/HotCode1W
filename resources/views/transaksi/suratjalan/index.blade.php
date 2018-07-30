@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
  	<li class="breadcrumb-item"><a href="{{ route('suratjalan.index') }}">Surat Jalan</a></li>
@endsection

@section('main_container')
	<div class="">
		<div class="row">
			<div class="x_panel">
				<div class="x_title">
					<h2>Surat Jalan</h2>
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
				                	<label style="margin-right: 10px;">Tgl. Surat Jalan</label>
									<input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
									<label>-</label>
									<input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
				                </div>
			                </form>
						</div>
						<div class="col-md-6 text-right">
							@can('suratjalan.create')
							<a onclick="open_formsj()" class="btn btn-success" id="skeyIns"><i class="fa fa-plus"></i> Tambah Surat Jalan - Ins</a>
							@endcan
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
						<table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th width="1%">A</th>
								    <th>Tgl. SJ</th>
								    <th>No. SJ</th>
								    <th>Toko</th>
								    <th>WilID</th>
								    <th>Expedisi</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						<div style="cursor: pointer; margin-top: 10px;" class="hidden">
							<p>
								<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
								<a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Tgl. SJ</a>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> No. SJ</a>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Toko</a>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> WilID</a>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> Expedisi</a>
							</p>
						</div>
						</div>
					</div>
					{{-- <hr> --}}
					<div class="row">
						<div class="col-md-8 col-sm-12">
							<table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
								<thead>
									<tr>
									    <th width="1%">A</th>
									    <th>Tgl. Proforma</th>
									    <th>No. Nota</th>
									    <th>Tempo Nota</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
							<div style="cursor: pointer; margin-top: 10px;" class="hidden">
								<p>
									<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
									<a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Tgl. Proforma</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> No. Nota</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<a class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Tempo Nota</a>
								</p>
							</div>
						</div>
						<div class="col-md-4 col-sm-12">
							<table id="table3" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
								<thead>
									<tr>
									    <th width="1%">A</th>
									    <th>Nomor Koli</th>
									    <th>Keterangan</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
							<div style="cursor: pointer; margin-top: 10px;" class="hidden">
								<p>
									<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
									<a class="toggle-vis-3" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Nomor Koli</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<a class="toggle-vis-3" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Keterangan</a>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Data double click -->
	<div class="modal fade" id="modalDoubleClickSuratJalan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
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

	<!-- SURAT JALAN MODAL -->
	<div class="modal fade" id="modalSJInsert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Penjualan - Surat Jalan Insert</h4>
				</div>
				<form class="form-horizontal" method="post">
					<input type="hidden" id="suratJalanId" class="form-clear" value="">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label>Tgl. SJ</label>
									<input type="text" id="tglsj" class="form-control form-clear" placeholder="Tgl. SJ" readonly tabindex="-1">
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label>No. SJ</label>
									<input type="text" id="nosj" class="form-control form-clear" placeholder="No. SJ" readonly tabindex="-1">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Toko</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-search"></i></span>
										<input type="text" id="toko" class="form-control form-clear" placeholder="Toko">
										<input type="hidden" id="tokoId" name="tokoId" class="form-clear" value="">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Alamat</label>
									<input type="text" id="alamat" class="form-control form-clear" placeholder="Alamat" readonly tabindex="-1">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="form-group">
									<label>Daerah</label>
									<input type="text" id="daerah" class="form-control form-clear" placeholder="Daerah" readonly tabindex="-1">
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="form-group">
									<label>Kota</label>
									<input type="text" id="kota" class="form-control form-clear" placeholder="Kota" readonly tabindex="-1">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="form-group">
									<label>WILID</label>
									<input type="text" id="wilid" class="form-control form-clear" placeholder="WILID" readonly tabindex="-1">
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="form-group">
									<label>NIT</label>
									<input type="text" id="nit" class="form-control form-clear" placeholder="NIT" readonly tabindex="-1">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="form-group">
									<label>Tgl. Proforma</label>
									<input type="text" id="tglproforma" class="tgl form-control form-clear" placeholder="Tgl. Proforma" data-inputmask="'mask': 'd-m-y'">
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="form-group">
									<label>No. Koli</label>
									<input type="text" id="nokoli" class="form-control form-clear" placeholder="No. Koli" readonly tabindex="-1">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Keterangan SJ</label>
									<input type="text" id="keterangansj" class="form-control form-clear" placeholder="Keterangan SJ">
								</div>
							</div>
						</div>
						<hr class="hr-text" data-content="KHUSUS TITIPAN">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Keterangan Kolian</label>
									<input type="text" id="keterangankoli" class="form-control form-clear" placeholder="Keterangan Kolian">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Dari</label>
									<input type="text" id="dari" class="form-control form-clear" placeholder="Dari">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Untuk</label>
									<input type="text" id="untuk" class="form-control form-clear" placeholder="Untuk">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Alamat</label>
									<input type="text" id="alamattitipan" class="form-control form-clear" placeholder="Alamat">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>No. Telepon</label>
									<input type="text" id="notelp" class="form-control form-clear" placeholder="No. Telepon">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>No. Kolian</label>
									<input type="text" id="nokolititipan" class="form-control form-clear" placeholder="No. Kolian">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSubmitSJInsert" class="btn btn-primary">Simpan</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
					</div>
				</form>
			</div>
		</div>
	</div>

{{-- 	<!-- modal pilih toko -->
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
 --}}
    <!-- Form Kewenangan -->
	<div class="modal fade" id="modalKewenangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
		<div class="modal-dialog modal-xs" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Form Kewenangan</h4>
				</div>
				<form class="form-horizontal" method="post">
					<input type="hidden" id="id" value="">
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
		{text   : '',filter : '='},
		{text   : '',filter : '='},
		{text   : '',filter : '='},
		{text   : '',filter : '='},
		{text   : '',filter : '='},
		{text   : '',filter : '='},
	];
	var filter_number = ['<=','<','=','>','>='];
	var filter_text   = ['=','!='];
	var tipe          = ['Find','Filter'];
	var column_index  = 0;
	var last_index    = '';
	var context_menu_number_state = 'hide';
	var context_menu_text_state   = 'hide';
	var tipe_edit         = null;
	var koliinsert_status = null;
	var table, table2, table3, table_index, table2_index, table3_index,fokus;

	lookuptoko();

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
				type: "POST",
				url : '{{ route("suratjalan.data") }}',
				data: function ( d ) {
					d._token        = '{{ csrf_token() }}';
					d.custom_search = custom_search;
					d.tglmulai 		= $('#tglmulai').val();
					d.tglselesai 	= $('#tglselesai').val();
					d.tipe_edit     = tipe_edit;
				},
			},
			order 		: [[ 1, 'asc' ]],
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
			rowCallback: function(row, data, index) {
				// console.log(data);
			},
			columns		: [
				{
					"data" : "action",
					"orderable" : false,
			    	render : function(data, type, row) {
			    		var html_buttonaction  = '<div class="btn-group no-margin-action">';
			    		    html_buttonaction += '	<button type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			    		    html_buttonaction += '		Tambah <span class="caret"></span>';
			    		    html_buttonaction += '	</button>';
			    		    html_buttonaction += '	<ul class="dropdown-menu">';
			    		    // html_buttonaction += '		<li><a onclick="tambah_sjd(this,'+row.id+')" data-message="'+row.tambahsjd+'" class="skeyF2">Tambah SJD - F2</a></li>';
			    		    html_buttonaction += '		<li><a onclick="tambah_koli(this,'+row.id+')" data-message="'+row.tambahkoli+'" data-tokoid="'+row.tokokoli+'" class="skeyF3">Tambah Koli - F3</a></li>';
			    		    html_buttonaction += '	</ul>';
			    		    html_buttonaction += '</div>';

			    		var html_buttonprints  = '<div class="btn-group no-margin-action">';
			    		    html_buttonprints += '	<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			    		    html_buttonprints += '		Cetak <span class="caret"></span>';
			    		    html_buttonprints += '	</button>';
			    		    html_buttonprints += '	<ul class="dropdown-menu">';
			    		    html_buttonprints += '		<li><a onclick="cetak(this,'+row.id+')" data-tipe="pl" class="skeyF6">Cetak PL - F6</a></li>';
			    		    html_buttonprints += '		<li><a onclick="cetak(this,'+row.id+')" data-tipe="amplop" class="skeyF7">Cetak Amplop - F7</a></li>';
			    		    html_buttonprints += '		<li><a onclick="cetak(this,'+row.id+')" data-tipe="sj" class="skeyF8">Cetak SJ - F8</a></li>';
			    		    html_buttonprints += '	</ul>';
			    		    html_buttonprints += '</div>';
		    			return html_buttonaction+"<div class='btn btn-xs btn-danger no-margin-action skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus - Del' onclick='hapus_sj(this,"+row.id+")' data-message='"+row.hapussj+"' data-tipe='header'><i class='fa fa-trash'></i></div>"+html_buttonprints;
			    	}
				},
			    {
			    	"data" : "tglsj",
			    	"className" : "menufilter numberfilter"
			    },
			    {
			    	"data" : "nosj",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "namatoko",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "wilid",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "expedisi",
			    	"className" : "menufilter textfilter"
			    },
			]
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
			order 		: [[ 1, 'asc' ]],
			rowCallback: function(row, data, index) {
				// console.log(data);
			},
			columns 	: [
				{
					"data" : "action",
					"orderable" : false
				},
				{
			    	"data" : "tglproforma",
			    },
			    {
			    	"data" : "nonota",
			    },
				{
					"data" : "temponota",
					"className" : "text-right"
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
			order 		: [[ 1, 'asc' ]],
			rowCallback: function(row, data, index) {
				// console.log(data);
			},
			columns 	: [
				{
					"data" : "action",
					"orderable" : false,
				},
				{
					"data" : "nokoli"
				},
				{
					"data" : "keterangan"
				},
			],
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
		
		$('a.toggle-vis-2').on( 'click', function (e) {
			e.preventDefault();

			// Get the column API object
			var column = table2.column( $(this).attr('data-column') );

			// Toggle the visibility
			column.visible( ! column.visible() );
			$('#eye-detail'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
		} );

		$('a.toggle-vis-3').on( 'click', function (e) {
			e.preventDefault();

			// Get the column API object
			var column = table3.column( $(this).attr('data-column') );

			// Toggle the visibility
			column.visible( ! column.visible() );
			$('#eye-detail'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
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

				if($('#modalToko').is(':visible')){
					if(ele.className == 'selected'){
						pilih_toko();
					}else if(ele.id == 'btnPilihToko'){
						pilih_toko();
					}else if(ele.id == 'txtQueryToko'){
						search_toko($(ele).val());
					}
					return false;
				}

				if($('#modalSJInsert').is(':visible')){
					if(!koliinsert_status){
						if(ele.id == 'toko'){
							$('#modalToko').modal('show');
		                    $('#modalToko #txtQueryToko').val($(ele).val());
		                    $('#modalToko').on('shown.bs.modal', function() {
								$('#modalToko #txtQueryToko').focus();
							});
		                    search_toko($(ele).val());
						}else{
							submit_sj();
						}
					}else{
						submit_sjdt();
					}
					return false;
				}

				if($('#modalKewenangan').is(':visible')){
					submit_kewenangan();
					return false;
				}
			}
		});

		$('#tbodyToko').on('click', 'tr', function(){
			$('.selected').removeClass('selected');
			$(this).addClass("selected");
		});

		$('#btnPilihToko').on('click', function(){
			pilih_toko();
		});
		$('#modalToko table.tablepilih tbody').on('dblclick', 'tr', function(){
       		 pilih_toko();
        });
		$('#btnSubmitSJInsert').on('click', function(){
			if(!koliinsert_status){
				submit_sj();
			}else{
				submit_sjdt();
			}
		});

		$('#btnSubmitKewenangan').click(function(){
	    	submit_kewenangan();
	    });

	    $('#modalSJInsert').on('hidden.bs.modal', function() {
			koliinsert_status = null;
		});

		$(document.body).on("keyup", function(e){
			if($('#modalSJInsert').is(':visible')){
				if(!koliinsert_status){
					var is_toko_kosong = check_value('modalSJInsert',['toko']);
					var is_kosong = check_value('modalSJInsert',['keterangankoli','dari','untuk','alamattitipan','notelp','nokolititipan']);

					if(is_toko_kosong){
						toogle_column('modalSJInsert',['keterangankoli','dari','untuk','alamattitipan','notelp','nokolititipan'], true);
						$('#modalSJInsert #tglproforma').val('{{ Carbon\Carbon::now()->format("d-m-Y") }}');
					}else{
						toogle_column('modalSJInsert',['keterangankoli','dari','untuk','alamattitipan','notelp','nokolititipan']);
					}

					if(is_kosong){
						toogle_column('modalSJInsert',['toko','tglproforma','keterangansj'],true);
					}else{
						toogle_column('modalSJInsert',['toko','tglproforma','keterangansj','alamat','daerah','kota','wilid','nit'],false,true);
					}
				}
			}
		});

		$('#table1 tbody').on('dblclick', 'tr', function(){
			var data = table.row(this).data();
			$.ajax({
	        	type: 'GET',
				url: '{{route("suratjalan.view")}}',
				data: {id: data.id},
				dataType: "json",
	        	success: function(data){
	          		tabledoubleclick.clear();
	          		tabledoubleclick.rows.add([
			            {'0':'1.', '1':'Tgl. SJ', '2':data.tglsj},
			            {'0':'2.', '1':'No. SJ', '2':data.nosj},
			            {'0':'3.', '1':'Toko', '2':data.toko},
			            {'0':'4.', '1':'Alamat', '2':data.alamat},
			            {'0':'5.', '1':'Kota', '2':data.kota},
			            {'0':'6.', '1':'Daerah', '2':data.daerah},
			            {'0':'7.', '1':'WILID', '2':data.wilid},
			            {'0':'8.', '1':'No. Induk Toko', '2':data.nit},
			            {'0':'9.', '1':'Print', '2':data.print},
			            {'0':'10.', '1':'Tgl. Print SJ', '2':data.tglprintsj},
			            {'0':'11.', '1':'Keterangan SJ', '2':data.keterangansj},
			            {'0':'12.', '1':'Titipan Keterangan', '2':data.titipanketerangan},
			            {'0':'13.', '1':'Titipan Dari', '2':data.titipandari},
			            {'0':'14.', '1':'Titipan Untuk', '2':data.titipanuntuk},
			            {'0':'15.', '1':'Titipan Alamat', '2':data.titipanalamat},
			            {'0':'16.', '1':'Titipan No. Telepon', '2':data.titipannotelepon},
			            {'0':'17.', '1':'Total Koli', '2':data.totalkoli},
			            {'0':'18.', '1':'Last Updated By', '2':data.lastupdatedby},
			            {'0':'19.', '1':'Last Updated On', '2':data.lastupdatedon},
	          		]);
	          		tabledoubleclick.draw();
	          		$('#modalDoubleClickSuratJalan #myModalLabel').html('Data Surat Jalan');
	          		$('#modalDoubleClickSuratJalan').modal('show');
	        	}
	      	});
		});

		$('#table2 tbody').on('dblclick', 'tr', function(){
			var data = table2.row(this).data();
			$.ajax({
	        	type: 'GET',
				url: '{{route("suratjalandetail.view")}}',
				data: {id: data.id},
				dataType: "json",
	        	success: function(data){
	          		tabledoubleclick.clear();
	          		tabledoubleclick.rows.add([
			            {'0':'1.', '1':'Tgl. Proforma', '2':data.tglproforma},
			            {'0':'2.', '1':'No. Nota', '2':data.nonota},
			            {'0':'3.', '1':'Salesman', '2':data.salesman},
			            {'0':'4.', '1':'Tipe Transaksi', '2':data.tipetransaksi},
			            {'0':'5.', '1':'Tgl. Check', '2':data.tglcheck},
			            {'0':'6.', '1':'Checker 1', '2':data.checker1},
			            {'0':'7.', '1':'Checker 2', '2':data.checker2},
			            {'0':'8.', '1':'Last Updated By', '2':data.lastupdatedby},
			            {'0':'9.', '1':'Last Updated On', '2':data.lastupdatedon},
	          		]);
	          		tabledoubleclick.draw();
	          		$('#modalDoubleClickSuratJalan #myModalLabel').html('Data Surat Jalan Detail');
	          		$('#modalDoubleClickSuratJalan').modal('show');
	        	}
	      	});
		});

		$('#table3 tbody').on('dblclick', 'tr', function(){
			var data = table3.row(this).data();
			var tipe = data.tipe;
			if(tipe == 'npjdk'){
				url = '{{route("notapenjualan.detailkoli")}}';
			}else{
				url = '{{route("suratjalantitipan.view")}}';
			}

			$.ajax({
	        	type: 'GET',
				url: url,
				data: {id: data.id},
				dataType: "json",
	        	success: function(data){
	          		tabledoubleclick.clear();
	          		tabledoubleclick.rows.add([
			            {'0':'1.', '1':'No. Koli', '2':data.nokoli},
			            {'0':'2.', '1':'Keterangan', '2':data.keterangan},
			            {'0':'3.', '1':'Last Updated By', '2':data.lastupdatedby},
			            {'0':'4.', '1':'Last Updated On', '2':data.lastupdatedon},  
	          		]);
	          		tabledoubleclick.draw();
	          		if(tipe == 'npjdk'){
	          			$('#modalDoubleClickSuratJalan #myModalLabel').html('Data Nota Penjualan Detail Koli');
	          		}else{
	          			$('#modalDoubleClickSuratJalan #myModalLabel').html('Data Surat Jalan Detail Titipan');
	          		}
	          		$('#modalDoubleClickSuratJalan').modal('show');
	        	}
	      	});
		});

		table.on('select', function ( e, dt, type, indexes ){
	        // table_index = indexes;
	        // fokus       = 'header';
	        var rowData = table.rows(indexes).data().toArray();
	        $.ajaxSetup({
	            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
	        });

	        $.ajax({
				type: 'POST',
				data: {id: rowData[0].id},
				dataType: "json",
				url: '{{route("suratjalandetail.data")}}',
				success: function(data){
					table2.clear();
					table3.clear();
					for (var i = 0; i < data.suratjalandetail.length; i++) {
						var html = "<div class='btn btn-xs btn-danger no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Hapus' onclick='hapus_sjd(this,"+data.suratjalandetail[i].id+")' data-message='"+data.suratjalandetail[i].hapus+"'><i class='fa fa-trash'></i></div>";
						table2.row.add({
							action : '',
							tglproforma : data.suratjalandetail[i].tglproforma,
							nonota : data.suratjalandetail[i].nonota,
							temponota : data.suratjalandetail[i].temponota,
							id : data.suratjalandetail[i].id,
						});
					}

					for (var i = 0; i < data.sjdt.length; i++) {
						var html = "<div class='btn btn-xs btn-danger no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Hapus' onclick='hapus_sjdt(this,"+data.sjdt[i].id+")' data-message='"+data.sjdt[i].hapus+"' data-tipe='"+data.sjdt[i].tipe+"'><i class='fa fa-trash'></i></div>";
						table3.row.add({
							action : '',
							nokoli : data.sjdt[i].nokoli,
							keterangan : data.sjdt[i].keterangan,
							id : data.sjdt[i].id,
							tipe : data.sjdt[i].tipe,
						});
					}
					table2.draw();
					table3.draw();
				},
				error: function(data){
					console.log(data);
				}
			});
		});

		// table2.on('select', function ( e, dt, type, indexes ){
	 //        table2_index = indexes;
	 //        fokus       = 'detail';
	 //  //       var rowData = table2.rows( indexes ).data().toArray();
	 //  //       $.ajaxSetup({
	 //  //           headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
	 //  //       });

	 //  //       $.ajax({
		// 	// 	type: 'POST',
		// 	// 	data: {id: rowData[0].id},
		// 	// 	dataType: "json",
		// 	// 	url: '{{route("suratjalantitipan.data")}}',
		// 	// 	success: function(data){
		// 	// 		table3.clear();
		// 	// 		console.log(data);
		// 	// 		for (var i = 0; i < data.sjdt.length; i++) {
		// 	// 			var html = "<div class='btn btn-xs btn-danger no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Hapus' onclick='hapus_sjdt(this,"+data.sjdt[i].id+")' data-message='"+data.sjdt[i].hapus+"' data-tipe='"+data.sjdt[i].tipe+"'><i class='fa fa-trash'></i></div>";
		// 	// 			table3.row.add({
		// 	// 				action : '',
		// 	// 				nokoli : data.sjdt[i].nokoli,
		// 	// 				keterangan : data.sjdt[i].keterangan,
		// 	// 				id : data.sjdt[i].id,
		// 	// 				tipe : data.sjdt[i].tipe,
		// 	// 			});
		// 	// 		}
		// 	// 		table3.draw();
		// 	// 	},
		// 	// 	error: function(data){
		// 	// 		console.log(data);
		// 	// 	}
		// 	// });
		// });
		// table3.on('select', function ( e, dt, type, indexes ){
  //           fokus       = 'subdetail';
  //           table3_index = indexes;
  //   	});
		table.on('deselect', function ( e, dt, type, indexes ) {
		    table2.clear().draw();
		    table3.clear().draw();
		});
		table2.on('deselect', function ( e, dt, type, indexes ) {
		    table3.clear().draw();
		});

		$('.tgl').change(function(){
			table.draw();
		});
	});

	function open_formsj(){
		$.ajax({
			type: 'GET',
			dataType: "json",
			url: '{{route("suratjalantitipan.nosj")}}',
			success: function(data){
				clear_column('modalSJInsert');
				toogle_column('modalSJInsert',['nokoli','nokolititipan']);
				toogle_column('modalSJInsert',['toko','tglproforma','keterangansj','keterangankoli','dari','untuk','alamattitipan','notelp','nokolititipan'],true);
				$('#modalSJInsert #tglsj').val('{{ Carbon\Carbon::now()->format("d-m-Y") }}');
				$('#modalSJInsert #tglproforma').val('{{ Carbon\Carbon::now()->format("d-m-Y") }}');
				// $('#modalSJInsert #nosj').val('data.nosj');
				$('#modalSJInsert #nosj').val('');
				$('#modalSJInsert').modal('show');
				$('#modalSJInsert').on('shown.bs.modal', function() {
					$('#modalSJInsert #toko').focus();
				});
			},
			error: function(data){
				console.log(data);
			}
		});
	}
	
	function tambah_sjd(e,data){
		var message = $(e).data('message');
		if(message == 'tambah'){
			@cannot('suratjalan.create')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			open_formsj();
			@endcannot
		}else{
			swal('Ups!', message,'error');
		}
	}

	function tambah_koli(e,data){
		var message = $(e).data('message');
		if(message == 'tambah'){
			@cannot('suratjalantitipan.create')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			koliinsert_status = true;
			var tokoid = $(e).data('tokoid');
			clear_column('modalSJInsert');
			$('#modalSJInsert #suratJalanId').val(data);
			if(tokoid){
				swal('Ups!', 'Penambahan koli hanya bisa dilakukan untuk Titipan. Hubungi manager anda.','error');
				// toogle_column('modalSJInsert',['toko','tglproforma','keterangansj','keterangankoli','dari','untuk','alamattitipan','notelp','nokolititipan']);
				// toogle_column('modalSJInsert',['nokoli'], true);
				// $('#modalSJInsert').modal('show');
				// $('#modalSJInsert').on('shown.bs.modal', function() {
				// 	$('#modalSJInsert #nokoli').focus();
				// });
			}else{
				toogle_column('modalSJInsert',['toko','tglproforma','keterangansj','keterangankoli','dari','untuk','alamattitipan','notelp','nokoli']);
				toogle_column('modalSJInsert',['nokolititipan'], true);
				$('#modalSJInsert').modal('show');
				$('#modalSJInsert').on('shown.bs.modal', function() {
					$('#modalSJInsert #nokolititipan').focus();
				});
			}
			@endcannot
		}else{
			swal('Ups!', message,'error');
		}
	}

	function hapus_sj(e,data){
		var message = $(e).data('message');
		if(message == 'auth'){
			@cannot('suratjalan.hapus')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			$('#modalKewenangan #id').val(data);
    		$('#modalKewenangan #permission').val('suratjalan.hapus');
    		$('#modalKewenangan #about').val('deletesj');
    		$('#modalKewenangan').modal('show');
    		@endcannot
		}else{
			swal('Ups!', message,'error');
		}
	}

	function hapus_sjd(e,data){
		var message = $(e).data('message');
		if(message == 'auth'){
			@cannot('suratjalandetail.hapus')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			$('#modalKewenangan #id').val(data);
    		$('#modalKewenangan #permission').val('suratjalandetail.hapus');
    		$('#modalKewenangan #about').val('deletesjd');
    		$('#modalKewenangan').modal('show');
    		@endcannot
		}else{
			swal('Ups!', message,'error');
		}
	}

	function hapus_sjdt(e,data){
		var message = $(e).data('message');
		if(message == 'auth'){
			@cannot('suratjalantitipan.hapus')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			tipe = $(e).data('tipe');
			if(tipe == 'npjdk'){
				about = 'deletenpjdk';
			}else{
				about = 'deletesjdt';
			}
			$('#modalKewenangan #id').val(data);
    		$('#modalKewenangan #permission').val('suratjalantitipan.hapus');
    		$('#modalKewenangan #about').val(about);
    		$('#modalKewenangan').modal('show');
    		@endcannot
		}else{
			swal('Ups!', message,'error');
		}
	}

	function submit_sj(){
		tipe_edit = true;
		textToko  = $('#modalSJInsert #toko').val();
		textTglProforma = $('#modalSJInsert #tglproforma').val();
		if(textToko && textTglProforma){
			@cannot('suratjalan.create')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			$.ajaxSetup({
				headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
			});

			$.ajax({
				type: 'POST',
				data: {
					toko         : $("#modalSJInsert #tokoId").val(),
					tglproforma  : $("#modalSJInsert #tglproforma").val(),
					keterangansj : $("#modalSJInsert #keterangansj").val(),
				},
				dataType: "json",
				url: '{{route("suratjalan.create")}}',
				success: function(data){
					if(data.success){
						$('#modalSJInsert').modal('hide');
						table.ajax.reload(null, true);
						tipe_edit = null;
						setTimeout(function(){
							table.row(0).select();
						},1000);
					}else{
						swal('Ups!', data.error,'error');
					}
				},
				error: function(data){
					console.log(data);
				}
			});
			@endcannot
		}else{
			@cannot('suratjalantitipan.create')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			var is_titipankosong = check_value('modalSJInsert',['keterangankoli','dari','untuk','alamattitipan','notelp','nokolititipan'],true);
			if(is_titipankosong){
				swal("Ups!", "Tidak bisa simpan record Surat jalan. Data titipan belum diisi dengan lengkap", "error");
			}else{
				$.ajaxSetup({
					headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
				});

				$.ajax({
					type: 'POST',
					data: {
						tglproforma    : $("#modalSJInsert #tglproforma").val(),
						keterangankoli : $("#modalSJInsert #keterangankoli").val(),
						dari           : $("#modalSJInsert #dari").val(),
						untuk          : $("#modalSJInsert #untuk").val(),
						alamattitipan  : $("#modalSJInsert #alamattitipan").val(),
						notelp         : $("#modalSJInsert #notelp").val(),
						nokolititipan  : $("#modalSJInsert #nokolititipan").val(),
					},
					dataType: "json",
					url: '{{route("suratjalantitipan.create")}}',
					success: function(data){
						$('#modalSJInsert').modal('hide');
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
	}

	function submit_sjdt(){
		@cannot('suratjalan.cekkoli')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		$.ajaxSetup({
			headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
		});

		$.ajax({
			type: 'POST',
			data: {
				nokoli        : $("#modalSJInsert #nokoli").val(),
				nokolititipan : $("#modalSJInsert #nokolititipan").val(),
				id            : $("#modalSJInsert #suratJalanId").val(),
			},
			dataType: "json",
			url: '{{route("suratjalan.cekkoli")}}',
			success: function(data){
				if(data.success){
					$('#modalSJInsert').modal('hide');
					table.row('#'+table_index).select();
				}else{
					swal("Ups!", data.error, "error");
				}
			},
			error: function(data){
				console.log(data);
			}
		});
		@endcannot
	}

	function cetak(e, data){
		@cannot('suratjalan.cetak')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
    	var tipe = $(e).data('tipe');
    	window.open('{{route("suratjalan.cetak")}}'+'?id='+data+'&tipe='+tipe);
    	@endcannot
    }

	function check_value(modal, column, all){
		column_kosong = 0;
		all_kosong = false;
		for (var i = 0; i < column.length; i++) {
			if(!$('#'+modal+' #'+column[i]).val()){
				column_kosong++; 
			}
		}

		if(column_kosong == column.length){
			all_kosong = true;
		}

		if(all){
			if(column_kosong > 0){
				all_kosong = true;
			}
		}

		return all_kosong;
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
				if(empty){
					$('#'+modal+' #'+column[i]).val('');
				}
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

	// function search_toko(query){
 //        $.ajax({
 //            type: 'GET',
 //            url: '{{route("lookup.gettoko",null)}}/' + query,
 //            success: function(data){
 //                var toko = JSON.parse(data);
 //                $('#tbodyToko tr').remove();
 //                var x = '';
 //                if (toko.length > 0) {
 //                    for (var i = 0; i < toko.length; i++) {
 //                        x += '<tr tabindex=0>';
 //                        x += '<td>'+ toko[i].id +'</td>';
 //                        x += '<td>'+ toko[i].namatoko;
 //                        x +=        '<input type="hidden" class="idtoko" value="'+ toko[i].id +'">';
 //                        x +=        '<input type="hidden" class="kotatoko" value="'+ toko[i].kota +'">';
 //                        x +=        '<input type="hidden" class="kecamatantoko" value="'+ toko[i].kecamatan +'">';
 //                        x +=        '<input type="hidden" class="customwilayahtoko" value="'+ toko[i].customwilayah +'">';
 //                        x +=        '<input type="hidden" class="tokoid" value="'+ toko[i].id +'">';
 //                        x +=        '<input type="hidden" class="jwkirim" value="'+ toko[i].jwkirim +'">';
 //                        x +=        '<input type="hidden" class="jwsales" value="'+ toko[i].jwsales +'">';
 //                        x +=        '<input type="hidden" class="karyawanidsalesman" value="'+ toko[i].karyawanidsalesman +'">';
 //                        x +=        '<input type="hidden" class="namakaryawan" value="'+ toko[i].namakaryawan +'">';
 //                        x += '</td>';
 //                        x += '<td>'+ toko[i].customwilayah +'</td>';
 //                        x += '<td>'+ toko[i].alamat +'</td>';
 //                        x += '<td>'+ toko[i].kecamatan +'</td>';
 //                        x += '<td>'+ toko[i].kota +'</td>';
 //                        x += '</tr>';
 //                    }
 //                }else {
 //                    x += '<tr><td colspan="10" class="text-center">Toko tidak ada detail</td></tr>';
 //                }
 //                $('#tbodyToko').append(x);
 //            },
 //            error: function(data){
 //                console.log(data);
 //            }
 //        });
 //    }

    // function pilih_toko(){
    //     if ($('#tbodyToko').find('tr.selected td').eq(1).text() == '') {
    //         swal("Ups!", "Toko belum dipilih.", "error");
    //         return false;
    //     }else {
    //         $('#toko').val($('#tbodyToko').find('tr.selected td').eq(1).text());
    //         $('#tokoId').val($('#tbodyToko').find('tr.selected td .idtoko').val());
    //         $('#alamat').val($('#tbodyToko').find('tr.selected td').eq(3).text());
    //         var id = $('#tbodyToko').find('tr.selected td .idtoko').val();

    //         $.ajax({
    //             type: 'GET',
    //             url: '{{route("lookup.gettokodetail",null)}}/' + id,
    //             dataType: 'json',
    //             success: function(data){
    //                 $('#kota').val(data.kota);
    //                 $('#daerah').val(data.kecamatan);
    //                 $('#wilid').val(data.customwilayah);
    //                 $('#idtoko').val(data.idtoko);
    //             },
    //             error: function(data){
    //             	console.log(data);
    //             } 
    //         });

    //         $('#modalToko').modal('hide');
    //         $('#modalSJIsert #tglproforma').focus();
    //     }
    // }

    function submit_kewenangan(){
    	$.ajax({
			type: 'POST',
			url: '{{route("suratjalan.kewenangan")}}',
			data: {
				username   : $('#modalKewenangan #uxserKewenangan').val().trim().toUpperCase(),
				password   : $('#modalKewenangan #pxassKewenangan').val(),
				id         : $('#modalKewenangan #id').val(),
				permission : $('#modalKewenangan #permission').val(),
				about      : $('#modalKewenangan #about').val(),
			},
			dataType: "json",
			success: function(data){
                $('#modalKewenangan #uxserKewenangan').val('').change();
                $('#modalKewenangan #pxassKewenangan').val('').change();

				if(data.success){
					$('#modalKewenangan').modal('hide');
					if(data.tipe == 'deletesjd'){
						table.row('#'+table_index).select();
					}else if(data.tipe == 'deletesjdt' || data.tipe == 'deletenpjdk'){
						table.row('#'+table_index).select();
						setTimeout(function(){
							table2.row('#'+table2_index).select();
						}, 400);
					}else{
						table.ajax.reload(null, true);
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
</script>
@endpush