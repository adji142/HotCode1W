@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Master</li>
    <li class="breadcrumb-item"><a href="{{ route('targetsales.index') }}">Laporan Aktiva</a></li>
@endsection

@section('main_container')
    <div class="">
        <div class="row">
            <div class="x_panel">
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-9">
                            <form class="form-inline" style="margin-bottom: 10px;" id="form_filter">
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
                                 {{--  <div class="form-group">
                                        <label></label>
                                        <input type="submit" class="btn-success" style="padding: 6px 12px; border-radius: 3px; text-align:center;" value="Tampilkan Data"></input>
                                </div>  --}}
                                 <div class="form-group">
                                        <label></label>
                                        <a onclick="cetak()" class="btn btn-success"><i class="fa fa-print"></i>Print</a>
                                </div>
                            </form>
                        </div>
                        {{--  <div class="col-md-3 text-right">
                            <p>
                                <a onclick="tampilkandata()" class="btn btn-success">Tampilkan Data</a>
                            </p>
                        </div>  --}}
                    </div>
                    <div id="table_wrapper" style="display: none;">
                     <hr>
                    @foreach($golongan as $golongan_key => $per_golongan)
                        <div class="row-fluid" style="margin-bottom:100px">
                            <div>
                                <h2>{{$per_golongan['name']}}</h2>
                            </div>
                            <table id="table{{$golongan_key}}" class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th rowspan=2>Kode Gudang</th>
                                        <th rowspan=2>Kode Aktiva</th>
                                        <th rowspan=2>Nama Item</th>
                                        <th rowspan=2>Tanggal</th>
                                        <th rowspan=2>Harga Perolehan</th>
                                        <th colspan=2>Mutasi</th>
                                        <th rowspan=2>Harga Perolehan Akhir</th>
                                        <th rowspan=2>Persen Susut</th>
                                        <th rowspan=2>Nilai Buku Awal Tahun</th>
                                        <th colspan=12>Penyusutan Tahun Berjalan</th>
                                        <th rowspan=2>Nilai Akumulasi</th>
                                        <th rowspan=2>Nilai Akumulasi Total</th>
                                        <th rowspan=2>ADJ/Koreksi</th>
                                        <th rowspan=2>Nilai Buku</th>
                                        <th rowspan=2>Keterangan1</th>
                                        <th rowspan=2>Keterangan2</th>
                                    </tr>
                                     <tr>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                        <th>Januari</th>
                                        <th>Februari</th>
                                        <th>Maret</th>
                                        <th>April</th>
                                        <th>Mei</th>
                                        <th>Juni</th>
                                        <th>Juli</th>
                                        <th>Agustus</th>
                                        <th>September</th>
                                        <th>Oktober</th>
                                        <th>November</th>
                                        <th>Desember</th>
                                       
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                     @endforeach
                      <hr>
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

    function cetak(){
        var bulan = $('#bulan').find(":selected").attr('value');
        var tahun = $('#tahun').find(":selected").attr('value');
        var url = '{{ route("laporanaktiva.cetak") }}'+'?bulan='+bulan+'&tahun='+tahun;
        window.open(url);
    }

    function tampilkandata(){
        table.ajax.reload(null, true);
    }

    $("form#form_filter").submit(function(event) {
        event.preventDefault();
        var tahun = $("#tahun").val();
        var bulan = $("#bulan").val();
        $('#table_wrapper').show();

        @foreach($golongan as $golongan_key => $per_golongan)

            $('#table{{$golongan_key}}').dataTable().fnDestroy();

            table = $('#table{{$golongan_key}}').DataTable({
            dom        : 'lrtp',//lrtip -> lrtp
            //serverSide : true,
            //stateSave  : true,
            //deferRender: true,
            select     : true,
            ajax       : {
                url  : '{{ route("laporanaktiva.data") }}',
                data: {jenisitem_id:"{{$per_golongan['aktivajenisitemid']}}",gol_id:"{{$per_golongan['golongan']}}" , tahun:tahun, bulan:parseInt(bulan)},
                
            },
            paging: true,
            searching: true,
            ordering: true,
            autoWidth: false,
            scrollY     : 320,
            scrollX     : true,
            fixedColumns: true,  
            //scroller    : {
            //    loadingIndicator: true
            //},
            columns     : [
                {
                    "data" : "aktiva_jenis_gudang.kodesubcabang",
                    "className" : 'menufilter numberfilter'
                    
                },
                {
                    "data" : "noaktiva",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "namaaktiva",
                    "className" : 'menufilter numberfilter',
                },
                {
                    "data" : "tglperolehan",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "nomperolehan",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_mutasi.nominal_debit",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_mutasi.nominal_credit",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_mutasi.harga_perolehan_akhir",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "persen_susut",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_saldo.nilai_buku_awal_tahun",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.0",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.1",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.2",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.3",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.4",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.5",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.6",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.7",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.8",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.9",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.10",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.11",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.nilai_akumulasi_penyusutan",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_penyusutan.nilai_akumulasi_penyusutan_total",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "aktiva_history.nilai_koreski",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "nilai_buku",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "keterangan",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "spec",
                    "className" : 'menufilter numberfilter'
                },
            ]
        });
        @endforeach

    })

    $(document).ready(function() {
        @include('master.javascript')
    });
</script>
@endpush