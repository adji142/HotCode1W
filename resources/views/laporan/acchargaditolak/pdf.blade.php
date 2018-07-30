<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <br>
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td style="text-align: center;"><h1 style="margin-bottom: 0;">ACC HARGA DITOLAK</h1></td>
            </tr>
            <tr>
                <td style="text-align: center;">Tanggal : {{ $tglmulai }} s/d {{ $tglselesai }}</td>
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
            <th style="border-bottom: 1px solid #000;">Nama Toko</th>
            <th style="border-bottom: 1px solid #000;">Alamat</th>
            <th style="border-bottom: 1px solid #000;">Kota</th>
            <th style="border-bottom: 1px solid #000;">Sts</th>
            <th style="border-bottom: 1px solid #000;">Tgl.PiL</th>
            <th style="border-bottom: 1px solid #000;">No.PiL</th>
            <th style="border-bottom: 1px solid #000;">Kd.Sales</th>
            <th style="border-bottom: 1px solid #000;">Nama Stok</th>
            <th style="border-bottom: 1px solid #000;">H.Jual</th>
            <th style="border-bottom: 1px solid #000;">H.BMK</th>
            <th style="border-bottom: 1px solid #000;">HPP AVG PiL</th>
            <th style="border-bottom: 1px solid #000;">Qty</th>
            <th style="border-bottom: 1px solid #000;">Total Harga Jual</th>
            <th style="border-bottom: 1px solid #000;">No.Nota</th>
            <th style="border-bottom: 1px solid #000;">Tgl.Nota</th>
            <th style="border-bottom: 1px solid #000;">H.Jual Terakhir</th>
            <th style="border-bottom: 1px solid #000;">Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datas as $opj)
        <tr>
            <td>{{ $opj->namatoko }}</td>
            <td>{{ $opj->alamat }}</td>
            <td>{{ $opj->kota }}</td>
            <td>{{ ($opj->laststatustoko) ? $opj->laststatustoko->status : '' }}</td>
            <td>{{ Carbon\Carbon::parse($opj->tglpickinglist)->format('d/m/Y') }}</td>
            <td>{{ $opj->nopickinglist }}</td>
            <td>{{ $opj->kodesales }}</td>
            <td>{{ $opj->namabarang }}</td>
            <td style="text-align: right;">{{ number_format($opj->opjdnetto,0,',','.') }}</td>
            <td style="text-align: right;">{{ number_format($opj->hargabmk,0,',','.') }}</td>
            <td style="text-align: right;">{{ number_format($opj->hppa,0,',','.') }}</td>
            <td style="text-align: right;">{{ $opj->qtyso }}</td>
            <td style="text-align: right;">{{ number_format($opj->totalhargajual,0,',','.') }}</td>
            <td>{{ $opj->nonota }}</td>
            <td>{{ Carbon\Carbon::parse($opj->tglproforma)->format('d/m/Y') }}</td>
            <td style="text-align: right;">{{ number_format($opj->hargajualterakhir,0,',','.') }}</td>
            <td>{{ $opj->catatan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>