@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/1.4.2/css/scroller.dataTables.min.css">
@endpush

@section('main_container')
	<a href="#" class="btn btn-success" id="dl-excel"><i class='fa fa-spinner fa-spin'></i> Generating Excel</a>

	<table class="table display nowrap table-bordered table-striped mbuhsakarepku" id="table_report" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th rowspan="2" class="text-center">KODE SALES</th>
				<th rowspan="2" class="text-center">NAMA SALES</th>
				<th rowspan="2" class="text-center">RO</th>
				<th colspan="3" class="text-center">TARGET OA</th>
				<th colspan="4" class="text-center">REALISASI OA</th>
				<th colspan="4" class="text-center">EFEKTIF OA</th>
				<th colspan="5" class="text-center">SKU</th>
				<th colspan="2" class="text-center">TARGET SALES</th>
				<th colspan="2" class="text-center">ACHIEVEMENT B &amp; LAINNYA</th>
				<th colspan="2" class="text-center">ACHIEVEMENT E</th>
				@for($i=1;$i<=31;$i++)
				<th rowspan="2" class="text-center">{{$i}}</th>
				@endfor
			</tr>
			<tr>
				<th class="text-center">P</th>
				<th class="text-center">NP</th>
				<th class="text-center">TOTAL</th>
				<th class="text-center">P</th>
				<th class="text-center">NP</th>
				<th class="text-center">TOTAL</th>
				<th class="text-center">%</th>
				<th class="text-center">P</th>
				<th class="text-center">NP</th>
				<th class="text-center">TOTAL</th>
				<th class="text-center">%</th>
				<th class="text-center">FB2</th>
				<th class="text-center">FB4</th>
				<th class="text-center">FE2</th>
				<th class="text-center">FE4</th>
				<th class="text-center">TOTAL</th>
				<th class="text-center">B &amp; LAINNYA</th>
				<th class="text-center">E</th>
				<th class="text-center">RP</th>
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
			url  : '{!!route("laporan.penjualan.performancesalesmanexcel").'?bulan='.$bulan.'&tahun='.$tahun.'&type='.$type!!}',
			cache: false,
			success: function(href){
				if(href) {
					$('#dl-excel').attr("href", href);
					$('#dl-excel').attr("download",true);
				}else{
					$('#dl-excel').attr("href", "javascript: void(0)");
					$('#dl-excel').removeAttr("download");
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
				"url" : '{{ route("laporan.penjualan.performancesalesmandata") }}',
				data: function (d) {
					d._token = '{{ csrf_token() }}';
					d.bulan  = '{{$bulan}}';
					d.tahun  = '{{$tahun}}';
					d.type   = '{{$type}}';
				}
			},
			"columns": [
				{ "data" : "kodesales" },
				{ "data" : "namakaryawan" },
				{ "data" : "ro", "className" : "text-right" },
				{ "data" : "target_oa_p", "className" : "text-right" },
				{ "data" : "target_oa_np", "className" : "text-right" },
				{ "data" : "target_oa_total", "className" : "text-right" },
				{ "data" : "realisasi_oa_p", "className" : "text-right" },
				{ "data" : "realisasi_oa_np", "className" : "text-right" },
				{ "data" : "realisasi_oa_total", "className" : "text-right" },
				{ "data" : "realisasi_oa_persen", "className" : "text-right" },
				{ "data" : "efektif_oa_p", "className" : "text-right" },
				{ "data" : "efektif_oa_np", "className" : "text-right" },
				{ "data" : "efektif_oa_total", "className" : "text-right" },
				{ "data" : "efektif_oa_persen", "className" : "text-right" },
				{ "data" : "sku_fb2", "className" : "text-right" },
				{ "data" : "sku_fb4", "className" : "text-right" },
				{ "data" : "sku_fe2", "className" : "text-right" },
				{ "data" : "sku_fe4", "className" : "text-right" },
				{ "data" : "sku_total", "className" : "text-right" },
				{ "data" : "targetfb", "className" : "text-right" },
				{ "data" : "targetfe", "className" : "text-right" },
				{ "data" : "achievement_b_rp", "className" : "text-right" },
				{ "data" : "achievement_b_persen", "className" : "text-right" },
				{ "data" : "achievement_e_rp", "className" : "text-right" },
				{ "data" : "achievement_e_persen", "className" : "text-right" },
				@for($i=1;$i<=31;$i++)
				{ "data" : "total_{{$i}}", "className" : "text-right" },
				@endfor
			],
			// language: {sProcessing: "<i class='fa fa-spinner fa-spin fa-4x'></i>", loadingRecords: "<i class='fa fa-spinner fa-spin fa-4x'></i>"},
		});
	});
</script>
@endpush