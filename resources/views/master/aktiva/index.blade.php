@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Master</li>
    <li class="breadcrumb-item"><a href="{{ route('aktiva.index') }}">Aktiva</a></li>
@endsection

@section('main_container')
<div class="">
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Aktiva</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="float: right;">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
               
                    <div class="col-md-8">
                        <button id="btntambah" class="btn btn-primary">Tambah</button>
                        <button id="btnrefresh" class="btn btn-primary">Refresh</button>
                        <button id="btnpenyusutan" class="btn btn-primary">Penyusutan</button>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="txtSearch" class="form-control" placeholder="KODE/NAMA AKTIVA">
                    </div>
                </div>
                    

                <table id="table1" class="table table-bordered table-striped display mbuhsakarepku" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Aktiva ID</th>
                            <th>No. Register</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Golongan</th>
                            <th>Tgl Perolehan</th>
                            <th>Umur Penyusutan</th>
                            <th>Nilai Perolehan</th>
                            <th>Qty Penyusutan</th>
                            <th>Nilai Buku</th>
                            <th>Tgl Keluar</th>
                            <th>Nilai Jual</th>
                            <th>Keterangan Keluar</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <hr>
                
                <table id="table2" class="table table-bordered table-striped display mbuhsakarepku" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Bukti</th>
                            <th>Penyusutan ke</th>
                            <th>Nilai Penyusutan</th>
                            <th>Uraian</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table> 
                
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH ITEM -->
<div class="modal fade" id="modalEntry" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xs" role="document">
        <form id="frmAktiva" class="form-horizontal" method="post">
        {!! csrf_field() !!}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">TAMBAH AKTIVA</h4>
            </div>
                
                <div class="modal-body">

                    <div class="row">
                        <div class="form-group">
                            <input type="hidden" id="txtId" name="id" class="form-control" value="0"> 
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" for="txtNoAktiva">Aktiva ID </label>
                            <div class="col-md-8 col-sm-10 col-xs-12">
                                <input type="text" id="txtNoAktiva" name="noaktiva" class="form-control" readonly="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" align="right" for="txtNilaiPenyusutan">No. Register </label>
                            <div class="col-md-5 col-sm-10 col-xs-12">
                                <input type="text" id="txtNoRegister" name="noregister" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" for="txtNama">Nama Aktiva </label>
                            <div class="col-md-8 col-sm-10 col-xs-12">
                                <input type="text" id="txtNama" name="namaaktiva" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" for="txtKelompok">Kelompok </label>
                            <div class="col-md-8 col-sm-10 col-xs-12">
                                <select id="txtKelompok" name="aktivajenisitemid" class="form-control">
                                    <option value="0"> </option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" for="txtGolongan">Golongan </label>
                            <div class="col-md-8 col-sm-10 col-xs-12">
                                <select id="txtGolongan" name="golongan" class="form-control">
                                    <option value="0"> </option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" for="txtTglPerolehan">Tgl Perolehan </label>
                            <div class="col-md-5 col-sm-10 col-xs-12">
                                <!--<input type="text" id="txtTglPerolehan" class="form-control"> -->
                                <input type="text" id="txtTglPerolehan" name="tglperolehan" class="tgl form-control" placeholder="Tanggal Perolehan" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" align="right" for="txtNilaiPerolehan">Nilai Perolehan </label>
                            <div class="col-md-5 col-sm-10 col-xs-12">
                                <input type="text" id="txtNilaiPerolehan" name="nomperolehan" class="form-control" onkeyup="hitungNilai();" onkeyup="hitungNilai();" value="0" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" align="right" for="txtNilaiBuku">Nilai Buku </label>
                            <div class="col-md-5 col-sm-10 col-xs-12">
                                <input type="text" id="txtNilaiBuku" name="nombuku" class="form-control" readonly ="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" align="right" for="txtUmurPenyusutan">Umur Penyusutan </label>
                            <div class="col-md-5 col-sm-10 col-xs-12">
                                <input type="number" id="txtUmurPenyusutan" name="usiapenyusutan" class="form-control" onkeyup="hitungNilai();" onkeyup="hitungNilai();" value="0" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" align="right" for="txtNilaiPenyusutan">Nilai Penyusutan </label>
                            <div class="col-md-5 col-sm-10 col-xs-12">
                                <input type="text" id="txtNilaiPenyusutan" name="nompenyusutan" class="form-control" readonly="">
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button  type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Modal Depresiasi --> 
<div class="modal fade" id="modalDepresiasi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">DEPRESIASI FIXED ASSET</h4>
            </div>
            
            <form id="frmDepresiasi" class="form-horizontal" method="post">
                <div class="modal-body">
                    <label class="control-label" id="periode"> </label>
                    <div class="row">
                    <table id="tblDepresiasi" class="table table-bordered table-striped display mbuhsakarepku" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID Aktiva</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Penyusutan ke</th>
                                    <th>Nilai Penyusutan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button  type="submit" id="btnsusut" class="btn btn-primary">Simpan</button>
                    
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- MODAL JUAL  -->
<div class="modal fade" id="modalJual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
    <div class="modal-dialog modal-xs" role="document">
        <form id="frmJual" class="form-horizontal" method="post">
        {!! csrf_field() !!}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">JUAL AKTIVA</h4>
            </div>

                
                <div class="modal-body">

                    <div class="row">
                        <div class="form-group">
                            <input type="hidden" id="txtAktivaID" name="aktivaid" class="form-control" >    
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" for="txtTglJual">Tgl Keluar </label>
                            <div class="col-md-8 col-sm-10 col-xs-12">
                                <!--<input type="text" id="txtTglPerolehan" class="form-control"> -->
                                <input type="text" id="txtTglJual" name="tglkeluar" class="tgl form-control" placeholder="Tanggal Keluar" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" for="txtNomJual">Nilai Jual </label>
                            <div class="col-md-5 col-sm-10 col-xs-12">
                                <input type="numeric" id="txtNomJual" name="nomjual" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" for="txtKeteranganKeluar">Keterangan Keluar </label>
                            <div class="col-md-8 col-sm-10 col-xs-12">
                                <input type="text" id="txtKeteranganKeluar" name="keterangankeluar" class="form-control">
                                <div id="txtNote"> </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button  type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
 
 <script type="text/javascript">
    var table, table2, tblDepresiasi, fokus, table_index, table2_index;
    //var search = '';
    var context_menu_number_state = 'hide';
    var context_menu_text_state   = 'hide';
    var last_index    = '';
    var sync          = '';
    var filter_number = ['<=','<','=','>','>='];
    var filter_text   = ['=','!='];
    var tipe          = ['Find','Filter'];
    var column_index  = 0;
    var custom_search = [
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
        { text : '', filter : '=' },
    ];
    var tipe_edit = null;
    var table_index = 0;

    //txtNilaiPerolehan
 
    $(document).ready(function(){
        $(".tgl").inputmask();

        $('#txtNilaiPerolehan').inputmask("numeric", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            prefix: '', //No Space, this will truncate the first character
            rightAlign: true,
            oncleared: function () { self.Value('0'); }
        });

        $("#txtNomJual").inputmask("numeric", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            prefix: '', //No Space, this will truncate the first character
            rightAlign: true,
            oncleared: function () { self.Value('0'); }
        });

        table = $('#table1').DataTable({
            dom 		: 'lrtp',//lrtip -> lrtp
            serverSide	: true,
            stateSave	: false,
            deferRender : true,
            bJQueryUI : true,
            bDestroy : true,
            aaSorting : [[6, 'desc']],
            select: {style:'single'},
            ajax 		: {
                url			: '{{ route("aktiva.data") }}',
                data 		: function ( d ) {
                    d.custom_search = custom_search;
                    d.search = $("#txtSearch").val();
                    d.tipe_edit     = tipe_edit;
                },
            },
            scrollY : 130,
            scrollX : true,
            scroller: {
                loadingIndicator: true
            },
            //order       : [[ 5, 'desc' ]],
            
            stateLoadParams: function (settings, data) {
                for (var i = 0; i < data.columns.length; i++) {
                    data.columns[i].search.search = "";
                }
            },
            rowCallback: function(row, data, index) {
                // console.log(data);
            },
            columns        : [
                {
                    "data" : "hapus",
                    "orderable" : false,
                    render : function(data, type, row) {
                        return "<div class='btn btn-xs btn-warning no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Edit' onclick='edit(this,"+ row.id +","+row.status+")' data-message='"+data+"' data-tipe='header'><i class='fa fa-pencil'></i></div>" + 
                        "<div class='btn btn-xs btn-danger no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Hapus' onclick='hapus(this,"+ row.id +")' data-message='"+data+"'  data-tipe='header'><i class='fa fa-trash'></i></div>" + 
                            "<div class='btn btn-xs btn-success no-margin-action' data-toggle='tooltip' data-placement='bottom' title='Jual' onclick='Jual(this,"+ row.id +","+row.qtypenyusutan+","+row.status+")' data-message='"+data+"' data-tipe='header'><i class='fa fa-shopping-basket'></i></div>"  
                            
                           
                        ;
                    }
                },
                { "data" : "noaktiva", "className" : "textfilter" },
                { "data" : "noregister", "className" : "textfilter" },
                { "data" : "namaaktiva", "className" : "textfilter" },
                { "data" : "keterangan", "className" : "textfilter"},
                { "data" : "golongan", "className" : "textfilter" },
                { "data" : "tglperolehan", "className" : "textfilter" },
                { "data" : "usiapenyusutan", "className" : "text-right" },
                { "data" : "nomperolehan", "className" : "text-right" },
                { "data" : "qtypenyusutan", "className" : "text-right" },
                { "data" : "nombuku", "className" : "text-right" },
                { "data" : "tglkeluar", "className" : "textfilter" },
                { "data" : "nomjual", "className" : "text-right" },
                { "data" : "keterangankeluar", "className" : "textfilter" },
            ]
        });

        table2 = $('#table2').DataTable({
            dom     : 'lrtp',
            select: {style:'single'},
            scrollY : "33vh",
            scrollX : true,
            scroller: {
                loadingIndicator: true
            },
            order 		: [[ 1, 'asc' ]],
            rowCallback: function(row, data, index) {
                // console.log(data);
            },
            columns     : [
                {
                    "data" : "tanggal"
                },
                {
                    "data" : "nobukti"
                },
                {
                    "data" : "penyusutanke", "className" : "text-right"
                },
                {
                    "data" : "nominal", "className" : "text-right"
                },
                {
                    "data" : "uraian"
                },
            ]
        });

        tblDepresiasi = $('#modalDepresiasi table').DataTable({
			dom     : 'lrt',
            keys    : true,
            // select  : true,
            scrollY : "50vh",
            scrollX : true,
            scroller: { loadingIndicator: true },

			order   : [[ 1, 'asc' ]],
			columns     : [
                {
                    "data" : "noaktiva", "className" : "textfilter" 
                },
                {
                    "data" : "namaaktiva", "className" : "textfilter" 
                },
                {
                    "data" : "keterangan", "className" : "textfilter" 
                },
                {
                    "data" : "penyusutanke", "className" : "text-right"
                },
                {
                    "data" : "nompenyusutan", "className" : "text-right"
                },
            ],
		}).on('key-focus', function (e, dt, cell) {
            dt.rows().deselect();
            dt.row( cell.index().row ).select();
        }).on('select', function ( e, dt, type, indexes ){
            tblDepresiasi = indexes;
        });


        table.on('select', function ( e, dt, type, indexes ){
            var rowData = table.rows(indexes).data().toArray();
            var id = rowData[0].id
            //console.log(rowData);
            table_index = indexes;
            table2.clear();
            $.ajax({
                type : 'GET',
                url : '{{ route("aktiva.detail") }}',
                data : {id : id},
                success : function(data){
                    var data_parse = JSON.parse(data);
                    table2.clear();
                    table2.rows.add(data_parse);
                    table2.draw();
                    setTimeout(function(){
                        table2.columns.adjust();
                        if(data_parse.length > 0) {
                            table2.cell(':eq(0)').focus();
                        }
                    }, 100);
                },
                error: function(data){
                    table2.clear();
                    table2.columns.adjust();
                }
            });
        });

        table.on('deselect', function ( e, dt, type, indexes ) {
            table2.clear().draw();
        });
        
    });
    //ready function

    $('#btnrefresh').click(function(){
        refresh_data();
        tipe_edit = 'refresh';
    })

    $('#txtSearch').on('keypress', function(e){
        if (e.keyCode == '13') {
            refresh_data();
        }
    });

    $('#btntambah').click(function(){
        tipe_edit = 'entry';
        $.ajax({
            type : 'GET',
            url : '{{ route("aktiva.edit") }}',
            data : {id : 0},
            success : function (data){
                $('#frmAktiva')[0].reset();
                enableInput();
                $('#txtKelompok').children('option:not(:first)').remove();
                $('#txtGolongan').children('option:not(:first)').remove();
                for (var i =0; i < data.kelompok.length; i++){
                    $("#txtKelompok").append("<option value='"+ data.kelompok[i].id +"'>"+ data.kelompok[i].keterangan +"</option>");
                }
                //$("#txtNoAktiva").val(data.noaktiva);
                $('#modalEntry').modal('show');
                $('#modalEntry #txtNama').focus();
            }
        });
    });

    $('#txtKelompok').change(function(){
       
        //console.log(id);
        var isDisKelompok = $('#txtKelompok').attr('disabled');
        var isDisGolongan = $('#txtGolongan').attr('disabled');

        if (isDisKelompok == true || isDisGolongan == true){
            return;
        }

        $('#txtGolongan').children('option:not(:first)').remove();

        var idKelompok = $('#txtKelompok').val();
        if (idKelompok > 0){
            $.ajax({
                type : 'GET',
                data : {'id':idKelompok},
                url : '{{ route("aktiva.usiapenyusutan") }}',
                dataType : 'json',
                success:function(data){
                    //console.log(data.data[0].usiapenyusutan);
                    for (var i=0; i <data.golongan.length; i++){
                        $("#txtGolongan").append("<option value='"+ data.golongan[i].golongan +"'>"+ data.golongan[i].keterangan +"</option>");
                    }
                    $('#txtUmurPenyusutan').val(data.data[0].usiapenyusutan);
                    hitungNilai();
                }
            });
        }else{
            $('#txtUmurPenyusutan').val('0');
            hitungNilai();
        }
    });

    $('#btnpenyusutan').click(function(){
        tipe_edit = 'penyusutan';
        $.ajax({
            type : 'GET',
            url : '{{ route("aktiva.depresiasi") }}',
            success : function(data){
                var data_parse = JSON.parse(data.data);
                var periode = data.periode;

                document.getElementById("periode").innerHTML = periode;
                console.log(data_parse);

                $("#tblDepresiasi").DataTable().clear();
                tblDepresiasi.rows.add(data_parse);
                $("#tblDepresiasi").DataTable().draw();

                setTimeout(function(){
                    tblDepresiasi.columns.adjust();
                    if(data_parse.length > 0) {
                        tblDepresiasi.cell(':eq(0)').focus();
                    }
                }, 100);

                $('#modalDepresiasi').modal('show');
                $('#modalDepresiasi').on('show.bs.modal', function(){
                    tblDepresiasi.columns.adjust().draw();
                });
                //$('#modalDepresiasi').focus();
            },
            error: function(data){
                tblDepresiasi.clear();
                tblDepresiasi.columns.adjust();
            }
        });
        
    });

    $('#frmAktiva').on('submit', function(e){
        e.preventDefault();

        var valPerolehan = $("#txtNilaiPerolehan").val().toString().replace(/\./g,"");
        var valBuku = $("#txtNilaiBuku").val().toString().replace(/\./g,"");
        var valPenyusutan = $("#txtNilaiPenyusutan").val().toString().replace(/\./g,"");
        console.log(valPenyusutan);
        //return false;

        if ($("#txtNama").val() == null || $("#txtNama").val() == ""){
            swal("Ups!", "Nama aktiva belum diisi.","error");
            return false;
        }

        if ($("#txtTglPerolehan").val() == null || $("#txtTglPerolehan").val() == ""){
            swal("Ups!", "Tanggal Perolehan belum diisi.","error");
            return false;
        }

        if ($("#txtKelompok").val() == null || $("#txtKelompok").val() < 1){
            swal("Ups!","Kelompok aktiva harus diisi.","error");
            return false;
        }

        if ($("#txtGolongan").val() == null || $("#txtGolongan").val() < 1){
            swal("Ups!","Golongan aktiva harus diisi.","error");
            return false;
        }

        var valNilaiPerolehan = (isNaN(valPerolehan) ? 0 : valPerolehan);
        if (parseFloat(valNilaiPerolehan) < 1){
            swal("Ups!","Isi nilai perolehan dengan benar","error");
            return false;
        }
        
        var valUsiaPenyusustan = (isNaN($('#txtUmurPenyusutan').val()) ? 0 : $('#txtUmurPenyusutan').val());
        if (parseInt(valUsiaPenyusustan) < 1){
            swal("Ups!","Isi usia penyusutan dengan benar","error");
            return false;
        }

        
        var varProp = $('#txtTglPerolehan').prop('disabled');
        
        if (varProp == true){
            $.ajax({
                type : 'GET',
                url : '{{ route("aktiva.closingdate") }}',
                dataType : 'json',
                success:function(data){
                    var tgl = $("#txtTglPerolehan").val().split("-");
                    var tglclosing =  new Date(data.tglclosing);
                    var tglinput = new Date(tgl[2], tgl[1]-1, tgl[0]);
                    //swal("Ups!", tglinput, "error");
                    
                    if(tglinput.getTime() <= tglclosing.getTime()) {
                        swal("Ups!", "Tanggal perolehan sudah closing. Hubungi Manager Anda!", "error");
                        $("#txtTglPerolehan").val('').focus();
                        return false;
                    }else{
                        $('#txtKelompok').removeAttr('disabled');
                        $('#txtGolongan').removeAttr('disabled');
                        $.ajax({
                            type : 'POST',
                            headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                            url  : '{{ route("aktiva.simpan") }}',
                            data : {
                                id                      : $("#txtId").val(),
                                noaktiva                : $("#txtNoAktiva").val(),
                                namaaktiva              : $("#txtNama").val(),
                                aktivajenisitemid       : $("#txtKelompok").val(),
                                golongan                : $("#txtGolongan").val(),
                                tglperolehan            : $("#txtTglPerolehan").val(),
                                nomperolehan            : valPerolehan,
                                nombuku                 : valBuku,
                                usiapenyusutan          : $("#txtUmurPenyusutan").val(),
                                nompenyusutan           : valPenyusutan,
                                noregister              : $("#txtNoRegister").val(),
                            },
                            
                            dataType : "json",
                            success : function(data){
                                
                                if (data.success){
                                    $('#frmAktiva')[0].reset();
                                    $('#modalEntry').modal('hide');
                                    table.ajax.reload(function(){                                       
                                        table.row(function( idx, dt, node){
                                            return dt.id == data.lastid;
                                        }).select().scrollTo();

                                    }, true);
                                    swal("Success",data.message,"success");
                                }
                                
                            }
                        });
                    }
                    
                }
            });
        }else{
            $('#txtKelompok').removeAttr('disabled');
            $('#txtGolongan').removeAttr('disabled');
            $.ajax({
                type : 'POST',
                headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                url  : '{{ route("aktiva.simpan") }}',
                data : {
                    id                      : $("#txtId").val(),
                    noaktiva                : $("#txtNoAktiva").val(),
                    namaaktiva              : $("#txtNama").val(),
                    aktivajenisitemid       : $("#txtKelompok").val(),
                    golongan                : $("#txtGolongan").val(),
                    tglperolehan            : $("#txtTglPerolehan").val(),
                    nomperolehan            : valPerolehan,
                    nombuku                 : valBuku,
                    usiapenyusutan          : $("#txtUmurPenyusutan").val(),
                    nompenyusutan           : valPenyusutan,
                    noregister              : $("#txtNoRegister").val(),
                },
                
                dataType : "json",
                success : function(data){
                    console.log(data.lastid);
                    if (data.success){
                        $('#frmAktiva')[0].reset();
                        $('#modalEntry').modal('hide');
                        table.ajax.reload(function(){                                       
                            table.row(function( idx, dt, node){
                                return dt.id == data.lastid;
                            }).select().scrollTo();

                        }, true);

                        swal("Success",data.message,"success");
                    }
                    
                }
            });
        }
    });

    $('#frmDepresiasi').on('submit', function(e){
        e.preventDefault();
        console.log("OK");
        $.ajax({
            type : 'POST',
            url : '{{ route("aktiva.depresiasi.simpan") }}',
            dataType : 'json',
            headers     : {'X-CSRF-TOKEN': $('[name="_token"]').val()},
            success:function(data){
                //console.log(data.data[0].usiapenyusutan);
                
                if (data.success == false){
                    swal("Ups!", data.message, "error");
                }else{
                    $('#frmAktiva')[0].reset();
                        swal("Success!", data.message, "success");
                        refresh_data();
                        tipe_edit = null;
                }
                $('#modalDepresiasi').modal('hide');
            }
        });

    });

    function refresh_data(){
        table.ajax.reload(function(){
            table.row(table_index).deselect();
            table.row(0).select();
            table.row(0).scrollTo(false);
        }, true);
        table2.draw();
    }

    function disableInput(){
        $("#txtKelompok").attr('disabled', true);
        $("#txtGolongan").attr('disabled', true);
        $("#txtTglPerolehan").prop('readonly', true);
        $("#txtNilaiPerolehan").prop('readonly', true);
        $("#txtUmurPenyusutan").prop('readonly', true);
        $("#txtNoRegister").prop("readonly", true);
    }

    function enableInput(){
        $("#txtKelompok").attr('disabled', false);
        $("#txtGolongan").attr('disabled', false);
        $("#txtTglPerolehan").prop('readonly', false);
        $("#txtNilaiPerolehan").prop('readonly', false);
        $("#txtUmurPenyusutan").prop('readonly', false);
        $("#txtNoRegister").prop("readonly", false);
    }

    $('#frmJual').on('submit', function(e){
        e.preventDefault();

        if (!$('#modalJual').is(':visible')){
            return;
        }
        
        var intNilaiJual = $("#txtNomJual").val().toString().replace(/\./g,"");
        console.log(intNilaiJual);
        var errMessage = '';

        if (intNilaiJual < 1){
            errMessage = "Isikan nominal nilai jual.";
        }

        if (errMessage == ''){
            //var data = $(this).serialize();
            var url = '{{ route("aktiva.jual") }}';
            var post = 'POST';
            $.ajax({
                type : post,
                data :{aktivaid : $("#txtAktivaID").val(), nomjual : intNilaiJual, tglkeluar : $("#txtTglJual").val(), keterangankeluar : $("#txtKeteranganKeluar").val() },
                url : url,
                dataType : 'json',
                headers     : {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                success:function(data)
                {
                    
                    if (data.success == false){
                        swal("Ups!", data.message, "error");
                       
                    }else{
                        $('#frmJual')[0].reset();
                        $('#modalJual').modal('hide');
                        swal("Success!", data.message, "success");
                        refresh_data();
                        tipe_edit = null;
                    }
                },
                error: function(data){
                    console.log(data.message);
                }
            });
        }else{
            swal("Ups!",errMessage,"error");
        }

    });

    function edit(e, id, status){
        tipe_edit = 'ubah';
        $.ajax({
            type : 'GET',
            url : '{{ route("aktiva.edit") }}',
            data : {id : id},
            success : function (data){
                
                $('#frmAktiva')[0].reset();
                $('#txtKelompok').children('option:not(:first)').remove();
                $('#txtGolongan').children('option:not(:first)').remove();

                var qty = parseInt(data.data[0].qtypenyusutan);
                if (status == 0 || qty > 0){
                    disableInput();
                }else{
                    enableInput();
                }

                $('#txtId').val(data.data[0].id);
                $('#txtNama').val(data.data[0].namaaktiva);
                $('#txtTglPerolehan').val(data.data[0].tglperolehan);
                
                for (var i =0; i < data.kelompok.length; i++){
                    $('#txtKelompok').append("<option value='"+ data.kelompok[i].id +"'>"+ data.kelompok[i].keterangan +"</option>");
                }

                for (var i=0; i <data.golongan.length; i++){
                    $("#txtGolongan").append("<option value='"+ data.golongan[i].golongan +"'>"+ data.golongan[i].keterangan +"</option>");
                }

                $("#txtKelompok").val(data.data[0].aktivajenisitemid);
                $("#txtGolongan").val(data.data[0].golongan);
                $("#txtNilaiPerolehan").val(data.data[0].nomperolehan);
                $("#txtNilaiBuku").val(currencyFormat(data.data[0].nombuku));
                $("#txtUmurPenyusutan").val(data.data[0].usiapenyusutan);
                $("#txtNilaiPenyusutan").val(currencyFormat(data.data[0].nompenyusutan));
                $("#txtNoRegister").val(data.data[0].noregister);
                
                $('#modalEntry').modal('show');
                $('#modalEntry #txtNama').focus();
                hitungNilai();
            }
        });
    }

    function hapus (e, id){
        console.log(id);
        var url = '{{ route("aktiva.hapus") }}';
        var post = 'GET';
        $.ajax({
            type : post,
            data :{id:id},
            url : url,
            dataType : 'json',
            success:function(data)
            {
                if (data.success == true) {
                    swal("Success!", data.message, "success");
                    refresh_data();
                    tipe_edit = null;
                }else{
                    swal("Ups!", data.message, "error");
                }
            }
        });
    }

    function hitungNilai(){
        //if($('#modalEntry').is(':visible')){
            var isDisKelompok = $('#txtKelompok').attr('disabled');
            var isDisGolongan = $('#txtGolongan').attr('disabled');

            if (isDisKelompok || isDisGolongan){
                return;
            }

            var intPerolehan = ($("#txtNilaiPerolehan").val()) ? $("#txtNilaiPerolehan").val().toString().replace(/\./g,""): 0;
            console.log(""+intPerolehan)
            var valNilaiPerolehan = Number(intPerolehan);
            // console.log(""+(valPerolehan+2))
                //.replace(".", ",") // replace decimal point character with ,
                //.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."); //use . as a separator
            //$("#txtNilaiPerolehan").val(valPerolehan);
            

            // var valNilaiPerolehan = (isNaN($('#txtNilaiPerolehan').val()) ? 0 : $('#txtNilaiPerolehan').val());
            var valUmurSusut = (isNaN($('#txtUmurPenyusutan').val()) ? 0 : $('#txtUmurPenyusutan').val());
            var valNilaiSusut = parseFloat((valNilaiPerolehan / valUmurSusut)).toFixed(2);

            if ($.isNumeric(valNilaiSusut) == false){
                valNilaiSusut = 0;
            }
            $('#txtNilaiBuku').val(currencyFormat(valNilaiPerolehan));
            $('#txtNilaiPenyusutan').val(currencyFormat(valNilaiSusut));
        //}
    }

    function Jual(e, id, qty, status){
        $('#frmJual')[0].reset();
        $('#txtNote').html('');
        $('#txtAktivaID').val(id);
        if (status == 0) {
            swal('Ups!', "Aktiva tidak bisa dijual karena sudah keluar.",'error');
        }else{
            tipe_edit = 'jual';
            if (qty > 0){
                $('#txtNote').append('<br/><div class="alert alert-info"><strong>INFORMASI : </strong> Aktiva sudah memiliki penyusutan. Jika dilanjutkan, maka penyusutan pada periode ini akan dihapus.</div>');
            }
            $('#modalJual').modal('show');
            $('#modalJual #txtTglJual').focus();
        }
   }

   function currencyFormat(num){
    return num.toString()
     .replace(".", ",")
     .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    }

 </script>

@endpush