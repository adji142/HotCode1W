<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <h1 style="margin-bottom: 0;">PICKING LIST ({PAGENO} of {nbpg})</h1>
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td width="15%">Tgl. Picking List</td>
                <td>: {{$cetak->tglpickinglist}}</td>
                <td width="11%">Toko</td>
                <td>: {{ $cetak->toko ? $cetak->toko->namatoko : ''}} {{($cetak->toko) ? $cetak->toko->id : ''}}</td>
            </tr>
            <tr>
                <td>No. Picking List</td>
                <td>: {{$cetak->nopickinglist}}</td>
                <td colspan="2" rowspan="2" style="vertical-align: top;">{{ $cetak->toko ? $cetak->toko->alamat.', '.$cetak->toko->kota.', '.$cetak->toko->kecamatan.', '.$cetak->toko->customwilayah : ''}}</td>
            </tr>
            <tr>
                <td>Expedisi</td>
                <td>: {{ $cetak->expedisi ? $cetak->expedisi->namaexpedisi : ''}} </td>
            </tr>
            <tr>
                <td>Kode Salesman</td>
                <td>: {{ $cetak->salesman ? $cetak->salesman->kodesales : ''}} </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Catatan Penjualan</td>
                <td>: {{ $cetak->catatanpenjualan}} </td>
                <td colspan="2"></td>
            </tr>
        </table>
    </div>
</htmlpageheader>
<htmlpagefooter name="MyFooter">
    <table width="100%" cellspacing="0" cellpadding="0" style="border-top: 1px solid #000;">
        <tr>
            <th width="25%" height="40px"></th>
            <th width="25%"></th>
            <th width="25%"></th>
            <th width="25%"></th>
        </tr>
        <tr>
            <td style="text-align: center;">Piutang</td>
            <td style="text-align: center;">Penjualan</td>
            <td style="text-align: center;">Stock</td>
            <td style="text-align: center;">Checker</td>      
        </tr>
    </table>
    <i>{{$user->name.', '.date('d-m-Y h:i:s').', cetakan ke-'.$cetak->print}}</i>
</htmlpagefooter>
<sethtmlpagefooter name="MyFooter" page="ALL"/>

<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1"/>
<table width="100%" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th width="5%" style="border-bottom: 1px solid #000;">No.</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Kode Area</th>
            <th width="45%" style="border-bottom: 1px solid #000;">Barang</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Qty. SO</th>
            <th width="20%" colspan="2" style="border-bottom: 1px solid #000;">Dikirim</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Qty. Gd.</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tfoot>
    <tbody>
        @foreach($details as $detail)
        <tr>
            <td style="text-align: center;">{{$loop->iteration}}.</td>
            <td>{{$detail->barang->area1}}</td>
            <td>{{$detail->barang->namabarang}}</td>
            <td style="text-align: center;">{{$detail->qtysoacc ? $detail->qtysoacc : '0'}}</td>
            @if($loop->iteration%2 == 0)
            <td></td>
            <td style="border-bottom: 1px solid #DDD;">{{$loop->iteration}}.</td>
            @else
            <td style="border-bottom: 1px solid #DDD;">{{$loop->iteration}}.</td>
            <td></td>
            @endif
            <td style="text-align: center;">{{$detail->qtystockgudang ? $detail->qtystockgudang : '0'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>