<htmlpageheader name="MyHeader1">
    <div style="position: absolute;top:15mm;left:15mm">
        <table cellspacing="0" cellpadding="2">
            <tr>
                <td>{{($sj->toko == null) ? '-' : $sj->toko->namatoko}} ({{($sj->toko == null) ? '-' : $sj->toko->customwilayah}})</td>
            </tr>
            <tr>
                <td>{{($sj->toko == null) ? '-' : $sj->toko->alamat}}</td>
            </tr>
            <tr>
                <td>{{($sj->toko == null) ? '-' : $sj->toko->kecamatan }}</td>
            </tr>
            <tr>
                <td>{{($sj->toko == null) ? '-' : $sj->toko->kota}}</td>
            </tr>
            <tr>
                <td>Telepon: {{ ($sj->toko == null) ? '-' : $sj->toko->telp }}</td>
            </tr>
        </table>
    </div>
</htmlpageheader>
<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1"/>