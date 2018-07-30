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
	<h3>PENJUALAN HUBUNGAN ISTIMEWA CABANG 18 </h3>
	<h4>Periode : </h4>


	<?php
		echo $table;
	?>
</div>

<iframe id="printArea" style="display:none"></iframe>

@endsection

@push('scripts')\
	<script type="text/javascript">
		$("#btnSimpanPdf").click(function(){
			setTimeout(function(){
				window.print();
			},150);
			$("#btnClose").trigger("click");
		});

		$("#btnSimpanXls").click(function(){
			setTimeout(function(){
				fnExcelReport();
			},150);
			// $.ajax({
			// 	url : "{{ route('laporan.tampil') }}",
			// 	type : "POST",
			// 	data : {
			// 		_token : "{{ csrf_token() }}",
			// 		markup : document.getElementById("databar").innerHTML
			// 	},
			// 	success : function(){
					
			// 	}
			// })
		});

		function fnExcelReport()
		{
		    var data = document.getElementById("databar").innerHTML;

		    data = data.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
		    data = data.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
		    data = data.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

		    var ua = window.navigator.userAgent;
		    var msie = ua.indexOf("MSIE "); 

		    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
		    {
		        printArea.document.open("txt/html","replace");
		        printArea.document.write(data);
		        printArea.document.close();
		        printArea.focus(); 
		        sa = printArea.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
		    }  
		    else                 //other browser not tested on IE 11
		        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(data));  
		    	//sa = window.open("data:application/pdf;base64, " + encodeURIComponent(data));

		    return (sa);
		}
	</script>

@endpush