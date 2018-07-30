
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
	<h3>Laporan SO, DO, BO dan Nota</h3>
		<table style="white-space: nowrap;">
			<tr>
				<td>Periode </td>
				<td>&nbsp;&nbsp;:</td>
				<td>&nbsp;{{$periode}}</td>
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
			        <td >Kode SALES</td>
			        <td >SO</td>
			        <td >DO</td>
			        <td >NOTA</td>
			        <td >TOLAK 11</td>
			        
			        <td >+/- DO DR 11</td>
			        <td >ACC PIUTANG</td>
			        <td >TOLAKAN PIUTANG</td>
			        <td >BO MURNI</td>
			        <td >DO BLM ADA NOTA</td>
			        
			        <td >DO BLM ADA NOTA SAMA SEKALI</td>
			        <td >DO TOLAK PIUTANG FULL</td>
			        <td >DO BLM DI OTAK ATIK PIUTANG</td>
			        <td >DO BLM TERPENUHI FULL ADA NOTA</td>
			        <td >DO BLM ACC PIUTANG</td>
			        
			        <td >PENAMBAHAN DO</td>
			        <td >DO PARETO</td>
			        <td >DO PARETO (%)</td>
			        <td >DO DISCONTINUE</td>
			        <td >DO DISCONTINUE (%)</td>
			        
			        <td >NOTA PARETO</td>
			        <td >NOTA DISCONTINUE</td>
			        <td >NOMINAL RETUR</td>
			        <td >NOMINAL NETTO</td>
			    </tr>
	     	</thead>
	    @foreach($table as $detail)
	    <tr>
	        <td >{{$detail->kodesales}}</td>
	        <td style="text-align: right;">{{number_format($detail->nominalso)}}</td>
	        <td style="text-align: right;">{{number_format($detail->nominaldo)}}</td>
	        <td style="text-align: right;">{{number_format($detail->nominalnota)}}</td>
	        <td style="text-align: right;">{{number_format($detail->nominaltolak11)}}</td>
	        
	        <td style="text-align: right;">{{number_format($detail->nominaldosunatdr11)}}</td>
	        <td style="text-align: right;">{{number_format($detail->rpaccpiutang)}}</td>
	        <td style="text-align: right;">{{number_format($detail->totaltolakpiutang)}}</td>
	        <td style="text-align: right;">{{number_format($detail->nominalbomurni)}}</td>
	        <td style="text-align: right;">{{number_format($detail->nominaldoblmadanota)}}</td>
	        
	        <td style="text-align: right;">{{number_format($detail->nominaldoblmadanotasamasekali)}}</td>
	        <td style="text-align: right;">{{number_format($detail->tolakfull)}}</td>
	        <td style="text-align: right;">{{number_format($detail->doblmdiutakatikpiutang)}}</td>
	        <td style="text-align: right;">{{number_format($detail->nominaldoblmterpenuhifulladanotanya)}}</td>
	        <td style="text-align: right;">{{number_format($detail->nominalblmaccpiutang)}}</td>
	        
	        <td style="text-align: right;">{{number_format($detail->nominalpenambahannota)}}</td>
	        <td style="text-align: right;">{{number_format($detail->nominaldopareto)}}</td>
	        <td style="text-align: right;">{{number_format($detail->prs_dopareto)}}</td>
	        <td style="text-align: right;">{{number_format($detail->nominaldodisc)}}</td>
	        <td style="text-align: right;">{{number_format($detail->prs_dodisc)}}</td>
	        
	        <td style="text-align: right;">{{number_format($detail->nominalnotapareto)}}</td>
	        <td style="text-align: right;">{{number_format($detail->nominalnotadisc)}}</td>
	        <td style="text-align: right;">{{number_format($detail->rpretur)}}</td>
	        <td style="text-align: right;">{{number_format($detail->rpjualnetto)}}</td>
	    </tr>
        @endforeach

        <tr>
          <td style="font-weight: bold;">JUMLAH</td>
          <td style="font-weight: bold; font-weight: bold; text-align: right;">{{number_format($total["nominalso"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominaldo"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominalnota"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominaltolak11"])}}</td>
          
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominaldosunatdr11"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["rpaccpiutang"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["totaltolakpiutang"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominalbomurni"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominaldoblmadanota"])}}</td>
          
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominaldoblmadanotasamasekali"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["tolakfull"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["doblmdiutakatikpiutang"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominaldoblmterpenuhifulladanotanya"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominalpenambahannota"])}}</td>
          
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominalblmaccpiutang"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominaldopareto"])}}</td>
          <td style="font-weight: bold; text-align: right;"></td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominaldodisc"])}}</td>
          <td style="font-weight: bold; text-align: right;"></td>
          
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominalnotapareto"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["nominalnotadisc"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["rpretur"])}}</td>
          <td style="font-weight: bold; text-align: right;">{{number_format($total["rpjualnetto"])}}</td>
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