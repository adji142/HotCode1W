@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Master</li>
	<li class="breadcrumb-item"><a href="{{ route('toko.index') }}">Toko</a></li>
@endsection

@section('main_container')
	<div class="">
		<div class="row">
		@if(session('message'))
	    <div class="alert alert-{{session('message')['status']}}">
	      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
	      {{ session('message')['desc'] }}
	    </div>
	    @endif
			<div class="x_panel">
				<div class="x_title">
					<h2>Daftar Toko</h2>
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
								<th>Action</th>
							    <th>Tk Id</th>
							    <!-- <th>Toko Id Warisan Lama</th> -->
							    <th>Kode Toko</th>
							    <th>Nama Toko</th>
							    <th>Provinsi</th>
							    <th>Kota</th>
							    <th>Alamat</th>
							    <th>Kecamatan</th>
							    <th>Custom Wilayah</th>
							    <th>Telp</th>
							    <th>Fax</th>
							    <th>Penanggung Jawab</th>
							    <th>Tgl Dob</th>
							    <th>Catatan</th>
							    <!-- <th>Status Aktif</th> -->
							    <th>Pemilik</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
						<p>
							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
							<a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Tk ID</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="2"><i id="eye3" class="fa fa-eye"></i> Kode Toko</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Nama Toko</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Provinsi</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> Kota</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="6"><i id="eye6" class="fa fa-eye"></i> Alamat</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="7"><i id="eye7" class="fa fa-eye"></i> Kecamatan</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="8"><i id="eye8" class="fa fa-eye"></i> Custom Wilayah</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="9"><i id="eye9" class="fa fa-eye"></i> Telp</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="10"><i id="eye10" class="fa fa-eye"></i> Fax</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="11"><i id="eye11" class="fa fa-eye"></i> Penanggung Jawab</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="12"><i id="eye12" class="fa fa-eye"></i> Tgl Dob</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<a class="toggle-vis" data-column="13"><i id="eye13" class="fa fa-eye"></i> Catatan</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;
							<!-- <a class="toggle-vis" data-column="14"><i id="eye14" class="fa fa-eye"></i> Status Aktif</a>
							&nbsp;&nbsp;|&nbsp;&nbsp; -->
							<a class="toggle-vis" data-column="14"><i id="eye14" class="fa fa-eye"></i> Pemilik</a>
						</p>
					</div>

					<!-- Tabel Status Toko Aktif/Pasif -->
					<div class="row">
            			<div class="col-md-6 col-sm-6 col-xs-12">
							<table id="tableStatus" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th width="5%">A</th>
									    <th>Tgl. Status</th>
									    <th>Status</th>
									    <th>Keterangan</th>
									    <th>Diubah Oleh</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
							<div style="cursor: pointer; margin-top: 10px;" class="hidden">
								<p>
									<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
									<a class="toggle-vis2" data-column="1"><i id="eye-status1" class="fa fa-eye"></i> Tgl. Status</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<a class="toggle-vis2" data-column="2"><i id="eye-status2" class="fa fa-eye"></i> Status</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<a class="toggle-vis2" data-column="3"><i id="eye-status3" class="fa fa-eye"></i> Keterangan</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<a class="toggle-vis2" data-column="4"><i id="eye-status4" class="fa fa-eye"></i> Diubah Oleh</a>
								</p>
							</div>
						</div>

					<!-- Tabel Toko Hak Akses -->
            			<div class="col-md-6 col-sm-6 col-xs-12">
							<table id="tableHakAkses" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th width="5%">A</th>
									    <th>Tgl. Aktif</th>
									    <th>Toko00</th>
									    <th>Perusahaan</th>
									    <th>Diubah Oleh</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
							<div style="cursor: pointer; margin-top: 10px;" class="hidden">
								<p>
									<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
									<a class="toggle-vis3" data-column="1"><i id="eye-hakakses1" class="fa fa-eye"></i> Tgl. Aktif</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<a class="toggle-vis3" data-column="2"><i id="eye-hakakses2" class="fa fa-eye"></i> Toko00</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<a class="toggle-vis3" data-column="3"><i id="eye-hakakses3" class="fa fa-eye"></i> Perusahaan</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<a class="toggle-vis3" data-column="4"><i id="eye-hakakses4" class="fa fa-eye"></i> Diubah Oleh</a>
								</p>
							</div>
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>

	<!-- Status Aktif Pasif MODAL -->
		<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Tambah Status Toko Aktif/Pasif</h4>
					</div>
					<form class="form-horizontal" method="post">
						<input type="hidden" id="pengirimanId" class="form-clear" value="">
						<div class="modal-body">
						<input type="hidden" id="rowid">
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label>Tgl. Status</label>
										<input type="text" id="tglstatus" class="form-control form-clear" placeholder="Tgl. Status" data-inputmask="'mask': 'd-m-y'" readonly tabindex="1">
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label for="active" class="control-label col-md-3">Aktif?</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<label>
												<input type="checkbox" id="active" name="active" class="js-switch" tabindex="2" />
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<label>Nama Toko</label>
										<input type="text" id="nmtoko" class="form-control form-clear" placeholder="Nama Toko" readonly tabindex="3">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<label>TokoID</label>
										<input type="text" id="tokoid" class="form-control form-clear" placeholder="TokoID" readonly tabindex="4">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<label>WilID</label>
										<input type="text" id="wilid" class="form-control form-clear" placeholder="wilid" readonly tabindex="5">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<label>Alamat</label>
										<input type="text" id="alamat" class="form-control form-clear" placeholder="Alamat" readonly tabindex="6">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<label>Kota</label>
										<input type="text" id="kota" class="form-control form-clear" placeholder="Kota" readonly tabindex="7">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<label>Keterangan</label>
										<input type="text" id="keterangan" class="form-control form-clear" placeholder="Keterangan" tabindex="8">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" id="btnSubmitStatus" class="btn btn-primary">Simpan</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<!-- End Status Aktif Pasif Modal -->
	<!-- Form Kewenangan -->
	  <div class="modal fade" id="modalKewenangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
	    <div class="modal-dialog modal-xs" role="document">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	          <h4 class="modal-title" id="myModalLabel">Form Kewenangan</h4>
	        </div>
	        <form id="formKewenangan" class="form-horizontal" method="post">
	          <input type="hidden" id="action" value="">
	          <input type="hidden" id="tipe" value="">
	          <div class="modal-body">
	            <div class="row">
	              <div class="form-group">
	                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="userKewenangan">Username</label>
	                <div class="col-md-10 col-sm-10 col-xs-12">
	                  <input type="text" id="userKewenangan" class="form-control" placeholder="Username" required>
	                </div>
	              </div>
	            </div>
	            <div class="row">
	              <div class="form-group">
	                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="passKewenangan">Password</label>
	                <div class="col-md-10 col-sm-10 col-xs-12">
	                  <input type="password" id="passKewenangan" class="form-control" placeholder="Password" required>
	                </div>
	              </div>
	            </div>
	          </div>
	          <div class="modal-footer">
	            <button type="button" id="btnSubmitKewenangan" class="btn btn-primary">Proses</button>
	            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
	          </div>
	        </form>
	      </div>
	    </div>
	  </div>
	 <!-- end of Form Kewenangan -->
@endsection

@push('scripts')
<script type="text/javascript">
	/*var custom_search = [];
	for (var i = 0; i < 16; i++) {
		if(i == 13){
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
	var custom_detail_search = [];
	for (var i = 0; i < 5; i++) {
		if(i == 1){
			custom_detail_search[i] = {
				text   : '',
				filter : '=',
				tipe   : 'number'
			}; 
		}else if(i == 2){
			custom_detail_search[i] = {
				text   : '',
				filter : '=',
				tipe   : 'boolean'
			};
		}else{
			custom_detail_search[i] = {
				text   : '',
				filter : '=',
				tipe   : 'text'
			};
		}
	}*/
	var custom_search = [];
	for (var i = 0; i < 15; i++) {
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
	var table,table_index,fokus,tableStatus,tableHakAkses;

	$(document).ready(function() {
		$(".tgl").inputmask();
		$('.modal').on('show.bs.modal', function(event) {
		  var idx = $('.modal:visible').length;
		  $(this).css('z-index', 1040 + (10 * idx));
		});
		$('.modal').on('shown.bs.modal', function(event) {
		  var idx = ($('.modal:visible').length) -1; // raise backdrop after animation.
		  $('.modal-backdrop').not('.stacked').css('z-index', 1039 + (10 * idx));
		  $('.modal-backdrop').not('.stacked').addClass('stacked');
		});
		$(document).on('hidden.bs.modal', '.modal', function () { //fix modal's scroll
		  $('.modal:visible').length && $(document.body).addClass('modal-open');
		});

		table = $('#table1').DataTable({
			dom 		: 'lrtp',//lrtip -> lrtp
			serverSide	: true,
			stateSave	: true,
			deferRender : true,
            select: {style:'single'},
            keys: {keys: [38,40]},
			ajax 		: {
				url			: '{{ route("toko.data") }}',
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
			order		: [[1, "asc"], ],
			columns		: [
				{
			    	render : function(data, type, row){
			    		var id = $(this).data('tokoidwarisan');
			    		return "<div class='btn btn-success btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Tambah Status Aktif Pasif' onclick='tambahStatus(this,"+JSON.stringify(row)+")' data-message='"+row.add+"' data-rowid='"+row.id+"'><i class='fa fa-plus'></i></div><div class='btn btn-warning btn-xs no-margin-action' onclick='editToko(this,"+JSON.stringify(row)+")' title='Edit Toko'><i class='fa fa-pencil'></i></div>";
			    	}
			    },
			    {
			    	"data" : 'tokoidwarisan',
			    	"className" : 'menufilter textfilter',
			    },
	            {
	            	"data" : 'kodetoko',
	            	"className" : 'menufilter textfilter'
	            },
	            {
	            	"data" : 'namatoko',
	            	"className" : 'menufilter textfilter'
	            },
	            {
	            	"data" : 'propinsi',
	            	"className" : 'menufilter textfilter'
	            },
	            {
	            	"data" : 'kota',
	            	"className" : 'menufilter textfilter'
	            },
	            {
	            	"data" : 'alamat',
	            	"className" : 'menufilter textfilter'
	            },
	            {
	            	"data" : 'kecamatan',
	            	"className" : 'menufilter textfilter'
	            },
	            {
	            	"data" : 'customwilayah',
	            	"className" : 'menufilter textfilter'
	            },
	            {
	            	"data" : 'telp',
	            	"className" : 'menufilter textfilter'
	            },
	            {
	            	"data" : 'fax',
	            	"className" : 'menufilter textfilter'
	            },
	            {
	            	"data" : 'penanggungjawab',
	            	"className" : 'menufilter textfilter'
	            },
	            {
	            	"data" : 'tgldob',
	            	"className" : 'menufilter numberfilter'
	            },
	            {
	            	"data" : 'catatan',
	            	"className" : 'menufilter textfilter'
	            },
	            // di buatin tabel detail
	            // {
	            // 	"data" : 'statusaktif',
	            // 	"className" : 'menufilter textfilter uppercase'
	            // },
	            {
	            	"data" : 'pemilik',
	            	"className" : 'menufilter textfilter'
	            },
			],
			drawCallback	: function(setting)
			{
				if($("#modalTambah #rowid").val() != "")
				{
					rowToko = $("[data-rowid=" + $("#modalTambah #rowid").val() + "]").closest("tr");
					var rowTokoIdx = rowToko.index();
					table.row(rowTokoIdx).select();
					$("#modalTambah #rowid").val("");
				}
			}
		});
		table.on('select', function ( e, dt, type, indexes ){
            rowIdx = $("#table1 tr.selected").index();
            tokoid = table.data()[rowIdx].id;
            refreshStatus(tokoid);
            refreshHakAkses(tokoid);
        });
		/*$.contextMenu({
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
		});*/

		tableStatus = $('#tableStatus').DataTable({
			dom 		: 'lrtp',//lrtip -> lrtp
			select 		: true,
			scrollY 	: 200,
			scrollX 	: true,
			scroller 	: {
				loadingIndicator: true
			},
			order 		: [[1, "desc"],],
			columns		: [
				null,
		        {
		          "className" : "text-right",
		        },
		        null,
		        null,
		        null,
			],
		});

		tableHakAkses = $('#tableHakAkses').DataTable({
			dom 		: 'lrtp',//lrtip -> lrtp
			select 		: true,
			scrollY 	: 200,
			scrollX 	: true,
			scroller 	: {
				loadingIndicator: true
			},
			order 		: [[1, "desc"],],
			columns		: [
				null,
		        {
		          "className" : "text-right",
		        },
		        null,
		        null,
		        null,
			],
		});

		/*$.contextMenu({
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
		});*/

		$('a.toggle-vis').on( 'click', function (e) {
			e.preventDefault();

			// Get the column API object
			var column = table.column( $(this).attr('data-column') );

			// Toggle the visibility
			column.visible( ! column.visible() );
			$('#eye'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
		} );

		$('a.toggle-vis2').on( 'click', function (e) {
			e.preventDefault();

			// Get the column API object
			var column = tableStatus.column( $(this).attr('data-column') );

			// Toggle the visibility
			column.visible( ! column.visible() );
			$('#eye-status'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
		} );

		$('a.toggle-vis3').on( 'click', function (e) {
			e.preventDefault();

			// Get the column API object
			var column = tableStatus.column( $(this).attr('data-column') );

			// Toggle the visibility
			column.visible( ! column.visible() );
			$('#eye-hakakses'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
		} );

		@include('master.javascript')

		$("#modalTambah #btnSubmitStatus").click(function() {
			$("#modalKewenangan #action").val("tambah");
			$("#modalKewenangan #tipe").val("manager");
			$("#modalKewenangan #userKewenangan").val("");
			$("#modalKewenangan #passKewenangan").val("");
			$("#modalKewenangan").modal("show");
		});

		$("#modalKewenangan #btnSubmitKewenangan").click(function() {
			if($("#modalKewenangan #action").val() == "tambah")
			{
				var elem = document.querySelector(".js-switch");

				var data = {
					index 			: "tokoaktifpasif",
					tokoid 			: $("#modalTambah #rowid").val(),
					keterangan 		: $("#modalTambah #keterangan").val(),
					statusaktif 	: !elem.checked,
					username 		: $("#modalKewenangan #userKewenangan").val().trim().toUpperCase(),
					password 		: $("#modalKewenangan #passKewenangan").val(),
					permission 		: "tokoaktifpasif.tambah",
					_token			: "{{ csrf_token() }}",
				};

				$.ajaxSetup({
		            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
		          });

				$.ajax({
					url		: "{{ route('tokoaktifpasif.tambah') }}",
					type	: "POST",
					data 	: data,
					success : function(result)
					{
						if(result.success)
						{
							table.ajax.reload();

							$("#modalTambah #tglstatus").val("");
							$("#modalTambah #nmtoko").val("");
							$("#modalTambah #tokoid").val("");
							$("#modalTambah #wilid").val("");
							$("#modalTambah #alamat").val("");
							$("#modalTambah #kota").val("");
							$("#modalTambah #keterangan").val("");

							$("#modalKewenangan #userKewenangan").val("");
							$("#modalKewenangan #passKewenangan").val("");

							$("#modalKewenangan").modal("hide");
							$("#modalTambah").modal("hide");
						}
						else
						{
							swal("Peringatan", result.message, "error");
						}
					},
				});
			}
			else
			{
				var data = {
					index 			: "tokoaktifpasif",
					id 				: $("#modalTambah #rowid").val(),
					username 		: $("#modalKewenangan #userKewenangan").val().trim().toUpperCase(),
					password 		: $("#modalKewenangan #passKewenangan").val(),
					permission 		: "tokoaktifpasif.hapus",
					_token			: "{{ csrf_token() }}",
				};

				$.ajaxSetup({
		            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
		          });

				$.ajax({
					url		: "{{ route('tokoaktifpasif.hapus') }}",
					type	: "POST",
					data 	: data,
					success : function(result)
					{
						if(result.success)
						{
							$("#modalTambah #rowid").val($("#table1 tr.selected").find("div.btn").attr("data-rowid"));
							table.ajax.reload();

							$("#modalKewenangan #userKewenangan").val("");
							$("#modalKewenangan #passKewenangan").val("");

							$("#modalKewenangan").modal("hide");
							$("#modalTambah").modal("hide");
						}
						else
						{
							swal("Peringatan", result.message, "error");
						}
					},
				});
			}
		});

		$('#modalTambah').on('hidden.bs.modal', function () {
			
		});

		@include('master.javascript')
	});


	function refreshStatus(tokoid)
	{
		$.ajax({
			url : "{{route('tokoaktifpasif.data')}}",
			type : "GET",
			data : {
				tokoid : tokoid
			},
			success : function(result)
			{
				tableStatus.clear();
				$.each(result.data, function(k, v)
				{
					tableStatus.row.add([
						"<div class='btn btn-danger btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Hapus Status Aktif Pasif' onclick='hapusStatus(this,"+JSON.stringify(v)+")' data-message=''><i class='fa fa-trash'></i></div>",
						v.tglstatus,
						v.statusaktif,
						v.keterangan,
						v.lastupdatedby,
					]);
				});
				tableStatus.draw();
			}
		})
	}

	function refreshHakAkses(tokoid)
	{
		$.ajax({
			url : "{{route('tokohakakses.data')}}",
			type : "GET",
			data : {
				tkid : tokoid
			},
			success : function(result)
			{
				tableHakAkses.clear();
				$.each(result.data, function(k, v)
				{
					tableHakAkses.row.add([
						"<div class='btn btn-danger btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Hapus Status Aktif Pasif' data-message=''><i class='fa fa-trash'></i></div>",
						v.tglaktif,
						v.kodesubcabang,
						v.initperusahaan,
						v.lastupdatedby,
					]);
				});
				tableHakAkses.draw();
			}
		})
	}

	function tambahStatus(object, data)
	{
		var elem = document.querySelector(".js-switch");
		$(elem).prop("readonly", false);
		$(elem).prop("disabled", false);

		tglstatus = data.tglstatus;
		if (data.tglstatus === null)
		{
			tglstatus = new Date();
			tglstatus.setDate(1);
		}
		else
		{
			tglstatus = new Date(data.tglstatus);
		}
		tglserver = new Date();

		$.ajax({
			url		: "{{route('mstr.tglserver')}}",
			type	: "GET",
			success : function(result)
			{
				tglserver = new Date(result);

				if(
					tglserver.getFullYear() == tglstatus.getFullYear() && 
					tglserver.getMonth() == tglstatus.getMonth() &&
					tglserver.getDate() == tglstatus.getDate()
				)
					swal("Notification", "Tidak Bisa Tambah Record!\nSudah ada record dengan tgl. status = tgl. server!\nHubungi Manager Anda!", "warning");
				else
				{
					var tgl = "00".substring(0, 2 - tglserver.getDate().toString().length) + tglserver.getDate().toString();
					var bln = "00".substring(0, 2 - tglserver.getMonth().toString().length) + (parseInt(tglserver.getMonth()) + 1).toString();
					var thn = "0000".substring(0, 4 - tglserver.getFullYear().toString().length) + tglserver.getFullYear().toString();

					$("#modalTambah").modal("show");
					$("#modalTambah #tglstatus").val(tgl + "-" + bln + "-" + thn);
					$("#modalTambah #nmtoko").val(data.namatoko);
					$("#modalTambah #tokoid").val(data.tokoidwarisan);
					$("#modalTambah #wilid").val(data.customwilayah);
					$("#modalTambah #alamat").val(data.alamat);
					$("#modalTambah #kota").val(data.kota);

					$("#modalTambah #rowid").val(data.id);
					
					var elem = document.querySelector(".js-switch");
					if(data.statusaktif == "Pasif")
					{
						if(elem.checked == false)
						{
							$(elem).click();
						}
					}
					else
					{
						if(elem.checked == true)
						{
							$(elem).click();
						}	
					}

					$(elem).prop("readonly", true);
					$(elem).prop("disabled", true);
				}
			}
		});
	}

	function hapusStatus(object, data)
	{
		tglstatus = data.tglstatus;
		if (data.tglstatus === null)
		{
			tglstatus = new Date();
			tglstatus.setDate(1);
		}
		else
		{
			tglstatus = new Date(data.tglstatus);
		}
		tglserver = new Date();

		$.ajax({
			url		: "{{route('mstr.tglserver')}}",
			type	: "GET",
			success : function(result)
			{
				tglserver = new Date(result);

				if(
					tglserver.getFullYear() == tglstatus.getFullYear() &&
					tglserver.getMonth() == tglstatus.getMonth() &&
					tglserver.getDate() == tglstatus.getDate()
				)
				{
					swal({
                          title: "Perhatian!",
                          text : "Apakah Anda yakin ingin menghapus data ini?",
                          type : "warning",
                          showCancelButton  : true,
                          confirmButtonColor: '#DD6B55',
                          confirmButtonText : "Ya",
                          cancelButtonText  : "Tidak",
                          closeOnConfirm    : true,
                          closeOnCancel     : true
					},
					function(isConfirm) {
					  if (isConfirm) {
					  	$("#modalTambah #rowid").val(data.id);
					  	$("#modalKewenangan #userKewenangan").val("");
						$("#modalKewenangan #passKewenangan").val("");
						$("#modalKewenangan #action").val("hapus");
						$("#modalKewenangan").modal("show");
					  } else {
					    swal("Pemberitahuan", "Penghapusan dibatalkan", "warning");
					  }
					});
				}
				else
				{
					swal("Notification", "Tidak Bisa Hapus Record!\nTgl. status tidak sama dengan tgl. server!\nHubungi Manager Anda!", "warning");
				}
			}
		});
	}

	function editToko(e,data){
	    window.location.replace('{{ route('toko.ubah',null)}}/'+data.kodetoko); 
	}

</script>
@endpush