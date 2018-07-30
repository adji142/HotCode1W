<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use XLSXWriter;

use App\Models\SubCabang;

// Traits
use App\Http\Traits\ReportTraits;

class LaporanPenjualanControllerBackup extends Controller
{
    use ReportTraits;
    private $type  = [
        1 => 'ALL',
        2 => 'Pareto',
        3 => 'Non Pareto',
        4 => 'Stock Lama Desember 2015',
        5 => 'Bureto April 2017',
    ];

    private $header_format = ['GENERAL'];
    private $header_style  = [
        'font-style' =>'bold',
        'fill'   =>'#dbe5f1',
        'halign' =>'center',
        'border' =>'left,right,top,bottom',
    ];
    private $data_style = [
        'border' =>'left,right,top,bottom',
    ];



    public function index(Request $req)
    {
        $bulan = ['01' => 'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL','05'=>'MEI','06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS','09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER'];
        $tahun = [];
        for($i = date('Y'); $i>= date('Y')-10; $i--){
            $tahun[] = $i;
        }

        return view('laporan.penjualan.index',['bulan'=>$bulan,'type'=>$this->type,'tahun'=>$tahun]);
    }

    public function performanceOutlet(Request $req)
    {
        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE OUTLET');
        $type      = $req->type;
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        return view('laporan.penjualan.performanceoutlet_preview',['type'=>$type,'tahun'=>$tahun,'bulan'=>$bulan]);
    }

    public function performanceOutletData(Request $req)
    {
        $subcabang  = $req->session()->get('subcabang');
        $type   = $req->type;
        $tahun  = $req->tahun;
        $bulan  = $req->bulan;
        $limit  = $req->length;
        $offset = $req->start;

        // need moar execution time
        ini_set('max_execution_time',300);

        // Result
        $data  = $this->performanceOutletQuery($subcabang,$type,$tahun,$bulan,$limit,$offset,true);

        return response()->json([
          'draw'            => $req->draw,
          'recordsTotal'    => $data['total'],
          'recordsFiltered' => $data['total'],
          'data'            => $data['result'],
        ]);
    }

    public function performanceOutletExcel(Request $req)
    {
        $subcabang = $req->session()->get('subcabang');
        $type      = $req->type;
        $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        // need moar execution time
        ini_set('max_execution_time',300);

        // Result
        $data  = $this->performanceOutletQuery($subcabang,$type,$tahun,$bulan);
        $count = count($data);

        // Xlsx Var
        $sheet  = str_slug("Laporan Perfomance Outlet");
        $file   = $sheet."-".uniqid().".xlsx";
        $folder = storage_path('excel/exports');
        $subcabang = SubCabang::find(session('subcabang'))->kodesubcabang;
        $username  = strtoupper(auth()->user()->username);
        $tanggal   = Carbon::now()->format('d/m/Y');

        $header_data   = [
            ["ID WIL", "NAMA TOKO", "P/N", "RODA", "NO TELP", "KODE SALES", "TANGGAL FIXEDROUTE", "SISA PLAFON", "SKU", "EFEKTIF OA%", "TARGET TOKO", "ACHIEVEMENT",""],
            ["","","","","","","","","","","","RP.","%"],
        ];
        for($x=1;$x<=31;$x++) {
            $header_data[0][] = $x;
            $header_data[1][] = "";
        }
        $data_format = [
            'GENERAL','GENERAL','GENERAL','GENERAL','GENERAL','GENERAL','GENERAL','#,##0','#,##0','0.00%','#,##0','#,##0','0.00%',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0',
        ];

        $col_width = [
            25,50,25,25,25,25,40,25,25,25,25,25,25,
            25,25,25,25,25,25,25,25,25,25,
            25,25,25,25,25,25,25,25,25,25,
            25,25,25,25,25,25,25,25,25,25,
            25,
        ];

        // Init Xlsx
        $writer = new XLSXWriter();
        $writer->setAuthor($username);
        $writer->setTempDir($folder);

        // Write Sheet Header
        $writer->writeSheetHeader($sheet,$this->header_format,['suppress_row'=>true,'widths'=>$col_width]);
        $writer->writeSheetRow($sheet,["MONITORING OUTLET CHANNEL ($type_desc)"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["Tanggal Update : $tanggal"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["Cabang : $subcabang"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,['']);

        // Write Table Header
        // Merge
        for($i=0; $i<11; $i++) {
            $writer->markMergedCell($sheet,4,$i,5,$i);
        }
        $writer->markMergedCell($sheet,4,$i,4,$i+1);
        for($j=($i+2); $j<44; $j++) {
            $writer->markMergedCell($sheet,4,$j,5,$j);
        }
        foreach ($header_data as $hdata) {
            $writer->writeSheetRow($sheet,$hdata,$this->header_style);
        }

        // Write Table Data
        foreach ($data as $row) {
            $value = [
                $row->customwilayah,$row->namatoko,$row->pn,$row->roda,$row->telp,$row->kodesales,$row->tglfixedroute,$row->sisa_plafon,$row->sku,$row->efektif_oa,$row->targetomset,$row->achievement_rp,$row->achievement_persen,
                $row->total_1,$row->total_2,$row->total_3,$row->total_4,$row->total_5,$row->total_6,$row->total_7,$row->total_8,$row->total_9,$row->total_10,
                $row->total_11,$row->total_12,$row->total_13,$row->total_14,$row->total_15,$row->total_16,$row->total_17,$row->total_18,$row->total_19,$row->total_20,
                $row->total_21,$row->total_22,$row->total_23,$row->total_24,$row->total_25,$row->total_26,$row->total_27,$row->total_28,$row->total_29,$row->total_30,
                $row->total_31,
            ];

            $writer->writeSheetRow($sheet,$value,$this->data_style,$data_format);
        }
        // Total
        $last = $count+6;
        $total = [
            'TOTAL', '', '', '', '', '', '',
            '=SUM(H7:H'.$last.')','=SUM(I7:I'.$last.')','=SUM(J7:J'.$last.')','=SUM(K7:K'.$last.')','=SUM(L7:L'.$last.')','=SUM(M7:M'.$last.')','=SUM(N7:N'.$last.')',
            '=SUM(O7:O'.$last.')','=SUM(P7:P'.$last.')','=SUM(Q7:Q'.$last.')','=SUM(R7:R'.$last.')','=SUM(S7:S'.$last.')','=SUM(T7:T'.$last.')','=SUM(U7:U'.$last.')',
            '=SUM(V7:V'.$last.')','=SUM(W7:W'.$last.')','=SUM(X7:X'.$last.')','=SUM(Y7:Y'.$last.')','=SUM(Z7:Z'.$last.')',
            '=SUM(AA7:AA'.$last.')','=SUM(AB7:AB'.$last.')','=SUM(AC7:AC'.$last.')','=SUM(AD7:AD'.$last.')','=SUM(AE7:AE'.$last.')','=SUM(AF7:AF'.$last.')','=SUM(AG7:AG'.$last.')',
            '=SUM(AH7:AH'.$last.')','=SUM(AI7:AI'.$last.')','=SUM(AJ7:AJ'.$last.')','=SUM(AK7:AK'.$last.')','=SUM(AL7:AL'.$last.')','=SUM(AM7:AM'.$last.')','=SUM(AN7:AN'.$last.')',
            '=SUM(AO7:AO'.$last.')','=SUM(AP7:AP'.$last.')','=SUM(AQ7:AQ'.$last.')','=SUM(AR7:AR'.$last.')'
        ];
        $writer->writeSheetRow($sheet,$total,['font-style'=>'bold', 'border'=>'left,right,top,bottom',],$data_format);

        // Footer
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,[$username.",".(Carbon::now()->format('d/m/Y h:i:s'))],['font-style'=>'italic']);

        // return if write to file complete
        $writer->writeToFile($folder.'/'.$file);
        return asset('storage/excel/exports/'.$file);
    }

    public function performanceOutletQuery($subcabang,$type,$tahun,$bulan,$limit=0,$offset=0,$wtotal=false)
    {
        // Pertanggal
        for($i=1;$i<=31;$i++) {
            $tanggal[] = "SUM(CASE WHEN EXTRACT(DAY FROM batch.rekappenjualanperitemmonthly.tglproforma) = $i THEN batch.rekappenjualanperitemmonthly.subtotalnettojual ELSE 0 END) AS total_$i ";
        }
        $pertanggal = implode(',', $tanggal);

        // Where
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
                -- (CASE WHEN (plafon.sisa_plafon IS NOT NULL) THEN plafon.sisa_plafon ELSE 0 END) as sisa_plafon,
                -- (CASE WHEN (SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) > 0) THEN SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) ELSE 0 END)
                -- +SUM(pj.notapenjualan.totalnominal)
                -- +SUM(CASE WHEN (rpaccpiutang <= sumdetail) THEN rpaccpiutang ELSE sumdetail END) as sisa_piutang,
                (CASE WHEN (plafon.sisa_plafon IS NOT NULL) THEN plafon.sisa_plafon ELSE 0 END)
                -(
                (CASE WHEN (SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) > 0) THEN SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) ELSE 0 END)
                +SUM(pj.notapenjualan.totalnominal)
                +SUM(CASE WHEN (rpaccpiutang <= sumdetail) THEN rpaccpiutang ELSE sumdetail END)
                ) as sisa_plafon,
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
                AND EXTRACT(MONTH FROM tglkunjung) = '$bulan'
                GROUP BY tokoid
                ) AS kunjungansales ON mstr.toko.id = kunjungansales.tokoid
            LEFT JOIN ptg.kartupiutang ON mstr.toko.id = ptg.kartupiutang.tokoid
            LEFT JOIN ptg.kartupiutangdetail ON ptg.kartupiutang.id = ptg.kartupiutangdetail.kartupiutangid
            LEFT JOIN (
                SELECT tokoid, (plafon.plafon+plafon.plafontambahan) AS sisa_plafon FROM ptg.plafon
                WHERE EXTRACT(YEAR FROM tanggal) = '$tahun'
                AND EXTRACT(MONTH FROM tanggal) = '$bulan'
                ) AS plafon ON mstr.toko.id = plafon.tokoid
            LEFT JOIN pj.notapenjualan ON (pj.notapenjualan.recordownerid = $subcabang AND pj.notapenjualan.tglnota IS NULL AND pj.notapenjualan.tokoid = mstr.toko.id)
            LEFT JOIN (
                SELECT tokoid, COUNT(tglso) AS counttglso FROM pj.orderpenjualan
                WHERE EXTRACT(YEAR FROM tglso) = '$tahun'
                AND EXTRACT(MONTH FROM tglso) = '$bulan'
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
            ORDER BY mstr.toko.customwilayah, mstr.toko.id
        ";

        // Jika butuh paginasi
        if($wtotal){
            $total  = DB::select(DB::raw("SELECT COUNT(id) as total FROM ($query) temptable"));
            $result = DB::select(DB::raw($query." LIMIT ".$limit." OFFSET ".$offset));

            return ['total'=>$total[0]->total,'result'=>$result];
        }else{
            $result = DB::select(DB::raw($query));
            return $result;
       }
    }

    // public function performanceOutlet(Request $req)
    // {
    //     $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE OUTLET');
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

    //     // Where
    //     if($type == 2) {
    //         $where = "AND mstr.tokopareto.tokoid IS NOT NULL";
    //     }elseif($type == 3) {
    //         $where = "AND mstr.tokopareto.tokoid IS NULL";
    //     }else{
    //         $where = "";
    //     }

    //     // Query
    //     $query = "
    //         SELECT
    //             mstr.toko.id,
    //             mstr.toko.namatoko,
    //             mstr.toko.customwilayah,
    //             mstr.toko.telp,
    //             statustoko.roda,
    //             hr.karyawan.kodesales,
    //             (CASE WHEN (mstr.tokopareto.tokoid IS NOT NULL) THEN 'P' ELSE 'N' END) AS pn,
    //             kunjungansales.tglfixedroute,
    //             SUM(CASE WHEN kunjungansales.counttglkunjung IS NOT NULL THEN kunjungansales.counttglkunjung ELSE 0 END) as counttglkunjung,
    //             -- (CASE WHEN (plafon.sisa_plafon IS NOT NULL) THEN plafon.sisa_plafon ELSE 0 END) as sisa_plafon,
    //             -- (CASE WHEN (SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) > 0) THEN SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) ELSE 0 END)
    //             -- +SUM(pj.notapenjualan.totalnominal)
    //             -- +SUM(CASE WHEN (rpaccpiutang <= sumdetail) THEN rpaccpiutang ELSE sumdetail END) as sisa_piutang,
    //             (CASE WHEN (plafon.sisa_plafon IS NOT NULL) THEN plafon.sisa_plafon ELSE 0 END)
    //             -(
    //             (CASE WHEN (SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) > 0) THEN SUM(ptg.kartupiutang.nominal)-SUM(ptg.kartupiutangdetail.nominalbayar) ELSE 0 END)
    //             +SUM(pj.notapenjualan.totalnominal)
    //             +SUM(CASE WHEN (rpaccpiutang <= sumdetail) THEN rpaccpiutang ELSE sumdetail END)
    //             ) as sisa_plafon,
    //             (CASE WHEN (mstr.tokopareto.targetomset IS NOT NULL) THEN mstr.tokopareto.targetomset ELSE 0 END) as targetomset,
    //             COUNT(batch.rekappenjualanperitemmonthly.stockid) AS sku,
    //             (SUM(orderpenjualan.counttglso)/SUM(kunjungansales.counttglkunjung))*100 AS efektif_oa,
    //             (CASE WHEN SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) IS NOT NULL THEN SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) ELSE 0 END) AS achievement_rp,
    //             (CASE WHEN (SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) IS NOT NULL AND mstr.tokopareto.targetomset IS NOT NULL)
    //                 THEN (SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual)/mstr.tokopareto.targetomset)*100 ELSE 0 END
    //             ) AS achievement_persen,
    //             $pertanggal
    //         FROM mstr.toko
    //         LEFT JOIN mstr.tokopareto ON (mstr.toko.id = mstr.tokopareto.tokoid AND mstr.tokopareto.periode = '$tahun$bulan')
    //         LEFT JOIN (
    //             SELECT DISTINCT ON (tokoid) tokoid, roda, tglaktif FROM mstr.statustoko
    //             WHERE roda != '' ORDER BY tokoid ASC, tglaktif DESC
    //             ) AS statustoko ON mstr.toko.id = statustoko.tokoid
    //         LEFT JOIN (
    //             SELECT DISTINCT ON (tokoid) tokoid, karyawanidsalesman, tglaktif FROM mstr.tokohakakses
    //             WHERE recordownerid = $subcabang ORDER BY tokoid ASC, tglaktif DESC
    //             ) AS tokohakakses ON mstr.toko.id = tokohakakses.tokoid
    //         LEFT JOIN hr.karyawan ON hr.karyawan.id = tokohakakses.karyawanidsalesman
    //         LEFT JOIN (
    //             SELECT tokoid, ARRAY_TO_STRING(ARRAY_AGG(DISTINCT(EXTRACT(DAY FROM tglkunjung))), ',') as tglfixedroute, COUNT(tglkunjung) as counttglkunjung
    //             FROM pj.kunjungansales
    //             WHERE recordownerid = $subcabang
    //             AND EXTRACT(YEAR FROM tglkunjung) = '$tahun'
    //             AND EXTRACT(MONTH FROM tglkunjung) = '$bulan'
    //             GROUP BY tokoid
    //             ) AS kunjungansales ON mstr.toko.id = kunjungansales.tokoid
    //         LEFT JOIN ptg.kartupiutang ON mstr.toko.id = ptg.kartupiutang.tokoid
    //         LEFT JOIN ptg.kartupiutangdetail ON ptg.kartupiutang.id = ptg.kartupiutangdetail.kartupiutangid
    //         LEFT JOIN (
    //             SELECT tokoid, (plafon.plafon+plafon.plafontambahan) AS sisa_plafon FROM ptg.plafon
    //             WHERE EXTRACT(YEAR FROM tanggal) = '$tahun'
    //             AND EXTRACT(MONTH FROM tanggal) = '$bulan'
    //             ) AS plafon ON mstr.toko.id = plafon.tokoid
    //         LEFT JOIN pj.notapenjualan ON (pj.notapenjualan.recordownerid = $subcabang AND pj.notapenjualan.tglnota IS NULL AND pj.notapenjualan.tokoid = mstr.toko.id)
    //         LEFT JOIN (
    //             SELECT tokoid, COUNT(tglso) AS counttglso FROM pj.orderpenjualan
    //             WHERE EXTRACT(YEAR FROM tglso) = '$tahun'
    //             AND EXTRACT(MONTH FROM tglso) = '$bulan'
    //             GROUP BY tokoid
    //             ) AS orderpenjualan ON mstr.toko.id = orderpenjualan.tokoid
    //         LEFT JOIN (
    //             SELECT pj.orderpenjualan.id, pj.orderpenjualan.tokoid, SUM(rpaccpiutang) AS rpaccpiutang, SUM(pj.orderpenjualandetail.qtysoacc*pj.orderpenjualandetail.hrgsatuannetto) AS sumdetail FROM pj.orderpenjualan
    //             LEFT JOIN pj.orderpenjualandetail ON pj.orderpenjualan.id = pj.orderpenjualandetail.orderpenjualanid
    //             LEFT JOIN pj.notapenjualan ON pj.orderpenjualan.id = pj.notapenjualan.orderpenjualanid
    //             WHERE noaccpiutang != ''
    //             AND pj.notapenjualan.id IS NULL
    //             GROUP BY pj.orderpenjualan.id
    //             ) AS opj ON mstr.toko.id = opj.tokoid
    //         LEFT JOIN batch.rekappenjualanperitemmonthly ON (mstr.toko.id = batch.rekappenjualanperitemmonthly.tokoid AND batch.rekappenjualanperitemmonthly.periode = '$tahun$bulan')
    //         WHERE (mstr.toko.statusaktif = TRUE OR orderpenjualan.counttglso != 0) $where
    //         GROUP BY
    //             mstr.toko.id,
    //             mstr.toko.namatoko,
    //             mstr.toko.customwilayah,
    //             mstr.toko.telp,
    //             statustoko.roda,
    //             hr.karyawan.kodesales,
    //             pn,
    //             kunjungansales.tglfixedroute,
    //             sisa_plafon,
    //             targetomset
    //         ORDER BY mstr.toko.customwilayah, mstr.toko.id
    //     ";

    //     // need moar execution time
    //     ini_set('max_execution_time',300);

    //     // Result
    //     $data  = DB::select(DB::raw($query));
    //     $count = count($data);

    //     // Xlsx Var
    //     $sheet  = str_slug("Laporan Perfomance Outlet");
    //     $file   = $sheet."-".uniqid().".xlsx";
    //     $folder = storage_path('excel/exports');
    //     $subcabang = SubCabang::find(session('subcabang'))->kodesubcabang;
    //     $username  = strtoupper(auth()->user()->username);
    //     $tanggal   = Carbon::now()->format('d/m/Y');

    //     $header_data   = [
    //         ["ID WIL", "NAMA TOKO", "P/N", "RODA", "NO TELP", "KODE SALES", "TANGGAL FIXEDROUTE", "SISA PLAFON", "SKU", "EFEKTIF OA%", "TARGET TOKO", "ACHIEVEMENT",""],
    //         ["","","","","","","","","","","","RP.","%"],
    //     ];
    //     for($x=1;$x<=31;$x++) {
    //         $header_data[0][] = $x;
    //         $header_data[1][] = "";
    //     }
    //     $data_format = [
    //         'GENERAL','GENERAL','GENERAL','GENERAL','GENERAL','GENERAL','GENERAL','#,##0','#,##0','0.00%','#,##0','#,##0','0.00%',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
    //         '#,##0',
    //     ];

    //     $col_width = [
    //         25,50,25,25,25,25,40,25,25,25,25,25,25,
    //         25,25,25,25,25,25,25,25,25,25,
    //         25,25,25,25,25,25,25,25,25,25,
    //         25,25,25,25,25,25,25,25,25,25,
    //         25,
    //     ];

    //     // Init Xlsx
    //     $writer = new XLSXWriter();
    //     $writer->setAuthor($username);
    //     $writer->setTempDir($folder);

    //     // Write Sheet Header
    //     $writer->writeSheetHeader($sheet,$this->header_format,['suppress_row'=>true,'widths'=>$col_width]);
    //     $writer->writeSheetRow($sheet,["MONITORING OUTLET CHANNEL ($type_desc)"],['font-style'=>'bold']);
    //     $writer->writeSheetRow($sheet,["Tanggal Update : $tanggal"],['font-style'=>'bold']);
    //     $writer->writeSheetRow($sheet,["Cabang : $subcabang"],['font-style'=>'bold']);
    //     $writer->writeSheetRow($sheet,['']);

    //     // Write Table Header
    //     // Merge
    //     for($i=0; $i<11; $i++) {
    //         $writer->markMergedCell($sheet,4,$i,5,$i);
    //     }
    //     $writer->markMergedCell($sheet,4,$i,4,$i+1);
    //     for($j=($i+2); $j<44; $j++) {
    //         $writer->markMergedCell($sheet,4,$j,5,$j);
    //     }
    //     foreach ($header_data as $hdata) {
    //         $writer->writeSheetRow($sheet,$hdata,$this->header_style);
    //     }

    //     // Write Table Data
    //     foreach ($data as $row) {
    //         $value = [
    //             $row->customwilayah,$row->namatoko,$row->pn,$row->roda,$row->telp,$row->kodesales,$row->tglfixedroute,$row->sisa_plafon,$row->sku,$row->efektif_oa,$row->targetomset,$row->achievement_rp,$row->achievement_persen,
    //             $row->total_1,$row->total_2,$row->total_3,$row->total_4,$row->total_5,$row->total_6,$row->total_7,$row->total_8,$row->total_9,$row->total_10,
    //             $row->total_11,$row->total_12,$row->total_13,$row->total_14,$row->total_15,$row->total_16,$row->total_17,$row->total_18,$row->total_19,$row->total_20,
    //             $row->total_21,$row->total_22,$row->total_23,$row->total_24,$row->total_25,$row->total_26,$row->total_27,$row->total_28,$row->total_29,$row->total_30,
    //             $row->total_31,
    //         ];

    //         $writer->writeSheetRow($sheet,$value,$this->data_style,$data_format);
    //     }
    //     // Total
    //     $last = $count+6;
    //     $total = [
    //         'TOTAL', '', '', '', '', '', '',
    //         '=SUM(H7:H'.$last.')','=SUM(I7:I'.$last.')','=SUM(J7:J'.$last.')','=SUM(K7:K'.$last.')','=SUM(L7:L'.$last.')','=SUM(M7:M'.$last.')','=SUM(N7:N'.$last.')',
    //         '=SUM(O7:O'.$last.')','=SUM(P7:P'.$last.')','=SUM(Q7:Q'.$last.')','=SUM(R7:R'.$last.')','=SUM(S7:S'.$last.')','=SUM(T7:T'.$last.')','=SUM(U7:U'.$last.')',
    //         '=SUM(V7:V'.$last.')','=SUM(W7:W'.$last.')','=SUM(X7:X'.$last.')','=SUM(Y7:Y'.$last.')','=SUM(Z7:Z'.$last.')',
    //         '=SUM(AA7:AA'.$last.')','=SUM(AB7:AB'.$last.')','=SUM(AC7:AC'.$last.')','=SUM(AD7:AD'.$last.')','=SUM(AE7:AE'.$last.')','=SUM(AF7:AF'.$last.')','=SUM(AG7:AG'.$last.')',
    //         '=SUM(AH7:AH'.$last.')','=SUM(AI7:AI'.$last.')','=SUM(AJ7:AJ'.$last.')','=SUM(AK7:AK'.$last.')','=SUM(AL7:AL'.$last.')','=SUM(AM7:AM'.$last.')','=SUM(AN7:AN'.$last.')',
    //         '=SUM(AO7:AO'.$last.')','=SUM(AP7:AP'.$last.')','=SUM(AQ7:AQ'.$last.')','=SUM(AR7:AR'.$last.')'
    //     ];
    //     $writer->writeSheetRow($sheet,$total,['font-style'=>'bold', 'border'=>'left,right,top,bottom',],$data_format);

    //     // Footer
    //     $writer->writeSheetRow($sheet,['']);
    //     $writer->writeSheetRow($sheet,['']);
    //     $writer->writeSheetRow($sheet,[$username.",".(Carbon::now()->format('d/m/Y h:i:s'))],['font-style'=>'italic']);
    //     $writer->writeToFile($folder.'/'.$file);

    //     $urlExcel = asset('storage/excel/exports/'.$file);

    //     $data_json = json_encode($data);
    //     return view('laporan.penjualan.performanceoutlets',['data'=>$data_json,'urlExcel'=>$urlExcel]);
    //     // return view('laporan.penjualan.performanceoutlet',['data'=>$data,'urlExcel'=>$urlExcel]);        
    // }

    public function performanceProduct(Request $req)
    {
        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE PRODUCT');
        $type      = $req->type;
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        return view('laporan.penjualan.performanceproducts_preview',['type'=>$type,'tahun'=>$tahun,'bulan'=>$bulan]);
    }

    public function performanceProductData(Request $req)
    {
        $subcabang  = $req->session()->get('subcabang');
        $type   = $req->type;
        $tahun  = $req->tahun;
        $bulan  = $req->bulan;
        $limit  = $req->length;
        $offset = $req->start;

        // need moar execution time
        ini_set('max_execution_time',300);

        // Result
        $data  = $this->performanceProductQuery($subcabang,$type,$tahun,$bulan,$limit,$offset,null,true);

        return response()->json([
          'draw'            => $req->draw,
          'recordsTotal'    => $data['total'],
          'recordsFiltered' => $data['total'],
          'data'            => $data['result'],
        ]);
    }

    public function performanceProductExcel(Request $req)
    {
        $subcabang = $req->session()->get('subcabang');
        $type      = $req->type;
        $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        // need moar execution time
        ini_set('max_execution_time',300);

        // Result
        $data  = $this->performanceProductQuery($subcabang,$type,$tahun,$bulan);
        $count = count($data);

        // Xlsx Var
        $sheet  = str_slug("Laporan Perfomance Product");
        $file   = $sheet."-".uniqid().".xlsx";
        $folder = storage_path('excel/exports');
        $subcabang = SubCabang::find(session('subcabang'))->kodesubcabang;
        $username  = strtoupper(auth()->user()->username);
        $tanggal   = Carbon::now()->format('d/m/Y');

        $header_data   = [
            ["KODE BARANG","MAIN GROUP","NAMA BARANG","P / N","PRODUCT MIX","TARGET JUAL","","STOCK GUDANG","","STOCK MASUK GIT","","PROYEKSI STOCK","","ACHIEVEMENT QTY","","ACHIEVEMENT RP",""],
            ["","","","","","QTY","RP","QTY","RP","QTY","RP","QTY","RP","QTY","%","RP","%",],
        ];
        for($x=1;$x<=31;$x++) {
            $header_data[0][] = $x;
            $header_data[1][] = "";
        }
        $data_format = [
            'GENERAL','GENERAL','GENERAL','GENERAL','GENERAL',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','0.00%','#,##0','0.00%',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0',
        ];

        $col_width = [
            17,20,90,8,15,10,18,10,18,10,
            18,10,18,10,10,18,10,
            18,18,18,18,18,18,18,18,18,18,
            18,18,18,18,18,18,18,18,18,18,
            18,18,18,18,18,18,18,18,18,18,
            18,
        ];

        // Init Xlsx
        $writer = new XLSXWriter();
        $writer->setAuthor($username);
        $writer->setTempDir($folder);

        // Write Sheet Header
        $writer->writeSheetHeader($sheet,$this->header_format,['suppress_row'=>true,'widths'=>$col_width]);
        $writer->writeSheetRow($sheet,["MONITORING PRODUCT ($type_desc)"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["Tanggal Update : $tanggal"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["Cabang : $subcabang"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,['']);

        // Write Table Header
        // Merge
        $writer->markMergedCell($sheet,4,0,5,0);
        $writer->markMergedCell($sheet,4,1,5,1);
        $writer->markMergedCell($sheet,4,2,5,2);
        $writer->markMergedCell($sheet,4,3,5,3);
        $writer->markMergedCell($sheet,4,4,5,4);
        $writer->markMergedCell($sheet,4,5,4,6);
        $writer->markMergedCell($sheet,4,7,4,8);
        $writer->markMergedCell($sheet,4,9,4,10);
        $writer->markMergedCell($sheet,4,11,4,12);
        $writer->markMergedCell($sheet,4,13,4,14);
        $writer->markMergedCell($sheet,4,15,4,16);
        for($k=17; $k<48; $k++) {
            $writer->markMergedCell($sheet,4,$k,5,$k);
        }
        foreach ($header_data as $hdata) {
            $writer->writeSheetRow($sheet,$hdata,$this->header_style);
        }

        // Write Table Data
        foreach ($data as $row) {
            $value = [
                $row->kodebarang, $row->groupname, $row->namabarang, $row->pn, $row->kategori,$row->target_qty,$row->target_rp,$row->stokgudang_qty,$row->stokgudang_rp,
                $row->git_qty,$row->git_rp,$row->proyeksi_qty,$row->proyeksi_rp,$row->achievement_qty,$row->achievement_qty_persen,$row->achievement_rp,$row->achievement_rp_persen,
                $row->total_1,$row->total_2,$row->total_3,$row->total_4,$row->total_5,$row->total_6,$row->total_7,$row->total_8,$row->total_9,$row->total_10,
                $row->total_11,$row->total_12,$row->total_13,$row->total_14,$row->total_15,$row->total_16,$row->total_17,$row->total_18,$row->total_19,$row->total_20,
                $row->total_21,$row->total_22,$row->total_23,$row->total_24,$row->total_25,$row->total_26,$row->total_27,$row->total_28,$row->total_29,$row->total_30,
                $row->total_31,
            ];

            $writer->writeSheetRow($sheet,$value,$this->data_style,$data_format);
        }
        // Total
        $last = $count+6;
        $total = [
            'TOTAL', '', '', '', '',
            '=SUM(F7:F'.$last.')','=SUM(G7:G'.$last.')','=SUM(H7:H'.$last.')','=SUM(I7:I'.$last.')','=SUM(J7:J'.$last.')','=SUM(K7:K'.$last.')','=SUM(L7:L'.$last.')',
            '=SUM(M7:M'.$last.')','=SUM(N7:N'.$last.')','=SUM(O7:O'.$last.')','=SUM(P7:P'.$last.')','=SUM(Q7:Q'.$last.')','=SUM(R7:R'.$last.')','=SUM(S7:S'.$last.')',
            '=SUM(T7:T'.$last.')','=SUM(U7:U'.$last.')','=SUM(V7:V'.$last.')','=SUM(W7:W'.$last.')','=SUM(X7:X'.$last.')','=SUM(Y7:Y'.$last.')','=SUM(Z7:Z'.$last.')',
            '=SUM(AA7:AA'.$last.')','=SUM(AB7:AB'.$last.')','=SUM(AC7:AC'.$last.')','=SUM(AD7:AD'.$last.')','=SUM(AE7:AE'.$last.')','=SUM(AF7:AF'.$last.')','=SUM(AG7:AG'.$last.')',
            '=SUM(AH7:AH'.$last.')','=SUM(AI7:AI'.$last.')','=SUM(AJ7:AJ'.$last.')','=SUM(AK7:AK'.$last.')','=SUM(AL7:AL'.$last.')','=SUM(AM7:AM'.$last.')','=SUM(AN7:AN'.$last.')',
            '=SUM(AO7:AO'.$last.')','=SUM(AP7:AP'.$last.')','=SUM(AQ7:AQ'.$last.')','=SUM(AR7:AR'.$last.')','=SUM(AS7:AS'.$last.')','=SUM(AT7:AT'.$last.')','=SUM(AU7:AU'.$last.')','=SUM(AV7:AV'.$last.')',
        ];
        $writer->markMergedCell($sheet,$last,0,$last,4);
        $writer->writeSheetRow($sheet,$total,['font-style'=>'bold', 'border'=>'left,right,top,bottom',],$data_format);

        // Footer
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,[$username.",".(Carbon::now()->format('d/m/Y h:i:s'))],['font-style'=>'italic']);

        // return if write to file complete
        $writer->writeToFile($folder.'/'.$file);
        return asset('storage/excel/exports/'.$file);
    }

    // public function performanceProduct(Request $req)
    // {
    //     $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE PRODUCT');
    //     $subcabang = $req->session()->get('subcabang');
    //     $type      = $req->type;
    //     $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
    //     $tahun     = $req->tahun;
    //     $bulan     = $req->bulan;

    //     // need moar execution time
    //     ini_set('max_execution_time',300);

    //     // Result
    //     $data  = $this->performanceProductQuery($subcabang,$type,$tahun,$bulan);
    //     $count = count($data);

    //     // Xlsx Var
    //     $sheet  = str_slug("Laporan Perfomance Product");
    //     $file   = $sheet."-".uniqid().".xlsx";
    //     $folder = storage_path('excel/exports');
    //     $subcabang = SubCabang::find(session('subcabang'))->kodesubcabang;
    //     $username  = strtoupper(auth()->user()->username);
    //     $tanggal   = Carbon::now()->format('d/m/Y');

    //     $header_data   = [
    //         ["KODE BARANG","MAIN GROUP","NAMA BARANG","P / N","PRODUCT MIX","TARGET JUAL","","STOCK GUDANG","","STOCK MASUK GIT","","PROYEKSI STOCK","","ACHIEVEMENT QTY","","ACHIEVEMENT RP",""],
    //         ["","","","","","QTY","RP","QTY","RP","QTY","RP","QTY","RP","QTY","%","RP","%",],
    //     ];
    //     for($x=1;$x<=31;$x++) {
    //         $header_data[0][] = $x;
    //         $header_data[1][] = "";
    //     }
    //     $data_format = [
    //         'GENERAL','GENERAL','GENERAL','GENERAL','GENERAL',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','0.00%','#,##0','0.00%',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
    //         '#,##0',
    //     ];

    //     $col_width = [
    //         17,20,90,8,15,10,18,10,18,10,
    //         18,10,18,10,10,18,10,
    //         18,18,18,18,18,18,18,18,18,18,
    //         18,18,18,18,18,18,18,18,18,18,
    //         18,18,18,18,18,18,18,18,18,18,
    //         18,
    //     ];

    //     // Init Xlsx
    //     $writer = new XLSXWriter();
    //     $writer->setAuthor($username);
    //     $writer->setTempDir($folder);

    //     // Write Sheet Header
    //     $writer->writeSheetHeader($sheet,$this->header_format,['suppress_row'=>true,'widths'=>$col_width]);
    //     $writer->writeSheetRow($sheet,["MONITORING PRODUCT ($type_desc)"],['font-style'=>'bold']);
    //     $writer->writeSheetRow($sheet,["Tanggal Update : $tanggal"],['font-style'=>'bold']);
    //     $writer->writeSheetRow($sheet,["Cabang : $subcabang"],['font-style'=>'bold']);
    //     $writer->writeSheetRow($sheet,['']);

    //     // Write Table Header
    //     // Merge
    //     $writer->markMergedCell($sheet,4,0,5,0);
    //     $writer->markMergedCell($sheet,4,1,5,1);
    //     $writer->markMergedCell($sheet,4,2,5,2);
    //     $writer->markMergedCell($sheet,4,3,5,3);
    //     $writer->markMergedCell($sheet,4,4,5,4);
    //     $writer->markMergedCell($sheet,4,5,4,6);
    //     $writer->markMergedCell($sheet,4,7,4,8);
    //     $writer->markMergedCell($sheet,4,9,4,10);
    //     $writer->markMergedCell($sheet,4,11,4,12);
    //     $writer->markMergedCell($sheet,4,13,4,14);
    //     $writer->markMergedCell($sheet,4,15,4,16);
    //     for($k=17; $k<48; $k++) {
    //         $writer->markMergedCell($sheet,4,$k,5,$k);
    //     }
    //     foreach ($header_data as $hdata) {
    //         $writer->writeSheetRow($sheet,$hdata,$this->header_style);
    //     }

    //     // Write Table Data
    //     foreach ($data as $row) {
    //         $value = [
    //             $row->kodebarang, $row->groupname, $row->namabarang, $row->pn, $row->kategori,$row->target_qty,$row->target_rp,$row->stokgudang_qty,$row->stokgudang_rp,
    //             $row->git_qty,$row->git_rp,$row->proyeksi_qty,$row->proyeksi_rp,$row->achievement_qty,$row->achievement_qty_persen,$row->achievement_rp,$row->achievement_rp_persen,
    //             $row->total_1,$row->total_2,$row->total_3,$row->total_4,$row->total_5,$row->total_6,$row->total_7,$row->total_8,$row->total_9,$row->total_10,
    //             $row->total_11,$row->total_12,$row->total_13,$row->total_14,$row->total_15,$row->total_16,$row->total_17,$row->total_18,$row->total_19,$row->total_20,
    //             $row->total_21,$row->total_22,$row->total_23,$row->total_24,$row->total_25,$row->total_26,$row->total_27,$row->total_28,$row->total_29,$row->total_30,
    //             $row->total_31,
    //         ];

    //         $writer->writeSheetRow($sheet,$value,$this->data_style,$data_format);
    //     }
    //     // Total
    //     $last = $count+6;
    //     $total = [
    //         'TOTAL', '', '', '', '',
    //         '=SUM(F7:F'.$last.')','=SUM(G7:G'.$last.')','=SUM(H7:H'.$last.')','=SUM(I7:I'.$last.')','=SUM(J7:J'.$last.')','=SUM(K7:K'.$last.')','=SUM(L7:L'.$last.')',
    //         '=SUM(M7:M'.$last.')','=SUM(N7:N'.$last.')','=SUM(O7:O'.$last.')','=SUM(P7:P'.$last.')','=SUM(Q7:Q'.$last.')','=SUM(R7:R'.$last.')','=SUM(S7:S'.$last.')',
    //         '=SUM(T7:T'.$last.')','=SUM(U7:U'.$last.')','=SUM(V7:V'.$last.')','=SUM(W7:W'.$last.')','=SUM(X7:X'.$last.')','=SUM(Y7:Y'.$last.')','=SUM(Z7:Z'.$last.')',
    //         '=SUM(AA7:AA'.$last.')','=SUM(AB7:AB'.$last.')','=SUM(AC7:AC'.$last.')','=SUM(AD7:AD'.$last.')','=SUM(AE7:AE'.$last.')','=SUM(AF7:AF'.$last.')','=SUM(AG7:AG'.$last.')',
    //         '=SUM(AH7:AH'.$last.')','=SUM(AI7:AI'.$last.')','=SUM(AJ7:AJ'.$last.')','=SUM(AK7:AK'.$last.')','=SUM(AL7:AL'.$last.')','=SUM(AM7:AM'.$last.')','=SUM(AN7:AN'.$last.')',
    //         '=SUM(AO7:AO'.$last.')','=SUM(AP7:AP'.$last.')','=SUM(AQ7:AQ'.$last.')','=SUM(AR7:AR'.$last.')','=SUM(AS7:AS'.$last.')','=SUM(AT7:AT'.$last.')','=SUM(AU7:AU'.$last.')','=SUM(AV7:AV'.$last.')',
    //     ];
    //     $writer->markMergedCell($sheet,$last,0,$last,4);
    //     $writer->writeSheetRow($sheet,$total,['font-style'=>'bold', 'border'=>'left,right,top,bottom',],$data_format);

    //     // Footer
    //     $writer->writeSheetRow($sheet,['']);
    //     $writer->writeSheetRow($sheet,['']);
    //     $writer->writeSheetRow($sheet,[$username.",".(Carbon::now()->format('d/m/Y h:i:s'))],['font-style'=>'italic']);
    //     $writer->writeToFile($folder.'/'.$file);

    //     $urlExcel = asset('storage/excel/exports/'.$file);

    //     $data_json = json_encode($data);
    //     return view('laporan.penjualan.performanceproducts',['data'=>$data_json,'urlExcel'=>$urlExcel]);
    // }

    public function performanceProductPersales(Request $req)
    {
        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE PRODUCT PERSALES');
        $subcabang = $req->session()->get('subcabang');
        $type      = $req->type;
        $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        // Get Sales list
        $sales = DB::select(DB::raw("
            SELECT DISTINCT(karyawanidsalesman) karyawanidsalesman, kodesales, namakaryawan
            FROM batch.rekappenjualanperitemmonthly
            INNER JOIN hr.karyawan ON hr.karyawan.id = batch.rekappenjualanperitemmonthly.karyawanidsalesman
            WHERE periode = '$tahun$bulan' AND kodesales != ''
            ORDER BY kodesales
        "));

        return view('laporan.penjualan.performanceproductspersales_preview',['sales'=>$sales,'type'=>$type,'tahun'=>$tahun,'bulan'=>$bulan]);
    }

    public function performanceProductPersalesData(Request $req)
    {
        $subcabang  = $req->session()->get('subcabang');
        $type       = $req->type;
        $tahun      = $req->tahun;
        $bulan      = $req->bulan;
        $idsalesman = $req->idsalesman;
        $limit  = $req->length;
        $offset = $req->start;

        if($req->idsalesman) {
            // need moar execution time
            ini_set('max_execution_time',300);

            // Result
            $data  = $this->performanceProductQuery($subcabang,$type,$tahun,$bulan,$limit,$offset,$idsalesman,true);

            return response()->json([
              'draw'            => $req->draw,
              'recordsTotal'    => $data['total'],
              'recordsFiltered' => $data['total'],
              'data'            => $data['result'],
            ]);
        }else{
            return response()->json([
              'draw'            => $req->draw,
              'recordsTotal'    => 0,
              'recordsFiltered' => 0,
              'data'            => [],
            ]);
        }
    }

    public function performanceProductPersalesExcel(Request $req)
    {
        $subcabang = $req->session()->get('subcabang');
        $type      = $req->type;
        $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        // Get Sales list
        $sales = DB::select(DB::raw("
            SELECT DISTINCT(karyawanidsalesman) karyawanidsalesman, kodesales, namakaryawan
            FROM batch.rekappenjualanperitemmonthly
            INNER JOIN hr.karyawan ON hr.karyawan.id = batch.rekappenjualanperitemmonthly.karyawanidsalesman
            WHERE periode = '$tahun$bulan' AND kodesales != ''
            ORDER BY kodesales
        "));

        // need moar execution time
        ini_set('max_execution_time',300);

        // Xlsx Var
        $sheet  = str_slug("Laporan Perfomance Product Per Sales");
        $file   = $sheet."-".uniqid().".xlsx";
        $folder = storage_path('excel/exports');
        $kodesubcabang = SubCabang::find(session('subcabang'))->kodesubcabang;
        $username = strtoupper(auth()->user()->username);
        $tanggal  = Carbon::now()->format('d/m/Y');
        $time     = Carbon::now()->format('d/m/Y h:i:s');

        $header_data   = [
            ["KODE BARANG","MAIN GROUP","NAMA BARANG","P / N","PRODUCT MIX","TARGET JUAL","","STOCK GUDANG","","STOCK MASUK GIT","","PROYEKSI STOCK","","ACHIEVEMENT QTY","","ACHIEVEMENT RP",""],
            ["","","","","","QTY","RP","QTY","RP","QTY","RP","QTY","RP","QTY","%","RP","%",],
        ];
        for($x=1;$x<=31;$x++) {
            $header_data[0][] = $x;
            $header_data[1][] = "";
        }
        $data_format = [
            'GENERAL','GENERAL','GENERAL','GENERAL','GENERAL',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','0.00%','#,##0','0.00%',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0',
        ];

        $col_width = [
            17,20,90,8,15,10,18,10,18,10,
            18,10,18,10,10,18,10,
            18,18,18,18,18,18,18,18,18,18,
            18,18,18,18,18,18,18,18,18,18,
            18,18,18,18,18,18,18,18,18,18,
            18,
        ];

        // Init Xlsx
        $writer = new XLSXWriter();
        $writer->setAuthor($username);
        $writer->setTempDir($folder);

        // Loop for multiple Sheet
        foreach ($sales as $sal) {
            // Get Data
            $data  = $this->performanceProductQuery($subcabang,$type,$tahun,$bulan,0,0,$sal->karyawanidsalesman);
            $count = count($data);

            // Write Sheet Header
            $writer->writeSheetHeader($sal->kodesales,$this->header_format,['suppress_row'=>true,'widths'=>$col_width]);
            $writer->writeSheetRow($sal->kodesales,["MONITORING PRODUCT ($type_desc)"],['font-style'=>'bold']);
            $writer->writeSheetRow($sal->kodesales,["Tanggal Update : $tanggal"],['font-style'=>'bold']);
            $writer->writeSheetRow($sal->kodesales,["Cabang : $kodesubcabang"],['font-style'=>'bold']);
            $writer->writeSheetRow($sal->kodesales,["Kode Sales : ".$sal->kodesales],['font-style'=>'bold']);
            $writer->writeSheetRow($sal->kodesales,["Nama Sales : ".$sal->namakaryawan],['font-style'=>'bold']);
            $writer->writeSheetRow($sal->kodesales,['']);

            // Merge Table Header
            $writer->markMergedCell($sal->kodesales,6,0,7,0);
            $writer->markMergedCell($sal->kodesales,6,1,7,1);
            $writer->markMergedCell($sal->kodesales,6,2,7,2);
            $writer->markMergedCell($sal->kodesales,6,3,7,3);
            $writer->markMergedCell($sal->kodesales,6,4,7,4);
            $writer->markMergedCell($sal->kodesales,6,5,6,6);
            $writer->markMergedCell($sal->kodesales,6,7,6,8);
            $writer->markMergedCell($sal->kodesales,6,9,6,10);
            $writer->markMergedCell($sal->kodesales,6,11,6,12);
            $writer->markMergedCell($sal->kodesales,6,13,6,14);
            $writer->markMergedCell($sal->kodesales,6,15,6,16);
            for($k=17; $k<48; $k++) {
                $writer->markMergedCell($sal->kodesales,6,$k,7,$k);
            }

            // Write Table Header
            foreach ($header_data as $hdata) {
                $writer->writeSheetRow($sal->kodesales,$hdata,$this->header_style);
            }

            // Write Table Data
            foreach ($data as $row) {
                $value = [
                    $row->kodebarang, $row->groupname, $row->namabarang, $row->pn, $row->kategori,$row->target_qty,$row->target_rp,$row->stokgudang_qty,$row->stokgudang_rp,
                    $row->git_qty,$row->git_rp,$row->proyeksi_qty,$row->proyeksi_rp,$row->achievement_qty,$row->achievement_qty_persen,$row->achievement_rp,
                    // 0,
                    $row->achievement_rp_persen,
                    $row->total_1,$row->total_2,$row->total_3,$row->total_4,$row->total_5,$row->total_6,$row->total_7,$row->total_8,$row->total_9,$row->total_10,
                    $row->total_11,$row->total_12,$row->total_13,$row->total_14,$row->total_15,$row->total_16,$row->total_17,$row->total_18,$row->total_19,$row->total_20,
                    $row->total_21,$row->total_22,$row->total_23,$row->total_24,$row->total_25,$row->total_26,$row->total_27,$row->total_28,$row->total_29,$row->total_30,
                    $row->total_31,
                ];

                $writer->writeSheetRow($sal->kodesales,$value,$this->data_style,$data_format);
            }
            
            // Total
            $last  = $count+8;
            $total = [
                'TOTAL', '', '', '', '',
                '=SUM(F9:F'.$last.')','=SUM(G9:G'.$last.')','=SUM(H9:H'.$last.')','=SUM(I9:I'.$last.')','=SUM(J9:J'.$last.')','=SUM(K9:K'.$last.')','=SUM(L9:L'.$last.')',
                '=SUM(M9:M'.$last.')','=SUM(N9:N'.$last.')','=SUM(O9:O'.$last.')','=SUM(P9:P'.$last.')','=SUM(Q9:Q'.$last.')','=SUM(R9:R'.$last.')','=SUM(S9:S'.$last.')',
                '=SUM(T9:T'.$last.')','=SUM(U9:U'.$last.')','=SUM(V9:V'.$last.')','=SUM(W9:W'.$last.')','=SUM(X9:X'.$last.')','=SUM(Y9:Y'.$last.')','=SUM(Z9:Z'.$last.')',
                '=SUM(AA9:AA'.$last.')','=SUM(AB9:AB'.$last.')','=SUM(AC9:AC'.$last.')','=SUM(AD9:AD'.$last.')','=SUM(AE9:AE'.$last.')','=SUM(AF9:AF'.$last.')','=SUM(AG9:AG'.$last.')',
                '=SUM(AH9:AH'.$last.')','=SUM(AI9:AI'.$last.')','=SUM(AJ9:AJ'.$last.')','=SUM(AK9:AK'.$last.')','=SUM(AL9:AL'.$last.')','=SUM(AM9:AM'.$last.')','=SUM(AN9:AN'.$last.')',
                '=SUM(AO9:AO'.$last.')','=SUM(AP9:AP'.$last.')','=SUM(AQ9:AQ'.$last.')','=SUM(AR9:AR'.$last.')','=SUM(AS9:AS'.$last.')','=SUM(AT9:AT'.$last.')','=SUM(AU9:AU'.$last.')','=SUM(AV9:AV'.$last.')',
            ];
            $writer->markMergedCell($sal->kodesales,$last,0,$last,4);
            $writer->writeSheetRow($sal->kodesales,$total,['font-style'=>'bold', 'border'=>'left,right,top,bottom',],$data_format);

            // Footer
            $writer->writeSheetRow($sal->kodesales,['']);
            $writer->writeSheetRow($sal->kodesales,['']);
            $writer->writeSheetRow($sal->kodesales,[$username.",".$time],['font-style'=>'italic']);
        }

        // return if write to file complete
        $writer->writeToFile($folder.'/'.$file);
        return asset('storage/excel/exports/'.$file);
    }

    public function performanceProductQuery($subcabang,$type,$tahun,$bulan,$limit=0,$offset=0,$salesman=null,$wtotal=false)
    {
        // Pertanggal
        for($i=1;$i<=31;$i++) {
            $tanggal[] = "SUM(CASE WHEN EXTRACT(DAY FROM batch.rekappenjualanperitemmonthly.tglproforma) = $i THEN batch.rekappenjualanperitemmonthly.subtotalnettojual ELSE 0 END) AS total_$i";
        }
        $pertanggal = implode(', ', $tanggal);

        // Where
        if($type == 2) {
            $where = "AND mstr.groupparts.groupname ILIKE '%PARETO%'";
        }elseif($type == 3) {
            $where = "AND mstr.groupparts.groupname NOT ILIKE '%PARETO%' AND mstr.groupparts.createdby = 'MANUAL_AND_ERV'";
        }elseif($type == 4) {
            $where = "AND mstr.groupparts.groupname ILIKE '%STOCK LAMA 201512%'";
        }elseif($type == 5) {
            $where = "AND mstr.groupparts.groupname ILIKE '%BURETO HAN MEI 2017%'";
        }else{
            $where = "";
        }

        // Query
        $query = "
            SELECT
                mstr.stock.kodebarang,
                mstr.stock.namabarang,
                mstr.groupparts.groupname,
                (CASE WHEN (mstr.groupparts.groupname ILIKE '%PARETO%') THEN 'P' ELSE 'N' END) AS pn,
                mstr.kategoripenjualan.kategori,
                COALESCE(batch.targetstockparetogoogledocsdk.planqty,0) as target_qty,
                COALESCE(batch.targetstockparetogoogledocsdk.plansales,0) as target_rp,
                COALESCE(rekapstockdaily.stokgudang,0) as stokgudang_qty,
                COALESCE(rekapstockdaily.stokgudang*rekapstockdaily.hppa,0) as stokgudang_rp,
                SUM(COALESCE(npjd.qtynota,0)) as git_qty,
                SUM(COALESCE(npjd.total_git,0)) as git_rp,
                COALESCE((rekapstockdaily.stokgudang+SUM(npjd.qtynota))-(SUM(batch.rekappenjualanperitemmonthly.qty)),0) as proyeksi_qty,
                COALESCE(((rekapstockdaily.stokgudang+SUM(npjd.qtynota))-(SUM(batch.rekappenjualanperitemmonthly.qty)))*npjd.nominalhppa,0) as proyeksi_rp,
                SUM(COALESCE(batch.rekappenjualanperitemmonthly.qty,0)) as achievement_qty,
                SUM(COALESCE(batch.rekappenjualanperitemmonthly.subtotalnettojual,0)) as achievement_rp,
                ROUND(COALESCE((SUM(batch.rekappenjualanperitemmonthly.qty)/batch.targetstockparetogoogledocsdk.planqty)*100,0),2) as achievement_qty_persen,
                ROUND(COALESCE((SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual)/batch.targetstockparetogoogledocsdk.plansales)*100,0),2) as achievement_rp_persen,
                $pertanggal
            FROM mstr.stock
            JOIN mstr.groupsubpartsstock ON mstr.stock.id = mstr.groupsubpartsstock.stockid
            JOIN mstr.groupsubparts ON (mstr.groupsubparts.id = mstr.groupsubpartsstock.groupsubpartsid AND mstr.groupsubparts.createdby = 'MANUAL_AND_ERV')
            JOIN mstr.groupparts ON mstr.groupparts.id = mstr.groupsubparts.grouppartsid
            JOIN mstr.kategoripenjualan ON mstr.kategoripenjualan.id = mstr.stock.kategoripenjualan
            LEFT JOIN (
                SELECT DISTINCT ON (stockid) stockid, stokgudang, hppa, tanggal FROM stk.rekapstockdaily
                WHERE recordownerid = $subcabang AND EXTRACT(YEAR FROM tanggal) = '$tahun' AND EXTRACT(MONTH FROM tanggal) = '$bulan'
                ORDER BY stockid ASC, tanggal DESC
            ) AS rekapstockdaily ON rekapstockdaily.stockid = mstr.stock.id
            LEFT JOIN batch.rekappenjualanperitemmonthly ON (mstr.stock.id = batch.rekappenjualanperitemmonthly.stockid AND batch.rekappenjualanperitemmonthly.periode = '$tahun$bulan' by_karyawanidsalesman)
            LEFT JOIN batch.targetstockparetogoogledocsdk ON (mstr.stock.id = batch.targetstockparetogoogledocsdk.stockid AND batch.targetstockparetogoogledocsdk.periode = '$tahun$bulan')
            LEFT JOIN (
                SELECT DISTINCT ON (\"id\",\"stockid\")
                    pj.notapenjualandetail.id, pj.notapenjualandetail.stockid, pj.notapenjualandetail.qtynota, pj.notapenjualan.tglproforma,
                    acc.historyhppa.nominalhppa, acc.historyhppa.tglaktif, (pj.notapenjualandetail.qtynota*acc.historyhppa.nominalhppa) AS total_git
                FROM pj.notapenjualandetail
                JOIN pj.notapenjualan ON pj.notapenjualan.recordownerid = $subcabang AND pj.notapenjualan.tglnota IS NULL AND pj.notapenjualan.id = pj.notapenjualandetail.notapenjualanid
                LEFT JOIN acc.historyhppa ON acc.historyhppa.recordownerid = $subcabang AND pj.notapenjualandetail.stockid = acc.historyhppa.stockid AND acc.historyhppa.tglaktif <= pj.notapenjualan.tglproforma
                ORDER BY pj.notapenjualandetail.id, pj.notapenjualandetail.stockid, acc.historyhppa.tglaktif DESC
            ) AS npjd ON (npjd.stockid = mstr.stock.id)
            WHERE (mstr.stock.statusaktif = TRUE AND (LEFT(mstr.stock.kodebarang,2) IN ('FB') OR LEFT(mstr.stock.kodebarang,2) IN ('FE'))) $where
            GROUP BY kodebarang, namabarang, groupname, pn, kategori, rekapstockdaily.stokgudang, rekapstockdaily.hppa, npjd.nominalhppa, planqty, plansales
            ORDER BY kodebarang
        ";

        // Jika by salesman
        if($salesman != null) {
            $query = str_replace("by_karyawanidsalesman", "AND batch.rekappenjualanperitemmonthly.karyawanidsalesman = ".$salesman." ", $query);
        }else{
            $query = str_replace("by_karyawanidsalesman", "", $query);
        }

        // Jika butuh paginasi
        if($wtotal){
            $total  = DB::select(DB::raw("SELECT COUNT(kodebarang) as total FROM ($query) temptable"));
            $result = DB::select(DB::raw($query." LIMIT ".$limit." OFFSET ".$offset));

            return ['total'=>$total[0]->total,'result'=>$result];
        }else{
            $result = DB::select(DB::raw($query));
            return $result;
       }
    }

    public function performanceSalesman(Request $req)
    {
        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE SALESMAN');
        $subcabang = $req->session()->get('subcabang');
        $type      = $req->type;
        $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        // Get Sales list
        $sales = DB::select(DB::raw("
            SELECT DISTINCT(karyawanidsalesman) karyawanidsalesman, kodesales, namakaryawan
            FROM batch.rekappenjualanperitemmonthly
            INNER JOIN hr.karyawan ON hr.karyawan.id = batch.rekappenjualanperitemmonthly.karyawanidsalesman
            WHERE periode = '$tahun$bulan' AND kodesales != ''
            ORDER BY kodesales
        "));

        return view('laporan.penjualan.performancessalesman_preview',['sales'=>$sales,'type'=>$type,'tahun'=>$tahun,'bulan'=>$bulan]);
    }

    public function performanceSalesmanData(Request $req)
    {
        $subcabang  = $req->session()->get('subcabang');
        $type       = $req->type;
        $tahun      = $req->tahun;
        $bulan      = $req->bulan;
        $idsalesman = $req->idsalesman;
        $limit  = $req->length;
        $offset = $req->start;

        if($req->idsalesman) {
            // need moar execution time
            ini_set('max_execution_time',300);

            // Result
            $data  = $this->performanceProductQuery($subcabang,$type,$tahun,$bulan,$limit,$offset,$idsalesman,true);

            return response()->json([
              'draw'            => $req->draw,
              'recordsTotal'    => $data['total'],
              'recordsFiltered' => $data['total'],
              'data'            => $data['result'],
            ]);
        }else{
            return response()->json([
              'draw'            => $req->draw,
              'recordsTotal'    => 0,
              'recordsFiltered' => 0,
              'data'            => [],
            ]);
        }
    }

    // public function performanceProduct(Request $req)
    // {
    //     $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE PRODUCT');
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

    //     // Where
    //     if($type == 2) {
    //         $where = "AND mstr.groupparts.groupname ILIKE '%PARETO%'";
    //     }elseif($type == 3) {
    //         $where = "AND mstr.groupparts.groupname NOT ILIKE '%PARETO%' AND mstr.groupparts.createdby = 'MANUAL_AND_ERV'";
    //     }elseif($type == 4) {
    //         $where = "AND mstr.groupparts.groupname like '%STOCK LAMA 201512%'";
    //     }elseif($type == 5) {
    //         $where = "AND mstr.groupparts.groupname like '%BURETO HAN MEI 2017%'";
    //     }else{
    //         $where = "";
    //     }

    //     // Query
    //     $query = "
    //         SELECT
    //             mstr.stock.kodebarang,
    //             mstr.stock.namabarang,
    //             mstr.groupparts.groupname,
    //             (CASE WHEN (mstr.groupparts.groupname ILIKE '%PARETO%') THEN 'P' ELSE 'N' END) AS pn,
    //             mstr.kategoripenjualan.kategori,
    //             COALESCE(batch.targetstockparetogoogledocsdk.planqty,0) as target_qty,
    //             COALESCE(batch.targetstockparetogoogledocsdk.plansales,0) as target_rp,
    //             COALESCE(rekapstockdaily.stokgudang,0) as stokgudang_qty,
    //             COALESCE(rekapstockdaily.stokgudang*rekapstockdaily.hppa,0) as stokgudang_rp,
    //             SUM(COALESCE(npjd.qtynota,0)) as git_qty,
    //             SUM(COALESCE(npjd.total_git,0)) as git_rp,
    //             COALESCE((rekapstockdaily.stokgudang+SUM(npjd.qtynota))-(SUM(batch.rekappenjualanperitemmonthly.qty)),0) as proyeksi_qty,
    //             COALESCE(((rekapstockdaily.stokgudang+SUM(npjd.qtynota))-(SUM(batch.rekappenjualanperitemmonthly.qty)))*npjd.nominalhppa,0) as proyeksi_rp,
    //             SUM(COALESCE(batch.rekappenjualanperitemmonthly.qty,0)) as achievement_qty,
    //             SUM(COALESCE(batch.rekappenjualanperitemmonthly.subtotalnettojual,0)) as achievement_rp,
    //             ROUND(COALESCE((SUM(batch.rekappenjualanperitemmonthly.qty)/batch.targetstockparetogoogledocsdk.planqty)*100,0),2) as achievement_qty_persen,
    //             ROUND(COALESCE((SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual)/batch.targetstockparetogoogledocsdk.plansales)*100,0),2) as achievement_rp_persen,
    //             -- (CASE WHEN SUM(batch.rekappenjualanperitemmonthly.qty) IS NOT NULL AND batch.targetstockparetogoogledocsdk.planqty IS NOT NULL
    //             --     THEN (SUM(batch.rekappenjualanperitemmonthly.qty)/batch.targetstockparetogoogledocsdk.planqty)*100
    //             --     ELSE 0 END
    //             -- ) as achievement_qty_persen,
    //             -- (CASE WHEN SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual) IS NOT NULL AND batch.targetstockparetogoogledocsdk.plansales IS NOT NULL
    //             --     THEN (SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual)/batch.targetstockparetogoogledocsdk.plansales)*100
    //             --     ELSE 0 END
    //             -- ) as achievement_rp_persen,
    //             $pertanggal
    //         FROM mstr.stock
    //         JOIN mstr.groupsubpartsstock ON mstr.stock.id = mstr.groupsubpartsstock.stockid
    //         JOIN mstr.groupsubparts ON (mstr.groupsubparts.id = mstr.groupsubpartsstock.groupsubpartsid AND mstr.groupsubparts.createdby = 'MANUAL_AND_ERV')
    //         JOIN mstr.groupparts ON mstr.groupparts.id = mstr.groupsubparts.grouppartsid
    //         JOIN mstr.kategoripenjualan ON mstr.kategoripenjualan.id = mstr.stock.kategoripenjualan
    //         LEFT JOIN (
    //             SELECT DISTINCT ON (stockid) stockid, stokgudang, hppa, tanggal FROM stk.rekapstockdaily
    //             WHERE recordownerid = $subcabang AND EXTRACT(YEAR FROM tanggal) = '$tahun' AND EXTRACT(MONTH FROM tanggal) = '$bulan'
    //             ORDER BY stockid ASC, tanggal DESC
    //         ) AS rekapstockdaily ON rekapstockdaily.stockid = mstr.stock.id
    //         LEFT JOIN batch.rekappenjualanperitemmonthly ON (mstr.stock.id = batch.rekappenjualanperitemmonthly.stockid AND batch.rekappenjualanperitemmonthly.periode = '$tahun$bulan')
    //         LEFT JOIN batch.targetstockparetogoogledocsdk ON (mstr.stock.id = batch.targetstockparetogoogledocsdk.stockid AND batch.targetstockparetogoogledocsdk.periode = '$tahun$bulan')
    //         LEFT JOIN (
    //             SELECT DISTINCT ON (\"id\",\"stockid\")
    //                 pj.notapenjualandetail.id, pj.notapenjualandetail.stockid, pj.notapenjualandetail.qtynota, pj.notapenjualan.tglproforma,
    //                 acc.historyhppa.nominalhppa, acc.historyhppa.tglaktif, (pj.notapenjualandetail.qtynota*acc.historyhppa.nominalhppa) AS total_git
    //             FROM pj.notapenjualandetail
    //             JOIN pj.notapenjualan ON pj.notapenjualan.recordownerid = $subcabang AND pj.notapenjualan.tglnota IS NULL AND pj.notapenjualan.id = pj.notapenjualandetail.notapenjualanid
    //             LEFT JOIN acc.historyhppa ON acc.historyhppa.recordownerid = $subcabang AND pj.notapenjualandetail.stockid = acc.historyhppa.stockid AND acc.historyhppa.tglaktif <= pj.notapenjualan.tglproforma
    //             ORDER BY pj.notapenjualandetail.id, pj.notapenjualandetail.stockid, acc.historyhppa.tglaktif DESC
    //         ) AS npjd ON (npjd.stockid = mstr.stock.id)
    //         WHERE (mstr.stock.statusaktif = TRUE AND (LEFT(mstr.stock.kodebarang,2) IN ('FB') OR LEFT(mstr.stock.kodebarang,2) IN ('FE'))) $where
    //         GROUP BY kodebarang, namabarang, groupname, pn, kategori, rekapstockdaily.stokgudang, rekapstockdaily.hppa, npjd.nominalhppa, planqty, plansales
    //         ORDER BY kodebarang
    //     ";

    //     // need moar execution time
    //     ini_set('max_execution_time',300);

    //     // Result
    //     $data  = DB::select(DB::raw($query));
    //     $count = count($data);

    //     // Xlsx Var
    //     $sheet  = str_slug("Laporan Perfomance Product");
    //     $file   = $sheet."-".uniqid().".xlsx";
    //     $folder = storage_path('excel/exports');
    //     $subcabang = SubCabang::find(session('subcabang'))->kodesubcabang;
    //     $username  = strtoupper(auth()->user()->username);
    //     $tanggal   = Carbon::now()->format('d/m/Y');

    //     $header_data   = [
    //         ["KODE BARANG","MAIN GROUP","NAMA BARANG","P / N","PRODUCT MIX","TARGET JUAL","","STOCK GUDANG","","STOCK MASUK GIT","","PROYEKSI STOCK","","ACHIEVEMENT QTY","","ACHIEVEMENT RP",""],
    //         ["","","","","","QTY","RP","QTY","RP","QTY","RP","QTY","RP","QTY","%","RP","%",],
    //     ];
    //     for($x=1;$x<=31;$x++) {
    //         $header_data[0][] = $x;
    //         $header_data[1][] = "";
    //     }
    //     $data_format = [
    //         'GENERAL','GENERAL','GENERAL','GENERAL','GENERAL',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','0.00%','#,##0','0.00%',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
    //         '#,##0',
    //     ];

    //     $col_width = [
    //         17,20,90,8,15,10,18,10,18,10,
    //         18,10,18,10,10,18,10,
    //         18,18,18,18,18,18,18,18,18,18,
    //         18,18,18,18,18,18,18,18,18,18,
    //         18,18,18,18,18,18,18,18,18,18,
    //         18,
    //     ];

    //     // Init Xlsx
    //     $writer = new XLSXWriter();
    //     $writer->setAuthor($username);
    //     $writer->setTempDir($folder);

    //     // Write Sheet Header
    //     $writer->writeSheetHeader($sheet,$this->header_format,['suppress_row'=>true,'widths'=>$col_width]);
    //     $writer->writeSheetRow($sheet,["MONITORING PRODUCT ($type_desc)"],['font-style'=>'bold']);
    //     $writer->writeSheetRow($sheet,["Tanggal Update : $tanggal"],['font-style'=>'bold']);
    //     $writer->writeSheetRow($sheet,["Cabang : $subcabang"],['font-style'=>'bold']);
    //     $writer->writeSheetRow($sheet,['']);

    //     // Write Table Header
    //     // Merge
    //     $writer->markMergedCell($sheet,4,0,5,0);
    //     $writer->markMergedCell($sheet,4,1,5,1);
    //     $writer->markMergedCell($sheet,4,2,5,2);
    //     $writer->markMergedCell($sheet,4,3,5,3);
    //     $writer->markMergedCell($sheet,4,4,5,4);
    //     $writer->markMergedCell($sheet,4,5,4,6);
    //     $writer->markMergedCell($sheet,4,7,4,8);
    //     $writer->markMergedCell($sheet,4,9,4,10);
    //     $writer->markMergedCell($sheet,4,11,4,12);
    //     $writer->markMergedCell($sheet,4,13,4,14);
    //     $writer->markMergedCell($sheet,4,15,4,16);
    //     for($k=17; $k<48; $k++) {
    //         $writer->markMergedCell($sheet,4,$k,5,$k);
    //     }
    //     foreach ($header_data as $hdata) {
    //         $writer->writeSheetRow($sheet,$hdata,$this->header_style);
    //     }

    //     // Write Table Data
    //     foreach ($data as $row) {
    //         $value = [
    //             $row->kodebarang, $row->groupname, $row->namabarang, $row->pn, $row->kategori,$row->target_qty,$row->target_rp,$row->stokgudang_qty,$row->stokgudang_rp,
    //             $row->git_qty,$row->git_rp,$row->proyeksi_qty,$row->proyeksi_rp,$row->achievement_qty,$row->achievement_qty_persen,$row->achievement_rp,$row->achievement_rp_persen,
    //             $row->total_1,$row->total_2,$row->total_3,$row->total_4,$row->total_5,$row->total_6,$row->total_7,$row->total_8,$row->total_9,$row->total_10,
    //             $row->total_11,$row->total_12,$row->total_13,$row->total_14,$row->total_15,$row->total_16,$row->total_17,$row->total_18,$row->total_19,$row->total_20,
    //             $row->total_21,$row->total_22,$row->total_23,$row->total_24,$row->total_25,$row->total_26,$row->total_27,$row->total_28,$row->total_29,$row->total_30,
    //             $row->total_31,
    //         ];

    //         $writer->writeSheetRow($sheet,$value,$this->data_style,$data_format);
    //     }
    //     // Total
    //     $last = $count+6;
    //     $total = [
    //         'TOTAL', '', '', '', '',
    //         '=SUM(F7:F'.$last.')','=SUM(G7:G'.$last.')','=SUM(H7:H'.$last.')','=SUM(I7:I'.$last.')','=SUM(J7:J'.$last.')','=SUM(K7:K'.$last.')','=SUM(L7:L'.$last.')',
    //         '=SUM(M7:M'.$last.')','=SUM(N7:N'.$last.')','=SUM(O7:O'.$last.')','=SUM(P7:P'.$last.')','=SUM(Q7:Q'.$last.')','=SUM(R7:R'.$last.')','=SUM(S7:S'.$last.')',
    //         '=SUM(T7:T'.$last.')','=SUM(U7:U'.$last.')','=SUM(V7:V'.$last.')','=SUM(W7:W'.$last.')','=SUM(X7:X'.$last.')','=SUM(Y7:Y'.$last.')','=SUM(Z7:Z'.$last.')',
    //         '=SUM(AA7:AA'.$last.')','=SUM(AB7:AB'.$last.')','=SUM(AC7:AC'.$last.')','=SUM(AD7:AD'.$last.')','=SUM(AE7:AE'.$last.')','=SUM(AF7:AF'.$last.')','=SUM(AG7:AG'.$last.')',
    //         '=SUM(AH7:AH'.$last.')','=SUM(AI7:AI'.$last.')','=SUM(AJ7:AJ'.$last.')','=SUM(AK7:AK'.$last.')','=SUM(AL7:AL'.$last.')','=SUM(AM7:AM'.$last.')','=SUM(AN7:AN'.$last.')',
    //         '=SUM(AO7:AO'.$last.')','=SUM(AP7:AP'.$last.')','=SUM(AQ7:AQ'.$last.')','=SUM(AR7:AR'.$last.')','=SUM(AS7:AS'.$last.')','=SUM(AT7:AT'.$last.')','=SUM(AU7:AU'.$last.')','=SUM(AV7:AV'.$last.')',
    //     ];
    //     $writer->markMergedCell($sheet,$last,0,$last,4);
    //     $writer->writeSheetRow($sheet,$total,['font-style'=>'bold', 'border'=>'left,right,top,bottom',],$data_format);

    //     // Footer
    //     $writer->writeSheetRow($sheet,['']);
    //     $writer->writeSheetRow($sheet,['']);
    //     $writer->writeSheetRow($sheet,[$username.",".(Carbon::now()->format('d/m/Y h:i:s'))],['font-style'=>'italic']);
    //     $writer->writeToFile($folder.'/'.$file);

    //     $urlExcel = asset('storage/excel/exports/'.$file);

    //     $data_json = json_encode($data);
    //     return view('laporan.penjualan.performanceproducts',['data'=>$data_json,'urlExcel'=>$urlExcel]);

    //     // return view('laporan.penjualan.performanceproduct',['data'=>$data,'urlExcel'=>$urlExcel]);
    // }

    // public function performanceproductpersales(Request $req)
    // {
    //     $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE PRODUCT');
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

    //     // Where
    //     if($type == 2) {
    //         $where = "AND mstr.groupparts.groupname ILIKE '%PARETO%'";
    //     }elseif($type == 3) {
    //         $where = "AND mstr.groupparts.groupname NOT ILIKE '%PARETO%' AND mstr.groupparts.createdby = 'MANUAL_AND_ERV'";
    //     }elseif($type == 4) {
    //         $where = "AND mstr.groupparts.groupname like '%STOCK LAMA 201512%'";
    //     }elseif($type == 5) {
    //         $where = "AND mstr.groupparts.groupname like '%BURETO HAN MEI 2017%'";
    //     }else{
    //         $where = "";
    //     }

    //     // Query
    //     $query = "
    //         SELECT
    //             mstr.stock.kodebarang,
    //             mstr.stock.namabarang,
    //             mstr.groupparts.groupname,
    //             (CASE WHEN (mstr.groupparts.groupname ILIKE '%PARETO%') THEN 'P' ELSE 'N' END) AS pn,
    //             mstr.kategoripenjualan.kategori,
    //             COALESCE(batch.targetstockparetogoogledocsdk.planqty,0) as target_qty,
    //             COALESCE(batch.targetstockparetogoogledocsdk.plansales,0) as target_rp,
    //             COALESCE(rekapstockdaily.stokgudang,0) as stokgudang_qty,
    //             COALESCE(rekapstockdaily.stokgudang*rekapstockdaily.hppa,0) as stokgudang_rp,
    //             SUM(COALESCE(npjd.qtynota,0)) as git_qty,
    //             SUM(COALESCE(npjd.total_git,0)) as git_rp,
    //             COALESCE((rekapstockdaily.stokgudang+SUM(npjd.qtynota))-(SUM(batch.rekappenjualanperitemmonthly.qty)),0) as proyeksi_qty,
    //             COALESCE(((rekapstockdaily.stokgudang+SUM(npjd.qtynota))-(SUM(batch.rekappenjualanperitemmonthly.qty)))*npjd.nominalhppa,0) as proyeksi_rp,
    //             SUM(COALESCE(batch.rekappenjualanperitemmonthly.qty,0)) as achievement_qty,
    //             SUM(COALESCE(batch.rekappenjualanperitemmonthly.subtotalnettojual,0)) as achievement_rp,
    //             ROUND(COALESCE((SUM(batch.rekappenjualanperitemmonthly.qty)/batch.targetstockparetogoogledocsdk.planqty)*100,0),2) as achievement_qty_persen,
    //             ROUND(COALESCE((SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual)/batch.targetstockparetogoogledocsdk.plansales)*100,0),2) as achievement_rp_persen,
    //             $pertanggal
    //         FROM mstr.stock
    //         JOIN mstr.groupsubpartsstock ON mstr.stock.id = mstr.groupsubpartsstock.stockid
    //         JOIN mstr.groupsubparts ON (mstr.groupsubparts.id = mstr.groupsubpartsstock.groupsubpartsid AND mstr.groupsubparts.createdby = 'MANUAL_AND_ERV')
    //         JOIN mstr.groupparts ON mstr.groupparts.id = mstr.groupsubparts.grouppartsid
    //         JOIN mstr.kategoripenjualan ON mstr.kategoripenjualan.id = mstr.stock.kategoripenjualan
    //         LEFT JOIN (
    //             SELECT DISTINCT ON (stockid) stockid, stokgudang, hppa, tanggal FROM stk.rekapstockdaily
    //             WHERE recordownerid = $subcabang AND EXTRACT(YEAR FROM tanggal) = '$tahun' AND EXTRACT(MONTH FROM tanggal) = '$bulan'
    //             ORDER BY stockid ASC, tanggal DESC
    //         ) AS rekapstockdaily ON rekapstockdaily.stockid = mstr.stock.id
    //         LEFT JOIN batch.rekappenjualanperitemmonthly ON (mstr.stock.id = batch.rekappenjualanperitemmonthly.stockid AND batch.rekappenjualanperitemmonthly.periode = '$tahun$bulan' AND batch.rekappenjualanperitemmonthly.karyawanidsalesman = karyawanidsalesman_x )
    //         LEFT JOIN batch.targetstockparetogoogledocsdk ON (mstr.stock.id = batch.targetstockparetogoogledocsdk.stockid AND batch.targetstockparetogoogledocsdk.periode = '$tahun$bulan')
    //         LEFT JOIN (
    //             SELECT DISTINCT ON (\"id\",\"stockid\")
    //                 pj.notapenjualandetail.id, pj.notapenjualandetail.stockid, pj.notapenjualandetail.qtynota, pj.notapenjualan.tglproforma,
    //                 acc.historyhppa.nominalhppa, acc.historyhppa.tglaktif, (pj.notapenjualandetail.qtynota*acc.historyhppa.nominalhppa) AS total_git
    //             FROM pj.notapenjualandetail
    //             JOIN pj.notapenjualan ON pj.notapenjualan.recordownerid = $subcabang AND pj.notapenjualan.tglnota IS NULL AND pj.notapenjualan.id = pj.notapenjualandetail.notapenjualanid
    //             LEFT JOIN acc.historyhppa ON acc.historyhppa.recordownerid = $subcabang AND pj.notapenjualandetail.stockid = acc.historyhppa.stockid AND acc.historyhppa.tglaktif <= pj.notapenjualan.tglproforma
    //             ORDER BY pj.notapenjualandetail.id, pj.notapenjualandetail.stockid, acc.historyhppa.tglaktif DESC
    //         ) AS npjd ON (npjd.stockid = mstr.stock.id)
    //         WHERE (mstr.stock.statusaktif = TRUE AND (LEFT(mstr.stock.kodebarang,2) IN ('FB') OR LEFT(mstr.stock.kodebarang,2) IN ('FE'))) $where
    //         GROUP BY kodebarang, namabarang, groupname, pn, kategori, rekapstockdaily.stokgudang, rekapstockdaily.hppa, npjd.nominalhppa, planqty, plansales
    //         ORDER BY kodebarang
    //     ";



    //     // Get Sales list
    //     $sales = DB::select(DB::raw("
    //         SELECT DISTINCT(karyawanidsalesman) karyawanidsalesman, kodesales, namakaryawan
    //         FROM batch.rekappenjualanperitemmonthly
    //         INNER JOIN hr.karyawan ON hr.karyawan.id = batch.rekappenjualanperitemmonthly.karyawanidsalesman
    //         WHERE periode = '$tahun$bulan' AND kodesales != ''
    //         ORDER BY kodesales
    //     "));

    //     // need moar execution time
    //     ini_set('max_execution_time',300);
    //     foreach ($sales as $sal) {
    //         // Result
    //         $data[$sal->karyawanidsalesman]  = DB::select(DB::raw(str_replace("karyawanidsalesman_x", $sal->karyawanidsalesman, $query)));
    //         $count[$sal->karyawanidsalesman] = count($data[$sal->karyawanidsalesman]);
    //     }

    //     // Xlsx Var
    //     $sheet  = str_slug("Laporan Perfomance Product Per Sales");
    //     $file   = $sheet."-".uniqid().".xlsx";
    //     $folder = storage_path('excel/exports');
    //     $subcabang = SubCabang::find(session('subcabang'))->kodesubcabang;
    //     $username  = strtoupper(auth()->user()->username);
    //     $tanggal   = Carbon::now()->format('d/m/Y');
    //     $time      = Carbon::now()->format('d/m/Y h:i:s');

    //     $header_data   = [
    //         ["KODE BARANG","MAIN GROUP","NAMA BARANG","P / N","PRODUCT MIX","TARGET JUAL","","STOCK GUDANG","","STOCK MASUK GIT","","PROYEKSI STOCK","","ACHIEVEMENT QTY","","ACHIEVEMENT RP",""],
    //         ["","","","","","QTY","RP","QTY","RP","QTY","RP","QTY","RP","QTY","%","RP","%",],
    //     ];
    //     for($x=1;$x<=31;$x++) {
    //         $header_data[0][] = $x;
    //         $header_data[1][] = "";
    //     }
    //     $data_format = [
    //         'GENERAL','GENERAL','GENERAL','GENERAL','GENERAL',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','0.00%','#,##0','0.00%',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
    //         '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
    //         '#,##0',
    //     ];

    //     $col_width = [
    //         25,30,120,11,30,25,25,25,25,25,
    //         25,25,25,25,25,25,25,
    //         25,25,25,25,25,25,25,25,25,25,
    //         25,25,25,25,25,25,25,25,25,25,
    //         25,25,25,25,25,25,25,25,25,25,
    //         25,
    //     ];

    //     // Init Xlsx
    //     $writer = new XLSXWriter();
    //     $writer->setAuthor($username);
    //     $writer->setTempDir($folder);

    //     // Loop for multiple Sheet
    //     foreach ($sales as $sal) {
    //         // Write Sheet Header
    //         $writer->writeSheetHeader($sal->kodesales,$this->header_format,['suppress_row'=>true,'widths'=>$col_width]);
    //         $writer->writeSheetRow($sal->kodesales,["MONITORING PRODUCT ($type_desc)"],['font-style'=>'bold']);
    //         $writer->writeSheetRow($sal->kodesales,["Tanggal Update : $tanggal"],['font-style'=>'bold']);
    //         $writer->writeSheetRow($sal->kodesales,["Cabang : $subcabang"],['font-style'=>'bold']);
    //         $writer->writeSheetRow($sal->kodesales,["Kode Sales : ".$sal->kodesales],['font-style'=>'bold']);
    //         $writer->writeSheetRow($sal->kodesales,["Nama Sales : ".$sal->namakaryawan],['font-style'=>'bold']);
    //         $writer->writeSheetRow($sal->kodesales,['']);

    //         // Write Table Header
    //         // Merge
    //         $writer->markMergedCell($sal->kodesales,6,0,7,0);
    //         $writer->markMergedCell($sal->kodesales,6,1,7,1);
    //         $writer->markMergedCell($sal->kodesales,6,2,7,2);
    //         $writer->markMergedCell($sal->kodesales,6,3,7,3);
    //         $writer->markMergedCell($sal->kodesales,6,4,7,4);
    //         $writer->markMergedCell($sal->kodesales,6,5,6,6);
    //         $writer->markMergedCell($sal->kodesales,6,7,6,8);
    //         $writer->markMergedCell($sal->kodesales,6,9,6,10);
    //         $writer->markMergedCell($sal->kodesales,6,11,6,12);
    //         $writer->markMergedCell($sal->kodesales,6,13,6,14);
    //         $writer->markMergedCell($sal->kodesales,6,15,6,16);
    //         for($k=17; $k<48; $k++) {
    //             $writer->markMergedCell($sal->kodesales,6,$k,7,$k);
    //         }
    //         // Write
    //         foreach ($header_data as $hdata) {
    //             $writer->writeSheetRow($sal->kodesales,$hdata,$this->header_style);
    //         }

    //         // Write Table Data
    //         foreach ($data[$sal->karyawanidsalesman] as $row) {
    //             $value = [
    //                 $row->kodebarang, $row->groupname, $row->namabarang, $row->pn, $row->kategori,$row->target_qty,$row->target_rp,$row->stokgudang_qty,$row->stokgudang_rp,
    //                 $row->git_qty,$row->git_rp,$row->proyeksi_qty,$row->proyeksi_rp,$row->achievement_qty,$row->achievement_qty_persen,$row->achievement_rp,
    //                 // 0,
    //                 $row->achievement_rp_persen,
    //                 $row->total_1,$row->total_2,$row->total_3,$row->total_4,$row->total_5,$row->total_6,$row->total_7,$row->total_8,$row->total_9,$row->total_10,
    //                 $row->total_11,$row->total_12,$row->total_13,$row->total_14,$row->total_15,$row->total_16,$row->total_17,$row->total_18,$row->total_19,$row->total_20,
    //                 $row->total_21,$row->total_22,$row->total_23,$row->total_24,$row->total_25,$row->total_26,$row->total_27,$row->total_28,$row->total_29,$row->total_30,
    //                 $row->total_31,
    //             ];

    //             $writer->writeSheetRow($sal->kodesales,$value,$this->data_style,$data_format);
    //         }
            
    //         // Total
    //         $last  = $count[$sal->karyawanidsalesman]+8;
    //         $total = [
    //             'TOTAL', '', '', '', '',
    //             '=SUM(F9:F'.$last.')','=SUM(G9:G'.$last.')','=SUM(H9:H'.$last.')','=SUM(I9:I'.$last.')','=SUM(J9:J'.$last.')','=SUM(K9:K'.$last.')','=SUM(L9:L'.$last.')',
    //             '=SUM(M9:M'.$last.')','=SUM(N9:N'.$last.')','=SUM(O9:O'.$last.')','=SUM(P9:P'.$last.')','=SUM(Q9:Q'.$last.')','=SUM(R9:R'.$last.')','=SUM(S9:S'.$last.')',
    //             '=SUM(T9:T'.$last.')','=SUM(U9:U'.$last.')','=SUM(V9:V'.$last.')','=SUM(W9:W'.$last.')','=SUM(X9:X'.$last.')','=SUM(Y9:Y'.$last.')','=SUM(Z9:Z'.$last.')',
    //             '=SUM(AA9:AA'.$last.')','=SUM(AB9:AB'.$last.')','=SUM(AC9:AC'.$last.')','=SUM(AD9:AD'.$last.')','=SUM(AE9:AE'.$last.')','=SUM(AF9:AF'.$last.')','=SUM(AG9:AG'.$last.')',
    //             '=SUM(AH9:AH'.$last.')','=SUM(AI9:AI'.$last.')','=SUM(AJ9:AJ'.$last.')','=SUM(AK9:AK'.$last.')','=SUM(AL9:AL'.$last.')','=SUM(AM9:AM'.$last.')','=SUM(AN9:AN'.$last.')',
    //             '=SUM(AO9:AO'.$last.')','=SUM(AP9:AP'.$last.')','=SUM(AQ9:AQ'.$last.')','=SUM(AR9:AR'.$last.')','=SUM(AS9:AS'.$last.')','=SUM(AT9:AT'.$last.')','=SUM(AU9:AU'.$last.')','=SUM(AV9:AV'.$last.')',
    //         ];
    //         $writer->writeSheetRow($sal->kodesales,$total,['font-style'=>'bold', 'border'=>'left,right,top,bottom',],$data_format);

    //         // Footer
    //         $writer->writeSheetRow($sal->kodesales,['']);
    //         $writer->writeSheetRow($sal->kodesales,['']);
    //         $writer->writeSheetRow($sal->kodesales,[$username.",".$time],['font-style'=>'italic']);
    //         $data[$sal->karyawanidsalesman] = json_encode($data[$sal->karyawanidsalesman]);
    //     }

    //     // Write to file
    //     $writer->writeToFile($folder.'/'.$file);
    //     $urlExcel = asset('storage/excel/exports/'.$file);

    //     return view('laporan.penjualan.performanceproductspersales',['data'=>$data,'sales'=>$sales,'urlExcel'=>$urlExcel]);
    // }


}