@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
  	<li class="breadcrumb-item"><a href="{{ route('mutasi.index') }}">Mutasi</a></li>
@endsection

@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>Mutasi</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li style="float: right;">
						<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="row">
					<div class="col-md-12">
						<form class="form-inline">
			                <div class="form-group">
			                	<label style="margin-right: 10px;">Tgl. Mutasi</label>
								<input type="text" id="tglmulai" class="tgl form-control" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
								<label>-</label>
								<input type="text" id="tglselesai" class="tgl form-control" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
			                </div>
		                </form>
					</div>
				</div>
				<div class="row-fluid">
					<table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
						<thead>
							<tr>
							    <th>Tgl. Mutasi</th>
							    <th>No. Mutasi</th>
							    <th>Keterangan</th>
							    <th>Tipe</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
						<p>
							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
							<a class="toggle-vis" data-column="0"><i id="eye0" class="fa fa-eye"></i> Tgl. Mutasi</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> No. Mutasi</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Keterangan</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Tipe</a>
						</p>
					</div>
				</div>
				{{-- <hr> --}}
				<div class="row-fluid">
					<table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
						<thead>
							<tr>
							    <th>Nama Barang</th>
							    <th>Kode Barang</th>
							    <th>Sat.</th>
							    <th>Qty. Mutasi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
						<p>
							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
							<a class="toggle-vis-2" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Nama Barang</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Kode Barang</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Sat.</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Qty. Mutasi</a>
						</p>
					</div>
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
	var tipe_edit_header = null;
	var table,
	    table2,
	    tabledoubleclick,table_index,table2_index,fokus;

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
				url			: '{{ route("mutasi.data") }}',
				data 		: function ( d ) {
					d.custom_search = custom_search;
					d.tglmulai 		= $('#tglmulai').val();
					d.tglselesai 	= $('#tglselesai').val();
				},
			},
			order 		: [[ 0, 'asc' ]],
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
			    	"data" : "tglmutasi",
			    	"className" : "menufilter numberfilter"
			    },
			    {
			    	"data" : "nomutasi",
			    	"className" : "menufilter textfilter text-right"
			    },
			    {
			    	"data" : "keterangan",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "tipemutasi",
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
			    	"data" : "namabarang",
			    },
			    {
			    	"data" : "kodebarang",
			    },
				{
					"data" : "sat",
				},
				{
					"data" : "qtymutasi",
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
			}
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
				url: '{{route("mutasi.data.detail")}}',
				success: function(data){
					table2.clear();
					for (var i = 0; i < data.mtd.length; i++) {
						table2.row.add({
							namabarang : data.mtd[i].namabarang,
							kodebarang : data.mtd[i].kodebarang,
							sat        : data.mtd[i].sat,
							qtymutasi  : data.mtd[i].qtymutasi,
							hppa       : data.mtd[i].hppa,
							id         : data.mtd[i].id,
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
</script>
@endpush