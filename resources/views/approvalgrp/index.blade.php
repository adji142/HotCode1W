@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Approval</li>
    <li class="breadcrumb-item"><a href="{{ route('approvalgrp.index') }}">Approval Group</a></li>
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
                    <h2>Daftar Approval Group</h2>
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
                                <th width="1%">A</th>
                                <th>Nama Modul</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a style="padding:0 5px;" class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i> Nama Modul</a> | 
                            <a style="padding:0 5px;" class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i> Keterangan</a>
                    </div>
                    {{-- <hr> --}}
                    <table id="table2" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="1%">A</th>
                                <th>ID Role</th>
                                <th>Nama Role</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :&nbsp;&nbsp;</span>
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="1"><i id="eye-detail1" class="fa fa-eye"></i> ID Role</a> | 
                            <a style="padding:0 5px;" class="toggle-vis-2" data-column="2"><i id="eye-detail2" class="fa fa-eye"></i> Nama Role</a>
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
                    <h4 class="modal-title" id="myModalLabel">Data Approval Module</h4>
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

    @can('approvalgrp.roles')
    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Tambah Roles</h4>
                </div>
                <form id="formDetail" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Pilih Role</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="select2_single form-control" tabindex="-1" name="tambah_role" required>
                                    <option></option>
                                    @foreach($roleObj as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="approvalmoduleid" name="approvalmoduleid" value="" readonly tabindex="-1">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

@endsection

@push('scripts')
<script type="text/javascript">
    var context_menu_number_state = 'hide';
    var context_menu_text_state   = 'hide';
    var custom_search = [
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
                url : '{{ route("approvalgrp.data") }}',
                data: function ( d ) {
                    d.custom_search = custom_search;
                    d.tipe_edit     = tipe_edit;
                },
            },
            order   : [[ 1, 'asc' ]],
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
                    "data"     : "modulename",
                    "className": "menufilter textfilter"
                },
                {
                    "data"     : "keterangan",
                    "className": "menufilter textfilter"
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
            order  : [[ 1, 'asc' ]],
            columns: [
                { "data": "action", "orderable": false },
                { "data": "id" },
                { "data": "name" },
            ],
        });

        table.on('select', function ( e, dt, type, indexes ){
            table_index = indexes;
            fokus       = 'header';
            var rowData = table.rows( indexes ).data().toArray();
            // console.log(rowData[0].id);
            $.ajax({
                headers : { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") },
                type    : 'POST',
                data    : {id: rowData[0].id},
                dataType: "json",
                url     : '{{route("approvalgrp.data.detail")}}',
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
                url: '{{route("approvalgrp.header")}}',
                data: {id: data.id, _token:"{{ csrf_token() }}"},
                dataType: "json",
                success: function(data){
                    tabledblclick.clear();
                    tabledblclick.rows.add([
                        {0:'1.', 1:'Nama Modul', 2:data.modulename},
                        {0:'2.', 1:'Email Subject', 2:data.emailsubject},
                        {0:'3.', 1:'Email Body', 2:data.emailbody},
                        {0:'4.', 1:'Keterangan', 2:data.keterangan},
                        {0:'5.', 1:'Created By', 2:data.createdby},
                        {0:'6.', 1:'Created On', 2:data.createdon},
                        {0:'7.', 1:'Last Updated By', 2:data.lastupdatedby},
                        {0:'8.', 1:'Last Updated On', 2:data.lastupdatedon},
                    ]);
                    tabledblclick.draw();
                    $('#modaldblclick myModalLabel').html('Data Approval Module');
                    $('#modaldblclick').modal('show');
                },
            });
        });

        $('#modalDetail').on('shown.bs.modal', function () {
            $('#barang').focus();
        });
        $('#modalSubCabang').on('shown.bs.modal', function () {
            $('#txtQuerySubCabang').focus();
        });

        $('#formDetail').on('submit', function(e){
            e.preventDefault();
            @cannot('approvalgrp.roles')
            swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
            @else
            tipe_edit = true;

            $.ajax({
                type    : 'POST',
                url     : '{{route('approvalgrp.roles')}}',
                data    : $('#formDetail').serialize(),
                dataType: "json",
                success : function(data){
                    swal({
                        title: "Sukses!",
                        text : "Tambah Roles Berhasil.",
                        type : "success",
                        html : true
                    },function(){
                        table.ajax.reload(null, true);
                        tipe_edit = null;
                        setTimeout(function(){
                            table.row(0).deselect();
                            table.row(0).select();
                        },1000);
                    });

                    return false;
                },
                error: function(data){
                    // console.log(data);
                }
            });

            return false;
            @endcannot
        });

    });

    function tambahrole(e) {
        @cannot('approvalgrp.roles')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        var data = table.row($(e).parents('tr')).data();
        $('#modalDetail #approvalmoduleid').val(data.id);
        $('#modalDetail').modal('show');
        @endcannot
    }

    function hapus(e) {
        @cannot('approvalgrp.roles')
        swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
        @else
        var data = table2.row($(e).parents('tr')).data();
        swal({
            title: "Apakah Anda yakin?",
            text: "Data akan terhapus!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ya, hapus data!",
            confirmButtonColor: "#ec6c62",
            closeOnConfirm: false
        },
        function(){
            $.ajax({
                type    : 'POST',
                url     : '{{route('approvalgrp.roles')}}',
                data    : {approvalmoduleid:data.approvalmoduleid, hapus_role:data.id, _token:"{{csrf_token()}}" },
                dataType: "json",
                success : function(data){
                    swal({
                        title: "Sukses!",
                        text : "Hapus Roles Berhasil.",
                        type : "success",
                        html : true
                    },function(){
                        table.ajax.reload(null, true);
                        tipe_edit = null;
                        setTimeout(function(){
                            table.row(0).deselect();
                            table.row(0).select();
                        },1000);
                    });

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
