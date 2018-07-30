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
                    <h2>Daftar Target Sales</h2>
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
                                <th>NIK System Lama</th>
                                <th>NIK HRD</th>
                                <th>Nama Karyawan</th>
                                <th>Kode Sales</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a class="toggle-vis" data-column="0"><i id="eye0" class="fa fa-eye"></i> NIK System Lama</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="1"><i id="eye0" class="fa fa-eye"></i> NIK HRD</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="2"><i id="eye1" class="fa fa-eye"></i> Nama Karyawan</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="3"><i id="eye2" class="fa fa-eye"></i> Kode Sales</a>
                        </p>
                    </div>
                    {{-- <hr> --}}
                    <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Target FB 2</th>
                                <th>Target FB 4</th>
                                <th>Target FB</th>
                                <th>Target FE 2</th>
                                <th>Target FE 4</th>
                                <th>Target FE</th>
                                <th>Target All</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a class="toggle-vis-2" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Periode</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Target FB 2</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Target FB 4</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Target FB</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Target FE 2</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Target FE 4</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Target FE</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis-2" data-column="0"><i id="eye-detail0" class="fa fa-eye"></i> Target All</a>
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

    $(document).ready(function() {
        table = $('#table1').DataTable({
            dom         : 'lrtp',//lrtip -> lrtp
            serverSide  : true,
            stateSave   : true,
            deferRender : true,
            select: {style:'single'},
            keys: {keys: [38,40]},
            ajax        : {
                url         : '{{ route("targetsales.data") }}',
                data        : function ( d ) {
                    d.custom_search = custom_search;
                    d.length        = 50;
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
                { "data" : "niksystemlama", "className" : 'menufilter numberfilter' },
                { "data" : "nikhrd", "className" : 'menufilter numberfilter' },
                { "data" : "namakaryawan", "className" : 'menufilter textfilter' },
                { "data" : "kodesales", "className" : 'menufilter numberfilter' },
            ]
        });

        table2 = $('#table2').DataTable({
            dom     : 'lrtp',
            select  : true,
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
                { "data": "periode", },
                { "data": "targetfb2", "className": "text-right", },
                { "data": "targetfb4", "className": "text-right", },
                { "data": "targetfb", "className": "text-right", },
                { "data": "targetfe2", "className": "text-right", },
                { "data": "targetfe4", "className": "text-right", },
                { "data": "targetfe", "className": "text-right", },
                { "data": "targetall", "className": "text-right", },
            ],
        });

        table.on('select', function ( e, dt, type, indexes ){
            fokus       = 'header';
            table_index = indexes;
            var rowData = table.rows( indexes ).data().toArray();
            $.ajaxSetup({
                headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
            });

            $.ajax({
                type    : 'GET',
                data    : {id: rowData[0].id},
                // data    : {id: 252},
                dataType: "json",
                url     : '{{route("targetsales.detail")}}',
                success : function(result){
                    table2.clear();
                    table2.rows.add(result.data);
                    table2.draw();
                },
                error: function(data){
                    console.log(data);
                }
            });
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