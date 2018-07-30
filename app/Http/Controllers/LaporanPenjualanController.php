<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use XLSXWriter;
use PDF;

use App\Models\SubCabang;

// Traits
use App\Http\Traits\ReportTraits;

class LaporanPenjualanController extends Controller
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
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.index')) {
            return redirect('/')->with('message',['status'=>'danger','desc'=>'Tidak Memiliki Hak Akses!']);
        }

        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE OUTLET');
        $type      = $req->type;
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        return view('laporan.penjualan.performanceoutlet_preview',['type'=>$type,'tahun'=>$tahun,'bulan'=>$bulan]);
    }

    public function performanceOutletData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.index')) {
            return response()->json(['draw'=>$req->draw, 'recordsTotal'=>0, 'recordsFiltered'=>0, 'data'=>[],]);
        }

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
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.index')) {
            return null;
        }

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
                $row->customwilayah,$row->namatoko,$row->pn,$row->roda,$row->telp,$row->kodesales,$row->tglfixedroute,$row->sisa_plafon,$row->sku,$row->efektif_oa,$row->targetomset,$row->achievement_rp,$row->achievement_persen/100,
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
                toko.id,
                toko.namatoko,
                toko.customwilayah,
                toko.telp,
                statustoko.roda,
                hr.karyawan.kodesales,
                (CASE WHEN (mstr.tokopareto.tokoid IS NOT NULL) THEN 'P' ELSE 'N' END) AS pn,
                kunjungansales.tglfixedroute,
                SUM(CASE WHEN kunjungansales.counttglkunjung IS NOT NULL THEN kunjungansales.counttglkunjung ELSE 0 END) as counttglkunjung,
                -- (CASE WHEN (plafon.sisa_plafon IS NOT NULL) THEN plafon.sisa_plafon ELSE 0 END) as sisa_plafon,
                -- (CASE WHEN (SUM(ptg.kartupiutang.nomnota)-SUM(ptg.kartupiutangdetail.nomtrans) > 0) THEN SUM(ptg.kartupiutang.nomnota)-SUM(ptg.kartupiutangdetail.nomtrans) ELSE 0 END)
                -- +SUM(pj.notapenjualan.totalnominal)
                -- +SUM(CASE WHEN (rpaccpiutang <= sumdetail) THEN rpaccpiutang ELSE sumdetail END) as sisa_piutang,
                (CASE WHEN (plafon.sisa_plafon IS NOT NULL) THEN plafon.sisa_plafon ELSE 0 END) --plafonsisa,
                -((CASE WHEN (kpiutang.saldo > 0) THEN kpiutang.saldo ELSE 0 END) --saldopiutang,
                +COALESCE(njual.nominal,0) --notablmterima,
                +COALESCE(opj.rpsoacc,0) /*rpsoacc*/) as sisa_plafon,
                (CASE WHEN (mstr.tokopareto.targetomset IS NOT NULL) THEN mstr.tokopareto.targetomset ELSE 0 END) as targetomset,
                COUNT(distinct batch.rekappenjualanperitemmonthly.stockid) AS sku,
            --  SUM(COALESCE(batch.rekappenjualanperitemmonthly.qty,0)) AS sku,
                COALESCE(ROUND((SUM(toko.counttglso)::numeric/SUM(kunjungansales.counttglkunjung)::numeric)*100,2),0) AS efektif_oa,
                (CASE WHEN SUM(COALESCE(batch.rekappenjualanperitemmonthly.subtotalnettojual,0)) IS NOT NULL THEN SUM(COALESCE(batch.rekappenjualanperitemmonthly.subtotalnettojual,0)) ELSE 0 END) AS achievement_rp,
                (CASE WHEN (SUM(COALESCE(batch.rekappenjualanperitemmonthly.subtotalnettojual,0)) IS NOT NULL AND mstr.tokopareto.targetomset IS NOT NULL)
                THEN (SUM(COALESCE(batch.rekappenjualanperitemmonthly.subtotalnettojual,0))/COALESCE(mstr.tokopareto.targetomset,0)) * 100 ELSE 0 END
                )::numeric(19, 2) AS achievement_persen,
                $pertanggal
            FROM (
                SELECT mstr.toko.*, orderpenjualan.*
                FROM mstr.toko
                LEFT JOIN (
                    SELECT distinct 
                        on (tokoid) tokoid, 
                        tglstatus,
                        statusaktif 
                    FROM mstr.tokoaktifpasif 
                    order by tokoid, 
                    tglstatus desc) 
                tokoaktifpasif ON (mstr.toko.id = tokoaktifpasif.tokoid)
                LEFT JOIN (
                    SELECT 
                        tokoid, 
                        COUNT(tglso) AS counttglso 
                    FROM pj.orderpenjualan
                    WHERE EXTRACT(YEAR FROM tglso) = '2017'
                    AND EXTRACT(MONTH FROM tglso) = '11'
                    GROUP BY tokoid
                ) AS orderpenjualan ON mstr.toko.id = orderpenjualan.tokoid
                WHERE (tokoaktifpasif.statusaktif = TRUE OR orderpenjualan.counttglso != 0) 
            --  AND namatoko like '%AANG%'
            --  limit 10
            )toko
            LEFT JOIN mstr.tokopareto ON (toko.id = mstr.tokopareto.tokoid AND mstr.tokopareto.periode = '201711')
            LEFT JOIN (
                SELECT DISTINCT ON (tokoid) tokoid, roda, tglaktif FROM mstr.statustoko
                WHERE roda != '' ORDER BY tokoid ASC, tglaktif DESC
            ) AS statustoko ON toko.id = statustoko.tokoid
            LEFT JOIN (
                SELECT DISTINCT ON (tokoid) tokoid, karyawanidsalesman, tglaktif FROM mstr.tokohakakses
                WHERE recordownerid = 3 ORDER BY tokoid ASC, tglaktif DESC
            ) AS tokohakakses ON toko.id = tokohakakses.tokoid
            LEFT JOIN hr.karyawan ON hr.karyawan.id = tokohakakses.karyawanidsalesman
            LEFT JOIN (
                SELECT 
                    tokoid, 
                    ARRAY_TO_STRING(ARRAY_AGG(DISTINCT(EXTRACT(DAY FROM tglkunjung))), ',') as tglfixedroute, 
                    COUNT(tglkunjung) as counttglkunjung
                FROM pj.kunjungansales
                WHERE recordownerid = 3
                AND EXTRACT(YEAR FROM tglkunjung) = '2017'
                AND EXTRACT(MONTH FROM tglkunjung) = '11'
                GROUP BY tokoid
            ) AS kunjungansales ON toko.id = kunjungansales.tokoid
            LEFT JOIN (
                SELECT
                    ptg.kartupiutang.tokoid,
                    SUM(ptg.kartupiutang.nomnota - kpiutdet.bayar) saldo
                FROM ptg.kartupiutang
                LEFT JOIN (
                    SELECT 
                        ptg.kartupiutangdetail.kartupiutangid,
                        SUM(ptg.kartupiutangdetail.nomtrans) bayar
                    FROM ptg.kartupiutangdetail 
                    group by ptg.kartupiutangdetail.kartupiutangid
                )kpiutdet ON ptg.kartupiutang.id = kpiutdet.kartupiutangid
                group by ptg.kartupiutang.tokoid
            )kpiutang on kpiutang.tokoid = toko.id
            -- -- LEFT JOIN ptg.kartupiutang ON toko.id = ptg.kartupiutang.tokoid
            -- -- LEFT JOIN ptg.kartupiutangdetail ON ptg.kartupiutang.id = ptg.kartupiutangdetail.kartupiutangid
            LEFT JOIN (
                SELECT tokoid, (plafon.plafon+plafon.plafontambahan) AS sisa_plafon FROM ptg.plafon
                WHERE EXTRACT(YEAR FROM tglaktif) = '2017'
                AND EXTRACT(MONTH FROM tglaktif) = '11'
            ) AS plafon ON toko.id = plafon.tokoid
            LEFT JOIN (
                SELECT
                    pj.notapenjualan.tokoid,
                    SUM(pj.notapenjualan.totalnominal) nominal
                FROM pj.notapenjualan 
                WHERE pj.notapenjualan.recordownerid = 3 AND pj.notapenjualan.tglnota IS NULL
                GROUP BY pj.notapenjualan.tokoid 
            )njual ON njual.tokoid = toko.id
            left join (
                select
                    opj.tokoid,
                    sum(case when opj.rpaccpiutang <= opj.soacc then opj.rpaccpiutang else opj.soacc end) rpsoacc
                from (
                    select 
                        pj.orderpenjualan.tokoid, 
                        pj.orderpenjualan.id, 
                        pj.orderpenjualan.rpaccpiutang,
                        sum(coalesce(qtysoacc,0) * coalesce(hrgsatuannetto,0)) soacc
                    from pj.orderpenjualan
                    left join pj.notapenjualan on pj.orderpenjualan.id = pj.notapenjualan.orderpenjualanid
                    join pj.orderpenjualandetail on pj.orderpenjualan.id = pj.orderpenjualandetail.orderpenjualanid
                    where pj.orderpenjualan.tokoid = 1442
                    and pj.notapenjualan.id is null
                    and pj.orderpenjualan.noaccpiutang != ''
                    group by pj.orderpenjualan.tokoid, 
                        pj.orderpenjualan.id, 
                        pj.orderpenjualan.rpaccpiutang
                ) opj
                group by opj.tokoid
            )opj on opj.tokoid = toko.id
            LEFT JOIN batch.rekappenjualanperitemmonthly ON (toko.id = batch.rekappenjualanperitemmonthly.tokoid AND batch.rekappenjualanperitemmonthly.periode = '201711')
            $where
            GROUP BY
                toko.id,
                toko.namatoko,
                toko.customwilayah,
                toko.telp,
                statustoko.roda,
                hr.karyawan.kodesales,
                pn,
                kunjungansales.tglfixedroute,
                sisa_plafon,
                targetomset,
                kpiutang.saldo,
                opj.rpsoacc,
                njual.nominal
            ORDER BY toko.customwilayah, toko.namatoko
        ";

        // Jika butuh paginasi
        if($wtotal){
            $total  = DB::connection('pgsql_report')->select(DB::raw("SELECT COUNT(id) as total FROM ($query) temptable"));
            $result = DB::connection('pgsql_report')->select(DB::raw($query." LIMIT ".$limit." OFFSET ".$offset));

            return ['total'=>$total[0]->total,'result'=>$result];
        }else{
            $result = DB::connection('pgsql_report')->select(DB::raw($query));
            return $result;
       }
    }

    public function performanceProduct(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.index')) {
            return redirect('/')->with('message',['status'=>'danger','desc'=>'Tidak Memiliki Hak Akses!']);
        }

        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE PRODUCT');
        $type      = $req->type;
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        return view('laporan.penjualan.performanceproducts_preview',['type'=>$type,'tahun'=>$tahun,'bulan'=>$bulan]);
    }

    public function performanceProductData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.index')) {
            return response()->json(['draw'=>$req->draw, 'recordsTotal'=>0, 'recordsFiltered'=>0, 'data'=>[],]);
        }

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
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.index')) {
            return null;
        }

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
                $row->git_qty,$row->git_rp,$row->proyeksi_qty,$row->proyeksi_rp,$row->achievement_qty,$row->achievement_qty_persen/100,$row->achievement_rp,$row->achievement_rp_persen/100,
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

    public function performanceProductPersales(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.index')) {
            return redirect('/')->with('message',['status'=>'danger','desc'=>'Tidak Memiliki Hak Akses!']);
        }

        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE PRODUCT PERSALES');
        $subcabang = $req->session()->get('subcabang');
        $type      = $req->type;
        $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        // Get Sales list
        $sales = DB::connection('pgsql_report')->select(DB::raw("SELECT * FROM hr.sales WHERE recordownerid = $subcabang"));

        return view('laporan.penjualan.performanceproductspersales_preview',['sales'=>$sales,'type'=>$type,'tahun'=>$tahun,'bulan'=>$bulan]);
    }

    public function performanceProductPersalesData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.index')) {
            return response()->json(['draw'=>$req->draw, 'recordsTotal'=>0, 'recordsFiltered'=>0, 'data'=>[],]);
        }

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
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.index')) {
            return null;
        }

        $subcabang = $req->session()->get('subcabang');
        $type      = $req->type;
        $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        // Get Sales list
        $sales = DB::connection('pgsql_report')->select(DB::raw("SELECT * FROM hr.sales WHERE recordownerid = $subcabang"));

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
            // echo "a";
            // $data  = $this->performanceProductQuery($subcabang,$type,$tahun,$bulan,0,0,$sal->id);
            // $count = count($data);
            // echo $sal->namakaryawan . " " . $count . "<br>";

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
                    $row->git_qty,$row->git_rp,$row->proyeksi_qty,$row->proyeksi_rp,$row->achievement_qty,$row->achievement_qty_persen/100,$row->achievement_rp,
                    // 0,
                    $row->achievement_rp_persen/100,
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
        // return "a";

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
                stk.kodebarang,
                stk.namabarang,
                grup.groupname,
                (CASE WHEN (grup.groupname ILIKE '%PARETO%') THEN 'P' ELSE 'N' END) AS pn,
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
                COALESCE(ROUND((SUM(batch.rekappenjualanperitemmonthly.qty)::numeric/batch.targetstockparetogoogledocsdk.planqty::numeric)*100,2),0) as achievement_qty_persen,
                COALESCE(ROUND((SUM(batch.rekappenjualanperitemmonthly.subtotalnettojual)::numeric/batch.targetstockparetogoogledocsdk.plansales::numeric)*100,2),0) as achievement_rp_persen,
                $pertanggal
            FROM (
                select * from mstr.stock 
                WHERE (
                mstr.stock.statusaktif = TRUE 
                AND (LEFT(mstr.stock.kodebarang,2) IN ('FB') OR LEFT(mstr.stock.kodebarang,2) IN ('FE')))
            ) stk
            left join(
                select
                    mstr.groupparts.groupname,
                    mstr.groupsubpartsstock.stockid
                from mstr.groupparts 
                left JOIN mstr.groupsubparts on mstr.groupparts.id = mstr.groupsubparts.grouppartsid
                left JOIN mstr.groupsubpartsstock on mstr.groupsubparts.id = mstr.groupsubpartsstock.groupsubpartsid
                where mstr.groupsubparts.createdby = 'MANUAL_AND_ERV'
            ) grup on grup.stockid = stk.id
            LEFT JOIN mstr.kategoripenjualan ON mstr.kategoripenjualan.id = stk.kategoripenjualan
            LEFT JOIN (
                SELECT DISTINCT ON (stockid) stockid, stokgudang, hppa, tanggal FROM stk.rekapstockdaily
                WHERE recordownerid = $subcabang AND EXTRACT(YEAR FROM tanggal) = '$tahun' AND EXTRACT(MONTH FROM tanggal) = '$bulan'
                ORDER BY stockid ASC, tanggal DESC
            ) AS rekapstockdaily ON rekapstockdaily.stockid = stk.id
            LEFT JOIN batch.rekappenjualanperitemmonthly ON (stk.id = batch.rekappenjualanperitemmonthly.stockid AND batch.rekappenjualanperitemmonthly.periode = '$tahun$bulan' by_karyawanidsalesman)
            LEFT JOIN batch.targetstockparetogoogledocsdk ON (stk.id = batch.targetstockparetogoogledocsdk.stockid AND batch.targetstockparetogoogledocsdk.periode = '$tahun$bulan')
            LEFT JOIN (
                SELECT DISTINCT ON (\"id\",\"stockid\")
                    pj.notapenjualandetail.id, pj.notapenjualandetail.stockid, pj.notapenjualandetail.qtynota, pj.notapenjualan.tglproforma,
                    acc.historyhppa.nominalhppa, acc.historyhppa.tglaktif, (pj.notapenjualandetail.qtynota*acc.historyhppa.nominalhppa) AS total_git
                FROM pj.notapenjualandetail
                JOIN pj.notapenjualan ON pj.notapenjualan.recordownerid = $subcabang AND pj.notapenjualan.tglnota IS NULL AND pj.notapenjualan.id = pj.notapenjualandetail.notapenjualanid
                LEFT JOIN acc.historyhppa ON acc.historyhppa.recordownerid = $subcabang AND pj.notapenjualandetail.stockid = acc.historyhppa.stockid AND acc.historyhppa.tglaktif <= pj.notapenjualan.tglproforma
                ORDER BY pj.notapenjualandetail.id, pj.notapenjualandetail.stockid, acc.historyhppa.tglaktif DESC
            ) AS npjd ON (npjd.stockid = stk.id)
            $where
            GROUP BY kodebarang, namabarang, groupname, pn, kategori, rekapstockdaily.stokgudang, rekapstockdaily.hppa, npjd.nominalhppa, planqty, plansales
            ORDER BY left(kodebarang, 3), grup.groupname, stk.namabarang
        ";

        // Jika by salesman
        if($salesman != null) {
            $query = str_replace("by_karyawanidsalesman", "AND batch.rekappenjualanperitemmonthly.karyawanidsalesman = ".$salesman." ", $query);
        }else{
            $query = str_replace("by_karyawanidsalesman", "", $query);
        }

        // Jika butuh paginasi
        if($wtotal){
            $total  = DB::connection('pgsql_report')->select(DB::raw("SELECT COUNT(kodebarang) as total FROM ($query) temptable"));
            $result = DB::connection('pgsql_report')->select(DB::raw($query." LIMIT ".$limit." OFFSET ".$offset));

            return ['total'=>$total[0]->total,'result'=>$result];
        }else{
            $result = DB::connection('pgsql_report')->select(DB::raw($query));
            return $result;
       }
    }

    public function performanceSalesman(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.index')) {
            return redirect('/')->with('message',['status'=>'danger','desc'=>'Tidak Memiliki Hak Akses!']);
        }

        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'PERFORMANCE SALESMAN');
        $subcabang = $req->session()->get('subcabang');
        $type      = $req->type;
        $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        return view('laporan.penjualan.performancesalesman_preview',['type'=>$type,'tahun'=>$tahun,'bulan'=>$bulan]);
    }

    public function performanceSalesmanData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.index')) {
            return response()->json(['draw'=>$req->draw, 'recordsTotal'=>0, 'recordsFiltered'=>0, 'data'=>[],]);
        }

        $subcabang  = $req->session()->get('subcabang');
        $type   = $req->type;
        $tahun  = $req->tahun;
        $bulan  = $req->bulan;
        $limit  = $req->length;
        $offset = $req->start;

        // need moar execution time
        ini_set('max_execution_time',300);

        // Result
        $data  = $this->performanceSalesmanQuery($subcabang,$type,$tahun,$bulan,$limit,$offset,true);

        return response()->json([
          'draw'            => $req->draw,
          'recordsTotal'    => $data['total'],
          'recordsFiltered' => $data['total'],
          'data'            => $data['result'],
        ]);
    }

    public function performanceSalesmanExcel(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.index')) {
            return null;
        }

        $subcabang = $req->session()->get('subcabang');
        $type      = $req->type;
        $type_desc = (array_key_exists($type, $this->type)) ? $this->type[$type] : '';
        $tahun     = $req->tahun;
        $bulan     = $req->bulan;

        // need moar execution time
        ini_set('max_execution_time',300);

        // Result
        $data  = $this->performanceSalesmanQuery($subcabang,$type,$tahun,$bulan);
        $count = count($data);

        // Xlsx Var
        $sheet  = str_slug("Laporan Perfomance Salesman");
        $file   = $sheet."-".uniqid().".xlsx";
        $folder = storage_path('excel/exports');
        $subcabang = SubCabang::find(session('subcabang'))->kodesubcabang;
        $username  = strtoupper(auth()->user()->username);
        $tanggal   = Carbon::now()->format('d/m/Y');

        $header_data   = [
            [
                "KODE SALES",
                "NAMA SALES",
                "RO",
                "TARGET OA","","",
                "REALISASI OA","","","",
                "EFEKTIF OA","","","",
                "SKU","","","","",
                "TARGET SALES","",
                "ACHIEVEMENT B & LAINNYA","",
                "ACHIEVEMENT E","",
            ],
            ["","","","P","NP","TOTAL","P","NP","TOTAL","%","P","NP","TOTAL","%","FB2","FB4","FE2","FE4","TOTAL","B & LAINNYA","E","RP","%","RP","%",],
        ];
        for($x=1;$x<=31;$x++) {
            $header_data[0][] = $x;
            $header_data[1][] = "";
        }
        $data_format = [
            'GENERAL','GENERAL','#,##0',
            '#,##0','#,##0','#,##0',
            '#,##0','#,##0','#,##0','#,##0.00%',
            '#,##0','#,##0','#,##0','#,##0.00%',
            '#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0','#,##0',
            '#,##0','#,##0.00%',
            '#,##0','#,##0.00%',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
            '#,##0',
        ];

        $col_width = [
            17,50,10,
            10,10,10,
            10,10,10,10,
            10,10,10,10,
            10,10,10,10,10,
            18,18,
            18,10,
            18,10,
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
        $writer->writeSheetRow($sheet,["MONITORING SALESMAN ($type_desc)"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["Tanggal Update : $tanggal"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["Cabang : $subcabang"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,['']);

        // Write Table Header
        // Merge
        $writer->markMergedCell($sheet,4,0,5,0);
        $writer->markMergedCell($sheet,4,1,5,1);
        $writer->markMergedCell($sheet,4,2,5,2);
        $writer->markMergedCell($sheet,4,3,4,5);
        $writer->markMergedCell($sheet,4,6,4,9);
        $writer->markMergedCell($sheet,4,10,4,13);
        $writer->markMergedCell($sheet,4,14,4,18);
        $writer->markMergedCell($sheet,4,19,4,20);
        $writer->markMergedCell($sheet,4,21,4,22);
        $writer->markMergedCell($sheet,4,23,4,24);
        for($k=0; $k<31; $k++) {
            $writer->markMergedCell($sheet,4,$k+25,5,$k+25);
        }
        foreach ($header_data as $hdata) {
            $writer->writeSheetRow($sheet,$hdata,$this->header_style);
        }

        // Write Table Data
        foreach ($data as $row) {
            $value = [
                $row->kodesales,$row->namakaryawan,$row->ro,
                $row->target_oa_p,$row->target_oa_np,$row->target_oa_total,
                $row->realisasi_oa_p,$row->realisasi_oa_np,$row->realisasi_oa_total,$row->realisasi_oa_persen/100,
                $row->efektif_oa_p,$row->efektif_oa_np,$row->efektif_oa_total,$row->efektif_oa_persen/100,
                $row->sku_fb2,$row->sku_fb4,$row->sku_fe2,$row->sku_fe4,$row->sku_total,
                $row->targetfb,$row->targetfe,
                $row->achievement_b_rp,$row->achievement_b_persen/100,
                $row->achievement_e_rp,$row->achievement_e_persen/100,
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
            'TOTAL', '',
            '=SUM(C7:C'.$last.')',
            '=SUM(D7:D'.$last.')','=SUM(E7:E'.$last.')','=SUM(F7:F'.$last.')',
            '=SUM(G7:G'.$last.')','=SUM(H7:H'.$last.')','=SUM(I7:I'.$last.')','=SUM(J7:J'.$last.')',
            '=SUM(K7:K'.$last.')','=SUM(L7:L'.$last.')','=SUM(M7:M'.$last.')','=SUM(N7:N'.$last.')',
            '=SUM(O7:O'.$last.')','=SUM(P7:P'.$last.')','=SUM(Q7:Q'.$last.')','=SUM(R7:R'.$last.')','=SUM(S7:S'.$last.')',
            '=SUM(T7:T'.$last.')','=SUM(U7:U'.$last.')',
            '=SUM(V7:V'.$last.')','=(V'.($last+1).'/T'.($last+1).')',
            '=SUM(X7:X'.$last.')','=(X'.($last+1).'/U'.($last+1).')',
            '=SUM(Z7:Z'.$last.')',
            '=SUM(AA7:AA'.$last.')','=SUM(AB7:AB'.$last.')','=SUM(AC7:AC'.$last.')','=SUM(AD7:AD'.$last.')','=SUM(AE7:AE'.$last.')','=SUM(AF7:AF'.$last.')','=SUM(AG7:AG'.$last.')','=SUM(AH7:AH'.$last.')','=SUM(AI7:AI'.$last.')','=SUM(AJ7:AJ'.$last.')',
            '=SUM(AK7:AK'.$last.')','=SUM(AL7:AL'.$last.')','=SUM(AM7:AM'.$last.')','=SUM(AN7:AN'.$last.')','=SUM(AO7:AO'.$last.')','=SUM(AP7:AP'.$last.')','=SUM(AQ7:AQ'.$last.')','=SUM(AR7:AR'.$last.')','=SUM(AS7:AS'.$last.')','=SUM(AT7:AT'.$last.')',
            '=SUM(AU7:AU'.$last.')','=SUM(AV7:AV'.$last.')','=SUM(AW7:AW'.$last.')','=SUM(AX7:AX'.$last.')','=SUM(AY7:AY'.$last.')','=SUM(AZ7:AZ'.$last.')','=SUM(BA7:BA'.$last.')','=SUM(BB7:BB'.$last.')','=SUM(BC7:BC'.$last.')','=SUM(BD7:BD'.$last.')',
        ];
        $writer->markMergedCell($sheet,$last,0,$last,1);
        $writer->writeSheetRow($sheet,$total,['font-style'=>'bold', 'border'=>'left,right,top,bottom',],$data_format);

        // Footer
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,[$username.",".(Carbon::now()->format('d/m/Y h:i:s'))],['font-style'=>'italic']);

        // return if write to file complete
        $writer->writeToFile($folder.'/'.$file);
        return asset('storage/excel/exports/'.$file);
    }

    public function performanceSalesmanQuery($subcabang,$type,$tahun,$bulan,$limit=0,$offset=0,$wtotal=false)
    {
        // Pertanggal
        for($i=1;$i<=31;$i++) {
            $tanggal_a[] = "SUM(CASE WHEN EXTRACT(DAY FROM batch.rekappenjualanperitemmonthly.tglproforma) = $i THEN batch.rekappenjualanperitemmonthly.subtotalnettojual ELSE 0 END) AS total_$i ";
            $tanggal_b[] = "achievement.total_$i";
        }
        $pertanggal_a = implode(', ', $tanggal_a);
        $pertanggal_b = implode(', ', $tanggal_b);

        // Query
        $query = "
            SELECT
                hr.sales.kodesales,
                hr.sales.namakaryawan,
                COUNT(DISTINCT mstr.tokohakakses.tokoid) AS ro,
                COALESCE(kunjungansales.target_oa_p,0) AS target_oa_p,
                COALESCE(kunjungansales.target_oa_np,0) AS target_oa_np,
                COALESCE(kunjungansales.target_oa_total,0) AS target_oa_total,
                COALESCE(orderpenjualan.realisasi_oa_p,0) AS realisasi_oa_p,
                COALESCE(orderpenjualan.realisasi_oa_np,0) AS realisasi_oa_np,
                COALESCE(orderpenjualan.realisasi_oa_total,0) AS realisasi_oa_total,
                COALESCE(ROUND((orderpenjualan.realisasi_oa_total/kunjungansales.target_oa_total)*100,2),0) AS realisasi_oa_persen,
                COALESCE(efektif.efektif_oa_p,0) AS efektif_oa_p,
                COALESCE(efektif.efektif_oa_np,0) AS efektif_oa_np,
                COALESCE(efektif.efektif_oa_p,0)+COALESCE(efektif.efektif_oa_np,0) AS efektif_oa_total,
                COALESCE(ROUND((efektif.efektif_oa_p+efektif.efektif_oa_np/kunjungansales.target_oa_total)*100,2),0) AS efektif_oa_persen,
                COALESCE(sku.sku_fb2,0) as sku_fb2,
                COALESCE(sku.sku_fb4,0) as sku_fb4,
                COALESCE(sku.sku_fe2,0) as sku_fe2,
                COALESCE(sku.sku_fe4,0) as sku_fe4,
                COALESCE(sku.sku_fb2+sku.sku_fb4+sku.sku_fe2+sku.sku_fe4,0) as sku_total,
                COALESCE(targetsales.targetfb,0) as targetfb,
                COALESCE(targetsales.targetfe,0) as targetfe,
                achievement.achievement_b_rp,
                achievement.achievement_e_rp,
                COALESCE(ROUND((achievement.achievement_b_rp::numeric/targetsales.targetfb::numeric)*100,2),0) AS achievement_b_persen,
                COALESCE(ROUND((achievement.achievement_e_rp::numeric/targetsales.targetfe::numeric)*100,2),0) AS achievement_e_persen,
                $pertanggal_b
            FROM hr.sales
            LEFT JOIN mstr.tokohakakses ON mstr.tokohakakses.karyawanidsalesman = hr.sales.id
            LEFT JOIN (
                SELECT DISTINCT ON (pj.kunjungansales.karyawanidsalesman) pj.kunjungansales.karyawanidsalesman,
                SUM(CASE WHEN (mstr.tokopareto.tokoid IS NOT NULL) THEN 1 ELSE 0 END) AS target_oa_p,
                SUM(CASE WHEN (mstr.tokopareto.tokoid IS NULL) THEN 1 ELSE 0 END) AS target_oa_np,
                COUNT(pj.kunjungansales.tokoid)::numeric as target_oa_total
                FROM pj.kunjungansales
                LEFT JOIN mstr.tokopareto ON mstr.tokopareto.tokoid = pj.kunjungansales.tokoid AND mstr.tokopareto.periode = '$tahun$bulan'
                WHERE pj.kunjungansales.recordownerid = $subcabang
                AND EXTRACT(YEAR FROM pj.kunjungansales.tglkunjung) = '$tahun'
                AND EXTRACT(MONTH FROM pj.kunjungansales.tglkunjung) = '$bulan'
                GROUP BY pj.kunjungansales.karyawanidsalesman
            ) AS kunjungansales
            ON kunjungansales.karyawanidsalesman = hr.sales.id 
            LEFT JOIN (
                SELECT DISTINCT ON (pj.orderpenjualan.karyawanidsalesman) pj.orderpenjualan.karyawanidsalesman,
                SUM(CASE WHEN (mstr.tokopareto.tokoid IS NOT NULL) THEN 1 ELSE 0 END) AS realisasi_oa_p,
                SUM(CASE WHEN (mstr.tokopareto.tokoid IS NULL) THEN 1 ELSE 0 END) AS realisasi_oa_np,
                COUNT(pj.orderpenjualan.tokoid)::numeric as realisasi_oa_total
                FROM pj.orderpenjualan
                LEFT JOIN mstr.tokopareto ON mstr.tokopareto.tokoid = pj.orderpenjualan.tokoid AND mstr.tokopareto.periode = '$tahun$bulan'
                WHERE pj.orderpenjualan.recordownerid = $subcabang
                AND EXTRACT(YEAR FROM pj.orderpenjualan.tglpickinglist) = '$tahun'
                AND EXTRACT(MONTH FROM pj.orderpenjualan.tglpickinglist) = '$bulan'
                GROUP BY pj.orderpenjualan.karyawanidsalesman
            ) AS orderpenjualan
            ON orderpenjualan.karyawanidsalesman = hr.sales.id 
            LEFT JOIN (
                SELECT DISTINCT ON (pj.kunjungansales.karyawanidsalesman) pj.kunjungansales.karyawanidsalesman,
                SUM(CASE WHEN (mstr.tokopareto.tokoid IS NOT NULL AND EXISTS(
                    SELECT 1 FROM pj.orderpenjualan
                    WHERE pj.orderpenjualan.tokoid = pj.kunjungansales.tokoid
                    AND pj.orderpenjualan.recordownerid = $subcabang
                    AND EXTRACT(YEAR FROM pj.orderpenjualan.tglpickinglist) = '$tahun'
                    AND EXTRACT(MONTH FROM pj.orderpenjualan.tglpickinglist) = '$bulan')
                    ) THEN 1 ELSE 0 END) AS efektif_oa_p,
                SUM(CASE WHEN (mstr.tokopareto.tokoid IS NULL AND EXISTS(
                    SELECT 1 FROM pj.orderpenjualan
                    WHERE pj.orderpenjualan.tokoid = pj.kunjungansales.tokoid
                    AND pj.orderpenjualan.recordownerid = $subcabang
                    AND EXTRACT(YEAR FROM pj.orderpenjualan.tglpickinglist) = '$tahun'
                    AND EXTRACT(MONTH FROM pj.orderpenjualan.tglpickinglist) = '$bulan')
                    ) THEN 1 ELSE 0 END) AS efektif_oa_np
                FROM pj.kunjungansales
                LEFT JOIN mstr.tokopareto ON mstr.tokopareto.tokoid = pj.kunjungansales.tokoid AND mstr.tokopareto.periode = '$tahun$bulan'
                WHERE pj.kunjungansales.recordownerid = $subcabang
                AND EXTRACT(YEAR FROM pj.kunjungansales.tglkunjung) = '$tahun'
                AND EXTRACT(MONTH FROM pj.kunjungansales.tglkunjung) = '$bulan'
                GROUP BY pj.kunjungansales.karyawanidsalesman
            ) AS efektif
            ON efektif.karyawanidsalesman = hr.sales.id 
            LEFT JOIN (
                SELECT karyawanidsalesman,
                SUM(CASE WHEN LEFT(mstr.stock.kodebarang,3) IN ('FB2') THEN batch.rekappenjualanperitemmonthly.qty ELSE 0 END) AS sku_fb2,
                SUM(CASE WHEN LEFT(mstr.stock.kodebarang,3) IN ('FB4') THEN batch.rekappenjualanperitemmonthly.qty ELSE 0 END) AS sku_fb4,
                SUM(CASE WHEN LEFT(mstr.stock.kodebarang,3) IN ('FE2') THEN batch.rekappenjualanperitemmonthly.qty ELSE 0 END) AS sku_fe2,
                SUM(CASE WHEN LEFT(mstr.stock.kodebarang,3) IN ('FE4') THEN batch.rekappenjualanperitemmonthly.qty ELSE 0 END) AS sku_fe4
                FROM batch.rekappenjualanperitemmonthly
                JOIN mstr.stock ON mstr.stock.id = batch.rekappenjualanperitemmonthly.stockid
                WHERE batch.rekappenjualanperitemmonthly.recordownerid = $subcabang
                AND EXTRACT(YEAR FROM batch.rekappenjualanperitemmonthly.tglproforma) = '$tahun'
                AND EXTRACT(MONTH FROM batch.rekappenjualanperitemmonthly.tglproforma) = '$bulan'
                AND batch.rekappenjualanperitemmonthly.jenis = '1'
                GROUP BY karyawanidsalesman
            ) AS sku
            ON sku.karyawanidsalesman = hr.sales.id 
            LEFT JOIN (
                SELECT karyawansalesid as karyawanidsalesman, targetfb, targetfe, targetall
                FROM batch.targetsalescabangmonthly
                WHERE batch.targetsalescabangmonthly.recordownerid = $subcabang
                AND EXTRACT(YEAR FROM batch.targetsalescabangmonthly.periode) = '$tahun'
                AND EXTRACT(MONTH FROM batch.targetsalescabangmonthly.periode) = '$bulan'
            ) AS targetsales
            ON targetsales.karyawanidsalesman = hr.sales.id 
            LEFT JOIN (
                SELECT karyawanidsalesman,
                SUM(CASE WHEN LEFT(mstr.stock.kodebarang,2) NOT IN ('FE') THEN batch.rekappenjualanperitemmonthly.subtotalnettojual ELSE 0 END) AS achievement_b_rp,
                SUM(CASE WHEN LEFT(mstr.stock.kodebarang,2) IN ('FE') THEN batch.rekappenjualanperitemmonthly.subtotalnettojual ELSE 0 END) AS achievement_e_rp,
                $pertanggal_a
                FROM batch.rekappenjualanperitemmonthly
                JOIN mstr.stock ON mstr.stock.id = batch.rekappenjualanperitemmonthly.stockid
                WHERE batch.rekappenjualanperitemmonthly.recordownerid = $subcabang
                AND EXTRACT(YEAR FROM batch.rekappenjualanperitemmonthly.tglproforma) = '$tahun'
                AND EXTRACT(MONTH FROM batch.rekappenjualanperitemmonthly.tglproforma) = '$bulan'
                GROUP BY karyawanidsalesman
            ) AS achievement
            ON achievement.karyawanidsalesman = hr.sales.id 
            WHERE hr.sales.recordownerid = $subcabang
            GROUP BY
            hr.sales.kodesales,
            hr.sales.namakaryawan,
            kunjungansales.target_oa_p,
            kunjungansales.target_oa_np,
            kunjungansales.target_oa_total,
            orderpenjualan.realisasi_oa_p,
            orderpenjualan.realisasi_oa_np,
            orderpenjualan.realisasi_oa_total,
            efektif.efektif_oa_p,
            efektif.efektif_oa_np,
            sku.sku_fb2,
            sku.sku_fb4,
            sku.sku_fe2,
            sku.sku_fe4,
            targetsales.targetfb,
            targetsales.targetfe,
            achievement.achievement_b_rp,
            achievement.achievement_e_rp,
            $pertanggal_b
        ";

        // Jika butuh paginasi
        if($wtotal){
            $total  = DB::connection('pgsql_report')->select(DB::raw("SELECT COUNT(1) as total FROM ($query) temptable"));
            $result = DB::connection('pgsql_report')->select(DB::raw($query." LIMIT ".$limit." OFFSET ".$offset));

            return ['total'=>$total[0]->total,'result'=>$result];
        }else{
            $result = DB::connection('pgsql_report')->select(DB::raw($query));
            return $result;
       }
    }

    public function git(Request $req)
    {
        return view('laporan.penjualan.git_index');
    }

    public function gitPreview(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.git')) {
            return redirect('/')->with('message',['status'=>'danger','desc'=>'Tidak Memiliki Hak Akses!']);
        }

        $this->insert_reportlog($req->session()->get('subcabang'),auth()->user()->id,'GOOD IN TRANSIT');
        $type    = $req->type;
        $periode = $req->periode;
        $cabang  = $req->cabang;
        // $periode = Carbon::parse($req->periode)->toDateString();

        return view('laporan.penjualan.git_preview',['type'=>$type,'periode'=>$periode,'cabang'=>$cabang]);
    }

    public function gitData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.git')) {
            return response()->json(['draw'=>$req->draw, 'recordsTotal'=>0, 'recordsFiltered'=>0, 'data'=>[],]);
        }

        $cabang  = $req->cabang;
        $type    = ($req->type) ? $req->type : 'all';
        $periode = Carbon::parse($req->periode)->toDateString();
        $limit   = $req->length;
        $offset  = $req->start;

        // need moar execution time
        ini_set('max_execution_time',300);

        // Result
        $total  = DB::connection('pgsql_report')->select(DB::raw("SELECT COUNT(1) as total FROM report.rsp_goodsintransit('$periode','',$cabang,'all')"));
        $result = DB::connection('pgsql_report')->select(DB::raw("SELECT * FROM report.rsp_goodsintransit('$periode','',$cabang,'$type') ORDER BY tglproforma ASC  LIMIT $limit OFFSET $offset"));

        foreach ($result as $res) {
            $res->tglproforma   = ($res->tglproforma) ? Carbon::parse($res->tglproforma)->format('d-m-Y') : $res->tglproforma;
            $res->tglterima     = ($res->tglterima) ? Carbon::parse($res->tglterima)->format('d-m-Y') : $res->tglterima;
            $res->tglkirim      = ($res->tglkirim) ? Carbon::parse($res->tglkirim)->format('d-m-Y') : $res->tglkirim;
            $res->tglterimatoko = ($res->tglterimatoko) ? Carbon::parse($res->tglterimatoko)->format('d-m-Y') : $res->tglterimatoko;
            $res->tglsj         = ($res->tglsj) ? Carbon::parse($res->tglsj)->format('d-m-Y') : $res->tglsj;
            $res->tglserahterimachecker    = ($res->tglserahterimachecker) ? Carbon::parse($res->tglserahterimachecker)->format('d-m-Y') : $res->tglserahterimachecker;
            $res->tglrencanakeluarexpedisi = ($res->tglrencanakeluarexpedisi) ? Carbon::parse($res->tglrencanakeluarexpedisi)->format('d-m-Y') : $res->tglrencanakeluarexpedisi;
        }

        return response()->json([
          'draw'            => $req->draw,
          'recordsTotal'    => $total[0]->total,
          'recordsFiltered' => $total[0]->total,
          'data'            => $result,
        ]);
    }

    public function gitExcel(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.git')) {
            return null;
        }

        $cabang  = $req->cabang;
        $type    = ($req->type) ? $req->type : 'all';
        $periode = Carbon::parse($req->periode)->toDateString();

        // need moar execution time
        ini_set('max_execution_time',300);

        // Result
        $data = DB::connection('pgsql_report')->select(DB::raw("SELECT * FROM report.rsp_goodsintransit('$periode','',$cabang,'$type') ORDER BY tglproforma ASC"));
        $count = count($data);

        // Xlsx Var
        $sheet  = str_slug("Laporan Good In Transit");
        $file   = $sheet."-".uniqid().".xlsx";
        $folder = storage_path('excel/exports');
        $username  = strtoupper(auth()->user()->username);
        $tanggal   = Carbon::now()->format('d/m/Y');

        $header_data   = [
            'NO',
            'C1',
            'C2',
            'TGL PROFORMA',
            'NO NOTA',
            'TIPE TRANSAKSI',
            'TGL TERIMA',
            'JW',
            'KODE SALES',
            'NAMA TOKO',
            'KOTA',
            'IDWIL',
            'RP NET',
            'LAMA KIRIM',
            'TGL KIRIM',
            'PREDIKSI OVERDUE',
            'TGL TERIMA TOKO',
            'TGL SJ',
            'TGL SERAH TERIMA CHECKER',
            'TGL RENCANA KELUAR EXPEDISI',
            'STS KIRIM',
            'KETERANGAN PENDING',
        ];
        $data_format = [
            'GENERAL',
            'GENERAL',
            'GENERAL',
            'DD-MM-YYYY',
            'GENERAL',
            'GENERAL',
            'GENERAL',
            'GENERAL',
            'GENERAL',
            'GENERAL',
            'GENERAL',
            'GENERAL',
            '#,##0',
            'GENERAL',
            'DD-MM-YYYY',
            'GENERAL',
            'DD-MM-YYYY',
            'DD-MM-YYYY',
            'DD-MM-YYYY',
            'DD-MM-YYYY',
            'GENERAL',
            'GENERAL',
        ];

        $col_width = [
            6,10,10,
            18,18,18,18,8,13,30,20,10,18,
            12,11,20,19,11,29,32,72,25,
        ];

        // Init Xlsx
        $writer = new XLSXWriter();
        $writer->setAuthor($username);
        $writer->setTempDir($folder);

        // Write Sheet Header
        $writer->writeSheetHeader($sheet,$this->header_format,['suppress_row'=>true,'widths'=>$col_width]);
        $writer->writeSheetRow($sheet,["LAPORAN GOOD IN TRANSIT"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["Periode : ".$req->periode],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["Cabang : $cabang"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,['']);

        // Write Table Header
        $writer->writeSheetRow($sheet,$header_data,$this->header_style);

        // // Write Table Data
        $n = 1;
        foreach ($data as $row) {
            $value = [
                $n++,
                $row->c1,
                $row->c2,
                $row->tglproforma,
                $row->nonota,
                $row->tipetransaksi,
                $row->tglterima,
                $row->jw,
                $row->kodesales,
                $row->namatoko,
                $row->kota,
                $row->idwil,
                $row->rpnet,
                $row->lamakirim,
                $row->tglkirim,
                $row->ovoverprediksierdue,
                $row->tglterimatoko,
                $row->tglsj,
                $row->tglserahterimachecker,
                $row->tglrencanakeluarexpedisi,
                $row->stskirim,
                $row->keteranganpending,
            ];

            $writer->writeSheetRow($sheet,$value,$this->data_style,$data_format);
        }

        // // Total
        $last = $count+5;
        $total = [
            '', '', '', '', '', '', '', '', '', '', '', '',
            '=SUM(M6:M'.$last.')',
            '', '', '', '', '', '', '', '', '',
        ];
        $writer->writeSheetRow($sheet,$total,['font-style' =>'bold', 'fill'   =>'#dbe5f1', 'border' =>'left,right,top,bottom',],$data_format);

        // Footer
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,[$username.",".(Carbon::now()->format('d/m/Y h:i:s'))],['font-style'=>'italic']);

        // return if write to file complete
        $writer->writeToFile($folder.'/'.$file);
        return asset('storage/excel/exports/'.$file);
    }

    public function gitPdf(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('laporan.penjualan.git')) {
            return null;
        }

        $cabang  = $req->cabang;
        $type    = ($req->type) ? $req->type : 'all';
        $periode = Carbon::parse($req->periode)->toDateString();

        // need moar execution time
        ini_set('max_execution_time',300);

        // Result
        $data = DB::connection('pgsql_report')->select(DB::raw("SELECT * FROM report.rsp_goodsintransit('$periode','',$cabang,'$type') ORDER BY tglproforma ASC"));
        $count = count($data);
        $timestamp = Carbon::now()->format('dmYHis');

        $config = [
            'mode'                 => '',
            'format'               => 'A4-L',
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 20,
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

        $pdf = PDF::loadView('laporan.penjualan.git_pdf',
                array(
                    "periode" => $periode,
                    "data" => $data,
                    "cabang" => SubCabang::find($cabang)->kodesubcabang,
                    ),[],$config);

        $pdf->save(storage_path('excel/exports') . "/LaporanGIT_" . $timestamp . '.pdf');

        return asset('storage/excel/exports/LaporanGIT_' . $timestamp . '.pdf');
    }

}