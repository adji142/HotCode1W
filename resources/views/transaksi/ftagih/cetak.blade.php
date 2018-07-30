@if(count($toko))
    @foreach($toko as $idx=>$tk)
    <htmlpageheader name="MyHeader{{$idx}}">
        <div style="padding-top: 1pt;">
            <table width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td><h1 style="margin-bottom: 0;">FORM TAGIH</h1></td>
                    <td width="8%"><img src="{{asset('assets/img/sas.png')}}" width="40" style="padding-top: 5pt;"></td>
                </tr>
            </table>
            <table width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td width="11%">TOKO</td>
                    <td>: {{$tk->namatoko}}</td>
                    <td width="11%">NO TELP</td>
                    <td>: {{$tk->telp}}</td>
                </tr>
                <tr>
                    <td>ALAMAT</td>
                    <td>: {{$tk->alamat}}</td>
                    <td>IDWIL</td>
                    <td>: {{$tk->customwilayah}}</td>
                </tr>
                <tr>
                    <td>DAERAH</td>
                    <td>: {{$tk->kecamatan, $tk->kota}}</td>
                </tr>
            </table>
        </div>
    </htmlpageheader>
    <sethtmlpageheader name="MyHeader{{$idx}}" value="on" show-this-page="1"/>

    <table width="100%" cellspacing="0" cellpadding="3" style="border-top: 1px solid #000;">
        <thead>
            <tr>
                <th rowspan="2" width="4%" style="border-bottom: 1px solid #000;">No</th>
                <th rowspan="2" width="10%" style="border-bottom: 1px solid #000;">No. Nota</th>
                <th rowspan="2" width="6%" style="border-bottom: 1px solid #000;">Kode</th>
                <th rowspan="2" width="12%" style="border-bottom: 1px solid #000;">Tgl Terima</th>
                <th rowspan="2" width="4%" style="border-bottom: 1px solid #000;">JW</th>
                <th rowspan="2" width="13%" style="border-bottom: 1px solid #000;">Tgl Jt Tempo</th>
                <th rowspan="2" width="12%" style="border-bottom: 1px solid #000;">Sales</th>
                <th colspan="3" style="border-bottom: 1px solid #000;">Pembayaran</th>
            </tr>
            <tr>
                <th width="13%" style="border-bottom: 1px solid #000;">Nilai</th>
                <th width="13%" style="border-bottom: 1px solid #000;">Titipan</th>
                <th width="13%" style="border-bottom: 1px solid #000;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php $i     = 1; ?>
            <?php $totalnominal = 0; ?>
            <?php $totaltitipan = 0; ?>
            <?php $totalsaldo   = 0; ?>
            @if(count($kartupiutang))
                @foreach($kartupiutang as $kp)
                    @if($kp->tokoid == $tk->id)
                    <?php $totalnominal += $kp->totalnominal; ?>
                    <?php $totaltitipan += $kp->titipan; ?>
                    <?php $totalsaldo   += $kp->totalnominal-$kp->titipan; ?>
                    <tr>
                        <td style="text-align: right;">{{$i++}}</td>
                        <td>{{$kp->nonota}}</td>
                        <td>{{$kp->tipetrans}}</td>
                        <td>{{$kp->tglterima}}</td>
                        <td style="text-align: right;">{{$kp->temponota}}</td>
                        <td>{{$kp->tgljt}}</td>
                        <td>{{$kp->kodesales}}</td>
                        <td style="text-align: right;">{{number_format($kp->totalnominal,2,',','.')}}</td>
                        <td style="text-align: right;">{{number_format($kp->titipan,2,',','.')}}</td>
                        <td style="text-align: right;">{{number_format($kp->totalnominal-$kp->titipan,2,',','.')}}</td>
                    </tr>
                    @endif
                @endforeach
            @endif
            @for($x=$i;$x<11;$x++)
            <tr>
                <td style="text-align: right;">{{$x}}</td>
                <td colspan="9">&nbsp;</td>
            </tr>
            @endfor
            <tr >
                <td  style="border-top: 1px solid #000; text-align: right;" colspan="7">Jumlah :</td>
                <td  style="border-top: 1px solid #000; text-align: right;">{{number_format($totalnominal,2,',','.')}}</td>
                <td  style="border-top: 1px solid #000; text-align: right;">{{number_format($totaltitipan,2,',','.')}}</td>
                <td  style="border-top: 1px solid #000; text-align: right;">{{number_format($totalsaldo,2,',','.')}}</td>
            </tr>
        </tbody>
    </table>
    <br/>
    <br/>
    <i>{{strtoupper($user).', '.Carbon\Carbon::now()->format('d-m-Y H:i:s')}}</i>
    @if(!$loop->last)
    <pagebreak>
    @endif
    @endforeach
@else
<htmlpageheader name="MyHeader">
    <div style="padding-top: 1pt;">
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td><h1 style="margin-bottom: 0;">FORM TAGIH</h1></td>
                <td width="8%"><img src="{{asset('assets/img/sas.png')}}" width="40" style="padding-top: 5pt;"></td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td width="11%">TOKO</td>
                <td>: <i>-kosong-</i></td>
                <td width="11%">NO TELP</td>
                <td>: <i>-kosong-</i></td>
            </tr>
            <tr>
                <td>ALAMAT</td>
                <td>: <i>-kosong-</i></td>
                <td>IDWIL</td>
                <td>: <i>-kosong-</i></td>
            </tr>
            <tr>
                <td>DAERAH</td>
                <td>: <i>-kosong-</i></td>
            </tr>
        </table>
    </div>
</htmlpageheader>
<sethtmlpageheader name="MyHeader" value="on" show-this-page="1"/>


<table width="100%" cellspacing="0" cellpadding="3" style="border-top: 1px solid #000;">
    <thead>
        <tr>
            <th rowspan="2" width="4%" style="border-bottom: 1px solid #000;">No</th>
            <th rowspan="2" width="10%" style="border-bottom: 1px solid #000;">No. Nota</th>
            <th rowspan="2" width="6%" style="border-bottom: 1px solid #000;">Kode</th>
            <th rowspan="2" width="12%" style="border-bottom: 1px solid #000;">Tgl Terima</th>
            <th rowspan="2" width="4%" style="border-bottom: 1px solid #000;">JW</th>
            <th rowspan="2" width="13%" style="border-bottom: 1px solid #000;">Tgl Jt Tempo</th>
            <th rowspan="2" width="12%" style="border-bottom: 1px solid #000;">Sales</th>
            <th colspan="3" style="border-bottom: 1px solid #000;">Pembayaran</th>
        </tr>
        <tr>
            <th width="13%" style="border-bottom: 1px solid #000;">Nilai</th>
            <th width="13%" style="border-bottom: 1px solid #000;">Titipan</th>
            <th width="13%" style="border-bottom: 1px solid #000;">Saldo</th>
        </tr>
    </thead>
    <tbody>
        @for($x=1;$x<11;$x++)
        <tr>
            <td style="text-align: right;">{{$x}}</td>
            <td colspan="9">&nbsp;</td>
        </tr>
        @endfor
        <tr >
            <td  style="border-top: 1px solid #000; text-align: right;" colspan="7">Jumlah :</td>
            <td  style="border-top: 1px solid #000; text-align: right;"></td>
            <td  style="border-top: 1px solid #000; text-align: right;"></td>
            <td  style="border-top: 1px solid #000; text-align: right;"></td>
        </tr>
    </tbody>
</table>
<br/>
<br/>
<i>{{strtoupper($user).', '.Carbon\Carbon::now()->format('d-m-Y H:i:s')}}</i>
@endif