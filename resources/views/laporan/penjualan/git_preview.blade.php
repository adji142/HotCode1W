@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/1.4.2/css/scroller.dataTables.min.css">
@endpush

@section('main_container')
	<a href="#" class="btn btn-success" id="dl-excel"><i class='fa fa-spinner fa-spin'></i> Generating Excel</a>

	<table class="table display nowrap table-bordered table-striped mbuhsakarepku" id="table_report" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th class="text-center">C1</th>
				<th class="text-center">C2</th>
				<th class="text-center">TGL PROFORMA</th>
				<th class="text-center">NO NOTA</th>
				<th class="text-center">TIPE TRANSAKSI</th>
				<th class="text-center">TGL TERIMA</th>
				<th class="text-center">JW</th>
				<th class="text-center">KODE SALES</th>
				<th class="text-center">NAMA TOKO</th>
				<th class="text-center">KOTA</th>
				<th class="text-center">IDWIL</th>
				<th class="text-center">RP NET</th>
				<th class="text-center">LAMA KIRIM</th>
				<th class="text-center">TGL KIRIM</th>
				<th class="text-center">PREDIKSI OVERDUE</th>
				<th class="text-center">TGL TERIMA TOKO</th>
				<th class="text-center">TGL SJ</th>
				<th class="text-center">TGL SERAH TERIMA CHECKER</th>
				<th class="text-center">TGL RENCANA KELUAR EXPEDISI</th>
				<th class="text-center">STS KIRIM</th>
				<th class="text-center">KETERANGAN PENDING</th>
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
			url  : '{!!route("laporan.penjualan.gitexcel").'?periode='.$periode.'&cabang='.$cabang.'&type='.$type!!}',
			cache: false,
			success: function(href){
				console.log(href);
				if(href) {
					$('#dl-excel').attr("href", href);
					// $('#dl-excel').attr("download",true);
				}else{
					$('#dl-excel').attr("href", "javascript: void(0)");
					// $('#dl-excel').removeAttr("download");
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
				"url" : '{{ route("laporan.penjualan.gitdata") }}',
				data: function (d) {
					d._token  = '{{ csrf_token() }}';
					d.periode = '{{$periode}}';
					d.cabang  = '{{$cabang}}';
					d.type    = '{{$type}}';
				}
			},
			"columns": [
				{ "data":"c1"},
				{ "data":"c2"},
				{ "data":"tglproforma"},
				{ "data":"nonota"},
				{ "data":"tipetransaksi"},
				{ "data":"tglterima"},
				{ "data":"jw"},
				{ "data":"kodesales"},
				{ "data":"namatoko"},
				{ "data":"kota"},
				{ "data":"idwil"},
				{ "data":"rpnet", "className" : "text-right"},
				{ "data":"lamakirim"},
				{ "data":"tglkirim"},
				{ "data":"ovoverprediksierdue"},
				{ "data":"tglterimatoko"},
				{ "data":"tglsj"},
				{ "data":"tglserahterimachecker"},
				{ "data":"tglrencanakeluarexpedisi"},
				{ "data":"stskirim"},
				{ "data":"keteranganpending"},
			],
			// language: {sProcessing: "<i class='fa fa-spinner fa-spin fa-4x'></i>", loadingRecords: "<i class='fa fa-spinner fa-spin fa-4x'></i>"},
		});
	});
</script>
@endpush