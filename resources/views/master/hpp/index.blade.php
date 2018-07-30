@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Master</li>
	<li class="breadcrumb-item"><a href="{{ route('hpp.index') }}">Harga Jual 11</a></li>
@endsection

@section('main_container')
	<div class="">
		<div class="row">
			<div class="x_panel">
				<div class="x_title">
					<h2>Daftar Harga Jual 11</h2>
					<ul class="nav navbar-right panel_toolbox">
						<li style="float: right;">
							<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
          <div class="row">
            <div class="col-md-9">
              <form class="form-inline" style="margin-bottom: 10px;" id="form_filter">
                <div class="form-group">
                  <label style="margin-right: 10px;">Nama Barang</label>
                  <input type="text" id="namabarang" class="form-control" placeholder="Nama Barang" value="{{session('namabarang')}}">
                </div>
                <div class="form-group">
                  <label style="margin-right: 10px;">Status Aktif</label>
                  <select class="form-control" id="statusaktif">
                    <option value="2" id="statusaktif_semua">SEMUA</option>
                    <option value="1" id="statusaktif_aktif" selected>AKTIF</option>
                    <option value="0" id="statusaktif_pasif">PASIF</option>
                  </select>
                </div>
                <div class="form-group">
                  <label style="margin-right: 10px;">Kelompok Barang</label>
                  <select class="form-control" id="kelompokbarang">
                    <option value="all" id="kelompokbarang_semua">SEMUA</option>
                    <option value="fbfe" id="kelompokbarang_fbfe" selected>FBFE</option>
                    <option value="fb" id="kelompokbarang_fb">FB</option>
                    <option value="fe" id="kelompokbarang_fe">FE</option>
                    <option value="nonfbfe" id="kelompokbarang_nonfbfe">NON-FBFE</option>
                  </select>
                </div>
              </form>
            </div>
            <div class="col-md-3 text-right">
              <p>
                <a onclick="tampilkandata()" class="btn btn-success">Tampilkan Data</a>
              </p>
            </div>
          </div>
          <div class="row-fluid">
  					<table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
  						<thead>
  							<tr>
  								<th>Kode Barang</th>
  							    <th>Nama Barang</th>
  							    <th>Tgl Aktif</th>
  							    <th>Nominal Harga Jual 11</th>
  							</tr>
  						</thead>
  						<tbody></tbody>
  					</table>
  					<div style="cursor: pointer; margin-top: 10px;" class="hidden">
  						<p>
  							<span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
  							<a class="toggle-vis" data-column="0"><i id="eye0" class="fa fa-eye"></i> Kode Barang</a>
  							&nbsp;&nbsp;|&nbsp;&nbsp;
  							<a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Nama Barang</a>
  							&nbsp;&nbsp;|&nbsp;&nbsp;
  							<a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Tgl Aktif</a>
  							&nbsp;&nbsp;|&nbsp;&nbsp;
  							<a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Nominal Harga Jual 11</a>
  						</p>
  					</div>
          </div>
          {{-- <hr> --}}
          <div class="row-fluid">
            <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Tgl. Aktif</th>
                  <th>Nominal Harga Jual 11</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <div style="cursor: pointer; margin-top: 10px;" class="hidden">
              <p>
                <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                <a class="toggle-vis-2" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Tgl. Aktif</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Nominal Harga Jual 11</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Keterangan</a>
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
    if(i > 1){
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
      dom        : 'lrtp',//lrtip -> lrtp
      serverSide : true,
      stateSave  : true,
      deferRender: true,
      select: {style:'single'},
      keys: {keys: [38,40]},
      ajax       : {
        url : '{{ route("hpp.data") }}',
        data: function ( d ) {
					d.custom_search = custom_search;
          d.namabarang = $('#namabarang').val();
          d.statusaktif = $('#statusaktif').val();
          d.kelompokbarang = $('#kelompokbarang').val();
				},
			},
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
      columnDefs: [{type: 'sortdate',targets: [2]}],
      columns   : [
        {
          "data" : 'kodebarang',
          "className": "menufilter textfilter"
        },
        {
          "data"     : 'namabarang',
          "className": "menufilter textfilter"
        },
        {
          "data" : 'tglaktif',
          "className": "menufilter numberfilter"
        },
        {
          "data" : 'nominalhpp',
          "className": "menufilter numberfilter text-right"
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
      order     : [[ 0, 'asc' ]],
      rowCallback: function(row, data, index) {
        // console.log(data);
      },
      columnDefs: [{type: 'sortdate',targets: [0]}],
      columns   : [
        {
          "data" : "tglaktif",
        },
        {
          "data" : "nominalhpp",
          "className": "text-right"
        },
        {
          "data" : "keterangan",
        },
      ],
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

    table.on('select', function ( e, dt, type, indexes ){
      var rowData = table.rows( indexes ).data().toArray();
      console.log(rowData);
      $.ajaxSetup({
          headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
      });

      $.ajax({
        type: 'GET',
        data: {id: rowData[0].stockid},
        dataType: "json",
        url: '{{route("hpp.detail")}}',
        success: function(result){
          table2.clear();
          for (var i = 0; i < result.data.length; i++) {
            table2.row.add({
              tglaktif   : result.data[i].tglaktif,
              nominalhpp : result.data[i].nominalhpp,
              keterangan : result.data[i].keterangan,
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

    $('#form_filter').submit(function(e){
      e.preventDefault();
      table.draw();
    });

    $('#statusaktif').change(function(){
      table.ajax.reload(null, true);
    });

    $('#kelompokbarang').change(function(){
      table.ajax.reload(null, true);
    });

    @include('master.javascript')
  });
  
  function tampilkandata(){
    table.ajax.reload(null, true);
  }
</script>
@endpush