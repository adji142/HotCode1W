@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Master</li>
	<li class="breadcrumb-item"><a href="{{ route('numerator.index') }}">Numerator</a></li>
@endsection

@section('main_container')
	<div class="">
		<div class="row">
			<div class="x_panel">
				<div class="x_title">
					<h2>Daftar Numerator</h2>
					<ul class="nav navbar-right panel_toolbox">
						<li style="float: right;">
							<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div class="row">
						<div class="col-md-12 text-right">
							<a href="{{route('numerator.edit')}}" class="btn btn-warning"><i class="fa fa-pencil"></i> Edit Numerator</a>
						</div>
					</div>
					<table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
						<thead>
							<tr>
							    <th>Doc</th>
							    <th>Depan</th>
							    <th>Nomor</th>
							    <th>Lebar</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
						<p>
							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
							<a class="toggle-vis" data-column="0"><i id="eye0" class="fa fa-eye"></i> Doc</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Depan</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Nomor</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="3"><i id="eye4" class="fa fa-eye"></i> Lebar</a>
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
    for (var i = 0; i < 4; i++) {
        if(i >= 2){
            custom_search[i] = {
                text   : '',
                filter : '=',
                tipe   : 'number'
            }; 
        }else{
            custom_search[i] = {
                text   : '',
                filter : '=',
                tipe   : 'text'
            };
        }
    }
    var filter_number = ['<=','<','=','>','>='];
    var filter_text = ['=','!='];
    var tipe = ['Find','Filter'];
    var column_index = 0;
    var last_index = 0;
    var context_menu_number_state = 'hide';
    var context_menu_text_state = 'hide';
    var table,table2,table_index,table2_index,fokus;

	$(document).ready(function() {
		table = $('#table1').DataTable({
			dom 		: 'lrtp',//lrtip -> lrtp
			serverSide	: true,
			stateSave	: true,
			deferRender : true,
            select: {style:'single'},
            keys: {keys: [38,40]},
			ajax 		: {
				url			: '{{ route("numerator.data") }}',
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
			    { "data" : 'doc', "className" : 'menufilter textfilter'  },
	            { "data" : 'depan', "className" : 'menufilter textfilter'  },
	            { "data" : 'nomor', "className" : 'menufilter numberfilter text-right'  },
	            { "data" : 'lebar', "className" : 'menufilter numberfilter text-right'  },
			]
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