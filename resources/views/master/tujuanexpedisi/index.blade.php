@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Master</li>
	<li class="breadcrumb-item"><a href="{{ route('tujuanexpedisi.index') }}">Tujuan Expedisi</a></li>
@endsection

@section('main_container')
	<div class="">
		<div class="row">
			<div class="x_panel">
				<div class="x_title">
					<h2>Daftar Tujuan Expedisi</h2>
					<ul class="nav navbar-right panel_toolbox">
						<li style="float: right;">
							<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
						<thead>
							<tr>
							    <th>Tujuan</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
						<p>
							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
							<a class="toggle-vis" data-column="0"><i id="eye0" class="fa fa-eye"></i> Tujuan</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
<script type="text/javascript">
	var custom_search = [];
	for (var i = 0; i < 1; i++) {
		custom_search[i] = {
			text   : '',
			filter : '=',
			tipe   : 'text'
		};
	}
	var filter_number = ['<=','<','=','>','>='];
	var filter_text = ['=','!='];
	var tipe = ['Find','Filter'];
	var column_index = 0;
	var last_index = 0;
	var context_menu_number_state = 'hide';
	var context_menu_text_state = 'hide';
	var table,table_index,fokus;


	$(document).ready(function() {
		table = $('#table1').DataTable({
			dom 		: 'lrtp',//lrtip -> lrtp
			serverSide	: true,
			stateSave	: true,
			deferRender : true,
            select: {style:'single'},
            keys: {keys: [38,40]},
			ajax 		: {
				url			: '{{ route("tujuanexpedisi.data") }}',
				data 		: function ( d ) {
					d.custom_search = custom_search;
					d.length 		= 50;
				},
			},
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
			columns		: [
			    { "data" : "tujuan", "className" : 'menufilter textfilter' },
			]
		});
		table.on('select', function ( e, dt, type, indexes ){
            fokus       = 'header';
            table_index = indexes;
        });
		$('a.toggle-vis').on( 'click', function (e) {
			e.preventDefault();

			// Get the column API object
			var column = table.column( $(this).attr('data-column') );

			// Toggle the visibility
			column.visible( ! column.visible() );
			$('#eye'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
		} );

		@include('master.javascript')
	});
</script>
@endpush