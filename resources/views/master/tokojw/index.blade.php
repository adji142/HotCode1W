
@extends('layouts.default')

@section('breadcrumb')
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{ route('tokojw.index') }}">Toko JW</a></li>
@endsection

@section('main_container')
<div class="mainmain">
  <div class="row">
    @if(session('message'))
    <div class="alert alert-{{session('message')['status']}}">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      {{ session('message')['desc'] }}
    </div>
    @endif
    <div class="x_panel">
      <div class="x_title">
        <h2>Daftar Toko JW</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float: right;">
            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">

      	<!-- Tabel Toko -->
        <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="5%">Action</th>
			    <th>Nama Toko</th>
			    <th>Toko ID</th>
			    <th>IDWil</th>
			    <th>Alamat</th>
			    <th>Kota</th>
			    <th>Daerah</th>
              </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div style="cursor: pointer; margin-top: 10px;" class="hidden">
            <p>
              <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
              	<a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Nama Toko</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Toko ID</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> IDWil</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Alamat</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i> Kota</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<a class="toggle-vis" data-column="6"><i id="eye6" class="fa fa-eye"></i> Daerah</a>
            </p>
        </div>

        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
				<!-- Tabel Toko JW -->
				<table id="tablejw" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="1%">A</th>
						    <th>Tgl Aktif</th>
						    <th>Tempo Kredit</th>
						    <th>Tempo Kirim</th>
						    <th>Tempo Sales</th>
						    <th>Catatan</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
				<div style="cursor: pointer; margin-top: 10px;" class="hidden">
					<p>
						<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
						<a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Tgl Aktif</a>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Tempo Kredit</a>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Tempo Kirim</a>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a class="toggle-vis-2" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> Tempo Sales</a>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a class="toggle-vis-2" data-column="5"><i id="eye-detail5" class="fa fa-eye"></i> Catatan</a>
					</p>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<!-- Tabel Toko Status -->
		      	<table id="tableStatus" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="1%">A</th>
						    <th>Tgl Aktif</th>
						    <th>Status Toko</th>
						    <th>Keterangan</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
				<div style="cursor: pointer; margin-top: 10px;" class="hidden">
					<p>
						<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
						<a class="toggle-vis-3" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Tgl Aktif</a>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a class="toggle-vis-3" data-column="2"><i id="eye-detail1" class="fa fa-eye"></i> Status Toko</a>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a class="toggle-vis-3" data-column="3"><i id="eye-detail1" class="fa fa-eye"></i> Keterangan</a>
					</p>
				</div>
			</div>
		</div>

      </div>

    </div>

  </div>
</div>

<!-- Toko JW MODAL -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			<form id="formJW" class="form-horizontal" method="post">
				<div class="modal-body">
				<input type="hidden" id="id">
				<input type="hidden" id="tokoid">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label>Tgl. Aktif</label>
								<input type="text" id="tglaktif" class="form-control form-clear" placeholder="Tgl. Aktif" data-inputmask="'mask': 'd-m-y'" tabindex="1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Tempo Kredit</label>
								<input type="text" id="jwkredit" class="form-control form-clear" placeholder="Tempo Kredit">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Tempo Kirim</label>
								<input type="text" id="jwkirim" class="form-control form-clear" placeholder="Tempo Kirim">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Tempo Sales</label>
								<input type="text" id="jwsales" class="form-control form-clear" placeholder="Tempo Sales">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Catatan</label>
								<input type="text" id="catatan" class="form-control form-clear" placeholder="Catatan">
							</div>
						</div>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" id="btnSubmitTokoJW" class="btn btn-primary" onclick="SimpanTokoJW()">Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Status Aktif Pasif Modal -->

<!-- Toko Status MODAL -->
<div class="modal fade" id="modalTambahStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			<form id="formStatus" class="form-horizontal" method="post">
				<div class="modal-body">
				<input type="hidden" id="ids">
				<input type="hidden" id="tokoids">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label>Tgl. Aktif</label>
								<input type="text" id="tglaktifstatus" class="form-control form-clear" placeholder="Tgl. Status" data-inputmask="'mask': 'd-m-y'" tabindex="1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
			                    <label>Status Toko</label>
			                      <p>
			                        <input type="radio" name="status" id="status" value="B" /> B 
			                        <br>

			                        <input type="radio" name="status" id="status" value="M" /> M 
			                        <br>

			                        <input type="radio" name="status" id="status" value="K" /> K 
			                        
			                      </p>
			                  </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Keterangan</label>
								<input type="text" id="keterangan" class="form-control form-clear" placeholder="Keterangan">
							</div>
						</div>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" id="btnSubmitTokoJW" class="btn btn-primary" onclick="SimpanTokoStatus()">Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Status Aktif Pasif Modal -->

@endsection

@push('scripts')
<script type="text/javascript">
  var table, tablejw, tablestatus, ts, taktif, jkredit, jkirim, jsales, cat, taktifstatus, sts, ket;
  var context_menu_nodata_state         = 'hide';
  var context_menu_number_state         = 'hide';
  var context_menu_text_state           = 'hide';
  var last_index                        = '';
  var custom_search = [
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
    { text : '', filter : '=' },
  ];
  var filter_number = ['<=','<','=','>','>='];
  var filter_text   = ['=','!='];
  var tipe          = ['Find','Filter'];
  var column_index  = 0;

    $(document).ready(function(){
      $("#tglaktif").inputmask();
      $("#tglaktifstatus").inputmask();

      $('a.toggle-vis').on( 'click', function (e) {
          e.preventDefault();
          var col = $(this).attr('data-column');
          var column = table.column(col);
          column.visible( ! column.visible() );
          $('#eye'+col).toggleClass('fa-eye-slash');
      });

      $('a.toggle-vis-2').on( 'click', function (e) {
          e.preventDefault();
          var col = $(this).attr('data-column');
          var column = tablejw.column(col);
          column.visible( ! column.visible() );
          $('#eye-detail'+col).toggleClass('fa-eye-slash');
      });

      $('a.toggle-vis-3').on( 'click', function (e) {
          e.preventDefault();
          var col = $(this).attr('data-column');
          var column = tablestatus.column(col);
          column.visible( ! column.visible() );
          $('#eye-detail'+col).toggleClass('fa-eye-slash');
      });

      table = $('#table1').DataTable({
          dom        : 'lrtp',//lrtip -> lrtp
          serverSide : true,
          stateSave  : true,
          deferRender: true,
          select: {style:'single'},
          keys: {keys: [38,40]},
          ajax       : {
	          url : '{{ route("tokojw.data") }}',
			  data 		: function ( d ) {
				d.custom_search = custom_search;
				d.length 		= 50;
			  },
          },
          order   : [[ 1, 'asc' ]],
          scrollY : "33vh",
          scrollX : true,
          scroller: {
          loadingIndicator: true
          },
          columns: [
          	{
			    	render : function(data, type, row){
			    		return "<div class='btn btn-primary btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Tambah Toko JW' onclick='tambahTokoJW(this,"+JSON.stringify(row)+")' data-message='"+row.add+"' data-rowid='"+row.id+"'><i class='fa fa-plus'></i></div><div class='btn btn-success btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Tambah Toko Status' onclick='tambahTokoStatus(this,"+JSON.stringify(row)+")' data-message='"+row.add+"' data-rowid='"+row.id+"'><i class='fa fa-plus'></i></div>";
			    	}
			    },
            {
		    	"data" : 'namatoko',
		    	"className" : 'menufilter textfilter',
		    },
            {
            	"data" : 'id',
            	"className" : 'menufilter textfilter'
            },
            {
            	"data" : 'customwilayah',
            	"className" : 'menufilter textfilter'
            },
            {
            	"data" : 'alamat',
            	"className" : 'menufilter textfilter'
            },
            {
            	"data" : 'kota',
            	"className" : 'menufilter textfilter'
            },
            {
            	"data" : 'daerah',
            	"className" : 'menufilter textfilter'
            },
          ]
      });

      @include('master.javascript');

      table.on('select', function ( e, dt, type, indexes ){
            rowIdx = $("#table1 tr.selected").index();
            tokoid = table.data()[rowIdx].id;
            refreshTokoJW(tokoid);
            refreshTokoStatus(tokoid);
        });

      tablejw = $('#tablejw').DataTable({
			dom 		: 'lrtp',//lrtip -> lrtp
			select: {style:'single'},
			scrollY 	: 200,
			scrollX 	: true,
			scroller 	: {
				loadingIndicator: true
			},
			/*order 		: [[1, "desc"],],*/
			columns		: [
				null,
				null,
		        null,
		        null,
		        null,
		        null,
			],
		});

      tablestatus = $('#tableStatus').DataTable({
			dom 		: 'lrtp',//lrtip -> lrtp
			select 		: true,
			scrollY 	: 200,
			scrollX 	: true,
			scroller 	: {
				loadingIndicator: true
			},
			/*order 		: [[1, "desc"],],*/
			columns		: [
				null,
				null,
		        null,
		        null,
			],
		});

      $('#modalTambah').on('hidden.bs.modal', function (e) {
		  $(this).find('#formJW').trigger('reset');
		  $("#modalTambah").find("input[type=hidden]").val("");
	  });

	  $('#modalTambahStatus').on('hidden.bs.modal', function (e) {
		  $(this).find('#formStatus').trigger('reset');
		  $("#modalTambahStatus").find("input[type=hidden]").val("");
	  });

	  $('#table1 tbody').on('mousedown','td',function(e) {
	      if (e.which == 3) {
	        colIndex = $(this).closest("td").index();
	      }
	  });

      $.ajax({
		url		: "{{route('mstr.tglserver')}}",
		type	: "GET",
		success : function(result)
			{
				tglserver = new Date(result);

				var tgls = "00".substring(0, 2 - tglserver.getDate().toString().length) + tglserver.getDate().toString();
				var blns = "00".substring(0, 2 - tglserver.getMonth().toString().length) + (parseInt(tglserver.getMonth()) + 1).toString();
				var thns = "0000".substring(0, 4 - tglserver.getFullYear().toString().length) + tglserver.getFullYear().toString();

				var newtglserver = tgls+'/'+blns+'/'+thns;

				var d1 = newtglserver.split("/");

				ts = new Date(d1[2], parseInt(d1[1])-1, d1[0]);

			}
		});

    });

	function selectfirstJW() {
	    var Row = document.getElementById("tablejw");
		var Cells = Row.getElementsByTagName("td");
		
		if ( tablejw.data().any() ) {
		    taktif = Cells[1].innerText;
			jkredit = Cells[2].innerText;
			jkirim = Cells[3].innerText;
			jsales = Cells[4].innerText;
			cat = Cells[5].innerText;
		} else{
			taktif = "";
			jkredit = "";
			jkirim = "";
			jsales = "";
			cat = "";
		}
	}

	function selectfirstStatus() {
	    var Row = document.getElementById("tableStatus");
		var Cells = Row.getElementsByTagName("td");
		
		if ( tablestatus.data().any() ) {
		    taktifstatus = Cells[1].innerText;
			sts = Cells[2].innerText;
			ket = Cells[3].innerText;
		} else{
			taktifstatus = "";
			sts = "";
			ket = "";
		}
	}

	function convertDate(inputFormat) {
	  function pad(s) { return (s < 10) ? '0' + s : s; }
	  var d = new Date(inputFormat);
	  return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('-');
	}

    function refreshTokoJW(tokoid)
	{
		$.ajax({
			url : "{{route('tokojw.datajw')}}",
			type : "GET",
			data : {
			tokoid : tokoid
			},
			success : function(result)
			{
				tablejw.clear();
				$.each(result.data, function(k, v)
				{
					tablejw.row.add([
						"<div class='btn btn-warning btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Ubah Toko JW' onclick='UbahTokoJW(this,"+JSON.stringify(v)+")' data-message='' data-rowid='"+v.id+"'><i class='fa fa-pencil'></i></div>",
						convertDate(v.tglaktif),
						v.jwkredit,
						v.jwkirim,
						v.jwsales,
						v.catatan,
					]);
				});
				tablejw.draw();
				selectfirstJW();
			}
		})
	}

	function refreshTokoStatus(tokoid)
	{
		$.ajax({
			url : "{{route('tokojw.datastatus')}}",
			type : "GET",
			data : {
			tokoid : tokoid
			},
			success : function(result)
			{
				tablestatus.clear();
				$.each(result.data, function(k, v)
				{
					tablestatus.row.add([
						"<div class='btn btn-warning btn-xs no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Ubah Toko Status' onclick='UbahTokoStatus(this,"+JSON.stringify(v)+")' data-message='' data-rowid='"+v.id+"'><i class='fa fa-pencil'></i></div>",
						convertDate(v.tglaktif),
						v.status,
						v.keterangan,
					]);
				});
				tablestatus.draw();
				selectfirstStatus();
			}
		})
	}

	function tambahTokoJW(object, data)
	{
		$.ajax({
			url		: "{{route('mstr.tglserver')}}",
			type	: "GET",
			success : function(result)
			{
				tglserver = new Date(result);

				var tgl = "00".substring(0, 2 - tglserver.getDate().toString().length) + tglserver.getDate().toString();
				var bln = "00".substring(0, 2 - tglserver.getMonth().toString().length) + (parseInt(tglserver.getMonth()) + 1).toString();
				var thn = "0000".substring(0, 4 - tglserver.getFullYear().toString().length) + tglserver.getFullYear().toString();

				$("#modalTambah").modal("show");
				$(".modal-title").text("Tambah Toko JW");
				$("#modalTambah #tokoid").val(data.id);

	      		if ( tablejw.data().any() ) {
					$("#modalTambah #tglaktif").val(taktif);
					$("#modalTambah #jwkredit").val(jkredit);
					$("#modalTambah #jwkirim").val(jkirim);
					$("#modalTambah #jwsales").val(jsales);
					$("#modalTambah #catatan").val(cat);
				} else{
					$("#modalTambah #tglaktif").val(tgl + "-" + bln + "-" + thn);
				}
				
			}
		});
	}

	function tambahTokoStatus(object, data)
	{
		$.ajax({
			url		: "{{route('mstr.tglserver')}}",
			type	: "GET",
			success : function(result)
			{
				tglserver = new Date(result);

				var tgl = "00".substring(0, 2 - tglserver.getDate().toString().length) + tglserver.getDate().toString();
				var bln = "00".substring(0, 2 - tglserver.getMonth().toString().length) + (parseInt(tglserver.getMonth()) + 1).toString();
				var thn = "0000".substring(0, 4 - tglserver.getFullYear().toString().length) + tglserver.getFullYear().toString();

				$("#modalTambahStatus").modal("show");
				$(".modal-title").text("Tambah Toko Status");
				$("#modalTambahStatus #tokoids").val(data.id);

				if ( tablestatus.data().any() ) {
					$("#modalTambahStatus #tglaktifstatus").val(taktifstatus);
					$('input[name="status"][value="'+sts+'"]').prop('checked',true);
					$("#modalTambahStatus #keterangan").val(ket);
				} else{
					$("#modalTambahStatus #tglaktifstatus").val(tgl + "-" + bln + "-" + thn);
				}
				
			}
		});
	}

	function UbahTokoJW(object, data)
	{
		tglaktif = new Date(data.tglaktif);

		var tgla = "00".substring(0, 2 - tglaktif.getDate().toString().length) + tglaktif.getDate().toString();
		var blna = "00".substring(0, 2 - tglaktif.getMonth().toString().length) + (parseInt(tglaktif.getMonth()) + 1).toString();
		var thna = "0000".substring(0, 4 - tglaktif.getFullYear().toString().length) + tglaktif.getFullYear().toString();

		var newtglaktif = tgla+'/'+blna+'/'+thna;

		var d2 = newtglaktif.split("/");

		var ta = new Date(d2[2], parseInt(d2[1])-1, d2[0]);
		
		if( ta >= ts )
		{
			var res = data.tglaktif.split("-");
			var y = res[0];
			var m = res[1];
			var d = res[2];

			$("#modalTambah").modal("show");
			$(".modal-title").text("Toko JW Update");
			$("#modalTambah #tglaktif").val(d + "-" + m + "-" + y);
			$("#modalTambah #jwkredit").val(data.jwkredit);
			$("#modalTambah #jwkirim").val(data.jwkirim);
			$("#modalTambah #jwsales").val(data.jwsales);
			$("#modalTambah #catatan").val(data.catatan);
			$("#modalTambah #id").val(data.id);

		} else
		{
			
			swal("Notification", "Tidak Bisa Update Record. Tanggal Aktif Lebih Kecil Dari Tanggal Server. Hubungi Manager Anda.", "warning");
		}
			
	}

	function UbahTokoStatus(object, data)
	{
		tglaktif = new Date(data.tglaktif);
		
		var tgla = "00".substring(0, 2 - tglaktif.getDate().toString().length) + tglaktif.getDate().toString();
		var blna = "00".substring(0, 2 - tglaktif.getMonth().toString().length) + (parseInt(tglaktif.getMonth()) + 1).toString();
		var thna = "0000".substring(0, 4 - tglaktif.getFullYear().toString().length) + tglaktif.getFullYear().toString();

		var newtglaktif = tgla+'/'+blna+'/'+thna;

		var d2 = newtglaktif.split("/");

		var ta = new Date(d2[2], parseInt(d2[1])-1, d2[0]);

		if( ta >= ts )
		{
			var res = data.tglaktif.split("-");
			var y = res[0];
			var m = res[1];
			var d = res[2];

			$("#modalTambahStatus").modal("show");
			$(".modal-title").text("Toko Status Update");
			$('input[name="status"][value="'+data.status+'"]').prop('checked',true);
			$("#modalTambahStatus #tglaktifstatus").val(d + "-" + m + "-" + y);
			$("#modalTambahStatus #keterangan").val(data.keterangan);
			$("#modalTambahStatus #ids").val(data.id);

		} else
		{
			
			swal("Notification", "Tidak Bisa Update Record. Tanggal Aktif Lebih Kecil Dari Tanggal Server. Hubungi Manager Anda.", "warning");
		}
			
	}

	function SimpanTokoJW()
	{
		tglaktif = $("#modalTambah #tglaktif").val();
		var d2 = tglaktif.split("-");
		var ta = new Date(d2[2], parseInt(d2[1])-1, d2[0]);
		
		if( ta >= ts )
		{
			if($("#modalTambah #jwkredit").val() == "" || $("#modalTambah #tglaktif").val() == "")
				swal("Notification", "Tidak Bisa Simpan Record. Kolom Tgl. Aktif dan Tempo Kredit Harus Diisi. Hubungi Manager Anda.", "warning");
			else
			{
				var tglaktif = d2[2]+'-'+d2[1]+'-'+d2[0];
				$.ajax({
				url		: "{{route('tokojw.cektglaktif')}}",
				type	: "GET",
				data : {
					tokoid : $("#modalTambah #tokoid").val(),
					tglaktif : tglaktif,
					index : "jw"
				},
				dataType: 'json',
				success : function(result)
				{
					if(result != "")
					{
						swal("Notification", "Tanggal Aktif Sudah Ada", "warning");
					} else{
						
						var data = {
							index 		: "tokojw",
							id 			: $("#modalTambah #id").val(),
							tokoid 		: $("#modalTambah #tokoid").val(),
							tglaktif 	: $("#modalTambah #tglaktif").val(),
							jwkredit 	: $("#modalTambah #jwkredit").val(),
							jwkirim 	: $("#modalTambah #jwkirim").val(),
							jwsales 	: $("#modalTambah #jwsales").val(),
							catatan 	: $("#modalTambah #catatan").val(),
							_token		: "{{ csrf_token() }}",
						};

						$.ajaxSetup({
				            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
				        });

						$.ajax({
						url		: "{{route('tokojw.simpan')}}",
						type	: "POST",
						data 	: data,
						success : function(result)
							{
								if(result.success)
								{
									table.ajax.reload();

									$("#modalTambah #tglaktif").val("");
									$("#modalTambah #jwkredit").val("");
									$("#modalTambah #jwkirim").val("");
									$("#modalTambah #jwsales").val("");
									$("#modalTambah #catatan").val("");

									$("#modalTambah").modal("hide");
								}
								else
								{
									swal("Peringatan", result.message, "error");
								}
							}
						});
					}
				}
				});
			
			}

		} else
		{
			
			swal("Notification", "Tidak Bisa Tambah Record. Tanggal Aktif Lebih Kecil Dari Tanggal Server. Hubungi Manager Anda.", "warning");
		}
		
	}

	function SimpanTokoStatus()
	{
		tglaktifstatus = $("#modalTambahStatus #tglaktifstatus").val();
		var d2 = tglaktifstatus.split("-");
		var ta = new Date(d2[2], parseInt(d2[1])-1, d2[0]);

		if( ta >= ts )
		{
			if($("#modalTambahStatus #tglaktifstatus").val() == "" || !$("input[name='status']:checked").val() || $("#modalTambahStatus #keterangan").val() == "")
				swal("Notification", "Tidak Bisa Simpan Record. Semua Kolom Harus Diisi. Hubungi Manager Anda.", "warning");
			else
			{
				var tglaktif = d2[2]+'-'+d2[1]+'-'+d2[0];
				$.ajax({
				url		: "{{route('tokojw.cektglaktif')}}",
				type	: "GET",
				data : {
					tokoid : $("#modalTambah #tokoid").val(),
					tglaktif : tglaktif,
					index : "status"
				},
				dataType: 'json',
				success : function(result)
				{
					if(result != "")
					{
						swal("Notification", "Tanggal Aktif Sudah Ada", "warning");
					} else{
						var data = {
							index 			: "statustoko",
							id 				: $("#modalTambahStatus #ids").val(),
							tokoid 			: $("#modalTambahStatus #tokoids").val(),
							tglaktifstatus 	: $("#modalTambahStatus #tglaktifstatus").val(),
							status 			: $('input[name=status]:checked').val(),
							keterangan 		: $("#modalTambahStatus #keterangan").val(),
							_token			: "{{ csrf_token() }}",
						};

						$.ajaxSetup({
				            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
				        });

						$.ajax({
						url		: "{{route('tokojw.simpan')}}",
						type	: "POST",
						data 	: data,
						success : function(result)
							{
								if(result.success)
								{
									table.ajax.reload();

									$("#modalTambahStatus #tglaktifstatus").val("");
									$('input[name="status"]').prop('checked',false);
									$("#modalTambahStatus #keterangan").val("");

									$("#modalTambahStatus").modal("hide");
								}
								else
								{
									swal("Peringatan", result.message, "error");
								}
							}
						});
					}
				}
				});
				
			}

		} else
		{
			
			swal("Notification", "Tidak Bisa Tambah Record. Tanggal Aktif Lebih Kecil Dari Tanggal Server. Hubungi Manager Anda.", "warning");
		}
			
	}

</script>
@endpush
