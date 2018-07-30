<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{config('app.name')}}</title>

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/sas.ico/apple-icon-57x57.png')}}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/sas.ico/apple-icon-60x60.png')}}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/sas.ico/apple-icon-72x72.png')}}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/sas.ico/apple-icon-76x76.png')}}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/sas.ico/apple-icon-114x114.png')}}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/sas.ico/apple-icon-120x120.png')}}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/sas.ico/apple-icon-144x144.png')}}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/sas.ico/apple-icon-152x152.png')}}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/sas.ico/apple-icon-180x180.png')}}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('assets/sas.ico/android-icon-192x192.png')}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/sas.ico/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/sas.ico/favicon-96x96.png')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/sas.ico/favicon-16x16.png')}}">
        <link rel="manifest" href="{{ asset('assets/sas.ico/manifest.json')}}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('assets/sas.ico/ms-icon-144x144.png')}}">
        <meta name="theme-color" content="#ffffff">

        <!-- Bootstrap -->        
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        
        <!-- Font Awesome -->
        <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

        <!-- Plugin -->
        <link href="{{asset('assets/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/keyTable.dataTables.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/scroller.dataTables.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/select.dataTables.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/sweetalert.css')}}" rel="stylesheet" type="text/css">

        <!-- jQuery custom content scroller -->
        <link href="{{asset('assets/css/jquery.mCustomScrollbar.min.css')}}" rel="stylesheet" type="text/css">

        <!-- iCheck -->
        <link href="{{asset('assets/css/green.css')}}" rel="stylesheet" type="text/css">
        
        <!-- select2 -->
        <link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" type="text/css">
        
        <!-- switchery -->
        <link rel="stylesheet" href="{{asset('assets/css/switchery.min.css')}}" />

        <!-- ContextMenu -->
        <link href="{{asset('assets/css/contextMenu.theme.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/jquery.contextMenu.css')}}" rel="stylesheet" type="text/css">
        
        <!-- Custom Theme Style -->
        <link href="{{asset('assets/css/gentelella.min.css')}}" rel="stylesheet" type="text/css">

        <!-- Custom Style -->
        <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/selectize.css')}}" rel="stylesheet" type="text/css">

        @stack('stylesheets')
    <!--</head>-->
    </head>
    <!--</head>-->

    {{-- <body class="nav-md"> --}}
    <body id="nice-scroll2" class="nav-md">
        <div class="container body">
            <div class="main_container">

                @include('includes/sidebar')

                @include('includes/topbar')

                <!-- page content -->
                <div class="right_col" role="main">
                    @yield('main_container')
                </div>
                <!-- /page content -->

                <!-- footer content -->
                <footer>
                    <div class="pull-right">
                        Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
                    </div>
                    <div class="clearfix"></div>
                </footer>
                <!-- /footer content -->
            </div>
        </div>

        @if(!session('subcabang'))
        <div class="modal fade" id="modalhomeSubcabang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Lookup Pemilihan Subcabang</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <table id="tablesubcabangpilihawal" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Kode Subcabang</th>
                                            <th>Nama Cabang</th>
                                            <th>Nama Subcabang</th>
                                            <th>Init Subcabang</th>
                                            <th width="5%">Aktif</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnPilihSubcabang" class="btn btn-primary">Pilih</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- jQuery -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <!-- Bootstrap -->
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <!-- i-Check -->
        <script src="{{ asset('assets/js/icheck.min.js')}}"></script>
        <!-- Custom Theme Scripts -->
        <script src="{{ asset('assets/js/gentelella.js') }}"></script>
        <!-- Form Validation -->
        <script src="{{asset('assets/js/validator.js')}}"></script>
        <!-- switchery -->
        <script src="{{asset('assets/js/switchery.min.js')}}"></script>
        <!-- Select2 -->
        <script src="{{asset('assets/js/select2.full.min.js')}}"></script>
        <!-- Sweetalert -->
        <script src="{{asset('assets/js/sweetalert.min.js')}}"></script>
        <!-- jquery.inputmask -->
        <script src="{{asset('assets/js/jquery.inputmask.bundle.min.js')}}"></script>
        <script src="{{asset('assets/js/microplugin.js')}}"></script>
        <script src="{{asset('assets/js/selectize.js')}}"></script>
        <script src="{{asset('assets/js/sifter.js')}}"></script>
        <!-- jqurey.slimscroll -->
        {{-- <script src="{{asset('assets/js/jquery.slimscroll.min.js')}}"></script> --}}

        <!-- jQuery custom content scroller -->
        <script src="{{asset('assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>

        <!-- Datatables -->
        <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/js/dataTables.bootstrap.min.js')}}"></script>
        <script src="{{asset('assets/js/dataTables.scroller.min.js')}}"></script>
        <script src="{{asset('assets/js/dataTables.select.min.js')}}"></script>
        <script src="{{asset('assets/js/dataTables.keyTable.min.js')}}"></script>
        <script src="{{asset('assets/js/jquery.contextMenu.js')}}"></script>
        <script src="{{asset('assets/js/contextMenu.main.js')}}"></script>
        <script src="{{asset('assets/js/hotkeys.js')}}"></script>

        <div id="theLoading">
            <div class="il">
                <div class="il2">
                    <div class="il3">
                        <img src="{{asset('assets/img/spinner-200px.gif')}}" alt="Loading..." /><br />
                        <span>Please wait</span>
                    </div>
                </div>
            </div>
        </div>
        <style>
            #theLoading {
                background-color: rgba(0, 0, 0, 0.4);
                position: fixed;
                z-index: 99999;
                display: none;
                height: 100%;
                width: 100%;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
            }
            #theLoading .il {
                display: table;
                height: 100%;
                width: 100%;
            }
            #theLoading .il2 {
                display: table-cell;
                vertical-align: middle;
                text-align: center;
            }
            #theLoading .il3 {
                color: rgb(50, 50, 50);
                background-color: #fff;
                display: inline-block;
                padding: 10px 15px;
                border-radius: 5px;
            }
            #theLoading .il3 img {
                display: inline-block;
                width: 100px;
            }
            #theLoading .il3 span {
                display: inline-block;
                padding: 0 5px 15px;
            }
        </style>
        <script>
            var __theLoading,
                showLoading;
            $(document).ready(function () {
                __theLoading = $("#theLoading", document);
                showLoading = (type, cb) => {
                    if (typeof type == "undefined") type = !__theLoading.is(":visible");
                    if (type) {
                        __theLoading.fadeIn(150, () => {
                            if (typeof cb == "function") cb();
                        });
                    } else {
                        __theLoading.fadeOut(150, () => {
                            if (typeof cb == "function") cb();
                        });
                    }
                };
            });
        </script>

       {{--  <script type="text/javascript">
            $(document).ready(function() {
                $('#nice-scroll').slimScroll({
                    size: '5px',
                    height: 'auto',
                    color: '#1abb9c',
                    distance: 0,
                    railVisible: true,
                    railColor: '#2a3f54',
                    railOpacity: 1,
                    alwaysVisible: true
                });

                $('#nice-scroll2').slimScroll({
                    size: '5px',
                    height: 'auto',
                    color: '#2a3f54',
                    distance: 0,
                    railVisible: true,
                    railColor: '#1abb9c',
                    railOpacity: 1,
                    alwaysVisible: true
                });
            });
        </script> --}}
        @if(!session('subcabang'))
        <script type="text/javascript">
            $(document).ready(function() {
                var tableawal = $('#tablesubcabangpilihawal').DataTable({
                    dom        : 'lrtp',//lrtip -> lrtp
                    serverSide : true,
                    stateSave  : true,
                    deferRender: true,
                    select: true,
                    ajax  : {
                        url : '{{ route("home.data") }}',
                        data: function ( d ) {
                            d.length = 50;
                        },
                    },
                    scrollY : "33vh",
                    scrollX : true,
                    scroller: {
                        loadingIndicator: true
                    },
                    columns: [
                        { "data" : "kodesubcabang" },
                        { "data" : "cabang" },
                        { "data" : "namasubcabang" },
                        { "data" : "initsubcabang" },
                        { "data" : "aktif" },
                    ],
                    "fnDrawCallback": function( oSettings ) {
                        if(!tableawal.data().any()) {
                            swal("Ups!", "User {{ auth()->user()->name }} belum memiliki hak akses ke subcabang, silahkan hubungi Tim IT!", "error");
                        }else if(!$('#modalhomeSubcabang').hasClass('in')){
                            $('#modalhomeSubcabang').modal('show');
                        }
                    }
                });

                $('#modalhomeSubcabang').on('shown.bs.modal', function () {
                    tableawal.columns.adjust().draw();
                });

                $('#modalhomeSubcabang table#tablesubcabangpilihawal tbody').on('dblclick', 'tr', function(){
                    var data = tableawal.row( { selected: true } ).data();
                    $.ajaxSetup({
                        headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
                    });

                    $.ajax({
                        type: 'POST',
                        url : '{{route("home.setsubcabang")}}',
                        data: {
                            _token  :"{{ csrf_token() }}",
                            id: data.id
                        },
                        dataType: "json",
                        success : function(data){
                            if(data.success){
                                $('#modalhomeSubcabang').modal('hide');
                                $('#homesubcabang').html('{{auth()->user()->name}} - '+data.subcabang);
                                window.location.reload(true); 
                            }
                        },
                    });
                });

                $('#btnPilihSubcabang').click(function(){
                    var data = tableawal.row( { selected: true } ).data();
                    $.ajaxSetup({
                        headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
                    });

                    $.ajax({
                        type: 'POST',
                        url : '{{route("home.setsubcabang")}}',
                        data: {
                            _token  :"{{ csrf_token() }}",
                            id: data.id
                        },
                        dataType: "json",
                        success : function(data){
                            if(data.success){
                                $('#modalhomeSubcabang').modal('hide');
                                $('#homesubcabang').html('{{auth()->user()->name}} - '+data.subcabang);
                                window.location.reload(true);
                            }
                        },
                    });
                })
            });

        </script>
        @endif

        <script type="text/javascript">
            $(document).ready(function () {
                var ls = [
                    '#barang',
                    '#namastok',
                    '#c2',
                    '#darigudang',
                    '#gudang',
                    '#pemeriksa',
                    '#pemeriksa00',
                    '#pemeriksa00Koreksi',
                    '#checker1',
                    '#checker2',
                    '#checker2Koreksi',
                    '#penghitung',
                    '#pengirim',
                    '#penerima',
                    '#karyawan',
                    '#karyawanexpedisi',
                    '#karyawanpenjualan',
                    '#karyawanstock',
                    '#sopir',
                    '#kernet',
                    '#namasales',
                    '#salesman',
                    '#penanggungjawabrak',
                    '#penanggungjawabarea',
                    '#namastok',
                    '#toko',
                    '#namatoko',
                    '#supplier',
                    '#expedisi',
                    '#armadakirim',
                    '#kategoriprb',
                    '#koreksikategorirpj',
                    '#nosj'
                ];

                for (var i in ls) {
                    (function (cur) {
                        var celm = $(cur);

                        var cpar = celm.parent();
                        if (cpar.is(".input-group")) {
                            var cbtn = $(".fa-search", cpar);
                            if (cbtn.length > 0) {
                                cbtn.on("click", function () {
                                    var e = $.Event("keypress", { 'keyCode': 13 });
                                    celm.trigger(e);
                                });
                                return;
                            }
                        }

                        var mdr = $("<table style='width: 100%;'><tr><td></td><td style='width: 1px; padding-left: 3px;'><input type='button' value='...' class='btn btn-default btn-small' /></td></tr></table>").insertBefore(celm);
                        $("input.btn", mdr).on("click", function () {
                            var e = $.Event("keypress", { 'keyCode': 13 });
                            celm.trigger(e);
                        });
                        $(cur).detach().appendTo($("td:first", mdr));

                    })(ls[i]);
                }
            });

            $.fn.focusNextInputField = function() {
                return this.each(function() {
                    var fields = $(this).parents('form:eq(0)').find('.form-control:not(:disabled):not([readonly])');
                    var index = fields.index(this);
                    if (index > -1 && (index+1) < fields.length ) {
                        fields.eq(index+1 ).focus();
                    }
                    return false;
                });
            };

            $("modalKewenangan").on("hidden.bs.modal", function() {
                $("modalKewenangan").find("#uxserKewenangan").val("").change();
                $("modalKewenangan").find("#pxassKewenangan").val("").change();
            });

            function lookupsubcabang(){
                var tlk_sbcbg, tlk_sbcbg_idx, xtipe_sbcbg;
                var tlk_sbcbg_query      = '';
                var tlk_sbcbg_text       = 'hide';
                var tlk_sbcbg_empty      = 'hide';
                var tlk_sbcbg_filter     = ['=','!='];
                // var tlk_sbcbg_search_def = [[],[]];
                function sbcbg_arr() { return [[],[]]; }
                var tlk_sbcbg_search     = new sbcbg_arr();
                $('body').on("hidden.bs.modal", "#modalSbcbg", function(e) {tlk_sbcbg.clear(); tlk_sbcbg.draw(); tlk_sbcbg_search = new sbcbg_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalSbcbg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-md" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Subcabang</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQuerySbcbg">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQuerySbcbg" class="form-control" placeholder="Kode/Nama Subcabang">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">Kode</th>';
                html += '                                        <th class="text-center">Nama Subcabang</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodySbcbg" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihSbcbg" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#c2').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalSbcbg').modal('show');
                        $('#txtQuerySbcbg').val($(this).val());
                        if(tlk_sbcbg_query != $(this).val()) {
                            // tlk_sbcbg_search = tlk_sbcbg_search_def;
                            tlk_sbcbg_search = new sbcbg_arr();
                        }
                        tlk_sbcbg_query = $(this).val();
                        xtipe_sbcbg = $(this).attr('id');

                        search_sbcbg();
                        return false;
                    }
                });

                $('#darigudang').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalSbcbg').modal('show');
                        $('#txtQuerySbcbg').val($(this).val());
                        if(tlk_sbcbg_query != $(this).val()) {
                            // tlk_sbcbg_search = tlk_sbcbg_search_def;
                            tlk_sbcbg_search = new sbcbg_arr();
                        }
                        tlk_sbcbg_query = $(this).val();
                        xtipe_sbcbg = $(this).attr('id');

                        search_sbcbg();
                        return false;
                    }
                });

                $('#gudang').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalSbcbg').modal('show');
                        $('#txtQuerySbcbg').val($(this).val());
                        if(tlk_sbcbg_query != $(this).val()) {
                            // tlk_sbcbg_search = tlk_sbcbg_search_def;
                            tlk_sbcbg_search = new sbcbg_arr();
                        }
                        tlk_sbcbg_query = $(this).val();
                        xtipe_sbcbg = $(this).attr('id');

                        search_sbcbg();
                        return false;
                    }
                });

                $('#txtQuerySbcbg').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_sbcbg_query = $(this).val();
                        search_sbcbg(); $(this).blur();
                        return false;
                    }
                });

                $('#modalSbcbg').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_sbcbg();
                    }
                }).on('click', '#btnPilihSbcbg', function(){
                    pilih_sbcbg();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_sbcbg();
                });

                $('#modalSbcbg tbody').on('click', 'tr', function(){
                    // fokus = 'lookupsbcbg';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_sbcbg_text == 'show'){
                            $(".lu-sbcbg-textfilter").contextMenu("hide");
                            search_sbcbg();
                        }
                        if(tlk_sbcbg_empty == 'show'){
                            $(".lu-sbcbg-emptyfilter").contextMenu("hide");
                            search_sbcbg();
                        }
                    }
                });

                // Data Tables
                tlk_sbcbg = $('#modalSbcbg table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "kodesubcabang", "className": "textfilter" },
                        { "data" : "namasubcabang", "className": "textfilter" },
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_sbcbg_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalSbcbg tbody td.textfilter',
                    className: 'lu-sbcbg-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_sbcbg_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-sbcbg-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_sbcbg_text = 'hide';
                            tlk_sbcbg_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_sbcbg_search[tlk_sbcbg_idx] = [];
                            }else{
                                var count = $.map(tlk_sbcbg_search[tlk_sbcbg_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_sbcbg_search[tlk_sbcbg_idx] = [];
                                tlk_sbcbg_search[tlk_sbcbg_idx].push({filter : tlk_sbcbg_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalSbcbg tbody td.dataTables_empty',
                    className: 'lu-sbcbg-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_sbcbg_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-sbcbg-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_sbcbg_empty = 'hide';
                            tlk_sbcbg_idx   = $this.index();

                            // Clear First
                            // tlk_sbcbg_search = tlk_sbcbg_search_def;
                            tlk_sbcbg_search = new sbcbg_arr();

                            if(contextData.name == "") {
                                tlk_sbcbg_search[tlk_sbcbg_idx] = [];
                            }else{
                                var count = $.map(tlk_sbcbg_search[tlk_sbcbg_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_sbcbg_search[tlk_sbcbg_idx] = [];
                                tlk_sbcbg_search[tlk_sbcbg_idx].push({filter : tlk_sbcbg_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_sbcbg(){
                    if (tlk_sbcbg.row('.selected').data().length) {
                        swal("Ups!", "Subcabang belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_sbcbg.row('.selected').data();
                        if(data.id){
                            $('#'+xtipe_sbcbg+'id').val(data.id);
                            $('#'+xtipe_sbcbg).val(data.kodesubcabang);
                        }else{
                            $('#'+xtipe_sbcbg+'id').val('');
                            $('#'+xtipe_sbcbg).val('');
                        }

                        $('#modalSbcbg').modal('hide');
                        $('#'+xtipe_sbcbg+'id').change();
                        $('#'+xtipe_sbcbg).focus();
                    }
                }

                function search_sbcbg(){
                    $.ajax({
                        type   : 'GET',
                        url    : '{{route("lookup.getsubcabang",null)}}/' + encodeURIComponent(tlk_sbcbg_query),
                        data   : {filter : tlk_sbcbg_search},
                        success: function(data){
                            var data_parse = JSON.parse(data);
                            tlk_sbcbg.clear();
                            tlk_sbcbg.rows.add(data_parse);
                            tlk_sbcbg.draw();

                            setTimeout(function(){
                                tlk_sbcbg.columns.adjust();
                            }, 100);

                            $('#modalSbcbg').focus();
                           fokus = '#modalSbcbg';
                        },
                        error: function(data){
                            tlk_sbcbg.clear();
                            tlk_sbcbg.columns.adjust();
                        }
                    });
                }
            }

            function lookupstaff(){
                var tlk_staff, tlk_staff_idx, xtipe_staff;
                var tlk_staff_query      = '';
                var tlk_staff_text       = 'hide';
                var tlk_staff_empty      = 'hide';
                var tlk_staff_filter     = ['=','!='];
                // var tlk_staff_search_def = [[],[],[]];
                function staff_arr() { return [[],[],[]]; }
                var tlk_staff_search     = new staff_arr();
                $('body').on("hidden.bs.modal", "#modalStaff", function(e) {tlk_staff.clear(); tlk_staff.draw(); tlk_staff_search = new staff_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalStaff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-lg" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Staff</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryStaff">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQueryStaff" class="form-control" placeholder="Kode/Nama Staff">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">NIK HRD</th>';
                html += '                                        <th class="text-center">Nama Staff</th>';
                html += '                                        <th class="text-center">Departemen</th>';
                html += '                                        <th class="text-center">Jabatan</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodyStaff" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihStaff" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#pemeriksa').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#pemeriksa00').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#koreksipemeriksa00').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#pemeriksa00Koreksi').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#checker1').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#checker2').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#checker2Koreksi').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#penghitung').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#pengirim').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#penerima').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#karyawan').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#karyawanexpedisi').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#karyawanpenjualan').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#karyawanstock').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#sopir').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#kernet').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalStaff').modal('show');
                        $('#txtQueryStaff').val($(this).val());
                        if(tlk_staff_query != $(this).val()) {
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();
                        }
                        tlk_staff_query = $(this).val();
                        xtipe_staff = $(this).attr('id');

                        search_staff();
                        return false;
                    }
                });

                $('#txtQueryStaff').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_staff_query = $(this).val();
                        search_staff(); $(this).blur();
                        return false;
                    }
                });

                $('#modalStaff').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_staff();
                    }
                }).on('click', '#btnPilihStaff', function(){
                    pilih_staff();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_staff();
                });

                $('#modalStaff tbody').on('click', 'tr', function(){
                    // fokus = 'lookupstaff';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_staff_text == 'show'){
                            $(".lu-staff-textfilter").contextMenu("hide");
                            search_staff();
                        }
                        if(tlk_staff_empty == 'show'){
                            $(".lu-staff-emptyfilter").contextMenu("hide");
                            search_staff();
                        }
                    }
                });

                // Data Tables
                tlk_staff = $('#modalStaff table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "nikhrd", "className": "textfilter" },
                        { "data" : "namakaryawan", "className": "textfilter" },
                        { "data" : "namadepartemen", "className": "textfilter" },
                        { "data" : "namajabatan", "className": "textfilter" },
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_staff_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalStaff tbody td.textfilter',
                    className: 'lu-staff-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_staff_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-staff-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_staff_text = 'hide';
                            tlk_staff_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_staff_search[tlk_staff_idx] = [];
                            }else{
                                var count = $.map(tlk_staff_search[tlk_staff_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_staff_search[tlk_staff_idx] = [];
                                tlk_staff_search[tlk_staff_idx].push({filter : tlk_staff_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalStaff tbody td.dataTables_empty',
                    className: 'lu-staff-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_staff_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-staff-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_staff_empty = 'hide';
                            tlk_staff_idx   = $this.index();

                            // Clear First
                            // tlk_staff_search = tlk_staff_search_def;
                            tlk_staff_search = new staff_arr();

                            if(contextData.name == "") {
                                tlk_staff_search[tlk_staff_idx] = [];
                            }else{
                                var count = $.map(tlk_staff_search[tlk_staff_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_staff_search[tlk_staff_idx] = [];
                                tlk_staff_search[tlk_staff_idx].push({filter : tlk_staff_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_staff(){
                    if (tlk_staff.row('.selected').data().length) {
                        swal("Ups!", "Staff belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_staff.row('.selected').data();
                        if(data.id){
                            $('#'+xtipe_staff+'id').val(data.id);
                            $('#'+xtipe_staff).val(data.namakaryawan);
                        }else{
                            $('#'+xtipe_staff+'id').val('');
                            $('#'+xtipe_staff).val('');
                        }

                        $('#modalStaff').modal('hide');
                        $('#'+xtipe_staff+'id').change();
                        $('#'+xtipe_staff).focus();
                    }
                }

                function search_staff(){
                    if(xtipe_staff == 'sopir'){
                        url = '{{route("lookup.karyawansopir")}}';
                    }else if(xtipe_staff == 'kernet'){
                        url = '{{route("lookup.karyawankernet")}}';
                    }else if(xtipe_staff == 'kernet'){
                        url = '{{route("lookup.karyawankernet")}}';
                    }else{
                        url = '{{route("lookup.getstaff")}}';
                    }

                    $.ajax({
                        type: 'POST',
                        url : '{{route("lookup.getstaff")}}',
                        data: {
                            _token   :"{{ csrf_token() }}",
                            katakunci: encodeURIComponent(tlk_staff_query),
                            filter   : tlk_staff_search,
                        },
                        success: function(data){
                            var data_parse = JSON.parse(data);
                            tlk_staff.clear();
                            tlk_staff.rows.add(data_parse);
                            tlk_staff.draw();

                            setTimeout(function(){
                                tlk_staff.columns.adjust();
                            }, 100);

                            $('#modalStaff').focus();
                           fokus = '#modalStaff';
                        },
                        error: function(data){
                            tlk_staff.clear();
                            tlk_staff.columns.adjust();
                        }
                    });
                }
            }

            function lookupsopir(){
                var tlk_sopir, tlk_sopir_idx, xtipe_sopir;
                var tlk_sopir_query      = '';
                var tlk_sopir_text       = 'hide';
                var tlk_sopir_empty      = 'hide';
                var tlk_sopir_filter     = ['=','!='];
                // var tlk_sopir_search_def = [[],[],[]];
                function sopir_arr() { return [[],[],[]]; }
                var tlk_sopir_search     = new sopir_arr();
                $('body').on("hidden.bs.modal", "#modalSopir", function(e) {tlk_sopir.clear(); tlk_sopir.draw(); tlk_sopir_search = new sopir_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalSopir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-lg" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Sopir</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQuerySopir">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQuerySopir" class="form-control" placeholder="Kode/Nama Sopir">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">NIK HRD</th>';
                html += '                                        <th class="text-center">Nama Sopir</th>';
                html += '                                        <th class="text-center">Kode Sopir</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodySopir" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihSopir" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#sopir').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalSopir').modal('show');
                        $('#txtQuerySopir').val($(this).val());
                        if(tlk_sopir_query != $(this).val()) {
                            // tlk_sopir_search = tlk_sopir_search_def;
                            tlk_sopir_search = new sopir_arr();
                        }
                        tlk_sopir_query = $(this).val();
                        xtipe_sopir = $(this).attr('id');

                        search_sopir();
                        return false;
                    }
                });

                $('#txtQuerySopir').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_sopir_query = $(this).val();
                        search_sopir(); $(this).blur();
                        return false;
                    }
                });

                $('#modalSopir').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_sopir();
                    }
                }).on('click', '#btnPilihSopir', function(){
                    pilih_sopir();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_sopir();
                });

                $('#modalSopir tbody').on('click', 'tr', function(){
                    // fokus = 'lookupsopir';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_sopir_text == 'show'){
                            $(".lu-sopir-textfilter").contextMenu("hide");
                            search_sopir();
                        }
                        if(tlk_sopir_empty == 'show'){
                            $(".lu-sopir-emptyfilter").contextMenu("hide");
                            search_sopir();
                        }
                    }
                });

                // Data Tables
                tlk_sopir = $('#modalSopir table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "nikhrd", "className": "textfilter" },
                        { "data" : "namakaryawan", "className": "textfilter" },
                        { "data" : "kodesopir", "className": "textfilter" },
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_sopir_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalSopir tbody td.textfilter',
                    className: 'lu-sopir-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_sopir_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-sopir-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_sopir_text = 'hide';
                            tlk_sopir_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_sopir_search[tlk_sopir_idx] = [];
                            }else{
                                var count = $.map(tlk_sopir_search[tlk_sopir_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_sopir_search[tlk_sopir_idx] = [];
                                tlk_sopir_search[tlk_sopir_idx].push({filter : tlk_sopir_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalSopir tbody td.dataTables_empty',
                    className: 'lu-sopir-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_sopir_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-sopir-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_sopir_empty = 'hide';
                            tlk_sopir_idx   = $this.index();

                            // Clear First
                            // tlk_sopir_search = tlk_sopir_search_def;
                            tlk_sopir_search = new sopir_arr();

                            if(contextData.name == "") {
                                tlk_sopir_search[tlk_sopir_idx] = [];
                            }else{
                                var count = $.map(tlk_sopir_search[tlk_sopir_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_sopir_search[tlk_sopir_idx] = [];
                                tlk_sopir_search[tlk_sopir_idx].push({filter : tlk_sopir_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_sopir(){
                    if (tlk_sopir.row('.selected').data().length) {
                        swal("Ups!", "Sopir belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_sopir.row('.selected').data();
                        if(data.id){
                            $('#'+xtipe_sopir+'id').val(data.id);
                            $('#'+xtipe_sopir).val(data.namakaryawan);
                        }else{
                            $('#'+xtipe_sopir+'id').val('');
                            $('#'+xtipe_sopir).val('');
                        }

                        $('#modalSopir').modal('hide');
                        $('#'+xtipe_sopir+'id').change();
                        $('#'+xtipe_sopir).focus();
                    }
                }

                function search_sopir(){
                    $.ajax({
                        type: 'POST',
                        url : '{{route("lookup.karyawansopir")}}',
                        data: {
                            _token   :"{{ csrf_token() }}",
                            katakunci: encodeURIComponent(tlk_sopir_query),
                            filter   : tlk_sopir_search,
                        },
                        success: function(data){
                            var data_parse = JSON.parse(data);
                            tlk_sopir.clear();
                            tlk_sopir.rows.add(data_parse);
                            tlk_sopir.draw();

                            setTimeout(function(){
                                tlk_sopir.columns.adjust();
                            }, 100);

                            $('#modalSopir').focus();
                           fokus = '#modalSopir';
                        },
                        error: function(data){
                            tlk_sopir.clear();
                            tlk_sopir.columns.adjust();
                        }
                    });
                }
            }

            function lookupkernet(){
                var tlk_kernet, tlk_kernet_idx, xtipe_kernet;
                var tlk_kernet_query      = '';
                var tlk_kernet_text       = 'hide';
                var tlk_kernet_empty      = 'hide';
                var tlk_kernet_filter     = ['=','!='];
                // var tlk_kernet_search_def = [[],[],[]];
                function kernet_arr() { return [[],[],[]]; }
                var tlk_kernet_search     = new kernet_arr();
                $('body').on("hidden.bs.modal", "#modalKernet", function(e) {tlk_kernet.clear(); tlk_kernet.draw(); tlk_kernet_search = new kernet_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalKernet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-lg" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Kernet</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryKernet">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQueryKernet" class="form-control" placeholder="Kode/Nama Kernet">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">NIK HRD</th>';
                html += '                                        <th class="text-center">Nama Kernet</th>';
                html += '                                        <th class="text-center">Kode Kernet</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodyKernet" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihKernet" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#kernet').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalKernet').modal('show');
                        $('#txtQueryKernet').val($(this).val());
                        if(tlk_kernet_query != $(this).val()) {
                            // tlk_kernet_search = tlk_kernet_search_def;
                            tlk_kernet_search = new kernet_arr();
                        }
                        tlk_kernet_query = $(this).val();
                        xtipe_kernet = $(this).attr('id');

                        search_kernet();
                        return false;
                    }
                });

                $('#txtQueryKernet').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_kernet_query = $(this).val();
                        search_kernet(); $(this).blur();
                        return false;
                    }
                });

                $('#modalKernet').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_kernet();
                    }
                }).on('click', '#btnPilihKernet', function(){
                    pilih_kernet();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_kernet();
                });

                $('#modalKernet tbody').on('click', 'tr', function(){
                    // fokus = 'lookupkernet';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_kernet_text == 'show'){
                            $(".lu-kernet-textfilter").contextMenu("hide");
                            search_kernet();
                        }
                        if(tlk_kernet_empty == 'show'){
                            $(".lu-kernet-emptyfilter").contextMenu("hide");
                            search_kernet();
                        }
                    }
                });

                // Data Tables
                tlk_kernet = $('#modalKernet table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "nikhrd", "className": "textfilter" },
                        { "data" : "namakaryawan", "className": "textfilter" },
                        { "data" : "kodekernet", "className": "textfilter" },
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_kernet_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalKernet tbody td.textfilter',
                    className: 'lu-kernet-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_kernet_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-kernet-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_kernet_text = 'hide';
                            tlk_kernet_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_kernet_search[tlk_kernet_idx] = [];
                            }else{
                                var count = $.map(tlk_kernet_search[tlk_kernet_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_kernet_search[tlk_kernet_idx] = [];
                                tlk_kernet_search[tlk_kernet_idx].push({filter : tlk_kernet_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalKernet tbody td.dataTables_empty',
                    className: 'lu-kernet-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_kernet_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-kernet-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_kernet_empty = 'hide';
                            tlk_kernet_idx   = $this.index();

                            // Clear First
                            // tlk_kernet_search = tlk_kernet_search_def;
                            tlk_kernet_search = new kernet_arr();

                            if(contextData.name == "") {
                                tlk_kernet_search[tlk_kernet_idx] = [];
                            }else{
                                var count = $.map(tlk_kernet_search[tlk_kernet_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_kernet_search[tlk_kernet_idx] = [];
                                tlk_kernet_search[tlk_kernet_idx].push({filter : tlk_kernet_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_kernet(){
                    if (tlk_kernet.row('.selected').data().length) {
                        swal("Ups!", "Kernet belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_kernet.row('.selected').data();
                        if(data.id){
                            $('#'+xtipe_kernet+'id').val(data.id);
                            $('#'+xtipe_kernet).val(data.namakaryawan);
                        }else{
                            $('#'+xtipe_kernet+'id').val('');
                            $('#'+xtipe_kernet).val('');
                        }

                        $('#modalKernet').modal('hide');
                        $('#'+xtipe_kernet+'id').change();
                        $('#'+xtipe_kernet).focus();
                    }
                }

                function search_kernet(){
                    $.ajax({
                        type: 'POST',
                        url : '{{route("lookup.karyawankernet")}}',
                        data: {
                            _token   :"{{ csrf_token() }}",
                            katakunci: encodeURIComponent(tlk_kernet_query),
                            filter   : tlk_kernet_search,
                        },
                        success: function(data){
                            var data_parse = JSON.parse(data);
                            tlk_kernet.clear();
                            tlk_kernet.rows.add(data_parse);
                            tlk_kernet.draw();

                            setTimeout(function(){
                                tlk_kernet.columns.adjust();
                            }, 100);

                            $('#modalKernet').focus();
                           fokus = '#modalKernet';
                        },
                        error: function(data){
                            tlk_kernet.clear();
                            tlk_kernet.columns.adjust();
                        }
                    });
                }
            }

            function lookupsalesman(){
                var tlk_salesman, tlk_salesman_idx, xtipe_salesman;
                var tlk_salesman_query      = '';
                var tlk_salesman_text       = 'hide';
                var tlk_salesman_empty      = 'hide';
                var tlk_salesman_filter     = ['=','!='];
                // var tlk_salesman_search_def = [[],[]];
                function salesman_arr() { return [[],[]]; }
                var tlk_salesman_search     = new salesman_arr();
                $('body').on("hidden.bs.modal", "#modalSalesman", function(e) {tlk_salesman.clear(); tlk_salesman.draw(); tlk_salesman_search = new salesman_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalSalesman" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-md" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Salesman</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQuerySalesman">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQuerySalesman" class="form-control" placeholder="Kode/Nama Salesman">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">Kode Sales</th>';
                html += '                                        <th class="text-center">Nama Salesman</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodySalesman" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihSalesman" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#namasales').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalSalesman').modal('show');
                        $('#txtQuerySalesman').val($(this).val());
                        if(tlk_salesman_query != $(this).val()) {
                            // tlk_salesman_search = tlk_salesman_search_def;
                            tlk_salesman_search = new salesman_arr();
                        }
                        tlk_salesman_query = $(this).val();
                        xtipe_salesman = $(this).attr('id');
                        // xtipe_salesman = $('#tipesalesman').val();

                        search_salesman();
                        return false;
                    }
                });

                $('#salesman').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalSalesman').modal('show');
                        $('#txtQuerySalesman').val($(this).val());
                        if(tlk_salesman_query != $(this).val()) {
                            // tlk_salesman_search = tlk_salesman_search_def;
                            tlk_salesman_search = new salesman_arr();
                        }
                        tlk_salesman_query = $(this).val();
                        xtipe_salesman = $(this).attr('id');
                        // xtipe_salesman = $('#tipesalesman').val();

                        search_salesman();
                        return false;
                    }
                });

                $('#txtQuerySalesman').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_salesman_query = $(this).val();
                        // xtipe_salesman = $('#tipesalesman').val();
                        search_salesman(); $(this).blur();
                        return false;
                    }
                });

                $('#modalSalesman').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_salesman();
                    }
                }).on('click', '#btnPilihSalesman', function(){
                    pilih_salesman();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_salesman();
                });

                $('#modalSalesman tbody').on('click', 'tr', function(){
                    // fokus = 'lookupsalesman';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_salesman_text == 'show'){
                            $(".lu-salesman-textfilter").contextMenu("hide");
                            search_salesman();
                        }
                        if(tlk_salesman_empty == 'show'){
                            $(".lu-salesman-emptyfilter").contextMenu("hide");
                            search_salesman();
                        }
                    }
                });

                // Data Tables
                tlk_salesman = $('#modalSalesman table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
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
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_salesman_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalSalesman tbody td.textfilter',
                    className: 'lu-salesman-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_salesman_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-salesman-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_salesman_text = 'hide';
                            tlk_salesman_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_salesman_search[tlk_salesman_idx] = [];
                            }else{
                                var count = $.map(tlk_salesman_search[tlk_salesman_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_salesman_search[tlk_salesman_idx] = [];
                                tlk_salesman_search[tlk_salesman_idx].push({filter : tlk_salesman_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalSalesman tbody td.dataTables_empty',
                    className: 'lu-salesman-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_salesman_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-salesman-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_salesman_empty = 'hide';
                            tlk_salesman_idx   = $this.index();

                            // Clear First
                            // tlk_salesman_search = tlk_salesman_search_def;
                            tlk_salesman_search = new salesman_arr();

                            if(contextData.name == "") {
                                tlk_salesman_search[tlk_salesman_idx] = [];
                            }else{
                                var count = $.map(tlk_salesman_search[tlk_salesman_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_salesman_search[tlk_salesman_idx] = [];
                                tlk_salesman_search[tlk_salesman_idx].push({filter : tlk_salesman_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_salesman(){
                    if (tlk_salesman.row('.selected').data().length) {
                        swal("Ups!", "Salesman belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_salesman.row('.selected').data();
                        if(data.id){
                            $('#salesmanid').val(data.id);
                            $('#salesid').val(data.id);
                            $('#salesman').val(data.namakaryawan);
                            $('#namasales').val(data.namakaryawan);
                            $('#kodesales').val(data.kodesales);
                        }else{
                            $('#salesmanid').val('');
                            $('#salesid').val('');
                            $('#salesman').val('');
                            $('#namasales').val('');
                            $('#kodesales').val('');
                        }

                        $('#modalSalesman').modal('hide');
                        $('#salesmanid').change();
                        $('#salesman').focus();
                    }
                }

                function search_salesman(){
                    $.ajax({
                        type   : 'GET',
                        url    : '{{route("lookup.getsalesman",[null,null])}}/{{session('subcabang')}}'+ ((tlk_salesman_query) ? '/'+encodeURIComponent(tlk_salesman_query) : ''),
                        data   : { filter : tlk_salesman_search },
                        success: function(data){
                            var data_parse = JSON.parse(data);
                            tlk_salesman.clear();
                            tlk_salesman.rows.add(data_parse);
                            tlk_salesman.draw();

                            setTimeout(function(){
                                tlk_salesman.columns.adjust();
                            }, 100);

                            $('#modalSalesman').focus();
                           fokus = '#modalSalesman';
                        },
                        error: function(data){
                            tlk_salesman.clear();
                            tlk_salesman.columns.adjust();
                        }
                    });
                }
            }

            function lookuppenanggungjawab(){
                var tlk_penanggungjawab, tlk_penanggungjawab_idx, xtipe_penanggungjawab;
                var tlk_penanggungjawab_query      = '';
                var tlk_penanggungjawab_text       = 'hide';
                var tlk_penanggungjawab_empty      = 'hide';
                var tlk_penanggungjawab_filter     = ['=','!='];
                // var tlk_penanggungjawab_search_def = [[],[]];
                function penanggungjawab_arr() { return [[],[]]; }
                var tlk_penanggungjawab_search     = new penanggungjawab_arr();
                $('body').on("hidden.bs.modal", "#modalPenanggungjawab", function(e) {tlk_penanggungjawab.clear(); tlk_penanggungjawab.draw(); tlk_penanggungjawab_search = new penanggungjawab_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalPenanggungjawab" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-md" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Penanggungjawab</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryPenanggungjawab">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQueryPenanggungjawab" class="form-control" placeholder="Kode/Nama Penanggungjawab">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">NIK</th>';
                html += '                                        <th class="text-center">Nama Penanggungjawab</th>';
                html += '                                        <th class="text-center">Kode Area</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodyPenanggungjawab" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihPenanggungjawab" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#penanggungjawabrak').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalPenanggungjawab').modal('show');
                        $('#txtQueryPenanggungjawab').val($(this).val());
                        if(tlk_penanggungjawab_query != $(this).val()) {
                            // tlk_penanggungjawab_search = tlk_penanggungjawab_search_def;
                            tlk_penanggungjawab_search = new penanggungjawab_arr();
                        }
                        tlk_penanggungjawab_query = $(this).val();
                        xtipe_penanggungjawab = $(this).attr('id');
                        // xtipe_penanggungjawab = $('#tipepenanggungjawab').val();

                        search_penanggungjawab();
                        return false;
                    }
                });

                $('#penanggungjawabarea').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalPenanggungjawab').modal('show');
                        $('#txtQueryPenanggungjawab').val($(this).val());
                        if(tlk_penanggungjawab_query != $(this).val()) {
                            // tlk_penanggungjawab_search = tlk_penanggungjawab_search_def;
                            tlk_penanggungjawab_search = new penanggungjawab_arr();
                        }
                        tlk_penanggungjawab_query = $(this).val();
                        xtipe_penanggungjawab = $(this).attr('id');
                        // xtipe_penanggungjawab = $('#tipepenanggungjawab').val();

                        search_penanggungjawab();
                        return false;
                    }
                });

                $('#txtQueryPenanggungjawab').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_penanggungjawab_query = $(this).val();
                        // xtipe_penanggungjawab = $('#tipepenanggungjawab').val();
                        search_penanggungjawab(); $(this).blur();
                        return false;
                    }
                });

                $('#modalPenanggungjawab').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_penanggungjawab();
                    }
                }).on('click', '#btnPilihPenanggungjawab', function(){
                    pilih_penanggungjawab();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_penanggungjawab();
                });

                $('#modalPenanggungjawab tbody').on('click', 'tr', function(){
                    // fokus = 'lookuppenanggungjawab';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_penanggungjawab_text == 'show'){
                            $(".lu-penanggungjawab-textfilter").contextMenu("hide");
                            search_penanggungjawab();
                        }
                        if(tlk_penanggungjawab_empty == 'show'){
                            $(".lu-penanggungjawab-emptyfilter").contextMenu("hide");
                            search_penanggungjawab();
                        }
                    }
                });

                // Data Tables
                tlk_penanggungjawab = $('#modalPenanggungjawab table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "nikhrd", "className": "textfilter" },
                        { "data" : "namakaryawan", "className": "textfilter" },
                        { "data" : "kodearea", "className": "textfilter" },
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_penanggungjawab_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalPenanggungjawab tbody td.textfilter',
                    className: 'lu-penanggungjawab-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_penanggungjawab_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-penanggungjawab-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_penanggungjawab_text = 'hide';
                            tlk_penanggungjawab_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_penanggungjawab_search[tlk_penanggungjawab_idx] = [];
                            }else{
                                var count = $.map(tlk_penanggungjawab_search[tlk_penanggungjawab_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_penanggungjawab_search[tlk_penanggungjawab_idx] = [];
                                tlk_penanggungjawab_search[tlk_penanggungjawab_idx].push({filter : tlk_penanggungjawab_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalPenanggungjawab tbody td.dataTables_empty',
                    className: 'lu-penanggungjawab-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_penanggungjawab_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-penanggungjawab-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_penanggungjawab_empty = 'hide';
                            tlk_penanggungjawab_idx   = $this.index();

                            // Clear First
                            // tlk_penanggungjawab_search = tlk_penanggungjawab_search_def;
                            tlk_penanggungjawab_search = new Penanggungjawab_arr();

                            if(contextData.name == "") {
                                tlk_penanggungjawab_search[tlk_penanggungjawab_idx] = [];
                            }else{
                                var count = $.map(tlk_penanggungjawab_search[tlk_penanggungjawab_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_penanggungjawab_search[tlk_penanggungjawab_idx] = [];
                                tlk_penanggungjawab_search[tlk_penanggungjawab_idx].push({filter : tlk_penanggungjawab_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_penanggungjawab(){
                    if (tlk_penanggungjawab.row('.selected').data().length) {
                        swal("Ups!", "Penanggungjawab belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_penanggungjawab.row('.selected').data();
                        if(data.id){
                            $('#'+xtipe_penanggungjawab+'Id').val(data.id);
                            $('#'+xtipe_penanggungjawab).val(data.namakaryawan);
                        }else{
                            $('#'+xtipe_penanggungjawab+'Id').val('');
                            $('#'+xtipe_penanggungjawab).val('');
                        }

                        $('#modalPenanggungjawab').modal('hide');
                        $('#'+xtipe_penanggungjawab+'Id').change();
                        $('#'+xtipe_penanggungjawab).focus();
                    }
                }

                function search_penanggungjawab(){
                    $.ajax({
                        type   : 'POST',
                        url    : '{{route("lookup.karyawangetpj")}}',
                        data   : {_token  :"{{ csrf_token() }}", katakunci : encodeURIComponent(tlk_penanggungjawab_query), filter : tlk_penanggungjawab_search},
                        success: function(data){
                            var data_parse = JSON.parse(data);
                            tlk_penanggungjawab.clear();
                            tlk_penanggungjawab.rows.add(data_parse);
                            tlk_penanggungjawab.draw();

                            setTimeout(function(){
                                tlk_penanggungjawab.columns.adjust();
                            }, 100);

                            $('#modalPenanggungjawab').focus();
                           fokus = '#modalPenanggungjawab';
                        },
                        error: function(data){
                            tlk_penanggungjawab.clear();
                            tlk_penanggungjawab.columns.adjust();
                        }
                    });
                }
            }

            /**
             * di argumen optfilter itu nanti bisa di tambahkan seperti hpp atau typebarang sebagai opsi,
             * jadi nggak perlu tambah satu argument lagi, tinggal masukin key di optfilter saja
             * [by gearintellix@javasign]
             **/
            function lookupbarang(bmk=null,ro=null,hitungbo=null,hpp=null,optfilter={}){
                var xtipe_trx = '';
                var tlk_brg, tlk_brg_idx, tlk_brg_parent, xtipe_brg;
                var tlk_brg_query      = '';
                var tlk_brg_text       = 'hide';
                var tlk_brg_empty      = 'hide';
                var tlk_brg_filter     = ['=','!='];
                // var tlk_brg_search_def = [[],[],[]];
                function brg_arr() { return [[],[],[]]; }
                var tlk_brg_search     = new brg_arr();
                $('body').on("hidden.bs.modal", "#modalBrg", function(e) {tlk_brg.clear(); tlk_brg.draw(); tlk_brg_search = new brg_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalBrg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-xl" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Barang</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryBrg">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                               <input type="text" id="txtQueryBrgs" class="form-control" placeholder="CARI BARANG...">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">Kode</th>';
                html += '                                        <th class="text-center">Nama Barang</th>';
                html += '                                        <th class="text-center">Stok</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodyBrg" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihBrg" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#barang').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalBrg').modal('show');
                        $('#txtQueryBrg').val("");
                        if(tlk_brg_query != $(this).val()) {
                            // tlk_brg_search = tlk_brg_search_def;
                            tlk_brg_search = new brg_arr();
                        }
                        tlk_brg.columns(1).search("").draw();
                        tlk_brg_query  = $(this).val();
                        xtipe_trx      = $('#tipetransaksidetail').val() ? $('#tipetransaksidetail').val() : $('#tipetransaksi').val();
                        tlk_brg_parent = $(this).parents('div.modal').attr('id');
                        xtipe_brg      = $(this).attr('id');

                        search_brg();
                        return false;
                    }
                });
                
                // Trigger Lookup
                $('#btnsBrg').on('click', function(e){
                    //if (e.keyCode == '13') {
                    $('#modalBrg').modal('show');
                    $('#txtQueryBrg').val("");
                    if(tlk_brg_query != $(this).val()) {
                        // tlk_brg_search = tlk_brg_search_def;
                        tlk_brg_search = new brg_arr();
                    }
                    tlk_brg.columns(1).search("").draw();
                    tlk_brg_query  = $('#txtQueryBrg').val();
                    xtipe_trx      = $('#tipetransaksidetail').val() ? $('#tipetransaksidetail').val() : $('#tipetransaksi').val();
                    tlk_brg_parent = $('#txtQueryBrg').parents('div.modal').attr('id');
                    xtipe_brg      = $('#txtQueryBrg').attr('id');

                    search_brg();
                    return false;
                    //}
                });

                $('#namastok').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalBrg').modal('show');
                        $('#txtQueryBrg').val("");
                        if(tlk_brg_query != $(this).val()) {
                            // tlk_brg_search = tlk_brg_search_def;
                            tlk_brg_search = new brg_arr();
                        }
                        tlk_brg.columns(1).search("").draw();
                        tlk_brg_query  = $(this).val();
                        xtipe_trx      = $('#tipetransaksidetail').val() ? $('#tipetransaksidetail').val() : $('#tipetransaksi').val();
                        tlk_brg_parent = $(this).parents('div.modal').attr('id');
                        xtipe_brg      = $(this).attr('id');

                        search_brg();
                        return false;
                    }
                });

                $('#txtQueryBrg').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_brg_query = $(this).val();
                        // xtipe_trx = $('#tipetransaksidetail').val() ? $('#tipetransaksidetail').val() : $('#tipetransaksi').val();
                        //search_brg(); $(this).blur();
                        return false;
                    }
                });

                $('#modalBrg').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_brg();
                    }
                }).on('click', '#btnPilihBrg', function(){
                    pilih_brg();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_brg();
                });

                $('#modalBrg tbody').on('click', 'tr', function(){
                    // fokus = 'lookupbarang';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_brg_text == 'show'){
                            $(".lu-brg-textfilter").contextMenu("hide");
                            search_brg();
                        }
                        if(tlk_brg_empty == 'show'){
                            $(".lu-brg-emptyfilter").contextMenu("hide");
                            search_brg();
                        }
                    }
                });

                // Data Tables
                tlk_brg = $('#modalBrg table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "kodebarang", "className": "textfilter" },
                        { "data" : "namabarang", "className": "textfilter" },
                        { "data" : "jmlgudang", "className": "numberfilter" },
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_brg_idx = indexes;
                });

                $('#txtQueryBrgs').on('keyup', function (){
                    try {
                        tlk_brg.columns(1).search( this.value ).draw();
                    } catch (ex) { console.log(ex); }
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalBrg tbody td.textfilter',
                    className: 'lu-brg-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_brg_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-brg-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_brg_text = 'hide';
                            tlk_brg_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_brg_search[tlk_brg_idx] = [];
                            }else{
                                var count = $.map(tlk_brg_search[tlk_brg_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_brg_search[tlk_brg_idx] = [];
                                tlk_brg_search[tlk_brg_idx].push({filter : tlk_brg_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalBrg tbody td.dataTables_empty',
                    className: 'lu-brg-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_brg_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-brg-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_brg_empty = 'hide';
                            tlk_brg_idx   = $this.index();

                            // Clear First
                            // tlk_brg_search = tlk_brg_search_def;
                            tlk_brg_search = new brg_arr();

                            if(contextData.name == "") {
                                tlk_brg_search[tlk_brg_idx] = [];
                            }else{
                                var count = $.map(tlk_brg_search[tlk_brg_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_brg_search[tlk_brg_idx] = [];
                                tlk_brg_search[tlk_brg_idx].push({filter : tlk_brg_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_brg(){
                    if (tlk_brg.row('.selected').data().length) {
                        swal("Ups!", "Barang belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_brg.row('.selected').data();
                        console.log(data)
                        if(data.id){
                            $('#'+xtipe_brg+'id').val(data.id);
                            $('#'+xtipe_brg).val(data.namabarang);
                            $('#kodebarang').val(data.kodebarang);
                            $('#satuan').val(data.satuan);
                            $('#kategoriPenjualan').val(data.kategoripenjualan);

                            // Get Stock Gudang
                            showLoading(true);
                            var _i = 0, _m = 0;
                            var _hit = () => {
                                _i += 1;
                                if (_i == _m || (_m == 0 && _i == 1)) showLoading(false);
                            };

                            _m += 1;
                            $.ajax({
                                type    : 'GET',
                                url     : '{{route("lookup.getqtystockgudang",[null,null,null])}}/{{date('Y-m-d')}}/'+data.id+'/{{session('subcabang')}}',
                                dataType: 'json',
                                success : function(data){
                                    _hit();
                                    $('#qtystockgudang').val(data.total).change();
                                    $('#jmlgudang').val(data.total).change();
                                },
                                error: () => _hit()
                            });

                            // Get BMK
                            if(bmk === 'bmk') {
                                var str_statustoko = $('#statustokodetail').val() ? $('#statustokodetail').val() : $('#statustoko').val();

                                _m += 1;
                                $.ajax({
                                    type    : 'GET',
                                    url     : '{{route("orderpenjualan.gethargabmk",[null,null])}}/'+data.id+((str_statustoko) ? '/'+ str_statustoko : ''),
                                    dataType: 'json',
                                    success : function(data){
                                        _hit();
                                        if(Object.keys(data).length > 0) {
                                            if (str_statustoko.substring(0,1).toLowerCase() == 'b') {
                                                $('#hrgbmk').val(data.hrgb).change();
                                                // $('#hrgsatuanbrutto').val(data.hrgb).change();
                                            }else if (str_statustoko.substring(0,1).toLowerCase() == 'm') {
                                                $('#hrgbmk').val(data.hrgm).change();
                                                // $('#hrgsatuanbrutto').val(data.hrgm).change();
                                            }else {
                                                $('#hrgbmk').val(data.hrgk).change();
                                                // $('#hrgsatuanbrutto').val(data.hrgk).change();
                                            }
                                            $('#hrgpricelist').val(data.pricelist).change();
                                            if($('#hrgpricelist').val()) {
                                                // $('#hrgsatuanbrutto').val(data.pricelist).change();
                                            }

                                            $('#arrhrgbmk').val(JSON.stringify(data));
                                        }else {
                                            $('#hrgbmk').val(0);
                                        }
                                    },
                                    error: function(data){
                                        _hit();
                                        console.log(data);
                                    }
                                });
                            }

                            // Get Riwayat Order
                            if(ro === 'riwayatorder') {
                                var str_tokoid = $('#tokoiddetail').val() ? $('#tokoiddetail').val() : $('#tokoid').val();

                                _m += 1;
                                $.ajax({
                                    type    : 'GET',
                                    url     : '{{route("orderpenjualan.getriwayatorder",[null,null])}}/'+data.id+((str_tokoid) ? '/'+ str_tokoid : ''),
                                    dataType: 'json',
                                    success : function(data){
                                        _hit();
                                        if(Object.keys(data).length > 0) {
                                            $('#riwayatorder').val(JSON.stringify(data));
                                        }else{
                                            $('#riwayatorder').val('');
                                        }
                                    },
                                    error: function(data){
                                        _hit();
                                        console.log(data);
                                    }
                                });
                            }

                            // Get Hitung BO
                            if(hitungbo === 'hitungbo') {
                                _m += 1;
                                $.ajax({
                                    type: 'GET',
                                    url : '{{route("lookup.gethitungbo",null)}}/'+data.id,
                                    success: function(data){
                                        _hit();
                                        if (data.length == 0) {
                                            $('#modalDetail #qtyrataratajual').val(0).change();
                                            $('#modalDetail #qtystokmin').val(0).change();
                                            $('#modalDetail #qtystokakhir').val(0).change();
                                            $('#modalDetail #qtypenjualanbo').val(0).change();
                                            $('#modalDetail #qtyorder').val(0).change();
                                        }
                                        else{
                                            $('#modalDetail #qtyrataratajual').val(data.rataratajual).change();
                                            $('#modalDetail #qtystokmin').val(data.qtystockmin).change();
                                            $('#modalDetail #qtystokakhir').val(data.qtystokakhir).change();
                                            $('#modalDetail #qtypenjualanbo').val(data.qtybo).change();
                                            $('#modalDetail #qtyorder').val(data.qtyorder).change();
                                        }
                                    },
                                    error: function(data){
                                        _hit();
                                        console.log(data);
                                    }
                                });
                            }

                            // Get HPP
                            if(hpp === 'hpp') {
                                _m += 1;
                                $.ajax({
                                    type: 'GET',
                                    url : '{{route("lookup.gethpp",null)}}/'+data.id,
                                    success: function(data){
                                        _hit();
                                        if (data.length == 0) {
                                            $('#modalDetail #hrgsatuanbrutto').val(0).change();
                                        }
                                        else{
                                            $('#modalDetail #hrgsatuanbrutto').val(data.nominalhpp).change();
                                        }
                                    },
                                    error: function(data){
                                        _hit();
                                        console.log(data);
                                    }
                                });
                            }


                        }else{
                            $('#'+xtipe_brg+'id').val('');
                            $('#'+xtipe_brg).val('');
                            $('#kodebarang').val('');
                            $('#satuan').val('');
                            $('#qtystockgudang').val('');
                            $('#jmlgudang').val('');
                            $('#hrgsatuanbrutto').val('');
                        }
                        
                        $('#txtQueryBrgs').val('');
                        $('#modalBrg').modal('hide');
                        $('#'+xtipe_brg+'id').change();
                        $('#'+xtipe_brg).focusNextInputField();
                    }
                }

                function search_brg(){
                    showLoading(true);
                    $.ajax({
                        type   : 'POST',
                        url    : '{{route("lookup.getbarang")}}',
                        data   : {
                            _token   :"{{ csrf_token() }}",
                            katakunci: tlk_brg_query,
                            tipe     : xtipe_trx,
                            filter   : tlk_brg_search,
                            optfilter: optfilter || {}
                        },
                        success: function(data){
                            showLoading(false);

                            var data_parse = JSON.parse(data);
                            tlk_brg.clear();
                            tlk_brg.rows.add(data_parse);
                            tlk_brg.draw();
                            setTimeout(function(){
                                tlk_brg.columns.adjust();
                            }, 100);

                            $('#modalBrg').focus();
                            fokus = 'modalBrg';
                            // if(data_parse.length > 0) {
                            //     $('#modalBrg').find('tbody tr:nth-of-type(1) td:nth-of-type(1)').click();
                            // }
                        },
                        error: function(data){
                            showLoading(false);

                            tlk_brg.clear();
                            tlk_brg.columns.adjust();
                        }
                    });
                }
            }

            function lookupnpbd(){
                var xtipe_trx = '';
                var tlk_npbd, tlk_npbd_idx;
                var tlk_npbd_query      = '';
                var tlk_npbd_text       = 'hide';
                var tlk_npbd_empty      = 'hide';
                var tlk_npbd_filter     = ['=','!='];
                // var tlk_npbd_search_def = [[],[],[],[],[],[],[],[],[]];
                function npbd_arr() { return [[],[],[],[],[],[],[],[],[]]; }
                var tlk_npbd_search     = new npbd_arr();
                $('body').on("hidden.bs.modal", "#modalNpbd", function(e) {tlk_npbd.clear(); tlk_npbd.draw(); tlk_npbd_search = new npbd_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalNpbd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-xl" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Daftar Nota Pembelian Detail</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryNpbd">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQueryNpbd" class="form-control" placeholder="ID Barang" readonly>';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">No. Nota</th>';
                html += '                                        <th class="text-center">Tgl. Nota</th>';
                html += '                                        <th class="text-center">Qty. Nota</th>';
                html += '                                        <th class="text-center">Qty. Terima</th>';
                html += '                                        <th class="text-center">Qty. Retur</th>';
                html += '                                        <th class="text-center">Harga Satuan Bruto</th>';
                html += '                                        <th class="text-center">Disc1</th>';
                html += '                                        <th class="text-center">Disc2</th>';
                html += '                                        <th class="text-center">Harga Satuan Netto</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodyNpbd" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihNpbd" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#barangid').on('change', function(e){
                    if($('#historis').is(':checked')) {
                        $('#modalNpbd').modal('show');
                        $('#txtQueryNpbd').val($('#barangid').val());
                        if(tlk_npbd_query != $('#barangid').val()) {
                            // tlk_npbd_search = tlk_npbd_search_def;
                            tlk_npbd_search = new npbd_arr();
                        }
                        tlk_npbd_query = $('#barangid').val();

                        search_npbd();
                    }
                });

                $('#txtQueryNpbd').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_npbd_query = $(this).val();
                        search_npbd(); $(this).blur();
                        return false;
                    }
                });

                $('#modalNpbd').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_npbd();
                    }
                }).on('click', '#btnPilihNpbd', function(){
                    pilih_npbd();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_npbd();
                });

                $('#modalNpbd tbody').on('click', 'tr', function(){
                    // fokus = 'lookupnpbd';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_npbd_text == 'show'){
                            $(".lu-npbd-textfilter").contextMenu("hide");
                            search_npbd();
                        }
                        if(tlk_npbd_empty == 'show'){
                            $(".lu-npbd-emptyfilter").contextMenu("hide");
                            search_npbd();
                        }
                    }
                });

                // Data Tables
                tlk_npbd = $('#modalNpbd table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "nonota", "className": "textfilter" },
                        { "data" : "tglnota", "className": "textfilter" },
                        { "data" : "qtynota", "className": "numberfilter text-right" },
                        { "data" : "qtyterima", "className": "numberfilter text-right" },
                        { "data" : "qtyretur", "className": "numberfilter text-right" },
                        { "data" : "hrgsatuanbrutto", "className": "numberfilter text-right" },
                        { "data" : "disc1", "className": "numberfilter text-right" },
                        { "data" : "disc2", "className": "numberfilter text-right" },
                        { "data" : "hrgsatuannetto", "className": "numberfilter text-right" },
                        // npbdid = id
                        // notapembelianid = notapembelianid
                        // stockid = stockid
                        // qtynota = qtynota
                        // qtyterima = qtyterima
                        // hrgsatuanbrutto = hrgsatuanbrutto
                        // disc1 = disc1
                        // disc2 = disc2
                        // ppn = ppn
                        // hrgsatuannetto = hrgsatuannetto
                        // qtyretur = qtyretur
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_npbd_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalNpbd tbody td.textfilter',
                    className: 'lu-npbd-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_npbd_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-npbd-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_npbd_text = 'hide';
                            tlk_npbd_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_npbd_search[tlk_npbd_idx] = [];
                            }else{
                                var count = $.map(tlk_npbd_search[tlk_npbd_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_npbd_search[tlk_npbd_idx] = [];
                                tlk_npbd_search[tlk_npbd_idx].push({filter : tlk_npbd_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalNpbd tbody td.dataTables_empty',
                    className: 'lu-npbd-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_npbd_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-npbd-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_npbd_empty = 'hide';
                            tlk_npbd_idx   = $this.index();

                            // Clear First
                            // tlk_npbd_search = tlk_npbd_search_def;
                            tlk_npbd_search = new npbd_arr();

                            if(contextData.name == "") {
                                tlk_npbd_search[tlk_npbd_idx] = [];
                            }else{
                                var count = $.map(tlk_npbd_search[tlk_npbd_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_npbd_search[tlk_npbd_idx] = [];
                                tlk_npbd_search[tlk_npbd_idx].push({filter : tlk_npbd_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_npbd(){
                    if (tlk_npbd.row('.selected').data().length) {
                        swal("Ups!", "Nota Pembelian Detail belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_npbd.row('.selected').data();
                        if(data.id){
                            $('#npbdid').val(data.id);
                            $('#notapembelianid').val(data.notapembelianid);
                            $('#stockid').val(data.stockid);
                            $('#qtynota').val(data.qtynota);
                            $('#qtyterima').val(data.qtyterima);
                            $('#hrgsatuanbrutto').val(data.hrgsatuanbrutto);
                            $('#disc1').val(data.disc1);
                            $('#disc2').val(data.disc2);
                            $('#ppn').val(data.ppn);
                            $('#hrgsatuannetto').val(data.hrgsatuannetto);
                            $('#qtyretur').val(data.qtyretur);
                            $('#maxqty').val(data.qtyterima-data.qtyretur);
                        }else{
                            $('#npbdid').val('');
                            $('#notapembelianid').val('');
                            $('#stockid').val('');
                            $('#qtynota').val(0);
                            $('#qtyterima').val(0);
                            $('#hrgsatuanbrutto').val(0);
                            $('#disc1').val(0);
                            $('#disc2').val(0);
                            $('#ppn').val(0);
                            $('#hrgsatuannetto').val(0);
                            $('#qtyretur').val(0);
                            $('#maxqty').val(0);
                        }

                        $('#modalNpbd').modal('hide');
                        $('#barang').focusNextInputField();
                    }
                }

                function search_npbd(){
                    $.ajax({
                        type   : 'GET',
                        url    : '{{route("lookup.getnpbd",null)}}/'+tlk_npbd_query,
                        data   : {filter : tlk_npbd_search},
                        success: function(data){
                            var data_parse = JSON.parse(data);
                            tlk_npbd.clear();
                            tlk_npbd.rows.add(data_parse);
                            tlk_npbd.draw();
                            setTimeout(function(){
                                tlk_npbd.columns.adjust();
                            }, 100);

                            $('#modalNpbd').focus();
                            fokus = 'modalNpbd';
                            // if(data_parse.length > 0) {
                            //     $('#modalNpbd').find('tbody tr:nth-of-type(1) td:nth-of-type(1)').click();
                            // }
                        },
                        error: function(data){
                            tlk_npbd.clear();
                            tlk_npbd.columns.adjust();
                        }
                    });
                }
            }

            function lookupnpjd(){
                var xtipe_trx = '';
                var tlk_npjd, tlk_npjd_idx;
                var tlk_npjd_query      = '';
                var tlk_npjd_text       = 'hide';
                var tlk_npjd_empty      = 'hide';
                var tlk_npjd_filter     = ['=','!='];
                // var tlk_npjd_search_def = [[],[],[],[],[],[],[],[]];
                function npjd_arr() { return [[],[],[],[],[],[],[],[]]; }
                var tlk_npjd_search     = new npjd_arr();
                $('body').on("hidden.bs.modal", "#modalNpjd", function(e) {tlk_npjd.clear(); tlk_npjd.draw(); tlk_npjd_search = new npjd_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalNpjd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-xl" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Daftar Nota Penjualan Detail</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryNpjd">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQueryNpjd" class="form-control" placeholder="ID Barang" readonly>';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">No. Nota</th>';
                html += '                                        <th class="text-center">Tgl. Nota</th>';
                html += '                                        <th class="text-center">Qty. Nota</th>';
                html += '                                        <th class="text-center">Qty. Retur</th>';
                html += '                                        <th class="text-center">Harga Satuan Bruto</th>';
                html += '                                        <th class="text-center">Disc1</th>';
                html += '                                        <th class="text-center">Disc2</th>';
                html += '                                        <th class="text-center">Harga Satuan Netto</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodyNpjd" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihNpjd" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#barangid').on('change', function(e){
                    if($('#historis').is(':checked')) {
                        $('#modalNpjd').modal('show');
                        $('#txtQueryNpjd').val($('#barangid').val());
                        if(tlk_npjd_query != $('#barangid').val()) {
                            // tlk_npjd_search = tlk_npjd_search_def;
                            tlk_npjd_search = new npjd_arr();
                        }
                        tlk_npjd_query = $('#barangid').val();

                        search_npjd();
                    }
                });

                $('#txtQueryNpjd').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_npjd_query = $(this).val();
                        search_npjd(); $(this).blur();
                        return false;
                    }
                });

                $('#modalNpjd').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_npjd();
                    }
                }).on('click', '#btnPilihNpjd', function(){
                    pilih_npjd();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_npjd();
                });

                $('#modalNpjd tbody').on('click', 'tr', function(){
                    // fokus = 'lookupnpjd';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_npjd_text == 'show'){
                            $(".lu-npjd-textfilter").contextMenu("hide");
                            search_npjd();
                        }
                        if(tlk_npjd_empty == 'show'){
                            $(".lu-npjd-emptyfilter").contextMenu("hide");
                            search_npjd();
                        }
                    }
                });

                // Data Tables
                tlk_npjd = $('#modalNpjd table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        {"data" : "nonota", "className": "textfilter" },
                        {"data" : "tglnota", "className": "textfilter" },
                        {"data" : "qtynota", "className": "numberfilter text-right" },
                        {"data" : "qtyretur", "className": "numberfilter text-right" },
                        {"data" : "hrgsatuanbrutto", "className": "numberfilter text-right" },
                        {"data" : "disc1", "className": "numberfilter text-right" },
                        {"data" : "disc2", "className": "numberfilter text-right" },
                        {"data" : "hrgsatuannetto", "className": "numberfilter text-right" },
                        // npjdid= id
                        // notapenjualanid= notapenjualanid
                        // stockid= stockid
                        // qtynota= qtynota
                        // qtyterima= qtyterima
                        // hrgsatuanbrutto= hrgsatuanbrutto
                        // disc1= disc1
                        // disc2= disc2
                        // hrgdisc1= hrgdisc1
                        // hrgdisc2= hrgdisc2
                        // ppn= ppn
                        // hrgsatuannetto= hrgsatuannetto
                        // qtyretur= qtyretur
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_npjd_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalNpjd tbody td.textfilter',
                    className: 'lu-npjd-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_npjd_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-npjd-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_npjd_text = 'hide';
                            tlk_npjd_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_npjd_search[tlk_npjd_idx] = [];
                            }else{
                                var count = $.map(tlk_npjd_search[tlk_npjd_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_npjd_search[tlk_npjd_idx] = [];
                                tlk_npjd_search[tlk_npjd_idx].push({filter : tlk_npjd_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalNpjd tbody td.dataTables_empty',
                    className: 'lu-npjd-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_npjd_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-npjd-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_npjd_empty = 'hide';
                            tlk_npjd_idx   = $this.index();

                            // Clear First
                            // tlk_npjd_search = tlk_npjd_search_def;
                            tlk_npjd_search = new npjd_arr();

                            if(contextData.name == "") {
                                tlk_npjd_search[tlk_npjd_idx] = [];
                            }else{
                                var count = $.map(tlk_npjd_search[tlk_npjd_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_npjd_search[tlk_npjd_idx] = [];
                                tlk_npjd_search[tlk_npjd_idx].push({filter : tlk_npjd_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_npjd(){
                    if (tlk_npjd.row('.selected').data().length) {
                        swal("Ups!", "Nota Pembelian Detail belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_npjd.row('.selected').data();
                        console.log(data)
                        if(data.id){
                            $('#npjdid').val(data.id);
                            $('#notapenjualanid').val(data.notapenjualanid);
                            $('#stockid').val(data.stockid);
                            $('#qtynota').val(data.qtynota);
                            $('#qtyretur').val(data.qtyretur);
                            $('#qtyterima').val(data.qtyterima);
                            $('#hrgsatuanbrutto').val(data.hrgsatuanbrutto);
                            $('#disc1').val(data.disc1);
                            $('#disc2').val(data.disc2);
                            $('#hrgdisc1').val(data.hrgdisc1);
                            $('#hrgdisc2').val(data.hrgdisc2);
                            $('#ppn').val(data.ppn);
                            $('#hrgsatuannetto').val(data.hrgsatuannetto);
                            $('#maxqty').val(data.qtyterima-data.qtyretur);
                        }else{
                            $('#npjdid').val('');
                            $('#notapenjualanid').val('');
                            $('#stockid').val('');
                            $('#qtynota').val(0);
                            $('#qtyterima').val(0);
                            $('#hrgsatuanbrutto').val(0);
                            $('#disc1').val(0);
                            $('#disc2').val(0);
                            $('#hrgdisc1').val(0);
                            $('#hrgdisc2').val(0);
                            $('#ppn').val(0);
                            $('#hrgsatuannetto').val(0);
                            $('#qtyretur').val(0);
                            $('#maxqty').val(0);
                        }

                        $('#modalNpjd').modal('hide');
                        $("#qtympr").focus();
                    }
                }

                function search_npjd(){
                    $.ajax({
                        type   : 'POST',
                        url    : '{{route("lookup.getnpjd")}}',
                        data   : {
                            _token  :"{{ csrf_token() }}",
                            filter  : tlk_npjd_search,
                            barangid: tlk_npjd_query,
                            tokoid  : $('#tokoid').val()
                        },
                        success: function(data){
                            var data_parse = JSON.parse(data);
                            tlk_npjd.clear();
                            tlk_npjd.rows.add(data_parse);
                            tlk_npjd.draw();
                            setTimeout(function(){
                                tlk_npjd.columns.adjust();
                            }, 100);

                            $('#modalNpjd').focus();
                            fokus = 'modalNpjd';
                            // if(data_parse.length > 0) {
                            //     $('#modalNpjd').find('tbody tr:nth-of-type(1) td:nth-of-type(1)').click();
                            // }
                        },
                        error: function(data){
                            tlk_npjd.clear();
                            tlk_npjd.columns.adjust();
                        }
                    });
                }
            }

            function lookuptoko(){
                var tlk_toko, tlk_toko_idx, xtipe_toko;
                var tlk_toko_query      = '';
                var tlk_toko_text       = 'hide';
                var tlk_toko_empty      = 'hide';
                var tlk_toko_filter     = ['=','!='];
                // var tlk_toko_search_def = [[],[],[],[],[],[]];
                function toko_arr() { return [[],[],[],[],[],[]]; }
                var tlk_toko_search     = new toko_arr();
                $('body').on("hidden.bs.modal", "#modalToko", function(e) {tlk_toko.clear(); tlk_toko.draw(); tlk_toko_search = new toko_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalToko" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-xl" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Toko</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryToko">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                               <input type="text" id="txtQueryTokos" class="form-control" placeholder="CARI TOKO...">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">Nama Toko</th>';
                html += '                                        <th class="text-center">Alamat</th>';
                html += '                                        <th class="text-center">Wilid</th>';
                html += '                                        <th class="text-center">Daerah</th>';
                html += '                                        <th class="text-center">Kota</th>';
                html += '                                        <th class="text-center">Status</th>';
                html += '                                        <th class="text-center">Toko ID</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodyToko" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihToko" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#toko').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalToko').modal('show');
                        $('#txtQueryTokos').val("");
                        if(tlk_toko_query != $(this).val()) {
                            // tlk_toko_search = tlk_toko_search_def;
                            tlk_toko_search = new toko_arr();
                        }
                        tlk_toko_query = $(this).val();
                        xtipe_toko = $(this).attr('id');

                        search_toko();
                        return false;
                    }
                }).on('keydown', function(e){
                    if (e.keyCode != '9') {
                        $('#idtoko').val('');
                        $('#tokoid').val('');
                        $('#alamat').val('');
                        $('#kota').val('');
                        $('#kecamatan').val('');
                        $('#wilid').val('');
                        $('#statustoko').val('');
                    }
                });

                /*$('#btnsToko').on('click', function(e){
                    $('#modalToko').modal('show');
                    $('#txtQueryToko').val($('#txtQueryToko').val());
                    if(tlk_toko_query != $('#txtQueryToko').val()) {
                        // tlk_toko_search = tlk_toko_search_def;
                        tlk_toko_search = new toko_arr();
                    }
                    tlk_toko_query = $('#txtQueryToko').val();
                    xtipe_toko = $('#txtQueryToko').attr('id');

                    search_toko();
                    return false;
                });*/

                $('#namatoko').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalToko').modal('show');
                        $('#txtQueryTokos').val("");
                        if(tlk_toko_query != $(this).val()) {
                            // tlk_toko_search = tlk_toko_search_def;
                            tlk_toko_search = new toko_arr();
                        }
                        tlk_toko_query = $(this).val();
                        xtipe_toko = $(this).attr('id');

                        search_toko();
                        return false;
                    }
                }).on('keydown', function(e){
                    if (e.keyCode != '9') {
                        $('#kodetoko').val('');
                        $('#wilid').val('');
                        $('#alamattoko').val('');
                        $('#kotatoko').val('');
                        $('#tokoid').val('');
                    }
                });

                $('#txtQueryToko').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_toko_query = $(this).val();
                        search_toko(); $(this).blur();
                        return false;
                    }
                });

                $('#modalToko').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_toko();
                    }
                }).on('click', '#btnPilihToko', function(){
                    pilih_toko();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_toko();
                });

                $('#modalToko tbody').on('click', 'tr', function(){
                    // fokus = 'lookuptoko';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_toko_text == 'show'){
                            $(".lu-toko-textfilter").contextMenu("hide");
                            search_toko();
                        }
                        if(tlk_toko_empty == 'show'){
                            $(".lu-toko-emptyfilter").contextMenu("hide");
                            search_toko();
                        }
                    }
                });

                // Data Tables
                tlk_toko = $('#modalToko table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "namatoko", "className": "textfilter" },
                        { "data" : "alamat", "className": "textfilter" },
                        { "data" : "customwilayah", "className": "textfilter" },
                        { "data" : "kecamatan", "className": "textfilter" },
                        { "data" : "kota", "className": "textfilter" },
                        { "data" : "statustoko", "className": "textfilter" },
                        { "data" : "id", "className": "textfilter" },
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_toko_idx = indexes;
                });

                $('#txtQueryTokos').on('keyup', function (){
                    tlk_toko.search( this.value ).draw();
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalToko tbody td.textfilter',
                    className: 'lu-toko-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_toko_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-toko-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_toko_text = 'hide';
                            tlk_toko_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_toko_search[tlk_toko_idx] = [];
                            }else{
                                var count = $.map(tlk_toko_search[tlk_toko_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_toko_search[tlk_toko_idx] = [];
                                tlk_toko_search[tlk_toko_idx].push({filter : tlk_toko_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalToko tbody td.dataTables_empty',
                    className: 'lu-toko-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_toko_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-toko-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_toko_empty = 'hide';
                            tlk_toko_idx   = $this.index();

                            // Clear First
                            // tlk_toko_search = tlk_toko_search_def;
                            tlk_toko_search = new toko_arr();

                            if(contextData.name == "") {
                                tlk_toko_search[tlk_toko_idx] = [];
                            }else{
                                var count = $.map(tlk_toko_search[tlk_toko_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_toko_search[tlk_toko_idx] = [];
                                tlk_toko_search[tlk_toko_idx].push({filter : tlk_toko_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_toko(){
                    if (tlk_toko.row('.selected').data().length) {
                        swal("Ups!", "Toko belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_toko.row('.selected').data();
                        if(data.id){
                            $('#idtoko').val(data.id);
                            $('#tokoid').val(data.id);
                            $('#toko').val(data.namatoko);
                            $('#alamat').val(data.alamat);
                            $('#kota').val(data.kota);
                            $('#kecamatan').val(data.kecamatan);
                            $('#wilid').val(data.customwilayah);

                            $('#kodetoko').val(data.kodetoko);
                            $('#namatoko').val(data.namatoko);
                            $('#alamattoko').val(data.alamat);
                            $('#kotatoko').val(data.kota);

                            showLoading(true, () => {
                                $.ajax({
                                    type    : 'GET',
                                    url     : '{{route("lookup.gettokodetail",null)}}/'+ data.id,
                                    dataType: 'json',
                                    success : function(data){
                                        showLoading(false);
                                        $('#statustoko').val(data.statustoko);

                                        // Khusus
                                        $('#temponota').val(data.jwkredit);
                                        $('#temponotas').val(data.jwkredit);
                                        $('#tempokirim').val(data.jwkirim);
                                        //$('#temponotas').attr('readonly', true).attr('tabindex',-1);
                                        $('#temposalesman').val(data.jwsales);

                                        var is_usersalesman = "{{(auth()->user()->karyawan && auth()->user()->karyawan->kodesales) ? auth()->user()->karyawan->id : ''}}";
                                        if(!is_usersalesman) {
                                            $('#salesman').val(data.namasalesman);
                                            $('#salesman').attr('readonly', false);
                                            $('#salesman').removeAttr('tabindex');
                                            $('#salesman').attr('required', true);
                                            $('#salesmanid').val(data.karyawanidsalesman);
                                            $('#expedisi').focus();
                                        }
                                    },
                                    error: () => showLoading(false)
                                });
                            });
                        }else{
                            $('#idtoko').val('');
                            $('#tokoid').val('');
                            $('#toko').val('');
                            $('#alamat').val('');
                            $('#kota').val('');
                            $('#kecamatan').val('');
                            $('#wilid').val('');
                            $('#statustoko').val('');

                            $('#kodetoko').val('');
                            $('#namatoko').val('');
                            $('#alamattoko').val('');
                            $('#kotatoko').val('');
                        }

                        $('#txtQueryTokos').val('');
                        $('#modalToko').modal('hide');
                        $('#tokoid').change();
                        $('#'+xtipe_toko).focus();
                    }
                }

                function search_toko(){
                    showLoading(true, () => {
                        $.ajax({
                            type   : 'GET',
                            url    : '{{route("lookup.gettoko",null)}}'+ (tlk_toko_query ? '/'+encodeURIComponent(tlk_toko_query) : ''),
                            data   : {filter : tlk_toko_search},
                            success: function(data){
                                showLoading(false);

                                var data_parse = JSON.parse(data);
                                tlk_toko.clear();
                                tlk_toko.rows.add(data_parse);
                                tlk_toko.draw();
                                setTimeout(function(){
                                    tlk_toko.columns.adjust();
                                }, 100);

                                $('#modalToko').focus();
                                fokus = 'modalToko';
                                // if(data_parse.length > 0) {
                                //     $('#modalToko').find('tbody tr:nth-of-type(1) td:nth-of-type(1)').click();
                                // }
                            },
                            error: function(data){
                                showLoading(false);

                                tlk_toko.clear();
                                tlk_toko.columns.adjust();
                            }
                        });
                    });
                }
            }

            function lookupsupplier(){
                var tlk_supplier, tlk_supplier_idx;
                var tlk_supplier_query      = '';
                var tlk_supplier_text       = 'hide';
                var tlk_supplier_empty      = 'hide';
                var tlk_supplier_filter     = ['=','!='];
                // var tlk_supplier_search_def = [[],[]];
                function supplier_arr() { return [[],[]]; }
                var tlk_supplier_search     = new supplier_arr();
                $('body').on("hidden.bs.modal", "#modalSupplier", function(e) {tlk_supplier.clear(); tlk_supplier.draw(); tlk_supplier_search = new supplier_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalSupplier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-lg" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Supplier</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQuerySupplier">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQuerySupplier" class="form-control" placeholder="Kode/Nama Supplier">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">Kode</th>';
                html += '                                        <th class="text-center">Nama Supplier</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodySupplier" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihSupplier" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#supplier').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalSupplier').modal('show');
                        $('#txtQuerySupplier').val($(this).val());
                        if(tlk_supplier_query != $(this).val()) {
                            // tlk_supplier_search = tlk_supplier_search_def;
                            tlk_supplier_search = new supplier_arr();
                        }
                        tlk_supplier_query = $(this).val();

                        search_supplier();
                        return false;
                    }
                });

                $('#txtQuerySupplier').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_supplier_query = $(this).val();
                        search_supplier(); $(this).blur();
                        return false;
                    }
                });

                $('#modalSupplier').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_supplier();
                    }
                }).on('click', '#btnPilihSupplier', function(){
                    pilih_supplier();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_supplier();
                });

                $('#modalSupplier tbody').on('click', 'tr', function(){
                    // fokus = 'lookupsupplier';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_supplier_text == 'show'){
                            $(".lu-supplier-textfilter").contextMenu("hide");
                            search_supplier();
                        }
                        if(tlk_supplier_empty == 'show'){
                            $(".lu-supplier-emptyfilter").contextMenu("hide");
                            search_supplier();
                        }
                    }
                });

                // Data Tables
                tlk_supplier = $('#modalSupplier table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "kode", "className": "textfilter" },
                        { "data" : "nama", "className": "textfilter" },
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_supplier_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalSupplier tbody td.textfilter',
                    className: 'lu-supplier-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_supplier_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-supplier-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_supplier_text = 'hide';
                            tlk_supplier_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_supplier_search[tlk_supplier_idx] = [];
                            }else{
                                var count = $.map(tlk_supplier_search[tlk_supplier_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_supplier_search[tlk_supplier_idx] = [];
                                tlk_supplier_search[tlk_supplier_idx].push({filter : tlk_supplier_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalSupplier tbody td.dataTables_empty',
                    className: 'lu-supplier-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_supplier_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-supplier-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_supplier_empty = 'hide';
                            tlk_supplier_idx   = $this.index();

                            // Clear First
                            // tlk_supplier_search = tlk_supplier_search_def;
                            tlk_supplier_search = new supplier_arr();

                            if(contextData.name == "") {
                                tlk_supplier_search[tlk_supplier_idx] = [];
                            }else{
                                var count = $.map(tlk_supplier_search[tlk_supplier_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_supplier_search[tlk_supplier_idx] = [];
                                tlk_supplier_search[tlk_supplier_idx].push({filter : tlk_supplier_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_supplier(){
                    if (tlk_supplier.row('.selected').data().length) {
                        swal("Ups!", "Supplier belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_supplier.row('.selected').data();
                        if(data.id){
                            $('#supplierid').val(data.id);
                            $('#supplier').val(data.nama);
                        }else{
                            $('#supplierid').val('');
                            $('#supplier').val('');
                        }

                        $('#modalSupplier').modal('hide');
                        $('#supplierid').change();
                        $('#supplier').focus();
                    }
                }

                function search_supplier(){
                    $.ajax({
                        type   : 'GET',
                        url    : '{{route("lookup.getsupplier",[null,null])}}/{{session('subcabang')}}'+ ((tlk_supplier_query) ? '/'+encodeURIComponent(tlk_supplier_query) : ''),
                        data   : {filter : tlk_supplier_search},
                        success: function(data){
                            var data_parse = JSON.parse(data);
                            tlk_supplier.clear();
                            tlk_supplier.rows.add(data_parse);
                            tlk_supplier.draw();

                            setTimeout(function(){
                                tlk_supplier.columns.adjust();
                            }, 100);

                            $('#modalSupplier').focus();
                            fokus = 'modalSupplier';
                        },
                        error: function(data){
                            tlk_supplier.clear();
                            tlk_supplier.columns.adjust();
                        }
                    });
                }
            }

            function lookupexpedisi(){
                var tlk_expedisi, tlk_expedisi_idx;
                var tlk_expedisi_query      = '';
                var tlk_expedisi_text       = 'hide';
                var tlk_expedisi_empty      = 'hide';
                var tlk_expedisi_filter     = ['=','!='];
                // var tlk_expedisi_search_def = [[],[]];
                function expedisi_arr() { return [[],[]]; }
                var tlk_expedisi_search     = new expedisi_arr();
                $('body').on("hidden.bs.modal", "#modalExpedisi", function(e) {tlk_expedisi.clear(); tlk_expedisi.draw(); tlk_expedisi_search = new expedisi_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalExpedisi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-lg" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Expedisi</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryExpedisi">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQueryExpedisi" class="form-control" placeholder="Kode/Nama expedisi">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">Kode</th>';
                html += '                                        <th class="text-center">Nama expedisi</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodyExpedisi" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihexpedisi" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#expedisi').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalExpedisi').modal('show');
                        $('#txtQueryExpedisi').val($(this).val());
                        if(tlk_expedisi_query != $(this).val()) {
                            // tlk_expedisi_search = tlk_expedisi_search_def;
                            tlk_expedisi_search = new expedisi_arr();
                        }
                        tlk_expedisi_query = $(this).val();
                        // xtipe_expedisi = $('#tipeexpedisi').val();

                        search_expedisi();
                        return false;
                    }
                });

                $('#txtQueryExpedisi').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_expedisi_query = $(this).val();
                        // xtipe_expedisi = $('#tipeexpedisi').val();
                        search_expedisi(); $(this).blur();
                        return false;
                    }
                });

                $('#modalExpedisi').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_expedisi();
                    }
                }).on('click', '#btnPilihexpedisi', function(){
                    pilih_expedisi();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_expedisi();
                });

                $('#modalExpedisi tbody').on('click', 'tr', function(){
                    // fokus = 'lookupexpedisi';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_expedisi_text == 'show'){
                            $(".lu-expedisi-textfilter").contextMenu("hide");
                            search_expedisi();
                        }
                        if(tlk_expedisi_empty == 'show'){
                            $(".lu-expedisi-emptyfilter").contextMenu("hide");
                            search_expedisi();
                        }
                    }
                });

                // Data Tables
                tlk_expedisi = $('#modalExpedisi table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "kodeexpedisi", "className": "textfilter" },
                        { "data" : "namaexpedisi", "className": "textfilter" },
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_expedisi_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalExpedisi tbody td.textfilter',
                    className: 'lu-expedisi-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_expedisi_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-expedisi-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_expedisi_text = 'hide';
                            tlk_expedisi_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_expedisi_search[tlk_expedisi_idx] = [];
                            }else{
                                var count = $.map(tlk_expedisi_search[tlk_expedisi_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_expedisi_search[tlk_expedisi_idx] = [];
                                tlk_expedisi_search[tlk_expedisi_idx].push({filter : tlk_expedisi_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalExpedisi tbody td.dataTables_empty',
                    className: 'lu-expedisi-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_expedisi_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-expedisi-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_expedisi_empty = 'hide';
                            tlk_expedisi_idx   = $this.index();

                            // Clear First
                            tlk_expedisi_search = null;
                            // tlk_expedisi_search = tlk_expedisi_search_def;
                            tlk_expedisi_search = new expedisi_arr();

                            if(contextData.name == "") {
                                tlk_expedisi_search[tlk_expedisi_idx] = [];
                                console.log(tlk_expedisi_search);
                            }else{
                                var count = $.map(tlk_expedisi_search[tlk_expedisi_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_expedisi_search[tlk_expedisi_idx] = [];
                                tlk_expedisi_search[tlk_expedisi_idx].push({filter : tlk_expedisi_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_expedisi(){
                    if (tlk_expedisi.row('.selected').data().length) {
                        swal("Ups!", "Expedisi belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_expedisi.row('.selected').data();
                        if(data.id){
                            $('#expedisiid').val(data.id);
                            $('#expedisi').val(data.namaexpedisi);
                            $('#kodeexpedisi').val(data.kodeexpedisi);

                            $.ajax({
                                type    : 'GET',
                                url     : '{{route("orderpenjualan.gettempokirim",[null,null])}}/' + data.id + '/' + $('#tokoid').val(),
                                dataType: 'json',
                                success : function(data){
                                    $('#tempokirim').val(data.jwkirim);
                                    $('#tempokirim').attr('readonly', true).attr('tabindex',-1);
                                }
                            });

                        }else{
                            $('#expedisiid').val('');
                            $('#expedisi').val('');
                            $('#kodeexpedisi').val('');
                        }

                        $('#modalExpedisi').modal('hide');
                        $('#expedisiid').change();
                        $('#expedisi').focus();
                    }
                }

                function search_expedisi(){
                    $.ajax({
                        type   : 'GET',
                        url    : '{{route("lookup.getexpedisi",[null,null])}}/{{session('subcabang')}}/' + encodeURIComponent(tlk_expedisi_query),
                        data   : {filter : tlk_expedisi_search},
                        success: function(data){
                            // var data_parse = JSON.parse(data);
                            tlk_expedisi.clear();
                            tlk_expedisi.rows.add(data);
                            tlk_expedisi.draw();

                            setTimeout(function(){
                                tlk_expedisi.columns.adjust();
                            }, 100);

                            $('#modalExpedisi').focus();
                           fokus = '#modalExpedisi';
                        },
                        error: function(data){
                            tlk_expedisi.clear();
                            tlk_expedisi.columns.adjust();
                        }
                    });
                }
            }

            function lookuparmada(){
                var tlk_armada, tlk_armada_idx;
                var tlk_armada_query      = '';
                var tlk_armada_text       = 'hide';
                var tlk_armada_empty      = 'hide';
                var tlk_armada_filter     = ['=','!='];
                // var tlk_armada_search_def = [[],[]];
                function armada_arr() { return [[],[]]; }
                var tlk_armada_search     = new armada_arr();
                $('body').on("hidden.bs.modal", "#modalArmada", function(e) {tlk_armada.clear(); tlk_armada.draw(); tlk_armada_search = new armada_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalArmada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-lg" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup armada</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryArmada">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQueryArmada" class="form-control" placeholder="Kode/Nama armada">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">Kode</th>';
                html += '                                        <th class="text-center">Nama armada</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodyArmada" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPiliharmada" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#armadakirim').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalArmada').modal('show');
                        $('#txtQueryArmada').val($(this).val());
                        if(tlk_armada_query != $(this).val()) {
                            // tlk_armada_search = tlk_armada_search_def;
                            tlk_armada_search = new armada_arr();
                        }
                        tlk_armada_query = $(this).val();
                        // xtipe_armada = $('#tipearmada').val();

                        search_armada();
                        return false;
                    }
                });

                $('#txtQueryArmada').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_armada_query = $(this).val();
                        // xtipe_armada = $('#tipearmada').val();
                        search_armada(); $(this).blur();
                        return false;
                    }
                });

                $('#modalArmada').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_armada();
                    }
                }).on('click', '#btnPiliharmada', function(){
                    pilih_armada();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_armada();
                });

                $('#modalArmada tbody').on('click', 'tr', function(){
                    // fokus = 'lookuparmada';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_armada_text == 'show'){
                            $(".lu-armada-textfilter").contextMenu("hide");
                            search_armada();
                        }
                        if(tlk_armada_empty == 'show'){
                            $(".lu-armada-emptyfilter").contextMenu("hide");
                            search_armada();
                        }
                    }
                });

                // Data Tables
                tlk_armada = $('#modalArmada table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "nomorpolisi", "className": "textfilter" },
                        { "data" : "namakendaraan", "className": "textfilter" },
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_armada_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalArmada tbody td.textfilter',
                    className: 'lu-armada-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_armada_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-armada-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_armada_text = 'hide';
                            tlk_armada_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_armada_search[tlk_armada_idx] = [];
                            }else{
                                var count = $.map(tlk_armada_search[tlk_armada_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_armada_search[tlk_armada_idx] = [];
                                tlk_armada_search[tlk_armada_idx].push({filter : tlk_armada_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalArmada tbody td.dataTables_empty',
                    className: 'lu-armada-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_armada_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-armada-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_armada_empty = 'hide';
                            tlk_armada_idx   = $this.index();

                            // Clear First
                            tlk_armada_search = null;
                            // tlk_armada_search = tlk_armada_search_def;
                            tlk_armada_search = new armada_arr();

                            if(contextData.name == "") {
                                tlk_armada_search[tlk_armada_idx] = [];
                                console.log(tlk_armada_search);
                            }else{
                                var count = $.map(tlk_armada_search[tlk_armada_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_armada_search[tlk_armada_idx] = [];
                                tlk_armada_search[tlk_armada_idx].push({filter : tlk_armada_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_armada(){
                    if (tlk_armada.row('.selected').data() == undefined) {
                        swal("Ups!", "Armada belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_armada.row('.selected').data();
                        if(data.id){
                            $('#armadakirimId').val(data.id);
                            $('#armadakirim').val(data.namakendaraan);
                            $('#kmberangkat').val(data.kmtempuh);
                        }else{
                            $('#armadakirimId').val('');
                            $('#armadakirim').val('');
                            $('#kmberangkat').val('');
                        }

                        $('#modalArmada').modal('hide');
                        $('#armadakirimId').change();
                        $('#armadakirim').focus();
                    }
                }

                function search_armada(){
                    $.ajax({
                        type   : 'POST',
                        url    : '{{route("lookup.armadakirim")}}',
                        data   : {_token  :"{{ csrf_token() }}", katakunci : encodeURIComponent(tlk_armada_query), filter : tlk_armada_search},
                        success: function(data){
                            var data = JSON.parse(data);
                            tlk_armada.clear();
                            tlk_armada.rows.add(data);
                            tlk_armada.draw();

                            setTimeout(function(){
                                tlk_armada.columns.adjust();
                            }, 100);

                            $('#modalArmada').focus();
                           fokus = '#modalArmada';
                        },
                        error: function(data){
                            tlk_armada.clear();
                            tlk_armada.columns.adjust();
                        }
                    });
                }
            }

            function lookupkategoriretur(){
                var tlk_kategoriretur, tlk_kategoriretur_idx, xtipe_kategoriretur, xtipe_kategoriretur_2;
                var tlk_kategoriretur_query      = '';
                var tlk_kategoriretur_text       = 'hide';
                var tlk_kategoriretur_empty      = 'hide';
                var tlk_kategoriretur_filter     = ['=','!='];
                // var tlk_kategoriretur_search_def = [[],[]];
                function kategoriretur_arr() { return [[],[]]; }
                var tlk_kategoriretur_search     = new kategoriretur_arr();
                $('body').on("hidden.bs.modal", "#modalKategoriretur", function(e) {tlk_kategoriretur.clear(); tlk_kategoriretur.draw(); tlk_kategoriretur_search = new kategoriretur_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalKategoriretur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-lg" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup Kategori Retur</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQueryKategoriretur">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQueryKategoriretur" class="form-control" placeholder="Kode/Nama Kategori Retur">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">Kode</th>';
                html += '                                        <th class="text-center">Nama kategoriretur</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodyKategoriretur" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihkategoriretur" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#kategoriprb').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalKategoriretur').modal('show');
                        $('#txtQueryKategoriretur').val($(this).val());
                        if(tlk_kategoriretur_query != $(this).val()) {
                            // tlk_kategoriretur_search = tlk_kategoriretur_search_def;
                            tlk_kategoriretur_search = new kategoriretur_arr();
                        }
                        tlk_kategoriretur_query = $(this).val();
                        xtipe_kategoriretur = $(this).attr('id');

                        search_kategoriretur();
                        return false;
                    }
                });

                $('#koreksikategoriprb').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalKategoriretur').modal('show');
                        $('#txtQueryKategoriretur').val($(this).val());
                        if(tlk_kategoriretur_query != $(this).val()) {
                            // tlk_kategoriretur_search = tlk_kategoriretur_search_def;
                            tlk_kategoriretur_search = new kategoriretur_arr();
                        }
                        tlk_kategoriretur_query = $(this).val();
                        xtipe_kategoriretur = $(this).attr('id');

                        search_kategoriretur();
                        return false;
                    }
                });

                $('#kategorirpj').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalKategoriretur').modal('show');
                        $('#txtQueryKategoriretur').val($(this).val());
                        if(tlk_kategoriretur_query != $(this).val()) {
                            // tlk_kategoriretur_search = tlk_kategoriretur_search_def;
                            tlk_kategoriretur_search = new kategoriretur_arr();
                        }
                        tlk_kategoriretur_query = $(this).val();
                        xtipe_kategoriretur = $(this).attr('id');

                        search_kategoriretur();
                        return false;
                    }
                });

                $('#koreksikategorirpj').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalKategoriretur').modal('show');
                        $('#txtQueryKategoriretur').val($(this).val());
                        if(tlk_kategoriretur_query != $(this).val()) {
                            // tlk_kategoriretur_search = tlk_kategoriretur_search_def;
                            tlk_kategoriretur_search = new kategoriretur_arr();
                        }
                        tlk_kategoriretur_query = $(this).val();
                        xtipe_kategoriretur = $(this).attr('id');

                        search_kategoriretur();
                        return false;
                    }
                });

                $("#tableupdate").on('click', '.editkategorirj', function(){
                    $('#modalKategoriretur').modal('show');
                    $('#txtQueryKategoriretur').val($(this).val());
                    if(tlk_kategoriretur_query != $(this).val()) {
                        // tlk_kategoriretur_search = tlk_kategoriretur_search_def;
                        tlk_kategoriretur_search = new kategoriretur_arr();
                    }
                    tlk_kategoriretur_query = $(this).val();
                    xtipe_kategoriretur     = $(this).parent().attr('id');
                    xtipe_kategoriretur_2   = 'tableupdate';

                    search_kategoriretur();
                    return false;
                });


                $('#txtQueryKategoriretur').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_kategoriretur_query = $(this).val();
                        search_kategoriretur(); $(this).blur();
                        return false;
                    }
                });

                $('#modalKategoriretur').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_kategoriretur();
                    }
                }).on('click', '#btnPilihkategoriretur', function(){
                    pilih_kategoriretur();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_kategoriretur();
                });

                $('#modalKategoriretur tbody').on('click', 'tr', function(){
                    // fokus = 'lookupkategoriretur';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_kategoriretur_text == 'show'){
                            $(".lu-kategoriretur-textfilter").contextMenu("hide");
                            search_kategoriretur();
                        }
                        if(tlk_kategoriretur_empty == 'show'){
                            $(".lu-kategoriretur-emptyfilter").contextMenu("hide");
                            search_kategoriretur();
                        }
                    }
                });

                // Data Tables
                tlk_kategoriretur = $('#modalKategoriretur table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "kode", "className": "textfilter" },
                        { "data" : "nama", "className": "textfilter" },
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_kategoriretur_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalKategoriretur tbody td.textfilter',
                    className: 'lu-kategoriretur-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_kategoriretur_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-kategoriretur-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_kategoriretur_text = 'hide';
                            tlk_kategoriretur_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_kategoriretur_search[tlk_kategoriretur_idx] = [];
                            }else{
                                var count = $.map(tlk_kategoriretur_search[tlk_kategoriretur_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_kategoriretur_search[tlk_kategoriretur_idx] = [];
                                tlk_kategoriretur_search[tlk_kategoriretur_idx].push({filter : tlk_kategoriretur_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalKategoriretur tbody td.dataTables_empty',
                    className: 'lu-kategoriretur-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_kategoriretur_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-kategoriretur-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_kategoriretur_empty = 'hide';
                            tlk_kategoriretur_idx   = $this.index();

                            // Clear First
                            tlk_kategoriretur_search = null;
                            // tlk_kategoriretur_search = tlk_kategoriretur_search_def;
                            tlk_kategoriretur_search = new kategoriretur_arr();

                            if(contextData.name == "") {
                                tlk_kategoriretur_search[tlk_kategoriretur_idx] = [];
                                console.log(tlk_kategoriretur_search);
                            }else{
                                var count = $.map(tlk_kategoriretur_search[tlk_kategoriretur_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_kategoriretur_search[tlk_kategoriretur_idx] = [];
                                tlk_kategoriretur_search[tlk_kategoriretur_idx].push({filter : tlk_kategoriretur_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_kategoriretur(){
                    if (tlk_kategoriretur.row('.selected').data().length) {
                        swal("Ups!", "Kategori Retur belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_kategoriretur.row('.selected').data();
                        if(xtipe_kategoriretur_2 == 'tableupdate') {
                            $("tr#"+xtipe_kategoriretur).find("input[name^=updatekategoriidrpj]").val(data.id);
                            $("tr#"+xtipe_kategoriretur).find("td:nth-child(10)").html(data.nama);
                            $('#modalKategoriretur').modal('hide');
                        }else{
                            if(data.id){
                                $('#'+xtipe_kategoriretur+'id').val(data.id);
                                $('#'+xtipe_kategoriretur).val(data.nama);
                            }else{
                                $('#'+xtipe_kategoriretur+'id').val('');
                                $('#'+xtipe_kategoriretur).val('');
                            }

                            $('#modalKategoriretur').modal('hide');
                            $('#'+xtipe_kategoriretur+'id').change();
                            $('#'+xtipe_kategoriretur).focus();
                        }

                    }
                }

                function search_kategoriretur(){
                    $.ajax({
                        type   : 'GET',
                        url    : '{{route("lookup.getkategoriretur",[null])}}/' + encodeURIComponent(tlk_kategoriretur_query),
                        data   : {filter : tlk_kategoriretur_search},
                        success: function(data){
                            var data_parse = JSON.parse(data);
                            tlk_kategoriretur.clear();
                            tlk_kategoriretur.rows.add(data_parse);
                            tlk_kategoriretur.draw();

                            setTimeout(function(){
                                tlk_kategoriretur.columns.adjust();
                            }, 100);

                            $('#modalKategoriretur').focus();
                           fokus = '#modalKategoriretur';
                        },
                        error: function(data){
                            tlk_kategoriretur.clear();
                            tlk_kategoriretur.columns.adjust();
                        }
                    });
                }
            }

            function lookupsuratjalan(){
                var tlk_suratjalan, tlk_suratjalan_idx;
                var tlk_suratjalan_query      = '';
                var tlk_suratjalan_text       = 'hide';
                var tlk_suratjalan_empty      = 'hide';
                var tlk_suratjalan_filter     = ['=','!='];
                // var tlk_suratjalan_search_def = [[],[]];
                function suratjalan_arr() { return [[],[]]; }
                var tlk_suratjalan_search     = new suratjalan_arr();
                $('body').on("hidden.bs.modal", "#modalSuratjalan", function(e) {tlk_suratjalan.clear(); tlk_suratjalan.draw(); tlk_suratjalan_search = new suratjalan_arr();});
                var html = '';

                // Html Lookup
                html += '<div class="modal fade" id="modalSuratjalan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">';
                html += '    <div class="modal-dialog modal-lg" role="document">';
                html += '        <div class="modal-content">';
                html += '            <div class="modal-header">';
                html += '                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '                <h4 class="modal-title" id="myModalLabel">Lookup suratjalan</h4>';
                html += '            </div>';
                html += '            <form class="form-horizontal" method="post">';
                html += '                <div class="modal-body">';
                html += '                    <div class="row">';
                html += '                        <div class="form-group">';
                html += '                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtQuerySuratjalan">Masukkan kata kunci pencarian</label>';
                html += '                            <div class="col-md-6 col-sm-6 col-xs-12">';
                html += '                                <input type="text" id="txtQuerySuratjalan" class="form-control" placeholder="Kode/Nama suratjalan">';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                    <div class="row">';
                html += '                        <div class="col-xs-12">';
                html += '                            <table class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%">';
                html += '                                <thead>';
                html += '                                    <tr>';
                html += '                                        <th class="text-center">No SJ</th>';
                html += '                                        <th class="text-center">Tgl SJ</th>';
                html += '                                        <th class="text-center">Nama Toko</th>';
                html += '                                        <th class="text-center">Alamat</th>';
                html += '                                    </tr>';
                html += '                                </thead>';
                html += '                                <tbody id="tbodySuratjalan" class="tbodySelect"></tbody>';
                html += '                            </table>';
                html += '                        </div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <div class="modal-footer">';
                html += '                    <button type="button" id="btnPilihsuratjalan" class="btn btn-primary">Pilih</button>';
                html += '                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>';
                html += '                </div>';
                html += '            </form>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

                $('body').append(html);

                // Trigger Lookup
                $('#nosj').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        $('#modalSuratjalan').modal('show');
                        $('#txtQuerySuratjalan').val($(this).val());
                        if(tlk_suratjalan_query != $(this).val()) {
                            // tlk_suratjalan_search = tlk_suratjalan_search_def;
                            tlk_suratjalan_search = new suratjalan_arr();
                        }
                        tlk_suratjalan_query = $(this).val();
                        // xtipe_suratjalan = $('#tipesuratjalan').val();

                        search_suratjalan();
                        return false;
                    }
                });

                $('#txtQuerySuratjalan').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        tlk_suratjalan_query = $(this).val();
                        // xtipe_suratjalan = $('#tipesuratjalan').val();
                        search_suratjalan(); $(this).blur();
                        return false;
                    }
                });

                $('#modalSuratjalan').on('keypress', function(e){
                    if (e.keyCode == '13') {
                        e.preventDefault();
                        pilih_suratjalan();
                    }
                }).on('click', '#btnPilihsuratjalan', function(){
                    pilih_suratjalan();
                }).on('dblclick', 'tbody tr', function(){
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    pilih_suratjalan();
                });

                $('#modalSuratjalan tbody').on('click', 'tr', function(){
                    // fokus = 'lookupsuratjalan';
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                });

                $(document).on("keydown", function(e){
                    ele = document.activeElement;
                    if(e.keyCode == 13){
                        if(tlk_suratjalan_text == 'show'){
                            $(".lu-suratjalan-textfilter").contextMenu("hide");
                            search_suratjalan();
                        }
                        if(tlk_suratjalan_empty == 'show'){
                            $(".lu-suratjalan-emptyfilter").contextMenu("hide");
                            search_suratjalan();
                        }
                    }
                });

                // Data Tables
                tlk_suratjalan = $('#modalSuratjalan table').DataTable({
                    dom     : 'lrt',
                    keys    : true,
                    // select  : true,
                    scrollY : "50vh",
                    scrollX : true,
                    scroller: { loadingIndicator: true },
                    order   : [[ 1, 'asc' ]],
                    columns : [
                        { "data" : "nosj", "className": "textfilter" },
                        { "data" : "tglsj", "className": "textfilter" },
                        { "data" : "namatoko", "className": "textfilter" },
                        { "data" : "alamat", "className": "textfilter" },
                    ],
                }).on('key-focus', function (e, dt, cell) {
                    dt.rows().deselect();
                    dt.row( cell.index().row ).select();
                }).on('select', function ( e, dt, type, indexes ){
                    tlk_suratjalan_idx = indexes;
                });

                // Trigger Filter
                $.contextMenu({
                    selector: '#modalSuratjalan tbody td.textfilter',
                    className: 'lu-suratjalan-textfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_suratjalan_text = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-suratjalan-textfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_suratjalan_text = 'hide';
                            tlk_suratjalan_idx  = $this.index();
                            if(contextData.name == "") {
                                tlk_suratjalan_search[tlk_suratjalan_idx] = [];
                            }else{
                                var count = $.map(tlk_suratjalan_search[tlk_suratjalan_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_suratjalan_search[tlk_suratjalan_idx] = [];
                                tlk_suratjalan_search[tlk_suratjalan_idx].push({filter : tlk_suratjalan_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                $.contextMenu({
                    selector: '#modalSuratjalan tbody td.dataTables_empty',
                    className: 'lu-suratjalan-emptyfilter',
                    items: {
                        name: { name: "Text", type: 'text', value: "", },
                        filter: { name: "Filter", type: 'select', options: {1: '=', 2: '!='}, selected: 1 },
                        key: { name: "Cancel", callback: $.noop }
                    },
                    events: {
                        show: function(opt) {
                            tlk_suratjalan_empty = 'show';
                            $(document).off('focusin.modal');
                            setTimeout(function(){
                                $('.lu-suratjalan-emptyfilter input[name="context-menu-input-name"]').focus();
                            }, 100);
                        },
                        hide: function(opt) {
                            var $this = this;
                            var contextData = $.contextMenu.getInputValues(opt, $this.data());

                            tlk_suratjalan_empty = 'hide';
                            tlk_suratjalan_idx   = $this.index();

                            // Clear First
                            tlk_suratjalan_search = null;
                            // tlk_suratjalan_search = tlk_suratjalan_search_def;
                            tlk_suratjalan_search = new suratjalan_arr();

                            if(contextData.name == "") {
                                tlk_suratjalan_search[tlk_suratjalan_idx] = [];
                                console.log(tlk_suratjalan_search);
                            }else{
                                var count = $.map(tlk_suratjalan_search[tlk_suratjalan_idx], function(n, i) { return i; }).length;
                                if(count >= 4) tlk_suratjalan_search[tlk_suratjalan_idx] = [];
                                tlk_suratjalan_search[tlk_suratjalan_idx].push({filter : tlk_suratjalan_filter[contextData.filter-1] , text : contextData.name});
                            }
                        },
                    }
                });

                // Function
                function pilih_suratjalan(){
                    if (tlk_suratjalan.row('.selected').data() == undefined) {
                        swal("Ups!", "suratjalan belum dipilih.", "error");
                        return false;
                    }else {
                        var data = tlk_suratjalan.row('.selected').data();
                        if(data.id){
                            $('#nosj').val(data.nosj);
                            $('#sjId').val(data.id);

                            $.ajax({
                                type    : 'GET',
                                url     : '{{route("suratjalan.view")}}',
                                data    : {id : data.id},
                                dataType: 'json',
                                success : function(d){
                                    $('#modalSJInsert #tglsj').val(d.tglsj);
                                    $('#modalSJInsert #toko').val(d.toko);
                                    $('#modalSJInsert #alamat').val(d.alamat);
                                    $('#modalSJInsert #kota').val(d.kota);
                                    $('#modalSJInsert #daerah').val(d.daerah);
                                    $('#modalSJInsert #wilid').val(d.wilid);
                                    $('#modalSJInsert #nit').val(d.nit);
                                    $('#modalSJInsert #keterangankoli').val(d.titipanketerangan);
                                    $('#modalSJInsert #dari').val(d.titipandari);
                                    $('#modalSJInsert #untuk').val(d.titipanuntuk);
                                    $('#modalSJInsert #alamattitipan').val(d.titipanalamat);
                                    $('#modalSJInsert #notelp').val(d.titipannotelepon);
                                    $('#modalSJInsert #totalkoli').val(d.totalkoli);
                                },
                                error: function(d){
                                    console.log(d);
                                }
                            });
                        }else{
                            $('#nosj').val('');
                            $('#sjId').val('');
                            $('#modalSJInsert #tglsj').val('');
                            $('#modalSJInsert #toko').val('');
                            $('#modalSJInsert #alamat').val('');
                            $('#modalSJInsert #kota').val('');
                            $('#modalSJInsert #daerah').val('');
                            $('#modalSJInsert #wilid').val('');
                            $('#modalSJInsert #nit').val('');
                            $('#modalSJInsert #keterangankoli').val('');
                            $('#modalSJInsert #dari').val('');
                            $('#modalSJInsert #untuk').val('');
                            $('#modalSJInsert #alamattitipan').val('');
                            $('#modalSJInsert #notelp').val('');
                            $('#modalSJInsert #totalkoli').val('');
                        }

                        $('#modalSuratjalan').modal('hide');
                        $('#sjId').change();
                        $('#nosj').focus();
                    }
                }

                function search_suratjalan(){
                    $.ajax({
                        type   : 'POST',
                        url    : '{{route("lookup.sj")}}',
                        data   : {_token  :"{{ csrf_token() }}", katakunci : encodeURIComponent(tlk_suratjalan_query), filter : tlk_suratjalan_search},
                        success: function(data){
                            var data = JSON.parse(data);
                            tlk_suratjalan.clear();
                            tlk_suratjalan.rows.add(data);
                            tlk_suratjalan.draw();

                            setTimeout(function(){
                                tlk_suratjalan.columns.adjust();
                            }, 100);

                            $('#modalSuratjalan').focus();
                           fokus = '#modalSuratjalan';
                        },
                        error: function(data){
                            tlk_suratjalan.clear();
                            tlk_suratjalan.columns.adjust();
                        }
                    });
                }
            }

        </script>

        @stack('scripts')

        <script type="text/javascript">
            // Fix Bootstrap SweetAlert
            const fixBootstrapModal = () => {
                const modalNode = $('.modal[tabindex="-1"]');
                if (!modalNode) return;

                $.each(modalNode, function (i,modal) {
                    modal.removeAttribute('tabindex');
                    modal.classList.add('js-swal-fixed');
                });
            };

            // call this before hiding SweetAlert (inside done callback):
            const restoreBootstrapModal = () => {
                const modalNode = $('.modal.js-swal-fixed');
                if (!modalNode) return;

                $.each(modalNode, function (i,modal) {
                    modal.setAttribute('tabindex', '-1');
                    modal.classList.remove('js-swal-fixed');
                });
            };

            // Fix Bootstrap Modal Stack
            $('.modal').on('hidden.bs.modal', function (e) {
                if($('.modal').hasClass('in')) {
                    $('body').addClass('modal-open');
                }
            });

            // $(document.body).on("keydown", function(e){
            //     ele = document.activeElement;

            //    if(e.keyCode==38){
            //     //cek focus table 1
            //         if (fokus=='lookupbarang') {
            //             var selectedrow = $('#tbodyBarang').find('.selected');
            //             if (selectedrow.prev().length !== 0) {
            //                 selectedrow.removeClass('selected');
            //                 selectedrow.prev().addClass('selected');
            //             }
            //         }else if (fokus=='header') {
            //              table.row(table_index).deselect();
            //              table.row(table_index-1).select();
            //         }else if(fokus=='detail'){
            //             var tr= $(table2.row(parseInt(table2_index)).node()).prev('tr');
            //             table2.row(parseInt(table2_index)).deselect();
            //             table2.row(parseInt(table2.row(tr).index())).select();
            //         }else if(fokus=='subdetail'){
            //             var tr= $(table3.row(parseInt(table3_index)).node()).prev('tr');
            //             table3.row(parseInt(table3_index)).deselect();
            //             table3.row(parseInt(table3.row(tr).index())).select();
            //         }else if(fokus=='subsubdetail'){
            //             var tr= $(table4.row(parseInt(table4_index)).node()).prev('tr');
            //             table4.row(parseInt(table4_index)).deselect();
            //             table4.row(parseInt(table4.row(tr).index())).select();
            //         }
            //     }
            //     else if(e.keyCode==40){
            //         if (fokus=='lookupbarang') {
            //             var selectedrow = $('#tbodyBarang').find('.selected');
            //             if (selectedrow.next().length !== 0) {
            //                 selectedrow.removeClass('selected');
            //                 selectedrow.next().addClass('selected');
            //             }
            //         }else if (fokus=='header') {
            //             table.row(table_index).deselect();
            //             table.rows(parseInt(table_index)+1).select();
            //         }else if(fokus=='detail'){
            //             var tr= $(table2.row(parseInt(table2_index)).node()).next('tr');
            //             table2.row(parseInt(table2_index)).deselect();
            //             table2.row(parseInt(table2.row(tr).index())).select();
            //         }else if(fokus=='subdetail'){
            //             var tr= $(table3.row(parseInt(table3_index)).node()).next('tr');
            //             table3.row(parseInt(table3_index)).deselect();
            //             table3.row(parseInt(table3.row(tr).index())).select();
            //         }else if(fokus=='subsubdetail'){
            //             var tr= $(table4.row(parseInt(table4_index)).node()).next('tr');
            //             table4.row(parseInt(table4_index)).deselect();
            //             table4.row(parseInt(table4.row(tr).index())).select();
            //         }
            //     }
            // });

            // keys: {keys: [ 13 /* ENTER */, 38 /* UP */, 40 /* DOWN */ ]},
            // Handle event when cell gains focus
            $('#table1').on('key-focus.dt', function(e, datatable, cell){
                table_index = table.row(cell.index().row).id();
                table.row(cell.index().row).select();
            });
            $('#table2').on('key-focus.dt', function(e, datatable, cell){
                table2_index = table2.row(cell.index().row).id();
                table2.row(cell.index().row).select();
            });
            $('#table3').on('key-focus.dt', function(e, datatable, cell){
                table3_index = table3.row(cell.index().row).id();
                table3.row(cell.index().row).select();
            });
            $('#table4').on('key-focus.dt', function(e, datatable, cell){
                table4_index = table4.row(cell.index().row).id();
                table4.row(cell.index().row).select();
            });

            // // Handle click on table cell
            // $('#table1').on('click', 'tbody td.menufilter', function(e){
            $('#table1').on('click', 'tbody td', function(e){
                e.stopPropagation();
                var rowIdx = table.cell(this).index().row;
                table_index = table.row(rowIdx).id();
                table.row(rowIdx).select();

                if($(e.target).hasClass('dropdown-toggle')){
                    $(e.target).dropdown("toggle");
                }else if($(e.target).hasClass('caret')){
                    $(e.target).parent().dropdown("toggle");
                }
            });
            $('#table2').on('click', 'tbody td', function(e){
                e.stopPropagation();
                var rowIdx = table2.cell(this).index().row;
                table2_index = table2.row(rowIdx).id();
                table2.row(rowIdx).select();
            });
            $('#table3').on('click', 'tbody td', function(e){
                e.stopPropagation();
                var rowIdx = table3.cell(this).index().row;
                table3_index = table3.row(rowIdx).id();
                table3.row(rowIdx).select();
            });
            $('#table4').on('click', 'tbody td', function(e){
                e.stopPropagation();
                var rowIdx = table4.cell(this).index().row;
                table4_index = table4.row(rowIdx).id();
                table4.row(rowIdx).select();
            });

            // // Handle event when cell looses focus
            // $('#table1').on('key-blur.dt', function(e, datatable, cell){
            //     // Deselect highlighted row
            //     // $(table.row(cell.index().row).node()).removeClass('selected');
            // });

            $('.modal').on('hidden.bs.modal', function () {
                if($('.modal.in').length == 0){
                    if($('#table3').length && table3_index) {
                        table3.cell('#'+table3_index,":eq(1)").focus();
                    }else if($('#table2').length && table2_index) {
                        table2.cell('#'+table2_index,":eq(1)").focus();
                    }else if($('#table1').length && table_index) {
                        table.cell('#'+table_index,":eq(1)").focus();
                    }
                }
            });

            // Shortcut
            function shortcutAction(selector) {
                if($('.modal.in').length == 0) {
                    if(typeof table4_index !== 'undefined' && table4_index != null
                        && $('#'+table4_index+' td.focus').length > 0 && $('#'+table4_index).find('.'+selector).length > 0) {
                        var btn = $('#'+table4_index).find('.'+selector);
                    }else if(typeof table3_index !== 'undefined' && table3_index != null
                        && $('#'+table3_index+' td.focus').length > 0 && $('#'+table3_index).find('.'+selector).length > 0) {
                        var btn = $('#'+table3_index).find('.'+selector);
                    }else if(typeof table2_index !== 'undefined' && table2_index != null
                        && $('#'+table2_index+' td.focus').length > 0 && $('#'+table2_index).find('.'+selector).length > 0) {
                        var btn = $('#'+table2_index).find('.'+selector);
                    }else if(typeof table_index !== 'undefined' && table_index != null
                        && $('#'+table_index+' td.focus').length > 0 && $('#'+table_index).find('.'+selector).length > 0) {
                        var btn = $('#'+table_index).find('.'+selector);
                    }else if($('#'+selector).length > 0) {
                        var btn = $('#'+selector);
                        btn.focus();
                    }else if($('[data-id="'+selector+'"]').length > 0) {
                        var btn = $('[data-id="'+selector+'"]');
                        btn.focus();
                    }else{
                        var btn = null;
                    }

                    if(btn == null){
                        // swal('Ups!', "Klik Gridview terlebih dahulu!",'error');
                    }else{
                        if(btn.attr('href') == undefined || btn.attr('href') == null || btn.attr('href') == '' || btn.attr('href') == '#'){ btn.click(); }
                        else{ window.open(btn.attr('href'),'_blank'); }
                    }
                }
            }

            var k = hotkeys.noConflict();
            k('insert', function(e) {
                e.preventDefault();
                if($('.modal.in').length == 0) {
                    e.preventDefault();
                    window.location = $('#skeyIns').attr('href');
                }
                return false;
            });
            k('delete', function(e) {
                e.preventDefault();
                shortcutAction('skeyDel');
                return false;
            });
            // Tambah Detail
            k('F1', function(e) {
                e.preventDefault();
                shortcutAction('skeyF1');
                return false;
            });
            // Edit / Update
            k('F2', function(e) {
                e.preventDefault();
                shortcutAction('skeyF2');
                return false;
            });
            // Cetak
            k('F3', function(e) {
                e.preventDefault();
                shortcutAction('skeyF3');
                return false;
            });
            // Cetak
            k('F4', function(e) {
                e.preventDefault();
                shortcutAction('skeyF4');
                return false;
            });
            // k('F5', function(e) {
            //     e.preventDefault();
            //     shortcutAction('skeyF5');
            //     return false;
            // });
            k('F6', function(e) {
                e.preventDefault();
                shortcutAction('skeyF6');
                return false;
            });
            k('F7', function(e) {
                e.preventDefault();
                shortcutAction('skeyF7');
                return false;
            });
            k('F8', function(e) {
                e.preventDefault();
                shortcutAction('skeyF8');
                return false;
            });
            k('F9', function(e) {
                e.preventDefault();
                shortcutAction('skeyF9');
                return false;
            });
            k('F10', function(e) {
                e.preventDefault();
                shortcutAction('skeyF10');
                return false;
            });
            k('F11', function(e) {
                e.preventDefault();
                shortcutAction('skeyF11');
                return false;
            });
            // k('F12', function(e) {
            //     e.preventDefault();
            //     shortcutAction('skeyF12');
            //     return false;
            // });

            // Pindah grid 1
            k('ctrl+shift+1', function(e) {
                e.preventDefault();
                if($('.modal.in').length == 0 && $('#table1').length > 0) {
                    if($('#table2').length > 0) { table2.cell.blur(); }
                    if($('#table3').length > 0) { table3.cell.blur(); }
                    if($('#table4').length > 0) { table4.cell.blur(); }

                    if(table_index && $('#'+table_index).length > 0) {
                        table.cell('#'+table_index,':eq(0)').focus();
                    }else{
                        table.cell(':eq(0)').focus();
                    }
                    $('#'+table_index+' td:first').addClass('focus');
                }
                return false;
            });
            // Pindah grid 2
            k('ctrl+shift+2', function(e) {
                e.preventDefault();
                if($('.modal.in').length == 0 && $('#table2').length > 0) {
                    if($('#table1').length > 0) { table.cell.blur(); }
                    if($('#table3').length > 0) { table3.cell.blur(); }
                    if($('#table4').length > 0) { table4.cell.blur(); }

                    if(table2_index && $('#'+table2_index).length > 0) {
                        table2.cell('#'+table2_index,':eq(0)').focus();
                    }else{
                        table2.cell(':eq(0)').focus();
                    }
                    $('#'+table2_index+' td:first').addClass('focus');
                }
                return false;
            });
            // Pindah grid 3
            k('ctrl+shift+3', function(e) {
                e.preventDefault();
                if($('.modal.in').length == 0 && $('#table3').length > 0) {
                    if($('#table1').length > 0) { table.cell.blur(); }
                    if($('#table2').length > 0) { table2.cell.blur(); }
                    if($('#table4').length > 0) { table4.cell.blur(); }

                    if(table3_index && $('#'+table3_index).length > 0) {
                        table3.cell('#'+table3_index,':eq(0)').focus();
                    }else{
                        table3.cell(':eq(0)').focus();
                    }
                    $('#'+table3_index+' td:first').addClass('focus');
                }
                return false;
            });
            // Pindah grid 4
            k('ctrl+shift+4', function(e) {
                e.preventDefault();
                if($('.modal.in').length == 0 && $('#table4').length > 0) {
                    if($('#table1').length > 0) { table.cell.blur(); }
                    if($('#table2').length > 0) { table2.cell.blur(); }
                    if($('#table3').length > 0) { table3.cell.blur(); }

                    if(table4_index && $('#'+table4_index).length > 0) {
                        table4.cell('#'+table4_index,':eq(0)').focus();
                    }else{
                        table4.cell(':eq(0)').focus();
                    }
                    $('#'+table4_index+' td:first').addClass('focus');
                }
                return false;
            });

            $(document).ready(function() {                
                var winwidth = $(window).width(); 

                $('#modalKewenangan').on('shown.bs.modal', function () {
                    $('#uxserKewenangan').focus();
                });  
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

                jQuery.extend(jQuery.fn.dataTableExt.oSort, {
                    "sortdate-pre": function ( a ) {
                        if (a == null || a == "") {
                            return 0;
                        }
                        var ukDatea = a.split('-');
                        return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
                    },
                    // "sortdate-pre": function(value) {
                    //   var date = $(value, 'span')[0].innerHTML;
                    //   date = date.split('-');
                    //   return Date.parse(date[1] + '-' + date[0] + '-' + date[2])
                    // },
                    "sortdate-asc": function(a, b) {
                        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
                    },
                    "sortdate-desc": function(a, b) {
                        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
                    }
                });

                $('#menu_toggle').on('click', function() {
                    if (typeof table != "undefined" && table instanceof $.fn.dataTable.Api) {
                        table.columns.adjust();
                    }
                    if (typeof table1 != "undefined" && table1 instanceof $.fn.dataTable.Api) {
                        table1.columns.adjust();
                    }
                    if (typeof table2 != "undefined" && table2 instanceof $.fn.dataTable.Api) {
                        table2.columns.adjust();
                    }
                    if (typeof table3 != "undefined" && table3 instanceof $.fn.dataTable.Api) {
                        table3.columns.adjust();
                    }
                    if (typeof table4 != "undefined" && table4 instanceof $.fn.dataTable.Api) {
                        table4.columns.adjust();
                    }
                });       

                          
                
                $(window).on('resize',function(){
                    if(detectmob())
                    {
                        if(window.innerWidth <= 390)
                        {
                            $('.breadcrumb').css({
                                'max-width' : '200px'
                            });
                        }else
                        {
                            $('.breadcrumb').css({
                                'max-width' : '350px'
                            });
                        }
                        $('.breadcrumb').css({
                            'padding' : '4px 0px'
                        });
                        $('.sbmenu').css({
                            'padding-top' : '10px'
                        });
                        $('.smenu').css({
                            'visibility' : 'visible'
                        });
                    }else
                    {                        
                        $('.breadcrumb').css({
                            'padding' : '11px 0px',
                            'max-width' : '300px'
                        });
                        $('.sbmenu').css({
                            'padding-top' : '10px'
                        });
                        $('.smenu').css({
                            'visibility' : 'hidden'
                        });
                    }
                })
                .trigger('resize');
            });     

            function detectmob() {
                var mobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
                if(window.innerWidth <= 990 && window.innerHeight <= 600 || mobile) {
                    return true;
                } else {
                    return false;
                }
            }       
        </script>
<!--</body></html>-->
    </body>
</html>
<!--</body></html>-->