@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
    <li class="breadcrumb-item"><a href="{{ route('planjual.index') }}">Plan Jual Salesman</a></li>
@endsection
@section('main_container')
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<style type="text/css">
    .numberfilter, .namabarang{
        text-align: left;
    }
</style>
<div class="">
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Plan Jual Salesman</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-5">
    					<form class="form-horizontal" style="margin-bottom: 10px;">

    						<div class="form-group">
                                <div class="row">
                                     <div class="col-sm-6">
    							         <label class="control-label">Periode</label>
                                     </div>
                                </div>
                                 <div class="row">
                                    <div class="col-sm-6">
            							<select class="select2_single form-control bulan opsi" tabindex="-1" name="bulan" id="bulan">
            									<option value="01" >JANUARI</option>
            									<option value="02" >FEBRUARI</option>
            									<option value="03" >MARET</option>
            									<option value="04" >APRIL</option>
            									<option value="05" >MEI</option>
            									<option value="06" >JUNI</option>
            									<option value="07" >JULI</option>
            									<option value="08" >AGUSTUS</option>
            									<option value="09" >SEPTEMBER</option>
            									<option value="10" >OKTOBER</option>
            									<option value="11" >NOVEMBER</option>
            									<option value="12">DESEMBER</option>
            							</select>
                                    </div>

                                <div class="col-sm-6">
    								<input type="text" id="tahun" class="form-control" placeholder="Tahun">
                                </div>
                                  </div>
    						</div>
                            <div class="row">
                                <div class="col-sm-6">
            						<div class="form-group">
            							<label class="control-label">Salesman</label>
        								<input type="text" id="namasales" class="form-control" placeholder="Press Enter to pick">
        								<input type="hidden" id="idsales" class="form-control">
            						</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" style="padding-bottom: 10px;">Type</label><br>
                                        <input type="radio" name="tipehistory" value="0" class="iCheck" CHECKED> History     
                                            <input type="radio" name="tipehistory" value="1" class="iCheck"> Non-History
                                    </div>
                                </div>
                            </div>
    						<div class="form-group">
    							<a class="btn btn-success" id="btnproses"> Proses</a>
    						</div>
    					</form>
                    </div>
                    <div class="col-sm-7">
                         <form class="form-horizontal" style="margin-bottom: 10px;">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label">Target Omset</label>
                                    <div class="input-group" style="margin-bottom: 0;">
                                        <div class="input-group-addon"><i class="fa fa-money"></i></div>
                                        <input type="text" id="txtTargetOmset" class="form-control" readonly="">
                                        
                                    </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class=" control-label">Nominal Plan Jual</label>
                                    <div class="input-group" style="margin-bottom: 0;">
                                        <div class="input-group-addon"><i class="fa fa-money"></i></div>
                                        <input type="text" id="txtNominalPlan" class="form-control" readonly="">
                                        
                                    </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class=" control-label">Selisih</label>
                                <div class="input-group" style="margin-bottom: 0;">
                                    <div class="input-group-addon"><i class="fa fa-money"></i></div>
                                    <input type="text" id="txtSelisih" class="form-control" readonly="">
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class=" col-sm-11">
                                <p>
                                    <a onclick="print()" class="btn btn-success"><i class="fa fa-print"></i> Print</a>
                                    <a onclick="upload()" class="btn btn-primary"><i class="fa fa-print"></i> Upload</a>
                                </p>
                            </div>
                        </div>
                        
                        
                        
                    </form>


                    </div>
				</div>

				
				
                <div class="row" style="text-align: right;">
                    <button type="button" id="btnedt" onclick="doedit()" class="btn btn-warning">
                        Edit
                    </button>  
                    <button type="button" onclick="doFinish()" class="btn btn-success" style="display: none" id="btnfinish">
                        Selesai
                    </button>  
                    <table id="table3" class="table table-bordered table-striped display mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <!-- <th>Action</th> -->
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Qty Sisa</th>
                                <th>Qty Plan Jual</th>
                                <th>Nominal</th>
                                <th>Qty His. Jual</th>
                                <th>Qty. 00</th>
                                <th>Qty. 11</th>
                                <th>Harga Jual</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div id="totalHistory"> </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MODAL LOOKUP SALES AKTIF -->
<div class="modal fade" id="modalSales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">PILIH SALESMAN</h4>
            </div>
            
            <form id="frmSales" class="form-horizontal" method="post">
                <div class="modal-body">
                	 <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtSearchSales">Masukkan kata kunci pencarian</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="txtSearchSales" class="form-control" placeholder="Kode/Nama Salesman">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table id="tblsales" class="table table-bordered table-striped display mbuhsakarepku" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kode Salesman</th>
                                    <th>Nama Salesman</th
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button  type="button" id="btnpilihsales" class="btn btn-primary">Pilih</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>

    </div>
</div>


<!-- MODAL UPDATE OMSET -->
<div class="modal fade" id="modalOmset" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xs" role="document">
        <form id="frmOmset" class="form-horizontal" method="post">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">UPDATE RECORD</h4>
            </div>
                
                <div class="modal-body">

                    <div class="row">
                        <div class="form-group">
                            <input type="hidden" id="txtId" name="id" class="form-control" value="0">    
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" for="txtQtyPlan">Qty Plan Jual </label>
                            <div class="col-md-6 col-sm-10 col-xs-12">
                                <input type="text" id="txtQtyPlan" name="qtyplanjual" class="form-control" maxlength="10" required>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button  type="submit" id="btnsimpan" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">

	var table2, tblsales;
	var fokus;
    var ready = false;
    var edit = false;

	$.ajaxSetup({
        headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
    });

    

	$(document).ready(function(){
        $('input.iCheck').iCheck({
            radioClass: 'iradio_flat-green'
        });

        $('#txtQtyPlan').inputmask('Regex', { regex: "[0-9]*" });

        $('#txtSelisih').val('0');
        $('#txtTargetOmset').val('0');
        $('#txtNominalPlan').val('0');

        
        var idx = 0;
        table2 = $('#table3').DataTable({
            dom         : 'lrtp',
            select         : true,
            scrollY     : 300,
            // scrollY     : 312,
            scrollX     : true,
            scroller     : {
                loadingIndicator: true
            },
            order         : [[ 1, 'asc' ]],
            rowCallback: function(row, data, index) {
                 //console.log(data);
            },

            columns        : [
                {
                    "data" : "kodebarang",
                    "className" : "menufilter numberfilter"
                },
                {
                    "data" : "namabarang",
                    "className" : "menufilter textfilter namabarang"
                },
                {
                    "data" : "qtysisa",
                    "className" : "menufilter textfilter"
                },
                {
                    "data" : "qtyplanjual",
                    "className" : "menufilter textfilter",
                    render : function(data, type, row) {
                        idx = row.id
                        if(edit){
                            // row.qtysisa = 1;
                            if(row.qtysisa > 0) {
                                return "<input type='text' value='"+row.qtyplanjual+"' id='text"+row.id+"' onkeyup='doUpdateNominal(this,this.value,"+row.hargajual+", "+row.qtysisa+", "+row.qtyplanjual+")' onblur='doSaveNominal(this,this.value,"+row.id+", "+row.qtysisa+", "+row.hargajual+")' style='color:black;'/>";    
                            }else{
                                return row.qtyplanjual;    
                            }
                            
                        }else{
                            return row.qtyplanjual;
                        }
                    }
                },
                {
                    "data" : "nomplj",
                    "className" : 'menufilter textfilter nominal',
                    render : function(data, type, row) {
                        return numeral(row.nomplj).format('0,0')
                    }
                },
                {
                    "data" : "qtyhisjual",
                    "className" : "menufilter textfilter"
                },
                {
                    "data" : "qtystokgudang00",
                    "className" : "menufilter textfilter"
                },
                {
                    "data" : "qtystokgudang11",
                    "className" : "menufilter textfilter"
                },
                
                {
                    "data" : "hargajual",
                    "className" : "menufilter textfilter",
                    render : function(data, type, row) {
                        return numeral(row.hargajual).format('0,0')
                    }
                },
                
            ]
        });



		tblsales = $('#modalSales table').DataTable({
			dom     : 'lrt',
			keys    : true,
			searching : true,
			scrollY : "50vh",
			scrollX : true,
			scroller: { loadingIndicator: true },
			order   : [[ 1, 'asc' ]],
			columns : [
				{ "data" : "kodesales", "className": "textfilter" },
				{ "data" : "namakaryawan", "className": "textfilter" },
			],
		}).on('key-focus', function (e, dt, cell) {
			dt.rows().deselect();
			dt.row( cell.index().row ).select();
		});


		$('#btnproses').click(function(){
            $('#btnproses').attr('disabled', true);
            $('#btnproses').html('<i class="fa fa-spin fa-spinner"></i> Loading ...');
            var tipeHistory = $("input[name='tipehistory']:checked").val();
			var periode, salesid;
			periode = $('#tahun').val().concat($('#bulan').val());
			salesid = $('#idsales').val();
			
			if (periode.length < 6){
				swal("Ups!", "Isikan tahun.", "error");
				return;
			}else if(salesid === '' ){
				swal("Ups!", "Salesman belum dipilih.", "error");
				return;
			}else{
                table2.clear().draw();
                $('#txtNominalPlan').val('0');
                $('#txtTargetOmset').val('0');
                $('#txtSelisih').val('0');
                retrieve_planjual(salesid, periode);
                retrieve_history(salesid, periode, tipeHistory);
                console.log(tipeHistory);
			}
		});
	});
	//end document ready	
	$('#namasales').on('keypress', function(e){
        if (e.keyCode == '13') {
            var nama = $('#namasales').val()
            $('#txtSearchSales').val(nama);
            $('#modalSales').modal('show');
            
            search_sales();
            return false;
        }
    });

    $('#namasales').dblclick(function(){
        $('#txtSearchSales').val('');
        $('#modalSales').modal('show');
        
        search_sales();
    })

    $('#txtSearchSales').on('keypress', function(e){
        if (e.keyCode == '13') {
            // xtipe_salesman = $('#tipesalesman').val();
            search_sales(); $(this).blur();
            return false;
        }
    });

    $('#modalSales').on('keypress', function(e){
        if (e.keyCode == '13') {
            e.preventDefault();
            pilih_sales();
        }
    }).on('click', '#btnpilihsales', function(){
        pilih_sales();
    }).on('dblclick', 'tbody tr', function(){
        $('.selected').removeClass('selected');
        $(this).addClass("selected");
        pilih_sales();
    });

    $('#frmOmset').on('submit', function(e){
        e.preventDefault();
        var errMessage = '';
        

        if (errMessage == ''){
            var data = $(this).serialize();
            var url = '{{ route("planjual.update") }}';
            var post = 'POST';
            var id = $('#txtId').val();
            var tipeHistory = $("input[name='tipehistory']:checked").val();
            var periode, salesid;
            periode = $('#tahun').val().concat($('#bulan').val());
            salesid = $('#idsales').val();
            $.ajax({
                type : post,
                data :data,
                url : url,
                dataType : 'json',
                success:function(data)
                {
                   

                    if (data.success == false){
                        swal("Ups!", data.message, "error");
                       
                    }else{
                        $('#frmOmset')[0].reset();
                        swal("Success!", data.message, "success");
                        retrieve_planjual(salesid,periode);
                        retrieve_history(salesid,periode,tipeHistory);
                        $('modalOmset').modal('hide');
                    }
                    

                },
                error: function(data){
                    console.log(data.message);
                }
            });
        }else{
            swal("Ups!", errMessage, "error");
        }
   });

    function resetForm(){
        table2.clear().draw();
        $('#txtTargetOmset').val('0');
        $('#txtNominalPlan').val('0');
        $('#txtSelisih').val('0');
        $('#idsales').val('');
        $('namasales').val('');
    }

    function retrieve_history(salesid, periode, history){
        table2.clear();
        $.ajax({
            type   : 'GET',
            url    : '{{ route("planjual.history") }}',
            data : {karyawanidsalesman : salesid, periode : periode, history : history},
            success : function(data){
                var data_parse = JSON.parse(data['data']);
                table2.clear();
                table2.rows.add(data_parse);
                table2.draw();

                setTimeout(function(){
                    table2.columns.adjust();
                    if(data_parse.length > 0) {
                        table2.cell(':eq(0)').focus();
                    }
                }, 100);
                $('#totalHistory').html('');
                $('#totalHistory').append('<br/><span class="label label-info">Jumlah Record :  '+data['recordsTotal']+'</span>');

                $('#btnproses').attr('disabled', false);
                $('#btnproses').html('Proses');
                $('#btnedt').attr('disabled', false);
                $('#btnedt').html('Edit');
                $('#btnfinish').attr('disabled', false);
                $('#btnfinish').html('Selesai');
            },
            error: function(data){
                table2.clear();
                table2.columns.adjust();
            }
        });
    }

    function retrieve_planjual(salesid, periode){
        $.ajax({
            type   : 'GET',
            url    : '{{ route("planjual.data") }}',
            data : {karyawanidsalesman : salesid, periode : periode},
            dataType : 'json',
            success: function(data){
                console.log(data);
                var data_parse = JSON.parse(data['data']);
                
                $('#totalPlanJual').html('');
                $('#totalPlanJual').append('<br/><span class="label label-info">Jumlah Record :  '+data['recordsTotal']+'</span>');
                $('#txtTargetOmset').val(data['totalOmset']);
                $('#txtNominalPlan').val(numeral(data['totalNominal']).format('0,0'));
                $('#txtSelisih').val(numeral(data['selisih']).format('0,0'));
            },
            error: function(data){
                console.log(data);
            }
        });
    }

    

    function edit_qtyplansales(e, id, qtyplan, qtysisa){
        var valQtySisa = parseInt(qtysisa);
        var valQtyPlan = parseInt(qtyplan);

        if (valQtyPlan > valQtySisa){
            swal("Ups!", "Tidak bisa update record. Qty sisa lebih sedikit dari Qty Plan Jual. Hubungi manager anda", "error");
        }else{
            $('#frmOmset')[0].reset();
            $('#modalOmset #txtId').val(id);
            $('#modalOmset').modal('show');
            $('#modalOmset #txtQtyPlan').focus();
        }
    }

	function search_sales(){
		var search = $('#txtSearchSales').val();
		$.ajax({
			type : 'GET',
			url : '{{ route("planjual.salesman") }}',
			data : {search : search},
			success : function(data){
				var data_parse = JSON.parse(data);
                tblsales.clear();
                tblsales.rows.add(data_parse);
                tblsales.draw();

                setTimeout(function(){
                    tblsales.columns.adjust();
                    if(data_parse.length > 0) {
                        tblsales.cell(':eq(0)').focus();
                    }
                }, 100);

                $('#modalSales').focus();
               fokus = '#modalSales';
			},
			error: function(data){
				tblsales.clear();
				tblsales.columns.adjust();
			}
		});
	}

	function pilih_sales(){
		if (tblsales.row('.selected').data().length) {
            swal("Ups!", "Salesman belum dipilih.", "error");
            return false;
        }else {
            var data = tblsales.row('.selected').data();
            if(data.id){
                $('#idsales').val(data.id);
                $('#namasales').val(data.namakaryawan);
            }else{
                $('#salesmanid').val('');
                $('#namasales').val('');
            }

            $('#modalSales').modal('hide');
            $('#idsales').change();
            $('#namasales').focus();
            ready = true;
        }
	}

    function print(){
        if (ready == false){
            swal("Ups!", "Belum diproses.", "error");
            return;
        }
        var periode, salesid;
        periode = $('#tahun').val().concat($('#bulan').val());
        salesid = $('#idsales').val();
        url = '{{ route("transaksi.planjual.cetak") }}'+'?karyawanidsalesman='+salesid+'&periode='+periode;
        window.open(url);
    }

    function upload(){
        if (ready == false){
            swal("Ups!", "Belum diproses.", "error");
            return;
        }

        var intOmset = parseFloat($('#txtTargetOmset').val());
        var intNominal = parseFloat($('#txtNominalPlan').val());
        var intResult = (intOmset / intNominal)
        var intPersen = 0.8;
        
        if (intResult <= intPersen){
            swal('Ups!', 'Tidak bisa upload record. Nominal Plan Jual Salesman belum mencapai 80% dari target omset salesman. Hubungi Manager Anda', 'error');
        }else{
            var periode, salesid;
            periode = $('#tahun').val().concat($('#bulan').val());
            salesid = $('#idsales').val();

            $.ajax({
                type : 'GET',
                url    : '{{ route("planjual.upload") }}',
                data : {karyawanidsalesman : salesid, periode : periode},
                dataType : 'json',
                success:function(data)
                {
                    if (data.success == false){
                        swal("Ups!", data.message, "error");
                    }else{
                        resetForm();
                         swal('SUccess!','Data berhasil diupload.', 'success');
                    }
                },
                error: function(data){
                    console.log(data.message);
                }
            });

           

            
        }
        
    }

    function doedit(){
        $('#btnedt').html('<i class="fa fa-spinner fa-spin"></i> Loading ...')
        $('#btnedt').attr('disabled', true)
        $('#btnfinish').html('<i class="fa fa-spinner fa-spin"></i> Loading ...')
        $('#btnfinish').attr('disabled', true)
        edit = true
        var tipeHistory = $("input[name='tipehistory']:checked").val();
        var periode, salesid;
        periode = $('#tahun').val().concat($('#bulan').val());
        salesid = $('#idsales').val();
        
        if (periode.length < 6){
            swal("Ups!", "Isikan tahun.", "error");
            return;
        }else if(salesid === '' ){
            swal("Ups!", "Salesman belum dipilih.", "error");
            return;
        }else{
            table2.clear().draw();
            $('#txtNominalPlan').val('0');
            $('#txtTargetOmset').val('0');
            $('#txtSelisih').val('0');
            
            retrieve_planjual(salesid, periode);
            retrieve_history(salesid, periode, tipeHistory);
            $('#btnfinish').show();
            $('#btnedt').hide();
        }

    }

    function doFinish(){
        $('#btnfinish').html('<i class="fa fa-spinner fa-spin"></i> Loading ...')
        $('#btnfinish').attr('disabled', true)
        $('#btnedt').html('<i class="fa fa-spinner fa-spin"></i> Loading ...')
        $('#btnedt').attr('disabled', true)
        edit = false
        var tipeHistory = $("input[name='tipehistory']:checked").val();
        var periode, salesid;
        periode = $('#tahun').val().concat($('#bulan').val());
        salesid = $('#idsales').val();
        
        if (periode.length < 6){
            swal("Ups!", "Isikan tahun.", "error");
            return;
        }else if(salesid === '' ){
            swal("Ups!", "Salesman belum dipilih.", "error");
            return;
        }else{
            table2.clear().draw();
            $('#txtNominalPlan').val('0');
            $('#txtTargetOmset').val('0');
            $('#txtSelisih').val('0');
            
            retrieve_planjual(salesid, periode);
            retrieve_history(salesid, periode, tipeHistory);
        }

        $('#btnfinish').hide();
        $('#btnedt').show();

    }

    function doUpdateNominal(e, qty, hrg, sisa, qtyawal){
        if(qty != ''){
            if(qty > sisa){
                var currentId = e.getAttribute('id')
                $('#'+currentId).val(qtyawal);
                swal("Ups!", "Tidak bisa update record. Qty sisa lebih sedikit dari Qty Plan Jual. Hubungi manager anda", "error");
            }else{
                if(qty < 0){
                    var currentId = e.getAttribute('id')
                    $('#'+currentId).val(qtyawal);
                    swal("Ups!", "Qty Plan jual tidak boleh kurang dari 0", "error");
                }else{
                    var currentId = e.getAttribute('id')
                    var jumlah = parseFloat(qty) * parseFloat(hrg)
                    var x = $('#'+currentId).parent().next().text(numeral(jumlah).format('0,0'));
                }
            }
        }
    }

    function doSaveNominal(e, qty, id, sisa, harga){
        if(qty != ''){
            if (qty <= sisa){
                $('#text' + id).attr("disabled", true);
                var url = '{{ route("planjual.update") }}';
                var post = 'POST';
                $.ajax({
                    type : post,
                    data :{id: id, qtyplanjual:qty },
                    url : url,
                    success:function(data)
                    {
                        $('#text' + id).attr("disabled", false);
                        var periode, salesid;
                        periode = $('#tahun').val().concat($('#bulan').val());
                        salesid = $('#idsales').val();
                        retrieve_planjual(salesid, periode);
                    },
                    error: function(data){
                        console.log(data.message);
                    }
                });
            }
        }
    }

    $(window).bind('beforeunload', function(){
      return 'Are you sure you want to leave?';
    });
</script>
@endpush