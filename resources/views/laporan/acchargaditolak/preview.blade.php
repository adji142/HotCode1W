@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endpush

@section('main_container')
	<a href="{{ $urlExcel }}" download class="btn btn-success"><i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel</a>
	<a href="{{ $urlPDF }}" download class="btn btn-warning"><i class="fa fa-file-pdf-o"></i> Simpan Sebagai PDF</a>
	<table class="table display nowrap table-bordered" id="table_report" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th>Nama Toko</th>
				<th>Alamat</th>
				<th>Kota</th>
				<th>Sts</th>
				<th>Tgl.PiL</th>
				<th>No.PiL</th>
				<th>Kd.Sales</th>
				<th>Nama Stok</th>
				<th>H.Jual</th>
				<th>H.BMK</th>
				<th>HPP AVG PiL</th>
				<th>Qty</th>
				<th>Total Harga Jual</th>
				<th>No.Nota</th>
				<th>Tgl.Nota</th>
				<th>H.Jual Terakhir</th>
				<th>Catatan</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $opj)
				<tr>
					<td>{{ $opj->namatoko }}</td>
					<td>{{ $opj->alamat }}</td>
					<td>{{ $opj->kota }}</td>
					<td>{{ ($opj->laststatustoko) ? $opj->laststatustoko->status : '' }}</td>
					<td>{{ Carbon\Carbon::parse($opj->tglpickinglist)->format('d/m/Y') }}</td>
					<td>{{ $opj->nopickinglist }}</td>
					<td>{{ $opj->kodesales }}</td>
					<td>{{ $opj->namabarang }}</td>
					<td>{{ number_format($opj->opjdnetto,0,',','.') }}</td>
					<td>{{ number_format($opj->hargabmk,0,',','.') }}</td>
					<td>{{ number_format($opj->hppa,0,',','.') }}</td>
					<td>{{ $opj->qtyso }}</td>
					<td>{{ number_format($opj->totalhargajual,0,',','.') }}</td>
					<td>{{ $opj->nonota }}</td>
					<td>{{ Carbon\Carbon::parse($opj->tglproforma)->format('d/m/Y') }}</td>
					<td>{{ number_format($opj->hargajualterakhir,0,',','.') }}</td>
					<td>{{ $opj->catatan }}</td>
				</tr>
			@endforeach
		</tbody>
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
					title: 'Laporan ACC Harga Ditolak'
				}
			]
		});
	});
</script>
@endpush