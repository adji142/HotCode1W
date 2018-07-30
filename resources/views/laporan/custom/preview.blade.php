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
				@foreach($report->field as $field)
				<th class="text-center">{{ $field->ketfield}}</th>
				@endforeach
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
			headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") },
			type: 'POST',
			url : '{{ route("laporan.custom.previewexcel") }}',
			data: {
				@foreach($get as $k=>$v)
				{{$k}} : '{{$v}}',
				@endforeach
			},
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
				"url" : '{{ route("laporan.custom.previewdata") }}',
				data: function (d) {
					d._token = '{{ csrf_token() }}';
					@foreach($get as $k=>$v)
					d.{{$k}} = '{{$v}}';
					@endforeach
				}
			},
			"columns": [
				@foreach($report->field as $field)
				{ "data" : "{{$field->namafield}}" {!!($field->tipefield == 1 || $field->tipefield == 2 || $field->tipefield == 3) ? ', "className" : "text-right"' : ''!!} },
				@endforeach
			],
			// language: {sProcessing: "<i class='fa fa-spinner fa-spin fa-4x'></i>", loadingRecords: "<i class='fa fa-spinner fa-spin fa-4x'></i>"},
		});
	});
</script>
@endpush