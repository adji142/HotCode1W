@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endpush

@section('main_container')
	<a href="{{ $urlExcel }}" download class="btn btn-success"><i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel</a>
	<table class="table display nowrap table-bordered" id="table_report" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th rowspan="2" class="text-center">KODE BARANG</th>
				<th rowspan="2" class="text-center">MAIN GROUP</th>
				<th rowspan="2" class="text-center">NAMA BARANG</th>
				<th rowspan="2" class="text-center">P / N</th>
				<th rowspan="2" class="text-center">PRODUCT MIX</th>
				<th colspan="2" class="text-center">TARGET JUAL</th>
				<th colspan="2" class="text-center">STOCK GUDANG</th>
				<th colspan="2" class="text-center">STOCK MASUK GIT</th>
				<th colspan="2" class="text-center">PROYEKSI STOCK</th>
				<th colspan="2" class="text-center">ACHIEVEMENT QTY</th>
				<th colspan="2" class="text-center">ACHIEVEMENT RP</th>
				@for($i=1;$i<=31;$i++)
				<th rowspan="2" class="text-center">{{$i}}</th>
				@endfor
			</tr>
			<tr>
				<th class="text-center">QTY</th>
				<th class="text-center">RP</th>
				<th class="text-center">QTY</th>
				<th class="text-center">RP</th>
				<th class="text-center">QTY</th>
				<th class="text-center">RP</th>
				<th class="text-center">QTY</th>
				<th class="text-center">RP</th>
				<th class="text-center">QTY</th>
				<th class="text-center">%</th>
				<th class="text-center">RP</th>
				<th class="text-center">%</th>
			</tr>
		</thead>
		<tbody>
		@foreach($data as $row)
		<tr>
			<td>{{ $row->kodebarang }}</td>
			<td>{{ $row->groupname }}</td>
			<td>{{ $row->namabarang }}</td>
			<td>{{ $row->pn }}</td>
			<td>{{ $row->kategori }}</td>
			<td style="text-align:right;">{{ $row->target_qty }}</td>
			<td style="text-align:right;">{{ $row->target_rp }}</td>
			<td style="text-align:right;">{{ $row->stokgudang_qty }}</td>
			<td style="text-align:right;">{{ $row->stokgudang_rp }}</td>
			<td style="text-align:right;">{{ $row->git_qty }}</td>
			<td style="text-align:right;">{{ $row->git_rp }}</td>
			<td style="text-align:right;">{{ $row->proyeksi_qty }}</td>
			<td style="text-align:right;">{{ $row->proyeksi_rp }}</td>
			<td style="text-align:right;">{{ $row->achievement_qty}}</td>
			<td style="text-align:right;">{{ $row->achievement_qty_persen}}</td>
			<td style="text-align:right;">{{ $row->achievement_rp}}</td>
			<td style="text-align:right;">{{ $row->achievement_rp_persen}}</td>
			@for($i=1;$i<=31;$i++)
			<td style="text-align:right;">{{ $row->{'total_'.$i} }}</td>
			@endfor
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
		});
	});
</script>
@endpush