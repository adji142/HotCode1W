<table width="100%">
	<thead>
		<tr>
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
			<td colspan="5" style="font-size: 18px; font-weight: bold;" align="left">RINCIAN PENJUALAN PER SALES</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="5" style="font-size: 12px; font-weight: bold;" align="left">Periode : {{ isset($periode) ? $periode : "" }}</td>
		</tr>
	</tbody>
</table>

<table width="100%">
	<thead>
		<tr>
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
			<td colspan="5" style="font-size: 14px; font-weight: bold;" align="left">Penjualan Brutto</td>
		</tr>
	</tbody>
</table>


<?php
	echo $tablePenjualan;
?>

<table width="100%">
	<thead>
		<tr>
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
			<td colspan="5" style="font-size: 14px; font-weight: bold;" align="left">Koreksi Jual</td>
		</tr>
	</tbody>
</table>


<?php
	echo $tableKoreksiJual;
?>

<table width="100%">
	<thead>
		<tr>
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
			<td colspan="5" style="font-size: 14px; font-weight: bold;" align="left">Retur Kotor</td>
		</tr>
	</tbody>
</table>


<?php
	echo $tableRetur;
?>

<table width="100%">
	<thead>
		<tr>
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
			<td colspan="5" style="font-size: 14px; font-weight: bold;" align="left">Koreksi Retur</td>
		</tr>
	</tbody>
</table>


<?php
	echo $tableKoreksiRetur;
?>

<table width="100%">
	<thead>
		<tr>
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
			<td colspan="5" style="font-size: 14px; font-weight: bold;" align="left">Penjualan Netto</td>
		</tr>
	</tbody>
</table>


<?php
	echo $tableNetto;
?>


<table width="100%">
	<thead>
		<tr>
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
			<td colspan="5" style="font-size: 12px; font-weight: bold;" align="left">({{ Auth::user()->name }}, {{date('d/m/Y H:i:s')}})</td>
		</tr>
	</tbody>
</table>