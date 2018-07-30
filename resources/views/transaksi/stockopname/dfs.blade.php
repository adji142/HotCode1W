<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <br>
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td rowspan="2" width="50%"><h1 style="margin-bottom: 0;">Form STOP Harian/ Tahunan</h1></td>
                <td width="10%">Tgl. Rencana</td>
                <td>: {{ $tglrencana }}</td>
            </tr>
            <tr>
                <td width="10%">Tgl. STOP</td>
                <td>: {{ $tglrencana }}</td>
            </tr>
        </table>
    </div>
</htmlpageheader>
<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1"/>

<table width="100%" cellspacing="0" cellpadding="2" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
    <thead>
        <tr>
            <th width="6%" style="border-bottom: 1px solid #000;">Stockid</th>
            <th width="54%" style="border-bottom: 1px solid #000;">Nama Barang</th>
            <th width="7%" style="border-bottom: 1px solid #000;">Area 1</th>
            <th width="7%" style="border-bottom: 1px solid #000;">Area 2</th>
            <th width="7%" style="border-bottom: 1px solid #000;">Area 3</th>
            <th width="9%" style="border-bottom: 1px solid #000;">Q. Baik</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Q. Rusak</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align: center;height: 60;">{{$soh ? $soh->stockid.'.' : ''}}</td>
            <td>{{$soh ? $soh->barang->namabarang : ''}}</td>
            <td style="text-align: center;">{{$soh ? $soh->barang->area1 : ''}}</td>
            <td style="text-align: center;">{{$soh ? $soh->barang->area2 : ''}}</td>
            <td style="text-align: center;">{{$soh ? $soh->barang->area3 : ''}}</td>
            <td style="text-align: center;">{{$soh ? $soh->qtybaik : ''}}</td>
            <td style="text-align: center;">{{$soh ? $soh->qtyrusak : ''}}</td>
        </tr>
    </tbody>
</table>

<table width="100%" cellspacing="0" cellpadding="2">
    <tr>
        <th width="33%" height="65px"></th>
        <th width="33%"></th>
        <th width="33%"></th>
    </tr>
    <tr>
        <td style="text-align: center;">Penghitung</td>
        <td style="text-align: center;">Pemeriksa</td>
        <td style="text-align: center;">Adm. Stock</td>
    </tr>
</table>