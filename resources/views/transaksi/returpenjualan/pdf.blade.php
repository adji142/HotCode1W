<h3>MEMO PENGAJUAN RETUR BELI</h3>
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
    </tr>
</table>

<br/>
<table width="100%" cellspacing="0" cellpadding="2" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
    <thead>
        <tr>
            <th width="7%" style="border-bottom: 1px solid #000;">No</th>
            <th width="73%" style="border-bottom: 1px solid #000;">Barang</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Sat</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Qty.</th>
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
<i>{{$user->name.', '.date('d-m-Y h:i:s')}}</i>