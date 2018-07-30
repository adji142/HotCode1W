@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
  	<li class="breadcrumb-item"><a href="{{ route('pengiriman.index') }}">Pengiriman</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>Pengiriman</h2>
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
			                	<label style="margin-right: 10px;">Tgl. Kirim</label>
								<input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
								<label>-</label>
								<input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
			                </div>
		                </form>
					</div>
					<div class="col-md-6 text-right">
						@can('pengiriman.create')
						<a onclick="open_formp()" class="btn btn-success" id="skeyIns"><i class="fa fa-plus"></i> Tambah Pengiriman - Ins</a>
						@endcan
					</div>
				</div>
				<div class="row-fluid">
					<table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="1%">A</th>
							    <th>Tgl. Kirim</th>
							    <th>No. Kirim</th>
							    <th>Sopir</th>
							    <th>Tgl. Kembali</th>
							    <th>Armada Kirim</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
						<p>
							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
							<a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Tgl. Kirim</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> No. Kirim</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Sopir</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Tgl. Kembali</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> Armada Kirim</a>
						</p>
					</div>
				</div>
				{{-- <hr> --}}
				<div class="row-fluid">
					<table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
						<thead>
							<tr>
							    <th width="1%">A</th>
							    <th width="10%">No. SJ</th>
							    <th width="10%">Tgl. SJ</th>
							    <th>Toko</th>
							    <th width="15%">IDWIL</th>
							    <th width="15%">Total Koli/Packing</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
						<p>
							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
							<a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> No. SJ</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Tgl. SJ</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Toko</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis-2" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> IDWIL</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis-2" data-column="5"><i id="eye-detail5" class="fa fa-eye"></i> Total Koli/Packing</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Data double click -->
<div class="modal fade" id="modalDoubleClickPengiriman" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
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

<!-- PENGIRIMAN MODAL -->
<div class="modal fade" id="modalPInsert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Penjualan - Pengiriman Insert</h4>
			</div>
			<form class="form-horizontal" method="post">
				<input type="hidden" id="pengirimanId" class="form-clear" value="">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label>Tgl. Kirim</label>
								<input type="text" id="tglkirim" class="form-control form-clear" placeholder="Tgl. Kirim" readonly tabindex="-1">
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label>No. Kirim</label>
								<input type="text" id="nokirim" class="form-control form-clear" placeholder="No. Kirim" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Tgl. Kembali</label>
								<input type="text" id="tglkembali" class="tgl form-control form-clear" placeholder="Tgl. Kembali" data-inputmask="'mask': 'd-m-y'" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Sopir</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-search"></i></span>
									<input type="text" id="sopir" class="form-control form-clear" placeholder="Sopir" readonly tabindex="-1">
									<input type="hidden" id="sopirid" name="sopirid" class="form-clear" value="">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Kernet</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-search"></i></span>
									<input type="text" id="kernet" class="form-control form-clear" placeholder="Kernet" readonly tabindex="-1">
									<input type="hidden" id="kernetid" name="kernetid" class="form-clear" value="">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Armada Kirim</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-search"></i></span>
									<input type="text" id="armadakirim" class="form-control form-clear" placeholder="Armada Kirim" readonly tabindex="-1">
									<input type="hidden" id="armadakirimId" name="armadakirimId" class="form-clear" value="">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label>KM Berangkat</label>
								<input type="text" id="kmberangkat" class="form-control form-clear" placeholder="KM Berangkat" readonly tabindex="-1">
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label>KM Kembali</label>
								<input type="text" id="kmkembali" class="form-control form-clear" placeholder="KM Kembali" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Catatan</label>
								<input type="text" id="catatan" class="form-control form-clear" placeholder="Catatan" readonly tabindex="-1">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="btnSubmitPInsert" class="btn btn-primary">Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- SURAT JALAN MODAL -->
<div class="modal fade" id="modalSJInsert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Penjualan - Surat Jalan Insert</h4>
			</div>
			<form class="form-horizontal" method="post">
				<input type="hidden" id="pId" class="form-clear" value="">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label>No. SJ</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-search"></i></span>
									<input type="text" id="nosj" class="form-control form-clear" placeholder="No. SJ">
									<input type="hidden" id="sjId" name="sjId" class="form-clear" value="">
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label>Tgl. SJ</label>
								<input type="text" id="tglsj" class="form-control form-clear" placeholder="Tgl. SJ" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Toko</label>
								<input type="text" id="toko" class="form-control form-clear" placeholder="Toko" readonly tabindex="-1">
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
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Kota</label>
								<input type="text" id="kota" class="form-control form-clear" placeholder="Kota" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Daerah</label>
								<input type="text" id="daerah" class="form-control form-clear" placeholder="Daerah" readonly tabindex="-1">
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
					<hr class="hr-text" data-content="KHUSUS TITIPAN">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Keterangan Kolian</label>
								<input type="text" id="keterangankoli" class="form-control form-clear" placeholder="Keterangan Kolian" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Dari</label>
								<input type="text" id="dari" class="form-control form-clear" placeholder="Dari" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Untuk</label>
								<input type="text" id="untuk" class="form-control form-clear" placeholder="Untuk" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Alamat</label>
								<input type="text" id="alamattitipan" class="form-control form-clear" placeholder="Alamat" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>No. Telepon</label>
								<input type="text" id="notelp" class="form-control form-clear" placeholder="No. Telepon" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<hr class="hr-text">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Total Koli/Packing</label>
								<input type="text" id="totalkoli" class="form-control form-clear" placeholder="Total Koli/Packing" readonly tabindex="-1">
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

{{-- <!-- STAFF MODAL -->
<div class="modal fade" id="modalSJ" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Pencarian Surat Jalan</h4>
			</div>
			<form class="form-horizontal" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="query">Masukkan kata kunci pencarian</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="querySJ" class="form-control" placeholder="Masukkan kata kunci pencarian">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<table id="staff" class="table table-bordered table-striped tablepilih">
								<thead>
									<tr>
										<th class="text-center">No SJ</th>
                                        <th class="text-center">Tgl SJ</th>
                                        <th class="text-center">Nama Toko</th>
                                        <th class="text-center">Alamat</th>
									</tr>
								</thead>
								<tbody id="tbodySJ">
				                    <tr class="kosong">
				                    	<td colspan="3" class="text-center">Tidak ada detail</td>
				                    </tr>
				                </tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="btnPilihSJ" class="btn btn-primary">Pilih</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>
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
		{text : '',filter : '='},
		{text : '',filter : '='},
		{text : '',filter : '='},
		{text : '',filter : '='},
		{text : '',filter : '='},
		{text : '',filter : '='},
	];
	var filter_number = ['<=','<','=','>','>='];
	var filter_text = ['=','!='];
	var tipe = ['Find','Filter'];
	var column_index = 0;
	var last_index = '';
	var context_menu_number_state = 'hide';
	var context_menu_text_state = 'hide';
	var tipe_edit = null,
	    update_pengiriman = null;
	var table,
	    table2,
	    tabledoubleclick,
	    table_index,
	    table2_index,
	    tipe_staff,fokus;

    // Lookup
    lookupsopir();
    lookupkernet();
    lookuparmada();
    lookupsuratjalan();

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
				url			: '{{ route("pengiriman.data") }}',
				data 		: function ( d ) {
					d.custom_search = custom_search;
					d.tglmulai 		= $('#tglmulai').val();
					d.tglselesai 	= $('#tglselesai').val();
					d.tipe_edit     = tipe_edit;
				},
			},
			order   : [[ 1, 'asc' ]],
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
			    		var html  = "<div class='btn btn-xs btn-success no-margin-action skeyF1' data-toggle='tooltip' data-placement='bottom' title='Tambah- F1' onclick='tambah_pd(this,"+row.id+")' data-message='"+row.tambahpd+"' data-tipe='header'><i class='fa fa-plus'></i></div>";
			    			html += "<div class='btn btn-xs btn-warning no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='Update - F2' onclick='update_p(this,"+row.id+")' data-message='"+row.updatep+"' data-tipe='header'><i class='fa fa-pencil'></i></div>";
			    		    html += "<div class='btn btn-xs btn-danger no-margin-action skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus - Del' onclick='hapus_p(this,"+row.id+")' data-message='"+row.hapusp+"' data-tipe='header'><i class='fa fa-trash'></i></div>";
			    		    html += "<div class='btn btn-xs btn-primary no-margin-action skeyF3' data-toggle='tooltip' data-placement='bottom' title='Cetak Daftar Kiriman - F3' onclick='cetak(this,"+row.id+")' data-tipe='header'><i class='fa fa-print'></i></div>";
		    			return html;
			    	}
				},
			    {
			    	"data" : "tglkirim",
			    	"className" : "menufilter numberfilter"
			    },
			    {
			    	"data" : "nokirim",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "sopir",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "tglkembali",
			    	"className" : "menufilter numberfilter"
			    },
			    {
			    	"data" : "armadakirim",
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
			order: [[ 1, 'asc' ]],
			rowCallback: function(row, data, index) {
				// console.log(data);
			},
			columns 	: [
				{"data" : "action", "orderable" : false},
				{"data" : "nosj",},
			    {"data" : "tglsj",},
				{"data" : "toko",},
				{"data" : "wilid",},
				{"data" : "totalkoli", "className" : "text-right"},
			],
		});

		tabledoubleclick = $('#doubleclickData').DataTable({
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

				// if($('#modalStaff').is(':visible')){
				// 	if(ele.className == 'selected'){
				// 		pilih_staff(tipe_staff);
				// 	}else if(ele.id == 'btnPilihStaff'){
				// 		pilih_staff(tipe_staff);
				// 	}else if(ele.id == 'query'){
				// 		search_staff($(ele).val(),tipe_staff);
				// 		return false;
				// 	}
				// }

				// if($('#modalArmadaKirim').is(':visible')){
				// 	if(ele.className == 'selected'){
				// 		pilih_armada();
				// 	}else if(ele.id == 'btnPilihArmada'){
				// 		pilih_armada();
				// 	}else if(ele.id == 'queryArmada'){
				// 		search_armada($(ele).val());
				// 		return false;
				// 	}
				// }

				// if($('#modalSJ').is(':visible')){
				// 	console.log(ele);
				// 	if(ele.className == 'selected'){
				// 		pilih_sj();
				// 	}else if(ele.id == 'btnPilihSJ'){
				// 		pilih_sj();
				// 	}else if(ele.id == 'querySJ'){
				// 		search_sj($(ele).val());
				// 		return false;
				// 	}
				// }

				//cek kalau sopir sudah terisi
				if($('#modalPInsert').is(':visible')){
					if(!update_pengiriman){
						if(ele.id == 'catatan' || ele.id == 'btnSubmitPInsert'){
							submit_p();
							return false;
						}
					}else{
						if(ele.id == 'tglkembali' || ele.id == 'kmkembali' || ele.id == 'catatan'){
							submit_updatep(); 
							return false;
						}
					}
				}
				// if($('#modalPInsert').is(':visible')){
				// 	if(!update_pengiriman){
				// 		// if(ele.id == 'sopir'){
				// 		// 	if(!$(ele).is('[readonly]')){
				// 		// 		tipe_staff = 'sopir';
				// 		// 		$('#modalStaff').modal('show');
				// 		// 		$('#modalStaff #query').val($(ele).val());
				// 		// 		$('#modalStaff').on('shown.bs.modal', function() {
				// 		// 			$('#query').focus();
				// 		// 		});
				// 		// 		search_staff($(ele).val(),tipe_staff);
				// 		// 	}
				// 		// }else if(ele.id == 'kernet'){
				// 		// 	if(!$(ele).is('[readonly]')){
				// 		// 		tipe_staff = 'kernet';
				// 		// 		$('#modalStaff').modal('show');
				// 		// 		$('#modalStaff #query').val($(ele).val());
				// 		// 		$('#modalStaff').on('shown.bs.modal', function() {
				// 		// 			$('#query').focus();
				// 		// 		});
				// 		// 		search_staff($(ele).val(),tipe_staff);
				// 		// 	}
				// 		// }else if(ele.id == 'armadakirim'){
				// 		// 	if(!$(ele).is('[readonly]')){
				// 		// 		$('#modalArmadaKirim').modal('show');
				// 		// 		$('#modalArmadaKirim #queryArmada').val($(ele).val());
				// 		// 		$('#modalArmadaKirim').on('shown.bs.modal', function() {
				// 		// 			$('#queryArmada').focus();
				// 		// 		});
				// 		// 		search_armada($(ele).val());
				// 		// 	}
				// 		// }else if(ele.id == 'catatan' || ele.id == 'btnSubmitPInsert'){
				// 		if(ele.id == 'catatan' || ele.id == 'btnSubmitPInsert'){
				// 			submit_p();
				// 		}
				// 	}else{
				// 		if(ele.id == 'tglkembali' || ele.id == 'kmkembali' || ele.id == 'catatan'){
				// 			submit_updatep(); 
				// 		}
				// 	}
				// 	return false;
				// }

				// //cek kalau nosj sudah terisi
				// if($('#modalSJInsert').is(':visible')){
				// 	if(ele.id == 'nosj'){
				// 		$('#modalSJ').modal('show');
				// 		$('#modalSJ #querySJ').val($(ele).val());
				// 		$('#modalSJ').on('shown.bs.modal', function() {
				// 			$('#querySJ').focus();
				// 		});
				// 		search_sj($(ele).val());
				// 	}
				// }

				if($('#modalKewenangan').is(':visible')){
					submit_kewenangan();
					return false;
				}
			}
		});

		$('#btnSubmitPInsert').on('click', function(){
			if(update_pengiriman){
				submit_updatep();
			}else{
				submit_p();
			}
		});

		$('#btnSubmitSJInsert').on('click', function(){
			submit_pd();
		});

		$('#btnSubmitKewenangan').click(function(){
	    	submit_kewenangan();
	    });

		// $('#tbodyStaff').on('click', 'tr', function(){
		// 	$('.selected').removeClass('selected');
		// 	$(this).addClass("selected");
		// });

		// $('#tbodyArmada').on('click', 'tr', function(){
		// 	$('.selected').removeClass('selected');
		// 	$(this).addClass("selected");
		// });

		// $('#tbodySJ').on('click', 'tr', function(){
		// 	$('.selected').removeClass('selected');
		// 	$(this).addClass("selected");
		// });

		// $('#btnPilihStaff').on('click', function(){
		// 	pilih_staff(tipe_staff);
		// });
		// $('#modalStaff table.tablepilih tbody').on('dblclick', 'tr', function(){
		// 	pilih_staff(tipe_staff);
		// });
		// $('#btnPilihArmada').on('click', function(){
		// 	pilih_armada();
		// });
		// $('#modalArmadaKirim table.tablepilih tbody').on('dblclick', 'tr', function(){
		// 	pilih_armada();
		// });
		// $('#btnPilihSJ').on('click', function(){
		// 	pilih_sj();
		// });
		// $('#modalSJ table.tablepilih tbody').on('dblclick', 'tr', function(){
		// 		pilih_sj();
		// });

		$('#table1 tbody').on('dblclick', 'tr', function(){
			var data = table.row(this).data();
			$.ajax({
	        	type: 'GET',
				url: '{{route("pengiriman.view")}}',
				data: {id: data.id},
				dataType: "json",
	        	success: function(data){
	          		tabledoubleclick.clear();
	          		tabledoubleclick.rows.add([
			            {'0':'1.', '1':'Tgl. Kirim', '2':data.tglkirim},
			            {'0':'2.', '1':'No. Kirim', '2':data.nokirim},
			            {'0':'3.', '1':'Tgl. Kembali', '2':data.tglkembali},
			            {'0':'4.', '1':'Sopir', '2':data.sopir},
			            {'0':'5.', '1':'Kernet', '2':data.kernet},
			            {'0':'6.', '1':'Armada Kirim', '2':data.armadakirim},
			            {'0':'7.', '1':'KM Berangkat', '2':data.kmberangkat},
			            {'0':'8.', '1':'KM Kembali', '2':data.kmkembali},
			            {'0':'9.', '1':'Catatan', '2':data.catatan},
			            {'0':'10.', '1':'Print', '2':data.print},
			            {'0':'11.', '1':'Tgl. Print', '2':data.tglprint},
			            {'0':'12.', '1':'Last Updated By', '2':data.lastupdatedby},
			            {'0':'13.', '1':'Last Updated On', '2':data.lastupdatedon},
	          		]);
	          		tabledoubleclick.draw();
	          		$('#modalDoubleClickPengiriman #myModalLabel').html('Data Pengiriman');
	          		$('#modalDoubleClickPengiriman').modal('show');
	        	}
	      	});
		});

		$('#table2 tbody').on('dblclick', 'tr', function(){
			var data = table2.row(this).data();
			$.ajax({
	        	type: 'GET',
				url: '{{route("pengirimandetail.view")}}',
				data: {id: data.id},
				dataType: "json",
	        	success: function(data){
	          		tabledoubleclick.clear();
	          		tabledoubleclick.rows.add([
			            {'0':'1.', '1':'No. SJ', '2':data.nosj},
			            {'0':'2.', '1':'Tgl. SJ', '2':data.tglsj},
			            {'0':'3.', '1':'Toko', '2':data.toko},
			            {'0':'4.', '1':'Alamat', '2':data.alamat},
			            {'0':'5.', '1':'Kecamatan', '2':data.kecamatan},
			            {'0':'6.', '1':'Kota', '2':data.kota},
			            {'0':'7.', '1':'WILID', '2':data.wilid},
			            {'0':'8.', '1':'Nomor Induk Toko', '2':data.nit},
			            {'0':'9.', '1':'Total Koli/Packing', '2':data.totalkoli},
			            {'0':'10.', '1':'Keterangan Pending', '2':data.keteranganpending},
			            {'0':'11.', '1':'Last Updated By', '2':data.lastupdatedby},
			            {'0':'12.', '1':'Last Updated On', '2':data.lastupdatedon},
	          		]);
	          		tabledoubleclick.draw();
	          		$('#modalDoubleClickPengiriman #myModalLabel').html('Data Pengiriman Detail');
	          		$('#modalDoubleClickPengiriman').modal('show');
	        	}
	      	});
		});

		table.on('select', function ( e, dt, type, indexes ){
	        var rowData = table.rows( indexes ).data().toArray();
	        $.ajaxSetup({
	            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
	        });

	        $.ajax({
				type: 'POST',
				data: {id: rowData[0].id},
				dataType: "json",
				url: '{{route("pengirimandetail.data")}}',
				success: function(data){
					table2.clear();
					for (var i = 0; i < data.pd.length; i++) {
						var html = "<div class='btn btn-xs btn-danger no-margin-action skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus - Del' onclick='hapus_pd(this,"+data.pd[i].id+")' data-message='"+data.pd[i].hapuspd+"' data-tipe='header'><i class='fa fa-trash'></i></div>";
						table2.row.add({
							action    : html,
							nosj      : data.pd[i].nosj,
							tglsj     : data.pd[i].tglsj,
							toko      : data.pd[i].toko,
							wilid     : data.pd[i].wilid,
							totalkoli : data.pd[i].totalkoli,
							id        : data.pd[i].id,
							DT_RowId  : data.pd[i].DT_RowId,
						});
					}
					table2.draw();
				},
				error: function(data){
					console.log(data);
				}
			});
		});
		table.on('deselect', function ( e, dt, type, indexes ) {
		    table2.clear().draw();
		});

		$('.tgl').change(function(){
			table.draw();
		});
	});
	
	function open_formp(update,id){
		if(update){
			@cannot('pengiriman.update')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			update_pengiriman = true;
			$.ajax({
				type: 'GET',
				url: '{{route("pengiriman.view")}}',
				data: {id: id},
				dataType: "json",
				success: function(data){
					clear_column('modalPInsert');
					toogle_column('modalPInsert',['sopir','kernet','armadakirim','catatan']);
					toogle_column('modalPInsert',['tglkembali','kmkembali','catatan'],true);
					$('#modalPInsert #pengirimanId').val(id);
					$('#modalPInsert #tglkirim').val(data.tglkirim);
					$('#modalPInsert #nokirim').val(data.nokirim);
					$('#modalPInsert #sopir').val(data.sopir);
					$('#modalPInsert #kernet').val(data.kernet);
					$('#modalPInsert #armadakirim').val(data.armadakirim);
					$('#modalPInsert #kmberangkat').val(data.kmberangkat);
					$('#modalPInsert').modal('show');
					$('#modalPInsert').on('shown.bs.modal', function() {
						$('#modalPInsert #tglkembali').focus();
					});
				},
				error: function(data){
					console.log(data);
				}
			});
			@endcannot
		}else{
			@cannot('pengiriman.create')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			$.ajax({
				type: 'GET',
				dataType: "json",
				url: '{{route("pengiriman.nokirim")}}',
				success: function(data){
					clear_column('modalPInsert');
					toogle_column('modalPInsert',['tglkembali','kmkembali','catatan']);
					toogle_column('modalPInsert',['sopir','kernet','armadakirim','catatan'],true);
					$('#modalPInsert #tglkirim').val('{{ Carbon\Carbon::now()->format("d-m-Y") }}');
					$('#modalPInsert #nokirim').val(data.nokirim);
					$('#modalPInsert').modal('show');
					$('#modalPInsert').on('shown.bs.modal', function() {
						$('#modalPInsert #sopir').focus();
					});
				},
				error: function(data){
					console.log(data);
				}
			});
			@endcannot
		}
	}

	function open_formpd(pId){
		clear_column('modalSJInsert');
		$('#modalSJInsert #pId').val(pId);
		$('#modalSJInsert').modal('show');
		$('#modalSJInsert').on('shown.bs.modal', function() {
			$('#modalSJInsert #nosj').focus();
		});
	}
	
	function tambah_pd(e,data){
		@cannot('pengirimandetail.create')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		var message = $(e).data('message');
		if(message == 'tambah'){
			open_formpd(data);
		}else{
			swal('Ups!', message,'error');
		}
		@endcannot
	}

	function update_p(e,data){
		var message = $(e).data('message');
		if(message == 'update'){
			open_formp(true,data);
		}else{
			swal('Ups!', message,'error');
		}
	}

	function hapus_p(e,data){
		var message = $(e).data('message');
		if(message == 'auth'){
			$('#modalKewenangan #id').val(data);
    		$('#modalKewenangan #permission').val('pengiriman.delete');
    		$('#modalKewenangan #about').val('deletep');
    		$('#modalKewenangan').modal('show');
		}else{
			swal('Ups!', message,'error');
		}
	}

	function hapus_pd(e,data){
		var message = $(e).data('message');
		if(message == 'auth'){
			$('#modalKewenangan #id').val(data);
    		$('#modalKewenangan #permission').val('pengirimandetail.delete');
    		$('#modalKewenangan #about').val('deletepd');
    		$('#modalKewenangan').modal('show');
		}else{
			swal('Ups!', message,'error');
		}
	}

	function submit_p(){
		@cannot('pengiriman.create')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		tipe_edit       = true;
		var sopir_text  = $('#modalPInsert #sopirid').val();
		var kernet_text = $('#modalPInsert #kernetid').val();
		var armada_text = $('#modalPInsert #armadakirimId').val();
		if(sopir_text && kernet_text && armada_text){
			$.ajaxSetup({
				headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
			});

			$.ajax({
				type: 'POST',
				data: {
					sopir       : sopir_text,
					kernet      : kernet_text,
					armada      : armada_text,
					kmberangkat : $('#modalPInsert #kmberangkat').val(),
					catatan     : $('#modalPInsert #catatan').val(),
				},
				dataType: "json",
				url: '{{route("pengiriman.create")}}',
				success: function(data){
					if(data.success){
						$('#modalPInsert').modal('hide');
						table.ajax.reload(null, true);
						tipe_edit = null;
						setTimeout(function(){
							table.row('#'+table_index).select();
						},1000);
					}else{
						swal('Ups!', data.error,'error'); 
					}
				},
				error: function(data){
					console.log(data);
				}
			});
		}else{
			if(!sopir_text){
				swal('Ups!', 'Tidak bisa simpan data Pengiriman. TextBox sopir masih kosong. Silahkan dilengkapi','error'); 
			}else if(!kernet_text){
				swal('Ups!', 'Tidak bisa simpan data Pengiriman. TextBox kernet masih kosong. Silahkan dilengkapi','error'); 
			}else{
				swal('Ups!', 'Tidak bisa simpan data Pengiriman. TextBox armada kirim masih kosong. Silahkan dilengkapi','error'); 
			}
		}
		@endcannot
	}

	function submit_pd(){
		@cannot('pengirimandetail.create')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		var nosj  = $('#modalSJInsert #nosj').val();
		if(nosj){
			$.ajaxSetup({
				headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
			});

			$.ajax({
				type: 'POST',
				data: {
					pid  : $('#modalSJInsert #pId').val(),
					sjid : $('#modalSJInsert #sjId').val(),
				},
				dataType: "json",
				url: '{{route("pengirimandetail.create")}}',
				success: function(data){
					if(data.success){
						$('#modalSJInsert').modal('hide');
						table.rows('#'+table_index).select();
						setTimeout(function(){
							open_formpd(data.pid);
						},400);
					}else{
						swal('Ups!', data.error,'error'); 
					}
				},
				error: function(data){
					console.log(data);
				}
			});
		}else{
			swal('Ups!', 'Tidak bisa simpan record. Nilai Kolom no. SJ belum diisi dengan benar. Silahkan diisi dengan benar atau batalkan input.','error');
		}
		@endcannot
	}

	function submit_updatep(){
		@cannot('pengiriman.update')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		tipe_edit       = true;
		var tglkembali  = $('#modalPInsert #tglkembali').val();
		var tglkirim    = $('#modalPInsert #tglkirim').val();
		var kmberangkat = $('#modalPInsert #kmberangkat').val();
		var kmkembali   = $('#modalPInsert #kmkembali').val();
		if(tglkembali<tglkirim){
			swal('Ups!', 'Tidak bisa simpan record. Tanggal kembali tidak boleh lebih kecil dari tanggal kirim. Hubungi manager Anda.','error');
		}else{
			if(kmkembali<kmberangkat){
				swal({
					title: "Ups!",
					text: "KM. Kirim lebih kecil dari KM berangkat. Apakah anda yakin?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Yes",
					cancelButtonText: "No",
					closeOnConfirm: false
				},
				function(isConfirm){
					if (isConfirm) {
						$.ajax({
							type     : 'post',
							url      : '{{ route("pengiriman.update") }}',
							data     : {
								id         : $('#modalPInsert #pengirimanId').val(),
								tglkembali : tglkembali,
								kmkembali  : kmkembali,
								catatan    : $('#modalPInsert #catatan').val(),
							},
							dataType : "json",
							success  : function(data){
								$('#modalPInsert').modal('hide');
								table.ajax.reload(null, true);
								tipe_edit = null;
								update_pengiriman = null;
								setTimeout(function(){
									table.row('#'+table_index).select();
								},1000);
							}
						});
					}
				});
			}else{
				$.ajax({
					type     : 'post',
					url      : '{{ route("pengiriman.update") }}',
					data     : {
						id         : $('#modalPInsert #pengirimanId').val(),
						tglkembali : tglkembali,
						kmkembali  : kmkembali,
						catatan    : $('#modalPInsert #catatan').val(),
					},
					dataType : "json",
					success  : function(data){
						$('#modalPInsert').modal('hide');
						table.ajax.reload(null, true);
						tipe_edit = null;
						update_pengiriman = null;
						setTimeout(function(){ 
							table.row('#'+table_index).select();
						},1000);
					}
				});
			}
		}
		@endcannot
	}

	function submit_kewenangan(){
    	$.ajax({
			type: 'POST',
			url: '{{route("pengiriman.kewenangan")}}',
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
					if(data.tipe == 'deletep'){
						table.ajax.reload(null, true);
					}else if(data.tipe == 'deletepd'){
						table.rows('#'+table_index).select();
					}
				}else{
					swal('Ups!', data.error,'error');
				}
			},
			error: function(data){
				console.log(data);
			}
		});
    }

    function cetak(e, data){
    	window.open('{{route("pengiriman.cetak")}}'+'?id='+data);
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

	// function search_staff(query, tipe){
	// 	if(tipe == 'sopir'){
	// 		url = '{{route("lookup.karyawansopir")}}';
	// 	}else{
	// 		url = '{{route("lookup.karyawankernet")}}';
	// 	}

	// 	$.ajaxSetup({
	//         headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
	//     });

	// 	$.ajax({
	// 		type: 'POST',
	// 		url: url,
	// 		data : {katakunci : query},
	// 		dataType : 'json',
	// 		success: function(data){
	// 			staff = data;
	// 			$('#tbodyStaff tr').remove();
	// 			var x = '';
	// 			if (staff.length > 0) {
	// 				for (var i = 0; i < staff.length; i++) {
	// 					x += '<tr tabindex=0>';
	// 					x += '<td>'+ staff[i].niksystemlama +'<input type="hidden" class="id_staff" value="'+ staff[i].id +'"></td>';
	// 					x += '<td>'+ staff[i].nikhrd +'</td>';
	// 					x += '<td>'+ staff[i].namakaryawan +'</td>';
	// 					x += '</tr>';
	// 				}
	// 			}else {
	// 				x += '<tr><td colspan="3" class="text-center">Tidak ada detail</td></tr>';
	// 			}
	// 			$('#tbodyStaff').append(x);
	// 		},
	// 		error: function(data){
	// 			console.log(data);
	// 		}
	// 	});
	// }

	// function search_armada(query){
	// 	$.ajaxSetup({
	//         headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
	//     });

	// 	$.ajax({
	// 		type: 'POST',
	// 		url: '{{route("lookup.armadakirim")}}',
	// 		data : {katakunci : query},
	// 		dataType : 'json',
	// 		success: function(data){
	// 			staff = data;
	// 			$('#tbodyArmada tr').remove();
	// 			var x = '';
	// 			if (staff.length > 0) {
	// 				for (var i = 0; i < staff.length; i++) {
	// 					x += '<tr tabindex=0>';
	// 					x += '<td>'+ staff[i].nomorpolisi +'<input type="hidden" class="id_staff" value="'+ staff[i].id +'"><input type="hidden" class="kmtempuh" value="'+ staff[i].kmtempuh +'"></td>';
	// 					x += '<td>'+ staff[i].namakendaraan +'</td>';
	// 					x += '</tr>';
	// 				}
	// 			}else {
	// 				x += '<tr><td colspan="3" class="text-center">Tidak ada detail</td></tr>';
	// 			}
	// 			$('#tbodyArmada').append(x);
	// 		},
	// 		error: function(data){
	// 			console.log(data);
	// 		}
	// 	});
	// }

	// function search_sj(query){
	// 	$.ajaxSetup({
	//         headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
	//     });

	// 	$.ajax({
	// 		type: 'POST',
	// 		url: '{{route("lookup.sj")}}',
	// 		data : {katakunci : query},
	// 		dataType : 'json',
	// 		success: function(data){
	// 			sj = data;
	// 			$('#tbodySJ tr').remove();
	// 			var x = '';
	// 			if (sj.length > 0) {
	// 				for (var i = 0; i < sj.length; i++) {
	// 					x += '<tr tabindex=0>';
	// 					x += '<td>'+ sj[i].nosj +'<input type="hidden" class="id_sj" value="'+ sj[i].id +'"></td>';
	// 					x += '<td>'+ sj[i].tglsj +'</td>';
	// 					x += '<td>'+ sj[i].namatoko +'</td>';
	// 					x += '<td>'+ sj[i].alamat +'</td>';
	// 					x += '</tr>';
	// 				}
	// 			}else {
	// 				x += '<tr><td colspan="3" class="text-center">Tidak ada detail</td></tr>';
	// 			}
	// 			$('#tbodySJ').append(x);
	// 		},
	// 		error: function(data){
	// 			console.log(data);
	// 		}
	// 	});
	// }

	// function pilih_staff(tipe){
	// 	if ($('#tbodyStaff').find('tr.selected td').eq(1).text() == '') {
	// 		swal("Ups!", "Staff belum dipilih.", "error");
	// 		return false;
	// 	}else {
	// 		if(tipe == 'sopir'){
	// 			$('#sopir').val($('#tbodyStaff').find('tr.selected td').eq(2).text());
	// 			$('#sopirid').val($('#tbodyStaff').find('tr.selected td .id_staff').val());
	// 			$('#modalStaff').modal('hide');
	// 			$("#sopir").focus();
	// 		}else if(tipe == 'kernet'){
	// 			$('#kernet').val($('#tbodyStaff').find('tr.selected td').eq(2).text());
	// 			$('#kernetid').val($('#tbodyStaff').find('tr.selected td .id_staff').val());
	// 			$('#modalStaff').modal('hide');
	// 			$("#kernet").focus();
	// 		}
	// 	}
	// 	tipe_search = null;
	// }

	// function pilih_armada(){
	// 	if ($('#tbodyArmada').find('tr.selected td').eq(1).text() == '') {
	// 		swal("Ups!", "Armada belum dipilih.", "error");
	// 		return false;
	// 	}else {
	// 		$('#armadakirim').val($('#tbodyArmada').find('tr.selected td').eq(1).text());
	// 		$('#armadakirimId').val($('#tbodyArmada').find('tr.selected td .id_staff').val());
	// 		$('#kmberangkat').val($('#tbodyArmada').find('tr.selected td .kmtempuh').val());
	// 		$('#modalArmadaKirim').modal('hide');
	// 		$("#armadakirim").focus();
	// 	}
	// }

	// function pilih_sj(){
	// 	if ($('#tbodySJ').find('tr.selected td').eq(0).text() == '') {
	// 		swal("Ups!", "Surat Jalan belum dipilih.", "error");
	// 		return false;
	// 	}else {
	// 		console.log($('#tbodySJ').find('tr.selected td'));
	// 		$('#nosj').val($('#tbodySJ').find('tr.selected td').eq(0).text());
	// 		$('#sjId').val($('#tbodySJ').find('tr.selected td .id_sj').val());
	// 		$.ajax({
	// 			type: 'GET',
	// 			url: '{{route("suratjalan.view")}}',
	// 			data : {id : $('#tbodySJ').find('tr.selected td .id_sj').val()},
	// 			dataType : 'json',
	// 			success: function(data){
	// 				$('#modalSJInsert #tglsj').val(data.tglsj);
	// 				$('#modalSJInsert #toko').val(data.toko);
	// 				$('#modalSJInsert #alamat').val(data.alamat);
	// 				$('#modalSJInsert #kota').val(data.kota);
	// 				$('#modalSJInsert #daerah').val(data.daerah);
	// 				$('#modalSJInsert #wilid').val(data.wilid);
	// 				$('#modalSJInsert #nit').val(data.nit);
	// 				$('#modalSJInsert #keterangankoli').val(data.titipanketerangan);
	// 				$('#modalSJInsert #dari').val(data.titipandari);
	// 				$('#modalSJInsert #untuk').val(data.titipanuntuk);
	// 				$('#modalSJInsert #alamattitipan').val(data.titipanalamat);
	// 				$('#modalSJInsert #notelp').val(data.titipannotelepon);
	// 				$('#modalSJInsert #totalkoli').val(data.totalkoli);
	// 				$('#modalSJ').modal('hide');
	// 				$("#modalSJInsert #nosj").focus();
	// 			},
	// 			error: function(data){
	// 				console.log(data);
	// 			}
	// 		});
	// 	}
	// }
</script>
@endpush