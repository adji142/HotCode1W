<html>
    <table>
        <tr>
            <td><strong>PENGIRIMAN GUDANG</strong></td>
        </tr>
        <tr>
            <td><strong>Tanggal : {{ $tgl }}</strong></td>
        </tr>
        <tr>
            <td><strong>Cabang : {{ $subcabang }}</strong></td>
        </tr>
    </table>
    <table style="border: 1px solid #000;">
        <tr>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">No</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Nama Toko</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Kota</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">WIL</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">No. Nota</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Salesman</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Exp</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Tunai (Rp)</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Kredit (Rp)</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; text-align: right;">Total</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;"></td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">{{ $total['totaltunai'] }}</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">{{ $total['totalkredit'] }}</td>
        </tr>
        @foreach($datas as $npj)
            <tr>
                <td style="border: 1px solid #000;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #000;">{{ $npj->namatoko }}</td>
                <td style="border: 1px solid #000;">{{ $npj->kota }}</td>
                <td style="border: 1px solid #000;">{{ $npj->customwilayah }}</td>
                <td style="border: 1px solid #000;">{{ $npj->nonota }}</td>
                <td style="border: 1px solid #000;">{{ $npj->namakaryawan }}</td>
                <td style="border: 1px solid #000;">{{ $npj->namaexpedisi }}</td>
                <td style="border: 1px solid #000;">{{ (substr($npj->tipetransaksi,0,1) == "T") ? $npj->totalnominal : 0 }}</td>
                <td style="border: 1px solid #000;">{{ (substr($npj->tipetransaksi,0,1) == "K") ? $npj->totalnominal : 0 }}</td>
            </tr>
        @endforeach
    </table>
    <table>
        <tr>
            <td style="font-style: italic;">{{ $username }} {{ Carbon\Carbon::now()->format('d/m/Y h:i:s') }}</td>
        </tr>
    </table>
</html>