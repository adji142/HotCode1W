@foreach($data as $row)
    <tr>
        <td>{{ $row->customwilayah }}</td>
        <td>{{ $row->namatoko }}</td>
        <td>{{ $row->pn }}</td>
        <td>{{ $row->roda }}</td>
        <td>{{ $row->telp }}</td>
        <td>{{ $row->kodesales }}</td>
        <td>{{ $row->tglfixedroute }}</td>
        <td style="text-align:right;">{{ ($row->sisa_plafon-$row->sisa_piutang) }}</td>
        <td style="text-align:right;">{{ $row->sku }}</td>
        <td style="text-align:right;">{{ $row->efektif_oa }}</td>
        <td style="text-align:right;">{{ $row->targetomset }}</td>
        <td style="text-align:right;">{{ $row->achievement_rp }}</td>
        <td style="text-align:right;">{{ $row->achievement_persen }}</td>
        @for($i=1;$i<=31;$i++)
        <td style="text-align:right;">{{ $row->{'total_'.$i} }}</td>
        @endfor
    </tr>
@endforeach
