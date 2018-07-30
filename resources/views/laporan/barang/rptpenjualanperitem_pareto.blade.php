
@extends('layouts.report')

@section('main_container')

<div id="headbar">
	<div class="col-md-12">

		<button type="submit" id="btnSimpanXls" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Simpan Sebagai Excel</button>
		<button type="submit" id="btnSimpanPdf" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i> Simpan Sebagai PDF</button>
		<button type="submit" id="btnClose" class="btn btn-danger" onclick="window.close()"><i class="fa fa-cross"></i> Close</button>

	</div>

	<div class="col-md-12">
		<hr>
	</div>
</div>

<div id="databar" class="col-md-12">
	<h3>LAPORAN REKAP PENJUALAN PER ITEM TOKO PARETO ({{strtoupper($jenis)}})</h3>
		<table style="white-space: nowrap">
			<tr>
				<td>Periode</td>
				<td>&nbsp;&nbsp;: </td>
				<td>&nbsp;{{$periode}}</td>
			</tr>
		</table>
		<br>
			<table style="white-space: nowrap" border=1 class='table table-bordered table-striped display nowrap mbuhsakarepku'>
            <thead style="text-align: center">
			    <tr>
			        <td rowspan="2">NO</td>
			        <td rowspan="2">ID TOKO</td>
			        <td rowspan="2">NAMA TOKO</td>
			        <td rowspan="2">KOTA</td>
			        <td rowspan="2">WILID</td>			        
			        <td colspan="4">OMSET</td>			        
			    </tr>
			    <tr>
   			        <td >FB2</td>
			        <td >FB4</td>
			        <td >FE2</td>
			        <td >FE4</td>
				</tr>
	     	</thead>
	    @foreach($table as $detail)
	    <tr>
	        <td >{{$detail->nomor}}</td>
	        <td >{{$detail->kodetoko}}</td>
	        <td >{{$detail->namatoko}}</td>
	        <td >{{$detail->kota}}</td>
	        <td >{{$detail->wilid}}</td>

	        <td style="text-align: right;">{{number_format($detail->omsetfb2)}}</td>
	        <td style="text-align: right;">{{number_format($detail->omsetfb4)}}</td>
	        <td style="text-align: right;">{{number_format($detail->omsetfe2)}}</td>
	        <td style="text-align: right;">{{number_format($detail->omsetfe4)}}</td>
	    </tr>
        @endforeach

        <tr>
          <td colspan="5" style="font-weight: bold;">JUMLAH</td>
          <td style="font-weight: bold; text-align: right; border: 2;">{{number_format($total["omsetfb2"])}}</td>
          <td style="font-weight: bold; text-align: right; border: 2;">{{number_format($total["omsetfb4"])}}</td>
          <td style="font-weight: bold; text-align: right; border: 2;">{{number_format($total["omsetfe2"])}}</td>
          <td style="font-weight: bold; text-align: right; border: 2;">{{number_format($total["omsetfe4"])}}</td>
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