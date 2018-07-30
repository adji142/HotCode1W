<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <h1 style="margin-bottom: 0;">DAFTAR KIRIMAN</h1>
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td width="11%">Tgl. Kirim</td>
                <td>: {{$p->tglkirim}}</td>
                <td width="11%">Sopir</td>
                <td>: {{$p->sopir->namakaryawan}}</td>
            </tr>
            <tr>
                <td>No. Kirim</td>
                <td>: {{$p->nokirim}}</td>
                <td>Kernet</td>
                <td>: {{$p->kernet->namakaryawan}}</td>
            </tr>
            <tr>
                <td>Armada Kirim</td>
                <td>: {{$p->armadakirim->namakendaraan}}</td>
                <td>No. Polisi</td>
                <td>: {{$p->armadakirim->nomorpolisi}}</td>
            </tr>
        </table>
    </div>
</htmlpageheader>
<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1"/>

<table width="100%" cellspacing="0" cellpadding="2" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
    <thead>
        <tr>
            <th width="7%" style="border-bottom: 1px solid #000;">No</th>
            <th width="53%" style="border-bottom: 1px solid #000;">Nama Toko</th>
            <th width="10%" style="border-bottom: 1px solid #000;">No. SJ</th>
            <th width="10%" style="border-bottom: 1px solid #000;">T/K</th>
            <th width="10%" style="border-bottom: 1px solid #000;">Jml. Koli</th>
        </tr>
    </thead>
    <tbody>
        @foreach($p->details as $detail)
        <tr>
            <td style="text-align: center;">{{$loop->iteration}}.</td>
            <td style="text-align: center;">{{$detail->sj->toko->namatoko}}</td>
            <td style="text-align: center;">{{$detail->sj->nosj}}</td>
            <td style="text-align: center;">{{$detail->sj->tipetransaksi}}</td>
            <td style="text-align: center;">{{$detail->sj->totalkoli()}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table width="100%" cellspacing="0" cellpadding="2">
    <tr>
        <th width="33%" height="65px"></th>
        <th width="33%"></th>
        <th width="33%"></th>
    </tr>
    <tr>
        <td style="text-align: center;">Sopir</td>
        <td style="text-align: center;">Kernet</td>
        <td style="text-align: center;">Adm. Expedisi</td>
    </tr>
</table>
<htmlpagefooter name="MyFooter">
    <i>{{auth()->user()->name.', '.Carbon\Carbon::now()->format('d-m-Y H:i:s').', cetakan ke-'.$p->print}}</i>
</htmlpagefooter>
<sethtmlpagefooter name="MyFooter" value="on" />