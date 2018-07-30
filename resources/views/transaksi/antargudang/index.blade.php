@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
  	<li class="breadcrumb-item"><a href="{{ route('antargudang.index') }}">Antar Gudang</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>Antar Gudang</h2>
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
			                	<label style="margin-right: 10px;">Tgl. Nota AG</label>
								<input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
								<label>-</label>
								<input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
			                </div>
		                </form>
					</div>
					<div class="col-md-6 text-right">
						@can('antargudang.create')
						<a onclick="tambah_ag()" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Antar Gudang</a>
						@endcan
					</div>
				</div>
				<div class="row-fluid">
					<table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
						<thead>
							<tr>
							    <th width="15%">Action</th>
							    <th width="9%">No. AG</th>
							    <th width="12%">Subcabang Pengirim</th>
							    <th width="12%">Tgl. Rq</th>
							    <th width="10%">Tgl. Nota AG</th>
							    <th width="12%">Pengirim</th>
							    <th width="12%">Subcabang Penerima</th>
							    <th width="10%">Tgl. Terima</th>
							    <th width="12%">Penerima</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
						<p>
							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
							<a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> No. AG</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Subcabang Pengirim</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Tgl. Rq</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Tgl. Nota AG</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> Pengirim</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="6"><i id="eye6" class="fa fa-eye"></i> Subcabang Penerima</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="7"><i id="eye7" class="fa fa-eye"></i> Tgl. Terima</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="8"><i id="eye8" class="fa fa-eye"></i> Penerima</a>
						</p>
					</div>
				</div>
				{{-- <hr> --}}
				<div class="row-fluid">
					<table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="1%">A</th>
							    <th width="60%">Nama Barang</th>
							    <th width="15%">Kode Barang</th>
							    <th width="10%">Qty. Nota AG</th>
							    <th width="10%">Qty. Terima</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
						<p>
							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
							<a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Nama Barang</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Kode Barang</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Qty. Nota AG</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis-2" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> Qty. Terima</a>
						</p>
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

<!-- MODAL TAMBAH AG -->
<div class="modal fade" id="modalTambahAg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post">
					<input type="hidden" id="tipe" class="form-clear">
					<input type="hidden" id="editedAgId" class="form-clear">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Gudang Pengirim</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-search"></i></span>
									<input type="text" id="darigudang" class="form-control form-clear" placeholder="Gudang Pengirim" readonly tabindex="-1">
									<input type="hidden" id="darigudangid" class="form-clear" value="">
								</div>
								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Tgl. Nota AG</label>
								<input type="text" id="tglnotaag" class="form-control form-clear" placeholder="Tgl. Nota AG" readonly tabindex="-1">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Kode Gudang</label>
								<input type="text" id="kodegudang" class="form-control form-clear" placeholder="Kode Gudang" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Gudang Penerima</label>
								<input type="text" id="kegudang" class="form-control form-clear" placeholder="Gudang Penerima" readonly tabindex="-1">
								<input type="hidden" id="kegudangId" class="form-clear" value="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Tgl. Terima</label>
								<input type="text" id="tglterima" class="form-control form-clear" placeholder="Tgl. Terima" readonly tabindex="-1">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>No. AG</label>
								<input type="text" id="norqag" class="form-control form-clear" placeholder="No. AG" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Pengirim</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-search"></i></span>
									<input type="text" id="pengirim" class="form-control form-clear" placeholder="Pengirim" readonly tabindex="-1">
									<input type="hidden" id="pengirimid" class="form-clear" value="">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Penerima</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-search"></i></span>
									<input type="text" id="penerima" class="form-control form-clear" placeholder="Penerima" readonly tabindex="-1">
									<input type="hidden" id="penerimaid" class="form-clear" value="">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Checker Pengirim 1</label>
								<select disabled class="form-control form-clear" id="checkerpengirim1">
									<option value="">CHECKER PENGIRIM 1</option>
									@foreach($checkers as $checker)
									<option value="{{ $checker->id }}">{{ $checker->namakaryawan }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Checker Pengirim 2</label>
								<select disabled class="form-control form-clear" id="checkerpengirim2">
									<option value="">CHECKER PENGIRIM 2</option>
									@foreach($checkers as $checker)
									<option value="{{ $checker->id }}">{{ $checker->namakaryawan }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Checker Penerima 1</label>
								<select disabled class="form-control form-clear" id="checkerpenerima1">
									<option value="">CHECKER PENERIMA 1</option>
									@foreach($checkers as $checker)
									<option value="{{ $checker->id }}">{{ $checker->namakaryawan }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Checker Penerima 2</label>
								<select disabled class="form-control form-clear" id="checkerpenerima2">
									<option value="">CHECKER PENERIMA 2</option>
									@foreach($checkers as $checker)
									<option value="{{ $checker->id }}">{{ $checker->namakaryawan }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Catatan</label>
								<input type="text" id="catatan" class="form-control form-clear" placeholder="Catatan" readonly tabindex="-1">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" id="btnSubmitTambahAg" class="btn btn-primary">Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<!-- END OF MODAL TAMBAH AG -->

<!-- MODAL TAMBAH AGD -->
<div class="modal fade" id="modalTambahAgd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post">
					<input type="hidden" id="agId" class="form-clear">
					<input type="hidden" id="agdId" class="form-clear">
					<input type="hidden" id="tipeAgd" class="form-clear">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Nama Stok</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-search"></i></span>
									<input type="text" id="namastok" class="form-control form-clear" placeholder="Nama Stok" readonly tabindex="-1">
									<input type="hidden" id="namastokid" class="form-clear" value="">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Kode Barang</label>
								<input type="text" id="kodebarang" class="form-control form-clear" placeholder="Kode Barang" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Qty. Rq</label>
								<input type="number" id="qtyrq" class="form-control form-clear" placeholder="Qty. Rq" readonly tabindex="-1">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Qty. Nota AG</label>
								<input type="number" id="qtynotaag" class="form-control form-clear" placeholder="Qty. Nota AG" readonly tabindex="-1">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Qty. Terima</label>
								<input type="number" id="qtyterima" class="form-control form-clear" placeholder="Qty. Terima" readonly tabindex="-1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Catatan</label>
								<input type="text" id="catatan" class="form-control form-clear" placeholder="Catatan" readonly tabindex="-1">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" id="btnSubmitTambahAgd" class="btn btn-primary">Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

{{-- <div class="modal fade" id="modalStaff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Pencarian Staff</h4>
			</div>
			<form class="form-horizontal" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="query">Masukkan kata kunci pencarian</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="query" class="form-control" placeholder="Masukkan kata kunci pencarian">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<table id="staff" class="table table-bordered table-striped tablepilih">
								<thead>
									<tr>
										<th class="text-center">NIK Lama</th>
                                        <th class="text-center">NIK HRD</th>
                                        <th class="text-center">Nama Pemeriksa 00</th>
									</tr>
								</thead>
								<tbody id="tbodyStaff">
				                    <tr class="kosong">
				                    	<td colspan="3" class="text-center">Tidak ada detail</td>
				                    </tr>
				                </tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="btnPilihStaff" class="btn btn-primary">Pilih</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div> --}}

{{-- <div class="modal fade" id="modalSubCabang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Sub Cabang</h4>
			</div>
			<form class="form-horizontal" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryBarang">Masukkan kata kunci pencarian</label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input type="text" id="txtQuerySubCabang" class="form-control" placeholder="Kode/Nama Sub Cabang">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<table class="table table-bordered table-striped tablepilih">
								<thead>
									<tr>
										<th class="text-center">Kode</th>
										<th class="text-center">Nama Sub Cabang</th>
									</tr>
								</thead>
								<tbody id="tbodySubCabang" class="tbodySelect">
									<tr class="kosong">
										<td colspan="2" class="text-center">Tidak ada detail</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="btnPilihSubCabang" class="btn btn-primary">Pilih</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div> --}}

{{-- <div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Barang</h4>
			</div>
			<form class="form-horizontal" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryBarang">Masukkan kata kunci pencarian</label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input type="text" id="txtQueryBarang" class="form-control" placeholder="Kode/Nama Barang">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<table class="table table-bordered table-striped tablepilih" id="tablebarang">
								<thead>
									<tr>
										<th class="text-center">Kode</th>
										<th class="text-center">Nama Barang</th>
										<th class="text-center">Stok Gudang</th>
									</tr>
								</thead>
								<tbody id="tbodyBarang" class="tbodySelect">
									<tr class="kosong">
										<td colspan="3" class="text-center">Tidak ada detail</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="btnPilihBarang" class="btn btn-primary">Pilih</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div> --}}
@endsection

@push('scripts')
<script type="text/javascript">
	var custom_search = [];
	for (var i = 0; i < 8; i++) {
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
	    tabledoubleclick,
	    table_index,table2_index,fokus;

    {{-- @include('lookupbarang') --}}
    // lookup
    lookupbarang();
    lookupsubcabang();
    lookupstaff();

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
				url			: '{{ route("antargudang.data") }}',
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
			    		console.log(row);
			    		var html_buttonaction  = '<div class="btn btn-xs btn-primary no-margin-action" data-toggle="tooltip" data-placement="bottom" title="Sync" onclick="sync(this,'+row.id+')" data-message="'+row.sync+'" data-tipe="header">SYNC</div>';
			    		    html_buttonaction += '<div class="btn btn-xs btn-success no-margin-action" data-toggle="tooltip" data-placement="bottom" title="Tambah" onclick="tambah_agd(this,'+row.id+')" data-message="'+row.tambahagd+'" data-tipe="header"><i class="fa fa-plus"></i></div>';
			    		    html_buttonaction += '<div class="btn btn-xs btn-warning no-margin-action" data-toggle="tooltip" data-placement="bottom" title="Edit" onclick="edit_ag(this,'+row.id+')" data-message="'+row.editag+'" data-tipe="header"><i class="fa fa-pencil"></i></div>';
			    		    html_buttonaction += '<div class="btn btn-xs btn-danger no-margin-action" data-toggle="tooltip" data-placement="bottom" title="Edit" onclick="delete_ag(this,'+row.id+')" data-message="'+row.deleteag+'" data-tipe="header"><i class="fa fa-trash"></i></div>';
			    		var html_buttonprints  = '<div class="btn-group no-margin-action">';
			    		    html_buttonprints += '	<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			    		    html_buttonprints += '		Cetak <span class="caret"></span>';
			    		    html_buttonprints += '	</button>';
			    		    html_buttonprints += '	<ul class="dropdown-menu">';
			    		    html_buttonprints += '		<li><a onclick="cetak(this,'+row.id+')" data-tipe="pil" data-message="'+row.cetakpil+'">Cetak PiL</a></li>';
			    		    html_buttonprints += '		<li><a onclick="cetak(this,'+row.id+')" data-tipe="nota" data-message="'+row.cetaknota+'">Cetak Nota</a></li>';
			    		    html_buttonprints += '	</ul>';
			    		    html_buttonprints += '</div>';
			    		var html = html_buttonaction + html_buttonprints;
		    			return html;
			    	}
				},
			    {
			    	"data" : "norqag",
			    	"className" : "menufilter numberfilter text-right"
			    },
			    {
			    	"data" : "darisubcabangnama",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "createdon",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "tglnotaag",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "namapengirim",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "kesubcabangnama",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "tglterima",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "namapenerima",
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
			    	"data" : "namabarang",
			    },
			    {
			    	"data" : "kodebarang",
			    },
				{
					"data" : "qtynotaag",
					"className" : "text-right"
				},
				{
					"data" : "qtyterima",
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
			console.log(ele);
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
				// 		pilih_staff(tipe_search);
				// 	}else if(ele.id == 'btnPilihStaff'){
				// 		pilih_staff(tipe_search);
				// 	}else if(ele.id == 'query'){
				// 		search_staff($(ele).val());
				// 		return false;
				// 	}
				// }

				// if($('#modalSubCabang').is(':visible')){
				// 	if(ele.className == 'selected'){
				// 		pilih_subcabang();
				// 	}else if(ele.id == 'btnPilihSubCabang'){
				// 		pilih_subcabang();
				// 	}else if(ele.id == 'txtQuerySubCabang'){
				// 		search_gudang($(ele).val());
				// 		return false;
				// 	}
				// }

				// if($('#modalBarang').is(':visible')){
				// 	if(ele.className == 'selected'){
				// 		pilih_barang();
				// 	}else if(ele.id == 'btnPilihBarang'){
				// 		pilih_barang();
				// 	}else if(ele.id == 'txtQueryBarang'){
				// 		search_barang($(ele).val());
				// 		return false;
				// 	}
				// }

				if($('#modalTambahAg').is(':visible')){
					// if(ele.id == 'darigudang'){
					// 	$('#modalSubCabang').modal('show');
					// 	$('#txtQuerySubCabang').val($(ele).val());
					// 	search_gudang($(ele).val());
					// }else if(ele.id == 'pengirim'){
					// 	tipe_search = 'pengirim';
					// 	$('#modalStaff').modal('show');
					// 	$('#modalStaff #query').val($(ele).val());
					// 	$('#modalStaff').on('shown.bs.modal', function() {
					// 		$('#query').focus();
					// 	});
					// 	search_staff($(ele).val());
					// }else if(ele.id == 'penerima'){
					// 	tipe_search = 'penerima';
					// 	$('#modalStaff').modal('show');
					// 	$('#modalStaff #query').val($(ele).val());
					// 	$('#modalStaff').on('shown.bs.modal', function() {
					// 		$('#query').focus();
					// 	});
					// 	search_staff($(ele).val());
					// }else if(ele.id == 'btnSubmitTambahAg'){
					if(ele.id == 'btnSubmitTambahAg'){
						submit_ag();
						return false;
					}else{
						if(!$('#modalStaff').is(':visible') && !$('#modalSubCabang').is(':visible')){
							submit_ag();
						}
					}
				}

				if($('#modalTambahAgd').is(':visible')){
					// if(ele.id == 'namastok'){
					// 	$('#modalBarang').modal('show');
					// 	$('#txtQueryBarang').val($(ele).val());
					// 	search_barang($(ele).val());
					// }else if(ele.id == 'btnSubmitTambahAgd'){
					if(ele.id == 'btnSubmitTambahAgd'){
						submit_agd();
						return false;
					}else{
						if(!$('#modalBarang').is(':visible')){
							submit_agd();
						}
					}
				}
			}
		});

		// $('#tbodyStaff').on('click', 'tr', function(){
		// 	$('.selected').removeClass('selected');
		// 	$(this).addClass("selected");
		// });

		// $('#tbodySubCabang').on('click', 'tr', function(){
		// 	$('.selected').removeClass('selected');
		// 	$(this).addClass("selected");
		// });

		// $('#tbodyBarang').on('click', 'tr', function(){
		// 	fokus = 'lookupbarang';
		// 	$('.selected').removeClass('selected');
		// 	$(this).addClass("selected");
		// });

		// $('#btnPilihBarang').on('click', function(){
		// 	pilih_barang();
		// });
		// $('#modalBarang table.tablepilih tbody').on('dblclick', 'tr', function(){
		// 	pilih_barang();
		// });
		// $('#btnPilihStaff').on('click', function(){
		// 	pilih_staff(tipe_search);
		// });
		// $('#modalStaff table.tablepilih tbody').on('dblclick', 'tr', function(){
		// 	pilih_staff(tipe_search);
		// });
		// $('#btnPilihSubCabang').on('click', function(){
		// 	pilih_subcabang();
		// });
		// $('#modalSubCabang table.tablepilih tbody').on('dblclick', 'tr', function(){
		// 	pilih_subcabang();
		// });
		$('#btnSubmitTambahAg').on('click', function(){
			submit_ag();
		});

		$('#btnSubmitTambahAgd').on('click', function(){
			submit_agd();
		});

		$('#table1 tbody').on('dblclick', 'tr', function(){
			var data = table.row(this).data();
			$.ajax({
	        	type: 'GET',
				url: '{{route("antargudang.view")}}',
				data: {id: data.id},
				dataType: "json",
	        	success: function(data){
	          		tabledoubleclick.clear();
	          		tabledoubleclick.rows.add([
			            {'0':'1.', '1':'No. AG', '2':data.norqag},
			            {'0':'2.', '1':'Subcabang Pengirim', '2':data.darisubcabangid},
			            {'0':'3.', '1':'Tgl. Nota AG', '2':data.tglnotaag},
			            {'0':'4.', '1':'Pengirim', '2':data.karyawanidpengirim},
			            {'0':'5.', '1':'Subcabang Penerima', '2':data.kesubcabangid},
			            {'0':'6.', '1':'Tgl. Terima', '2':data.tglterima},
			            {'0':'7.', '1':'Penerima', '2':data.karyawanidpenerima},
			            {'0':'8.', '1':'Checker 1 Pengirim', '2':data.karyawanidchecker1pengirim},
			            {'0':'9.', '1':'Checker 2 Pengirim', '2':data.karyawanidchecker2pengirim},
			            {'0':'10.', '1':'Checker 1 Penerima', '2':data.karyawanidchecker1penerima},
			            {'0':'11.', '1':'Checker 2 Penerima', '2':data.karyawanidchecker2penerima},
			            {'0':'12.', '1':'Tgl. Print Nota AG', '2':data.tglprintnotaag},
			            {'0':'13.', '1':'Catatan', '2':data.catatan},
			            {'0':'14.', '1':'Syncflag', '2':data.syncflag},
			            {'0':'15.', '1':'Created By', '2':data.createdby},
			            {'0':'16.', '1':'Created On', '2':data.createdon},
			            {'0':'17.', '1':'Last Updated By', '2':data.lastupdatedby},
			            {'0':'18.', '1':'Last Updated On', '2':data.lastupdatedon},
	          		]);
	          		tabledoubleclick.draw();
	          		$('#modalDoubleClickSuratJalan #myModalLabel').html('Data Antar Gudang');
	          		$('#modalDoubleClickSuratJalan').modal('show');
	        	}
	      	});
		});

		$('#table2 tbody').on('dblclick', 'tr', function(){
			var data = table2.row(this).data();
			$.ajax({
	        	type: 'GET',
				url: '{{route("antargudang.view.detail")}}',
				data: {id: data.id},
				dataType: "json",
	        	success: function(data){
	          		tabledoubleclick.clear();
	          		tabledoubleclick.rows.add([
			            {'0':'1.', '1':'Nama Barang', '2':data.namabarang},
			            {'0':'2.', '1':'Kode Barang', '2':data.kodebarang},
			            {'0':'3.', '1':'Qty. Rq', '2':data.qtyrq},
			            {'0':'4.', '1':'Qty. Nota AG', '2':data.qtynotaag},
			            {'0':'5.', '1':'Qty. Terima', '2':data.qtyterima},
			            {'0':'6.', '1':'Catatan', '2':data.catatan},
			            {'0':'7.', '1':'Created By', '2':data.createdby},
			            {'0':'8.', '1':'Created On', '2':data.createdon},
			            {'0':'9.', '1':'Last Updated By', '2':data.lastupdatedby},
			            {'0':'10.', '1':'Last Updated On', '2':data.lastupdatedon},
	          		]);
	          		tabledoubleclick.draw();
	          		$('#modalDoubleClickSuratJalan #myModalLabel').html('Data Antar Gudang Detail');
	          		$('#modalDoubleClickSuratJalan').modal('show');
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
				url: '{{route("antargudang.data.detail")}}',
				success: function(data){
					table2.clear();
					for (var i = 0; i < data.agd.length; i++) {
						var html_buttonaction  = '<div class="btn btn-xs btn-warning no-margin-action" data-toggle="tooltip" data-placement="bottom" title="Edit" onclick="edit_agd(this,'+data.agd[i].id+')" data-message="'+data.agd[i].edit+'" data-tipe="header"><i class="fa fa-pencil"></i></div>';
			    		    html_buttonaction += '<div class="btn btn-xs btn-danger no-margin-action" data-toggle="tooltip" data-placement="bottom" title="Edit" onclick="delete_agd(this,'+data.agd[i].id+')" data-message="'+data.agd[i].delete+'" data-tipe="header"><i class="fa fa-trash"></i></div>';
						table2.row.add({
							action    : html_buttonaction,
							namabarang: data.agd[i].namabarang,
							kodebarang: data.agd[i].kodebarang,
							qtynotaag : data.agd[i].qtynotaag,
							qtyterima : data.agd[i].qtyterima,
							id        : data.agd[i].id,
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

	function tambah_ag(){
		@cannot('antargudang.create')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		toogle_column('modalTambahAg',['darigudang'],true);
		toogle_select('modalTambahAg',['checkerpengirim1','checkerpengirim2']);
		$('#modalTambahAg #btnSubmitTambahAg').attr("tabindex",'2');
		$('#modalTambahAg #myModalLabel').html('Antar Gudang');
		$('#modalTambahAg #kegudang').val('{{ SubCabang::find(session("subcabang"))->kodesubcabang }}');
		$('#modalTambahAg #kegudangId').val('{{ session("subcabang") }}');
		$('#modalTambahAg').modal('show');
		@endcannot
	}

	function edit_ag(e,data){
		@cannot('antargudang.update')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		var message = $(e).data('message');
		if(message == 'editpengirim'){
			$('#modalTambahAg #tipe').val('edit');
			$.ajax({
				type     : 'get',
				url      : '{{ route("antargudang.view") }}',
				data     : {
					id : data,
				},
				dataType : "json",
				success  : function(data){
					toogle_column('modalTambahAg',['pengirim'],true);
					toogle_select('modalTambahAg',['checkerpengirim1','checkerpengirim2'],false);
					$('#modalTambahAg #myModalLabel').html('Edit Antar Gudang Pengirim');
					$('#modalTambahAg #tglnotaag').val('{{ Carbon\Carbon::now()->format("d-m-Y") }}');
					$('#modalTambahAg #editedAgId').val(data.id);
					$('#modalTambahAg #darigudang').val(data.darisubcabangid);
					$('#modalTambahAg #kegudang').val(data.kesubcabangid);
					$('#modalTambahAg #norqag').val(data.norqag);
					$('#modalTambahAg #checkerpengirim1').val(data.idchecker1pengirim);
					$('#modalTambahAg #checkerpengirim2').val(data.idchecker2pengirim);
					$('#modalTambahAg #pengirim').val(data.karyawanidpengirim);
					$('#modalTambahAg #pengirimid').val(data.idpengirim);
					$('#modalTambahAg #catatan').val(data.catatan);
					$('#modalTambahAg').modal('show');
				}
			});
		}else if(message == 'editpenerima'){
			$('#modalTambahAg #tipe').val('edit');
			$.ajax({
				type     : 'get',
				url      : '{{ route("antargudang.view") }}',
				data     : {
					id : data,
				},
				dataType : "json",
				success  : function(data){
					toogle_column('modalTambahAg',['penerima'],true);
					toogle_select('modalTambahAg',['checkerpenerima1','checkerpenerima2'],false);

					$('#modalTambahAg #checkerpengirim1').append($('<option>', {
					    value: data.idchecker1pengirim,
					    text: data.karyawanidchecker1pengirim
					}));
					$('#modalTambahAg #checkerpengirim2').append($('<option>', {
					    value: data.idchecker2pengirim,
					    text: data.karyawanidchecker2pengirim
					}));

					$('#modalTambahAg #myModalLabel').html('Edit Antar Gudang Penerima');
					$('#modalTambahAg #tglterima').val('{{ Carbon\Carbon::now()->format("d-m-Y") }}');
					$('#modalTambahAg #editedAgId').val(data.id);
					$('#modalTambahAg #darigudang').val(data.darisubcabangid);
					$('#modalTambahAg #kegudang').val(data.kesubcabangid);
					$('#modalTambahAg #norqag').val(data.norqag);
					$('#modalTambahAg #tglnotaag').val(data.tglnotaag);
					$('#modalTambahAg #checkerpengirim1').val(data.idchecker1pengirim);
					$('#modalTambahAg #checkerpengirim2').val(data.idchecker2pengirim);
					$('#modalTambahAg #pengirim').val(data.karyawanidpengirim);
					$('#modalTambahAg #pengirimid').val(data.idpengirim);
					$('#modalTambahAg #checkerpenerima1').val(data.idchecker1penerima);
					$('#modalTambahAg #checkerpenerima2').val(data.idchecker2penerima);
					$('#modalTambahAg #penerima').val(data.karyawanidpenerima);
					$('#modalTambahAg #penerimaid').val(data.idpenerima);
					$('#modalTambahAg #catatan').val(data.catatan);
					$('#modalTambahAg').modal('show');
				}
			});
		}else if(message == 'editag'){
			$('#modalTambahAg #tipe').val('edit');
			$.ajax({
				type     : 'get',
				url      : '{{ route("antargudang.view") }}',
				data     : {
					id : data,
				},
				dataType : "json",
				success  : function(data){
					toogle_column('modalTambahAg',['catatan'],true);
					toogle_select('modalTambahAg',['checkerpengirim1','checkerpengirim2','checkerpenerima1','checkerpenerima2']);
					$('#modalTambahAg #myModalLabel').html('Edit Antar Gudang');
					$('#modalTambahAg #editedAgId').val(data.id);
					$('#modalTambahAg #darigudang').val(data.darisubcabangid);
					$('#modalTambahAg #kegudang').val(data.kesubcabangid);
					$('#modalTambahAg #norqag').val(data.norqag);
					$('#modalTambahAg #catatan').val(data.catatan);
					$('#modalTambahAg').modal('show');
				}
			});
		}else{
			swal('Ups!', message,'error');
		}
		@endcannot
	}

	function delete_ag(e,data){
		@cannot('antargudang.delete')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		var message = $(e).data('message');
		if(message == 'delete'){
			$.ajaxSetup({
	            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
	        });

	        $.ajax({
				type: 'POST',
				data: {id: data},
				dataType: "json",
				url: '{{route("antargudang.delete")}}',
				success: function(data){
					table.ajax.reload(null, true);
					swal("Yes!", "Data berhasil dihapus", "success");
				},
				error: function(data){
					console.log(data);
				}
			});
		}else{
			swal('Ups!', message,'error');
		}
		@endcannot
	}

	function tambah_agd(e,data){
		@cannot('antargudang.create.detail')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		var message = $(e).data('message');
		if(message == 'tambah'){
			toogle_column('modalTambahAgd',['namastok','qtyrq','catatan'],true);
			$('#modalTambahAgd #myModalLabel').html('Antar Gudang Detail');
			$('#modalTambahAgd #agId').val(data);
			$('#modalTambahAgd').modal('show');
		}else{
			swal('Ups!', message,'error');
		}
		@endcannot
	}

	function edit_agd(e,data){
		@cannot('antargudang.update.detail')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		var message = $(e).data('message');
		if(message == 'edit'){
			$('#modalTambahAgd #tipeAgd').val('edit');
			$.ajax({
				type     : 'get',
				url      : '{{ route("antargudang.view.detail") }}',
				data     : {
					id : data,
				},
				dataType : "json",
				success  : function(data){
					toogle_column('modalTambahAgd',['namastok','qtyrq','catatan'],true);
					$('#modalTambahAgd #myModalLabel').html('Edit Antar Gudang Detail');
					$('#modalTambahAgd #agdId').val(data.id);
					$('#modalTambahAgd #namastok').val(data.namabarang);
					$('#modalTambahAgd #namastokid').val(data.stockid);
					$('#modalTambahAgd #kodebarang').val(data.kodebarang);
					$('#modalTambahAgd #qtyrq').val(data.qtyrq);
					$('#modalTambahAgd #catatan').val(data.catatan);
					$('#modalTambahAgd').modal('show');
				}
			});
		}else if(message == 'editpengirim'){
			$('#modalTambahAgd #tipeAgd').val('edit');
			$.ajax({
				type     : 'get',
				url      : '{{ route("antargudang.view.detail") }}',
				data     : {
					id : data,
				},
				dataType : "json",
				success  : function(data){
					toogle_column('modalTambahAgd',['qtynotaag'],true);
					$('#modalTambahAgd #myModalLabel').html('Edit Antar Gudang Detail Kirim');
					$('#modalTambahAgd #agdId').val(data.id);
					$('#modalTambahAgd #namastok').val(data.namabarang);
					$('#modalTambahAgd #namastokid').val(data.stockid);
					$('#modalTambahAgd #kodebarang').val(data.kodebarang);
					$('#modalTambahAgd #qtyrq').val(data.qtyrq);
					$('#modalTambahAgd #qtynotaag').val(data.qtynotaag);
					$('#modalTambahAgd #catatan').val(data.catatan);
					$('#modalTambahAgd').modal('show');
				}
			});
		}else{
			swal('Ups!', message,'error');
		}
		@endcannot
	}

	function delete_agd(e,data){
		@cannot('antargudang.delete.detail')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		var message = $(e).data('message');
		if(message == 'delete'){
			$.ajaxSetup({
	            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
	        });

	        $.ajax({
				type: 'POST',
				data: {id: data},
				dataType: "json",
				url: '{{route("antargudang.delete.detail")}}',
				success: function(data){
					table.rows(table_index).deselect();
					table.rows(table_index).select();
					swal("Yes!", "Data berhasil dihapus", "success");
				},
				error: function(data){
					console.log(data);
				}
			});
		}else{
			swal('Ups!', message,'error');
		}
		@endcannot
	}

	function sync(e,data){
		@cannot('antargudang.sync')
		swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
		@else
		tipe_edit = true;
		var message = $(e).data('message');
		agId = data;
		if(message == 'confirmsyncpengirim'){
			swal({
				title: "Ups!",
				text: "Anda yakin ingin synch ke subcabang pengirim?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes",
				cancelButtonText: "No",
				closeOnConfirm: false,
			},
			function(isConfirm){
				if (isConfirm) {
					swal({
						title: "Email Notifikasi",
						text: "Masukkan email yang ingin Anda notifikasikan supaya segera diproses",
						type: "input",
						showCancelButton: true,
						closeOnConfirm: false,
						animation: "slide-from-top",
						inputPlaceholder: "Email"
					},
					function(inputValue){
						if (inputValue === false) return false;

						if (inputValue === "") {
							swal.showInputError("Email tidak boleh kosong!");
							return false
						}

						$.ajax({
							type     : 'post',
							url      : '{{ route("antargudang.sync") }}',
							data     : {
								id    : agId,
								email : inputValue,
							},
							dataType : "json",
							success  : function(data){
								table.ajax.reload(null, true);
								tipe_edit = null;
								setTimeout(function(){
									table.row(0).select();
								},1000);
								swal("Yes!", "Data berhasil di synch", "success");
							}
						});
					});
				}
			});
		}else if(message == 'confirmsyncpengirim2'){
			swal({
				title: "Ups!",
				text: "Anda yakin ingin synch ke subcabang pengirim?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes",
				cancelButtonText: "No",
				closeOnConfirm: false,
				showLoaderOnConfirm: true,
			},
			function(isConfirm){
				if (isConfirm) {
					$.ajax({
						type     : 'post',
						url      : '{{ route("antargudang.sync") }}',
						data     : {
							id : agId,
						},
						dataType : "json",
						success  : function(data){
							table.ajax.reload(null, true);
							tipe_edit = null;
							setTimeout(function(){
								table.row(0).select();
							},1000);
							swal("Yes!", "Data berhasil di synch", "success");
						}
					});
				}
			});
		}else if(message == 'confirmsyncpenerima'){
			swal({
				title: "Ups!",
				text: "Anda yakin ingin synch ke subcabang penerima?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes",
				cancelButtonText: "No",
				closeOnConfirm: false,
				showLoaderOnConfirm: true,
			},
			function(isConfirm){
				if (isConfirm) {
					$.ajax({
						type     : 'post',
						url      : '{{ route("antargudang.sync") }}',
						data     : {
							id : agId,
						},
						dataType : "json",
						success  : function(data){
							table.ajax.reload(null, true);
							tipe_edit = null;
							setTimeout(function(){
								table.row(0).select();
							},1000);
							swal("Yes!", "Data berhasil di synch", "success");
						}
					});
				}
			});
		}else{
			swal('Ups!', message, 'error');
		}
		@endcannot
	}

	$('#modalTambahAg').on('hide.bs.modal', function(e){
		clear_column('modalTambahAg');
		toogle_column('modalTambahAg',['darigudang','tglterima','pengirim','penerima','catatan']);
		toogle_select('modalTambahAg',['checkerpengirim1','checkerpengirim2','checkerpenerima1','checkerpenerima2']);
	});

	$('#modalTambahAgd').on('hide.bs.modal', function(e){
		clear_column('modalTambahAgd');
		toogle_column('modalTambahAgd',['namastok','qtynotaag','qtyterima','catatan']);
	});

	function submit_ag(){
		tipe = $('#modalTambahAg #tipe').val();
		if(!tipe){
			@cannot('antargudang.create')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			darigudang = $('#modalTambahAg #darigudangid').val();
			if(darigudang){
				tipe_edit = true;
				$.ajaxSetup({
					headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
				});

				$.ajax({
					type: 'POST',
					data: {
						darigudang : $('#modalTambahAg #darigudangid').val(),
					},
					dataType: "json",
					url: '{{route("antargudang.create")}}',
					success: function(data){
						if(data.success){
							$('#modalTambahAg').modal('hide');
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
			}else{
				swal('Ups!', 'Gudang pengirim tidak boleh kosong, isi kolom atau batalkan input','error');
			}
			@endcannot
		}else{
			@cannot('antargudang.update')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			tipe_edit = true;
			$.ajaxSetup({
				headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
			});

			$.ajax({
				type: 'POST',
				data: {
					agId             : $('#modalTambahAg #editedAgId').val(),
					tglnotaag         : $('#modalTambahAg #tglnotaag').val(),
					tglterima        : $('#modalTambahAg #tglterima').val(),
					pengirimId       : $('#modalTambahAg #pengirimid').val(),
					checkerpengirim1 : $('#modalTambahAg #checkerpengirim1').val(),
					checkerpengirim2 : $('#modalTambahAg #checkerpengirim2').val(),
					penerimaId       : $('#modalTambahAg #penerimaid').val(),
					checkerpenerima1 : $('#modalTambahAg #checkerpenerima1').val(),
					checkerpenerima2 : $('#modalTambahAg #checkerpenerima2').val(),
					catatan          : $('#modalTambahAg #catatan').val(),
				},
				dataType: "json",
				url: '{{route("antargudang.update")}}',
				success: function(data){
					if(data.success){
						$('#modalTambahAg').modal('hide');
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
		}
	}

	function submit_agd(){
		tipe     = $('#modalTambahAgd #tipeAgd').val();
		namastok = $('#modalTambahAgd #namastokid').val();
		qtyrq    = $('#modalTambahAgd #qtyrq').val();
		if(!tipe){
			@cannot('antargudang.create.detail')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			if(namastok && qtyrq){
				tipe_edit = true;
				$.ajaxSetup({
					headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
				});

				$.ajax({
					type: 'POST',
					data: {
						agId    : $('#modalTambahAgd #agId').val(),
						stockId : $('#modalTambahAgd #namastokid').val(),
						qtyrq   : $('#modalTambahAgd #qtyrq').val(),
						catatan : $('#modalTambahAgd #catatan').val(),
					},
					dataType: "json",
					url: '{{route("antargudang.create.detail")}}',
					success: function(data){
						if(data.success){
							$('#modalTambahAgd').modal('hide');
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
			}else{
				swal('Ups!', 'Isi barang dan qty. rq lebih dahulu', 'error');
			}
			@endcannot
		}else{
			@cannot('antargudang.update.detail')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			qtynotaag = $('#modalTambahAgd #qtynotaag').val();
			if(namastok && qtynotaag){
				if(qtynotaag == 0){
					swal({
						title: "Ups!",
						text: "Anda yakin ingin mengisi Qty Nota AG 0?",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "Yes",
						cancelButtonText: "No",
						closeOnConfirm: true,
					},
					function(){
						$.ajaxSetup({
							headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
						});

						$.ajax({
							type: 'POST',
							data: {
								agdId    : $('#modalTambahAgd #agdId').val(),
								stockId  : $('#modalTambahAgd #namastokid').val(),
								qtyrq    : $('#modalTambahAgd #qtyrq').val(),
								qtynotaag : $('#modalTambahAgd #qtynotaag').val(),
								catatan  : $('#modalTambahAgd #catatan').val(),
							},
							dataType: "json",
							url: '{{route("antargudang.update.detail")}}',
							success: function(data){
								if(data.success){
									$('#modalTambahAgd').modal('hide');
									table.rows(table_index).deselect();
									table.rows(table_index).select();
								}else{
									swal('Ups!', data.error,'error'); 
								}
							},
							error: function(data){
								console.log(data);
							}
						});
					});
				}else{
					$.ajaxSetup({
						headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
					});

					$.ajax({
						type: 'POST',
						data: {
							agdId    : $('#modalTambahAgd #agdId').val(),
							stockId  : $('#modalTambahAgd #namastokid').val(),
							qtyrq    : $('#modalTambahAgd #qtyrq').val(),
							qtynotaag : $('#modalTambahAgd #qtynotaag').val(),
							catatan  : $('#modalTambahAgd #catatan').val(),
						},
						dataType: "json",
						url: '{{route("antargudang.update.detail")}}',
						success: function(data){
							if(data.success){
								$('#modalTambahAgd').modal('hide');
								table.rows(table_index).deselect();
								table.rows(table_index).select();
							}else{
								swal('Ups!', data.error,'error'); 
							}
						},
						error: function(data){
							console.log(data);
						}
					});
				}

			}else{
				swal('Ups!', 'Isi barang dan qty. Nota AG lebih dahulu', 'error');
			}
			@endcannot
		}
	}

	function cetak(e,data){
		var tipe    = $(e).data('tipe');
		var message = $(e).data('message');
		if(tipe == 'pil'){
			@cannot('antargudang.cetakpil')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			if(message == 'cetakpil'){
				url = '{{route("antargudang.cetakpil")}}'+'?id='+data;
			}else{
				swal('Ups!', message, 'error');
			}
			@endcannot
		}else if(tipe == 'nota'){
			@cannot('antargudang.cetaknota')
			swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
			@else
			if(message == 'cetaknota'){
				url = '{{route("antargudang.cetaknota")}}'+'?id='+data;
			}else{
				swal('Ups!', message, 'error');
			}
			@endcannot
		}
		window.open(url);
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

	function toogle_select(modal, column, disable = true){
		for (var i = 0; i < column.length; i++) {
			$('#'+modal+' #'+column[i]).prop('disabled', disable);
		}
	}

	// function search_staff(query){
	// 	$.ajaxSetup({
	// 		headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
	// 	});

	// 	$.ajax({
	// 		type: 'POST',
	// 		url: '{{route("lookup.getstaff")}}',
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

	// function search_gudang(query){
	// 	$.ajaxSetup({
	// 		headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
	// 	});

	// 	$.ajax({
	// 		type: 'POST',
	// 		url: '{{route("mstr.subcabang")}}',
	// 		data : {katakunci : query},
	// 		dataType : 'json',
	// 		success: function(data){
	// 			subcabang = data.subcabang;
	// 			$('#tbodySubCabang tr').remove();
	// 			var x = '';
	// 			if (subcabang.length > 0) {
	// 				for (var i = 0; i < subcabang.length; i++) {
	// 					x += '<tr>';
	// 					x += '<td>'+ subcabang[i].kodesubcabang +'<input type="hidden" class="id_sub" value="'+ subcabang[i].id +'"></td>';
	// 					x += '<td>'+ subcabang[i].namasubcabang +'</td>';
	// 					x += '</tr>';
	// 				}
	// 			}else {
	// 				x += '<tr><td colspan="7" class="text-center">Tidak ada detail</td></tr>';
	// 			}
	// 			$('#tbodySubCabang').append(x);
	// 		},
	// 		error: function(data){
	// 			console.log(data);
	// 		}
	// 	});
	// }

	// function search_barang(query,tipe=null){
	// 	$.ajax({
	// 		type   : 'GET',
	// 		url    : '{{route("lookup.getbarang",[null,null])}}/' + query + '/' + tipe,
	// 		data   : {filter : barang_custom_search},
	// 		success: function(data){
	// 			var barang = JSON.parse(data);
	// 			$('#tbodyBarang tr').remove();
	// 			var x = '';
	// 			if (barang.length > 0) {
	// 				for (var i = 0; i < barang.length; i++) {
	// 					x += '<tr>';
	// 					x += '<td class="menufilter textfilter">'+ barang[i].kodebarang +'<input type="hidden" class="id_brg" value="'+ barang[i].id +'"><input type="hidden" class="satuan" value="'+barang[i].satuan+'"></td>';
	// 					x += '<td class="menufilter textfilter">'+ barang[i].namabarang +'</td>';
	// 					x += '<td>'+ barang[i].jmlgudang +'</td>';
	// 					x += '</tr>';
	// 				}
	//             // }else {
	//             //     x += '<tr><td colspan="3" class="text-center">Tidak ada detail</td></tr>';
	//             }

	//             if ($.fn.DataTable.isDataTable("#tablebarang")) {
	//                 $('#tablebarang').DataTable().clear().destroy();
	//             }
	//             $('#tbodyBarang').append(x);
	//             $('#tablebarang').DataTable({ dom: 'rt', paging: false, "order": [[ 1, 'asc' ]],});
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
	// 		if(tipe == 'pengirim'){
	// 			$('#pengirim').val($('#tbodyStaff').find('tr.selected td').eq(2).text());
	// 			$('#pengirimid').val($('#tbodyStaff').find('tr.selected td .id_staff').val());
	// 			$('#modalStaff').modal('hide');
	// 			$("#pengirim").focus();
	// 		}else if(tipe == 'penerima'){
	// 			$('#penerima').val($('#tbodyStaff').find('tr.selected td').eq(2).text());
	// 			$('#penerimaid').val($('#tbodyStaff').find('tr.selected td .id_staff').val());
	// 			$('#modalStaff').modal('hide');
	// 			$("#penerima").focus();
	// 		}
	// 	}
	// 	tipe_search = null;
	// }

	// function pilih_subcabang(){
	// 	if ($('#tbodySubCabang').find('tr.selected td').eq(1).text() == '') {
	// 		swal("Ups!", "Subcabang belum dipilih.", "error");
	// 		return false;
	// 	}else{
	// 		$('#darigudang').val($('#tbodySubCabang').find('tr.selected td').eq(0).text());
	// 		$('#darigudangid').val($('#tbodySubCabang').find('tr.selected td .id_sub').val());
	// 		$('#modalSubCabang').modal('hide');
	// 		$("#darigudang").focus();
	// 	}
	// }

	// function pilih_barang(){
	// 	if ($('#tbodyBarang').find('tr.selected td').eq(1).text() == '') {
	// 		swal("Ups!", "Barang belum dipilih.", "error");
	// 		return false;
	// 	}else {
	// 		$('#kodebarang').val($('#tbodyBarang').find('tr.selected td').eq(0).text());
	// 		$('#namastok').val($('#tbodyBarang').find('tr.selected td').eq(1).text());
	// 		$('#namastokid').val($('#tbodyBarang').find('tr.selected td .id_brg').val());
	// 		$('#modalBarang').modal('hide');
	// 	}
	// }
</script>
@endpush