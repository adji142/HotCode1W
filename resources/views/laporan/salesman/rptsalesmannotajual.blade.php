<table width="100%">
	<thead>
		<tr>
			<th width="5px"></th>
			<th width="5px"></th>
			<th width="10px"></th>
			<th width="10px"></th>
			<th width="10px"></th>
			<th width="10px"></th>
			<th width="10px"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td></td>
			<td colspan="6" style="font-size: 18px; font-weight: bold;" align="left">LAPORAN SALESMAN NOTA JUAL</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="6" style="font-size: 12px; font-weight: bold;" align="left">Periode : {{ isset($periode) ? $periode : "" }}</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="6" style="font-size: 12px; font-weight: bold;" align="left">Salesman : {{ isset($salesman) ? $salesman : "" }}</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="6" style="font-size: 12px; font-weight: bold;" align="left">WILID : {{ isset($wilid) ? $wilid : "" }}</td>
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
			<th width="5px"></th>
			<th width="10px"></th>
			<th width="10px"></th>
			<th width="10px"></th>
			<th width="10px"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td></td>
			<td colspan="5" style="font-size: 12px; font-weight: bold;" align="left">({{ Auth::user()->name }}, {{date('d/m/Y H:i:s')}})</td>
		</tr>
	</tbody>
</table>