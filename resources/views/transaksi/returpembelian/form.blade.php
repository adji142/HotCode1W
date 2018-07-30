@extends('layouts.default')

@section('breadcrumb')
    <li class="breadcrumb-item">Pembelian</li>
    <li class="breadcrumb-item"><a href="{{ route('returpembelian.index') }}">Retur Pembelian</a></li>
    <li class="breadcrumb-item active">Tambah Transaksi</li>
@endsection

@section('main_container')
    <div class="mainmain">
        <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Tambah Retur Pembelian - {{$subcabanguser}}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li style="float:right;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @can('returpembelian.tambah')
                    <form class="form-horizontal form-label-left" id="formRetur" method="POST" action="{{route('returpembelian.tambah')}}">
                    @else
                    <form class="form-horizontal form-label-left" id="formRetur" method="POST" action="#">
                    @endcan
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglprb">Tgl. PRB</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="tglprb" name="tglprb" class="form-control col-md-7 col-xs-12" value="{{date('d-m-Y')}}" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="noprb">No. PRB</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="noprb" name="noprb" class="form-control col-md-7 col-xs-12" value="{{$next_no}}" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier">Supplier <span class="required">*</span></label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <input type="text" id="supplier" class="form-control col-md-7 col-xs-12" placeholder="Supplier" autocomplete="off" required="required" autofocus>
                                        <input type="hidden" id="supplierid" name="supplierid" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pemeriksa00">Pemeriksa 00 <span class="required">*</span></label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <input type="text" id="pemeriksa00" class="form-control col-md-7 col-xs-12" placeholder="Pemeriksa 00" autocomplete="off" required="required">
                                        <input type="hidden" id="pemeriksa00id" name="staffidpemeriksa00" value="">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tglkirim">Tgl. Kirim</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="tglkirim" name="tglkirim" class="form-control col-md-7 col-xs-12" value="" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtykoli">Qty. Koli</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="qtykoli" name="qtykoli" class="form-control col-md-7 col-xs-12" value="" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nokoli">No. Koli</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="nokoli" name="nokoli" class="form-control col-md-7 col-xs-12" value="" readonly tabindex="-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                @can('returpembelian.tambah')
                                <button type="submit" class="btn btn-success" id="btnSimpan">Simpan</button>
                                @endcan
                                @can('returpembelian.detail.tambah')
                                <button type="button" id="btnTambah" class="btn btn-default" data-toggle="modal" data-target="#modalDetail"><i class="fa fa-plus"></i> Tambah Detail</button>
                                @endcan
                                <table id="tableDetail" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Barang</th>
                                            <th class="text-center">Satuan</th>
                                            <th class="text-center">Qty. PRB</th>
                                            {{-- <th class="text-center">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyDetail">
                                    </tbody>
                                    {{-- <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">Total</th>
                                            <th colspan="2" class="text-right" id="txt_total">Rp. 0</th>
                                        </tr>
                                    </tfoot> --}}
                                </table>
                                <div class="ln_solid"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{route('returpembelian.index')}}" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @can('returpembelian.detail.tambah')
    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-backdrop="static" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Tambah Detail Retur Pembelian</h4>
                </div>
                <form id="formDetail" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    <div class="modal-body">
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="historis">Historis</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class="checkbox">
                                    <input type="checkbox" class="flat" id="historis" checked name="historis" value="1" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="barang">Barang <span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="barang" name="barang" class="form-control" placeholder="Barang" autocomplete="off" required="required">
                                <input type="hidden" id="barangid" name="barangid">
                                <input type="hidden" id="qtystockgudang">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Satuan</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input type="text" id="satuan" name="satuan" class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qtyprb">Qty PRB</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="number" id="qtyprb" name="qtyprb" class="form-control text-right" required="required">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="dtnokoli">No. Koli</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="number" id="dtnokoli" name="dtnokoli" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kategoriprbid">Kategori PRB</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="kategoriprb" name="kategoriprb" class="form-control" placeholder="Kategori PRB" autocomplete="off" required="required">
                                <input type="hidden" id="kategoriprbid" name="kategoriprbid">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keteranganprb">Keterangan PRB</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="keteranganprb" name="keteranganprb" class="form-control" required="required">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="returid" name="returid" value="" readonly tabindex="-1">
                        <input type="hidden" id="npbdid" name="npbdid" value="" readonly tabindex="-1">
                        <input type="hidden" id="notapembelianid" name="notapembelianid" value="" readonly tabindex="-1">
                        <input type="hidden" id="qtynota" name="qtynota" value="" readonly tabindex="-1">
                        <input type="hidden" id="qtyterima" name="qtyterima" value="" readonly tabindex="-1">
                        <input type="hidden" id="hrgsatuanbrutto" name="hrgsatuanbrutto" value="" readonly tabindex="-1">
                        <input type="hidden" id="disc1" name="disc1" value="" readonly tabindex="-1">
                        <input type="hidden" id="disc2" name="disc2" value="" readonly tabindex="-1">
                        <input type="hidden" id="ppn" name="ppn" value="" readonly tabindex="-1">
                        <input type="hidden" id="hrgsatuannetto" name="hrgsatuannetto" value="" readonly tabindex="-1">
                        <input type="hidden" id="qtyretur" name="qtyretur" value="" readonly tabindex="-1">
                        <input type="hidden" id="maxqty" name="maxqty" value="" readonly tabindex="-1">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
@endsection

@push('scripts')
    <!-- Form Validation -->
    <script src="{{asset('assets/js/validator.js')}}"></script>

    <script type="text/javascript">
        var table1,fokus;

        {{-- @include('lookupbarang') --}}
        // Run Lookup
        lookupstaff();
        lookupsupplier();
        lookupbarang();
        lookupnpbd();
        lookupkategoriretur();

        $(document).ready(function(){
            reloadtabledetail();
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

            $('#btnTambah').on('click', function(){
                if ($('#supplierid').val()=='') {
                    $('#supplier').focus();
                    swal("Ups!", "Pilih Supplier terlebih dulu.", "error");
                    return false;
                }else if ($('#pemeriksa00id').val()=='') {
                    $('#pemeriksa00').focus();
                    swal("Ups!", "Pilih Staff Pemeriksa terlebih dulu.", "error");
                    return false;
                }else {
                    return true;
                }
            });

            /* BEGIN - SUBMIT RETUR */
            $('#formRetur').on('submit', function(e){
                e.preventDefault();

                @cannot('returpembelian.tambah')
                    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
                @else

                if ($('#supplierid').val()=='' || $('#pemeriksa00id').val()=='') {
                    return false;
                }

                $.ajax({
                    type    : 'POST',
                    url     : $(this).attr('action'),
                    data    : $(this).serialize(),
                    dataType: "json",
                    success : function(data){
                        console.log(data);
                        $('#btnSimpan').hide();
                        $('#supplier').attr('disabled',true);
                        $('#pemeriksa00').attr('disabled',true);

                        $('#noprb').val(data.noprb);
                        $('#modalDetail #returid').val(data.returid);
                        $('#modalDetail').modal('show');
                        $('#barang').focus();
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
                @endcannot
                return false;
            });
            /* END - SUBMIT RETUR */

            /* BEGIN - TAMBAH DETAIL */
            $('#formDetail').on('submit', function(e){
                e.preventDefault();

                @cannot('returpembelian.detail.tambah')
                    swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.",'error'); return false;
                @else

                if($('#returid').val() == null) {
                    swal("Ups!", "Tidak bisa simpan record. Retur Pembelian belum disimpan.", "error");
                }else if(($('#historis').is(':checked')) && parseInt($('#qtyprb').val()) > parseInt($('#maxqty').val())) {
                    swal("Ups!", "Tidak bisa simpan record. Nilai Qty. PRB lebih besar dari sisa Qty. terima di pembelian. Hubungi Manager anda atau isi nilai Qty. PRB kekurangannya di nota record baru.", "error");
                    return false;
                }else{
                    $.ajax({
                        type    : 'POST',
                        url     : '{{route('returpembelian.detail.tambah')}}',
                        data    : $('#formDetail').serialize(),
                        dataType: "json",
                        success : function(data){
                            table1.destroy();

                            var x = '<tr>';
                            x += '<td>'+ $('#barang').val() +'</td>';
                            x += '<td>'+ $('#satuan').val() +'</td>';
                            x += '<td class"text-right">'+ $('#qtyprb').val() +'</td>';
                            x += '</tr>';

                            $('#tbodyDetail').append(x);
                            // swal("Sukses!", "Tambah Retur Detail Berhasil.", "success");

                            $('#barang').val('');
                            $('#barangid').val('');
                            $('#satuan').val('');
                            $('#qtyprb').val('');
                            $('#dtnokoli').val('');
                            $('#kategoriprb').val('');
                            $('#kategoriprbid').val('');
                            $('#keteranganprb').val('');

                            $('#npbdid').val('');
                            $('#notapembelianid').val('');
                            $('#stockid').val('');
                            $('#qtynota').val('');
                            $('#qtyterima').val('');
                            $('#hrgsatuanbrutto').val('');
                            $('#disc1').val('');
                            $('#disc2').val('');
                            $('#ppn').val('');
                            $('#hrgsatuannetto').val('');
                            $('#qtyretur').val('');
                            $('#maxqty').val('');

                            reloadtabledetail();
                            // $('#modalDetail').modal('hide');
                            $('#barang').focus();
                            return false;
                        },
                        error: function(data){
                            console.log(data);
                        }
                    });
                }

                return false;
                @endcannot
            });
            /* END - TAMBAH DETAIL */

            $('#tbodyDetail').on('click', '.btnRemove', function(){
                $(this).closest('tr').remove();
                // hitung_total();
            });
        });

        // function search_supplier(query){
        //     $.ajax({
        //         type: 'GET',
        //         url: '{{route("lookup.getsupplier",[null,null])}}/{{session('subcabang')}}/' + query,
        //         success: function(data){
        //             var data_json = JSON.parse(data);
        //             $('#tbodySupplier tr').remove();
        //             var x = '';
        //             if (data_json.length > 0) {
        //                 for (var i = 0; i < data_json.length; i++) {
        //                     x += '<tr>';
        //                     x += '<td>'+ data_json[i].kode +'<input type="hidden" class="id_sup" value="'+ data_json[i].id +'"></td>';
        //                     x += '<td>'+ data_json[i].nama +'</td>';
        //                     x += '</tr>';
        //                 }
        //             }else {
        //                 x += '<tr><td colspan="15" class="text-center">Tidak ada detail</td></tr>';
        //             }
        //             $('#tbodySupplier').append(x);
        //         },
        //         error: function(data){
        //             console.log(data);
        //         }
        //     });
        //     $("#txt")
        // }

        // function pilih_supplier(){
        //     if ($('#tbodySupplier').find('tr.selected td').eq(1).text() == '') {
        //         swal("Ups!", "Supplier belum dipilih.", "error");
        //         return false;
        //     }else {
        //         $('#supplier').val($('#tbodySupplier').find('tr.selected td').eq(1).text());
        //         $('#supplierid').val($('#tbodySupplier').find('tr.selected td .id_sup').val());
        //         $('#modalSupplier').modal('hide');
        //         $('#supplier').focus();
        //     }
        // }

        // function search_pemeriksa00(query){
        //     $.ajax({
        //         type    : 'POST',
        //         url     : '{{route("lookup.getstaff")}}',
        //         data    : { katakunci:query, _token:"{{ csrf_token() }}"},
        //         dataType: 'json',
        //         success : function(data){
        //             $('#tbodyPemeriksa00 tr').remove();
        //             var x = '';
        //             if (data.length > 0) {
        //                 for (var i = 0; i < data.length; i++) {
        //                     x += '<tr>';
        //                     x += '<td>'+ data[i].niksystemlama +'<input type="hidden" class="id_sup" value="'+ data[i].id +'"></td>';
        //                     x += '<td>'+ data[i].nikhrd +'</td>';
        //                     x += '<td>'+ data[i].namakaryawan +'</td>';
        //                     x += '</tr>';
        //                 }
        //             }else {
        //                 x += '<tr><td colspan="3" class="text-center">Tidak ada detail</td></tr>';
        //             }
        //             $('#tbodyPemeriksa00').append(x);
        //         },
        //         error: function(data){
        //             console.log(data);
        //         }
        //     });
        // }

        // function pilih_pemeriksa00(){
        //     if ($('#tbodyPemeriksa00').find('tr.selected td').eq(2).text() == '') {
        //         swal("Ups!", "Pemeriksa00 belum dipilih.", "error");
        //         return false;
        //     }else {
        //         $('#pemeriksa00').val($('#tbodyPemeriksa00').find('tr.selected td').eq(2).text());
        //         $('#pemeriksa00id').val($('#tbodyPemeriksa00').find('tr.selected td .id_sup').val());
        //         $('#modalPemeriksa00').modal('hide');
        //         $('#pemeriksa00').focus();
        //     }
        // }

        @can('returpembelian.detail.tambah')
        // function search_barang(query){
        //     $.ajax({
        //         type   : 'GET',
        //         url    : '{{route("lookup.getbarang",null)}}/' + query,
        //         data   : {filter : barang_custom_search},
        //         success: function(data){
        //             var barang = JSON.parse(data);
        //             $('#tbodyBarang tr').remove();
        //             var x = '';
        //             if (barang.length > 0) {
        //                 for (var i = 0; i < barang.length; i++) {
        //                     x += '<tr>';
        //                     x += '<td>'+ barang[i].kodebarang +'<input type="hidden" class="id_brg" value="'+ barang[i].id +'"><input type="hidden" class="satuan" value="'+barang[i].satuan+'"></td>';
        //                     x += '<td>'+ barang[i].namabarang +'</td>';
        //                     x += '<td>'+ barang[i].jmlgudang +'</td>';
        //                     x += '</tr>';
        //                 }
        //             // }else {
        //             //     x += '<tr><td colspan="3" class="text-center">Tidak ada detail</td></tr>';
        //             }

        //             if ($.fn.DataTable.isDataTable("#tablebarang")) {
        //                 $('#tablebarang').DataTable().clear().destroy();
        //             }
        //             $('#tbodyBarang').append(x);
        //             $('#tablebarang').DataTable({ dom: 'rt', paging: false, "order": [[ 1, 'asc' ]],});
        //         },
        //         error: function(data){
        //             console.log(data);
        //         }
        //     });
        // }

        // function pilih_barang(){
        //     if ($('#tbodyBarang').find('tr.selected td').eq(1).text() == '') {
        //         swal("Ups!", "Barang belum dipilih.", "error");
        //         return false;
        //     }else {
        //         $('#barang').val($('#tbodyBarang').find('tr.selected td').eq(1).text());
        //         $('#barangid').val($('#tbodyBarang').find('tr.selected td .id_brg').val());
        //         $('#satuan').val($('#tbodyBarang').find('tr.selected td .satuan').val());
        //         $('#modalBarang').modal('hide');
        //         cek_npbd();
        //     }
        // }

        // function cek_npbd(){
        //     if($('#historis').is(':checked')) {
        //         $('#modalNPBD').modal('show');
        //         search_npbd($('#barangid').val());
        //     }else{
        //         $('#barang').focus();
        //     }
        // }

        // function search_npbd(query){
        //     $.ajax({
        //         type: 'GET',
        //         url: '{{route("lookup.getnpbd",null)}}/' + query,
        //         success: function(data){
        //             var data_json = JSON.parse(data);
        //             $('#tbodyNPBD tr').remove();
        //             var x = '';
        //             if (data_json.length > 0) {
        //                 for (var i = 0; i < data_json.length; i++) {
        //                     x += '<tr>';
        //                     x += '<td>'+ data_json[i].nonota +'</td>';
        //                     x += '<td>'+ data_json[i].tglnota +'</td>';
        //                     x += '<td class="text-right">'+ data_json[i].qtynota +'</td>';
        //                     x += '<td class="text-right">'+ data_json[i].qtyterima +'</td>';
        //                     x += '<td class="text-right">'+ data_json[i].qtyretur +'</td>';
        //                     x += '<td class="text-right">'+ data_json[i].hrgsatuanbrutto +'</td>';
        //                     x += '<td class="text-right">'+ data_json[i].disc1 +'</td>';
        //                     x += '<td class="text-right">'+ data_json[i].disc2 +'</td>';
        //                     // x += '<td class="text-right">'+ data_json[i].hrgsatuannetto +'</td>';
        //                     x += '<td class="text-right">'+ data_json[i].hrgsatuannetto;
        //                     // x += '<td>'+ data_json[i].lastupdatedon;
        //                     x += '        <input type="hidden" class="npbdid" value="'+ data_json[i].id +'">';
        //                     x += '        <input type="hidden" class="notapembelianid" value="'+ data_json[i].notapembelianid +'">';
        //                     x += '        <input type="hidden" class="stockid" value="'+ data_json[i].stockid +'">';
        //                     x += '        <input type="hidden" class="qtynota" value="'+ data_json[i].qtynota +'">';
        //                     x += '        <input type="hidden" class="qtyterima" value="'+ data_json[i].qtyterima +'">';
        //                     x += '        <input type="hidden" class="hrgsatuanbrutto" value="'+ data_json[i].hrgsatuanbrutto +'">';
        //                     x += '        <input type="hidden" class="disc1" value="'+ data_json[i].disc1 +'">';
        //                     x += '        <input type="hidden" class="disc2" value="'+ data_json[i].disc2 +'">';
        //                     x += '        <input type="hidden" class="ppn" value="'+ data_json[i].ppn +'">';
        //                     x += '        <input type="hidden" class="hrgsatuannetto" value="'+ data_json[i].hrgsatuannetto +'">';
        //                     x += '        <input type="hidden" class="qtyretur" value="'+ data_json[i].qtyretur +'">';
        //                     x += '</td>';
        //                     x += '</tr>';
        //                 }
        //             }else {
        //                 x += '<tr><td colspan="15" class="text-center">Tidak ada detail</td></tr>';
        //             }
        //             $('#tbodyNPBD').append(x);
        //         },
        //         error: function(data){
        //             console.log(data);
        //         }
        //     });
        // }

        // function pilih_npbd(){
        //     if ($('#tbodyNPBD').find('tr.selected td').eq(1).text() == '') {
        //         swal("Ups!", "NPBD belum dipilih.", "error");
        //         return false;
        //     }else {
        //         $('#npbdid').val($('#tbodyNPBD').find('tr.selected td .npbdid').val());
        //         $('#notapembelianid').val($('#tbodyNPBD').find('tr.selected td .notapembelianid').val());
        //         $('#stockid').val($('#tbodyNPBD').find('tr.selected td .stockid').val());
        //         $('#qtynota').val($('#tbodyNPBD').find('tr.selected td .qtynota').val());
        //         $('#qtyterima').val($('#tbodyNPBD').find('tr.selected td .qtyterima').val());
        //         $('#hrgsatuanbrutto').val($('#tbodyNPBD').find('tr.selected td .hrgsatuanbrutto').val());
        //         $('#disc1').val($('#tbodyNPBD').find('tr.selected td .disc1').val());
        //         $('#disc2').val($('#tbodyNPBD').find('tr.selected td .disc2').val());
        //         $('#ppn').val($('#tbodyNPBD').find('tr.selected td .ppn').val());
        //         $('#hrgsatuannetto').val($('#tbodyNPBD').find('tr.selected td .hrgsatuannetto').val());
        //         $('#qtyretur').val($('#tbodyNPBD').find('tr.selected td .qtyretur').val());
        //         $('#maxqty').val($('#tbodyNPBD').find('tr.selected td .qtyterima').val()-$('#tbodyNPBD').find('tr.selected td .qtyretur').val());
        //         $('#modalNPBD').modal('hide');

        //         $('#barang').focus();
        //     }
        // }

        // function search_kategoriretur(query){
        //     $.ajax({
        //         type: 'GET',
        //         url: '{{route("lookup.getkategoriretur",[null])}}/' + query,
        //         success: function(data){
        //             var data_json = JSON.parse(data);
        //             $('#tbodyKategoriRetur tr').remove();
        //             var x = '';
        //             if (data_json.length > 0) {
        //                 for (var i = 0; i < data_json.length; i++) {
        //                     x += '<tr>';
        //                     x += '<td>'+ data_json[i].kode +'<input type="hidden" class="id_kategori" value="'+ data_json[i].id +'"></td>';
        //                     x += '<td>'+ data_json[i].nama +'</td>';
        //                     x += '</tr>';
        //                 }
        //             }else {
        //                 x += '<tr><td colspan="15" class="text-center">Tidak ada detail</td></tr>';
        //             }
        //             $('#tbodyKategoriRetur').append(x);
        //         },
        //         error: function(data){
        //             console.log(data);
        //         }
        //     });
        // }

        // function pilih_kategoriretur(){
        //     if ($('#tbodyKategoriRetur').find('tr.selected td').eq(1).text() == '') {
        //         swal("Ups!", "Kategori PRB belum dipilih.", "error");
        //         return false;
        //     }else {
        //         $('#kategoriprb').val($('#tbodyKategoriRetur').find('tr.selected td').eq(0).text()+' '+$('#tbodyKategoriRetur').find('tr.selected td').eq(1).text());
        //         $('#kategoriprbid').val($('#tbodyKategoriRetur').find('tr.selected td .id_kategori').val());
        //         $('#modalKategoriRetur').modal('hide');
        //         $('#kategoriprb').focus();
        //     }
        // }
        @endcan

        // function hitung_total(){
        //     var total = 0;
        //     $('#tbodyDetail tr').each(function(){
        //         total += Number($(this).find('td').eq(4).text().toString().replace(/[^\d\,\-\ ]/g, ''));
        //     });
        //     $('#txt_total').text('Rp. ' + total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
        // }

        function reloadtabledetail(){
            table1 = $('#tableDetail').DataTable({
                "searching": false,
                "ordering" : false,
                "info"     : false,
                "paging"   : false,
                responsive : true,
                language   : {
                    "emptyTable": "Tidak ada detail",
                },
                "columns": [
                    { "data": "barang" },
                    { "data": "satuan" },
                    { "data": "qtyprb" },
                    // { "data": "action" },
                ],
            });
        }
        
        $('#modalDetail').on('shown.bs.modal', function () {
            $('#barang').focus();
        });

         $('#modalNpbd').on('hidden.bs.modal', function () {
            // $('#qtystockgudang').val()
            $('#qtyprb').focus();
        });

         $('#qtyprb').keyup(function(){
            var stok = $('#qtystockgudang').val()
            if(parseFloat($('#qtyprb').val()) > parseFloat(stok)){
                $('#qtyprb').val(0);
                swal("Ups!", "qtyprb melebihi stok gudang", "error");
            }else{
                if(stok == ''){
                    $('#qtyprb').val('');
                    swal("Ups!", "stok gudang kosong", "error");
                }
            }
         })
    </script>
@endpush