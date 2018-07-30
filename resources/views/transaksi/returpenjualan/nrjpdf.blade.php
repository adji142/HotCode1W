@for($copy=0;$copy<=2;$copy++)
<htmlpageheader name="MyHeader">
    <h1 style="margin-bottom: 0;">Nota Retur Jual</h1>
    <table width="100%" cellspacing="0" cellpadding="2">
        <tr>
            <td width="11%">Tanggal MPR</td>
            <td>: {{$retur->tglmpr}}</td>
            <td width="11%">Nama Toko</td>
            <td>: {{ $retur->toko ? $retur->toko->namatoko : ''}}</td>
        </tr>
        <tr>
            <td>No MPR</td>
            <td>: {{$retur->nompr}}</td>
            <td>Alamat</td>
            <td>: {{ $retur->toko ? $retur->toko->alamat : ''}}</td>
        </tr>
        <tr>
            <td>Tanggal Nota</td>
            <td>: {{$retur->tglnotaretur}}</td>
            <td></td>
            <td>&nbsp;&nbsp;{{ $retur->toko ? $retur->toko->kota.', '.$retur->toko->kecamatan.', '.$retur->toko->customwilayah.', '.$retur->toko->id : ''}}</td>
        </tr>
        <tr>
            <td>No Nota</td>
            <td>: {{$retur->nonotaretur}}</td>
            <td></td>
            <td align="right" style="font-weight: bold;">Hal. {PAGENO}/{nbpg}</td>
        </tr>
    </table>
</htmlpageheader>
<sethtmlpageheader name="MyHeader" value="on" show-this-page="1"/>

<htmlpagefooter name="MyFooter">
    <i>{{$user->name.', '.date('d-m-Y h:i:s').', cetakan ke-'.$retur->printnrj}}</i>
</htmlpagefooter>
<sethtmlpagefooter name="MyFooter" value="on" />

@if($copy)
<watermarktext content="COPY {{$copy}}" alpha="0.2" />
<watermarkimage src=""/>
@else
<watermarkimage src="{{ asset('assets/img/asli.png') }}" alpha="1" size="111,97" />
@endif

<table width="100%" cellspacing="0" cellpadding="2" style="border-top: 1px solid #000;">
    <thead>
        <tr>
            <th width="7%" style="text-align:left; border-bottom: 1px solid #000;">No</th>
            <th width="55%" style="text-align:left; border-bottom: 1px solid #000;">Barang</th>
            <th width="7%" style="text-align:left; border-bottom: 1px solid #000;">Sat</th>
            <th width="7%" style="text-align:left; border-bottom: 1px solid #000;">Qty. Nota</th>
            <th width="14%" style="text-align:left; border-bottom: 1px solid #000;">Hrg. Sat. Netto</th>
            <th width="10%" style="text-align:left; border-bottom: 1px solid #000;">Jml. Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php $total = 0;?>
        @foreach($retur->details as $detail)
        <?php
            $hrgdisc1  = (1-($detail->disc1/100)) * $detail->hrgsatuanbrutto;
            $hrgdisc2  = (1-($detail->disc2/100)) * $hrgdisc1;
            $hrgppn    = (1+($detail->ppn/100)) * $hrgdisc2;
            $hrgqtynrj = $hrgppn*$detail->qtynrj;
            $total     += $hrgqtynrj;
        ?>
        <tr>
            <td>{{$loop->iteration}}.</td>
            <td>{{$detail->barang->namabarang}}</td>
            <td>{{$detail->barang->satuan}}</td>
            <td style="text-align: right;">{{$detail->qtynrj}}</td>
            <td style="text-align: right;">{{number_format($hrgppn)}}</td>
            <td style="text-align: right;">{{number_format($hrgqtynrj)}}</td>
        </tr>
        @endforeach
        <tr>
            <td style="border-top: 1px solid #000; text-align: right;" colspan="5">Total Harga Rp</td>
            <td style="border-top: 1px solid #000; text-align: right;">{{number_format($total)}}</td>
        </tr>
    </tbody>
</table>

<br/>
<table width="100%" cellspacing="0" cellpadding="2">
    <tr>
        <th width="50%" height="65px"></th>
        <th width="50%"></th>
    </tr>
    <tr>
        <td style="text-align: center;">Ka. Adm</td>
        <td style="text-align: center;">Stock</td>
    </tr>
</table>

@if($copy < 2)
<pagebreak resetpagenum="1" >
@endif
@endfor