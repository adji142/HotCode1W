@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endpush

@section('main_container')
	<a href="{{ $urlExcel }}" download class="btn btn-success"><i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel</a>
	<table class="table display nowrap table-bordered" id="table_report" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th rowspan="2" class="text-center">PERIODE</th>
				<th colspan="5" class="text-center">PENJUALAN NETTO</th>
				<th colspan="5" class="text-center">PEMBAYARAN UANG</th>
				<th colspan="5" class="text-center">PEMBAYARAN NON UANG</th>
				<th colspan="9" class="text-center">PEMBAYARAN SESUAI TEMPO DENGAN UMUR NOTA (HARI)</th>
				<th colspan="3" class="text-center">PEMBAYARAN UANG SUDAH LEWAT TEMPO (HARI)</th>
				<th colspan="5" class="text-center">PENCAPAIAN PENJUALAN NETTO</th>
				<th colspan="10" class="text-center">LABA PENJUALAN NETTO</th>
				<th colspan="8" class="text-center">LABA PER TRANSAKSI</th>
				<th rowspan="2" class="text-center">RATA-RATA PEMBAYARAN</th>
			</tr>
			<tr>
				<th class="text-center">B2</th>
				<th class="text-center">B4</th>
				<th class="text-center">E</th>
				<th class="text-center">LAINNYA</th>
				<th class="text-center">TOTAL</th>
				<th class="text-center">IMPORT R2</th>
				<th class="text-center">IMPORT R4</th>
				<th class="text-center">PABRIK</th>
				<th class="text-center">LAINNYA</th>
				<th class="text-center">TOTAL</th>
				<th class="text-center">IMPORT R2</th>
				<th class="text-center">IMPORT R4</th>
				<th class="text-center">PABRIK</th>
				<th class="text-center">LAINNYA</th>
				<th class="text-center">TOTAL</th>
				<th class="text-center"><60</th>
				<th class="text-center">61 - 75</th>
				<th class="text-center">76 - 90</th>
				<th class="text-center">91 - 105</th>
				<th class="text-center">106 - 120</th>
				<th class="text-center">121 -  150</th>
				<th class="text-center">151 - 180</th>
				<th class="text-center">>180</th>
				<th class="text-center">TOTAL</th>
				<th class="text-center">>1 s/d 30 </th>
				<th class="text-center">>30</th>
				<th class="text-center">TOTAL</th>
				<th class="text-center">TERTINGGI</th>
				<th class="text-center">TOKO TERTINGGI</th>
				<th class="text-center">WILID TERTINGGI</th>
				<th class="text-center">TERENDAH</th>
				<th class="text-center">RATA2 PER BULAN</th>
				<th class="text-center">B2</th>
				<th class="text-center">B2(%)</th>
				<th class="text-center">B4</th>
				<th class="text-center">B4(%)</th>
				<th class="text-center">E</th>
				<th class="text-center">E(%)</th>
				<th class="text-center">LAINNYA</th>
				<th class="text-center">LAINNYA(%)</th>
				<th class="text-center">TOTAL</th>
				<th class="text-center">TOTAL(%)</th>
				<th class="text-center">NOTA JUAL</th>
				<th class="text-center">%</th>
				<th class="text-center">RETUR JUAL</th>
				<th class="text-center">%</th>
				<th class="text-center">KOREKSI JUAL</th>
				<th class="text-center">%</th>
				<th class="text-center">KOREKSI RETUR JUAL</th>
				<th class="text-center">%</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $toko)
				<tr>
					<td>{{ $toko->periode }}</td>

					<td>{{ number_format($toko->penjualannettob2,0,',','.') }}</td>
					<td>{{ number_format($toko->penjualannettob4,0,',','.') }}</td>
					<td>{{ number_format($toko->penjualannettoe,0,',','.') }}</td>
					<td>{{ number_format($toko->penjualannettolainnya,0,',','.') }}</td>
					<td>{{ number_format($toko->penjualannettototal,0,',','.') }}</td>

					<td>{{ number_format($toko->pembayaranuangimportr2,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaranuangimportr4,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaranuangpabrik,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaranuanglainnya,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaranuangtotal,0,',','.') }}</td>

					<td>{{ number_format($toko->pembayarannonuangimportr2,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayarannonuangimportr4,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayarannonuangpabrik,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayarannonuanglainnya,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayarannonuangtotal,0,',','.') }}</td>

					<td>{{ number_format($toko->pembayaransesuaitempokurangdari60,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaransesuaitempoantara6175,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaransesuaitempoantara7690,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaransesuaitempoantara91105,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaransesuaitempoantara106120,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaransesuaitempoantara121150,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaransesuaitempoantara151180,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaransesuaitempolebihdari180,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaransesuaitempototal,0,',','.') }}</td>

					<td>{{ number_format($toko->pembayaranlewattempokurangdari30,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaranlewattempolebihdari30,0,',','.') }}</td>
					<td>{{ number_format($toko->pembayaranlewattempototal,0,',','.') }}</td>

					<td>{{ number_format($toko->pencapaiantertinggi,0,',','.') }}</td>
					<td>{{ $toko->namatoko }}</td>
					<td>{{ $toko->customwilayah }}</td>
					<td>{{ number_format($toko->pencapaianterendah,0,',','.') }}</td>
					<td>{{ number_format($toko->ratarata,2,',','.') }}</td>

					<td>{{ number_format($toko->labanettob2,0,',','.') }}</td>
					<td>{{ number_format($toko->persenlabanettob2*100,2,',','.') }}</td>
					<td>{{ number_format($toko->labanettob4,0,',','.') }}</td>
					<td>{{ number_format($toko->persenlabanettob4*100,2,',','.') }}</td>
					<td>{{ number_format($toko->labanettoe,0,',','.') }}</td>
					<td>{{ number_format($toko->persenlabanettoe*100,2,',','.') }}</td>
					<td>{{ number_format($toko->labanettolainnya,0,',','.') }}</td>
					<td>{{ number_format($toko->persenlabanettolainnya*100,2,',','.') }}</td>
					<td>{{ number_format($toko->labanettototal,0,',','.') }}</td>
					<td>{{ number_format($toko->persenlabanettototal*100,2,',','.') }}</td>

					<td>{{ number_format($toko->notajual,0,',','.') }}</td>
					<td>{{ number_format($toko->persennotajual*100,2,',','.') }}</td>
					<td>{{ number_format($toko->returjual,0,',','.') }}</td>
					<td>{{ number_format($toko->persenreturjual*100,2,',','.') }}</td>
					<td>{{ number_format($toko->koreksijual,0,',','.') }}</td>
					<td>{{ number_format($toko->persenkoreksijual*100,2,',','.') }}</td>
					<td>{{ number_format($toko->koreksireturjual,0,',','.') }}</td>
					<td>{{ number_format($toko->persenkoreksireturjual*100,2,',','.') }}</td>

					<td>{{ number_format(($toko->pembayaranuangtotal+$toko->pembayarannonuangtotal)/$totalBulan,2,',','.') }}</td>
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