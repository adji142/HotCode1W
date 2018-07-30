@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/1.4.2/css/scroller.dataTables.min.css">
@endpush

@section('main_container')
    <a href="#" download class="btn btn-success" id="dl-excel"><i class='fa fa-spinner fa-spin'></i> Generating Excel</a>

    <div class="row">
        <div class="col-md-12">
            <table id="header" width="800px" cellspacing="0">
                <thead style="color: #000;">
                    <tr>
                        <th width="50%">PIUTANG {{$toko->namatoko}}</th>
                        <th width="50%">WILID : {{$toko->customwilayah}}</th>
                    </tr>
                    <tr>
                        <th rowspan="2" valign="top">{{$toko->alamat}}</th>
                        <th>{{$toko->kota.($toko->kecamatan ? ', '.$toko->kecamatan : '')}}</th>
                    </tr>
                    <tr>
                        <th>No. Telepon : {{$toko->telepon}}</th>
                    </tr>
                    <tr>
                        <th>Plafon :  {{number_format($toko->plafon,2,',','.')}}</th>
                        <th>Penanggungjawab :  {{$toko->penanggungjawab}}</th>
                    </tr>
                    <tr><th>&nbsp;</th></tr>
                    <tr><th>&nbsp;</th></tr>
                </thead>
            </table>
        </div>
        <div class="col-md-12">
            <strong>I. INFORMASI NOTA YANG MASIH BERJALAN</strong>
            <table class="table display nowrap table-bordered table-striped mbuhsakarepku" id="table_report_detail" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10%" class="text-center">Tanggal</th>
                        <th width="10%" class="text-center">No.Nota</th>
                        <th width="10%" class="text-center">Sales</th>
                        <th width="10%" class="text-center">Nilai Nota (Rp)</th>
                        <th width="10%" class="text-center">Dibayar (Rp)</th>
                        <th width="10%" class="text-center">Saldo (Rp)</th>
                        <th width="10%" class="text-center">Jatuh Tempo </th>
                        <th width="10%" class="text-center">Keterlambatan</th>
                    </tr>
                </thead>
                @if($kartupiutang)
                <tbody>
                    @foreach($kartupiutang as $kp)
                    <tr>
                        <td>{{$kp->tglproforma}}</td>
                        <td>{{$kp->nonota}}</td>
                        <td>{{$kp->kodesales}}</td>
                        <td class="text-right">{{number_format($kp->nomnota,2,',','.')}}</td>
                        <td class="text-right">{{number_format($kp->tnomtrans,2,',','.')}}</td>
                        <td class="text-right">{{number_format($kp->nomnota-$kp->tnomtrans,2,',','.')}}</td>
                        <td>{{$kp->tgljt}}</td>
                        <td>{{$kp->keterlambatan}} HR</td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
            <br/>
        </div>
        <div class="col-md-12">
            <strong>VERSI BULANAN</strong>
            <table class="table display nowrap table-bordered table-striped mbuhsakarepku" id="table_report_detail" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="30%" class="text-center" colspan="2">Bulan</th>
                        <th width="10%" class="text-center">Nilai Nota (Rp)</th>
                        <th width="10%" class="text-center">Dibayar (Rp)</th>
                        <th width="10%" class="text-center">Saldo (Rp)</th>
                    </tr>
                </thead>
                @if($kartupiutangbulan)
                <tbody>
                    @foreach($kartupiutangbulan as $kp)
                    <tr>
                        <td>{{$kp->bulan}}</td>
                        <td>{{$kp->tahun}}</td>
                        <td class="text-right">{{number_format($kp->nomnota,2,',','.')}}</td>
                        <td class="text-right">{{number_format($kp->tnomtrans,2,',','.')}}</td>
                        <td class="text-right">{{number_format($kp->nomnota-$kp->tnomtrans,2,',','.')}}</td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
                <tfoot>
                    <tr>
                        <th class="text-right" colspan="2">JUMLAH</th>
                        <th class="text-right">{{number_format($total_nomnota,2,',','.')}}</th>
                        <th class="text-right">{{number_format($total_tnomtrans,2,',','.')}}</th>
                        <th class="text-right">{{number_format($total_sisa,2,',','.')}}</th>
                    </tr>
                </tfoot>
            </table>
            <br/>
            <br/>
        </div>
        <div class="col-md-12">
            <table id="header" width="800px">
                <thead style="color: #000;">
                    <tr>
                        <th width="15%">II. Riwayat Omset</th>
                        <th width="30%">Penjualan Tunai</th>
                        <th width="5%">Rp.</th>
                        <th width="20%" class="text-right">{{($riwayat_tunai->nomnota) ? number_format($riwayat_tunai->nomnota,2,',','.') : 0}}&nbsp;&nbsp;</th>
                        <th width="5%" class="text-right">{{($riwayat_tunai->lembarnota) ? $riwayat_tunai->lembarnota : 0}}&nbsp;&nbsp;</th>
                        <th width="20%">Lembar Nota</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Penjualan Kredit</th>
                        <th>Rp.</th>
                        <th class="text-right">{{($riwayat_kredit->nomnota) ? number_format($riwayat_kredit->nomnota,2,',','.') : 0}}&nbsp;&nbsp;</th>
                        <th class="text-right">{{($riwayat_kredit->lembarnota) ? $riwayat_kredit->lembarnota : 0}}&nbsp;&nbsp;</th>
                        <th>Lembar Nota</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Total</th>
                        <th>Rp.</th>
                        <th class="text-right">{{($riwayat_total['nomnota']) ? number_format($riwayat_total['nomnota'],2,',','.') : 0}}&nbsp;&nbsp;</th>
                        <th class="text-right">{{($riwayat_total['lembarnota']) ? $riwayat_total['lembarnota'] : 0}}&nbsp;&nbsp;</th>
                        <th>x Transaksi</th>
                    </tr>
                    <tr><th>&nbsp;</th></tr>
                    <tr><th>&nbsp;</th></tr>
                </thead>
            </table>
            <br/>
            <br/>
        </div>
        <div class="col-md-12">
            <strong>III. BG-BG MASIH BERJALAN (semua sales)</strong>
            <table class="table display nowrap table-bordered table-striped mbuhsakarepku" id="table_report_detail" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="15%" class="text-center">No BGC</th>
                        <th width="15%" class="text-center">Tgl Terima</th>
                        <th width="15%" class="text-center">Jt Tempo</th>
                        <th width="15%" class="text-center">Hari Overdue</th>
                        <th width="15%" class="text-center">Nominal</th>
                    </tr>
                </thead>
                @if($bgc)
                <tbody>
                    @foreach($bgc as $bg)
                    <tr>
                        <td>{{$bg->nobgc}}</td>
                        <td>{{$bg->createdon}}</td>
                        <td>{{$bg->tgljtbgc}}</td>
                        <td class="text-right">{{number_format($bg->nomtrans,2,',','.')}}</td>
                        <td>{{$bg->overdue}} HR</td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
                {{-- <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="text-right">Total Saldo Piutang + BG</th>
                        <th></th>
                    </tr>
                </tfoot> --}}
            </table>
            <br/>
            <br/>
        </div>
        <div class="col-md-12">
            <strong>IV. Denda Tagihan</strong>
            <table class="table display nowrap table-bordered table-striped mbuhsakarepku" id="table_report_detail" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="15%" class="text-center">Tanggal</th>
                        <th width="15%" class="text-center">No. Nota</th>
                        <th width="25%" class="text-center">Salesman</th>
                        <th width="15%" class="text-center">Rp. Denda</th>
                        <th width="15%" class="text-center">Rp. Bayar</th>
                        <th width="15%" class="text-center">Rp. Sisa</th>
                    </tr>
                </thead>
                @if($dt)
                <tbody>
                    @foreach($dt as $dx)
                    <tr>
                        <td>{{$dx->tgldenda}}</td>
                        <td>{{$dx->nonota}}</td>
                        <td>{{$dx->namakaryawan}}</td>
                        <td class="text-right">{{number_format($dx->rpdenda,2,',','.')}}</td>
                        <td class="text-right">{{number_format($dx->rpbayar,2,',','.')}}</td>
                        <td class="text-right">{{number_format($dx->rpsisa,2,',','.')}}</td>
                    </tr>
                    @endforeach
                </tbody>
                @endif
                <tfoot>
                    <tr>
                        <th class="text-right" colspan="3">Total</th>
                        <th class="text-right">{{number_format($total_rpdenda,2,',','.')}}&nbsp;&nbsp;</th>
                        <th class="text-right">{{number_format($total_rpbayar,2,',','.')}}&nbsp;&nbsp;</th>
                        <th class="text-right">{{number_format($total_rpsisa,2,',','.')}}&nbsp;&nbsp;</th>
                    </tr>
                </tfoot>
            </table>
            <br/>
            <br/>
        </div>
        <div class="col-md-12">
            <strong>Catatan Toko : {{$toko->catatan}}</strong>
            <br/>
            <br/>
            <strong>Jangka Waktu Tertinggi : {{$tempotertinggi}}</strong>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/scroller/1.4.2/js/dataTables.scroller.min.js"></script>
<script type="text/javascript">
    var table_report, table_report_detail;
    var idsalesman = null;

    $(document).ready(function(){
        $("#dataTablesSpinner").hide();
        // Get Excel Files
        $.ajax({
            url  : '{!!route("accpiutang.rekappembayarantoko.excel").'?id='.$id!!}',
            cache: false,
            success: function(href){
                if(href) {
                    $('#dl-excel').attr("href", href);
                }
            },
            complete: function(){
                $('#dl-excel').html('<i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel');
            }
        });
    });
</script>
@endpush