<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <h1 style="margin-bottom: 0;">PiL AG</h1>
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td width="11%">Gud. Tujuan</td>
                <td>: {{ $ag->kesubcabang->kodesubcabang }}</td>
                <td width="11%">No.</td>
                <td>: {{ $ag->norqag }}</td>
            </tr>
            <tr>
                <td>Gud. Pengirim</td>
                <td>: {{ $ag->darisubcabang->kodesubcabang }}</td>
                <td>Tanggal</td>
                <td>: {{ $ag->tglnotaag }}</td>
            </tr>
        </table>
    </div>
</htmlpageheader>
<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1"/>

@for($i=0;$i<$nilai_cetak;$i++)
@if($i != 0)
<pagebreak>    
@endif
<table width="100%" cellspacing="0" cellpadding="2" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
    <thead>
        <tr>
            <th width="5%" style="border-bottom: 1px solid #000;">No.</th>
            <th width="70%" style="border-bottom: 1px solid #000;">Nama Barang</th>
            <th width="5%" style="border-bottom: 1px solid #000;">Sat.</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Qty. Rq</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Qty. Kirim</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ag->details as $agd)
        <tr>
            <td style="text-align: center;">{{ $loop->iteration }}</td>
            <td style="text-align: center;">{{ $agd->barang->namabarang }}</td>
            <td style="text-align: center;">{{ $agd->barang->sat }}</td>
            <td style="text-align: center;">{{ $agd->qtyrq }}</td>
            <td style="text-align: center;">{{ $agd->qtynotaag }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table width="100%" cellspacing="0" cellpadding="2">
    <tr>
        <th width="33%" height="65px"></th>
        <th width="33%"></th>
        <th width="33%"></th>
    </tr>
    <tr>
        <td style="text-align: center;">Gudang</td>
        <td style="text-align: center;">Checker 1</td>
        <td style="text-align: center;">Checker 2</td>
    </tr>
</table>
@if($i == 0)
<watermarkimage src="{{ asset('assets/img/asli.png') }}" alpha="1" size="111,97" />
@else
<watermarkimage src=""/>
<watermarktext content="COPY {{$i}}" alpha="0.2" />
@endif
@endfor