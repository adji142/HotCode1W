
@extends('layouts.report')

@section('main_container')

<div id="headbar">
	<div class="col-md-12">
		<a type="submit" href="{{$linkExcel}}" id="btnSimpanXls" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel</a>
		<!-- <a type="submit" href="{{$linkPdf}}" id="btnSimpanPdf" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i> Simpan Sebagai PDF</a> -->
		<button type="submit" id="btnClose" class="btn btn-danger" onclick="window.close()"><i class="fa fa-cross"></i> Close</button>
	</div>

	<div class="col-md-12">
		<hr>
	</div>
</div>

<div id="databar" class="col-md-12">
	<h3>LAPORAN PENJUALAN PER BARANG {{strtoupper($jenis)}}</h3>
		<table style="white-space: nowrap">
			<tr>
				<td>Periode</td>
				<td>&nbsp;&nbsp;: </td>
				<td>&nbsp;{{$periode}}</td>
			</tr>
		</table>
		<br>
			<table style="white-space: nowrap" border=1 class='table table-bordered table-striped display nowrap mbuhsakarepku'>
            <thead>
			    <tr>
			        <td >NO</td>
			        <td >KOTA</td>
			        <td >ID TOKO</td>
			        <td >NAMA TOKO</td>
			        <td >WILID</td>
			        
			        <td >KODE SALES</td>
			        <td >TANGGAL</td>
			        <td >NO DO</td>
			        <td >NO NOTA</td>
			        <td >KODE BARANG</td>
			        
			        <td >KATEGORI</td>
			        <td >NAMABARANG</td>
			        <td >QTY</td>
			        <td >SATUAN</td>
			        <td >JUMLAH {{strtoupper($jenis)}}</td>
			        
			        <td >RP/ITEM</td>
			        <td >KODEGUDANG</td>
			        <td >HPP AVG/ITEM</td>
			        <td >HPP AVG TOTAL</td>
			        
			        <td >CATATAN</td>
			        <td >KOMISI KHUSUS</td>
			        <td >K / T</td>
			        <td >STATUS PIUTANG</td>
			    </tr>
	     	</thead>
	    @foreach($table as $detail)
	    <tr>
	        <td >{{$detail->nomor}}</td>
	        <td >{{$detail->kota}}</td>
	        <td >{{$detail->kodetoko}}</td>
	        <td >{{$detail->namatoko}}</td>
	        <td >{{$detail->wilid}}</td>

	        <td >{{$detail->kodesales}}</td>
	        <td >{{$detail->tglproforma}}</td>
	        <td >{{$detail->nopickinglist}}</td>
	        <td >{{$detail->nonota }}</td>
	        <td >{{$detail->kodebarang }}</td>

	        <td >{{$detail->kategoripj }}</td>
	        <td >{{$detail->namabarang }}</td>
	        <td style="text-align: right;">{{number_format($detail->quantity)}}</td>
	        <td >{{$detail->satuan }}</td>
	        <td style="text-align: right;">{{number_format($detail->jumlahbruto)}}</td>

	        <td style="text-align: right;">{{number_format($detail->hrgjual)}}</td>
	        <td >{{$detail->cabang1 }}</td>
	        <td style="text-align: right;">{{number_format($detail->hppa)}}</td>
	        <td style="text-align: right;">{{number_format($detail->rpnet)}}</td>

	        <td >{{$detail->catatan}}</td>
	        <td style="text-align: right;">{{$detail->komisikhusus11 }}</td>
	        <td >{{$detail->tipetransaksi }}</td>
	        <td >{{$detail->stslunas }}</td>
	    </tr>
        @endforeach

        <tr>
          <td colspan="12" style="font-weight: bold;">JUMLAH</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["quantity"])}}</td>
          <td ></td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["jumlahbruto"])}}</td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
      </tr>
	</table>
	<h6>({{$user}}, {{date('d/m/Y H:i:s')}})</h6>
</div>
 
<iframe id="printArea" style="display:none"></iframe>

@endsection

@push('scripts')
	<script type="text/javascript">
		
	</script>

@endpush