@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/1.4.2/css/scroller.dataTables.min.css">
@endpush

@section('main_container')
	<a href="#" download class="btn btn-success" id="dl-excel"><i class='fa fa-spinner fa-spin'></i> Generating Excel</a>

	<table class="table display nowrap table-bordered table-striped mbuhsakarepku" id="table_report" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th rowspan="2" class="text-center">KODE BARANG</th>
				<th rowspan="2" class="text-center">MAIN GROUP</th>
				<th rowspan="2" class="text-center">NAMA BARANG</th>
				<th rowspan="2" class="text-center">P / N</th>
				<th rowspan="2" class="text-center">PRODUCT MIX</th>
				<th colspan="2" class="text-center">TARGET JUAL</th>
				<th colspan="2" class="text-center">STOCK GUDANG</th>
				<th colspan="2" class="text-center">STOCK MASUK GIT</th>
				<th colspan="2" class="text-center">PROYEKSI STOCK</th>
				<th colspan="2" class="text-center">ACHIEVEMENT QTY</th>
				<th colspan="2" class="text-center">ACHIEVEMENT RP</th>
				@for($i=1;$i<=31;$i++)
				<th rowspan="2" class="text-center">{{$i}}</th>
				@endfor
			</tr>
			<tr>
				<th class="text-center">QTY</th>
				<th class="text-center">RP</th>
				<th class="text-center">QTY</th>
				<th class="text-center">RP</th>
				<th class="text-center">QTY</th>
				<th class="text-center">RP</th>
				<th class="text-center">QTY</th>
				<th class="text-center">RP</th>
				<th class="text-center">QTY</th>
				<th class="text-center">%</th>
				<th class="text-center">RP</th>
				<th class="text-center">%</th>
			</tr>
		</thead>
	</table>
@endsection

@push('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/scroller/1.4.2/js/dataTables.scroller.min.js"></script>
<script type="text/javascript">
	var table_report;

	$(document).ready(function(){
		// Get Excel Files
		$.ajax({
			url  : '{!!route("laporan.penjualan.performanceproductexcel").'?bulan='.$bulan.'&tahun='.$tahun.'&type='.$type!!}',
			cache: false,
			success: function(href){
				if(href) {
					$('#dl-excel').attr("href", href);
				}
			},
			complete: function(){
				$('#dl-excel').html('<i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel');
			}
		});

		table_report = $('#table_report')
		.on('preXhr.dt', function(){ $("#dataTablesSpinner").show(); })
		.on('xhr.dt', function(){ $("#dataTablesSpinner").hide(); })
		.DataTable({
			"processing" : true,
			"serverSide" : true,
			"stateSave"  : true,
			"deferRender": true,
			"ordering"   : false,
			"searching"  : false,
			"scrollY" : '70vh',
			"scrollX" : true,
			"scroller": { loadingIndicator: true },
			stateLoadParams: function (settings, data) {
				for (var i = 0; i < data.columns.length; i++) {
					data.columns[i].search.search = "";
				}
			},
			"ajax"  : {
				"type": 'POST',
				"url" : '{{ route("laporan.penjualan.performanceproductdata") }}',
				data: function (d) {
					d._token = '{{ csrf_token() }}';
					d.bulan  = '{{$bulan}}';
					d.tahun  = '{{$tahun}}';
					d.type   = '{{$type}}';
				}
			},
			"columns": [
				{ "data" : "kodebarang" },
				{ "data" : "groupname" },
				{ "data" : "namabarang" },
				{ "data" : "pn" },
				{ "data" : "kategori" },
				{ "data" : "target_qty", "className" : "text-right" },
				{ "data" : "target_rp", "className" : "text-right" },
				{ "data" : "stokgudang_qty", "className" : "text-right" },
				{ "data" : "stokgudang_rp", "className" : "text-right" },
				{ "data" : "git_qty", "className" : "text-right" },
				{ "data" : "git_rp", "className" : "text-right" },
				{ "data" : "proyeksi_qty", "className" : "text-right" },
				{ "data" : "proyeksi_rp", "className" : "text-right" },
				{ "data" : "achievement_qty", "className" : "text-right" },
				{ "data" : "achievement_qty_persen", "className" : "text-right" },
				{ "data" : "achievement_rp", "className" : "text-right" },
				{ "data" : "achievement_rp_persen", "className" : "text-right" },
				@for($i=1;$i<=31;$i++)
				{ "data" : "total_{{$i}}", "className" : "text-right" },
				@endfor
			],
			// language: {sProcessing: "<i class='fa fa-spinner fa-spin fa-4x'></i>", loadingRecords: "<i class='fa fa-spinner fa-spin fa-4x'></i>"},
		});
	});
</script>
@endpush