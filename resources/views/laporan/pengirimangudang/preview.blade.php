@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endpush

@section('main_container')
	<a href="{{ $urlExcel }}" download class="btn btn-success"><i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel</a>
	<a href="{{ $urlPDF }}" download class="btn btn-warning"><i class="fa fa-file-pdf-o"></i> Simpan Sebagai PDF</a>
	<table class="table" id="table_report">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Toko</th>
				<th>Kota</th>
				<th>WIL</th>
				<th>No. Nota</th>
				<th>Salesman</th>
				<th>Exp</th>
				<th>Tunai (Rp)</th>
				<th>Kredit (Rp)</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $npj)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>{{ $npj->namatoko }}</td>
					<td>{{ $npj->kota }}</td>
					<td>{{ $npj->customwilayah }}</td>
					<td>{{ $npj->nonota }}</td>
					<td>{{ $npj->namakaryawan }}</td>
					<td>{{ $npj->namaexpedisi }}</td>
					<td>{{ (substr($npj->tipetransaksi,0,1) == "T") ? number_format($npj->totalnominal,0,',','.') : 0 }}</td>
					<td>{{ (substr($npj->tipetransaksi,0,1) == "K") ? number_format($npj->totalnominal,0,',','.') : 0 }}</td>
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
			paging: false,
			footerCallback : function ( tfoot, data, start, end, display ) {
				var total_tunai = 0;
				var total_kredit = 0;
				for (var i = 0; i < data.length; i++) {
					total_tunai += parseInt(data[i][7].replace('.',''));
					total_kredit += parseInt(data[i][8].replace('.',''));
				}
				$(tfoot).find('td').eq(1).html(total_tunai.toLocaleString('bali'));
				$(tfoot).find('td').eq(2).html(total_kredit.toLocaleString('bali'));
			},
			buttons: [
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel-o"></i>',
					title: 'Laporan Pengiriman Gudang'
				},
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'LEGAL',
					download: 'open',
					text: '<i class="fa fa-file-pdf-o"></i>',
					title: 'Laporan Pengiriman Gudang'
				}
			]
		});
	});
</script>
@endpush