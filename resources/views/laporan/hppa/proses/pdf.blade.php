<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <br>
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td style="text-align: center;"><h1 style="margin-bottom: 0;"></h1></td>
            </tr>
            <tr>
                <td style="text-align: center;">Tanggal : {{ $args['custom']['tglAwal'] }} s/d {{ $args['custom']['tglAkhir'] }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">Cabang : {{ $args['custom']['kodeCabang'] }}</td>
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
            <th style="border-bottom: 1px solid #000;">KodeBrg</th>
            <th style="border-bottom: 1px solid #000;">Nama Barang</th>
            <th style="border-bottom: 1px solid #000;">Satuan</th>
            <th style="border-bottom: 1px solid #000;">Qty</th>
            <th style="border-bottom: 1px solid #000;">Qty Total</th>
            <th style="border-bottom: 1px solid #000;">Nominal</th>
            <th style="border-bottom: 1px solid #000;">Nominal Total</th>
            <th style="border-bottom: 1px solid #000;">HPPA Awal</th>
            <th style="border-bottom: 1px solid #000;">HPPA Baru</th>
            <th style="border-bottom: 1px solid #000;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datas as $dat)
        <tr>
            <td>{{ $dat->kodebarang }}</td>
            <td>{{ $dat->namabarang }}</td>
            <td>{{ $dat->satuan }}</td>
            <td style="text-align: right;">{{ number_format($dat->qtyakhir, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($dat->qtytotal, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($dat->rpakhir, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($dat->rptotal, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($dat->oldhppa, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($dat->newhppa, 0, ',', '.') }}</td>
            <td>{{ $dat->keterangan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>