<html>
    <table>
        <tr>
            <td><strong>HPPA Rata-rata</strong></td>
        </tr>
        <tr>
            <td><strong>Tanggal : {{ $args['custom']['tglAwal'] }} s/d {{ $args['custom']['tglAkhir'] }}</strong></td>
        </tr>
        <tr>
            <td><strong>Cabang : {{ $args['custom']['kodeCabang'] }}</strong></td>
        </tr>
    </table>
    <table style="border: 1px solid #000;">
        <tr>
            <td rowspan='2' style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">KodeBrg</td>
            <td rowspan='2' style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Nama Barang</td>
            <td rowspan='2' style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Satuan</td>
            <td colspan='2' style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Awal</td>
            <td colspan='2' style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Beli</td>
            <td colspan='2' style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Koreksi Beli</td>
            <td colspan='2' style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Total</td>
            <td colspan='2' style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Akhir</td>
            <td rowspan='2' style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">HPPA Awal</td>
            <td rowspan='2' style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">HPPA Baru</td>
            <td rowspan='2' style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Keterangan</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Qty</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Rp</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Qty</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Rp</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Qty</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Rp</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Qty</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Rp</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Qty</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">Rp</td>
        </tr>
        @foreach($datas as $dat)
        <tr>
            <td style="border: 1px solid #000;">{{ $dat->kodebarang }}</td>
            <td style="border: 1px solid #000;">{{ $dat->namabarang }}</td>
            <td style="border: 1px solid #000;">{{ $dat->satuan }}</td>

            <td style="border: 1px solid #000;">{{ $dat->qtyawal }}</td>
            <td style="border: 1px solid #000;">{{ $dat->rpawal }}</td>
            <td style="border: 1px solid #000;">{{ $dat->qtybeli }}</td>
            <td style="border: 1px solid #000;">{{ $dat->rpbeli }}</td>
            <td style="border: 1px solid #000;">{{ $dat->qtykorbeli }}</td>
            <td style="border: 1px solid #000;">{{ $dat->rpkorbeli }}</td>
            <td style="border: 1px solid #000;">{{ $dat->qtytotal }}</td>
            <td style="border: 1px solid #000;">{{ $dat->rptotal }}</td>
            <td style="border: 1px solid #000;">{{ $dat->qtyakhir }}</td>
            <td style="border: 1px solid #000;">{{ $dat->rpakhir }}</td>
            <td style="border: 1px solid #000;">{{ $dat->oldhppa }}</td>
            <td style="border: 1px solid #000;">{{ $dat->newhppa }}</td>
            <td style="border: 1px solid #000;">{{ $dat->keterangan }}</td>
        </tr>
        @endforeach
    </table>
    <table>
        <tr>
            <td style="font-style: italic;">{{ $username }} {{ Carbon\Carbon::now()->format('d/m/Y h:i:s') }}</td>
        </tr>
    </table>
</html>