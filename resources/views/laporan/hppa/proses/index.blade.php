@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endpush

@section('main_container')
<div style="display: block; width: 100%; height: 100%; overflow: auto;">
	@if ($urlExcel != null) <a href="{{ $urlExcel }}" download class="btn btn-success"><i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel</a> @endif
	@if ($urlPDF != null) <a href="{{ $urlPDF }}" download class="btn btn-warning"><i class="fa fa-file-pdf-o"></i> Simpan Sebagai PDF</a> @endif
	<table class="table display nowrap table-bordered" id="table_report" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th rowspan='2'>KodeBrg</th>
				<th rowspan='2'>Nama Barang</th>
				<th rowspan='2'>Satuan</th>
				<th colspan='2'>Awal</th>
				<th colspan='2'>Beli</th>
				<th colspan='2'>Koreksi Beli</th>
				<th colspan='2'>Total</th>
				<th colspan='2'>Akhir</th>
				<th rowspan='2'>HPPA Awal</th>
				<th rowspan='2'>HPPA Baru</th>
				<th rowspan='2'>Keterangan</th>
			</tr>
			<tr>
				<th>Qty</th>
				<th>Rp</th>
				<th>Qty</th>
				<th>Rp</th>
				<th>Qty</th>
				<th>Rp</th>
				<th>Qty</th>
				<th>Rp</th>
				<th>Qty</th>
				<th>Rp</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $dat)
				<tr>
					<td>{{ $dat->kodebarang }}</td>
					<td>{{ $dat->namabarang }}</td>
					<td>{{ $dat->satuan }}</td>

					<td>{{ number_format($dat->qtyawal, 0, ',', '.') }}</td>
					<td>{{ number_format($dat->rpawal, 0, ',', '.') }}</td>
					<td>{{ number_format($dat->qtybeli, 0, ',', '.') }}</td>
					<td>{{ number_format($dat->rpbeli, 0, ',', '.') }}</td>
					<td>{{ number_format($dat->qtykorbeli, 0, ',', '.') }}</td>
					<td>{{ number_format($dat->rpkorbeli, 0, ',', '.') }}</td>
					<td>{{ number_format($dat->qtytotal, 0, ',', '.') }}</td>
					<td>{{ number_format($dat->rptotal, 0, ',', '.') }}</td>
					<td>{{ number_format($dat->qtyakhir, 0, ',', '.') }}</td>
					<td>{{ number_format($dat->rpakhir, 0, ',', '.') }}</td>
					<td>{{ number_format($dat->oldhppa, 0, ',', '.') }}</td>
					<td>{{ number_format($dat->newhppa, 0, ',', '.') }}</td>
					<td>{{ $dat->keterangan }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
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
	$(document).ready(function (){
		var buttonCommon = {
	        exportOptions: {
	            format: {
	                body: function ( data, row, column, node ) {
	                    // Strip $ from salary column to make it numeric
	                    return column === 8 ?
	                        data.replace( /[$,]/g, '' ) :
	                        data;
	                }
	            }
	        }
	    };

		$('#table_report').DataTable({
			dom: 'rt',
			paging: false,
			scrollX: true,
			scrollY: '70vh',
			scrollCollapse: true,
			buttons: [
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'LEGAL',
					download: 'open',
					text: '<i class="fa fa-file-pdf-o"></i>',
					title: 'HPPA Rata-rata'
				}
			]
		});
	});
</script>
@endpush