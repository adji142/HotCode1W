<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <br>
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td style="text-align: center;"><h1 style="margin-bottom: 0;">PENGIRIMAN GUDANG</h1></td>
            </tr>
            <tr>
                <td style="text-align: center;">Tanggal : {{ $tgl }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">Cabang : {{ $subcabang }}</td>
            </tr>
        </table>
    </div>
</htmlpageheader>
<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1"/>

<htmlpagefooter name="MyFooter">
    <i>{{$username.' '.date('d/m/Y h:i:s')}}</i>
</htmlpagefooter>
<sethtmlpagefooter name="MyFooter" value="on" />

<table width="100%" cellspacing="0" cellpadding="2" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
    <thead>
        <tr>
            <th style="border-bottom: 1px solid #000;">No</th>
            <th style="border-bottom: 1px solid #000;">Nama Toko</th>
            <th style="border-bottom: 1px solid #000;">Kota</th>
            <th style="border-bottom: 1px solid #000;">WIL</th>
            <th style="border-bottom: 1px solid #000;">No. Nota</th>
            <th style="border-bottom: 1px solid #000;">Salesman</th>
            <th style="border-bottom: 1px solid #000;">Exp</th>
            <th style="border-bottom: 1px solid #000;">Tunai (Rp)</th>
            <th style="border-bottom: 1px solid #000;">Kredit (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datas as $npj)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $npj->namatoko }}</td>
            <td>{{ $npj->kota }}</td>
            <td>{{ $npj->customwilayah }}</td>
            <td>{{ $npj->nonota }}</td>
            <td>{{ $npj->namakaryawan }}</td>
            <td>{{ $npj->namaexpedisi }}</td>
            <td style="text-align: right;">{{ (substr($npj->tipetransaksi,0,1) == "T") ? number_format($npj->totalnominal,0,',','.') : 0 }}</td>
            <td style="text-align: right;">{{ (substr($npj->tipetransaksi,0,1) == "K") ? number_format($npj->totalnominal,0,',','.') : 0 }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7">Total</td>
            <td style="text-align: right;">{{ number_format($total['totaltunai'],0,',','.') }}</td>
            <td style="text-align: right;">{{ number_format($total['totalkredit'],0,',','.') }}</td>
        </tr>
    </tfoot>
</table>