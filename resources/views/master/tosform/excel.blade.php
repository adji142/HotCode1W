<html>
    <table>
        <tr><td colspan="{{ count($cols) }}"><strong>TOS Form</strong></td></tr>
        @foreach ($pars as $k => $d)
            <tr><td colspan="{{ count($cols) }}"><strong>{{ $k }} : {{ $d }}</strong></td></tr>
        @endforeach
    </table>
    <table style="border: 1px solid #000;">
        <tr>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">No</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Inputor</td>
            @foreach ($cols as $k => $d)
                <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">
                   {{ $uicols[$k] }}
                </td>
            @endforeach
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">Verified</td>
        </tr>
        @foreach ($dats as $d)
            <tr>
                <td style="border: 1px solid #000; {{ $d['verified'] == 'SUDAH' ? "background-color: #c9ffcb;" : "" }}">{{ $d['no'] }}</td>
                <td style="border: 1px solid #000; {{ $d['verified'] == 'SUDAH' ? "background-color: #c9ffcb;" : "" }}">{{ $d['inputor'] }}</td>
            @foreach ($cols as $k => $kc)
                <td style="border: 1px solid #000; {{ $d['verified'] == 'SUDAH' ? "background-color: #c9ffcb;" : "" }}">
                @if ($k == "plafon_rp")
                    {{ (isset($d[$k]) ? (is_numeric($d[$k]) ? number_format($d[$k], 0, ",", ".") : $d[$k]) : $kc) }}
                @else
                    {{ (isset($d[$k]) ? $d[$k] : $kc) }}
                @endif
                </td>
            @endforeach
                <td style="border: 1px solid #000; {{ $d['verified'] == 'SUDAH' ? "background-color: #c9ffcb;" : "" }}">{{ $d['verified'] }}</td>
            </tr>
        @endforeach
    </table>
</html>