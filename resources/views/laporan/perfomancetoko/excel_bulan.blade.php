<html>
    <table>
        <tr>
            <td><strong>LAPORAN PERFOMANCE TOKO DETAIL PER BULAN</strong></td>
        </tr>
        <tr>
            <td><strong>Tanggal Transaksi: {{ $tglmulai }} sd. {{ $tglselesai }}</strong></td>
        </tr>
        <tr>
            <td><strong>Toko: {{ $toko }}</strong></td>
        </tr>
        <tr>
            <td><strong>Salesman: {{ $salesman }}</strong></td>
        </tr>
        <tr>
            <td><strong>Kota: {{ $kota }}</strong></td>
        </tr>
        <tr>
            <td><strong>Wilid: {{ $wilid }}</strong></td>
        </tr>
        <tr>
            <td><strong>Cabang : {{ $subcabang }}</strong></td>
        </tr>
    </table>
    <table style="border: 1px solid #000;">
        <tr>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">PERIODE</td>
            <td colspan="5" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">PENJUALAN NETTO</td>
            <td colspan="5" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">PEMBAYARAN UANG</td>
            <td colspan="5" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">PEMBAYARAN NON UANG</td>
            <td colspan="9" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">PEMBAYARAN SESUAI TEMPO DENGAN UMUR NOTA (HARI)</td>
            <td colspan="3" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">PEMBAYARAN UANG SUDAH LEWAT TEMPO (HARI)</td>
            <td colspan="5" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">PENCAPAIAN PENJUALAN NETTO</td>
            <td colspan="10" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">LABA PENJUALAN NETTO</td>
            <td colspan="8" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">LABA PER TRANSAKSI</td>
            <td rowspan="2" style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">RATA-RATA PEMBAYARAN</td>
        </tr>
        <tr>
            <td></td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">B2</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">B4</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">E</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">LAINNYA</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">TOTAL</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">IMPORT R2</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">IMPORT R4</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">PABRIK</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">LAINNYA</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">TOTAL</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">IMPORT R2</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">IMPORT R4</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">PABRIK</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">LAINNYA</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">TOTAL</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">60</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">61 - 75</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">76 - 90</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">91 - 105</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">106 - 120</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">121 -  150</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">151 - 180</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">>180</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">TOTAL</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">>1 s/d 30 </td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">>30</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">TOTAL</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">TERTINGGI</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">TOKO TERTINGGI</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">WILID TERTINGGI</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">TERENDAH</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">RATA2 PER BULAN</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">B2</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">B2(%)</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">B4</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">B4(%)</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">E</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">E(%)</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">LAINNYA</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">LAINNYA(%)</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">TOTAL</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">TOTAL(%)</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">NOTA JUAL</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">%</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">RETUR JUAL</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">%</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">KOREKSI JUAL</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">%</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">KOREKSI RETUR JUAL</td>
            <td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">%</td>
        </tr>
        <tr>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000; text-align: right;">JUMLAH</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('penjualannettob2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('penjualannettob4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('penjualannettoe') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('penjualannettolainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('penjualannettototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaranuangimportr2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaranuangimportr4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaranuangpabrik') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaranuanglainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaranuangtotal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayarannonuangimportr2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayarannonuangimportr4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayarannonuangpabrik') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayarannonuanglainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayarannonuangtotal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaransesuaitempokurangdari60') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaransesuaitempoantara6175') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaransesuaitempoantara7690') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaransesuaitempoantara91105') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaransesuaitempoantara106120') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaransesuaitempoantara121150') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaransesuaitempoantara151180') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaransesuaitempolebihdari180') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaransesuaitempototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaranlewattempokurangdari30') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaranlewattempolebihdari30') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pembayaranlewattempototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pencapaiantertinggi') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('namatoko') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('customwilayah') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('pencapaianterendah') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('ratarata') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('labanettob2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('persenlabanettob2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('labanettob4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('persenlabanettob4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('labanettoe') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('persenlabanettoe') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('labanettolainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('persenlabanettolainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('labanettototal') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('persenlabanettototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('notajual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('persennotajual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('returjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('persenreturjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('koreksijual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('persenkoreksijual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('koreksireturjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->sum('persenkoreksireturjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $total['totalrata2pembayaran'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000; text-align: right;">RATA-RATA</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('penjualannettob2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('penjualannettob4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('penjualannettoe') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('penjualannettolainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('penjualannettototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaranuangimportr2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaranuangimportr4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaranuangpabrik') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaranuanglainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaranuangtotal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayarannonuangimportr2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayarannonuangimportr4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayarannonuangpabrik') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayarannonuanglainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayarannonuangtotal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaransesuaitempokurangdari60') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaransesuaitempoantara6175') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaransesuaitempoantara7690') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaransesuaitempoantara91105') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaransesuaitempoantara106120') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaransesuaitempoantara121150') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaransesuaitempoantara151180') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaransesuaitempolebihdari180') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaransesuaitempototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaranlewattempokurangdari30') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaranlewattempolebihdari30') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pembayaranlewattempototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pencapaiantertinggi') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('namatoko') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('customwilayah') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('pencapaianterendah') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('ratarata') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('labanettob2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('persenlabanettob2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('labanettob4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('persenlabanettob4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('labanettoe') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('persenlabanettoe') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('labanettolainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('persenlabanettolainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('labanettototal') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('persenlabanettototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('notajual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('persennotajual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('returjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('persenreturjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('koreksijual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('persenkoreksijual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('koreksireturjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->avg('persenkoreksireturjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $total['rata2rata2pembayaran'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000; text-align: right;">MAX</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('penjualannettob2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('penjualannettob4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('penjualannettoe') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('penjualannettolainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('penjualannettototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaranuangimportr2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaranuangimportr4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaranuangpabrik') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaranuanglainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaranuangtotal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayarannonuangimportr2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayarannonuangimportr4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayarannonuangpabrik') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayarannonuanglainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayarannonuangtotal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaransesuaitempokurangdari60') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaransesuaitempoantara6175') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaransesuaitempoantara7690') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaransesuaitempoantara91105') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaransesuaitempoantara106120') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaransesuaitempoantara121150') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaransesuaitempoantara151180') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaransesuaitempolebihdari180') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaransesuaitempototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaranlewattempokurangdari30') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaranlewattempolebihdari30') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pembayaranlewattempototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pencapaiantertinggi') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('namatoko') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('customwilayah') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('pencapaianterendah') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('ratarata') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('labanettob2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('persenlabanettob2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('labanettob4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('persenlabanettob4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('labanettoe') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('persenlabanettoe') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('labanettolainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('persenlabanettolainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('labanettototal') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('persenlabanettototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('notajual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('persennotajual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('returjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('persenreturjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('koreksijual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('persenkoreksijual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('koreksireturjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->max('persenkoreksireturjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $total['maxrata2pembayaran'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000; text-align: right;">MIN</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('penjualannettob2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('penjualannettob4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('penjualannettoe') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('penjualannettolainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('penjualannettototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaranuangimportr2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaranuangimportr4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaranuangpabrik') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaranuanglainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaranuangtotal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayarannonuangimportr2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayarannonuangimportr4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayarannonuangpabrik') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayarannonuanglainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayarannonuangtotal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaransesuaitempokurangdari60') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaransesuaitempoantara6175') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaransesuaitempoantara7690') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaransesuaitempoantara91105') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaransesuaitempoantara106120') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaransesuaitempoantara121150') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaransesuaitempoantara151180') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaransesuaitempolebihdari180') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaransesuaitempototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaranlewattempokurangdari30') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaranlewattempolebihdari30') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pembayaranlewattempototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pencapaiantertinggi') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('namatoko') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('customwilayah') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('pencapaianterendah') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('ratarata') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('labanettob2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('persenlabanettob2') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('labanettob4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('persenlabanettob4') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('labanettoe') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('persenlabanettoe') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('labanettolainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('persenlabanettolainnya') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('labanettototal') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('persenlabanettototal') }}</td>

            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('notajual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('persennotajual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('returjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('persenreturjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('koreksijual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('persenkoreksijual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('koreksireturjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $datas->min('persenkoreksireturjual') }}</td>
            <td style="font-weight: bold;background-color: #dbe5f1; border: 1px solid #000;">{{ $total['minrata2pembayaran'] }}</td>
        </tr>
        @foreach($datas as $toko)
            <tr>
                <td style="border: 1px solid #000;text-align: left;">{{ $toko->periode }}</td>

                <td style="border: 1px solid #000;">{{ $toko->penjualannettob2 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->penjualannettob4 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->penjualannettoe }}</td>
                <td style="border: 1px solid #000;">{{ $toko->penjualannettolainnya }}</td>
                <td style="border: 1px solid #000;">{{ $toko->penjualannettototal }}</td>

                <td style="border: 1px solid #000;">{{ $toko->pembayaranuangimportr2 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaranuangimportr4 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaranuangpabrik }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaranuanglainnya }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaranuangtotal }}</td>

                <td style="border: 1px solid #000;">{{ $toko->pembayarannonuangimportr2 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayarannonuangimportr4 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayarannonuangpabrik }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayarannonuanglainnya }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayarannonuangtotal }}</td>

                <td style="border: 1px solid #000;">{{ $toko->pembayaransesuaitempokurangdari60 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaransesuaitempoantara6175 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaransesuaitempoantara7690 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaransesuaitempoantara91105 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaransesuaitempoantara106120 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaransesuaitempoantara121150 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaransesuaitempoantara151180 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaransesuaitempolebihdari180 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaransesuaitempototal }}</td>

                <td style="border: 1px solid #000;">{{ $toko->pembayaranlewattempokurangdari30 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaranlewattempolebihdari30 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pembayaranlewattempototal }}</td>

                <td style="border: 1px solid #000;">{{ $toko->pencapaiantertinggi }}</td>
                <td style="border: 1px solid #000;">{{ $toko->namatoko }}</td>
                <td style="border: 1px solid #000;">{{ $toko->customwilayah }}</td>
                <td style="border: 1px solid #000;">{{ $toko->pencapaianterendah }}</td>
                <td style="border: 1px solid #000;">{{ $toko->ratarata }}</td>

                <td style="border: 1px solid #000;">{{ $toko->labanettob2 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->persenlabanettob2 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->labanettob4 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->persenlabanettob4 }}</td>
                <td style="border: 1px solid #000;">{{ $toko->labanettoe }}</td>
                <td style="border: 1px solid #000;">{{ $toko->persenlabanettoe }}</td>
                <td style="border: 1px solid #000;">{{ $toko->labanettolainnya }}</td>
                <td style="border: 1px solid #000;">{{ $toko->persenlabanettolainnya }}</td>
                <td style="border: 1px solid #000;">{{ $toko->labanettototal }}</td>
                <td style="border: 1px solid #000;">{{ $toko->persenlabanettototal }}</td>

                <td style="border: 1px solid #000;">{{ $toko->notajual }}</td>
                <td style="border: 1px solid #000;">{{ $toko->persennotajual }}</td>
                <td style="border: 1px solid #000;">{{ $toko->returjual }}</td>
                <td style="border: 1px solid #000;">{{ $toko->persenreturjual }}</td>
                <td style="border: 1px solid #000;">{{ $toko->koreksijual }}</td>
                <td style="border: 1px solid #000;">{{ $toko->persenkoreksijual }}</td>
                <td style="border: 1px solid #000;">{{ $toko->koreksireturjual }}</td>
                <td style="border: 1px solid #000;">{{ $toko->persenkoreksireturjual }}</td>

                <td style="border: 1px solid #000;">{{ ($toko->pembayaranuangtotal+$toko->pembayarannonuangtotal)/$totalBulan }}</td>
            </tr>
        @endforeach
    </table>
    <table>
        <tr>
            <td style="font-style: italic;">{{ $username }}, {{ Carbon\Carbon::now()->format('d/m/Y h:i:s') }}</td>
        </tr>
    </table>
</html>