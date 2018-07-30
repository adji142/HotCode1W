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
    <!-- </head> -->
    </head>

    {{-- <body class="nav-md"> --}}
    <body id="nice-scroll2" class="nav-md">
        <div class="container body">
            <div class="main_container">

                <div class="col-md-3 left_col menu_fixed">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="{{ url('/') }}" class="site_title"><i class="fa fa-database"></i> <span>{{config('app.name')}}</span></a>
                        </div>
                        
                        <div class="clearfix"></div>
                        
                        <!-- menu profile quick info -->
                        <div class="profile clearfix">
                            <div class="profile_pic">
                                <img src="{{ asset('assets/img/sas.png') }}" alt="Avatar" class="img-circle profile_img">
                            </div>
                            <div class="profile_info">
                                <span>Welcome,</span>
                                <h2 id="homesubcabang">{{ $username }} - </h2>
                                <input type="hidden" value="{{ $username }}" id="username">
                            </div>
                        </div>
                        <!-- /menu profile quick info -->
                    </div>
                </div>

                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="mainmain">
                        <div class="row">
                          <div class="x_panel">
                            <div class="x_title">
                              <h2>{{$title}}</h2>
                              <ul class="nav navbar-right panel_toolbox">
                                <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                              </ul>
                              <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                              Pengajuan Tanggal : {{ $tglajuan }} <br>
                              Status        : <label class="label @if($approvalstatus=='APPROVED'){{'label-success'}}@elseif($approvalstatus=='REJECTED'){{ 'label-danger' }}@else{{ 'label-default' }}@endif">{{ $approvalstatus }}</label>

                              <table id="tableHeader" class="table table-bordered table-striped">
                                <caption>{{ $header }}</caption>
                                <thead>
                                  <tr>
                                    <th class="text-center" width="40%">Tgl. Order</th>
                                    <th class="text-center">No. Order</th>
                                    <th class="text-center">Supplier</th>
                                    <th class="text-center">Di Ajukan Oleh</th>
                                  </tr>
                                </thead>
                                <tbody id="tbodyDetail">
                                    @if(isset($headerdata))
                                        @foreach($headerdata as $k=>$v)
                                            <tr>
                                                <td>{{ $v->{'Tgl. Order'} }}</td>
                                                <td>{{ $v->{'No. Order'} }}</td>
                                                <td>{{ $v->{'Nama Supplier'} }}</td>
                                                <td>{{ $v->{'Di Ajukan Oleh'} }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                              </table>

                              <table id="tableDetail" class="table table-bordered table-striped">
                                <caption>{{ $detail }}</caption>
                                <thead>
                                  <tr>
                                    <th class="text-center">Kode Barang</th>
                                    <th class="text-center" width="40%">Nama Barang</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-center">Qty Order</th>
                                    <th class="text-center">Hrg. Sat. Netto</th>
                                    <th class="text-center">Hrg. Total</th>
                                  </tr>
                                </thead>
                                <tbody id="tbodyDetail">
                                    @if(isset($detaildata))
                                        @foreach($detaildata as $k=>$v)
                                            <tr>
                                                <td>{{ $v->{'Kode Barang'} }}</td>
                                                <td>{{ $v->{'Nama Barang'} }}</td>
                                                <td>{{ $v->{'Satuan'} }}</td>
                                                <td>{{ $v->{'Qty. Order'} }}</td>
                                                <td>{{ $v->{'Hrg. Sat. Netto'} }}</td>
                                                <td>{{ $v->{'Hrg. Total'} }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                              </table>

                              <button type="button" id="btnAcc" onclick="ubahstatusacc(this, {{ $approvalid }})" class="btn btn-success hidden" @if($disablebutton != 0) title='Anda tidak bisa mengubah karena sudah disetujui, ditolak, atau anda tidak memiliki kewenangan untuk menyetujui maupum menolak.' disabled @endif>Acc</button>
                              <button type="button" id="btnTolak" onclick="ubahstatustolak(this, {{ $approvalid }})" class="btn btn-danger hidden" @if($disablebutton != 0) title='Anda tidak bisa mengubah karena sudah disetujui, ditolak, atau anda tidak memiliki kewenangan untuk menyetujui maupum menolak.' disabled @endif>Tolak</button>
                              
                            </div>
                          </div>
                        </div>
                      </div>
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

        <script type="text/javascript">

            $(document).ready(function(){
                var searchParams = new URLSearchParams(window.location.search);
                if(searchParams.has('tipe'))
                {
                    if(searchParams.get("tipe") == "tolak" && "{{ $approvalstatus }}" == "-")
                    {
                        ubahstatustolak(window, "{{ $approvalid }}");
                    }
                }
            })

            function ubahstatusacc(e,id) {
                
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
                        type    : 'GET',
                        url     : '{{route("approvalmgmt.simpan")}}',
                        data    : {id:id, tipe:"acc", username : $("#username").val()},
                        dataType: "json",
                        success : function(data){
                            swal({
                                title: "Sukses!",
                                text : "ACC Data Berhasil.",
                                type : "success",
                                html : true
                            },function(){
                                window.location.reload();
                            });
                        },
                        error: function(jqXHR, textResponse, errorThrown){
                            swal("Peringatan", errorThrown, "error");
                        }
                    });
                });
            }

            function ubahstatustolak(e,id) {
                
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
                        type    : 'GET',
                        url     : '{{route("approvalmgmt.simpan")}}',
                        data    : {id:id, tipe:"tolak", username : $("#username").val(), keterangan:inputValue,},
                        dataType: "json",
                        success : function(data){
                            swal({
                                title: "Sukses!",
                                text : "TOLAK Data Berhasil.",
                                type : "success",
                                html : true
                            },function(){
                                window.location.reload();
                            });
                        },
                        error: function(jqXHR, textResponse, errorThrown){
                            swal("Peringatan", errorThrown, "error");
                        }
                    });
                });
            }
        </script> 

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

            $(document.body).on("keydown", function(e){
                ele = document.activeElement;

               if(e.keyCode==38){
                //cek focus table 1
                    if (fokus=='lookupbarang') {
                        var selectedrow = $('#tbodyBarang').find('.selected');
                        if (selectedrow.prev().length !== 0) {
                            selectedrow.removeClass('selected');
                            selectedrow.prev().addClass('selected');
                        }
                    }else if (fokus=='header') {
                         table.row(table_index).deselect();
                         table.row(table_index-1).select();

                    }else if(fokus=='detail'){
                        var tr= $(table2.row(parseInt(table2_index)).node()).prev('tr');
                        // console.log(tr);
                        // console.log(table2.row(tr).index());

                        table2.row(parseInt(table2_index)).deselect();
                        table2.row(parseInt(table2.row(tr).index())).select();
                    }else if(fokus=='subdetail'){
                        var tr= $(table3.row(parseInt(table3_index)).node()).prev('tr');
                        table3.row(parseInt(table3_index)).deselect();
                        table3.row(parseInt(table3.row(tr).index())).select();
                    }else if(fokus=='subsubdetail'){
                        var tr= $(table4.row(parseInt(table4_index)).node()).prev('tr');
                        table4.row(parseInt(table4_index)).deselect();
                        table4.row(parseInt(table4.row(tr).index())).select();
                    }


                }
                else if(e.keyCode==40){
                    if (fokus=='lookupbarang') {
                        var selectedrow = $('#tbodyBarang').find('.selected');
                        if (selectedrow.next().length !== 0) {
                            selectedrow.removeClass('selected');
                            selectedrow.next().addClass('selected');
                        }
                    }else if (fokus=='header') {
                        table.row(table_index).deselect();
                        table.rows(parseInt(table_index)+1).select();
                        console.log(parseInt(table_index)+1);
                    }else if(fokus=='detail'){
                        var tr= $(table2.row(parseInt(table2_index)).node()).next('tr');
                        table2.row(parseInt(table2_index)).deselect();
                        table2.row(parseInt(table2.row(tr).index())).select();
                    }else if(fokus=='subdetail'){
                        var tr= $(table3.row(parseInt(table3_index)).node()).next('tr');
                        table3.row(parseInt(table3_index)).deselect();
                        table3.row(parseInt(table3.row(tr).index())).select();
                    }else if(fokus=='subsubdetail'){
                        var tr= $(table4.row(parseInt(table4_index)).node()).next('tr');
                        table4.row(parseInt(table4_index)).deselect();
                        table4.row(parseInt(table4.row(tr).index())).select();
                    }
                }
            });

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
            });
        </script>
    <!-- </body></html> -->
    </body>
</html>