<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td><h1 style="margin-bottom: 0;">ORDER HARIAN</h1></td>
                <td width="8%"><img src="{{asset('assets/img/sas-print.jpg')}}" width="50" style="padding-top: 5pt;"></td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td width="11%">Tanggal</td>
                <td>: {{$order->tglorder}}</td>
                <td width="11%"></td>
                <td></td>
            </tr>
            <tr>
                <td>No. Request</td>
                <td>: {{$order->noorder}}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
</htmlpageheader>
<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1"/>

<table width="100%" cellspacing="0" cellpadding="3" style="border-bottom: 1px solid #000;">
    <thead>
        <tr>
            <th width="3%" style="border-bottom: 1px solid #000;">No</th>
            <th width="32%" style="border-bottom: 1px solid #000;">Nama Stok</th>
            <th width="5%" style="border-bottom: 1px solid #000;">Sat</th>
            <th width="12%" style="border-bottom: 1px solid #000;">Kode Barang</th>
            <th width="5%" style="border-bottom: 1px solid #000;">BO</th>
            <th width="8%" style="border-bottom: 1px solid #000;">Keb Stok</th>
            <th width="8%" style="border-bottom: 1px solid #000;">Total</th>
            <th width="8%" style="border-bottom: 1px solid #000;">Stok Akhir</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Rata-rata Jual</th>
            <th width="8%" style="border-bottom: 1px solid #000;">Qty. Di+</th>
        </tr>
    </thead>
    <tbody>
        @foreach($details as $row)
        <tr>
            <td style="text-align: right;">{{$loop->iteration}}</td>
            <td>{{$row->barang->namabarang}}</td>
            <td style="text-align: center;">{{$row->barang->satuan}}</td>
            <td>{{$row->barang->kodebarang}}</td>
            <td style="text-align: right;">{{$row->qtypenjualanbo}}</td>
            <td style="text-align: right;">{{$row->qtyorder}}</td>
            <td style="text-align: right;">{{$row->qtypenjualanbo+$row->qtyorder}}</td>
            <td style="text-align: right;">{{$row->qtystokakhir}}</td>
            <td style="text-align: right;">{{$row->rataratajual}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<br/>
<br/>
<i>{{strtoupper($user->name).', '.Carbon\Carbon::now()->format('d-m-Y H:i:s')}}</i>