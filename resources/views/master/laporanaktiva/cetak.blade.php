@extends('layouts.report') @push('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css"> @endpush @section('main_container')
<a href="{{ route("laporanaktiva.download") }}?type=excel&file={{$file_excel}}" class="btn btn-success">
	<i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel</a>
<a href="" download class="btn btn-warning">
	<i class="fa fa-file-pdf-o"></i> Simpan Sebagai PDF</a>


<div style="margin-top: 100px;">
	<h2>Laporan Penyusutan Harta Tetap</h2>
</div>
<div>
	<h2>Periode : {{$tahun}}{{$bulan}}</h2>
</div>
<div style="margin-bottom: 80px;">
	<h2>Kode Gudang : {{$id_gudang}}</h2>
</div>

@foreach($golongan as $golongan_key => $per_golongan)
<div class="row-fluid" style="margin-bottom:80px">
    <div>
		<h2>[{{$per_golongan['jenisitem']}}]  {{$per_golongan['jenis']}}</h2>
	</div>
	<div>
		<h2>{{$per_golongan['name']}}</h2>
	</div>
	<table id="table{{$golongan_key}}" class="table table-bordered" width="100%" cellspacing="0">
		<thead>
			<tr>
				{{--
				<th rowspan=2>Kode Gudang</th> --}}
				<th rowspan=2>Kode Aktiva</th>
                <th rowspan=2>No. Register</th>
				<th rowspan=2 >Nama Item</th>
				<th rowspan=2>Tanggal</th>
				<th rowspan=2>Harga Perolehan</th>
				<th colspan=2>Mutasi</th>
				<th rowspan=2>Harga Perolehan Akhir</th>
				<th rowspan=2>Persen Susut</th>
				<th rowspan=2>Nilai Buku Awal Tahun</th>
				<th colspan=12></th>
				<th rowspan=2>Nilai Akumulasi</th>
				<th rowspan=2>Nilai Akumulasi Total</th>
				<th rowspan=2>ADJ/Koreksi</th>
				<th rowspan=2>Nilai Buku</th>
				<th colspan=3>Keterangan</th>
			</tr>
			<tr>
				<th>Masuk</th>
				<th>Keluar</th>
				<th>Januari</th>
				<th>Februari</th>
				<th>Maret</th>
				<th>April</th>
				<th>Mei</th>
				<th>Juni</th>
				<th>Juli</th>
				<th>Agustus</th>
				<th>September</th>
				<th>Oktober</th>
				<th>November</th>
				<th>Desember</th>
				<th>
					<th>
						<th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
@endforeach @endsection @push('scripts')
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

	var tahun = {{$tahun}};
    var bulan = {{$bulan}};
    
	@foreach($golongan as $golongan_key => $per_golongan)

	table = $('#table{{$golongan_key}}').DataTable({
            dom        : 'lrtp',//lrtip -> lrtp
            //serverSide : true,
            //stateSave  : true,
            //deferRender: true,
            //select     : true,
            ajax       : {
                url  : '{{ route("laporanaktiva.data") }}',
                data: {jenisitem_id:"{{$per_golongan['aktivajenisitemid']}}",gol_id:"{{$per_golongan['golongan']}}" , tahun:tahun, bulan:parseInt(bulan)},
                
            },
			
            paging: false,
            ordering: true,
            //autoWidth: false,
            scrollY     : false,
            scrollX     : true,
            columns     : [
                {
                    "data" : "noaktiva",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "noregister",
                    "className" : 'menufilter numberfilter',
                },
                {
                    "data" : "namaaktiva",
                    "className" : 'menufilter numberfilter',
                },
                {
                    "data" : "tglperolehan",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "nomperolehan",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_mutasi.nominal_credit",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_mutasi.nominal_debit",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_mutasi.harga_perolehan_akhir",
                    "className" : 'text-right'
                },
                {
                    "data" : "persen_susut",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_saldo.nilai_buku_awal_tahun",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.0",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.1",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.2",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.3",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.4",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.5",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.6",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.7",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.8",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.9",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.10",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.data_per_periode.11",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.nilai_akumulasi_penyusutan",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_penyusutan.nilai_akumulasi_penyusutan_total",
                    "className" : 'text-right'
                },
                {
                    "data" : "aktiva_history.nilai_koreski",
                    "className" : 'text-right'
                },
                {
                    "data" : "nilai_buku",
                    "className" : 'text-right'
                },
                {
                    "data" : "keterangan",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "spec",
                    "className" : 'menufilter numberfilter'
                },
                {
                    "data" : "keterangankeluar",
                    "className" : 'menufilter numberfilter'
                },
            ]
        });
	@endforeach

});

</script>
<style>
</style>
@endpush