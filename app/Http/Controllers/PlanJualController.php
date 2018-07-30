<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Models\AppSetting;
use App\Models\PlanJual;
use App\Models\TargetSalesCabangMonthly;
use App\Models\Karyawan;
use App\Models\RekapPenjualanPerItemMonthly;
use App\Models\Toko;
use App\Models\Stock;
use App\Models\HistoryBMK1;
use App\Models\ReportLog;
use App\Models\SubCabang;
use DB;
use EXCEL;
use PDF;

class PlanJualController extends Controller
{
    //
	public function index(){
        return view('transaksi.planjual.index');
    }

    public function getSalesmanActive(Request $Request){
        // gunakan permission dari indexnya aja
        if(!$Request->user()->can('planjual.index')) {
            return response()->json([
                'draw'            => $Request->draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
            ]);
        }

        $search = $Request->search;
        $data = Karyawan::select('id', 'kodesales', 'namakaryawan')
            ->where(function ($q) use($search){
                $q->where('namakaryawan', 'ilike', '%'.$search.'%');
                $q->orWhere('kodesales', 'ilike', '%'.$search.'%');
            })
            ->where('recordownerid',$Request->session()->get('subcabang'))
            ->whereNull('tglkeluar')
            ->whereNotNull('kodesales')
            ->orderBy('kodesales','asc')
            ->get();
    
        return json_encode($data);
    }
    /*
    public function getNettoPenjualan(Request $Request){
        // gunakan permission dari indexnya aja
        if(!$Request->user()->can('planjual.index')) {
            return response()->json([
                'draw'            => $Request->draw,
                'recordsTotal'    => 0,
                'data'            => [],
            ]);
        }

        // jika lolos, tampilkan data
        $Request->session()->put('tglmulai', $Request->tglmulai);
        $Request->session()->put('tglselesai', $Request->tglselesai);

        $recordownerid = $Request->session()->get('subcabang');
        $karyawanidsalesman = $Request->karyawanidsalesman;
        $periode = $Request->periode.'01';
        
        $lastPeriod = Carbon::parse($periode)->subDay();
        $firstPeriod = Carbon::parse($periode)->subMonths(7);
        

        $periodBegin = $firstPeriod->year.str_pad($firstPeriod->month, 2, "0", STR_PAD_LEFT);
        $periodEnd = $lastPeriod->year.str_pad($lastPeriod->month, 2, "0", STR_PAD_LEFT);

        
        $columns = array(
            0 => 'mstr.toko.namatoko',
            1 => 'mstr.stock.namabarang',
            2 => 'batch.rekappenjualanperitemmonthly.subtotalnettojual',
            3 => 'batch.rekappenjualanperitemmonthly.hrgsatuannetto',
            4 => 'batch.rekappenjualanperitemmonthly.qty'
        );

        $batch = RekapPenjualanPerItemMonthly::selectRaw(collect($columns)->implode(', '))
            ->leftJoin('mstr.toko','batch.rekappenjualanperitemmonthly.tokoid','=','mstr.toko.id')
            ->leftJoin('mstr.stock','batch.rekappenjualanperitemmonthly.stockid','=','mstr.stock.id')
            ->where('batch.rekappenjualanperitemmonthly.karyawanidsalesman', $karyawanidsalesman)
            ->where('batch.rekappenjualanperitemmonthly.recordownerid', $recordownerid)
            ->where('mstr.stock.statusaktif',true)
            ->whereBetween('batch.rekappenjualanperitemmonthly.periode', [$periodBegin, $periodEnd])
            ->orderBy('mstr.stock.namabarang');

        $data = $batch->get();

        /*
        return response()->json([
            'draw'              => $Request->draw,
            'recordsTotal'      => $total_data,
            'recordsFiltered'   => $filtered_data,
            'data'              => json_encode($data),
        ]);
        */
        /*
        return response()->json([
            'draw'            => $Request->draw,
            'recordsTotal'    => count($data),
            'data'            => json_encode($data),
        ]);
    }
    */


    public function getPlanSales(Request $Request){
         // gunakan permission dari indexnya aja
        if(!$Request->user()->can('planjual.index')) {
            return response()->json([
                'draw'            => $Request->draw,
                'recordsTotal'    => 0,
                'totalNominal'    => 0,
                'totalOmset'      => 0,
                'selisih'         => 0,
                'data'            => [],
            ]);
        }

        ini_set("max_execution_time", (60 * 60 * 3)); // 3 hours

        // jika lolos, tampilkan data
        $Request->session()->put('tglmulai', $Request->tglmulai);
        $Request->session()->put('tglselesai', $Request->tglselesai);

        $recordownerid = $Request->session()->get('subcabang');
        $karyawanidsalesman = $Request->karyawanidsalesman;
        $periode = $Request->periode.'01';

        $lastPeriod = Carbon::parse($periode)->subDay();
        $firstPeriod = Carbon::parse($periode)->subMonths(6);

        $periodBegin = $firstPeriod->year.str_pad($firstPeriod->month, 2, "0", STR_PAD_LEFT);
        $periodEnd = $lastPeriod->year.str_pad($lastPeriod->month, 2, "0", STR_PAD_LEFT);

        $check = PlanJual::where('periode',$Request->periode);
        $check->where("recordownerid", $recordownerid);
        $check->where("karyawanidsalesman",$karyawanidsalesman);

        //dd($periodBegin, $periodEnd, $lastPeriod, $firstPeriod);
        

        if ($check->count() < 1){
            DB::beginTransaction();
            try
            {
                // $strSQL = "
                //     SELECT 
                //         rp.karyawanidsalesman
                //         ,rp.stockid
                //         ,rp.kodebarang
                //         ,(CASE WHEN rp.qty ISNULL THEN 0 ELSE rp.qty END) AS qtyhisjual
                //         ,(CASE WHEN rs.qty00 ISNULL THEN 0 ELSE rs.qty00 END) AS qtystokgudang00
                //         ,(CASE WHEN sg.qty11 ISNULL THEN 0 ELSE sg.qty11 END) AS qtystokgudang11
                //         ,(CASE WHEN bmk.hrgb1 ISNULL THEN 0 ELSE bmk.hrgb1 END) AS hrgb1
                //     FROM
                //     (
                //         SELECT
                //             srp.karyawanidsalesman
                //             ,srp.stockid
                //             ,st.kodebarang
                //             ,sum(qty) as qty
                //         FROM batch.rekappenjualanperitemmonthly srp
                //         INNER JOIN mstr.stock st ON st.id = srp.stockid
                //         WHERE (srp.periode BETWEEN :awal AND :akhir
                //         AND srp.recordownerid = :recordownerid
                //         AND srp.karyawanidsalesman = :karyawanidsalesman
                //         AND st.statusaktif = true)
                //         GROUP BY srp.stockid, st.kodebarang, srp.karyawanidsalesman
                //     ) AS rp
                    // LEFT JOIN
                    // (
                    //     SELECT
                    //         srs.stockid
                    //         ,SUM(srs.stokgudang) AS qty00
                    //     FROM stk.rekapstockdaily srs
                    //     WHERE (srs.recordownerid = :recordownerid
                    //     AND srs.tanggal = :lastperiod)
                    //     GROUP BY srs.stockid
                    // ) AS rs ON rs.stockid = rp.stockid
                //     LEFT JOIN
                //     (
                //         SELECT
                //             ssg.stockid
                //             ,SUM(ssg.qtystokawal) AS qty11
                //         FROM mstr.stockgudang11 ssg
                //         WHERE ssg.tglstokawal = :periode
                //         GROUP BY ssg.stockid
                //     ) AS sg ON sg.stockid = rp.stockid
                //     LEFT JOIN
                //     (
                //         SELECT
                //             sh.stockid
                //             ,SUM(sh.hrgb1) AS hrgb1
                //         FROM pj.historybmk1 sh
                //         WHERE sh.tglaktif BETWEEN :firstperiod AND :lastperiod
                //         GROUP BY sh.stockid
                //     ) AS bmk ON bmk.stockid = rp.stockid
                //     WHERE rp.kodebarang like 'FB%' or rp.kodebarang like 'FE%'
                // ";

                $strSQL = "
                
                    SELECT
                        stk.id AS stockid,
                        stk.kodebarang,
                        stk.namabarang,
                        COALESCE(rpp.qtyhisjual,0) AS qtyhisjual,
                        COALESCE(rs.qty00,0) AS qtystokgudang00,
                        COALESCE(sg.qty11,0) AS qtystokgudang11,
                        COALESCE(bmk.hrgb1) AS hrgb1
                    FROM mstr.stock stk 
                    LEFT JOIN
                    (
                        SELECT
                            rpp.stockid, SUM(rpp.qty) AS qtyhisjual
                        FROM batch.rekappenjualanperitemmonthly rpp
                        WHERE rpp.karyawanidsalesman = :karyawanidsalesman
                        AND rpp.recordownerid = :recordownerid
                        AND rpp.periode BETWEEN :awal AND :akhir
                        GROUP BY rpp.stockid
                    ) AS rpp ON rpp.stockid = stk.id
                    LEFT JOIN
                    (
                            SELECT
                                    srs.stockid
                                    ,SUM(srs.stokgudang) AS qty00
                            FROM stk.rekapstockdaily srs
                            WHERE (srs.recordownerid = :recordownerid
                            AND srs.tanggal = :lastperiod)
                            GROUP BY srs.stockid
                    ) AS rs ON rs.stockid = stk.id
                    LEFT JOIN
                    (
                        SELECT
                                ssg.stockid
                                ,SUM(ssg.qtystokawal) AS qty11
                        FROM mstr.stockgudang11 ssg
                        WHERE ssg.tglstokawal = :periode
                        GROUP BY ssg.stockid
                    ) AS sg ON sg.stockid = stk.id
                    LEFT JOIN
                    (
                            SELECT
                                    sh.stockid
                                    ,SUM(sh.hrgb1) AS hrgb1
                        FROM pj.historybmk1 sh
                            WHERE sh.tglaktif BETWEEN :firstperiod AND :lastperiod
                            GROUP BY sh.stockid
                    ) AS bmk ON bmk.stockid = stk.id
                    
                    WHERE SUBSTRING(stk.kodebarang,1,2) IN ('FB','FE') AND stk.statusaktif = true
                
                ";

                $mupbarangB = AppSetting::select('value')->where('recordownerid',$Request->session()->get('subcabang'))->where('keyid','MUPbarangB')->first();
                $mupbarangE = AppSetting::select('value')->where('recordownerid',$Request->session()->get('subcabang'))->where('keyid','MUPbarangE')->first();
                
                

                $query = DB::select($strSQL, array(
                    ':awal' => $periodBegin,
                    ':akhir' => $periodEnd,
                    ':recordownerid' => $recordownerid,
                    ':karyawanidsalesman' => $karyawanidsalesman,
                    ':lastperiod' => $lastPeriod,
                    ':periode' => $periode,
                    ':firstperiod' => $firstPeriod

                
                ));

                //dd($query);

                foreach ($query as $row){
                    $stockid = $row->stockid;
                    $kodebarang = substr($row->kodebarang, 0, 2);
                    $qtyhisjual = $row->qtyhisjual; //($row->qtyhisjual) ? $row->qtyhisjual : 0;
                    $qtystokgudang00 = ($row->qtystokgudang00) ? $row->qtystokgudang00 : 0;
                    $qtystokgudang11 = ($row->qtystokgudang11) ? $row->qtystokgudang11 : 0;
                    $hrgb1 = ($row->hrgb1) ? $row->hrgb1 : 0;
                    $mup = 0;
                    if ($kodebarang = 'FE'){
                        $mup = $mupbarangE->value;
                    }elseif ($kodebarang = 'FB') {
                        $mup = $mupbarangB->value;
                    }
                    $hargajual = $hrgb1 + (($mup / 100) * $hrgb1);

                    $Record = new PlanJual();

                    $Record->recordownerid = $recordownerid;
                    $Record->karyawanidsalesman = $karyawanidsalesman;
                    $Record->stockid = $stockid;
                    $Record->periode = $Request->periode;
                    $Record->qtystokgudang00 = $qtystokgudang00;
                    $Record->qtystokgudang11 = $qtystokgudang11;
                    $Record->qtyhisjual = $qtyhisjual;
                    $Record->hargajual = $hargajual;
                    $Record->qtyplanjual = 0;
                    $Record->createdby = strtoupper(auth()->user()->username);
                    $Record->lastupdatedby = strtoupper(auth()->user()->username);



                    $Record->save();
                 }
                
                DB::commit();
            }catch (Exception $ex){
                DB::rollback();
            }
        }

        $columns = array
        (
            0 => 'pj.planjualsales.id',
            1 => 'pj.planjualsales.periode',
            2 => 'mstr.stock.namabarang',
            3 => 'pj.planjualsales.qtystokgudang00',
            4 => 'pj.planjualsales.qtystokgudang11',
            5 => 'pj.planjualsales.qtyhisjual',
            6 => 'pj.planjualsales.hargajual',
            7 => 'pj.planjualsales.qtyplanjual',
            8 => 'pj.planjualsales.createdon',
            9 => 'pj.planjualsales.createdby',
            10 => 'pj.planjualsales.lastupdatedon',
            11 => 'pj.planjualsales.lastupdatedby',
            12 => 'hr.karyawan.namakaryawan'
        );

        $columns2 = array
        (
            0 => 'mstr.targetsalescabangmonthly.id',
            1 => 'mstr.targetsalescabangmonthly.periode',
            2 => 'mstr.targetsalescabangmonthly.targetfb',
            3 => 'mstr.targetsalescabangmonthly.targetfe',
            4 => 'mstr.targetsalescabangmonthly.targetall',
        );

        $plan = PlanJual::selectRaw(collect($columns)->implode(', '));
        $plan->leftJoin('hr.karyawan', 'pj.planjualsales.karyawanidsalesman','=','hr.karyawan.id');
        $plan->leftJoin('mstr.stock', 'pj.planjualsales.stockid','=','mstr.stock.id');
        $plan->where('pj.planjualsales.recordownerid','=', $Request->session()->get('subcabang'));
        $plan->where('pj.planjualsales.periode','=',$Request->periode);
        $plan->where("pj.planjualsales.karyawanidsalesman",'=',$Request->karyawanidsalesman);
        $plan->orderBy('mstr.stock.namabarang','asc');

        $data = $plan->get();

        $omset = TargetSalesCabangMonthly::selectRaw(collect($columns2)->implode(', '));
        $omset->where('mstr.targetsalescabangmonthly.recordownerid','=', $Request->session()->get('subcabang'));
        // $omset->where('mstr.targetsalescabangmonthly.periode','=',$Request->periode);
        $omset->where("mstr.targetsalescabangmonthly.karyawanidsalesman",'=',$Request->karyawanidsalesman);
        $data2 = $omset->get();

        $totalNominal = 0;
        $totalOmset = 0;

        foreach ($data as $key=>$row){
            $totalNominal += $row['hargajual'] * $row['qtyplanjual'];
        }

        foreach ($data2 as $key=>$row){
            $totalOmset += $row['targetall'];
        }

        return response()->json([
            'draw'            => $Request->draw,
            'recordsTotal'    => count($data),
            'totalNominal'    => $totalNominal,
            'totalOmset'      => $totalOmset,
            'selisih'         => $totalOmset - $totalNominal,
            'data'            => json_encode($data),
        ]);
    }

    public function getHistorySales(Request $Request){
        $mupbarangB = AppSetting::select('value')->where('recordownerid',$Request->session()->get('subcabang'))->where('keyid','MUPbarangB')->first();
        $columns = array(
            0 => 'pj.planjualsales.id',
            1 => 'mstr.stock.kodebarang',
            2 => 'mstr.stock.namabarang',
            3 => 'pj.planjualsales.qtyhisjual',
            4 => 'pj.planjualsales.qtystokgudang00',
            5 => "(case when pj.planjualsales.qtystokgudang11  < 1 then 'TIDAK ADA' else 'ADA' end) as qtystokgudang11",
            6 => '(pj.planjualsales.qtystokgudang00 - pj.planjualsales.qtystokgudang11 - pj.planjualsales.qtyplanjual) as qtysisa',
            7 => 'round(pj.planjualsales.hargajual * '.$mupbarangB->value.' /100) as hargajual ',
            8 => '(pj.planjualsales.qtyplanjual * pj.planjualsales.hargajual) as nomplj',
            9 => 'pj.planjualsales.qtyplanjual'
        );

        $plan = PlanJual::selectRaw(collect($columns)->implode(', '));
        $plan->leftJoin('mstr.stock', 'pj.planjualsales.stockid','=','mstr.stock.id');
        $plan->where('pj.planjualsales.recordownerid','=', $Request->session()->get('subcabang'));
        $plan->where('pj.planjualsales.periode','=',$Request->periode);
        $plan->where("pj.planjualsales.karyawanidsalesman",'=',$Request->karyawanidsalesman);
        if ($Request->history == 0){
            $plan->where('pj.planjualsales.qtyhisjual','!=', 0);
        }
        $plan->orderBy('mstr.stock.namabarang');

        $data = $plan->get();

        return response()->json([
            'draw'            => $Request->draw,
            'recordsTotal'    => count($data),
            'data'            => json_encode($data),
        ]);
    }

    public function RunQueryUpdate(Request $Request){
        $blnResult = false;
        try{
            $query = PlanJual::find($Request->id);
            $query->qtyplanjual         = $Request->qtyplanjual;
            $query->lastupdatedby       = strtoupper(auth()->user()->username);
            $query->save();
            $blnResult = true;
        }catch (Exception $ex){
            $blnResult = false;
            //throw new Exception($ex->getMessage());
        }
        
        return response()->json(array(
            'success'   => $blnResult
        ));
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

    public function cetak(Request $Request){
        $this->insert_reportlog($Request->session()->get('subcabang'),auth()->user()->id,'PLAN JUAL SALES');

        $mupbarangB = AppSetting::select('value')->where('recordownerid',$Request->session()->get('subcabang'))->where('keyid','MUPbarangB')->first();
        
        $recordownerid = $Request->session()->get('subcabang');
        $karyawanidsalesman = $Request->karyawanidsalesman;
        $namakaryawan = Karyawan::select('kodesales')->where('id',$Request->karyawanidsalesman)->first();
        $periode = $Request->periode.'01';

        $lastPeriod = Carbon::parse($periode)->subDay();
        $firstPeriod = Carbon::parse($periode)->subMonths(7);

        $subcabang = SubCabang::find(session('subcabang'))->kodesubcabang;
        $username  = strtoupper(auth()->user()->username);

        $strSQL = "
            SELECT
                ps.id 
                ,ps.periode
                ,st.kodebarang
                ,st.namabarang 
                ,ps.qtyhisjual
                ,ps.qtystokgudang00
                ,(CASE WHEN ps.qtystokgudang11 < 1 THEN 'TIDAK ADA' ELSE 'ADA' END) AS qtystokgudang11
                ,(ps.hargajual * :mupb) AS hargajual
                ,ps.qtyplanjual
                ,(ps.qtyplanjual * ps.hargajual) AS nominal
                ,p.listtoko
            FROM pj.planjualsales ps
            INNER JOIN mstr.stock st ON ps.stockid = st.id
            LEFT JOIN
            (
            SELECT DISTINCT
                    np.karyawanidsalesman, npd.stockid, string_agg(tk.namatoko, ', ') AS listtoko
                FROM pj.notapenjualandetail npd
                INNER JOIN pj.notapenjualan np ON npd.notapenjualanid = np.id
                INNER JOIN mstr.toko tk ON np.tokoid = tk.id
                WHERE np.tglnota BETWEEN :firstperiod AND :lastperiod
                GROUP BY np.karyawanidsalesman, npd.stockid
            ) AS p ON p.stockid = ps.stockid AND p.karyawanidsalesman = ps.karyawanidsalesman
            WHERE ps.periode = :periode
            AND ps.karyawanidsalesman = :karyawanidsalesman
            AND ps.recordownerid = :recordownerid
            ORDER BY st.namabarang
        ";

        $data = DB::select($strSQL, array(
            ':firstperiod' => $firstPeriod,
            ':lastperiod' => $lastPeriod,
            ':karyawanidsalesman' => $karyawanidsalesman,
            ':recordownerid' => $recordownerid,
            ':mupb' => $mupbarangB->value,
            ':periode' => $Request->periode
        ));
        // dd($namakaryawan->namakaryawan);

        Excel::create('Laporan Plan Jual Salesman', function($excel) use ($periode,$subcabang,$data,$username,$namakaryawan) {
            $excel->setTitle('Laporan Plan Jual Salesman');
            $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
            $excel->setmanager(strtoupper(auth()->user()->username));
            $excel->setsubject('Laporan Plan Jual Salesman');
            $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
            $excel->setDescription('Laporan Plan Jual Salesman');
            $excel->sheet('LaporanPlanJualSalesman', function($sheet) use ($periode,$subcabang,$data,$username,$namakaryawan) {
                $sheet->setColumnFormat(array(
                    'E' => '@',
                    'H' => '#,##0',
                    'I' => '#,##0',
                ));
                $sheet->loadView('transaksi.planjual.excel',array('periode'=>$periode,'subcabang'=>$subcabang,'datas'=>$data,'username'=>$username,'salesman' => $namakaryawan->kodesales));
            });
        })->store('xls', storage_path('excel/exports'));

        $config = [
            'mode'                 => '',
            'format'               => 'A4-L',
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 30,
            'margin_bottom'        => 8,
            'margin_header'        => 0,
            'margin_footer'        => 5,
            'orientation'          => 'L',
            'title'                => "SAS",
            'author'               => '',
            'watermark'            => '',
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
            'show_watermark_image' => true,
            'show_watermark'       => true,
        ];

        $pdf = PDF::loadView('transaksi.planjual.pdf',[
            'periode'   => $Request->periode,
            'subcabang' => $subcabang,
            'datas'     => $data,
            'username'  => $username,
            'salesman'  => $namakaryawan->kodesales
        ],[],$config);




        $pdf->save(storage_path('excel/exports') . '/Laporan Plan Jual Salesman.pdf');
        $urlExcel = 'Laporan Plan Jual Salesman.xls'; // asset('storage/excel/exports/Laporan Plan Jual Salesman.xls');
        $urlPDF = 'Laporan Plan Jual Salesman.pdf'; // asset('storage/excel/exports/Laporan Plan Jual Salesman.pdf');

        //$urlPDF = $pdf->download('Laporan Plan Jual Salesman.pdf');

        return view('transaksi.planjual.cetak',compact('data', 'urlPDF', 'urlExcel'));
    }

    public function UploadSQLServerStaging(Request $Request){
        $blnresult = false;
        $message = '';

        DB::beginTransaction();

        try{
            $blncontinue = true;

            $periode = $Request->periode;
            $recordownerid = $Request->recordownerid;
            $karyawanidsalesman = $Request->karyawanidsalesman;

            $sqlserver = DB::connection('sqlsrv');
            $queryStag = $sqlserver->table('dbo.planjualsales')->get();

            $columns = array(
                0 => 'pj.planjualsales.id',
                1 => 'mstr.stock.kodebarang',
                2 => 'pj.planjualsales.stockid',
                3 => 'pj.planjualsales.qtyhisjual',
                4 => 'pj.planjualsales.qtystokgudang00',
                5 => "pj.planjualsales.qtystokgudang11",
                7 => 'pj.planjualsales.hargajual',
                9 => 'pj.planjualsales.qtyplanjual',
                10 => 'hr.karyawan.kodesales'
            );

            DB::commit();
        }catch (Exception $ex){
            $blnresult = false;
            $message = $ex->getMessage();
            DB::rollback();
        }
    }

    public function UploadSQLServerStaging1(Request $Request){
        $blnresult = false;
        $message = '';

        DB::beginTransaction();
        try{

            $sqlserver = DB::connection('sqlsrv');
            //$query = $sqlserver->table('dbo.planjualsales')->selectRaw('id');
            dd($query->get());

            $mupbarangB = AppSetting::select('value')->where('recordownerid',$Request->session()->get('subcabang'))->where('keyid','MUPbarangB')->first();
            $kodesubcabang = SubCabang::select('kodesubcabang')->where('id', $Request->session()->get('subcabang'))->first()->kodesubcabang;

            $columns = array(
                0 => 'pj.planjualsales.id',
                1 => 'mstr.stock.kodebarang',
                2 => 'pj.planjualsales.stockid',
                3 => 'pj.planjualsales.qtyhisjual',
                4 => 'pj.planjualsales.qtystokgudang00',
                5 => "pj.planjualsales.qtystokgudang11",
                7 => '(pj.planjualsales.hargajual * '.$mupbarangB->value.') as hargajual ',
                8 => '(pj.planjualsales.qtyplanjual * pj.planjualsales.hargajual) as nomplj',
                9 => 'pj.planjualsales.qtyplanjual',
                10 => 'hr.karyawan.kodesales'
            );

            $plan = PlanJual::selectRaw(collect($columns)->implode(', '));
            $plan->leftJoin('mstr.stock', 'pj.planjualsales.stockid','=','mstr.stock.id');
            $plan->leftJoin('hr.karyawan', 'pj.planjualsales.karyawanidsalesman', '=', 'hr.karyawan.id');
            $plan->where('pj.planjualsales.recordownerid','=', $Request->session()->get('subcabang'));
            $plan->where('pj.planjualsales.periode','=',$Request->periode);
            $plan->where("pj.planjualsales.karyawanidsalesman",'=',$Request->karyawanidsalesman);
            if ($Request->history = 0){
                $plan->where('pj.planjualsales.qtyhisjual != 0');
            }
            $plan->orderBy('mstr.stock.namabarang');

            $data = $plan->get();

            foreach ($data as $key=>$row){
                
                $kodebarang = $row['kodebarang'];
                $kodegudang = $kodesubcabang;
                $kodesales = $row['kodesales'];
                $recordownerid = $Request->session()->get('subcabang');
                $karyawanidsalesman = $Request->karyawanidsalesman;
                $stockid = $row['stockid'];
                $periode = $Request->periode;
                $qtystokgudang00 = $row['qtystokgudang00'];
                $qtystokgudang11 = $row['qtystokgudang11'];
                $qtyhisjual = $row['qtyhisjual'];
                $hargajual = $row['hargajual'];
                $qtyplanjual = $row['qtyplanjual'];
                $tglupload = Carbon::now();
                $createdon = Carbon::now();
                $createdby = strtoupper(auth()->user()->username);
                $lastupdatedon = Carbon::now();
                $lastupdatedby = strtoupper(auth()->user()->username);
            }

            DB::commit();
            $blnresult = true;
            $message = 'Data berhasil diupload.';
        }catch (Exception $ex){
            DB::rollback();
            $blnresult = false;
            $message = $ex->getMessage();

        }

        
        return response()->json(['success' => $blnresult, 'message' => $message]);

        
    }

    public function download($i){
        return response()->download(storage_path("excel/exports/".$i));
    }
    
}
