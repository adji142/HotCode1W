
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
	<h3>LAPORAN DETAIL BO MURNI</h3>
		<table style="white-space: nowrap">
			<tr>
				<td>Periode</td>
				<td>&nbsp;&nbsp;: </td>
				<td>&nbsp;{{$periode}}</td>
			</tr>
			<tr>
				<td>Cabang</td>
				<td>&nbsp;&nbsp;: </td>
				<td>&nbsp;{{$kodegudang}}</td>
			</tr>
			<tr>
				<td>Kelompok Barang </td>
				<td>&nbsp;&nbsp;: </td>
				<td>&nbsp;{{$kelompok}}</td>
			</tr>
		</table>
		<br>
			<table style="white-space: nowrap" border=1 class='table table-bordered table-striped display nowrap mbuhsakarepku'>
            <thead>
			    <tr>
			        <td >KODE SALES</td>
			        <td >KODETOKO</td>
			        <td >NAMA TOKO</td>
			        <td >TGL DO</td>
			        <td >NO DO</td>
			        <td >NO ACC PIUTANG</td>
			        <td >KODEBARANG</td>
			        <td >NAMA BARANG</td>
			        <td >QTY BO MURNI</td>
			        <td >RP BO MURNI</td>
			        <td >QTY DO</td>
			        <td >RP DO</td>
			        <td >NOTA QTY TOTAL</td>
			        <td >QTY STOCK</td>
			    </tr>
	     	</thead>
	    @foreach($table as $detail)
	    <tr>
	        <td >{{$detail->kodesales}}</td>
	        <td >{{$detail->kodetoko}}</td>
	        <td >{{$detail->namatoko}}</td>
	        <td >{{$detail->tglpickinglist}}</td>
	        <td >{{$detail->nopickinglist}}</td>
	        <td >{{$detail->noaccpiutang}}</td>
	        <td >{{$detail->kodebarang}}</td>
	        <td >{{$detail->namabarang}}</td>
	        
	        <td style="text-align: right;">{{number_format($detail->qtybomurni)}}</td>
	        <td style="text-align: right;">{{number_format($detail->rpbomurni)}}</td>
	        <td style="text-align: right;">{{number_format($detail->qtysoacc)}}</td>
	        <td style="text-align: right;">{{number_format($detail->rpsoacc)}}</td>
	        <td style="text-align: right;">{{number_format($detail->notaqtytotal)}}</td>
	        <td style="text-align: right;">{{number_format($detail->qtystock)}}</td>
	    </tr>
        @endforeach

        <tr>
          <td colspan="8" style="font-weight: bold;">JUMLAH</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["qtybomurni"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["rpbomurni"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["qtysoacc"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["rpsoacc"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["notaqtytotal"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["qtystock"])}}</td>
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