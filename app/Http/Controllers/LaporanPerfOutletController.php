<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use EXCEL;
use PDF;
use XLSXWriter;

use App\Models\RekapPenjualanPerItemMonthly;
use App\Models\SubCabang;

// Traits
use App\Http\Traits\ReportTraits;

class LaporanPerfOutletController extends Controller
{
    use ReportTraits;
    private $type  = [
        1 => 'ALL',
        2 => 'Pareto',
        3 => 'Non Pareto',
        4 => 'Stock Lama Desember 2015',
        5 => 'Bureto April 2017',
    ];


    public function index(Request $req)
    {
        $bulan = ['01' => 'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL','05'=>'MEI','06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS','09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER'];
        $tahun = [];
        for($i = date('Y'); $i>= date('Y')-10; $i--){
            $tahun[] = $i;
        }

        return view('laporan.perfoutlet.index',['bulan'=>$bulan,'type'=>$this->type,'tahun'=>$tahun]);
    }

    // public function cetak(Request $req)
    // {
    //     // $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE OUTLET');
    //     $subcabang = $req->session()->get('subcabang');
    //     $type      = $req->type;
    //     $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
    //     $tahun     = $req->tahun;
    //     $bulan     = $req->bulan;

    //     // Pertanggal
    //     for($i=1;$i<=31;$i++) {
    //         $tanggal[] = "SUM(CASE WHEN EXTRACT(DAY FROM batch.rekappenjualanperitemmonthly.tglproforma) = $i THEN batch.rekappenjualanperitemmonthly.subtotalnettojual ELSE 0 END) AS total_$i ";
    //     }
    //     $pertanggal = implode(',', $tanggal);

    //     if($type == 2) {
    //         $where = "AND mstr.tokopareto.tokoid IS NOT NULL";
    //     }elseif($type == 3) {
    //         $where = "AND mstr.tokopareto.tokoid IS NULL";
    //     }else{
    //         $where = "";
    //     }

    //     $data = DB::select(DB::raw("
    //     SELECT
    //         mstr.toko.id,
    //         mstr.toko.namatoko,
    //         mstr.toko.customwilayah,
    //         mstr.toko.telp,
    //         statustoko.roda,
    //         hr.karyawan.kodesales,
    //         (CASE WHEN (mstr.tokopareto.tokoid IS NOT NULL) THEN 'P' ELSE 'N' END) AS pn,
    //         kunjungansales.tglfixedroute,
    //         SUM(CASE WHEN kunjungansales.counttglkunjung IS NOT NULL THEN kunjungansales.counttglkunjung ELSE 0 END) as counttglkunjung,
    //         (CASE WHEN (plafon.sisa_plafon IS NOT NULL) THEN plafon.sisa_plafon ELSE 0 END) as sisa_plafon,
    //         (CASE WHEN (SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) > 0) THEN SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) ELSE 0 END)
    //         +SUM(pj.notapenjualan.totalnominal)
    //         +SUM(CASE WHEN (rpaccpiutang <= sumdetail) THEN rpaccpiutang ELSE sumdetail END) as sisa_piutang,
    //         (CASE WHEN (mstr.tokopareto.targetomset IS NOT NULL) THEN mstr.tokopareto.targetomset ELSE 0 END) as targetomset,
    //         COUNT(batch.rekappenjualanperitemmonthly.stockid) AS sku,
    //         (SUM(orderpenjualan.counttglso)/SUM(kunjungansales.counttglkunjung))*100 AS efektif_oa,
    //         (CASE WHEN SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) IS NOT NULL THEN SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) ELSE 0 END) AS achievement_rp,
    //         (CASE WHEN (SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) IS NOT NULL AND mstr.tokopareto.targetomset IS NOT NULL)
    //             THEN (SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual)/mstr.tokopareto.targetomset)*100 ELSE 0 END
    //         ) AS achievement_persen,
    //         $pertanggal
    //     FROM mstr.toko
    //     LEFT JOIN mstr.tokopareto ON (mstr.toko.id = mstr.tokopareto.tokoid AND mstr.tokopareto.periode = '$tahun$bulan')
    //     LEFT JOIN (
    //         SELECT DISTINCT ON (tokoid) tokoid, roda, tglaktif FROM mstr.statustoko
    //         WHERE roda != '' ORDER BY tokoid ASC, tglaktif DESC
    //         ) AS statustoko ON mstr.toko.id = statustoko.tokoid
    //     LEFT JOIN (
    //         SELECT DISTINCT ON (tokoid) tokoid, karyawanidsalesman, tglaktif FROM mstr.tokohakakses
    //         WHERE recordownerid = $subcabang ORDER BY tokoid ASC, tglaktif DESC
    //         ) AS tokohakakses ON mstr.toko.id = tokohakakses.tokoid
    //     LEFT JOIN hr.karyawan ON hr.karyawan.id = tokohakakses.karyawanidsalesman
    //     LEFT JOIN (
    //         SELECT tokoid, ARRAY_TO_STRING(ARRAY_AGG(DISTINCT(EXTRACT(DAY FROM tglkunjung))), ',') as tglfixedroute, COUNT(tglkunjung) as counttglkunjung
    //         FROM pj.kunjungansales
    //         WHERE recordownerid = $subcabang
    //         AND EXTRACT(YEAR FROM tglkunjung) = '$tahun'
    //         AND EXTRACT(YEAR FROM tglkunjung) = '$bulan'
    //         GROUP BY tokoid
    //         ) AS kunjungansales ON mstr.toko.id = kunjungansales.tokoid
    //     LEFT JOIN ptg.kartupiutang ON mstr.toko.id = ptg.kartupiutang.tokoid
    //     LEFT JOIN ptg.kartupiutangdetail ON ptg.kartupiutang.id = ptg.kartupiutangdetail.kartupiutangid
    //     LEFT JOIN (
    //         SELECT tokoid, (plafon.plafon+plafon.plafontambahan) AS sisa_plafon FROM ptg.plafon
    //         WHERE EXTRACT(YEAR FROM tanggal) = '$tahun'
    //         AND EXTRACT(YEAR FROM tanggal) = '$bulan'
    //         ) AS plafon ON mstr.toko.id = plafon.tokoid
    //     LEFT JOIN pj.notapenjualan ON (pj.notapenjualan.recordownerid = $subcabang AND pj.notapenjualan.tglnota IS NULL AND pj.notapenjualan.tokoid = mstr.toko.id)
    //     LEFT JOIN (
    //         SELECT tokoid, COUNT(tglso) AS counttglso FROM pj.orderpenjualan
    //         WHERE EXTRACT(YEAR FROM tglso) = '$tahun'
    //         AND EXTRACT(YEAR FROM tglso) = '$bulan'
    //         GROUP BY tokoid
    //         ) AS orderpenjualan ON mstr.toko.id = orderpenjualan.tokoid
    //     LEFT JOIN (
    //         SELECT pj.orderpenjualan.id, pj.orderpenjualan.tokoid, SUM(rpaccpiutang) AS rpaccpiutang, SUM(pj.orderpenjualandetail.qtysoacc*pj.orderpenjualandetail.hrgsatuannetto) AS sumdetail FROM pj.orderpenjualan
    //         LEFT JOIN pj.orderpenjualandetail ON pj.orderpenjualan.id = pj.orderpenjualandetail.orderpenjualanid
    //         LEFT JOIN pj.notapenjualan ON pj.orderpenjualan.id = pj.notapenjualan.orderpenjualanid
    //         WHERE noaccpiutang != ''
    //         AND pj.notapenjualan.id IS NULL
    //         GROUP BY pj.orderpenjualan.id
    //         ) AS opj ON mstr.toko.id = opj.tokoid
    //     LEFT JOIN batch.rekappenjualanperitemmonthly ON (mstr.toko.id = batch.rekappenjualanperitemmonthly.tokoid AND batch.rekappenjualanperitemmonthly.periode = '$tahun$bulan')
    //     WHERE (mstr.toko.statusaktif = TRUE OR orderpenjualan.counttglso != 0) $where
    //     -- WHERE mstr.tokopareto.targetomset > 0
    //     GROUP BY
    //         mstr.toko.id,
    //         mstr.toko.namatoko,
    //         mstr.toko.customwilayah,
    //         mstr.toko.telp,
    //         statustoko.roda,
    //         hr.karyawan.kodesales,
    //         pn,
    //         kunjungansales.tglfixedroute,
    //         sisa_plafon,
    //         targetomset
    //     ORDER BY mstr.toko.id
    //     -- LIMIT 10
    //     "));
    //     // dd($data->toSql());
    //     // dd($data);

    //     $namasheet  = "Laporan Perfomance Outlet";
    //     $namafile   = $namasheet."_".csrf_token();
    //     $subcabang  = SubCabang::find(session('subcabang'))->kodesubcabang;
    //     $username   = strtoupper(auth()->user()->username);
    //     $tanggal    = Carbon::now()->format('d/m/Y');

    //     // Chunk Big Data
    //     $chunk = array_chunk($data, 500);
    //     Excel::create($namafile, function($excel) use ($chunk,$namasheet,$subcabang,$username,$tanggal,$type_desc){
    //         $excel->setTitle($namasheet);
    //         $excel->setCreator($username)->setCompany('SAS');
    //         $excel->setmanager($username);
    //         $excel->setsubject($namasheet);
    //         $excel->setlastModifiedBy($username);
    //         $excel->setDescription($namasheet);
    //         $excel->sheet('PerfomanceOutlet', function($sheet) use ($chunk,$subcabang,$username,$tanggal,$type_desc){
    //             $sheet->setColumnFormat(array(
    //                 'H' => '#,##0','I' => '#,##0','J' => '0.00%','K' => '#,##0','L' => '#,##0','M' => '0.00%', 'N' => '#,##0',
    //                 'O' => '#,##0','P' => '#,##0','Q' => '#,##0','R' => '#,##0','S' => '#,##0','T' => '#,##0','U' => '#,##0',
    //                 'V' => '#,##0','W' => '#,##0','X' => '#,##0','Y' => '#,##0','Z' => '#,##0',
    //                 'AA' => '#,##0','AB' => '#,##0','AC' => '#,##0','AD' => '#,##0','AE' => '#,##0','AF' => '#,##0','AG' => '#,##0',
    //                 'AH' => '#,##0','AI' => '0.00%','AJ' => '#,##0','AK' => '0.00%','AL' => '#,##0','AM' => '0.00%','AN' => '#,##0','AO' => '0.00%','AP' => '#,##0','AQ' => '#,##0',
    //                 'AR' => '#,##0','AS' => '0.00%','AT' => '#,##0','AU' => '0.00%','AV' => '#,##0','AW' => '0.00%','AX' => '#,##0','AY' => '0.00%','AZ' => '#,##0',
    //             ));
    //             foreach ($chunk as $loop=>$data) {
    //                 $sheet->loadView('laporan.perfoutlet.excel',array(
    //                     'loop'      => $loop,
    //                     'data'      => $data,
    //                     'subcabang' => $subcabang,
    //                     'username'  => $username,
    //                     'tanggal'   => $tanggal,
    //                     'type_desc' => $type_desc,
    //                 ));
    //             }
    //         });
    //     })->store('xls', storage_path('excel/exports'));

    //     $urlExcel = asset('storage/excel/exports/'.$namafile.'.xls');

    //     return view('laporan.perfoutlet.preview',compact('data','urlExcel'));
    // }

    public function cetak(Request $req)
    {
        // $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE OUTLET');
        $subcabang = $req->session()->get('subcabang');
        $type      = $req->type;
        $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        // Pertanggal
        for($i=1;$i<=31;$i++) {
            $tanggal[] = "SUM(CASE WHEN EXTRACT(DAY FROM batch.rekappenjualanperitemmonthly.tglproforma) = $i THEN batch.rekappenjualanperitemmonthly.subtotalnettojual ELSE 0 END) AS total_$i ";
        }
        $pertanggal = implode(',', $tanggal);

        if($type == 2) {
            $where = "AND mstr.tokopareto.tokoid IS NOT NULL";
        }elseif($type == 3) {
            $where = "AND mstr.tokopareto.tokoid IS NULL";
        }else{
            $where = "";
        }

        // Query
        $query = "
        SELECT
            mstr.toko.id,
            mstr.toko.namatoko,
            mstr.toko.customwilayah,
            mstr.toko.telp,
            statustoko.roda,
            hr.karyawan.kodesales,
            (CASE WHEN (mstr.tokopareto.tokoid IS NOT NULL) THEN 'P' ELSE 'N' END) AS pn,
            kunjungansales.tglfixedroute,
            SUM(CASE WHEN kunjungansales.counttglkunjung IS NOT NULL THEN kunjungansales.counttglkunjung ELSE 0 END) as counttglkunjung,
            (CASE WHEN (plafon.sisa_plafon IS NOT NULL) THEN plafon.sisa_plafon ELSE 0 END) as sisa_plafon,
            (CASE WHEN (SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) > 0) THEN SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) ELSE 0 END)
            +SUM(pj.notapenjualan.totalnominal)
            +SUM(CASE WHEN (rpaccpiutang <= sumdetail) THEN rpaccpiutang ELSE sumdetail END) as sisa_piutang,
            (CASE WHEN (mstr.tokopareto.targetomset IS NOT NULL) THEN mstr.tokopareto.targetomset ELSE 0 END) as targetomset,
            COUNT(batch.rekappenjualanperitemmonthly.stockid) AS sku,
            (SUM(orderpenjualan.counttglso)/SUM(kunjungansales.counttglkunjung))*100 AS efektif_oa,
            (CASE WHEN SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) IS NOT NULL THEN SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) ELSE 0 END) AS achievement_rp,
            (CASE WHEN (SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) IS NOT NULL AND mstr.tokopareto.targetomset IS NOT NULL)
                THEN (SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual)/mstr.tokopareto.targetomset)*100 ELSE 0 END
            ) AS achievement_persen,
            $pertanggal
        FROM mstr.toko
        LEFT JOIN mstr.tokopareto ON (mstr.toko.id = mstr.tokopareto.tokoid AND mstr.tokopareto.periode = '$tahun$bulan')
        LEFT JOIN (
            SELECT DISTINCT ON (tokoid) tokoid, roda, tglaktif FROM mstr.statustoko
            WHERE roda != '' ORDER BY tokoid ASC, tglaktif DESC
            ) AS statustoko ON mstr.toko.id = statustoko.tokoid
        LEFT JOIN (
            SELECT DISTINCT ON (tokoid) tokoid, karyawanidsalesman, tglaktif FROM mstr.tokohakakses
            WHERE recordownerid = $subcabang ORDER BY tokoid ASC, tglaktif DESC
            ) AS tokohakakses ON mstr.toko.id = tokohakakses.tokoid
        LEFT JOIN hr.karyawan ON hr.karyawan.id = tokohakakses.karyawanidsalesman
        LEFT JOIN (
            SELECT tokoid, ARRAY_TO_STRING(ARRAY_AGG(DISTINCT(EXTRACT(DAY FROM tglkunjung))), ',') as tglfixedroute, COUNT(tglkunjung) as counttglkunjung
            FROM pj.kunjungansales
            WHERE recordownerid = $subcabang
            AND EXTRACT(YEAR FROM tglkunjung) = '$tahun'
            AND EXTRACT(YEAR FROM tglkunjung) = '$bulan'
            GROUP BY tokoid
            ) AS kunjungansales ON mstr.toko.id = kunjungansales.tokoid
        LEFT JOIN ptg.kartupiutang ON mstr.toko.id = ptg.kartupiutang.tokoid
        LEFT JOIN ptg.kartupiutangdetail ON ptg.kartupiutang.id = ptg.kartupiutangdetail.kartupiutangid
        LEFT JOIN (
            SELECT tokoid, (plafon.plafon+plafon.plafontambahan) AS sisa_plafon FROM ptg.plafon
            WHERE EXTRACT(YEAR FROM tanggal) = '$tahun'
            AND EXTRACT(YEAR FROM tanggal) = '$bulan'
            ) AS plafon ON mstr.toko.id = plafon.tokoid
        LEFT JOIN pj.notapenjualan ON (pj.notapenjualan.recordownerid = $subcabang AND pj.notapenjualan.tglnota IS NULL AND pj.notapenjualan.tokoid = mstr.toko.id)
        LEFT JOIN (
            SELECT tokoid, COUNT(tglso) AS counttglso FROM pj.orderpenjualan
            WHERE EXTRACT(YEAR FROM tglso) = '$tahun'
            AND EXTRACT(YEAR FROM tglso) = '$bulan'
            GROUP BY tokoid
            ) AS orderpenjualan ON mstr.toko.id = orderpenjualan.tokoid
        LEFT JOIN (
            SELECT pj.orderpenjualan.id, pj.orderpenjualan.tokoid, SUM(rpaccpiutang) AS rpaccpiutang, SUM(pj.orderpenjualandetail.qtysoacc*pj.orderpenjualandetail.hrgsatuannetto) AS sumdetail FROM pj.orderpenjualan
            LEFT JOIN pj.orderpenjualandetail ON pj.orderpenjualan.id = pj.orderpenjualandetail.orderpenjualanid
            LEFT JOIN pj.notapenjualan ON pj.orderpenjualan.id = pj.notapenjualan.orderpenjualanid
            WHERE noaccpiutang != ''
            AND pj.notapenjualan.id IS NULL
            GROUP BY pj.orderpenjualan.id
            ) AS opj ON mstr.toko.id = opj.tokoid
        LEFT JOIN batch.rekappenjualanperitemmonthly ON (mstr.toko.id = batch.rekappenjualanperitemmonthly.tokoid AND batch.rekappenjualanperitemmonthly.periode = '$tahun$bulan')
        WHERE (mstr.toko.statusaktif = TRUE OR orderpenjualan.counttglso != 0) $where
        -- WHERE mstr.tokopareto.targetomset > 0
        GROUP BY
            mstr.toko.id,
            mstr.toko.namatoko,
            mstr.toko.customwilayah,
            mstr.toko.telp,
            statustoko.roda,
            hr.karyawan.kodesales,
            pn,
            kunjungansales.tglfixedroute,
            sisa_plafon,
            targetomset
        -- ORDER BY mstr.toko.customwilayah, mstr.toko.id
        ORDER BY mstr.toko.id, mstr.toko.customwilayah
        -- LIMIT 1000
        ";

        // need moar RAM and execution time
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time',300);
        
        $data = DB::select(DB::raw($query));
        $count_data = count($data);

        // Convert to variable
        $table = view('laporan.perfoutlet.table',['data'=>$data])->render();

        // Cleanup
        $data = null;
        unset($data);

        $namasheet  = "Laporan Perfomance Outlet";
        $namafile   = $namasheet."_".uniqid();
        $subcabang  = SubCabang::find(session('subcabang'))->kodesubcabang;
        $username   = strtoupper(auth()->user()->username);
        $tanggal    = Carbon::now()->format('d/m/Y');

        Excel::create($namafile, function($excel) use ($table,$namasheet,$subcabang,$username,$tanggal,$type_desc,$count_data){
            $excel->setTitle($namasheet);
            $excel->setCreator($username)->setCompany('SAS');
            $excel->setmanager($username);
            $excel->setsubject($namasheet);
            $excel->setlastModifiedBy($username);
            $excel->setDescription($namasheet);
            $excel->sheet('PerfomanceOutlet', function($sheet) use ($table,$subcabang,$username,$tanggal,$type_desc,$count_data){
                // Load
                $sheet->loadView('laporan.perfoutlet.excel',array(
                    'data'      => $table,
                    'subcabang' => $subcabang,
                    'username'  => $username,
                    'tanggal'   => $tanggal,
                    'type_desc' => $type_desc,
                ));

                // Index
                $idx = $count_data+6;
                $idz = $idx+1;

                // Set Format
                $sheet->setWidth(['A'=>25,'B'=>50,'C'=>25,'D'=>25,'E'=>25,'F'=>25,'G'=>40,'H'=>25,'I'=>25,'J'=>25,'K'=>25,'L'=>25,'M'=>25,'N'=>25,'O'=>25,'P'=>25,'Q'=>25,'R'=>25,'S'=>25,'T'=>25,'U'=>25,'V'=>25,'W'=>25,'X'=>25,'Y'=>25,'Z'=>25,'AA'=>25,'AB'=>25,'AC'=>25,'AD'=>25,'AE'=>25,'AF'=>25,'AG'=>25,'AH'=>25,'AI'=>25,'AJ'=>25,'AK'=>25,'AL'=>25,'AM'=>25,'AN'=>25,'AO'=>25,'AP'=>25,'AQ'=>25,'AR'=>25,]);
                $sheet->setColumnFormat(array(
                    'H7:H'.$idz => '#,##0','I7:I'.$idz => '#,##0','J7:J'.$idz => '0.00%','K7:K'.$idz => '#,##0','L7:L'.$idz => '#,##0','M7:M'.$idz => '0.00%', 'N7:N'.$idz => '#,##0',
                    'O7:O'.$idz => '#,##0','P7:P'.$idz => '#,##0','Q7:Q'.$idz => '#,##0','R7:R'.$idz => '#,##0','S7:S'.$idz => '#,##0','T7:T'.$idz => '#,##0','U7:U'.$idz => '#,##0',
                    'V7:V'.$idz => '#,##0','W7:W'.$idz => '#,##0','X7:X'.$idz => '#,##0','Y7:Y'.$idz => '#,##0','Z7:Z'.$idz => '#,##0',

                    'AA7:AA'.$idz => '#,##0','AB7:AB'.$idz => '#,##0','AC7:AC'.$idz => '#,##0','AD7:AD'.$idz => '#,##0','AE7:AE'.$idz => '#,##0','AF7:AF'.$idz => '#,##0','AG7:AG'.$idz => '#,##0',
                    'AH7:AH'.$idz => '#,##0','AI7:AI'.$idz => '#,##0','AJ7:AJ'.$idz => '#,##0','AK7:AK'.$idz => '#,##0','AL7:AL'.$idz => '#,##0','AM7:AM'.$idz => '#,##0','AN7:AN'.$idz => '#,##0',
                    'AO7:AO'.$idz => '#,##0','AP7:AP'.$idz => '#,##0','AQ7:AQ'.$idz => '#,##0','AR7:AR'.$idz => '#,##0',
                ));

                // Style
                $sheet->cells('A'.$idz.':AR'.$idz, function($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setStyle(['borders'=>['allborders'=>['color'=>['rgb'=>'000000']]]]);
                $sheet->setBorder('A7:AR'.$idz);

                // Append Total
                $sheet->row($idz, [
                    'TOTAL', '', '', '', '', '', '',
                    '=SUM(H7:H'.$idx.')','=SUM(I7:I'.$idx.')','=SUM(J7:J'.$idx.')','=SUM(K7:K'.$idx.')','=SUM(L7:L'.$idx.')','=SUM(M7:M'.$idx.')','=SUM(N7:N'.$idx.')',
                    '=SUM(O7:O'.$idx.')','=SUM(P7:P'.$idx.')','=SUM(Q7:Q'.$idx.')','=SUM(R7:R'.$idx.')','=SUM(S7:S'.$idx.')','=SUM(T7:T'.$idx.')','=SUM(U7:U'.$idx.')',
                    '=SUM(V7:V'.$idx.')','=SUM(W7:W'.$idx.')','=SUM(X7:X'.$idx.')','=SUM(Y7:Y'.$idx.')','=SUM(Z7:Z'.$idx.')',
                    '=SUM(AA7:AA'.$idx.')','=SUM(AB7:AB'.$idx.')','=SUM(AC7:AC'.$idx.')','=SUM(AD7:AD'.$idx.')','=SUM(AE7:AE'.$idx.')','=SUM(AF7:AF'.$idx.')','=SUM(AG7:AG'.$idx.')',
                    '=SUM(AH7:AH'.$idx.')','=SUM(AI7:AI'.$idx.')','=SUM(AJ7:AJ'.$idx.')','=SUM(AK7:AK'.$idx.')','=SUM(AL7:AL'.$idx.')','=SUM(AM7:AM'.$idx.')','=SUM(AN7:AN'.$idx.')',
                    '=SUM(AO7:AO'.$idx.')','=SUM(AP7:AP'.$idx.')','=SUM(AQ7:AQ'.$idx.')','=SUM(AR7:AR'.$idx.')'
                ]);
            });
        })->store('xls', storage_path('excel/exports'));

        $urlExcel = asset('storage/excel/exports/'.$namafile.'.xls');

        return view('laporan.perfoutlet.previews',['data'=>$table,'urlExcel'=>$urlExcel]);
    }

    // public function cetak(Request $req)
    // {
    //     // $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE OUTLET');
    //     $subcabang = $req->session()->get('subcabang');
    //     $type      = $req->type;
    //     $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
    //     $tahun     = $req->tahun;
    //     $bulan     = $req->bulan;

    //     // Pertanggal
    //     for($i=1;$i<=31;$i++) {
    //         $tanggal[] = "SUM(CASE WHEN EXTRACT(DAY FROM batch.rekappenjualanperitemmonthly.tglproforma) = $i THEN batch.rekappenjualanperitemmonthly.subtotalnettojual ELSE 0 END) AS total_$i ";
    //     }
    //     $pertanggal = implode(',', $tanggal);

    //     if($type == 2) {
    //         $where = "AND mstr.tokopareto.tokoid IS NOT NULL";
    //     }elseif($type == 3) {
    //         $where = "AND mstr.tokopareto.tokoid IS NULL";
    //     }else{
    //         $where = "";
    //     }

    //     $query = "
    //     SELECT
    //         mstr.toko.id,
    //         mstr.toko.namatoko,
    //         mstr.toko.customwilayah,
    //         mstr.toko.telp,
    //         statustoko.roda,
    //         hr.karyawan.kodesales,
    //         (CASE WHEN (mstr.tokopareto.tokoid IS NOT NULL) THEN 'P' ELSE 'N' END) AS pn,
    //         kunjungansales.tglfixedroute,
    //         SUM(CASE WHEN kunjungansales.counttglkunjung IS NOT NULL THEN kunjungansales.counttglkunjung ELSE 0 END) as counttglkunjung,
    //         (CASE WHEN (plafon.sisa_plafon IS NOT NULL) THEN plafon.sisa_plafon ELSE 0 END) as sisa_plafon,
    //         (CASE WHEN (SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) > 0) THEN SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) ELSE 0 END)
    //         +SUM(pj.notapenjualan.totalnominal)
    //         +SUM(CASE WHEN (rpaccpiutang <= sumdetail) THEN rpaccpiutang ELSE sumdetail END) as sisa_piutang,
    //         (CASE WHEN (mstr.tokopareto.targetomset IS NOT NULL) THEN mstr.tokopareto.targetomset ELSE 0 END) as targetomset,
    //         COUNT(batch.rekappenjualanperitemmonthly.stockid) AS sku,
    //         (SUM(orderpenjualan.counttglso)/SUM(kunjungansales.counttglkunjung))*100 AS efektif_oa,
    //         (CASE WHEN SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) IS NOT NULL THEN SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) ELSE 0 END) AS achievement_rp,
    //         (CASE WHEN (SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) IS NOT NULL AND mstr.tokopareto.targetomset IS NOT NULL)
    //             THEN (SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual)/mstr.tokopareto.targetomset)*100 ELSE 0 END
    //         ) AS achievement_persen,
    //         $pertanggal
    //     FROM mstr.toko
    //     LEFT JOIN mstr.tokopareto ON (mstr.toko.id = mstr.tokopareto.tokoid AND mstr.tokopareto.periode = '$tahun$bulan')
    //     LEFT JOIN (
    //         SELECT DISTINCT ON (tokoid) tokoid, roda, tglaktif FROM mstr.statustoko
    //         WHERE roda != '' ORDER BY tokoid ASC, tglaktif DESC
    //         ) AS statustoko ON mstr.toko.id = statustoko.tokoid
    //     LEFT JOIN (
    //         SELECT DISTINCT ON (tokoid) tokoid, karyawanidsalesman, tglaktif FROM mstr.tokohakakses
    //         WHERE recordownerid = $subcabang ORDER BY tokoid ASC, tglaktif DESC
    //         ) AS tokohakakses ON mstr.toko.id = tokohakakses.tokoid
    //     LEFT JOIN hr.karyawan ON hr.karyawan.id = tokohakakses.karyawanidsalesman
    //     LEFT JOIN (
    //         SELECT tokoid, ARRAY_TO_STRING(ARRAY_AGG(DISTINCT(EXTRACT(DAY FROM tglkunjung))), ',') as tglfixedroute, COUNT(tglkunjung) as counttglkunjung
    //         FROM pj.kunjungansales
    //         WHERE recordownerid = $subcabang
    //         AND EXTRACT(YEAR FROM tglkunjung) = '$tahun'
    //         AND EXTRACT(YEAR FROM tglkunjung) = '$bulan'
    //         GROUP BY tokoid
    //         ) AS kunjungansales ON mstr.toko.id = kunjungansales.tokoid
    //     LEFT JOIN ptg.kartupiutang ON mstr.toko.id = ptg.kartupiutang.tokoid
    //     LEFT JOIN ptg.kartupiutangdetail ON ptg.kartupiutang.id = ptg.kartupiutangdetail.kartupiutangid
    //     LEFT JOIN (
    //         SELECT tokoid, (plafon.plafon+plafon.plafontambahan) AS sisa_plafon FROM ptg.plafon
    //         WHERE EXTRACT(YEAR FROM tanggal) = '$tahun'
    //         AND EXTRACT(YEAR FROM tanggal) = '$bulan'
    //         ) AS plafon ON mstr.toko.id = plafon.tokoid
    //     LEFT JOIN pj.notapenjualan ON (pj.notapenjualan.recordownerid = $subcabang AND pj.notapenjualan.tglnota IS NULL AND pj.notapenjualan.tokoid = mstr.toko.id)
    //     LEFT JOIN (
    //         SELECT tokoid, COUNT(tglso) AS counttglso FROM pj.orderpenjualan
    //         WHERE EXTRACT(YEAR FROM tglso) = '$tahun'
    //         AND EXTRACT(YEAR FROM tglso) = '$bulan'
    //         GROUP BY tokoid
    //         ) AS orderpenjualan ON mstr.toko.id = orderpenjualan.tokoid
    //     LEFT JOIN (
    //         SELECT pj.orderpenjualan.id, pj.orderpenjualan.tokoid, SUM(rpaccpiutang) AS rpaccpiutang, SUM(pj.orderpenjualandetail.qtysoacc*pj.orderpenjualandetail.hrgsatuannetto) AS sumdetail FROM pj.orderpenjualan
    //         LEFT JOIN pj.orderpenjualandetail ON pj.orderpenjualan.id = pj.orderpenjualandetail.orderpenjualanid
    //         LEFT JOIN pj.notapenjualan ON pj.orderpenjualan.id = pj.notapenjualan.orderpenjualanid
    //         WHERE noaccpiutang != ''
    //         AND pj.notapenjualan.id IS NULL
    //         GROUP BY pj.orderpenjualan.id
    //         ) AS opj ON mstr.toko.id = opj.tokoid
    //     LEFT JOIN batch.rekappenjualanperitemmonthly ON (mstr.toko.id = batch.rekappenjualanperitemmonthly.tokoid AND batch.rekappenjualanperitemmonthly.periode = '$tahun$bulan')
    //     WHERE (mstr.toko.statusaktif = TRUE OR orderpenjualan.counttglso != 0) $where
    //     -- WHERE mstr.tokopareto.targetomset > 0
    //     GROUP BY
    //         mstr.toko.id,
    //         mstr.toko.namatoko,
    //         mstr.toko.customwilayah,
    //         mstr.toko.telp,
    //         statustoko.roda,
    //         hr.karyawan.kodesales,
    //         pn,
    //         kunjungansales.tglfixedroute,
    //         sisa_plafon,
    //         targetomset
    //     -- ORDER BY mstr.toko.customwilayah, mstr.toko.id
    //     ORDER BY mstr.toko.id, mstr.toko.customwilayah
    //     -- LIMIT 100
    //     ";

    //     // need moar RAM and execution time
    //     ini_set('memory_limit', '1024M');
    //     ini_set('max_execution_time',300);
        
    //     $result = DB::select(DB::raw($query));
    //     $data   = array_chunk($result,100);
    //     $preview_data = $data[0];

    //     // Cleanup
    //     unset($result);

    //     $namasheet  = "Laporan Perfomance Outlet";
    //     $namafile   = $namasheet."_".csrf_token();
    //     $subcabang  = SubCabang::find(session('subcabang'))->kodesubcabang;
    //     $username   = strtoupper(auth()->user()->username);
    //     $tanggal    = Carbon::now()->format('d/m/Y');

    //     Excel::create($namafile, function($excel) use ($data,$namasheet,$subcabang,$username,$tanggal,$type_desc){
    //         $excel->setTitle($namasheet);
    //         $excel->setCreator($username)->setCompany('SAS');
    //         $excel->setmanager($username);
    //         $excel->setsubject($namasheet);
    //         $excel->setlastModifiedBy($username);
    //         $excel->setDescription($namasheet);
    //         $excel->sheet('PerfomanceOutlet', function($sheet) use ($data,$subcabang,$username,$tanggal,$type_desc){

    //             // Header
    //             $sheet->setCellValue('A1', "MONITORING OUTLET CHANNEL ($type_desc)");
    //             $sheet->setCellValue('A2', "Tanggal Update : $tanggal");
    //             $sheet->setCellValue('A3', "Cabang : $subcabang");
    //             $sheet->cells('A1:A3', function($cells) {
    //                 $cells->setFontWeight('bold');
    //             });

    //             // Table Header
    //             $range = range(1,31);
    //             $sheet->row(5, array_merge(["ID WIL","NAMA TOKO","P/N","RODA","NO TELP","KODE SALES","TANGGAL FIXEDROUTE","SISA PLAFON","SKU","EFEKTIF OA%","TARGET TOKO","ACHIEVEMENT",""],$range));
    //             $sheet->mergeCells('L5:M5');
    //             $sheet->setCellValue('L6', "Rp");
    //             $sheet->setCellValue('M6', "%");
    //             $sheet->setMergeColumn(array(
    //                 'columns' => ['A','B','C','D','E','F','G','H','I','J','K','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR',],
    //                 'rows'    => [[5,6]]
    //             ));
    //             $sheet->setWidth(['A'=>25,'B'=>50,'C'=>25,'D'=>25,'E'=>25,'F'=>25,'G'=>40,'H'=>25,'I'=>25,'J'=>25,'K'=>25,'L'=>25,'M'=>25,'N'=>25,'O'=>25,'P'=>25,'Q'=>25,'R'=>25,'S'=>25,'T'=>25,'U'=>25,'V'=>25,'W'=>25,'X'=>25,'Y'=>25,'Z'=>25,'AA'=>25,'AB'=>25,'AC'=>25,'AD'=>25,'AE'=>25,'AF'=>25,'AG'=>25,'AH'=>25,'AI'=>25,'AJ'=>25,'AK'=>25,'AL'=>25,'AM'=>25,'AN'=>25,'AO'=>25,'AP'=>25,'AQ'=>25,'AR'=>25,]);

    //             // Table Data
    //             $fst = 7;
    //             $idx = $fst;
    //             foreach ($data as $keys=>$rows)
    //             {
    //                 foreach ($rows as $key=>$row)
    //                 {
    //                     $sheet->row($idx, [
    //                         $row->customwilayah, $row->namatoko, $row->pn, $row->roda, $row->telp, $row->kodesales, $row->tglfixedroute, (float)($row->sisa_plafon-$row->sisa_piutang),
    //                         (float)$row->sku, (float)$row->efektif_oa, (float)$row->targetomset, (float)$row->achievement_rp, (float)$row->achievement_persen,
    //                         (float)$row->total_1, (float)$row->total_2, (float)$row->total_3, (float)$row->total_4, (float)$row->total_5, (float)$row->total_6, (float)$row->total_7, (float)$row->total_8, (float)$row->total_9, (float)$row->total_10, 
    //                         (float)$row->total_11, (float)$row->total_12, (float)$row->total_13, (float)$row->total_14, (float)$row->total_15, (float)$row->total_16, (float)$row->total_17, (float)$row->total_18, (float)$row->total_19, (float)$row->total_20,
    //                         (float)$row->total_21, (float)$row->total_22, (float)$row->total_23, (float)$row->total_24, (float)$row->total_25, (float)$row->total_26, (float)$row->total_27, (float)$row->total_28, (float)$row->total_29, (float)$row->total_30, (float)$row->total_31,
    //                     ]);
    //                     $idx++;

    //                     // Sum
    //                     // $tt_sisa_plafon =+ ($row->sisa_plafon-$row->sisa_piutang); $tt_sku =+ $row->sku; $tt_efektif_oa =+ $row->efektif_oa; $tt_targetomset =+ $row->targetomset; $tt_achievement_rp =+ $row->achievement_rp;
    //                     // // $tt_achievement_persen =+ $row->achievement_persen;
    //                     // $tt_total_1 =+ $row->total_1; $tt_total_2 =+ $row->total_2; $tt_total_3 =+ $row->total_3; $tt_total_4 =+ $row->total_4; $tt_total_5 =+ $row->total_5; $tt_total_6 =+ $row->total_6; $tt_total_7 =+ $row->total_7; $tt_total_8 =+ $row->total_8; $tt_total_9 =+ $row->total_9; $tt_total_10 =+ $row->total_10; 
    //                     // $tt_total_11 =+ $row->total_11; $tt_total_12 =+ $row->total_12; $tt_total_13 =+ $row->total_13; $tt_total_14 =+ $row->total_14; $tt_total_15 =+ $row->total_15; $tt_total_16 =+ $row->total_16; $tt_total_17 =+ $row->total_17; $tt_total_18 =+ $row->total_18; $tt_total_19 =+ $row->total_19; $tt_total_20 =+ $row->total_20;
    //                     // $tt_total_21 =+ $row->total_21; $tt_total_22 =+ $row->total_22; $tt_total_23 =+ $row->total_23; $tt_total_24 =+ $row->total_24; $tt_total_25 =+ $row->total_25; $tt_total_26 =+ $row->total_26; $tt_total_27 =+ $row->total_27; $tt_total_28 =+ $row->total_28; $tt_total_29 =+ $row->total_29; $tt_total_30 =+ $row->total_30; $tt_total_31 =+ $row->total_31;

    //                     // Cleanup
    //                     $rows[$key] = null;
    //                     unset($row);
    //                     unset($rows[$key]);
    //                 }

    //                 // Cleanup
    //                 $data[$key] = null;
    //                 unset($rows);
    //                 unset($data[$keys]);
    //             }
    //             // Cleanup
    //             $data = null;

    //             // Append Total
    //             $idxx = $idx-1;
    //             $sheet->row($idx, [
    //                 'TOTAL', '', '', '', '', '', '',
    //                 '=SUM(H'.$fst.':H'.$idxx.')','=SUM(I'.$fst.':I'.$idxx.')','=SUM(J'.$fst.':J'.$idxx.')','=SUM(K'.$fst.':K'.$idxx.')','=SUM(L'.$fst.':L'.$idxx.')','=SUM(M'.$fst.':M'.$idxx.')','=SUM(N'.$fst.':N'.$idxx.')',
    //                 '=SUM(O'.$fst.':O'.$idxx.')','=SUM(P'.$fst.':P'.$idxx.')','=SUM(Q'.$fst.':Q'.$idxx.')','=SUM(R'.$fst.':R'.$idxx.')','=SUM(S'.$fst.':S'.$idxx.')','=SUM(T'.$fst.':T'.$idxx.')','=SUM(U'.$fst.':U'.$idxx.')',
    //                 '=SUM(V'.$fst.':V'.$idxx.')','=SUM(W'.$fst.':W'.$idxx.')','=SUM(X'.$fst.':X'.$idxx.')','=SUM(Y'.$fst.':Y'.$idxx.')','=SUM(Z'.$fst.':Z'.$idxx.')',
    //                 '=SUM(AA'.$fst.':AA'.$idxx.')','=SUM(AB'.$fst.':AB'.$idxx.')','=SUM(AC'.$fst.':AC'.$idxx.')','=SUM(AD'.$fst.':AD'.$idxx.')','=SUM(AE'.$fst.':AE'.$idxx.')','=SUM(AF'.$fst.':AF'.$idxx.')','=SUM(AG'.$fst.':AG'.$idxx.')',
    //                 '=SUM(AH'.$fst.':AH'.$idxx.')','=SUM(AI'.$fst.':AI'.$idxx.')','=SUM(AJ'.$fst.':AJ'.$idxx.')','=SUM(AK'.$fst.':AK'.$idxx.')','=SUM(AL'.$fst.':AL'.$idxx.')','=SUM(AM'.$fst.':AM'.$idxx.')','=SUM(AN'.$fst.':AN'.$idxx.')',
    //                 '=SUM(AO'.$fst.':AO'.$idxx.')','=SUM(AP'.$fst.':AP'.$idxx.')','=SUM(AQ'.$fst.':AQ'.$idxx.')','=SUM(AR'.$fst.':AR'.$idxx.')'
    //             ]);

    //             $sheet->row($idx+2, [$username.', '.Carbon::now()->format('d/m/Y h:i:s')]);

    //             // Set Format
    //             $sheet->setColumnFormat(array(
    //                 'H'.$fst.':H'.$idx => '#,##0','I'.$fst.':I'.$idx => '#,##0','J'.$fst.':J'.$idx => '0.00%','K'.$fst.':K'.$idx => '#,##0','L'.$fst.':L'.$idx => '#,##0','M'.$fst.':M'.$idx => '0.00%', 'N'.$fst.':N'.$idx => '#,##0',
    //                 'O'.$fst.':O'.$idx => '#,##0','P'.$fst.':P'.$idx => '#,##0','Q'.$fst.':Q'.$idx => '#,##0','R'.$fst.':R'.$idx => '#,##0','S'.$fst.':S'.$idx => '#,##0','T'.$fst.':T'.$idx => '#,##0','U'.$fst.':U'.$idx => '#,##0',
    //                 'V'.$fst.':V'.$idx => '#,##0','W'.$fst.':W'.$idx => '#,##0','X'.$fst.':X'.$idx => '#,##0','Y'.$fst.':Y'.$idx => '#,##0','Z'.$fst.':Z'.$idx => '#,##0',

    //                 'AA'.$fst.':AA'.$idx => '#,##0','AB'.$fst.':AB'.$idx => '#,##0','AC'.$fst.':AC'.$idx => '#,##0','AD'.$fst.':AD'.$idx => '#,##0','AE'.$fst.':AE'.$idx => '#,##0','AF'.$fst.':AF'.$idx => '#,##0','AG'.$fst.':AG'.$idx => '#,##0',
    //                 'AH'.$fst.':AH'.$idx => '#,##0','AI'.$fst.':AI'.$idx => '#,##0','AJ'.$fst.':AJ'.$idx => '#,##0','AK'.$fst.':AK'.$idx => '#,##0','AL'.$fst.':AL'.$idx => '#,##0','AM'.$fst.':AM'.$idx => '#,##0','AN'.$fst.':AN'.$idx => '#,##0',
    //                 'AO'.$fst.':AO'.$idx => '#,##0','AP'.$fst.':AP'.$idx => '#,##0','AQ'.$fst.':AQ'.$idx => '#,##0','AR'.$fst.':AR'.$idx => '#,##0','AS'.$fst.':AS'.$idx => '#,##0','AT'.$fst.':AT'.$idx => '#,##0','AU'.$fst.':AU'.$idx => '#,##0',
    //                 'AV'.$fst.':AV'.$idx => '#,##0','AW'.$fst.':AW'.$idx => '#,##0','AX'.$fst.':AX'.$idx => '#,##0','AY'.$fst.':AY'.$idx => '#,##0','AZ'.$fst.':AZ'.$idx => '#,##0',
    //             ));

    //             // Style
    //             $sheet->cells('A5:AR6', function($cells) {
    //                 $cells->setAlignment('center');
    //                 $cells->setFontWeight('bold');
    //                 $cells->setBackground('#dbe5f1');
    //             });
    //             $sheet->cells('A'.$idx.':AR6'.$idx, function($cells) {
    //                 $cells->setFontWeight('bold');
    //             });
    //             $sheet->setStyle(['borders'=>['allborders'=>['color'=>['rgb'=>'000000']]]]);
    //             $sheet->setBorder('A5:AR6');
    //             $sheet->setBorder('A5:AR6');
    //             $sheet->setBorder('A7:AR'.$idx);
    //         });
    //     })->store('xls', storage_path('excel/exports'));

    //     $urlExcel = asset('storage/excel/exports/'.$namafile.'.xls');

    //     return view('laporan.perfoutlet.preview',['data'=>$preview_data,'urlExcel'=>$urlExcel]);
    // }


}


// AJUR CCUKK!!!
        // $query = RekapPenjualanPerItemMonthly::selectRaw("
        //     batch.rekappenjualanperitemmonthly.tokoid,
        //     mstr.toko.namatoko,
        //     mstr.toko.customwilayah,
        //     mstr.toko.telp,
        //     hr.karyawan.kodesales,
        //     kunjungansales.tglfixedroute,
        //     COUNT(batch.rekappenjualanperitemmonthly.stockid) as sku,
        //     mstr.tokopareto.targetomset,
        //     statustoko.roda,
        //     sum(plafon.sisa_plafon) as sisa_plafon,
        //     sum(kunjungansales.counttglkunjung) as counttglkunjung
        // ");
        // $query->leftJoin('mstr.toko','batch.rekappenjualanperitemmonthly.tokoid','=','mstr.toko.id');
        // $query->leftJoin('mstr.tokopareto',function($join) use ($req) {
        //     $join->on('batch.rekappenjualanperitemmonthly.tokoid','=','mstr.tokopareto.tokoid');
        //     $join->on('mstr.tokopareto.periode','=',DB::raw("'".$req->tahun.$req->bulan."'"));
        // });
        // $query->leftJoin(DB::raw('(SELECT DISTINCT ON (tokoid) tokoid, roda, tglaktif FROM mstr.statustoko WHERE roda != \'\' ORDER BY tokoid ASC, tglaktif DESC) AS statustoko'), function($join){
        //     $join->on('batch.rekappenjualanperitemmonthly.tokoid','=','statustoko.tokoid');
        // });
        // $query->leftJoin(DB::raw('(SELECT DISTINCT ON (tokoid) tokoid, karyawanidsalesman, tglaktif FROM mstr.tokohakakses WHERE recordownerid = '.$req->session()->get('subcabang').' ORDER BY tokoid ASC, tglaktif DESC) AS tokohakakses'), function($join){
        //     $join->on('batch.rekappenjualanperitemmonthly.tokoid','=','tokohakakses.tokoid');
        // });
        // $query->leftJoin('hr.karyawan','hr.karyawan.id','=','tokohakakses.karyawanidsalesman');
        // $query->leftJoin(DB::raw('(SELECT tokoid, ARRAY_TO_STRING(ARRAY_AGG(DISTINCT(EXTRACT(DAY FROM tglkunjung))), \',\') as tglfixedroute, COUNT(tglkunjung) as counttglkunjung FROM pj.kunjungansales WHERE recordownerid = '.$req->session()->get('subcabang').' AND EXTRACT(YEAR FROM pj.kunjungansales.tglkunjung) = \''.$req->tahun.'\' AND EXTRACT(YEAR FROM pj.kunjungansales.tglkunjung) = \''.$req->bulan.'\' GROUP BY tokoid) AS kunjungansales'), function($join){
        //     $join->on('batch.rekappenjualanperitemmonthly.tokoid','=','kunjungansales.tokoid');
        // });
        // $query->leftJoin(DB::raw(''), function($join){
        //     $join->on('batch.rekappenjualanperitemmonthly.tokoid','=','plafon.tokoid');
        // });
        // $query->where("batch.rekappenjualanperitemmonthly.periode",'=',$req->tahun.$req->bulan);
        // $query->where("mstr.toko.namatoko",'!=',NULL);
        // $query->groupBy([
        //     'batch.rekappenjualanperitemmonthly.tokoid',
        //     'mstr.toko.customwilayah',
        //     'mstr.toko.namatoko',
        //     'mstr.toko.telp',
        //     'hr.karyawan.kodesales',
        //     'kunjungansales.tglfixedroute',
        //     'mstr.tokopareto.targetomset',
        //     'statustoko.roda',
        // ]);
        // // $query->limit(50);
        // $data  = $query->get();
