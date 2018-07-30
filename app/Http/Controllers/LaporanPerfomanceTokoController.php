<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Toko;
use App\Models\SubCabang;
use App\Models\RekapPenjualanPerItemMonthly;
use App\Models\ReportLog;
use Carbon\Carbon;
use DB;
use EXCEL;

class LaporanPerfomanceTokoController extends Controller
{
    public function index(Request $req)
    {
    	return view('laporan.perfomancetoko.index');
    }

    public function insert_reportlog($recordownerid,$userid,$reportname)
    {
        $reportlog = ReportLog::create([
            'recordownerid' => $recordownerid,
            'userid'        => $userid,
            'reportname'    => $reportname,
            'createdon'     => Carbon::now(),
        ]);
    }

    public function cetak(Request $req)
    {
        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFOMANCE TOKO');
    	$columns = array(
            0 => 'distinct batch.rekappenjualanperitemmonthly.periode',
    		1 => 'mstr.toko.namatoko',
    		2 => 'mstr.toko.customwilayah',
            3 => "sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) as penjualannettob2",
            4 => "sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) as penjualannettob4",
            5 => "sum(case when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) as penjualannettoe",
            6 => "sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) as penjualannettolainnya",
            7 => "sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) as penjualannettototal",
            8 => "ptg1.pembayaranuangimportr2,ptg1.pembayaranuangimportr4,ptg1.pembayaranuangpabrik,ptg1.pembayaranuanglainnya,ptg1.pembayaranuangtotal",
            9 => "ptg1.pembayarannonuangimportr2,ptg1.pembayarannonuangimportr4,ptg1.pembayarannonuangpabrik,ptg1.pembayarannonuanglainnya,ptg1.pembayarannonuangtotal",
            10 => "ptg1.pembayaransesuaitempokurangdari60,ptg1.pembayaransesuaitempoantara6175,ptg1.pembayaransesuaitempoantara7690,ptg1.pembayaransesuaitempoantara91105,ptg1.pembayaransesuaitempoantara106120,ptg1.pembayaransesuaitempoantara121150,ptg1.pembayaransesuaitempoantara151180,ptg1.pembayaransesuaitempolebihdari180,ptg1.pembayaransesuaitempototal",
            11 => "ptg1.pembayaranlewattempokurangdari30,ptg1.pembayaranlewattempolebihdari30,ptg1.pembayaranlewattempototal",
            12 => "q.pencapaiantertinggi",
            13 => "q.pencapaianterendah",
            14 => "q.ratarata",
            15 => "sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab2 else 0 end) as labanettob2",
            16 => "case when sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) = 0 then 0 else sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab2 else 0 end)/sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) end as persenlabanettob2",
            17 => "sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab4 else 0 end) as labanettob4",
            18 => "case when sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) = 0 then 0 else sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab4 else 0 end)/sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) end as persenlabanettob4",
            19 => "sum(case when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppae else 0 end) as labanettoe",
            20 => "case when sum(case when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) = 0 then 0 else sum(case when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppae else 0 end)/sum(case when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) end as persenlabanettoe",
            21 => "sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppalainnya else 0 end) as labanettolainnya",
            22 => "case when sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) = 0 then 0 else sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppalainnya else 0 end)/sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) end as persenlabanettolainnya",
            23 => "sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab2
                    when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab4
                    when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppae
                    when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppalainnya
                    else 0 end) as labanettototal",
            24 => "case when sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) = 0 then 0 else sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab2
                    when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab4
                    when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppae
                    when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppalainnya
                    else 0 end)/sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) end as persenlabanettototal",
            25 => "sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end) as notajual",
            26 => "case when sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) = 0 then 0 else sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end)/sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) end as persennotajual",
            27 => "sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Retur Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end) as returjual",
            28 => "case when sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) = 0 then 0 else sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Retur Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end)/sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) end as persenreturjual",
            29 => "sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Koreksi Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end) as koreksijual",
            30 => "case when sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) = 0 then 0 else sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Koreksi Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end)/sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) end as persenkoreksijual",
            31 => "sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Koreksi Retur Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end) as koreksireturjual",
            32 => "case when sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) = 0 then 0 else sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Koreksi Retur Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end)/sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) end as persenkoreksireturjual",
    	);
    	$toko = RekapPenjualanPerItemMonthly::selectRaw(collect($columns)->implode(', '));
        $toko->leftJoin('mstr.toko','batch.rekappenjualanperitemmonthly.tokoid','=','mstr.toko.id');
        $toko->leftJoin('mstr.stock','batch.rekappenjualanperitemmonthly.stockid','=','mstr.stock.id');
        $toko->leftJoin(DB::raw("
            (select ptg.kartupiutang.tokoid,
            sum(case when ((ptg.kartupiutang.tipetrans in ('K4','KB','T4','TB')) and (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC'))) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuangimportr2,
            sum(case when ((ptg.kartupiutang.tipetrans in ('K2','T2')) and (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC'))) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuangimportr4,
            sum(case when ((ptg.kartupiutang.tipetrans in ('KC','TC')) and (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC'))) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuangpabrik,
            sum(case when ((ptg.kartupiutang.tipetrans not in ('K2','K4','KB','KC','T2','T4','TC','TB')) and (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC'))) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuanglainnya,
            sum(case when (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC')) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuangtotal,
            sum(case when (ptg.kartupiutang.tipetrans in ('K4','KB','T4','TB')) and (left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC')) then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuangimportr2,
            sum(case when (ptg.kartupiutang.tipetrans in ('K2','T2')) and (left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC')) then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuangimportr4,
            sum(case when (ptg.kartupiutang.tipetrans in ('KC','TC')) and (left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC')) then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuangpabrik,
            sum(case when (ptg.kartupiutang.tipetrans not in ('K2','K4','KB','KC','T2','T4','TC','TB')) and (left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC')) then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuanglainnya,
            sum(case when left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC') then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuangtotal,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 0 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 60 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempokurangdari60,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 60 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 75 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara6175,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 75 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 90 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara7690,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 90 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 105 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara91105,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 105 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 120 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara106120,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 120 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 150 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara121150,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 150 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 180 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara151180,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 180  and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempolebihdari180,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempototal,
            sum(case when ptg.kartupiutang.tgljt<ptg.kartupiutang.tglterima and ptg.kartupiutang.tglterima-ptg.kartupiutang.tgljt > 0 and ptg.kartupiutang.tglterima-ptg.kartupiutang.tgljt <= 30 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranlewattempokurangdari30,
            sum(case when ptg.kartupiutang.tgljt<ptg.kartupiutang.tglterima and ptg.kartupiutang.tglterima-ptg.kartupiutang.tgljt > 30 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranlewattempolebihdari30,
            sum(case when ptg.kartupiutang.tgljt<ptg.kartupiutang.tglterima and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranlewattempototal
            from ptg.kartupiutang
            left join ptg.kartupiutangdetail on ptg.kartupiutang.id = ptg.kartupiutangdetail.kartupiutangid
            group by ptg.kartupiutang.tokoid) ptg1
            "), 'mstr.toko.id','=','ptg1.tokoid');
        $toko->leftJoin(DB::raw("
            (select mstr.stock.id,a.nominalhppa as hppa,
            sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then a.nominalhppa else 0 end) as hppab2,
            sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then a.nominalhppa else 0 end) as hppab4,
            sum(case when left(mstr.stock.kodebarang,2) in ('FE') then a.nominalhppa else 0 end) as hppae,
            sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then a.nominalhppa else 0 end) as hppalainnya
            from acc.historyhppa a
            inner join (select stockid, max(acc.historyhppa.tglaktif) as tglaktif
                from acc.historyhppa
                group by stockid) b
                on a.stockid = b.stockid and a.tglaktif = b.tglaktif
            inner join mstr.stock on a.stockid = mstr.stock.id
            group by mstr.stock.id,a.nominalhppa) acc1
            "), 'mstr.stock.id','=','acc1.id');
        $toko->join(DB::raw("
            (select tokoid,
            max(subtotalnettojual) as pencapaiantertinggi,
            min(subtotalnettojual) as pencapaianterendah,
            avg(subtotalnettojual) as ratarata
            from batch.rekappenjualanperitemmonthly
            where periode >= '".Carbon::parse($req->tglmulai)->format('Ym')."' and periode <= '".Carbon::parse($req->tglselesai)->format('Ym')."'
            group by tokoid) q
            "), function($join){
                $join->on('mstr.toko.id','=','q.tokoid');
                $join->on('batch.rekappenjualanperitemmonthly.subtotalnettojual','=','q.pencapaiantertinggi');
            });
        // $toko->where('batch.rekappenjualanperitemmonthly.recordownerid',session('subcabang'));
        $toko->where("batch.rekappenjualanperitemmonthly.periode",'>=',Carbon::parse($req->tglmulai)->format('Ym'));
    	$toko->where("batch.rekappenjualanperitemmonthly.periode",'<=',Carbon::parse($req->tglselesai)->format('Ym'));
        $toko->groupBy([
            "batch.rekappenjualanperitemmonthly.periode",
            "mstr.toko.namatoko",
            "mstr.toko.customwilayah",
            "q.pencapaiantertinggi","q.pencapaianterendah","q.ratarata",
            "ptg1.pembayaranuangimportr2","ptg1.pembayaranuangimportr4","ptg1.pembayaranuangpabrik","ptg1.pembayaranuanglainnya","ptg1.pembayaranuangtotal",
            "ptg1.pembayarannonuangimportr2","ptg1.pembayarannonuangimportr4","ptg1.pembayarannonuangpabrik","ptg1.pembayarannonuanglainnya","ptg1.pembayarannonuangtotal",
            "ptg1.pembayaransesuaitempokurangdari60","ptg1.pembayaransesuaitempoantara6175","ptg1.pembayaransesuaitempoantara7690","ptg1.pembayaransesuaitempoantara91105","ptg1.pembayaransesuaitempoantara106120","ptg1.pembayaransesuaitempoantara121150","ptg1.pembayaransesuaitempoantara151180","ptg1.pembayaransesuaitempolebihdari180","ptg1.pembayaransesuaitempototal",
            "ptg1.pembayaranlewattempokurangdari30","ptg1.pembayaranlewattempolebihdari30","ptg1.pembayaranlewattempototal",
        ]);
        $data       = $toko->get();
        $totalBulan = Carbon::parse($req->tglmulai)->diffInMonths(Carbon::parse($req->tglselesai))+1;
        $subcabang  = SubCabang::find(session('subcabang'))->kodesubcabang;
        $username   = strtoupper(auth()->user()->username);
        $tglmulai   = Carbon::parse($req->tglmulai)->format('d/m/Y');
        $tglselesai = Carbon::parse($req->tglselesai)->format('d/m/Y');
        $toko       = $req->toko;
        $salesman   = $req->salesman;
        $gudang     = $req->gudang;
        $kota       = $req->kota;
        $wilid      = $req->wilid;

        Excel::create('Laporan Perfomance Toko Rekap Toko', function($excel) use ($data,$totalBulan,$username,$toko,$salesman,$gudang,$kota,$wilid,$tglmulai,$tglselesai,$subcabang){
            $excel->setTitle('Laporan Perfomance Toko Rekap Toko');
            $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
            $excel->setmanager(strtoupper(auth()->user()->username));
            $excel->setsubject('Laporan Perfomance Toko Rekap Toko');
            $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
            $excel->setDescription('Laporan Perfomance Toko Rekap Toko');
            $excel->sheet('LaporanPerfomanceTokoRekapToko', function($sheet) use ($data,$totalBulan,$username,$toko,$salesman,$gudang,$kota,$wilid,$tglmulai,$tglselesai,$subcabang){
                $sheet->setColumnFormat(array(
                    'C' => '#,##0','D' => '#,##0','E' => '#,##0','F' => '#,##0','G' => '#,##0',
                    'H' => '#,##0','I' => '#,##0','J' => '#,##0','K' => '#,##0','L' => '#,##0',
                    'M' => '#,##0','N' => '#,##0','O' => '#,##0','P' => '#,##0','Q' => '#,##0',
                    'R' => '#,##0','S' => '#,##0','T' => '#,##0','U' => '#,##0','V' => '#,##0','W' => '#,##0','X' => '#,##0','Y' => '#,##0','Z' => '#,##0',
                    'AA' => '#,##0','AB' => '#,##0','AC' => '#,##0',
                    'AD' => '#,##0','AF' => '#,##0','AG' => '#,##0',
                    'AH' => '#,##0','AI' => '0.00%','AJ' => '#,##0','AK' => '0.00%','AL' => '#,##0','AM' => '0.00%','AN' => '#,##0','AO' => '0.00%','AP' => '#,##0','AQ' => '0.00%',
                    'AR' => '#,##0','AS' => '0.00%','AT' => '#,##0','AU' => '0.00%','AV' => '#,##0','AW' => '0.00%','AX' => '#,##0','AY' => '0.00%','AZ' => '#,##0',
                ));
                $sheet->loadView('laporan.perfomancetoko.excel',array(
                    'datas'      => $data,
                    'totalBulan' => $totalBulan,
                    'username'   => $username,
                    'toko'       => $toko,
                    'salesman'   => $salesman,
                    'gudang'     => $gudang,
                    'kota'       => $kota,
                    'wilid'      => $wilid,
                    'tglmulai'   => $tglmulai,
                    'tglselesai' => $tglselesai,
                    'subcabang'  => $subcabang
                ));
            });
        })->store('xls', storage_path('excel/exports'));

        $urlExcel = asset('storage/excel/exports/Laporan Perfomance Toko Rekap Toko.xls');

    	return view('laporan.perfomancetoko.preview',compact('data','totalBulan','urlExcel'));
    }

    public function cetakBulan(Request $req)
    {
        $columns = array(
            0 => 'batch.rekappenjualanperitemmonthly.periode',
            1 => 'mstr.toko.namatoko',
            2 => 'mstr.toko.customwilayah',
            3 => "sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) as penjualannettob2",
            4 => "sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) as penjualannettob4",
            5 => "sum(case when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) as penjualannettoe",
            6 => "sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) as penjualannettolainnya",
            7 => "sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) as penjualannettototal",
            8 => "ptg1.pembayaranuangimportr2,ptg1.pembayaranuangimportr4,ptg1.pembayaranuangpabrik,ptg1.pembayaranuanglainnya,ptg1.pembayaranuangtotal",
            9 => "ptg1.pembayarannonuangimportr2,ptg1.pembayarannonuangimportr4,ptg1.pembayarannonuangpabrik,ptg1.pembayarannonuanglainnya,ptg1.pembayarannonuangtotal",
            10 => "ptg1.pembayaransesuaitempokurangdari60,ptg1.pembayaransesuaitempoantara6175,ptg1.pembayaransesuaitempoantara7690,ptg1.pembayaransesuaitempoantara91105,ptg1.pembayaransesuaitempoantara106120,ptg1.pembayaransesuaitempoantara121150,ptg1.pembayaransesuaitempoantara151180,ptg1.pembayaransesuaitempolebihdari180,ptg1.pembayaransesuaitempototal",
            11 => "ptg1.pembayaranlewattempokurangdari30,ptg1.pembayaranlewattempolebihdari30,ptg1.pembayaranlewattempototal",
            12 => "q.pencapaiantertinggi",
            13 => "q.pencapaianterendah",
            14 => "q.ratarata",
            15 => "sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab2 else 0 end) as labanettob2",
            16 => "case when sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) = 0 then 0 else sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab2 else 0 end)/sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) end as persenlabanettob2",
            17 => "sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab4 else 0 end) as labanettob4",
            18 => "case when sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) = 0 then 0 else sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab4 else 0 end)/sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) end as persenlabanettob4",
            19 => "sum(case when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppae else 0 end) as labanettoe",
            20 => "case when sum(case when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) = 0 then 0 else sum(case when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppae else 0 end)/sum(case when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) end as persenlabanettoe",
            21 => "sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppalainnya else 0 end) as labanettolainnya",
            22 => "case when sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) = 0 then 0 else sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppalainnya else 0 end)/sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) end as persenlabanettolainnya",
            23 => "sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab2
                    when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab4
                    when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppae
                    when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppalainnya
                    else 0 end) as labanettototal",
            24 => "case when sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) = 0 then 0 else sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab2
                    when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab4
                    when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppae
                    when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppalainnya
                    else 0 end)/sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) end as persenlabanettototal",
            25 => "sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end) as notajual",
            26 => "case when sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) = 0 then 0 else sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end)/sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) end as persennotajual",
            27 => "sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Retur Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end) as returjual",
            28 => "case when sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) = 0 then 0 else sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Retur Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end)/sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) end as persenreturjual",
            29 => "sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Koreksi Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end) as koreksijual",
            30 => "case when sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) = 0 then 0 else sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Koreksi Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end)/sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) end as persenkoreksijual",
            31 => "sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Koreksi Retur Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end) as koreksireturjual",
            32 => "case when sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) = 0 then 0 else sum(case when batch.rekappenjualanperitemmonthly.keterangan = 'Koreksi Retur Penjualan' then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppa else 0 end)/sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) end as persenkoreksireturjual",
        );
        $toko = RekapPenjualanPerItemMonthly::selectRaw(collect($columns)->implode(', '));
        $toko->leftJoin('mstr.toko','batch.rekappenjualanperitemmonthly.tokoid','=','mstr.toko.id');
        $toko->leftJoin('mstr.stock','batch.rekappenjualanperitemmonthly.stockid','=','mstr.stock.id');
        $toko->leftJoin(DB::raw("
            (select to_char(ptg.kartupiutang.tglterima,'YYYYMM') as periode,
            sum(case when ((ptg.kartupiutang.tipetrans in ('K4','KB','T4','TB')) and (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC'))) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuangimportr2,
            sum(case when ((ptg.kartupiutang.tipetrans in ('K2','T2')) and (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC'))) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuangimportr4,
            sum(case when ((ptg.kartupiutang.tipetrans in ('KC','TC')) and (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC'))) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuangpabrik,
            sum(case when ((ptg.kartupiutang.tipetrans not in ('K2','K4','KB','KC','T2','T4','TC','TB')) and (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC'))) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuanglainnya,
            sum(case when (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC')) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuangtotal,
            sum(case when (ptg.kartupiutang.tipetrans in ('K4','KB','T4','TB')) and (left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC')) then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuangimportr2,
            sum(case when (ptg.kartupiutang.tipetrans in ('K2','T2')) and (left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC')) then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuangimportr4,
            sum(case when (ptg.kartupiutang.tipetrans in ('KC','TC')) and (left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC')) then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuangpabrik,
            sum(case when (ptg.kartupiutang.tipetrans not in ('K2','K4','KB','KC','T2','T4','TC','TB')) and (left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC')) then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuanglainnya,
            sum(case when left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC') then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuangtotal,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 0 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 60 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempokurangdari60,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 60 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 75 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara6175,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 75 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 90 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara7690,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 90 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 105 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara91105,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 105 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 120 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara106120,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 120 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 150 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara121150,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 150 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 180 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara151180,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 180  and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempolebihdari180,
            sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempototal,
            sum(case when ptg.kartupiutang.tgljt<ptg.kartupiutang.tglterima and ptg.kartupiutang.tglterima-ptg.kartupiutang.tgljt > 0 and ptg.kartupiutang.tglterima-ptg.kartupiutang.tgljt <= 30 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranlewattempokurangdari30,
            sum(case when ptg.kartupiutang.tgljt<ptg.kartupiutang.tglterima and ptg.kartupiutang.tglterima-ptg.kartupiutang.tgljt > 30 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranlewattempolebihdari30,
            sum(case when ptg.kartupiutang.tgljt<ptg.kartupiutang.tglterima and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranlewattempototal
            from ptg.kartupiutang
            left join ptg.kartupiutangdetail on ptg.kartupiutang.id = ptg.kartupiutangdetail.kartupiutangid
            group by to_char(ptg.kartupiutang.tglterima,'YYYYMM')) ptg1
            "), 'batch.rekappenjualanperitemmonthly.periode','=','ptg1.periode');
        $toko->leftJoin(DB::raw("
            (select mstr.stock.id,a.nominalhppa as hppa,
            sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then a.nominalhppa else 0 end) as hppab2,
            sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then a.nominalhppa else 0 end) as hppab4,
            sum(case when left(mstr.stock.kodebarang,2) in ('FE') then a.nominalhppa else 0 end) as hppae,
            sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then a.nominalhppa else 0 end) as hppalainnya
            from acc.historyhppa a
            inner join (select stockid, max(acc.historyhppa.tglaktif) as tglaktif
                from acc.historyhppa
                group by stockid) b
                on a.stockid = b.stockid and a.tglaktif = b.tglaktif
            inner join mstr.stock on a.stockid = mstr.stock.id
            group by mstr.stock.id,a.nominalhppa) acc1
            "), 'mstr.stock.id','=','acc1.id');
        $toko->join(DB::raw("
            (select periode,
            max(subtotalnettojual) as pencapaiantertinggi,
            min(subtotalnettojual) as pencapaianterendah,
            avg(subtotalnettojual) as ratarata
            from batch.rekappenjualanperitemmonthly
            where periode >= '".Carbon::parse($req->tglmulai)->format('Ym')."' and periode <= '".Carbon::parse($req->tglselesai)->format('Ym')."'
            group by periode) q
            "), function($join){
                $join->on('batch.rekappenjualanperitemmonthly.periode','=','q.periode');
                $join->on('batch.rekappenjualanperitemmonthly.subtotalnettojual','=','q.pencapaiantertinggi');
            });
        // $toko->where('batch.rekappenjualanperitemmonthly.recordownerid',session('subcabang'));
        $toko->where("batch.rekappenjualanperitemmonthly.periode",'>=',Carbon::parse($req->tglmulai)->format('Ym'));
        $toko->where("batch.rekappenjualanperitemmonthly.periode",'<=',Carbon::parse($req->tglselesai)->format('Ym'));
        $toko->groupBy([
            "batch.rekappenjualanperitemmonthly.periode",
            "mstr.toko.namatoko","mstr.toko.customwilayah",
            "q.pencapaiantertinggi","q.pencapaianterendah","q.ratarata",
            "ptg1.pembayaranuangimportr2","ptg1.pembayaranuangimportr4","ptg1.pembayaranuangpabrik","ptg1.pembayaranuanglainnya","ptg1.pembayaranuangtotal",
            "ptg1.pembayarannonuangimportr2","ptg1.pembayarannonuangimportr4","ptg1.pembayarannonuangpabrik","ptg1.pembayarannonuanglainnya","ptg1.pembayarannonuangtotal",
            "ptg1.pembayaransesuaitempokurangdari60","ptg1.pembayaransesuaitempoantara6175","ptg1.pembayaransesuaitempoantara7690","ptg1.pembayaransesuaitempoantara91105","ptg1.pembayaransesuaitempoantara106120","ptg1.pembayaransesuaitempoantara121150","ptg1.pembayaransesuaitempoantara151180","ptg1.pembayaransesuaitempolebihdari180","ptg1.pembayaransesuaitempototal",
            "ptg1.pembayaranlewattempokurangdari30","ptg1.pembayaranlewattempolebihdari30","ptg1.pembayaranlewattempototal",
        ]);
        $data       = $toko->get();
        $totalBulan = Carbon::parse($req->tglmulai)->diffInMonths(Carbon::parse($req->tglselesai))+1;
        $total      = array(
            'totalrata2pembayaran' => $data->sum(function ($jual) use ($totalBulan){
                return ($jual->pembayaranuangtotal+$jual->pembayarannonuangtotal)/$totalBulan;
            }),
            'rata2rata2pembayaran' => $data->avg(function ($jual) use ($totalBulan){
                return ($jual->pembayaranuangtotal+$jual->pembayarannonuangtotal)/$totalBulan;
            }),
            'maxrata2pembayaran' => $data->max(function ($jual) use ($totalBulan){
                return ($jual->pembayaranuangtotal+$jual->pembayarannonuangtotal)/$totalBulan;
            }),
            'minrata2pembayaran' => $data->min(function ($jual) use ($totalBulan){
                return ($jual->pembayaranuangtotal+$jual->pembayarannonuangtotal)/$totalBulan;
            })
        );
        $subcabang  = SubCabang::find(session('subcabang'))->kodesubcabang;
        $username   = strtoupper(auth()->user()->username);
        $tglmulai   = Carbon::parse($req->tglmulai)->format('d/m/Y');
        $tglselesai = Carbon::parse($req->tglselesai)->format('d/m/Y');
        $toko       = $req->toko;
        $salesman   = $req->salesman;
        $gudang     = $req->gudang;
        $kota       = $req->kota;
        $wilid      = $req->wilid;

        Excel::create('Laporan Perfomance Toko Detail Per Bulan', function($excel) use ($data,$totalBulan,$username,$toko,$salesman,$gudang,$kota,$wilid,$tglmulai,$tglselesai,$subcabang,$total){
            $excel->setTitle('Laporan Perfomance Toko Detail Per Bulan');
            $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
            $excel->setmanager(strtoupper(auth()->user()->username));
            $excel->setsubject('Laporan Perfomance Toko Detail Per Bulan');
            $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
            $excel->setDescription('Laporan Perfomance Toko Detail Per Bulan');
            $excel->sheet('PerfomanceTokoDetailPerBulan', function($sheet) use ($data,$totalBulan,$username,$toko,$salesman,$gudang,$kota,$wilid,$tglmulai,$tglselesai,$subcabang,$total){
                $sheet->setColumnFormat(array(
                    'B' => '#,##0','C' => '#,##0','D' => '#,##0','E' => '#,##0','F' => '#,##0',
                    'G' => '#,##0','H' => '#,##0','I' => '#,##0','J' => '#,##0','K' => '#,##0',
                    'L' => '#,##0','M' => '#,##0','N' => '#,##0','O' => '#,##0','P' => '#,##0',
                    'Q' => '#,##0','R' => '#,##0','S' => '#,##0','T' => '#,##0','U' => '#,##0','V' => '#,##0','W' => '#,##0','X' => '#,##0','Y' => '#,##0',
                    'Z' => '#,##0','AA' => '#,##0','AB' => '#,##0',
                    'AC' => '#,##0','AF' => '#,##0','AG' => '#,##0',
                    'AH' => '#,##0','AI' => '0.00%','AJ' => '#,##0','AK' => '0.00%','AL' => '#,##0','AM' => '0.00%','AN' => '#,##0','AO' => '0.00%','AP' => '#,##0','AQ' => '0.00%',
                    'AR' => '#,##0','AS' => '0.00%','AT' => '#,##0','AU' => '0.00%','AV' => '#,##0','AW' => '0.00%','AX' => '#,##0','AY' => '0.00%','AZ' => '#,##0',
                ));
                $sheet->loadView('laporan.perfomancetoko.excel_bulan',array(
                    'datas'      => $data,
                    'totalBulan' => $totalBulan,
                    'username'   => $username,
                    'toko'       => $toko,
                    'salesman'   => $salesman,
                    'gudang'     => $gudang,
                    'kota'       => $kota,
                    'wilid'      => $wilid,
                    'tglmulai'   => $tglmulai,
                    'tglselesai' => $tglselesai,
                    'subcabang'  => $subcabang,
                    'total'      => $total
                ));
            });
        })->store('xls', storage_path('excel/exports'));

        $urlExcel = asset('storage/excel/exports/Laporan Perfomance Toko Detail Per Bulan.xls');

        return view('laporan.perfomancetoko.preview_bulan',compact('data','totalBulan','urlExcel'));
    }
}

// select mstr.toko.namatoko,
// mstr.toko.customwilayah,
// sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) as penjualannettob2,
// sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) as penjualannettob4,
// sum(case when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) as penjualannettoe,
// sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual else 0 end) as penjualannettolainnya,
// sum(batch.rekappenjualanperitemmonthly.subtotalnettojual) as penjualannettototal,
// ptg1.pembayaranuangimportr2,ptg1.pembayaranuangimportr4,ptg1.pembayaranuangpabrik,ptg1.pembayaranuanglainnya,ptg1.pembayaranuangtotal,
// ptg1.pembayarannonuangimportr2,ptg1.pembayarannonuangimportr4,ptg1.pembayarannonuangpabrik,ptg1.pembayarannonuanglainnya,ptg1.pembayarannonuangtotal,
// ptg1.pembayaransesuaitempokurangdari60,ptg1.pembayaransesuaitempoantara6175,ptg1.pembayaransesuaitempoantara7690,ptg1.pembayaransesuaitempoantara91105,ptg1.pembayaransesuaitempoantara106120,ptg1.pembayaransesuaitempoantara121150,ptg1.pembayaransesuaitempoantara151180,ptg1.pembayaransesuaitempolebihdari180,ptg1.pembayaransesuaitempototal,
// ptg1.pembayaranlewattempokurangdari30,ptg1.pembayaranlewattempolebihdari30,ptg1.pembayaranlewattempototal,
// max(batch.rekappenjualanperitemmonthly.subtotalnettojual) as pencapaiantertinggi,
// min(batch.rekappenjualanperitemmonthly.subtotalnettojual) as pencapaianterendah,
// avg(batch.rekappenjualanperitemmonthly.subtotalnettojual) as ratarata,
// sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab2 else 0 end) as labanettob2,
// sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab4 else 0 end) as labanettob4,
// sum(case when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppae else 0 end) as labanettoe,
// sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppalainnya else 0 end) as labanettolainnya,
// sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab2
//         when left(mstr.stock.kodebarang,3) in ('FB4') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppab4
//         when left(mstr.stock.kodebarang,2) in ('FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppae
//         when left(mstr.stock.kodebarang,2) not in ('FB','FE') then batch.rekappenjualanperitemmonthly.subtotalnettojual-acc1.hppalainnya
//         else 0 end) as labanettototal from batch.rekappenjualanperitemmonthly left join mstr.toko on batch.rekappenjualanperitemmonthly.tokoid = mstr.toko.id left join mstr.stock on batch.rekappenjualanperitemmonthly.stockid = mstr.stock.id
// left join
//     (select ptg.kartupiutang.tokoid,
//     sum(case when ((ptg.kartupiutang.tipetrans in ('K4','KB','T4','TB')) and (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC'))) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuangimportr2,
//     sum(case when ((ptg.kartupiutang.tipetrans in ('K2','T2')) and (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC'))) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuangimportr4,
//     sum(case when ((ptg.kartupiutang.tipetrans in ('KC','TC')) and (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC'))) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuangpabrik,
//     sum(case when ((ptg.kartupiutang.tipetrans not in ('K2','K4','KB','KC','T2','T4','TC','TB')) and (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC'))) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuanglainnya,
//     sum(case when (left(ptg.kartupiutangdetail.kodetrans,3) in ('KAS','TRN','BGC')) and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranuangtotal,
//     sum(case when (ptg.kartupiutang.tipetrans in ('K4','KB','T4','TB')) and (left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC')) then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuangimportr2,
//     sum(case when (ptg.kartupiutang.tipetrans in ('K2','T2')) and (left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC')) then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuangimportr4,
//     sum(case when (ptg.kartupiutang.tipetrans in ('KC','TC')) and (left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC')) then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuangpabrik,
//     sum(case when (ptg.kartupiutang.tipetrans not in ('K2','K4','KB','KC','T2','T4','TC','TB')) and (left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC')) then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuanglainnya,
//     sum(case when left(ptg.kartupiutangdetail.kodetrans,3) not in ('KAS','TRN','BGC') then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayarannonuangtotal,
//     sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 0 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 60 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempokurangdari60,
//     sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 60 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 75 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara6175,
//     sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 75 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 90 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara7690,
//     sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 90 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 105 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara91105,
//     sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 105 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 120 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara106120,
//     sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 120 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 150 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara121150,
//     sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 150 and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima <= 180 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempoantara151180,
//     sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutang.tgljt-ptg.kartupiutang.tglterima > 180  and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempolebihdari180,
//     sum(case when ptg.kartupiutang.tgljt>=ptg.kartupiutang.tglterima and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaransesuaitempototal,
//     sum(case when ptg.kartupiutang.tgljt<ptg.kartupiutang.tglterima and ptg.kartupiutang.tglterima-ptg.kartupiutang.tgljt > 0 and ptg.kartupiutang.tglterima-ptg.kartupiutang.tgljt <= 30 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranlewattempokurangdari30,
//     sum(case when ptg.kartupiutang.tgljt<ptg.kartupiutang.tglterima and ptg.kartupiutang.tglterima-ptg.kartupiutang.tgljt > 30 and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranlewattempolebihdari30,
//     sum(case when ptg.kartupiutang.tgljt<ptg.kartupiutang.tglterima and ptg.kartupiutangdetail.nomtrans >= 0 then ptg.kartupiutangdetail.nomtrans else 0 end) as pembayaranlewattempototal
//     from ptg.kartupiutang
//     left join ptg.kartupiutangdetail on ptg.kartupiutang.id = ptg.kartupiutangdetail.kartupiutangid
//     group by ptg.kartupiutang.tokoid) ptg1
//     on mstr.toko.id = ptg1.tokoid
// left join 
//     (select mstr.stock.id,
//     sum(case when left(mstr.stock.kodebarang,3) in ('FB2') then a.nominalhppa else 0 end) as hppab2,
//     sum(case when left(mstr.stock.kodebarang,3) in ('FB4') then a.nominalhppa else 0 end) as hppab4,
//     sum(case when left(mstr.stock.kodebarang,2) in ('FE') then a.nominalhppa else 0 end) as hppae,
//     sum(case when left(mstr.stock.kodebarang,2) not in ('FB','FE') then a.nominalhppa else 0 end) as hppalainnya
//     from acc.historyhppa a
//     inner join (select stockid, max(acc.historyhppa.tglaktif) as tglaktif
//     from acc.historyhppa
//     group by stockid) b
//     on a.stockid = b.stockid and a.tglaktif = b.tglaktif
//     inner join mstr.stock on a.stockid = mstr.stock.id
//     group by mstr.stock.id) acc1
//     on mstr.stock.id = acc1.id
//     where batch.rekappenjualanperitemmonthly.periode >= '201701' and batch.rekappenjualanperitemmonthly.periode <= '201701'
//     group by mstr.toko.namatoko, mstr.toko.customwilayah, ptg1.pembayaranuangimportr2, ptg1.pembayaranuangimportr4, ptg1.pembayaranuangpabrik, ptg1.pembayaranuanglainnya, ptg1.pembayaranuangtotal, ptg1.pembayarannonuangimportr2, ptg1.pembayarannonuangimportr4, ptg1.pembayarannonuangpabrik, ptg1.pembayarannonuanglainnya, ptg1.pembayarannonuangtotal, ptg1.pembayaransesuaitempokurangdari60, ptg1.pembayaransesuaitempoantara6175, ptg1.pembayaransesuaitempoantara7690, ptg1.pembayaransesuaitempoantara91105, ptg1.pembayaransesuaitempoantara106120, ptg1.pembayaransesuaitempoantara121150, ptg1.pembayaransesuaitempoantara151180, ptg1.pembayaransesuaitempolebihdari180, ptg1.pembayaransesuaitempototal, ptg1.pembayaranlewattempokurangdari30, ptg1.pembayaranlewattempolebihdari30, ptg1.pembayaranlewattempototal