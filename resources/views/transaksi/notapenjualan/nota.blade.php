<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td><h1 style="margin-bottom: 0;">NOTA JUAL</h1></td>
                <td width="8%"><img src="{{asset('assets/img/sas-print.jpg')}}" width="50" style="padding-top: 5pt;"></td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td width="11%">Tanggal</td>
                <td>: {{$nota->tglnota}}</td>
                <td width="11%">Kepada</td>
                <td>: {{$nota->toko->namatoko}}</td>
            </tr>
            <tr>
                <td>No. Nota</td>
                <td>: {{$nota->nonota}}</td>
                <td>Alamat</td>
                <td>: {{$nota->toko->alamat, $nota->toko->kecamatan, $nota->toko->kota}}</td>
            </tr>
            <tr>
                <td>No PIL</td>
                <td>: {{ ($nota->orderpenjualan) ? $nota->orderpenjualan->nopickinglist : ''}}</td>
                <td>{{$nota->toko->customwilayah, $nota->toko->id}}</td>
                <td></td>
            </tr>
            <tr>
                <td>Tempo</td>
                <td>: {{$nota->temponota}}</td>
                <td>Kota</td>
                <td>: {{$nota->toko->kota}}</td>
            </tr>
            <tr>
                <td>Salesman</td>
                <td>: {{ ($nota->salesman) ? $nota->salesman->namakaryawan : ''}}</td>
                <td>Kode Expedisi</td>
                <td>: {{ ($nota->orderpenjualan) && ($nota->orderpenjualan->expedisi) ? $nota->orderpenjualan->expedisi->kodeexpedisi : ''}}</td>
            </tr>
        </table>
    </div>
</htmlpageheader>
<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1"/>

<table width="100%" cellspacing="0" cellpadding="3" style="border-bottom: 1px solid #000;">
    <thead>
        <tr>
            <th width="7%" style="border-bottom: 1px solid #000;">No</th>
            <th width="53%" style="border-bottom: 1px solid #000;">Barang</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Qty.</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Hrg. sat.</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Jml. Disc</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Jml. Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nota->details as $detail)
        <tr>
            <td style="text-align: center;">{{$loop->iteration}}.</td>
            <td>{{$detail->barang->namabarang}}</td>
            <td style="text-align: right;">{{$detail->qtynota}}</td>
            <td style="text-align: right;">{{number_format($detail->hrgsatuanbrutto,0,',','.')}}</td>
            <td style="text-align: right;">{{$detail->totaldiscount}}</td>
            <td style="text-align: right;">{{number_format($detail->qtynota*$detail->hrgsatuannetto,0,',','.')}}</td>
        </tr>
        @endforeach
        @for($i=count($nota->details);$i<12;$i++)
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        @endfor
    </tbody>
</table>

<table width="100%" cellspacing="0" cellpadding="2">
    <tr>
        <th width="25%"></th>
        <th width="25%"></th>
        <th width="25%"></th>
        <th width="25%"></th>
    </tr>
    <tr>
        <td rowspan="5" colspan="2"><barcode code="{{Carbon\Carbon::parse($nota->tglnota)->format('Ym').$nota->nonota}}" type="C39" height="2" size="0.5"/></td>
    </tr>
    <tr>
        <td style="text-align: right;">Ttl. Hrg. Nota</td>
        <td style="text-align: right;">Rp {{ number_format($hrgnota,0,',','.') }}</td>
    </tr>
    <tr>
        <td style="text-align: right;">Ttl Disc.</td>
        <td style="text-align: right;">Rp {{ number_format($hrgdisc,0,',','.') }}</td>
    </tr>
    <tr>
        <td style="text-align: right;">PPN</td>
        <td style="text-align: right;">Rp {{ number_format($hrgppn,0,',','.') }}</td>
    </tr>
    <tr>
        <td style="text-align: right;font-size: 18px;">Ttl. Hrg. Netto</td>
        <td style="text-align: right;font-size: 18px;">Rp {{ number_format($hrgtotalnetto,0,',','.') }}</td>
    </tr>
    <tr>
        <td colspan="4">
            Catatan pembayaran: {{$catatan}}
            <br>Bila nota jual ini belum terbayar sesuai waktu yang ditentukan, maka kami berhak menarik kembali barang-barang di nota jual ini.
            <br>Saran dan komplain: hubungi nomortelepon atau email: alamatemail. Website: alamatwebsite.
            <br>Batas maksimum komplain: 7 hari dari tanggal terima.
        </td>
    </tr>
</table>
<watermarkimage src="{{ asset('assets/img/asli.png') }}" alpha="1" size="111,97" />

@for($x=0;$x<2;$x++)
<pagebreak>
<table width="100%" cellspacing="0" cellpadding="3" style="border-bottom: 1px solid #000;">
    <thead>
        <tr>
            <th width="7%" style="border-bottom: 1px solid #000;">No</th>
            <th width="53%" style="border-bottom: 1px solid #000;">Barang</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Qty.</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Hrg. sat.</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Jml. Disc</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Jml. Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nota->details as $detail)
        <tr>
            <td style="text-align: center;">{{$loop->iteration}}.</td>
            <td>{{$detail->barang->namabarang}}</td>
            <td style="text-align: right;">{{$detail->qtynota}}</td>
            <td style="text-align: right;">{{number_format($detail->hrgsatuanbrutto,0,',','.')}}</td>
            <td style="text-align: right;">{{$detail->totaldiscount}}</td>
            <td style="text-align: right;">{{number_format($detail->qtynota*$detail->hrgsatuannetto,0,',','.')}}</td>
        </tr>
        @endforeach
        @for($i=count($nota->details);$i<12;$i++)
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        @endfor
    </tbody>
</table>

<table width="100%" cellspacing="0" cellpadding="2">
    <tr>
        <th width="25%"></th>
        <th width="25%"></th>
        <th width="25%"></th>
        <th width="25%"></th>
    </tr>
    <tr>
        {{-- <td rowspan="5" colspan="2"><barcode code="{{Carbon\Carbon::parse($nota->tglnota)->format('Ym').$nota->nonota}}" type="C39" height="2" size="0.5"/></td> --}}
        <td rowspan="5" colspan="2"></td>
    </tr>
    <tr>
        <td style="text-align: right;">Ttl. Hrg. Nota</td>
        <td style="text-align: right;">Rp {{ number_format($hrgnota,0,',','.') }}</td>
    </tr>
    <tr>
        <td style="text-align: right;">Ttl Disc.</td>
        <td style="text-align: right;">Rp {{ number_format($hrgdisc,0,',','.') }}</td>
    </tr>
    <tr>
        <td style="text-align: right;">PPN</td>
        <td style="text-align: right;">Rp {{ number_format($hrgppn,0,',','.') }}</td>
    </tr>
    <tr>
        <td style="text-align: right;font-size: 18px;">Ttl. Hrg. Netto</td>
        <td style="text-align: right;font-size: 18px;">Rp {{ number_format($hrgtotalnetto,0,',','.') }}</td>
    </tr>
    <tr>
        <td colspan="4">
            Catatan pembayaran: {{$catatan}}
            <br>Bila nota jual ini belum terbayar sesuai waktu yang ditentukan, maka kami berhak menarik kembali barang-barang di nota jual ini.
            <br>Saran dan komplain: hubungi nomortelepon atau email: alamatemail. Website: alamatwebsite.
            <br>Batas maksimum komplain: 7 hari dari tanggal terima.
        </td>
    </tr>
</table>
<watermarkimage src=""/>
<watermarktext content="COPY {{$x+1}}" alpha="0.2" />
@endfor