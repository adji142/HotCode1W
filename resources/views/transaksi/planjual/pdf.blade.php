<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <br>
        <table width="100%" cellspacing="0" cellpadding="2">
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
            <th style="border-bottom: 1px solid #000;">No</th>
			<th style="border-bottom: 1px solid #000;">Kode Barang</th>
			<th style="border-bottom: 1px solid #000;">Nama Barang</th>
			<th style="border-bottom: 1px solid #000;">History Jual Qty</th>
			<th style="border-bottom: 1px solid #000;">Qty 00</th>
			<th style="border-bottom: 1px solid #000;">Keterangan Qty 11</th>
			<th style="border-bottom: 1px solid #000;">Harga Jual</th>
			<th style="border-bottom: 1px solid #000;">Qty Plan</th>
			<th style="border-bottom: 1px solid #000;">Nominal</th>
			<th style="border-bottom: 1px solid #000;">Toko</th>
        </tr>
    </thead>
    <tbody>
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
    </tbody>
    <tfoot>
        <tr>
            <td style="font-style: italic;">{{ $username }} {{ Carbon\Carbon::now()->format('d/m/Y h:i:s') }}</td>
        </tr>
    </tfoot>
</table>