@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Approval</li>
    <li class="breadcrumb-item"><a href="{{ route('approvalmgmt.index') }}">Approval Management</a></li>
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
                    <h2>Daftar Approval Management</h2>
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
                                    <label for="tglmulai" style="margin-right: 10px;">Tanggal Submit</label>
                                    <input type="text" id="tglmulai" class="tgl form-control search" placeholder="tgl mulai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
                                    <label for="tglselesai">-</label>
                                    <input type="text" id="tglselesai" class="tgl form-control search" placeholder="tgl selesai" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
                                    <label for="tipestatus" style="margin-left: 10px; margin-right: 10px;">Status</label>
                                    <select class="select2_single form-control search" tabindex="-1" name="tipestatus" id="tipestatus">
                                        <option></option>
                                        @foreach($tipestatus as $v)
                                        @if(session('tipestatus') == $v)
                                        <option value="{{$v}}" selected>{{$v}}</option>
                                        @else
                                        <option value="{{$v}}">{{$v}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    <label for="tipemodul" style="margin-left: 10px; margin-right: 10px;">Modul</label>
                                    <select class="select2_single form-control search" tabindex="-1" name="tipemodul" id="tipemodul">
                                        <option></option>
                                        @foreach($tipemodul as $modul)
                                        @if(session('tipemodul') == $modul->modulename)
                                        <option value="{{$modul->modulename}}" selected>{{$modul->modulename}}</option>
                                        @else
                                        <option value="{{$modul->modulename}}">{{$modul->modulename}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th>Tgl Submit</th>
                                <th>Nama Modul</th>
                                <th>Cabang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a style="padding:0 5px;" class="toggle-vis" data-column="1"><i id="eye2" class="fa fa-eye"></i> Tgl Submit</a> |
                            <a style="padding:0 5px;" class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Nama Modul</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Cabang</a> |
                            <a style="padding:0 5px;" class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Status</a>
                    </div>
                    {{-- <hr> --}}
                    <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tgl Approval</th>
                                <th>Status</th>
                                <th>Username</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> Tgl Approval</a> | 
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Status</a> |
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="3"><i id="eye-detail3" class="fa fa-eye"></i> Username</a> | 
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="4"><i id="eye-detail4" class="fa fa-eye"></i> Keterangan</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modaldblclick" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Data Retur Penjualan</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="tbldblclick" class="table table-bordered table-striped tablepilih" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Kolom</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodydblclick">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalpengajuan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Data Pengajuan</h4>
                </div>
                <div class="modal-body" id="datapengajuan">
                </div>
                <div class="modal-footer">
                    <span id="tombolpengajuan"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script type="text/javascript">
    var context_menu_number_state = 'hide';
    var context_menu_text_state   = 'hide';
    var custom_search = [
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
    var last_index, tipe_edit;
    var table, table2, table_index, table2_index,fokus;

    // Initialize
    $(document).ajaxComplete(function() {
        $('input[type=checkbox]').iCheck({
            checkboxClass: 'icheckbox_flat-green',
        });
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
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

        table = $('#table1').DataTable({
            dom        : 'lrtp',//lrtip -> lrtp
            serverSide : true,
            stateSave  : true,
            deferRender: true,
            select: {style:'single'},
            keys: {keys: [38,40]},
            ajax       : {
                url : '{{ route("approvalmgmt.data") }}',
                data: function ( d ) {
                    d.custom_search = custom_search;
                    d.tglmulai   = $('#tglmulai').val();
                    d.tglselesai = $('#tglselesai').val();
                    d.tipestatus = $('#tipestatus').val();
                    d.tipemodul  = $('#tipemodul').val();
                    d.tipe_edit  = tipe_edit;
                },
            },
            // order   : [[ 1, 'desc' ]],
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
            columns: [
                {
                    "orderable": false,
                    "data"     : "action",
                },
                {
                    "data"     : "createdon",
                    "className": "menufilter numberfilter"
                },
                {
                    "data"     : "modulename",
                    "className": "menufilter textfilter"
                },
                {
                    "data"     : "namasubcabang",
                    "className": "menufilter textfilter"
                },
                {
                    "data"     : "status",
                    "className": "menufilter textfilter"
                },
            ]
        });
        $(".tgl").inputmask();
        $('.search').change(function(){
            table.ajax.reload(null, false);
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
            order  : [[0, 'desc' ]],
            columns: [
                { "data": "createdon" },
                { "data": "status" },
                { "data": "username" },
                { "data": "keterangan" },
            ],
        });

        table.on('select', function ( e, dt, type, indexes ){
            table_index = indexes;
            fokus       = 'header';
            var rowData = table.rows( indexes ).data().toArray();

            $.ajax({
                headers : { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") },
                type    : 'POST',
                data    : {id: rowData[0].id},
                dataType: "json",
                url     : '{{route("approvalmgmt.data.detail")}}',
                success : function(data){
                    table2.clear();
                    table2.rows.add(data.node);
                    table2.draw();
                },
                error: function(data){
                    // console.log(data);
                }
            });
        });
        table2.on('select', function ( e, dt, type, indexes ){
            fokus       = 'detail';
            table2_index = indexes;
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
                // tipe: {
                //     name: "Tipe",
                //     type: 'select',
                //     options: {1: 'Find', 2: 'Filter'},
                //     selected: 1
                // },
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
                            window.console && console.log('key: '+ e.keyCode);
                        }
                    }
                },
                // tipe: {
                //     name: "Tipe",
                //     type: 'select',
                //     options: {1: 'Find', 2: 'Filter'},
                //     selected: 1
                // },
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
                    // console.log('number');
                    // console.log(contextData);
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
                            window.console && console.log('key: '+ e.keyCode);
                        }
                    }
                },
                // tipe: {
                //     name: "Tipe",
                //     type: 'select',
                //     options: {1: 'Find', 2: 'Filter'},
                //     selected: 1
                // },
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
                    // console.log('text');
                    // console.log(contextData);
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

        $(document.body).on("keydown", function(e){
            if(e.keyCode == 13){
                if(context_menu_number_state == 'show'){
                    $(".context-menu-list.numberfilter").contextMenu("hide");
                    table.ajax.reload(null, false);
                }else if(context_menu_text_state == 'show'){
                    $(".context-menu-list.textfilter").contextMenu("hide");
                    table.ajax.reload(null, false);
                }
            }
        });

        tabledblclick = $('#tbldblclick').DataTable({ dom : 'lrtp', paging : false,});

        $('#table1 tbody').on('dblclick', 'tr', function(){
            var data = table.row(this).data();
            $.ajax({
                type: 'POST',
                url: '{{route("approvalmgmt.header")}}',
                data: {id: data.id, _token:"{{ csrf_token() }}"},
                dataType: "json",
                success: function(data){
                    tabledblclick.clear();
                    tabledblclick.rows.add([
                        {0:'1.', 1:'Tgl Submit', 2:data.createdon},
                        {0:'2.', 1:'Nama Modul', 2:data.modulename},
                        {0:'3.', 1:'Keterangan', 2:data.keterangan},
                        {0:'4.', 1:'Created By', 2:data.createdby},
                        // {0:'5.', 1:'Created On', 2:data.createdon},
                        {0:'5.', 1:'Last Updated By', 2:data.lastupdatedby},
                        {0:'6.', 1:'Last Updated On', 2:data.lastupdatedon},
                    ]);
                    tabledblclick.draw();
                    $('#modaldblclick myModalLabel').html('Data Approval Module');
                    $('#modaldblclick').modal('show');
                },
            });
        });
    });

    function detail(e,id) {
        var data = table.row($(e).parents('tr')).data();
        @cannot('approvalgrp.index')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        $.ajax({
            type: 'POST',
            url: '{{route("approvalmgmt.header.data")}}',
            data: {id: id, _token:"{{ csrf_token() }}"},
            dataType: "json",
            success: function(result){
                var html = '';
                $.each(result.datareportingheader, function (i,node) {
                    if(result.datareportingdetail[i].length > 0){
                        console.log(result.datareportingdetail[i]);
                        var fieldname = Object.keys(result.datareportingdetail[i][0]);
                    }else{
                        var fieldname = null;
                    }
                    html += '<div class="row">';
                    html += '    <div class="col-xs-12">';
                    html += '        <h2>'+node+'</h2>';
                    html += '        <div class="table-responsive">';
                    html += '            <table id="tabel'+i+'" class="table table-bordered table-striped" width="100%" cellspacing="0">';
                    html += '                <thead>';
                    html += '                    <tr>';
                    if(fieldname){
                        $.each(fieldname, function (f,field) {
                            html += '<th>'+field+'</th>';
                        });
                    }else{
                        html += '<th>Data Kosong</th>';
                    }
                    html += '                    </tr>';
                    html += '                </thead>';
                    html += '                <tbody>';
                    if(fieldname){
                        $.each(result.datareportingdetail[i], function (d,val) {
                            html += '                    <tr>';
                                $.each(fieldname, function (fx,fieldx) {
                                    html += '<td>'+val[fieldx]+'</td>';
                                });
                            html += '                    </tr>';
                        });
                    }else{
                        html += '<td>Data Kosong</td>';
                    }
                    html += '                </tbody>';
                    html += '            </table>';
                    html += '        </div>';
                    html += '    </div>';
                    html += '</div>';
                });

                var tombol = '';
                @can('approvalmgmt.simpan')
                if(data.status == "PENGAJUAN") {
                    tombol += "<div class='btn btn-success' onclick='ubahstatusacc(this,"+data.id+")'>ACC</div>";
                    tombol += "<div class='btn btn-danger' onclick='ubahstatustolak(this,"+data.id+")'>TOLAK</div>";
                }else{
                    tombol += "<button class='btn btn-success' disabled>ACC</button>";
                    tombol += "<button class='btn btn-danger' disabled>TOLAK</button>";
                }
                @endcan

                $("#datapengajuan").html(html);
                $("#tombolpengajuan").html(tombol);
                $('#modalpengajuan').modal('show');
            },
        });
        @endcannot
    }

    function ubahstatusacc(e,id) {
        fixBootstrapModal();
        @cannot('approvalmgmt.simpan')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); restoreBootstrapModal(); return false;
        @else
        tipe_edit = true;
        swal({
            title: "Apakah Anda yakin?",
            text: "Data Approval akan di-ACC !",
            type: "warning",
            input: "text",
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            confirmButtonText: "Ya, ACC data!",
            confirmButtonColor: "#ec6c62",
            closeOnConfirm: false
        },
        function(){
            $.ajax({
                type    : 'POST',
                url     : '{{route('approvalmgmt.simpan')}}',
                data    : {id:id, tipe:"acc", _token:"{{ csrf_token() }}"},
                dataType: "json",
                success : function(data){
                    swal({
                        title: "Sukses!",
                        text : "ACC Data Berhasil.",
                        type : "success",
                        html : true
                    },function(){
                        table.ajax.reload(null, true);
                        tipe_edit = null;
                        setTimeout(function(){
                            table.row(0).deselect();
                            table.row(0).select();
                        },1000);
                        $('#modalpengajuan').modal('hide');
                    });

                    restoreBootstrapModal();
                    return false;
                },
                error: function(data){
                    // console.log(data);
                }
            });
        });
        @endcannot
    }

    function ubahstatustolak(e,id) {
        fixBootstrapModal();
        @cannot('approvalmgmt.simpan')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); restoreBootstrapModal(); return false;
        @else
        tipe_edit = true;
        swal({
            title: "Apakah Anda yakin?",
            text: "Data Approval akan di-TOLAK !",
            type: "input",
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            confirmButtonText: "Ya, TOLAK data!",
            confirmButtonColor: "#ec6c62",
            closeOnConfirm: false
        },
        function(inputValue){
            if (inputValue === false) return false;

            if (inputValue === "") {
                swal.showInputError("You need to write something!");
                return false
            }

            $.ajax({
                type    : 'POST',
                url     : '{{route('approvalmgmt.simpan')}}',
                data    : {id:id, tipe:"tolak", keterangan:inputValue, _token:"{{ csrf_token() }}"},
                dataType: "json",
                success : function(data){
                    swal({
                        title: "Sukses!",
                        text : "TOLAK Data Berhasil.",
                        type : "success",
                        html : true
                    },function(){
                        table.ajax.reload(null, true);
                        tipe_edit = null;
                        setTimeout(function(){
                            table.rows(0).deselect();
                            table.rows(0).select();
                        },1000);
                        $('#modalpengajuan').modal('hide');
                    });

                    restoreBootstrapModal();
                    return false;
                },
                error: function(data){
                    // console.log(data);
                }
            });
        });
        @endcannot
    }
</script>
@endpush
