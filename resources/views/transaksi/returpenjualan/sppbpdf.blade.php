@for($copy=0;$copy<=2;$copy++)
<htmlpageheader name="MyHeader">
    <h1 style="margin-bottom: 0;">Surat Permintaan Pengambilan Barang</h1>
    <table width="100%" cellspacing="0" cellpadding="2">
        <tr>
            <td width="11%">Tanggal MPR</td>
            <td>: {{$retur->tglmpr}}</td>
            <td width="11%">Nama Toko</td>
            <td>: {{ ($retur->toko) && $retur->toko ? $retur->toko->namatoko : ''}}</td>
        </tr>
        <tr>
            <td>No MPR</td>
            <td>: {{$retur->nompr}}</td>
            <td>Alamat</td>
            <td>: {{ ($retur->toko) && $retur->toko ? $retur->toko->alamat : ''}}</td>
        </tr>
        <tr>
            <td>Tanggal SPPB</td>
            <td>: {{$retur->tglsppb}}</td>
            <td></td>
            <td>&nbsp;&nbsp;{{ $retur->toko ? $retur->toko->kota.', '.$retur->toko->kecamatan.', '.$retur->toko->customwilayah.', '.$retur->toko->id : ''}}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td align="right" style="font-weight: bold;">Hal. {PAGENO}/{nbpg}</td>
        </tr>
    </table>
</htmlpageheader>
<sethtmlpageheader name="MyHeader" value="on" show-this-page="1"/>

<htmlpagefooter name="MyFooter">
    <i>{{$user->name.', '.date('d-m-Y h:i:s').', cetakan ke-'.$retur->printsppb}}</i>
</htmlpagefooter>
<sethtmlpagefooter name="MyFooter" value="on" />

@if($copy)
<watermarktext content="COPY {{$copy}}" alpha="0.2" />
<watermarkimage src=""/>
@else
<watermarkimage src="{{ asset('assets/img/asli.png') }}" alpha="1" size="111,97" />
@endif
<table width="100%" cellspacing="0" cellpadding="2" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
    <thead>
        <tr>
            <th width="7%" style="text-align:left; border-bottom: 1px solid #000;">No</th>
            <th width="63%" style="text-align:left; border-bottom: 1px solid #000;">Barang</th>
            <th width="10%" style="text-align:left; border-bottom: 1px solid #000;">Sat</th>
            <th width="10%" style="text-align:left; border-bottom: 1px solid #000;">Qty. MPR</th>
            <th width="10%" style="text-align:left; border-bottom: 1px solid #000;">Qty. SPPB</th>
        </tr>
    </thead>
    <tbody>
        @foreach($retur->details as $detail)
        <tr>
            <td>{{$loop->iteration}}.</td>
            <td>{{$detail->barang->namabarang}}</td>
            <td>{{$detail->barang->satuan}}</td>
            <td style="text-align: right;">{{$detail->qtympr}}</td>
            <td style="text-align: right;">{{$detail->qtysppb}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br/>
<table width="100%" cellspacing="0" cellpadding="2">
    <tr>
        <th width="25%" height="65px"></th>
        <th width="25%"></th>
        <th width="25%"></th>
        <th width="25%"></th>
    </tr>
    <tr>
        <td style="text-align: center;">Pelanggan</td>
        <td style="text-align: center;">Expedisi</td>
        <td style="text-align: center;">Penjualan</td>
        <td style="text-align: center;">Stock</td>
    </tr>
</table>

@if($copy < 2)
<pagebreak resetpagenum="1" >
@endif
@endfor