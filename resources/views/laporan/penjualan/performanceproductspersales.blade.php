@extends('layouts.report')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endpush

@section('main_container')
	<a href="{{ $urlExcel }}" download class="btn btn-success"><i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel</a>

	<ul class="nav nav-tabs" role="tablist">
		@foreach($sales as $sal)
		<li {{($loop->first) ? 'class=active' : ''}}><a data-toggle="tab" href="#tab{{$sal->karyawanidsalesman}}">{{$sal->kodesales}}</a></li>
		@endforeach
	</ul>

	<div class="tab-content">
		@foreach($sales as $sal)
		<div role="tabpanel" id="tab{{$sal->karyawanidsalesman}}" class="tab-pane {{($loop->first) ? 'active' : ''}}">
			<table class="table display nowrap table-bordered" id="table_report{{$sal->karyawanidsalesman}}" width="100%" cellspacing="0">
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
			</table>
		</div>
		@endforeach
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

		@foreach($sales as $sal)
		var data{{$sal->karyawanidsalesman}} = {!!$data[$sal->karyawanidsalesman]!!};
		$('#table_report{{$sal->karyawanidsalesman}}').DataTable({
			dom    : 'rtpi',
			data   : data{{$sal->karyawanidsalesman}},
			paging : true,
			scrollX: true,
			scrollY: '70vh',
			"iDisplayLength": 500,
			scrollCollapse  : true,
			deferRender     : true,
			columns: [
				{ "data" : "kodebarang" },
				{ "data" : "groupname" },
				{ "data" : "namabarang" },
				{ "data" : "pn" },
				{ "data" : "kategori" },
				{ "data" : "target_qty", "className" : "text-right" },
				{ "data" : "target_rp", "className" : "text-right" },
				{ "data" : "stokgudang_qty", "className" : "text-right" },
				{ "data" : "stokgudang_rp", "className" : "text-right" },
				{ "data" : "git_qty", "className" : "text-right" },
				{ "data" : "git_rp", "className" : "text-right" },
				{ "data" : "proyeksi_qty", "className" : "text-right" },
				{ "data" : "proyeksi_rp", "className" : "text-right" },
				{ "data" : "achievement_qty", "className" : "text-right" },
				{ "data" : "achievement_qty_persen", "className" : "text-right" },
				{ "data" : "achievement_rp", "className" : "text-right" },
				{ "data" : "achievement_rp_persen", "className" : "text-right" },
				@for($i=1;$i<=31;$i++)
				{ "data" : "total_{{$i}}", "className" : "text-right" },
				@endfor
			],
		});
		@endforeach
	});
</script>
@endpush