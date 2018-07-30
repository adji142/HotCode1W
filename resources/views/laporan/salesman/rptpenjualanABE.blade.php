<table width="100%">
	<thead>
		<tr>
			<th width="5px"></th>
			<th width="30px"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td></td>
			<td colspan="5" style="font-size: 18px; font-weight: bold;" align="left">PERINCIAN PENJUALAN PER SALES (HPP RATA-RATA)</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="5" style="font-size: 12px; font-weight: bold;" align="left">Periode : {{ isset($periode) ? $periode : "" }}</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="5" style="font-size: 12px; font-weight: bold;" align="left">{{ isset($jenis) ? $jenis : "" }}</td>
		</tr>
	</tbody>
</table>


<?php
	echo $table;
?>


<table width="100%">
	<thead>
		<tr>
			<th width="5px"></th>
			<th width="20px"></th>
			<!-- <th width="20px"></th>
			<th width="20px"></th>
			<th width="50px"></th>
			<th width="30px"></th> -->
		</tr>
	</thead>
	<tbody>
		<tr>
			<td></td>
			<td colspan="5" style="font-size: 12px; font-weight: bold;" align="left">({{ Auth::user()->name }}, {{date('d/m/Y H:i:s')}})</td>
		</tr>
	</tbody>
</table>