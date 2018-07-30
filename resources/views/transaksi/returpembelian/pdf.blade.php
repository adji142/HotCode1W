@for($copy=0;$copy<=2;$copy++)
<htmlpageheader name="MyHeader">
    <h1 style="margin-bottom: 0;">MEMO PENGAJUAN RETUR BELI</h1>
    <table width="100%" cellspacing="0" cellpadding="2">
        <tr>
            <td width="11%">Tanggal PRB</td>
            <td>: {{$retur->tglprb}}</td>
            <td width="11%">Kode Gudang</td>
            <td>: {{$retur->subcabang->kodesubcabang}}</td>
        </tr>
        <tr>
            <td>No</td>
            <td>: {{$retur->noprb}}</td>
            <td>Pemeriksa 00</td>
            <td>: {{ ($retur->staffidpemeriksa00) && $retur->pemeriksa00 ? $retur->pemeriksa00->namakaryawan : ''}}</td>
        </tr>
        <tr>
            <td>Tanggal Kirim</td>
            <td>: {{$retur->tglkirim}}</td>
            <td></td>
            <td align="right" style="font-weight: bold;">Hal. {PAGENO}/{nbpg}</td>
        </tr>
    </table>
</htmlpageheader>
<sethtmlpageheader name="MyHeader" value="on" show-this-page="1"/>

<htmlpagefooter name="MyFooter">
    <i>{{$user->name.', '.date('d-m-Y h:i:s').', cetakan ke-'.$retur->nprint}}</i>
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
            <th width="73%" style="text-align:left; border-bottom: 1px solid #000;">Barang</th>
            <th width="10%" style="text-align:left; border-bottom: 1px solid #000;">Sat</th>
            <th width="10%" style="text-align:left; border-bottom: 1px solid #000;">Qty.</th>
        </tr>
    </thead>
    <tbody>
        @foreach($retur->details as $detail)
        <tr>
            <td>{{$loop->iteration}}.</td>
            <td>{{$detail->barang->namabarang}}</td>
            <td>{{$detail->barang->satuan}}</td>
            <td style="text-align: right;">{{$detail->qtyprb}}</td>
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
        <td style="text-align: center;">Pemeriksa 00</td>
        <td style="text-align: center;">Ka. Gudang</td>
        <td style="text-align: center;">Ekspedisi</td>
        <td style="text-align: center;">Pemeriksa 11</td>
    </tr>
</table>

@if($copy < 2)
<pagebreak resetpagenum="1" >
@endif
@endfor