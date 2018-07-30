@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Master</li>
    <li class="breadcrumb-item"><a href="{{ route('targetsales.index') }}">Target Sales</a></li>
@endsection

@section('main_container')
    <div class="mainmain">
        <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Daftar Harga Jual</h2>
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
                                    <th>Kelompok Barang</th>
                                    <th>Satuan</th>
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
                                <a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Kelompok Barang</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Satuan</a>
                            </p>
                        </div>
                    </div>
                    {{-- <hr> --}}
                    <div class="row-fluid">
                        <h2>History BMK 1</h2>
                        <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tgl Aktif</th>
                                    <th>Qty Min B</th>
                                    <th>Harga B</th>
                                    <th>Qty Min M</th>
                                    <th>Harga M</th>
                                    <th>Qty Min K</th>
                                    <th>Harga K</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                            <p>
                                <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                                <a class="toggle-vis-2" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Tgl Aktif</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Qty Min B</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Harga B</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Qty Min M</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis-2" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> Harga M</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis-2" data-column="5"><i id="eye-detail5" class="fa fa-eye"></i> Qty Min K</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis-2" data-column="6"><i id="eye-detail6" class="fa fa-eye"></i> Harga K</a>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row-fluid">
                        <h2>History BMK 2</h2>
                        <table id="table3" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tgl Aktif</th>
                                    <th>Qty Min B</th>
                                    <th>Harga B</th>
                                    <th>Qty Min M</th>
                                    <th>Harga M</th>
                                    <th>Qty Min K</th>
                                    <th>Harga K</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                            <p>
                                <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                                <a class="toggle-vis-3" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Tgl Aktif</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis-3" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Qty Min B</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis-3" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Harga B</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis-3" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Qty Min M</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis-3" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> Harga M</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis-3" data-column="5"><i id="eye-detail5" class="fa fa-eye"></i> Qty Min K</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a class="toggle-vis-3" data-column="6"><i id="eye-detail6" class="fa fa-eye"></i> Harga K</a>
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
    for (var i = 0; i < 3; i++) {
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
    var stockid;

    function tampilkandata(){
        table.ajax.reload(null, true);
    }

    $(document).ready(function() {
        table = $('#table1').DataTable({
            dom        : 'lrtp',//lrtip -> lrtp
            serverSide : true,
            stateSave  : true,
            deferRender: true,
            select: {style:'single'},
            keys: {keys: [38,40]},
            ajax       : {
                url         : '{{ route("hargajual.data") }}',
                data        : function ( d ) {
                    d.custom_search  = custom_search;
                    d.namabarang     = $('#namabarang').val();
                    d.statusaktif    = $('#statusaktif').val();
                    d.kelompokbarang = $('#kelompokbarang').val();
                },
            },
            scrollY : 130,
            scrollX : true,
            scroller    : {
                loadingIndicator: true
            },
            stateLoadParams: function (settings, data) {
                for (var i = 0; i < data.columns.length; i++) {
                    data.columns[i].search.search = "";
                }
            },
            columns     : [
                {
                    "data" : "kodebarang",
                    "className" : 'menufilter textfilter'
                },
                {
                    "data" : "namabarang",
                    "className" : 'menufilter textfilter'
                },
                {
                    "data" : "kelompokbarang",
                    "className" : 'menufilter textfilter'
                },
                {
                    "data" : "satuan",
                    "className" : 'menufilter textfilter'
                },
            ]
        });

        table2 = $('#table2').DataTable({
            dom        : 'lrtp',
            serverSide : true,
            stateSave  : true,
            deferRender: true,
            select: {style:'single'},
            keys: {keys: [38,40]},
            ajax       : {
                url : '{{ route("hargajual.detail") }}',
                data: function ( d ) {
                    d.id      = stockid;
                    d.tipebmk = 1;
                },
            },
            scrollY : "33vh",
            scrollX : true,
            scroller: {
                loadingIndicator: true
            },
            order: [[ 0, 'asc' ]],
                rowCallback: function(row, data, index) {
                // console.log(data);
            },
            columnDefs: [{type: 'sortdate',targets: [0]}],
            columns   : [
                { "data":"tglaktif" },
                { "data":"qtyminb1", "className": "text-right" },
                { "data":"hrgb1", "className": "text-right" },
                { "data":"qtyminm1", "className": "text-right" },
                { "data":"hrgm1", "className": "text-right" },
                { "data":"qtymink1", "className": "text-right" },
                { "data":"hrgk1", "className": "text-right" },
            ],
        });

        table3 = $('#table3').DataTable({
            dom        : 'lrtp',
            serverSide : true,
            stateSave  : true,
            deferRender: true,
            select: {style:'single'},
            keys: {keys: [38,40]},
            ajax       : {
                url : '{{ route("hargajual.detail") }}',
                data: function ( d ) {
                    d.id      = stockid;
                    d.tipebmk = 2;
                },
            },
            scrollY : "33vh",
            scrollX : true,
            scroller: {
                loadingIndicator: true
            },
            order: [[ 0, 'asc' ]],
                rowCallback: function(row, data, index) {
                // console.log(data);
            },
            columnDefs: [{type: 'sortdate',targets: [0]}],
            columns   : [
                { "data":"tglaktif" },
                { "data":"qtyminb2", "className": "text-right" },
                { "data":"hrgb2", "className": "text-right" },
                { "data":"qtyminm2", "className": "text-right" },
                { "data":"hrgm2", "className": "text-right" },
                { "data":"qtymink2", "className": "text-right" },
                { "data":"hrgk2", "className": "text-right" },
            ],
        });

        table.on('select', function ( e, dt, type, indexes ){
            var rowData = table.rows( indexes ).data().toArray();
            stockid =  rowData[0].id;

            table2.clear().draw();
            table2.ajax.reload(null, true);
            table3.clear().draw();
            table3.ajax.reload(null, true);
        });
        table.on('deselect', function ( e, dt, type, indexes ) {
            table2.clear().draw();
            table3.clear().draw();
        });

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
            var column = table2.column( $(this).attr('data-column') );

            // Toggle the visibility
            column.visible( ! column.visible() );
            $('#eye'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
        } );

        $('a.toggle-vis3').on( 'click', function (e) {
            e.preventDefault();

            // Get the column API object
            var column = table3.column( $(this).attr('data-column') );

            // Toggle the visibility
            column.visible( ! column.visible() );
            $('#eye'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
        } );

        @include('master.javascript')
    });
</script>
@endpush