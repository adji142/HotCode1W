@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endpush

@section('main_container')
	<!-- urlExcel urlPDF-->
	<a href="/transaksi/planjual/download/{{ $urlExcel }}" download class="btn btn-success"><i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel</a>
	<a href="/transaksi/planjual/download/{{ $urlPDF }}" download class="btn btn-warning"><i class="fa fa-file-pdf-o"></i> Simpan Sebagai PDF</a>
	<table class="table" id="table_report">
		<thead>
			<tr>
				<th>No</th>
				<th>Kode Barang</th>
				<th>Nama Barang</th>
				<th>History Jual Qty</th>
				<th>Qty 00</th>
				<th>Keterangan Qty 11</th>
				<th>Harga Jual</th>
				<th>Qty Plan</th>
				<th>Nominal</th>
				<th>Toko</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $row)
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
				<td style="text-align: right;">{{ $row->listtoko }}</td>
			</tr>
		@endforeach
		</tbody>
		<tfoot>
			<tr>
				<td colspan="7">Total</td>
				<td>0</td>
            	<td>0</td>
			</tr>
		</tfoot>
	</table>
@endsection

@push('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js "></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#table_report').DataTable({
			dom: 'rt',
			paging: false
			
		});
	});
</script>
@endpush