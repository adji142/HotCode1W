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
				<th rowspan="2" class="text-center">ID WIL</th>
				<th rowspan="2" class="text-center">NAMA TOKO</th>
				<th rowspan="2" class="text-center">P/N</th>
				<th rowspan="2" class="text-center">RODA</th>
				<th rowspan="2" class="text-center">NO TELP</th>
				<th rowspan="2" class="text-center">KODE SALES</th>
				<th rowspan="2" class="text-center">TANGGAL FIXEDROUTE</th>
				<th rowspan="2" class="text-center">SISA PLAFON</th>
				<th rowspan="2" class="text-center">SKU</th>
				<th rowspan="2" class="text-center">EFEKTIF OA%</th>
				<th rowspan="2" class="text-center">TARGET TOKO</th>
				<th colspan="2" class="text-center">ACHIEVEMENT</th>
				@for($i=1;$i<=31;$i++)
				<th rowspan="2" class="text-center">{{$i}}</th>
				@endfor
			</tr>
			<tr>
				<th class="text-center">RP.</th>
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
			url  : '{!!route("laporan.penjualan.performanceoutletexcel").'?bulan='.$bulan.'&tahun='.$tahun.'&type='.$type!!}',
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
				"url" : '{{ route("laporan.penjualan.performanceoutletdata") }}',
				data: function (d) {
					d._token = '{{ csrf_token() }}';
					d.bulan  = '{{$bulan}}';
					d.tahun  = '{{$tahun}}';
					d.type   = '{{$type}}';
				}
			},
			"columns": [
				{ "data" : "customwilayah" },
				{ "data" : "namatoko" },
				{ "data" : "pn" },
				{ "data" : "roda" },
				{ "data" : "telp" },
				{ "data" : "kodesales" },
				{ "data" : "tglfixedroute" },
				{ "data" : "sisa_plafon", "className" : "text-right"},
				{ "data" : "sku", "className" : "text-right"},
				{ "data" : "efektif_oa", "className" : "text-right"},
				{ "data" : "targetomset", "className" : "text-right"},
				{ "data" : "achievement_rp", "className" : "text-right"},
				{ "data" : "achievement_persen", "className" : "text-right"},
				@for($i=1;$i<=31;$i++)
				{ "data" : "total_{{$i}}", "className" : "text-right" },
				@endfor
			],
			// language: {sProcessing: "<i class='fa fa-spinner fa-spin fa-4x'></i>", loadingRecords: "<i class='fa fa-spinner fa-spin fa-4x'></i>"},
		});
	});
</script>
@endpush