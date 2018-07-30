<html>
        <table>
            <tr>
                <td valign="middle" colspan="5" style="text-align: center;"><strong>LAPORAN RIWAYAT JUAL 6 BULAN TERAKHIR</strong></td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td>NAMA TOKO</td>
                <td>: {{ $rjual->namatoko ? $rjual->namatoko : ''}} {{($rjual->namatoko) ? $rjual->namatoko : ''}}
            </tr>
            <tr>
                <td>ALAMAT</td>
                <td>: {{ $rjual->alamat ? $rjual->alamat : ''}}</td>
            </tr>
            <tr>
                <td>KOTA</td>
                <td>: {{ $rjual->kota ? $rjual->kota : ''}}</td>
            </tr>
            <tr>
                <td>DAERAH</td>
                <td>: {{$rjual->kecamatan}},{{$rjual->propinsi}}</td>
            </tr>
            <tr>
                <td>WILID</td>
                <td>: {{$rjual->customwilayah ? $rjual->customwilayah : ''}}</td>
            </tr>
            <tr>
                <td>TANGGAL UPDATE</td>
                <td>: {{Carbon\Carbon::parse($rjual->tgllastupdate)->format('d-m-Y')}}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td width="20" style="font-weight: bold;border: 2px solid #000000;">Tgl. Nota</td>
                <td width="65" style="font-weight: bold;border: 2px solid #000000;">Nama Barang</td>
                <td width="10" style="font-weight: bold;border: 2px solid #000000;">Qty. Nota</td>
                <td width="15" style="font-weight: bold;border: 2px solid #000000;">Hrg. Netto</td>
                <td width="15" style="font-weight: bold;border: 2px solid #000000;">Total Hrg</td>
            </tr>
            @foreach($notajual as $detail)
            <tr>
                <td width="20" style="border:2px solid #000000">{{$detail->tglnota}}</td>
                <td width="65" style="border:2px solid #000000">{{$detail->namabarang}}</td>
                <td width="10" style="border:2px solid #000000">{{$detail->qtynota ? $detail->qtynota : '0'}}</td>
                <td width="15" style="border:2px solid #000000;">{{$detail->hrgsatuannetto}}</td>
                <td width="15" style="border:2px solid #000000">{{$detail->qtynota * $detail->hrgsatuannetto}}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" align="right" style="font-weight: bold;border:2px solid #000000">TOTAL</td>
                <td style="border:2px solid #000000">{{$totalharga}}</td>
            </tr>
        </table>
</html>