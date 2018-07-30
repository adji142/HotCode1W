@extends('layouts.default')
@section('breadcrumb')
    <li class="breadcrumb-item">Stock</li>
    <li class="breadcrumb-item"><a href="{{ route('salesorder.index') }}">Standar Stok</a></li>
@endsection
@php
if(Session::has('tahun')){
	$tahun = Session::get('tahun');
}else{
	$tahun = date('Y');
}

if(Session::has('bulan')){
	$bulan = Session::get('bulan');
}else{
	$bulan = date('m');
}
@endphp
@section('main_container')
<div class="">
	<div class="row">
		<div class="x_panel">
			<div class="x_title">
				<h2>Standar Stok</h2>
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
			                	<label style="margin-right: 10px;">Bulan</label>
			                	<select class="form-control blnpilih" value="{{$bulan}}" name="bulan">
			                		<option value="">Pilih Bulan</option>
			                		<option value="01" selected="selected">Januari</option>
			                		<option value="02">Februari</option>
			                		<option value="03">Maret</option>
			                		<option value="04">April</option>
			                		<option value="05">Mei</option>
			                		<option value="06">Juni</option>
			                		<option value="07">Juli</option>
			                		<option value="08">Agustus</option>
			                		<option value="09">September</option>
			                		<option value="10">Oktober</option>
			                		<option value="11">November</option>
			                		<option value="12">Desember</option>
			                	</select>
								<label>Tahun</label>
								<input type="text" id="tahun" name="tahun" class="form-control thnpilih" placeholder="tahun" value="{{$tahun}}"> <!--{{session('tahun')}}-->
			                </div>
		                </form>
					</div>
				</div>
				<div class="row-fluid">
					<a href="{{ route('standarstock.laporan') }}" class="btn btn-success pull-right">Laporan</a>
					<a href="javascrit:void(0)" class="btn btn-success pull-right" onclick="doProses()">Proses</a>
					<table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
						<thead>
							<tr>
							    <th>Kd. Barang</th>
							    <th>Nama Barang</th>
							    <th>Rata2 jual</th>
							    <th>Min. index</th>
							    <th>Max. index</th>	
							    <th>Stock Min.</th>
							    <th>Stock Max.</th>
							    <th>Keterangan</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
						<p>
							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
							<a class="toggle-vis" data-column="0"><i id="eye0" class="fa fa-eye"></i> Kd. Barang</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Nama Barang</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Rata2 jual</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Min. index</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="4"><i id="eye3" class="fa fa-eye"></i> Max. index</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="5"><i id="eye3" class="fa fa-eye"></i> Stock Min.</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="6"><i id="eye3" class="fa fa-eye"></i> Stock Max.</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="7"><i id="eye3" class="fa fa-eye"></i> Keterangan</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- modal proses -->
<div class="modal fade" id="modalProses" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Proses</h4>
            </div>
            <form class="form-horizontal" method="post" id="frmProses">
                <div class="modal-body">
                    <div class="row">
                    	<div class="col-xs-12">
                        	<label>Tanggal Aktif</label>
                        	<input type="text" name="tanggal" class="form-control tanggal" disabled="disabled">
                        </div>
                        <div class="col-xs-6">
                        	<label>Min. Index</label>
                        	<input type="number" name="minindex" class="form-control minindex" value="1">
                        </div>
                        <div class="col-xs-6">
                        	<label>Max. Index</label>
                        	<input type="number" name="maxindex" class="form-control maxindex" value="1">
                        </div>
                        <input type="hidden" name="recordownerid" class="recordownerid">
                        <input type="hidden" name="stockid" class="stockid">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btnproses" onclick="prosesSubmit()">Pilih</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </form>
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
		console.log($('.blnpilih').val())
		table = $('#table1').DataTable({
			dom 		: 'lrtp',//lrtip -> lrtp
			serverSide	: true,
			stateSave	: true,
			deferRender : true,
			select 		: true,
			ajax 		: {
				url			: '{{ route("standarstock.getdata") }}',
				data 		: function ( d ) {
					d.custom_search = custom_search;
					d.bulan 		= $('.blnpilih').val();
					d.tahun 	= $('.thnpilih').val();
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
			    	"data" : "kodebarang",
			    	"className" : "menufilter numberfilter"
			    },
			    {
			    	"data" : "namabarang",
			    	"className" : "menufilter textfilter text-left"
			    },
			    {
			    	"data" : "rataratajual",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "minindex",
			    	"className" : "menufilter textfilter"
			    },
			    {
			    	"data" : "maxindex",
			    	"className" : "menufilter numberfilter"
			    },
			    {
			    	"data" : "namabarang",
			    	"className" : "menufilter textfilter text-right",
			    	render     : function(data, type, row) {
                        return parseFloat(row.rataratajual) * parseFloat(row.minindex);
                    }
			    },
			    {
			    	"data" : "namabarang",
			    	"className" : "menufilter textfilter",
			    	render     : function(data, type, row) {
                        return parseFloat(row.rataratajual) * parseFloat(row.maxindex);
                    }
			    },
			    {
			    	"data" : "keterangan",
			    	"className" : "menufilter textfilter"
			    }
			]
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

		$('.tgl').change(function(){
			table.draw();
		});
	});

	function doProses(){
		var d = new Date()
		var n = d.getMonth()
		var months = d.getMonth() + 1
		months = String("00000" + months).slice(-2)
		if(n !== 0 && n !== 3 && n !== 6 && n !== 9){
			swal('Ups!', 'Tidak bisa proses STDS. Standard stok hanya bisa dilakukan di periode bulan Januari/ April/ Juli/oktober. HMA.','error')
		}else{
			console.log('modal show')
			$.ajax({
                type: 'POST',
                data: {tgl: d.getFullYear()+'-'+months+'-01', _token: '{{csrf_token()}}'},
                dataType: "json",
                url: '{{route("standarstock.chekdata")}}',
                success: function(data){
                	console.log(data)
                	if(data.status == 'false') {
              			swal('Ups!', 'Tidak bisa proses STDS. Record STDS periode namabulan tahun sudah diproses. HMA','error')  		
                	}else{
                		var month = d.getMonth() + 1
						var year = d.getFullYear()
						month  = String("00000" + month).slice(-2)
						$('#modalProses .tanggal').val(year + '-' + month+ '-'+'01' )
						// $('#modalProses .minindex').val(row.minindex)
						// $('#modalProses .maxindex').val(row.maxindex)
						// $('#modalProses .recordownerid').val(3)
						// $('#modalProses .stockid').val(row.id)
						$('#modalProses').modal('show')
                	}
                }
            });
		}
	}

	function prosesSubmit () {
		var minindex = parseFloat($('#modalProses .minindex').val())
		var maxindex = parseFloat($('#modalProses .maxindex').val())
		if(minindex == ''){
			$('#modalProses .minindex').focus()
		}

		if(maxindex == ''){
			$('#modalProses .maxindex').focus()
		}

		if (minindex < 1) {
			$('#modalProses .minindex').focus()
			swal('Ups!', 'DMNOB ‘Tidak bisa proses STDS. TB Min. Index harus diisi > 0','error')
		}

		if(maxindex < minindex) {
			swal('Ups!', 'DMNOB ‘TB Max. Index harus  diisi  dan > TB Min Index','error')
		}else{
			if(minindex > 4){
				swal('Ups!', 'DMNOB ‘TB Min. Index harus  diisi  dan <= 4','error')
			}else if (maxindex > 4) {
				swal('Ups!', 'DMNOB ‘TB Max. Index harus  diisi  dan <= 4','error')
			} else {
				$('.btnproses').attr('disabled', 'disabled')
				$('.btnproses').html('<i class="fa fa-spinner fa-spin"></i> loading')
				$.ajax({
	                type: 'POST',
	                data: $('#frmProses').serialize(),
	                url: '{{route("standarstock.insertdata")}}',
	                success: function(data){
	                	$('.btnproses').removeAttr('disabled')
						$('.btnproses').html('<span>Pilih</span>')
	                	console.log(data)
	                	if(data.status == 'true'){
	                		$('#modalProses').modal('hide')
	                		swal('Yeay!', 'Data Inserted, Proses Done','success')
	                	}
	                }
	            });
			}
		}
	}

	$('.blnpilih').change(function(){
		table.draw();
	})

	$('.thnpilih').blur(function(){
		table.draw();
	})
</script>
@endpush