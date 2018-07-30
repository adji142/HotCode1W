<html>
    <table>
        <tr>
            <td style="text-align: center;"><h1 style="margin-bottom: 0;">PLAN JUAL SALESMAN</h1></td>
        </tr>
        <tr>
            <td style="text-align: center;">Periode : {{ $periode }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">Kode Cabang : {{ $subcabang }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">Salesman : {{ $salesman }}</td>
        </tr>
    </table>
    <table style="border: 1px solid #000;">
        <tr>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">No</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Kode Barang</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Nama Barang</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">History Jual</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Qty 00</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Keterangan Qty 00</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Harga Jual</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Qty Plan</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Nominal</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Toko</td>

         
        </tr>
        @foreach($datas as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->kodebarang }}</td>
                <td>{{ $row->namabarang }}</td>
                <td style="text-align: right;">{{ $row->qtyhisjual }}</td>
                <td style="text-align: right;">{{ $row->qtystokgudang00 }}</td>
                <td>{{ $row->qtystokgudang11 }}</td>
                <td style="text-align: right;">{{ $row->hargajual }}</td>
                <td style="text-align: right;">{{ $row->qtyplanjual }}</td>
                <td style="text-align: right;">{{ $row->nominal }}</td>
                <td>{{ $row->listtoko }}</td>
            </tr>
        @endforeach
    </table>
    <table>
        <tr>
            <td style="font-style: italic;">{{ $username }} {{ Carbon\Carbon::now()->format('d/m/Y h:i:s') }}</td>
        </tr>
    </table>
</html>