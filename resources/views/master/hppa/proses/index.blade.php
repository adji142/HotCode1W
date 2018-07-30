@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Master</li>
    <li class="breadcrumb-item"><a href="{{ route('hppaproseshpp.index') }}">Proses HPP Rata-rata</a></li>
@endsection

@section('main_container')
    <div class="mainmain">
        <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Daftar Proses HPP Rata-rata</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li style="float: right;">
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="form-inline">
                                <div class="form-group">
                                    <label style="margin-right: 10px;">Bulan</label>
                                    <select class="select2_single form-control bulan opsi" tabindex="-1" name="bulan" id="bulan">
                                        @foreach($bulan as $k => $v)
                                            <option value="{{$k}}" {{ date('m') == $k ? 'selected' : '' }}>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label style="margin-right: 10px;">Tahun</label>
                                    <select class="select2_single form-control tahun opsi" tabindex="-1" name="tahun" id="tahun">
                                        @foreach($tahun as $t)
                                            <option value="{{$t}}" {{ date('Y') == $t ? 'selected' : '' }}>{{$t}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label style="margin-right: 10px;">Mode</label>
                                    <select class="select2_single form-control" tabindex="-1" name="mode" id="mode">
                                        <option value="all">Semua</option>
                                        <option value="selected">Dipilih</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table id="table1" class="table table-bordered table-striped display nowrap mbuhsakarepku" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Tgl Aktif</th>
                                <th>Nominal HPPA</th>
                                <th>Keterangan</th>
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
                            <a class="toggle-vis" data-column="3"><i id="eye3" class="fa fa-eye"></i> Nominal HPPA</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a class="toggle-vis" data-column="4"><i id="eye4" class="fa fa-eye"></i> Keterangan</a>
                        </p>
                    </div>
                    <a href="javascript:print()" class="btn btn-default">Proses</a>
                    <!-- <a href="javascript:process()" class="btn btn-default">Proses</a> -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    var custom_search = [];
    for (var i = 0; i < 5; i++) {
        if(i == 2 || i == 3){
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
    var table,table2,table_index,fokus,table_data;
    var proc_modal;

    /**
     * other models, not yet working
     **/
    function process (){
        var doProc = (kdbrg) => {
            var xhr;
            var upar = "?bln=" + $('#bulan').val() + "&thn=" + $('#tahun').val() + (kdbrg ? "&kodebarang=" + kdbrg : '');
            var cswal = swal({
                title: 'HPPA Rata-Rata',
                text: 'Processing ...',
                timer: 1000 * 60 * 30, // 15min
                onOpen: () => {
                    cswal.showLoading();
                    xhr = $.ajax({
                        url: '{{ route("laporan.hppa.index", ["dname" => "proses"]) }}' + upar,
                        type: 'GET',
                        onSuccess: (h) => {
                            var errMsg;
                            try {
                                jres = JSON.parse(h);
                                if (!jres.result) errMsg = jres.msg || 'Something when wrong';

                            } catch (ex) { errMsg = $("<div>" + h + "</div>").text(); }
                            if (errMsg) {
                                swal('Ups!', 'Terjadi kegagalan, ' + errMsg, 'error');
                            } else swal('Yeay!', 'HPPA Rata-Rata telah berhasil di proses!', 'success');
                            
                        },
                        onError: (x, ao, te) => swal('Ups!', 'Terjadi kegagalan,\n' + te, 'error')
                    });
                }

            }).then((r) => {
                if (r.dismiss === 'timer' && xhr !== null) {
                    swal('Ups!', 'Proses terlalu lama, dibatalkan otomatis', 'error');
                    xhr.abort();
                }
            });
        };

        switch ($("#mode").val()) {
            case "all": doProc(null); break;
            case "selected":
                if (table_data !== null) doProc(table_data.kodebarang);
                else {
                    swal({
                        title: 'Ups!',
                        text: "Tidak ada yang di pilih, apakah akan di proses semua?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, proses semua'

                    }).then(function (result) {
                        if (result.value) doProc(null);
                    });
                }
            break;
            default: swal('Ups!', "Something when wrong.",'error');
        }
    }

    function print () {
        var kdbrg;
        switch ($("#mode").val()) {
            case "all": kdbrg = null; break;
            case "selected":
                if (table_data !== null) kdbrg = table_data.kodebarang;
                else return swal('Ups!', "Anda belum memilih apapun.",'warning');
            break;
            default: return swal('Ups!', "Something when wrong.",'error');
        }

        if (typeof kdbrg !== 'undefined') {
            var bln = $('#bulan').val(),
                thn = $('#tahun').val(),
                url = '{{ route("laporan.hppa.index", ["dname" => "proses"]) }}' + '?bln=' + bln + '&thn=' + thn + (kdbrg !== null ? '&kodebarang=' + kdbrg : '');
            window.open(url);
        }
    }

    $(document).ready(function() {
        proc_modal = $('#hppaProcessModal');

        table = $('#table1').DataTable({
            dom        : 'lrtp',//lrtip -> lrtp
            serverSide : true,
            stateSave  : true,
            deferRender: true,
            select     : true,
            ajax       : {
                url : '{{ route("hppaproseshpp.data") }}',
                data: function ( d ) {
                    d.bulan         = $('#bulan').val();
                    d.tahun         = $('#tahun').val();
                    d.custom_search = custom_search;
                    d.length        = 50;
                },
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
            columnDefs: [{type: 'sortdate',targets: [2]}],
            columns: [
                { "data" : 'kodebarang', "className" : 'menufilter textfilter' },
                { "data" : 'namabarang', "className" : 'menufilter textfilter' },
                { "data" : 'tglaktif', "className" : 'menufilter numberfilter' },
                { "data" : 'nominalhppa', "className" : 'menufilter numberfilter text-right' },
                { "data" : 'keterangan', "className" : 'menufilter textfilter' },
            ]
        });
        table.on('select', function ( e, dt, type, indexes ){
            fokus       = 'header';
            table_index = indexes;
            table_data  = dt.data();
        });
        $('a.toggle-vis').on( 'click', function (e) {
            e.preventDefault();

            // Get the column API object
            var column = table.column( $(this).attr('data-column') );

            // Toggle the visibility
            column.visible( ! column.visible() );
            $('#eye'+ $(this).attr('data-column')).toggleClass('fa-eye-slash');
        } );

        $('.opsi').change(function(){
            table_data = null;
            table.ajax.reload(null, false);
        });

        @include('master.javascript')
    });
</script>
@endpush