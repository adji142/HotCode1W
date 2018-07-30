<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <h1 style="margin-bottom: 0;">SURAT JALAN ({PAGENO}/{nbpg})</h1>
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td width="11%">Tgl. SJ</td>
                <td>: {{$sj->tglsj}}</td>
                <td colspan="2">Kepada yang terhormat:</td>
            </tr>
            <tr>
                <td>No. SJ</td>
                <td>: {{$sj->nosj}}</td>
                <td colspan="2">{{($sj->toko == null) ? '-' : $sj->toko->namatoko}} ({{($sj->toko == null) ? '-' : $sj->toko->customwilayah}})</td>
            </tr>
            <tr>
                <td>Tgl. Proforma</td>
                <td>: {{$sj->tglproforma}}</td>
                <td colspan="2">{{($sj->toko == null) ? '-' : $sj->toko->alamat}}</td>
            </tr>
            <tr>
                <td>Expedisi</td>
                <td>: {{ ($sj->pengirimanid == null) ? '-' : $sj->pengirimanid }}</td>
                <td colspan="2">{{($sj->toko == null) ? '-' : $sj->toko->kecamatan }}, {{($sj->toko == null) ? '-' : $sj->toko->kota}}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td colspan="2">Telepon: {{ ($sj->toko == null) ? '-' : $sj->toko->telp }}</td>
            </tr>
        </table>
    </div>
</htmlpageheader>
<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1"/>

<table width="100%" cellspacing="0" cellpadding="2" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
    <thead>
        <tr>
            <th width="7%" style="border-bottom: 1px solid #000;">No. SO</th>
            <th width="10%" style="border-bottom: 1px solid #000;">No. Nota</th>
            <th width="20%" style="border-bottom: 1px solid #000;">Salesman</th>
            <th width="63%" style="border-bottom: 1px solid #000;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sj->details as $detail)
        <tr>
            <td style="text-align: center;">{{$detail->nota->orderpenjualan->noso}}</td>
            <td style="text-align: center;">{{$detail->nota->nonota}}</td>
            <td style="text-align: center;">{{($detail->nota->salesman) ? $detail->nota->salesman->namakaryawan : '-'}}</td>
            <td style="text-align: center;">{{$detail->keterangan}}</td>
        </tr>
        @endforeach
        @foreach($sj->titipan as $titipan)
        <tr>
            <td style="text-align: center;">TITIPAN</td>
            <td style="text-align: center;">TITIPAN</td>
            <td style="text-align: center;">TITIPAN</td>
            <td style="text-align: center;">TITIPAN</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table width="100%" cellspacing="0" cellpadding="2">
    <tr>
        <th width="7%"></th>
        <th width="10%"></th>
        <th width="20%"></th>
        <th width="63%"></th>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td style="text-align: right;font-size: 16px;font-weight: bold;">Total Koli/ Packing/ ikat: {{$sj->totalkoli()}}</td>
    </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="2">
    <tr>
        <th width="33%" height="65px"></th>
        <th width="33%"></th>
        <th width="33%"></th>
    </tr>
    <tr>
        <td style="text-align: center;">Penerima</td>
        <td style="text-align: center;">Pengirim</td>
        <td></td>
    </tr>
</table>
<htmlpagefooter name="MyFooter">
    <i>Surat jalan ini adalah bukti tanda terima yang tidak terpisahkan dengan nomor nota penjualan yang tercantum dalam surat jalan ini.</i>
    <br>
    <i>{{auth()->user()->name.', '.Carbon\Carbon::now()->format('d-m-Y H:i:s').', cetakan ke-'.$sj->print}}</i>
</htmlpagefooter>
<sethtmlpagefooter name="MyFooter" value="on" />