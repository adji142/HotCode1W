@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
    <li class="breadcrumb-item"><a href="{{ route('ftagih.index') }}">Form Tagih</a></li>
@endsection

@section('main_container')
    <div class="mainmain">
        <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Daftar Tagihan</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li style="float: right;">
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-inline">
                                <div class="form-group">
                                    <label style="margin-right: 10px;">Tgl. Jatuh Tempo</label>
                                    <input type="text" id="tglmulai" class="tgl form-control" data-inputmask="'mask': 'd-m-y'" value="{{session('tglmulai')}}">
                                    <label>-</label>
                                    <input type="text" id="tglselesai" class="tgl form-control" data-inputmask="'mask': 'd-m-y'" value="{{session('tglselesai')}}">
                                </div>
                                <div class="form-group">
                                    <label style="margin: 0 10px;">Toko</label>
                                    <input type="text" id="toko" class="tgl form-control">
                                    <input type="hidden" id="tokoid" name="tokoid">
                                </div>
                                <div class="form-group">
                                    <label></label>
                                    <button type="button" class="btn btn-default" id="prosesData" style="margin-bottom: 0;" onclick="table.ajax.reload(null, false);">Proses</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 text-right">
                            @can('ftagih.print')
                            <a onclick="print()" class="btn btn-success"><i class="fa fa-print"></i> Cetak FTagih</a>
                            @endcan
                        </div>
                    </div>
                    <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="10%">Tgl. Jatuh Tempo</th>
                                    <th>No. Nota</th>
                                    <th>J.TR</th>
                                    <th>JKW</th>
                                    <th>Kd. Sales</th>
                                    <th>Nama Toko</th>
                                    <th>IDWIL</th>
                                    <th>Alamat</th>
                                    <th>Kota</th>
                                    <th>Nominal Nett</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                    </table>
                    <div style="cursor: pointer; margin-top: 10px;" class="hidden">
                        <p>
                            <span>Toggle column <strong>Hide/Show</strong> :</span>
                            <a class="toggle-vis" data-column="0"><i id="eye0" class="fa fa-eye"></i>&nbsp;&nbsp;Tgl. Jatuh Tempo&nbsp;&nbsp;</a>|
                            <a class="toggle-vis" data-column="1"><i id="eye1" class="fa fa-eye"></i>&nbsp;&nbsp;No. Nota&nbsp;&nbsp;</a>|
                            <a class="toggle-vis" data-column="2"><i id="eye2" class="fa fa-eye"></i>&nbsp;&nbsp;J.TR&nbsp;&nbsp;</a>|
                            <a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i>&nbsp;&nbsp;JKW&nbsp;&nbsp;</a>|
                            <a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i>&nbsp;&nbsp;Kd. Sales&nbsp;&nbsp;</a>|
                            <a class="toggle-vis" data-column="5"><i id="eye5" class="fa fa-eye"></i>&nbsp;&nbsp;Nama Toko&nbsp;&nbsp;</a>|
                            <a class="toggle-vis" data-column="6"><i id="eye6" class="fa fa-eye"></i>&nbsp;&nbsp;IDWIL&nbsp;&nbsp;</a>|
                            <a class="toggle-vis" data-column="7"><i id="eye7" class="fa fa-eye"></i>&nbsp;&nbsp;Alamat&nbsp;&nbsp;</a>|
                            <a class="toggle-vis" data-column="8"><i id="eye8" class="fa fa-eye"></i>&nbsp;&nbsp;Kota&nbsp;&nbsp;</a>|
                            <a class="toggle-vis" data-column="9"><i id="eye9" class="fa fa-eye"></i>&nbsp;&nbsp;Nominal Nett&nbsp;&nbsp;</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    var tipe_edit, index, table, table_index,table2_index, table2, tabledataorder, tabledatadetailorder,fokus;
    var context_menu_number_state = 'hide';
    var context_menu_text_state   = 'hide';
    var last_index    = '';
    var sync          = '';
    var filter_number = ['<=','<','=','>','>='];
    var filter_text   = ['=','!='];
    var tipe          = ['Find','Filter'];
    var column_index  = 0;
    var custom_search = [[],[],[],[],[],[],[],[],[],[],];

    // Run Lookup Toko
    lookuptoko();

    $(document).ready(function() {
        $(".tgl").inputmask();
        table = $('#table1').DataTable({
            dom        : 'lrtp',//lrtip -> lrtp
            serverSide : true,
            stateSave  : true,
            deferRender: true,
            select: {style:'single'},
            keys: {keys: [38,40]},
            ajax       : {
                type: "POST",
                url : '{{ route("ftagih.data") }}',
                data: function ( d ) {
                    d._token        = '{{ csrf_token() }}';
                    d.custom_search = custom_search;
                    d.tglmulai      = $('#tglmulai').val();
                    d.tglselesai    = $('#tglselesai').val();
                    d.tokoid        = $('#toko').val() ? $('#tokoid').val() : '';
                    d.issync        = sync;
                    d.tipe_edit     = tipe_edit;
                }
            },
            scrollY : "33vh",
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
                { "data" : "tgljt", "className": "menufilter numberfilter" },
                { "data" : "nonota", "className": "menufilter textfilter" },
                { "data" : "tipetrans", "className": "menufilter textfilter" },
                { "data" : "temponota", "className": "menufilter textfilter" },
                { "data" : "kodesales", "className": "menufilter textfilter" },
                { "data" : "namatoko", "className": "menufilter textfilter" },
                { "data" : "customwilayah", "className": "menufilter textfilter" },
                { "data" : "alamat", "className": "menufilter textfilter" },
                { "data" : "kota", "className": "menufilter textfilter" },
                { "data" : "nomnota", "className": "menufilter numberfilter text-right" },
            ]
        });

        $('a.toggle-vis').on( 'click', function (e) {
            e.preventDefault();
            var column = table.column( $(this).attr('data-column') );
            column.visible( ! column.visible() );
            $('#eye'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
        });
    });

    @can('ftagih.print')
    function print(){
        var tglmulai   = $('#tglmulai').val();
        var tglselesai = $('#tglselesai').val();
        var tokoid     = $('#tokoid').val();

        swal({
            title: "Perhatian!",
            text : "Apakah Ingin Mencetak Semua FTagih?",
            type : "warning",
            showCancelButton  : true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText : "Ya",
            cancelButtonText  : "Tidak",
            closeOnConfirm    : true,
            closeOnCancel     : true
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: '{{route("ftagih.cek")}}'+'?tglmulai='+tglmulai+'&tglselesai='+tglselesai+'&tokoid='+tokoid+'&all=1',
                    type : 'GET',
                    dataType : 'json',
                    success: function (count) {
                        if(count) {
                            var popup1  = window.open("about:blank", "_blank");
                            popup1.location = '{{route("ftagih.print")}}'+'?tglmulai='+tglmulai+'&tglselesai='+tglselesai+'&tokoid='+tokoid+'&all=1';
                        }else{
                            swal("Error!", "Tidak Ada Data Tagihan yang dicetak!", "error");
                        }
                    }
                });
            } else {
                $.ajax({
                    url: '{{route("ftagih.cek")}}'+'?tglmulai='+tglmulai+'&tglselesai='+tglselesai+'&tokoid='+tokoid+'&all=0',
                    type : 'GET',
                    dataType : 'json',
                    success: function (count) {
                        if(count) {
                            var popup1  = window.open("about:blank", "_blank");
                            popup1.location = '{{route("ftagih.print")}}'+'?tglmulai='+tglmulai+'&tglselesai='+tglselesai+'&tokoid='+tokoid+'&all=0';
                        }else{
                            swal("Error!", "Tidak Ada Data Tagihan yang dicetak!", "error");
                        }
                    }
                });
            }
        });

    }
    @endcan
</script>
@endpush