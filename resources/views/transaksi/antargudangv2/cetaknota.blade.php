<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <h1 style="margin-bottom: 0;">Nota AG</h1>
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td width="11%">Tgl. Nota</td>
                <td>: {{ $ag->tglnotaag }}</td>
                <td width="8%">Dari</td>
                <td>: {{ $ag->darisubcabangnama }}</td>
            </tr>
            <tr>
                <td>No. Rq. AG</td>
                <td>: {{ $ag->norqag }}</td>
                <td>Ke</td>
                <td>: {{ $ag->kesubcabangnama }}</td>
            </tr>
        </table>
    </div>
</htmlpageheader>
<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1"/>

<table width="100%" cellspacing="0" cellpadding="2" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
    <thead>
        <tr>
            <th width="5%" style="border-bottom: 1px solid #000;">No.</th>
            <th width="43%" style="border-bottom: 1px solid #000;">Nama Barang</th>
            <th width="8%" style="border-bottom: 1px solid #000;">Qty Nota</th>
            <th width="8%" style="border-bottom: 1px solid #000;">No. Koli</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ag->details as $row)
        <tr>
            <td style="text-align: center;">{{ $loop->iteration }}</td>
            <td style="text-align: left;">{{ $row->barang->namabarang }}</td>
            <td style="text-align: center;">{{ $row->qtynotaag }}</td>
            <td style="text-align: center;">{{ $row->nokoli }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<table width="100%" cellspacing="0" cellpadding="2">
    <tr>
        <th width="33%" height="65px">Stock</th>
        <th width="33%">Checker 1</th>
        <th width="33%">Checker 2</th>
        <th width="33%">Expedisi</th>
    </tr>
    <tr>
        <td style="text-align: center;">{{ $ag->stockpenerima }}</td>
        <td style="text-align: center;">{{ $ag->checkerpenerima1 }}</td>
        <td style="text-align: center;">{{ $ag->checkerpenerima2 }}</td>
        <td style="text-align: center;">{{ $ag->namasopir }}</td>
        <td></td>
    </tr>
</table>
<htmlpagefooter name="MyFooter">
    <i>{{auth()->user()->name.', '.Carbon\Carbon::now()->format('d-m-Y H:i:s')}}</i>
</htmlpagefooter>
<sethtmlpagefooter name="MyFooter" value="on" />