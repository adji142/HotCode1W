<html>
    <table>
        <tr>
            <td><strong>MONITORING OUTLET CHANNEL ({{$type_desc}})</strong></td>
        </tr>
        <tr>
            <td><strong>Tanggal Update: {{ $tanggal }}</strong></td>
        </tr>
        <tr>
            <td><strong>Cabang : {{ $subcabang }}</strong></td>
        </tr>
    </table>
    <table style="border: 1px solid #000;">
        <tr>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">ID WIL</td>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; width: 50">NAMA TOKO</td>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; width: 10">P/N</td>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; width: 10;">RODA</td>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; width: 20;">NO TELP</td>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; width: 20;">KODE SALES</td>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; width: 25;">TANGGAL FIXEDROUTE</td>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; width: 20;">SISA PLAFON</td>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; width: 20;">SKU</td>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; width: 20;">EFEKTIF OA%</td>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; width: 20;">TARGET TOKO</td>
            <td colspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; width: 20;">ACHIEVEMENT</td>
            @for($i=1;$i<=31;$i++)
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000; width: 20;">{{$i}}</td>
            @endfor
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">RP.</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">%</td>
        </tr>
        {!! $data !!}
    </table>
    <table>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td style="font-style: italic;">{{ $username }}, {{ Carbon\Carbon::now()->format('d/m/Y h:i:s') }}</td>
        </tr>
    </table>
</html>