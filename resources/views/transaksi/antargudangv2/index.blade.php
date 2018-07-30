@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
  	<li class="breadcrumb-item"><a href="{{ route('antargudangv2.index') }}">Antar Gudang V2</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>Antar Gudang V2</h2>
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
			                	<label style="margin-right: 10px;">Tgl. Rq. AG</label>
								<input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
								<label>-</label>
								<input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
			                </div>
		                </form>
					</div>
					<div class="col-md-6 text-right">
						@can('antargudangv2.tambah')
						<a href="{{route('antargudangv2.tambah')}}" class="btn btn-success"><i class="fa fa-plus"></i> Tambah AG</a>
						@endcan
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th width="15%">Action</th>
					<th width="9%">Tgl. Rq. AG</th>
					<th width="12%">Tgl. Nota AG</th>
					<th width="12%">Tgl. Kirim</th>
					<th width="10%">Tgl. Terima</th>
					<th width="12%">No. Rq. AG</th>
					<th width="12%">Dari</th>
					<th width="10%">Ke</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
		<div style="cursor: pointer; margin-top: 10px;" class="hidden">
			<p>
				<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
				<a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Tgl. Rq. AG</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Tgl. Nota AG</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Tgl. Kirim</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Tgl. Terima</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> No. Rq. AG</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis" data-column="6"><i id="eye6" class="fa fa-eye"></i> Dari</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis" data-column="7"><i id="eye7" class="fa fa-eye"></i> Ke</a>
			</p>
		</div>
	</div>

	<div class="row-fluid">
		<table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th width="1%">A</th>
					<th width="60%">Nama Barang</th>
					<th width="15%">Satuan</th>
					<th width="10%">Qty. Rq AG</th>
					<th width="10%">Qty. Nota</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
		<div style="cursor: pointer; margin-top: 10px;" class="hidden">
			<p>
				<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
				<a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Nama Barang</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Satuan</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Qty. Rq AG</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis-2" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> Qty. Nota</a>
			</p>
		</div>
	</div>

</div>

<!-- Data double click -->
<div class="modal fade" id="modalDoubleClick" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
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

<!-- Form Kewenangan -->
<div class="modal fade" id="modalKewenangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
	<div class="modal-dialog modal-xs" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Form Kewenangan</h4>
		</div>
		<form id="formKewenangan" action="{{route("antargudangv2.kewenangan")}}" class="form-horizontal" method="post">
			{!! csrf_field() !!}
			<input type="hidden" id="aghapusid" name="aghapusid" value="">
			<input type="hidden" id="tipe" name="tipe" value="">
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

<!-- Form tambah detail -->
@can('antargudangv2.detail.tambah')
    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Tambah Detail Antar Gudang V2</h4>
                </div>
                <form id="formDetail" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    <div class="modal-body">
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barang">Barang <span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="barang" name="barang" class="form-control" placeholder="Barang" autocomplete="off" required="required">
                                <input type="hidden" id="barangid" name="barangid">
                                <input type="hidden" id="qtystockgudang">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Kode Barang / Satuan</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
								<input type="text" id="kodebarang" name="kodebarang" class="form-control" readonly tabindex="-1">
                            </div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<input type="text" id="satuan" name="satuan" class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtyrqag">Qty Rq. AG</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="number" id="qtyrqag" name="qtyrqag" class="form-control text-right" required="required">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="catatandetail">Catatan</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="catatandetail" name="catatandetail" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="antargudangid" name="antargudangid" value="" readonly tabindex="-1">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
<!-- End tambah detail -->

<!-- Form update nota -->
<div class="modal fade" id="modalNota" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Update Nota</h4>
			</div>
			<form id="formNota" class="form-horizontal" method="post">
				{!! csrf_field() !!}
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Tgl. Rq. AG</label>
								<input type="text" id="tglrqag" name="tglrqag" class="form-control form-clear" placeholder="Tgl. Rq. AG" readonly tabindex="-1">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>No. Rq. AG</label>
								<input type="text" id="norqag" name="norqag" class="form-control form-clear" placeholder="No. Rq. AG" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Subcabang Pengirim</label>
								<input type="text" id="subcabangnamapengirim" name="subcabangnamapengirim" class="form-control form-clear" placeholder="Subcabang Pengirim" readonly tabindex="-1">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Subcabang Penerima</label>
								<input type="text" id="subcabangnamapenerima" name="subcabangnamapenerima" class="form-control form-clear" placeholder="Subcabang Penerima" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Tgl. Terima</label>
								<input type="text" id="tglterima" name="tglterima" class="form-control form-clear" placeholder="Tgl. Terima" readonly tabindex="-1">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Tgl. Nota AG</label>
								<input type="text" id="tglnotaag" name="tglnotaag" class="form-control form-clear" placeholder="Tgl. Nota AG" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Stock Pengirim</label>
								<input type="text" id="pengirim" name="pengirim" class="form-control form-clear" placeholder="Stock Pengirim" tabindex="-1">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Stock Penerima</label>
								<input type="text" id="penerima" name="penerima" class="form-control form-clear" placeholder="Stock Penerima" tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Checker 1 Pengiriman</label>
								<input type="text" id="checker1" name="checker1" class="form-control form-clear" placeholder="Checker 1 Pengiriman" tabindex="-1">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Checker 1 Penerima</label>
								<input type="text" id="pemeriksa" name="pemeriksa" class="form-control form-clear" placeholder="Checker 1 Penerimaan" tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Checker 2 Pengiriman</label>
								<input type="text" id="checker2" name="checker2" class="form-control form-clear" placeholder="Checker 2 Pengiriman" tabindex="-1">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Checker 2 Penerima</label>
								<input type="text" id="pemeriksa00" name="pemeriksa00" class="form-control form-clear" placeholder="Checker 2 Penerimaan" tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Catatan</label>
								<input type="text" id="catatan" name="catatan" class="form-control form-clear" placeholder="Catatan" tabindex="-1" readonly="">
							</div>
						</div>
					</div>

					<div class="row-fluid">
						<table id="tableUpdateNota" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th width="60%">Nama Barang</th>
									<th width="15%">Satuan</th>
									<th width="10%">Qty. Rq AG</th>
									<th width="10%">Qty. Nota</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>

				</div>
				<div class="modal-footer">
					<input type="hidden" id="subcabangidpengirim" name="subcabangidpengirim">
					<input type="hidden" id="subcabangidpenerima" name="subcabangidpenerima">
					<input type="hidden" id="pengirimid" name="pengirimid">
					<input type="hidden" id="penerimaid" name="penerimaid">
					<input type="hidden" id="checker1id" name="checker1id">
					<input type="hidden" id="checker2id" name="checker2id">
					<input type="hidden" id="pemeriksaid" name="pemeriksaid">
					<input type="hidden" id="pemeriksa00id" name="pemeriksa00id">
					<input type="hidden" id="notaupdateid" name="notaupdateid">
					<input type="hidden" id="notaupdatetipe" name="notaupdatetipe">
					<button type="submit" class="btn btn-primary">Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Form update nota -->

<!-- Form pengiriman -->
<div class="modal fade" id="modalPengiriman" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Pengiriman</h4>
			</div>
			<form id="formPengiriman" class="form-horizontal" method="post">
				{!! csrf_field() !!}
				<div class="modal-body">
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sopir">Sopir</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<input type="text" id="sopir" name="sopir" class="form-control">
						</div>
					</div>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="kernet">Helper</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<input type="text" id="kernet" name="kernet" class="form-control">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" id="sopirid" name="sopirid" value="">
					<input type="hidden" id="kernetid" name="kernetid" value="">
					<input type="hidden" id="pengirimanid" name="pengirimanid" value="">
					<button type="submit" class="btn btn-primary">Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Form pengiriman -->

@endsection

@push('scripts')
<script type="text/javascript">

// =============================================
// Author		: M Nur Halim
// Create date	: 22 Jan 2018
// Description	: Fitur Antar Gudang Versi 2 / Pengiriman
// =============================================

// =============================================
// Modifier 1	: 
// date			: 
// Description	: 
// =============================================

lookupbarang();
lookupsubcabang();
lookupstaff();
var custom_search = [];
for (var i = 0; i < 7; i++) {
	custom_search[i] = {
		text   : '',
		filter : '='
	};
}
var filter_number = ['<=','<','=','>','>='];
var filter_text = ['=','!='];
var tipe = ['Find','Filter'];
var column_index = 0;
var last_index = '';
var context_menu_number_state = 'hide';
var context_menu_text_state = 'hide';
var tipe_edit = null;
var tipe_search = null;
var table,
	table2,
	tabledoubleclick, tableUpdateNota,
	table_index,table2_index,fokus;

$(document).ready(function(){
	$(".tgl").inputmask();
	table = $('#table1').DataTable({
		dom 		: 'lrtp',//lrtip -> lrtp
		serverSide	: true,
		stateSave	: true,
		deferRender : true,
		select: {style:'single'},
		ajax 		: {
			url			: '{{ route("antargudangv2.data") }}',
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
					//console.log(row);
					return "<div class='btn btn-success btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Tambah Detail' onclick='Kewenangan(this,"+JSON.stringify(row)+")' data-message='"+row.insert+"' data-tipe='tambah'><i class='fa fa-plus'></i></div>"+
						"<div class='btn btn-warning btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Update AG' onclick='updateNota(this,"+JSON.stringify(row)+")' data-message='"+row.update+"' data-tipe='header'><i class='fa fa-pencil'></i></div>"+
						"<div class='btn btn-danger btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Hapus AG' onclick='Kewenangan(this,"+JSON.stringify(row)+")' data-message='"+row.delete+"' data-tipe='header'><i class='fa fa-trash'></i></div>"+
						"<div class='btn btn-warning btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Update Pengiriman' onclick='UpdatePengiriman(this,"+JSON.stringify(row)+")' data-message='"+row.pengiriman+"' data-tipe='header'><i class='fa fa-truck'></i></div>"+
						"<div class='btn-group no-margin-action'><button type='button' class='btn btn-primary dropdown-toggle btn-xs no-margin-action' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fa fa-print'></i> <span class='caret'></span></button>"+
						"<ul class='dropdown-menu'>"+
						"<li><a href='#' onclick='Kewenangan(this,"+JSON.stringify(row)+")' data-tipe='cetaknota'>Print Nota</a></li>"+
						"<li><a href='#' onclick='Kewenangan(this,"+JSON.stringify(row)+")' data-tipe='cetakpil'>Print PIL</a></li>"+
						"</ul></div>";
				}
			},
			{
				"data" : "tglrqag",
				"className" : "menufilter numberfilter text-right"
			},
			{
				"data" : "tglnotaag",
				"className" : "menufilter textfilter"
			},
			{
				"data" : "tglkirim",
				"className" : "menufilter textfilter"
			},
			{
				"data" : "tglterima",
				"className" : "menufilter textfilter"
			},
			{
				"data" : "norqag",
				"className" : "menufilter textfilter"
			},
			{
				"data" : "darisubcabangnama",
				"className" : "menufilter textfilter"
			},
			{
				"data" : "kesubcabangnama",
				"className" : "menufilter textfilter"
			},
		]
	});

	table2 = $('#table2').DataTable({
		dom     : 'lrtp',
		select: {style:'single'},
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
				"data" : "namabarang",
			},
			{
				"data" : "satuan",
			},
			{
				"data" : "qtyrqag",
				"className" : "text-right"
			},
			{
				"data" : "qtynotaag",
				"className" : "text-right"
			},
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

	tableUpdateNota = $("#tableUpdateNota").DataTable({
		dom     : 'lrtp',
		select: {style:'single'},
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
				"data" : "namabarang",
			},
			{
				"data" : "satuan",
			},
			{
				"data" : "qtyrqag",
				"className" : "text-right"
			},
			{
				"data" : "qtynotaag",
				"className" : "text-right"
			},
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
	});
	
	$('a.toggle-vis-2').on( 'click', function (e) {
		e.preventDefault();

		// Get the column API object
		var column = table2.column( $(this).attr('data-column') );

		// Toggle the visibility
		column.visible( ! column.visible() );
		$('#eye-detail'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
	});

	$(document.body).on("keydown", function(e){
		ele = document.activeElement;
		//console.log(ele);
		if(e.keyCode == 13){
			if(context_menu_number_state == 'show'){
				$(".context-menu-list.numberfilter").contextMenu("hide");
				table.ajax.reload(null, true);
			}else if(context_menu_text_state == 'show'){
				$(".context-menu-list.textfilter").contextMenu("hide");
				table.ajax.reload(null, true);
			}
		}
	});

	table.on('select', function ( e, dt, type, indexes ){
		table_index = indexes;
		fokus       = 'header';
		var rowData = table.rows( indexes ).data().toArray();
		$.ajaxSetup({
			headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
		});

		$.ajax({
			type: 'GET',
			data: {id: rowData[0].id},
			dataType: "json",
			url: '{{route("antargudangv2.detail")}}',
			success: function(data){
				table2.clear();
				for (var i = 0; i < data.data.length; i++) {
					var html_buttonaction  = "<div class='btn btn-danger btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Hapus AG' onclick='Kewenangan(this,"+data.data[i].id+")' data-message='"+data.data[i].delete+"' data-tipe='detail'><i class='fa fa-trash'></i></div>";
					table2.row.add({
						action    : html_buttonaction,
						namabarang: data.data[i].namabarang,
						satuan	  : data.data[i].satuan,
						qtyrqag   : data.data[i].qtyrqag,
						qtynotaag : data.data[i].qtynotaag,
						id        : data.data[i].id
					});
				}
				table2.draw();
			},
			error: function(data){
				console.log(data);
			}
		});
	});

	$('.tgl').change(function(){
		table.draw();
		table2.draw();
	});

	$('#table1 tbody').on('dblclick', 'tr', function(){
		var data = table.row(this).data();
		$.ajax({
			type: 'GET',
			url: '{{route("antargudangv2.view")}}',
			data: {id: data.id},
			dataType: "json",
			success: function(data){
				tabledoubleclick.clear();
				tabledoubleclick.rows.add([
					{'0':'1.', '1':'Tanggal Rq. AG', '2':data.tglrqag},
					{'0':'2.', '1':'Tanggal Nota AG', '2':data.tglnotaag},
					{'0':'3.', '1':'No. Nota AG', '2':data.norqag},
					{'0':'4.', '1':'Tanggal Terima', '2':data.tglterima},
					{'0':'5.', '1':'Dari Sub Cabang', '2':data.darisubcabangnama},
					{'0':'6.', '1':'Ke Sub Cabang', '2':data.kesubcabangnama},
					{'0':'7.', '1':'Stock Pengirim', '2':data.stockpengirim},
					{'0':'8.', '1':'Checker 1 Pengirim', '2':data.checkerpengirim1},
					{'0':'9.', '1':'Checker 2 Pengirim', '2':data.checkerpengirim2},
					{'0':'10.', '1':'Stock Penerima', '2':data.stockpenerima},
					{'0':'11.', '1':'Checker 1 Penerima', '2':data.checkerpenerima1},
					{'0':'12.', '1':'Checker 2 Penerima', '2':data.checkerpenerima2},
					{'0':'13.', '1':'Catatan', '2':data.catatan},
					{'0':'14.', '1':'Tanggal Print Nota AG', '2':data.tglprintnotaag},
					{'0':'15.', '1':'Tanggal Print Rq. AG', '2':data.tglprintpilag},
					{'0':'16.', '1':'Print Nota AG', '2':data.printnotaag},
					{'0':'17.', '1':'Print Rq. AG', '2':data.printpilag},
					{'0':'18.', '1':'Terakhir diupdate tanggal', '2':data.lastupdatedon},
					{'0':'19.', '1':'Terakhir diupdate oleh', '2':data.lastupdatedby},
				]);
				tabledoubleclick.draw();
				$('#modalDoubleClick #myModalLabel').html('Data AG');
				$('#modalDoubleClick').modal('show');
			}
		});
	});

	$('#table2 tbody').on('dblclick', 'tr', function(){
		var data = table2.row(this).data();
		$.ajax({
			type: 'GET',
			url: '{{route("antargudangv2.detail.view")}}',
			data: {id: data.id},
			dataType: "json",
			success: function(data){
				tabledoubleclick.clear();
				tabledoubleclick.rows.add([
					{'0':'1.', '1':'Nama Barang', '2':data.namabarang},
					{'0':'2.', '1':'Qty Rq. AG', '2':data.qtyrqag},
					{'0':'3.', '1':'No. Nota AG', '2':data.qtynotaag},
					{'0':'4.', '1':'Catatan', '2':data.catatan},
					{'0':'5.', '1':'Terakhir Diupdate Tanggal', '2':data.lastupdatedon},
					{'0':'6.', '1':'Terakhir Diupdate Oleh', '2':data.lastupdatedby},
					{'0':'7.', '1':'No. Koli', '2':data.nokoli},
				]);
				tabledoubleclick.draw();
				$('#modalDoubleClick #myModalLabel').html('Data AG Detail');
				$('#modalDoubleClick').modal('show');
			}
		});
	});

	$('#formKewenangan').submit(function(e){
      e.preventDefault();
	  var permisi;
	  if ($("#tipe").val() == "header"){
		permisi = 'antargudangv2.hapus';
	  }else if ($("#tipe").val() == "detail"){
		permisi = 'antargudangv2.detail.hapus';
	  }else if ($("#tipe").val() == "tambah"){
		permisi = 'antargudangv2.detail.tambah';
	  }else if ($("#tipe").val() == "cetakpil"){
		permisi = 'antargudangv2.cetakpil';
	  }else if ($("#tipe").val() == "cetaknota"){
		permisi = 'antargudangv2.cetaknota';
	  }
      $.ajax({
        type: 'POST',
        url: '{{route("antargudangv2.kewenangan")}}',
        data: {
          username    : $('#modalKewenangan #uxserKewenangan').val().trim().toUpperCase(),
          password    : $('#modalKewenangan #pxassKewenangan').val(),
          aghapusid   : $('#modalKewenangan #aghapusid').val(),
          tipe        : $('#modalKewenangan #tipe').val(),
          permission  : permisi,
        },
        dataType: "json",
        success: function(data){
			var tipeact = $('#modalKewenangan #tipe').val();
			var idact = $("#modalKewenangan #aghapusid").val();

			$('#modalKewenangan #uxserKewenangan').val('').change();
			$('#modalKewenangan #pxassKewenangan').val('').change();

			if (data.success == true){
				$('#modalKewenangan').modal('hide');
				if (tipeact == 'tambah')
				{
					resetDetail(idact);
				}else if (tipeact == 'header' || tipeact == 'detail')
				{
					swal('Sukses!', data.message,'success');
					table.ajax.reload(null, true);
					table2.clear().draw();
					setTimeout(function(){
					table.row('#'+table_index).select();
					},1000);
				}else if (tipeact == "cetakpil"){
					cetakPiL(idact);
				}else if (tipeact == "cetaknota"){
					cetakNota(idact);
				}
			}else{
				swal('Ups!', 'Terdapat kesalahan pada sistem.','error');
			}
        },
        error: function(data){
          console.log(data);
        }
      });
    });
	
	$("#qtyrqag").keyup(function(){
		var stok = $("#qtystockgudang").val();
		if (parseFloat($("#qtyrqag").val()) > parseFloat(stok)){
			$('#qtyrqag').val(0);
			swal("Ups!","Qty melebihi stock gudang.","warning");
		}else{
			if (stok == ''){
				$('#qtyrqag').val('');
				swal("Ups!", "Stock gudang kosong", "error");
			}
		}
	});

	$("#formDetail").on("submit", function (e){
		e.preventDefault();
		@cannot("antargudangv2.detail.tambah")
    		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
    	@else

			if ($("#antargudangid").val() == '' || $("#antargudangid").val() == null){
				swal("Ups!","Tidak bisa simpan record detail. Data antar gudang belum disimpan.","error"); $("#barang").focus(); return false;
			}

			if ($("#barangid").val() == '' || $("#barangid").val() == null){
				swal("Ups!","Barang belum dipilih.","error"); $("#barang").focus(); return false;
			}

			if (parseInt($("#qtyrqag").val()) < 1 || $("#qtyrqag").val() == null){
				swal("Ups!","Qty Rq. AG belum diisi","error"); $("#qtyrqag").focus(); return false;
			}

			var stok = $("#qtystockgudang").val();
			if (parseFloat($("#qtyrqag").val()) > parseFloat(stok)){
				$('#qtyrqag').val(0);
				swal("Ups!","Qty melebihi stock gudang.","warning"); return false;
			}else{
				if (stok == ''){
				$('#qtyrqag').val('');
				swal("Ups!", "Stock gudang kosong", "error"); return false;
				}
			}
			
			$.ajax({
				type      : 'POST',
				url       :'{{route("antargudangv2.detail.tambah")}}',
				data      : $(this).serialize(),
				dataType  : "json",
				success   : function(data){
					if (data.success){
						$('#modalDetail').modal('hide');
						resetPengiriman();
						table.ajax.reload(null, true);
						setTimeout(function(){
							table.row(0).select();
						},1000);
					}
				},
				error: function(data){
					console.log(data);
				}
			});
		@endcannot
	});

	$("#formNota").on("submit", function (e){
		e.preventDefault();
		@cannot('antargudangv2.updatenota')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
			var notaupdatetipe = $("#notaupdatetipe").val();
			var notaupdateid = $("#notaupdateid").val();
			var checker1pengirim = $("#checker1id").val();
			var checker2pengirim = $("#checker2id").val();
			var checker1penerima = $("#pemeriksaid").val();
			var checker2penerima = $("#pemeriksa00id").val();
			var stockpengirim = $("#pengirimid").val();
			var stockpenerima = $("#penerimaid").val();

			if (notaupdatetipe == "pengirim"){
				if (stockpengirim == null || stockpengirim == ""){
					swal("Ups!", "Stock pengirim belum dipilih","error"); $("#pengirim").focus(); return false;
				}
				if (checker1pengirim == null || checker1pengirim == ""){
					swal("Ups!", "Checker 1 pengirim belum dipilih","error"); $("#checker1").focus(); return false;
				}
				if (checker2pengirim == null || checker2pengirim == ""){
					swal("Ups!", "Checker 2 pengirim belum dipilih","error"); $("#checker2").focus(); return false;
				}
			}else if (notaupdatetipe == "penerima"){
				if (stockpenerima == null || stockpenerima == ""){
					swal("Ups!", "Stock penerima belum dipilih","error"); $("#penerima").focus(); return false;
				}
				if (checker1penerima == null || checker1penerima == ""){
					swal("Ups!", "Checker 1 penerima belum dipilih","error"); $("#pemeriksa").focus(); return false;
				}
				if (checker2penerima == null || checker2penerima == ""){
					swal("Ups!", "Checker 2 penerima belum dipilih","error"); $("#pemeriksa00").focus(); return false;
				}
			}
		
			$.ajax({
				type      : 'POST',
				url       :'{{route("antargudangv2.updatenota")}}',
				data      : $(this).serialize(),
				dataType  : "json",
				success   : function(data){
					if (data.success){
						$('#modalNota').modal('hide');
						resetUpdateNota();
						swal("Berhasil", "Update nota berhasil","success");
						table.ajax.reload(null, true);
						setTimeout(function(){
							table.row(0).select();
						},1000);
					}
				},
				error: function(data){
					console.log(data);
				}
			});

		@endcannot
	});

	$("#formPengiriman").on("submit", function(e){
		e.preventDefault();
		@cannot('antargudangv2.pengiriman')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
			var pengirimanid = $("#pengirimanid").val();
			var sopirid = $("#sopirid").val();
			var kernetid = $("#kernetid").val();

			if (pengirimanid == null || pengirimanid == ''){
				swal("Ups!","Tidak bisa simpan record detail. Data pengiriman belum disimpan.","error"); $("#sopir").focus(); return false;
			}

			if (sopirid == null || sopirid == ''){
				swal("Ups!", "Sopir belum dipilih.","error"); $("#sopir").focus(); return false;
			}

			if (kernetid == null || kernetid == ''){
				swal("Ups!", "Helper belum dipilih.","error"); $("#modalPengiriman #kernet").focus(); return false;
			}

			$.ajax({
				type      : 'POST',
				url       :'{{route("antargudangv2.pengiriman")}}',
				data      : $(this).serialize(),
				dataType  : "json",
				success   : function(data){
					if (data.success){
						$('#modalPengiriman').modal('hide');
						resetPengiriman();
						swal("Berhasil", "Update pengiriman berhasil","success");
						table.ajax.reload(null, true);
						setTimeout(function(){
							table.row(0).select();
						},1000);
					}
				},
				error: function(data){
					console.log(data);
				}
			});
		@endcannot
	});

});
//end document ready

function resetDetail(id){
	$("#modalDetail antargudangid").val('');
	$("#modalDetail #barang").val('');
	$("#modalDetail #barangid").val('');
	$("#modalDetail #satuan").val('');
	$("#modalDetail #qtyrqag").val(0);
	$("#modalDetail #catatandetail").val('');
	$("#modalDetail #qtystockgudang").val('');
	$("#modalDetail #kodebarang").val('');
	
	$("#modalDetail #antargudangid").val(id);
	$("#modalDetail").modal('show');
	$("#modalDetail #barang").focus();
}

function resetPengiriman(){
	$("#modalPengiriman #sopir").val('');
	$("#modalPengiriman #kernet").val('');
	$("#modalPengiriman #sopirid").val('');
	$("#modalPengiriman #kernetid").val('');
	$("#modalPengiriman #pengirimanid").val('');
}

function resetUpdateNota(){
	$("#modalNota #subcabangidpengirim").val('');
	$("#modalNota #subcabangidpenerima").val('');
	$("#modalNota #pengirimid").val('');
	$("#modalNota #penerimaid").val('');
	$("#modalNota #checker1id").val('');
	$("#modalNota #checker2id").val('');
	$("#modalNota #pemeriksaid").val('');
	$("#modalNota #pemeriksa00id").val('');
	$("#modalNota #notaupdateid").val('');
	$("#modalNota #notaupdatetipe").val('');

	$("#modalNota #norqag").val('');
	$("#modalNota #tglrqag").val('');
	$("#modalNota #subcabangnamapenerima").val('');
	$("#modalNota #subcabangnamapengirim").val('');
	$("#modalNota #tglterima").val('');
	$("#modalNota #tglnotaag").val('');
	$("#modalNota #pengirim").val('');
	$("#modalNota #penerima").val('');
	$("#modalNota #checker1").val('');
	$("#modalNota #pemeriksa").val('');
	$("#modalNota #checker2").val('');
	$("#modalNota #pemeriksa00").val('');
	$("#modalNota #catatan").val('');
}

function Kewenangan(e, data){
	var tipe = $(e).data('tipe');
	var message = $(e).data('message');
	
	var id;
	var blncontinue = false;

	
	if (tipe == 'detail'){
		@cannot('antargudangv2.detail.hapus')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
			if (message == 'auth'){
				id = parseInt(data);
				blncontinue = true;
			}
		@endcannot

	}else if (tipe == 'header'){
		@cannot('antargudangv2.hapus')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
			if (message == 'auth'){
				id = parseInt(data.id);
				blncontinue = true;
			}
		@endcannot
	}else if (tipe == 'tambah'){
		@cannot('antargudangv2.detail.tambah')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
			if (message == 'auth'){
				id = parseInt(data.id);
				blncontinue = true;
			}
		@endcannot
	}else if (tipe == "cetakpil"){
		@cannot('antargudangv2.cetakpil')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
			if (data.printpilag > 0){
				id = parseInt(data.id);
				blncontinue = true;
			}
		@endcannot
	}else if (tipe == "cetaknota"){
		@cannot('antargudangv2.cetaknota')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
			if (data.printnotaag > 0){
				id = parseInt(data.id);
				blncontinue = true;
			}
		@endcannot
	}
	
	$('#modalKewenangan #aghapusid').val('');
	$('#modalKewenangan #tipe').val('');
	$('#modalKewenangan #uxserKewenangan').val('');
	$('#modalKewenangan #pxassKewenangan').val('');

	if (blncontinue == true){
		if (tipe == 'tambah'){
			if (parseInt(data.printpilag) > 0){
				$("#modalKewenangan #aghapusid").val(id);
				$("#modalKewenangan #tipe").val(tipe);
				$('#modalKewenangan').modal('show');
			}else{
				resetDetail(id);
			}
		}else{
			$("#modalKewenangan #aghapusid").val(id);
			$("#modalKewenangan #tipe").val(tipe);
			$('#modalKewenangan').modal('show');
		}
		
	}else{
		if (tipe == "cetakpil"){
			cetakPiL(data.id);
		}else if (tipe == "cetaknota"){
			cetakNota(data.id);
		}else{
			swal("Ups!",message, "error");
		}
	}

}

function updateNota(e, data){
	var tipe = $(e).data('tipe');
	var message = $(e).data('message');

	@cannot('antargudangv2.updatenota')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
	@else
		if (message == 'auth'){
			$.ajax({
				type: 'GET',
				url: '{{route("antargudangv2.view")}}',
				data: {id: data.id},
				dataType: "json",
				success: function(data){

					resetUpdateNota();

					$("#norqag").val(data.norqag);
					$("#tglrqag").val(data.tglrqag);
					$("#subcabangnamapengirim").val(data.darisubcabangnama);
					$("#subcabangnamapenerima").val(data.kesubcabangnama);
					$("#pengirim").val(data.stockpengirim);
					$("#penerima").val(data.stockpenerima);
					$("#checker1").val(data.checkerpengirim1);
					$("#checker2").val(data.checkerpengirim2);
					$("#pemeriksa").val(data.checkerpenerima1);
					$("#pemeriksa00").val(data.checkerpenerima2);
					$("#catatan").val(data.catatan);
					
					$("#tglterima").val(data.notaupdatetglterima);
					$("#tglnotaag").val(data.notaupdatetglnotaag);
					
					$("#notaupdatetipe").val(data.notaupdatetipe);
					$("#modalNota #notaupdateid").val(data.id);

					$("#modalNota #subcabangidpengirim").val(data.subcabangidpengirim);
					$("#modalNota #subcabangidpenerima").val(data.subcabangidpenerima);
					$("#modalNota #pengirimid").val(data.karyawanidpengirim);
					$("#modalNota #penerimaid").val(data.karyawanidpenerima);
					$("#modalNota #checker1id").val(data.karyawanidchecker1pengirim);
					$("#modalNota #checker2id").val(data.karyawanidchecker2pengirim);
					$("#modalNota #pemeriksaid").val(data.karyawanidchecker1penerima);
					$("#modalNota #pemeriksa00id").val(data.karyawanidchecker2penerima);
					
					if (data.notaupdatetipe == "pengirim"){
						$('#pemeriksa').attr('disabled',true);
						$('#pemeriksa00').attr('disabled',true);
						$('#penerima').attr('disabled',true);
						$("#modalNota").modal("show");
						$("#pengirim").focus();
					}else{
						$('#checker1').attr('disabled',true);
						$('#checker2').attr('disabled',true);
						$('#pengirim').attr('disabled',true);
						$("#modalNota").modal("show");
						$("#penerima").focus();
					}
				}
			});

			$.ajaxSetup({
			headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
		});

		$.ajax({
			type: 'GET',
			data: {id: data.id},
			dataType: "json",
			url: '{{route("antargudangv2.detail")}}',
			success: function(data){
				tableUpdateNota.clear();
				for (var i = 0; i < data.data.length; i++) {
					tableUpdateNota.row.add({
						namabarang: data.data[i].namabarang,
						satuan	  : data.data[i].satuan,
						qtyrqag   : data.data[i].qtyrqag,
						qtynotaag : data.data[i].qtynotaag,
						id        : data.data[i].id
					});
				}
				tableUpdateNota.draw();
			},
			error: function(data){
				console.log(data);
			}
		});
			
		}else{
			swal('Ups!', message,'error'); return false;
		}
	@endcannot
}

function UpdatePengiriman(e, data){
	var tipe = $(e).data('tipe');
	var message = $(e).data('message');
	@cannot('antargudangv2.pengiriman')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
	@else
		resetPengiriman();
		if (message == 'auth'){
			resetPengiriman();
			$("#modalPengiriman #pengirimanid").val(data.id);
			$("#modalPengiriman").modal("show");
			$("#modalPengiriman #sopir").focus();
		}else{
			swal('Ups!', message,'error'); return false;
		}
	@endcannot	
}

function cetakPiL(id){
	window.open('{{route("antargudangv2.cetakpil")}}'+'?id='+id);
}

function cetakNota(id){
	window.open('{{route("antargudangv2.cetaknota")}}'+'?id='+id);
}

</script>
@endpush