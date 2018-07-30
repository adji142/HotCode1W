<html>
    <table>
        <tr>
            <td><strong>ACC HARGA DITOLAK</strong></td>
        </tr>
        <tr>
            <td><strong>Tanggal : {{ $tglmulai }} s/d {{ $tglselesai }}</strong></td>
        </tr>
        <tr>
            <td><strong>Cabang : {{ $subcabang }}</strong></td>
        </tr>
    </table>
    <table style="border: 1px solid #000;">
        <tr>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Nama Toko</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Alamat</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Kota</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Sts</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Tgl.PiL</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">No.PiL</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Kd.Sales</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Nama Stok</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">H.Jual</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">H.BMK</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">HPP AVG PiL</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Qty</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Total Harga Jual</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">No.Nota</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Tgl.Nota</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">H.Jual Terakhir</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Catatan</td>
        </tr>
        <tr>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000; text-align: right;">Total</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('opjdnetto') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('totalhargajual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;"></td>
        </tr>
        @foreach($datas as $opj)
        <tr>
            <td style="border: 1px solid #000;">{{ $opj->namatoko }}</td>
            <td style="border: 1px solid #000;">{{ $opj->alamat }}</td>
            <td style="border: 1px solid #000;">{{ $opj->kota }}</td>
            <td style="border: 1px solid #000;">{{ ($opj->laststatustoko) ? $opj->laststatustoko->status : '' }}</td>
            <td style="border: 1px solid #000;">{{ Carbon\Carbon::parse($opj->tglpickinglist)->format('d/m/Y') }}</td>
            <td style="border: 1px solid #000;">{{ $opj->nopickinglist }}</td>
            <td style="border: 1px solid #000;">{{ $opj->kodesales }}</td>
            <td style="border: 1px solid #000;">{{ $opj->namabarang }}</td>
            <td style="border: 1px solid #000;">{{ $opj->opjdnetto }}</td>
            <td style="border: 1px solid #000;">{{ $opj->hargabmk }}</td>
            <td style="border: 1px solid #000;">{{ $opj->hppa }}</td>
            <td style="border: 1px solid #000;">{{ $opj->qtyso }}</td>
            <td style="border: 1px solid #000;">{{ $opj->totalhargajual }}</td>
            <td style="border: 1px solid #000;">{{ $opj->nonota }}</td>
            <td style="border: 1px solid #000;">{{ Carbon\Carbon::parse($opj->tglproforma)->format('d/m/Y') }}</td>
            <td style="border: 1px solid #000;">{{ $opj->hargajualterakhir }}</td>
            <td style="border: 1px solid #000;">{{ $opj->catatan }}</td>
        </tr>
        @endforeach
    </table>
    <table>
        <tr>
            <td style="font-style: italic;">{{ $username }} {{ Carbon\Carbon::now()->format('d/m/Y h:i:s') }}</td>
        </tr>
    </table>
</html>