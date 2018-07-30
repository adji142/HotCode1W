<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use XLSXWriter;
use DB;

use App\Models\OrderPenjualan;
use App\Models\KartuPiutang;
use App\Models\Plafon;
use App\Models\Toko;
use App\Models\ApprovalMgmt; // ambil dari model yang sudah saja

//ditambahkah halim
use App\Models\ApprovalManagement;
use App\Models\ApprovalManagementDetail;
use App\Models\ApprovalModule;
use App\Models\Role;
use App\Models\NotaPenjualan;
use App\Models\NotaPenjualanDetail;
use App\Models\ReturPenjualan;
use App\Models\ReturPenjualanDetail;
use App\Models\KPDetailKonsol;
use App\Models\KPKonsol;
use APp\Models\Numerator;

class AccpiutangController extends Controller
{
    protected $original_column = array(
        1 => "pj.orderpenjualan.tglpickinglist",
        2 => "pj.orderpenjualan.nopickinglist",
        3 => "mstr.toko.namatoko",
        4 => "pj.orderpenjualan.tglprintpickinglist",
        5 => "pj.orderpenjualan.noaccpiutang",
        6 => "pj.orderpenjualan.rpaccpiutang",
    );

    public function index()
    {
    	return view('transaksi.accpiutang.index');
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('notapenjualan.index')) {
            return response()->json([
                'draw'            => $req->draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
            ]);
        }

        // jika lolos, tampilkan data
        $req->session()->put('tglmulai', $req->tglmulai);
        $req->session()->put('tglselesai', $req->tglselesai);

        $filter_count = 0;
        $empty_filter = 0;
        $columns = array(
            0 => "pj.orderpenjualan.tglpickinglist",
            1 => "pj.orderpenjualan.nopickinglist",
            2 => "mstr.toko.namatoko",
            3 => "pj.orderpenjualan.tglterimapilpiutang",
            4 => "pj.orderpenjualan.noaccpiutang",
            5 => "pj.orderpenjualan.rpaccpiutang",
            6 => "pj.orderpenjualan.id",
            7 => "pj.orderpenjualan.tokoid",
            8 => "detail.status"
        );
        for ($i=1; $i < 7; $i++) {
            if($req->custom_search[$i]['text'] == ''){
                $empty_filter++;
            }
        }
        $accpiutang = OrderPenjualan::selectRaw(collect($columns)->implode(', '));
        $accpiutang->leftJoin('mstr.toko', 'pj.orderpenjualan.tokoid', '=', 'mstr.toko.id');
        $accpiutang->leftJoin("secure.approvalmgmt", function($join){
            $join->on("secure.approvalmgmt.id","=","pj.orderpenjualan.approvalmgmtidkaadm");
            $join->orOn("secure.approvalmgmt.id","=","pj.orderpenjualan.approvalmgmtidkacabang");
            $join->orOn("secure.approvalmgmt.id","=","pj.orderpenjualan.approvalmgmtidpusat11");
        });
        $accpiutang->leftJoin("secure.approvalmgmtdetail as detail", function($join){
            $join->on("detail.approvalmgmtid","=","secure.approvalmgmt.id");
            $join->on("detail.createdon","=",DB::raw("(SELECT MAX(x.createdon) FROM secure.approvalmgmtdetail x WHERE x.approvalmgmtid = detail.approvalmgmtid)"));
        });
        $accpiutang->where("pj.orderpenjualan.omsetsubcabangid",$req->session()->get('subcabang'))->where("pj.orderpenjualan.tglpickinglist",'>=',Carbon::parse($req->tglmulai))->where("pj.orderpenjualan.tglpickinglist",'<=',Carbon::parse($req->tglselesai));
        $total_data = $accpiutang->count();
        if($empty_filter < 6){
            for ($i=1; $i < 7; $i++) {
                if($req->custom_search[$i]['text'] != ''){
                    $index = $i;
                    if($index == 2 || $index == 3 || $index == 5){
                        if($req->custom_search[$i]['filter'] == '='){
                            $accpiutang->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
                        }else{
                            $accpiutang->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
                        }
                    }else{
                        $accpiutang->where($this->original_column[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                    }
                    $filter_count++;
                }
            }
        }
        if($filter_count > 0){
            $filtered_data = $accpiutang->count();
        }else{
            $filtered_data = $total_data;
        }
        if($req->tipe_edit){
            $accpiutang->orderBy('pj.orderpenjualan.lastupdatedon','desc');
        }else{
            if(array_key_exists($req->order[0]['column'], $this->original_column)){
                $accpiutang->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
            }
        }
        if($req->start > $filtered_data){
            $accpiutang->skip(0)->take($req->length);
        }else{
            $accpiutang->skip($req->start)->take($req->length);
        }

        $data = array();
        foreach ($accpiutang->get() as $key => $jalan) {
            $jalan->tglpickinglist = $jalan->tglpickinglist;
            $jalan->rpaccpiutang   = number_format($jalan->rpaccpiutang,0,',','.');
            $data[$key]            = $jalan->toArray();
            $data[$key]['DT_RowId'] = 'gv1_'.$jalan->id;

            if($jalan->tglterimapilpiutang){
                // $isi_acc = $this->statusIsiAcc($jalan);
                $isi_acc = 'isi';
                $terima  = $this->statusTerimaPiL($jalan);
            }else{
                $isi_acc = 'Tidak bisa isi ACC Piutang. Picking List belum diterima. Isi tanggal terima Picking List dahulu.';
                $terima  = 'terima';
            }

            if($jalan->noaccpiutang){
                $isi_acc = 'auth';                
                if ($jalan->status == 'PENGAJUAN'){
                    $isi_acc = "Tidak bisa isi acc piutang. Status Approval Request : PENGAJUAN. Hubungi Manager Anda!";
                }else if($jalan->status == 'APPROVED'){
                    if ($jalan->rpaccpiutang < 1){
                        $isi_acc = 'isi';
                    }
                }
            }

            $data[$key]['terima']  = $terima;
            $data[$key]['isi_acc'] = $isi_acc;
        }
        return response()->json([
            'draw'              => $req->draw,
            'recordsTotal'      => $total_data,
            'recordsFiltered'   => $filtered_data,
            'data'              => $data,
        ]);
    }

    /* di rekarks halim
    public function getDataAppr(Request $req)
    {
        // gunakan permission dari indexnya aja
        // if(!$req->user()->can('notapenjualan.index')) {
        //     return response()->json([
        //         'draw'            => $req->draw,
        //         'recordsTotal'    => 0,
        //         'recordsFiltered' => 0,
        //         'data'            => [],
        //     ]);
        // }

        // jika lolos, tampilkan data
        // $req->session()->put('tglmulai', $req->tglmulai);
        // $req->session()->put('tglselesai', $req->tglselesai);

        $filter_count = 0;
        $empty_filter = 0;
        $columns = array(
            0 => "secure.approvalmgmtmodule.modulename",
            1 => "secure.approvalmgmt.datareportingdetail",
            2 => "secure.approvalmgmt.keterangan",
            3 => "detail.status",
            4 => "secure.approvalmgmt.createdon",
            // 5 => "secure.approvalmgmt.nojawaban",
        );
        // for ($i=1; $i < 7; $i++) {
        //     if($req->custom_search[$i]['text'] == ''){
        //         $empty_filter++;
        //     }
        // }
        $accpiutang = ApprovalMgmt::selectRaw(collect($columns)->implode(', '));
        $accpiutang->join('secure.approvalmgmtdetail as detail', function ($join) {
            $join->on('detail.approvalmgmtid', '=', 'secure.approvalmgmt.id');
            $join->on('detail.createdon', '=',DB::raw('(select max(createdon) from secure.approvalmgmtdetail where approvalmgmtid = detail.approvalmgmtid)'));
        });
        $accpiutang->leftJoin('mstr.subcabang', 'secure.approvalmgmt.recordownerid', '=', 'mstr.subcabang.id');
        $accpiutang->join('secure.approvalmgmtmodule', 'secure.approvalmgmtmodule.id', '=', 'secure.approvalmgmt.moduleid'); 
        $accpiutang->where("secure.approvalmgmt.recordownerid",$req->session()->get('subcabang'));

        $total_data = $accpiutang->count();
        if($empty_filter < 6){
            for ($i=1; $i < 7; $i++) {
                if($req->custom_search[$i]['text'] != ''){
                    $index = $i;
                    if($index == 2 || $index == 3 || $index == 5){
                        if($req->custom_search[$i]['filter'] == '='){
                            $accpiutang->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
                        }else{
                            $accpiutang->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
                        }
                    }else{
                        $accpiutang->where($this->original_column[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                    }
                    $filter_count++;
                }
            }
        }
        if($filter_count > 0){
            $filtered_data = $accpiutang->count();
        }else{
            $filtered_data = $total_data;
        }
        if($req->tipe_edit){
            $accpiutang->orderBy('pj.orderpenjualan.lastupdatedon','desc');
        }else{
            // if(array_key_exists($req->order[0]['column'], $this->original_column)){
            //     $accpiutang->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
            // }
        }
        if($req->start > $filtered_data){
            // $accpiutang->skip(0)->take($req->length);
        }else{
            // $accpiutang->skip($req->start)->take($req->length);
        }

        $data = array();
        foreach ($accpiutang->get() as $key => $jalan) {
            $jalan->tglpickinglist = $jalan->tglpickinglist;
            $jalan->rpaccpiutang   = number_format($jalan->rpaccpiutang,0,',','.');
            $data[$key]            = $jalan->toArray();

            if($jalan->tglterimapilpiutang){
                // $isi_acc = $this->statusIsiAcc($jalan);
                $isi_acc = 'isi';
                $terima  = $this->statusTerimaPiL($jalan);
            }else{
                $isi_acc = 'Tidak bisa proses ACC Piutang. Picking List belum diterima. Isi tanggal terima Picking List dahulu.';
                $terima  = 'terima';
            }

            if($jalan->noaccpiutang){
                $isi_acc = 'auth';
            }
            $data[$key]['terima']  = $terima;
            $data[$key]['isi_acc'] = $isi_acc;
        }
        return response()->json([
            'draw'              => $req->draw,
            'recordsTotal'      => $total_data,
            'recordsFiltered'   => $filtered_data,
            'data'              => $data,
        ]);
    }
    */
    
    public function terimaTglTerimaPiL(Request $req)
    {
        $opj = OrderPenjualan::find($req->id);
        $opj->tglterimapilpiutang = Carbon::now()->toDateString();
        $opj->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function deleteTglTerimaPiL(Request $req)
    {
        $opj = OrderPenjualan::find($req->id);
        $opj->tglterimapilpiutang = null;
        $opj->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function statusTerimaPiL($jalan)
    {
        if($jalan->nota->isEmpty()){
            return 'konfirmasi';
        }else{
            return 'Tidak bisa update ACC Piutang. Picking List sudah jadi Proforma Invoice. Hubungi Manager anda.';
        }
    }

    public function getAccPiutangData(Request $req)
    {
        $opj    = OrderPenjualan::find($req->id);
        $tokoid = $opj->tokoid;
        $plafon = DB::select(DB::raw("SELECT plafon AS total FROM ptg.plafon WHERE tokoid = $tokoid ORDER BY tglaktif DESC LIMIT 1"));
        $plafon = ($plafon) ? $plafon[0]->total : 0;
        // $plafon = $opj->plafon->sortByDesc('tanggal')->isEmpty() ? 0 : $opj->plafon->sortByDesc('tanggal')->first()->plafon;
        $saldopiutang   = $this->cekSaldoPiutang($opj);
        $git            = $this->cekRpGit($opj);
        $pildalamproses = $this->cekPiLDalamProses($opj);
        $sisaplafon     = $plafon-$saldopiutang-$git-$pildalamproses;
        $pickinglist    = $this->cekPickingList($opj);
        $saldooverdue   = $this->cekSaldoOverdue($opj);

        if($sisaplafon < 0){
            $keterangan = 'PLAFON KURANG';
        }else{
            $keterangan = 'PLAFON CUKUP';
        }

        return response()->json([
            'id'             => $req->id,
            'plafon'         => sprintf("%d",$plafon),
            'saldopiutang'   => $saldopiutang,
            'git'            => $git,
            'pildalamproses' => $pildalamproses,
            'sisaplafon'     => $sisaplafon,
            'pickinglist'    => $pickinglist,
            'overdue'        => $saldooverdue,
            'keterangan'     => $keterangan,
            'tglacc'         => Carbon::now()->format('d-m-Y'),
            'pkp'            => strtoupper(auth()->user()->username),
            'totalacc'       => $opj->rpaccpiutang
        ]);
    }

    public function cekOvdKP($tokoid, $min, $max){
        //cek saldo overdue nota piutang berdasarkan toko dan range tanggal server - tanggal jatuh tempo
        $query = "
            SELECT
                kp.id
                ,kp.isarowid
                ,kp.kodetoko
                ,tk.namatoko
                ,kp.notransaksi
                ,kp.tgltransaksi
                ,kp.tgllink
                ,kp.tgljatuhtempo
                ,kpd.rpnota
                ,kpd.rpbayar
            FROM konsol.kp
            INNER JOIN mstr.toko tk ON kp.kodetoko = tk.kodetoko
            LEFT JOIN LATERAL
            (
                SELECT
                        SUM
                        (
                            CASE WHEN
                                (POSITION(skpd.kodetransaksi IN 'PJK # PJT') > 0 OR (skpd.kodetransaksi = 'TAC' AND skpd.debet <> 0))
                                THEN skpd.debet
                                ELSE 0
                            END
                        ) AS rpnota
                        ,SUM
                        (
                            CASE WHEN
                                (POSITION(skpd.kodetransaksi IN 'PJK # PJT') > 0 OR (skpd.kodetransaksi = 'TAC' AND skpd.debet <> 0))
                                THEN 0 
                                ELSE (- 1 * skpd.debet) + skpd.kredit
                            END
                        ) AS rpbayar
                FROM konsol.kpdetail skpd
                WHERE skpd.isaheaderid = kp.isarowid
            ) AS kpd ON true
            WHERE CURRENT_DATE - kp.tgljatuhtempo::DATE BETWEEN :val1 AND :val2
            AND kp.status = 'OPEN'
            AND kpd.rpnota - kpd.rpbayar > 0
            AND tk.id = :tokoid
        ";
        $result = DB::select($query, array(":tokoid" => $tokoid, ":val1" => $min, ":val2" => $max));
        return $result;
    }

    public function cekSaldoKP($tokoid){
        $query = "
            SELECT
                    kp.id
                    ,kp.isarowid
                    ,kp.kodetoko
                    ,tk.namatoko
                    ,kp.notransaksi
                    ,kp.tgltransaksi
                    ,kp.tgllink
                    ,kp.tgljatuhtempo
                    ,kpd.rpnota
                    ,kpd.rpbayar
            FROM konsol.kp
            INNER JOIN mstr.toko tk ON kp.kodetoko = tk.kodetoko
            LEFT JOIN LATERAL
            (
                    SELECT
                            SUM
                            (
                                    CASE WHEN
                                            (POSITION(skpd.kodetransaksi IN 'PJK # PJT') > 0 OR (skpd.kodetransaksi = 'TAC' AND skpd.debet <> 0))
                                            THEN skpd.debet
                                            ELSE 0
                                    END
                            ) AS rpnota
                            ,SUM
                            (
                                CASE WHEN
                                    (POSITION(skpd.kodetransaksi IN 'PJK # PJT') > 0 OR (skpd.kodetransaksi = 'TAC' AND skpd.debet <> 0))
                                    THEN 0 
                                    ELSE (- 1 * skpd.debet) + skpd.kredit
                                END
                            ) AS rpbayar
                    FROM konsol.kpdetail skpd
                    WHERE skpd.isaheaderid = kp.isarowid
            ) AS kpd ON true
            WHERE CURRENT_DATE - kp.tgljatuhtempo::DATE > 60
            AND kp.status = 'OPEN'
            AND kpd.rpnota - kpd.rpbayar > 0
            AND tk.id = :tokoid
            ";
        $result = DB::select($query, array(":tokoid" => $tokoid));
        return $result;
    }

    public function cekGITKP($tokoid){
        //cek Goods In Transit nota penjualan
        $query = "
            SELECT
                np.id, 
                np.tglproforma, 
                np.nonota,
                np.tglnota,
                np.totalnominal,
                tk.kodetoko,
                tk.namatoko
            FROM pj.notapenjualan np
            INNER JOIN mstr.toko tk ON np.tokoid = tk.id
            WHERE np.tokoid = :tokoid AND np.tglnota ISNULL AND CURRENT_DATE > np.tglproforma + INTERVAL '7 day' 
            ORDER BY np.tglnota DESC
        ";
        $result = DB::select($query, array(":tokoid" => $tokoid));
        return $result;
    }

    public function cekRDPKP($tokoid){
        //cek Return Dalam Proses
        $query = "
            SELECT
                rp.id,
                rp.nompr,
                rp.tglmpr,
                tk.kodetoko,
                tk.namatoko
            FROM pj.returpenjualan rp
            INNER JOIN mstr.toko tk ON rp.tokoid = tk.id
            WHERE rp.tokoid = :tokoid AND rp.kartupiutangdetailid ISNULL AND NOW()::DATE > rp.tglmpr + INTERVAL '7 day'
            ORDER BY rp.tglmpr DESC
        ";
        $result = DB::select($query, array(":tokoid" => $tokoid));
        return $result;
    }

    public function cekBGCKP($tokoid){
        /* ????? */
        $query = "
                    SELECT
                        kp.id
                        ,kp.isarowid
                        ,kp.kodetoko
                        ,tk.namatoko
                        ,kp.notransaksi
                        ,kp.tgltransaksi
                        ,kp.tgllink
                        ,kp.tgljatuhtempo
                        ,kpd.rpnota
                        ,COALESCE(kpd.rpbayar,0) AS rpbayar
                    FROM konsol.kp
                    INNER JOIN mstr.toko tk ON kp.kodetoko = tk.kodetoko
                    LEFT JOIN LATERAL(
                            SELECT
                            SUM
                            (
                                CASE WHEN
                                    (POSITION(skpd.kodetransaksi IN 'PJK # PJT') > 0 OR (skpd.kodetransaksi = 'TAC' AND skpd.debet <> 0))
                                    THEN skpd.debet
                                    ELSE 0
                                END
                            ) AS rpnota
                            ,SUM
                            (
                                CASE WHEN
                                    skpd.kodetransaksi = 'BGC' AND skpd.tgljtgiro > kp.tgljatuhtempo + INTERVAL '15 day'
                                    THEN skpd.kredit
                                END
                            ) AS rpbayar
                            FROM konsol.kpdetail skpd
                            WHERE skpd.isaheaderid = kp.isarowid
                    ) AS kpd ON true
                    WHERE kp.kodetoko = :tokoid
                    AND kp.status = 'OPEN'
                    AND COALESCE(kpd.rpnota,0) - COALESCE(kpd.rpbayar,0) > 0
        ";
        $result = DB::select($query, array(":tokoid" => $tokoid));
        return $result;
    }

    public function cekBGCTolak($tokoid){
        $query = "
            SELECT
                    kp.id
                    ,kp.isarowid
                    ,kp.kodetoko
                    ,tk.namatoko
                    ,kp.notransaksi
                    ,kp.tgltransaksi
                    ,kp.tgllink
                    ,kp.tgljatuhtempo
                    ,kpd.rpnota
                    ,kpd.rpbayar
            FROM konsol.kp
            INNER JOIN mstr.toko tk ON kp.kodetoko = tk.kodetoko
            LEFT JOIN LATERAL
            (
                SELECT
                    SUM
                    (
                        CASE WHEN
                                (POSITION(skpd.kodetransaksi IN 'PJK # PJT') > 0 OR (skpd.kodetransaksi = 'TAC' AND skpd.debet <> 0))
                                THEN skpd.debet
                                ELSE 0
                        END
                    ) AS rpnota
                    ,SUM
                    (
                        CASE WHEN
                            (POSITION(skpd.kodetransaksi IN 'PJK # PJT') > 0 OR (skpd.kodetransaksi = 'TAC' AND skpd.debet <> 0))
                            THEN 0 
                            ELSE (- 1 * skpd.debet) + skpd.kredit
                        END
                    ) AS rpbayar
                    ,SUM
                    (
                        CASE WHEN
                            skpd.kodetransaksi = 'BGC' AND skpd.kredit < 0 THEN 1 ELSE 0
                        END
                    ) AS bgc
                FROM konsol.kpdetail skpd
                WHERE skpd.isaheaderid = kp.isarowid
            ) AS kpd ON true
            WHERE kp.status = 'OPEN'
            AND kpd.rpnota - kpd.rpbayar > 0
            AND kpd.bgc > 0
            AND tk.id = :tokoid

        ";
        $result = DB::select($query, array(":tokoid" => $tokoid));
        return $result;
    }

    public function getModuleId($namaModul){
        $query = ApprovalModule::selectRaw("id")
            ->where("modulename", $namaModul)
            ->first();
        return $query->id;
    }

    public function getNoReq(){
        $query = ApprovalManagement::max("secure.approvalmgmt.norequest");
        $intId = 0;
        if ($query){
            $intId = $query;
        }
        return $intId + 1;
    }

    public function getDataAppr(Request $req){
        $columns = array(
            0 => "secure.approvalmgmt.id",
            1 => "secure.approvalmgmt.createdon",
            2 => "secure.approvalmgmt.norequest",
            3 => "secure.approvalmgmtmodule.modulename",
            4 => "detail.status",
            5 => "secure.approvalmgmt.keterangan",
            7 => "secure.approvalmgmt.lastupdatedon"
        );

        $query = ApprovalManagement::selectRaw(collect($columns)->implode(", "))
            ->join("secure.approvalmgmtmodule", "secure.approvalmgmt.moduleid", "secure.approvalmgmtmodule.id")
            ->join('secure.approvalmgmtdetail as detail', function ($join) {
                $join->on('detail.approvalmgmtid', '=', 'secure.approvalmgmt.id');
                $join->on('detail.createdon', '=',DB::raw('(select max(createdon) from secure.approvalmgmtdetail where approvalmgmtid = detail.approvalmgmtid)'));
            })
            ->join('pj.orderpenjualan', function($join){
                $join->on('pj.orderpenjualan.approvalmgmtidkaadm','=','secure.approvalmgmt.id');
                $join->orOn('pj.orderpenjualan.approvalmgmtidkacabang','=','secure.approvalmgmt.id');
                $join->orOn('pj.orderpenjualan.approvalmgmtidpusat11','=','secure.approvalmgmt.id');
            })
            ->where('pj.orderpenjualan.id',$req->id)
            //->whereIn("secure.approvalmgmtmodule.modulename", array("ACCPIUTANG11BGCTOLAK","ACCPIUTANG11OVDNOTA","ACCPIUTANGKACABRDP","ACCPIUTANGKACABGIT","ACCPIUTANGKACABOVDBGC","ACCPIUTANGKACABOVDNOTA","ACCPIUTANGKAADMOVDNOTA"))
            //->whereRaw("secure.approvalmgmt.createdon::date BETWEEN ? AND ?",[Carbon::parse($req->tglmulai)->toDateString(), Carbon::parse($req->tglselesai)->toDateString()])
            ;
            
            
        $data = $query->get();
        // foreach ($query->get() as $key=>$val){
        //     $val->createdon   = ($val->createdon) ? Carbon::parse($val->createdon)->format('d-m-Y') : '';
        //     $val->lastupdatedon   = ($val->lastupdatedon) ? Carbon::parse($val->lastupdatedon)->format('d-m-Y') : '';
        //     $data[$key] = $val->toArray();
        // }
        //return response()->json(array("data"=>$data));
        return json_encode($data);
    }

    public function approvalRequest(Request $req){
        $blnsuccess = false;
        $message = "";
        $blncontinue = true;

        $columns = array(
            0 => "pj.orderpenjualan.id",
            1 => "pj.orderpenjualan.noaccpiutang",
            2 => "pj.orderpenjualan.rpaccpiutang",
            3 => "pj.orderpenjualan.tokoid",
            4 => "detail.status"
        );

        $accpiutang = OrderPenjualan::selectRaw(collect($columns)->implode(', '));
        $accpiutang->leftJoin("secure.approvalmgmt", function($join){
            $join->on("secure.approvalmgmt.id","=","pj.orderpenjualan.approvalmgmtidkaadm");
            $join->orOn("secure.approvalmgmt.id","=","pj.orderpenjualan.approvalmgmtidkacabang");
            $join->orOn("secure.approvalmgmt.id","=","pj.orderpenjualan.approvalmgmtidpusat11");
        });
        $accpiutang->leftJoin("secure.approvalmgmtdetail as detail", function($join){
            $join->on("detail.approvalmgmtid","=","secure.approvalmgmt.id");
            $join->on("detail.createdon","=",DB::raw("(SELECT MAX(x.createdon) FROM secure.approvalmgmtdetail x WHERE x.approvalmgmtid = detail.approvalmgmtid)"));
        });
        $accpiutang->where("pj.orderpenjualan.id","=",$req->id);

        if ($accpiutang->first()->status){
            $blncontinue = false;
            if ($accpiutang->first()->status == "PENGAJUAN"){
                
                $message == "Tidak bisa proses ACC Piutang. Status approval request : PENGAJUAN. Hubungi Manaager Anda!";
            }
        }
        
        if ($blncontinue == false){
            if ($message == ''){
                $blnsuccess = false;
            }else{
                $blnsuccess = true;
            }
        }else{
            
            $rolename = Role::selectRaw("secure.roles.name")
            ->join("secure.roleuser", "secure.roles.id","secure.roleuser.role_id")
            ->where("secure.roleuser.user_id",auth()->user()->id)
            ->where("secure.roles.groupapps","TRADING")
            ->orderBy("secure.roles.name","desc")
            ->get();

        
            $opj = OrderPenjualan::find($req->id);
            $norequest = $this->getNoReq();

            $arrModule = [];
            $arrKeterangan = [];
            $arrReportHeader = [];
            $arrHeader = [];
            $arrDetail = [];

            $opj = OrderPenjualan::find($req->id);

            foreach ($rolename as $role){
                
                if (strtolower($role->name) == "piutang11"){
                    $kpSaldo = $this->cekSaldoKP($opj->tokoid);
                    $arrSaldo = [];
                    $arrSaldoD = [];
                    $blnSaldo = false;
                    foreach($kpSaldo as $row){
                        $arrSaldo[] = [
                            "No. Nota" => $row->notransaksi,
                            "Tgl. Transaksi" => $row->tgltransaksi,
                            "Tgl. JT" => $row->tgljatuhtempo,
                            "Kode Toko" => $row->kodetoko,
                            "Nama Toko" => $row->namatoko,
                            "Rp Nota" => $row->rpnota,
                            "Rp Bayar" => $row->rpbayar,
                            "Diajukan oleh" => strtoupper(auth()->user()->username)
                        ];
                        $saldo_columns = array(
                            0 => "konsol.kpdetail.tgltransaksi",
                            1 => "konsol.kpdetail.kredit"
                        );
                        $d_saldo = KPDetailKonsol::selectRaw(collect($saldo_columns)->implode(", "))
                            ->where("konsol.kpdetail.isaheaderid",$row->isarowid)
                            ->whereNotIn("konsol.kpdetail.kodetransaksi", array("PJK","PJT","TAC"))
                            ->get();
                        foreach($d_saldo as $data){
                            $arrSaldoD[] = [
                                "Tanggal. Bayar" => $data->tgltransaksi,
                                "Jumlah Bayar" => $data->kredit
                            ];
                        }
                        $blnSaldo = true;
                    }
                    if ($blnSaldo == true){
                        $arrModule[] = "ACCPIUTANG11OVDNOTA";
                        $arrKeterangan[] = "PENGAJUAN ACC PIUTANG";
                        $arrReportHeader[] = ["header"=>"Data Kartu Piutang","detail"=>"Data pembayaran Kartu piutang"];
                        $arrHeader[] = $arrSaldo;
                        $arrDetail[] = $arrSaldoD;
                        break;
                    }

                    $kpTolak = $this->cekBGCTolak($opj->tokoid);
                    $arrTolak = [];
                    $arrTolakD = [];
                    $blnTolak = false;
                    foreach($kpTolak as $row){
                        $arrTolak[] = [
                            "No. Nota" => $row->notransaksi,
                            "Tgl. Transaksi" => $row->tgltransaksi,
                            "Tgl. JT" => $row->tgljatuhtempo,
                            "Kode Toko" => $row->kodetoko,
                            "Nama Toko" => $row->namatoko,
                            "Rp Nota" => $row->rpnota,
                            "Rp Bayar" => $row->rpbayar,
                            "Diajukan oleh" => strtoupper(auth()->user()->username)
                        ];
                        $tolak_columns = array(
                            0 => "konsol.kpdetail.tgltransaksi",
                            1 => "konsol.kpdetail.kredit"
                        );
                        $d_tolak = KPDetailKonsol::selectRaw(collect($tolak_columns)->implode(", "))
                            ->where("konsol.kpdetail.isaheaderid",$row->isarowid)
                            ->whereNotIn("konsol.kpdetail.kodetransaksi", array("PJK","PJT","TAC"))
                            ->get();
                        foreach($d_tolak as $data){
                            $arrTolakD[] = [
                                "Tanggal. Bayar" => $data->tgltransaksi,
                                "Jumlah Bayar" => $data->kredit
                            ];
                        }
                        $blnTolak = true;
                    }
                    if ($blnTolak == true){
                        $arrModule[] = "ACCPIUTANG11BGCTOLAK";
                        $arrKeterangan[] = "PENGAJUAN ACC PIUTANG";
                        $arrReportHeader[] = ["header"=>"Data Kartu Piutang","detail"=>"Data pembayaran Kartu piutang"];
                        $arrHeader[] = $arrTolak;
                        $arrDetail[] = $arrTolakD;
                        break;
                    }
                }else if (strtolower($role->name == "ka.cab")){
                    $kpCab = $this->cekOvdKP($opj->tokoid,30,60);
                    $arrCab = [];
                    $arrCabD = [];
                    $blnCab = false;
                    foreach ($kpCab as $row){
                        $arrCab[] = [
                            "No. Nota" => $row->notransaksi,
                            "Tgl. Transaksi" => $row->tgltransaksi,
                            "Tgl. JT" => $row->tgljatuhtempo,
                            "Kode Toko" => $row->kodetoko,
                            "Nama Toko" => $row->namatoko,
                            "Rp Nota" => $row->rpnota,
                            "Rp Bayar" => $row->rpbayar,
                            "Diajukan oleh" => strtoupper(auth()->user()->username)
                        ];
                        $cab_columns = array(
                            0 => "konsol.kpdetail.tgltransaksi",
                            1 => "konsol.kpdetail.kredit"
                        );
                        $d_cab = KPDetailKonsol::selectRaw(collect($cab_columns)->implode(", "))
                            ->where("konsol.kpdetail.isaheaderid",$row->isarowid)
                            ->whereNotIn("konsol.kpdetail.kodetransaksi", array("PJK","PJT","TAC"))
                            ->get();

                        foreach($d_cab as $data){
                            $arrCabD[] = [
                                "Tanggal. Bayar" => $data->tgltransaksi,
                                "Jumlah Bayar" => $data->kredit
                            ];
                        }

                        $blnCab = true;
                    }
                    if ($blnCab == true){
                        $arrModule[] = "ACCPIUTANGKACABOVDNOTA";
                        $arrKeterangan[] = "PENGAJUAN ACC PIUTANG";
                        $arrReportHeader[] = ["header"=>"Data Kartu Piutang","detail"=>"Data pembayaran Kartu piutang"];
                        $arrHeader[] = $arrCab;
                        $arrDetail[] = $arrCabD;
                        break;
                    }
                    
                    $kpGit = $this->cekBGCKP($opj->tokoid);
                    $arrGit = [];
                    $arrGitD = [];
                    $blnGit = false;
                    foreach ($kpGit as $row){
                        $arrGit[] = [
                            "No Nota"=>$row->$nonota,
                            "Tgl Nota"=>$row->$tglnota,
                            "Tgl Proforma"=>$row->tglproforma,
                            "Kode Toko"=>$row->kodetoko,
                            "Nama Toko"=>$row->namatoko,
                            "Total Nominal"=>$row->totalnominal,
                            "Diajukan oleh" => strtoupper(auth()->user()->username)
                        ];
                        $git_columns = array(
                            0 => "mstr.stock.kodebarang",
                            1 => "mstr.stock.namabarang",
                            2 => "mstr.stock.satuan",
                            3 => "pj.notapenjualandetail.qtynota",
                            4 => "pj.notapenjualandetai.hrgsatuannetto"
                        );
                        $d_git = NotaPenjualanDetail::selectRaw(collect($git_columns)->implode(", "))
                            ->join("mstr.stock","pj.notapenjualandetail.stockid","mstr.stock.id")
                            ->where("pj.notapenjualandetai.notapenjualanid", $row->id)
                            ->get();
                        foreach($d_git as $data){
                            $arrGitD[] = [
                                "Kode Barang" => $data->kodebarang,
                                "Nama Barang" => $data->namabarang,
                                "Satuan" => $data->satuan,
                                "Qty" => $data->qtynota,
                                "Harga Sat. Netto" => $data->hrgsatuannetto
                            ];
                        }
                        $blnGit = true;
                    }
                    if ($blnGit == true){
                        $arrModule[] = "ACCPIUTANGKACABGIT";
                        $arrKeterangan[] = "PENGAJUAN ACC PIUTANG";
                        $arrReportHeader[] = ["header"=>"Data nota penjualan","detail"=>"Data nota penjualan detail"];
                        $arrHeader[] = $arrGit;
                        $arrDetail[] = $arrGitD;
                        break;
                    }

                    // RDP
                    $kpRdp = $this->cekRDPKP($opj->tokoid);
                    $arrRdp = [];
                    $arrRdpD = [];
                    $blnRdp = false;
                    foreach ($kpRdp as $row){
                        $arrRdp[] = [
                            "No MPR"=>$row->nompr,
                            "Tgl MPR"=>$row->tglmpr,
                            "Kode Toko"=>$row->kodetoko,
                            "Nama Toko"=>$row->namatoko,
                            "Diajukan oleh" => strtoupper(auth()->user()->username)
                        ];
                        $rdp_columns = array(
                            0 => "mstr.stock.kodebarang",
                            1 => "mstr.stock.namabarang",
                            2 => "mstr.stock.satuan",
                            3 => "pj.returpenjualandetail.qtympr",
                            4 => "pj.returpenjualandetail.hrgsatuannetto"
                        );
                        $d_rdp = ReturPenjualanDetail::selectRaw(collect($rdp_columns)->implode(", "))
                            ->join("mstr.stock","pj.returpenjualandetail.stockid","mstr.stock.id")
                            ->where("pj.returpenjualandetail.returpenjualanid",$row->id)
                            ->get();
                        foreach ($d_rdp as $data){
                            $arrRdpD[] = [
                                "Kode Barang" => $data->kodebarang,
                                "Nama Barang" => $data->namabarang,
                                "Satuan" => $data->satuan,
                                "Qty" => $data->qtympr,
                                "Harga Sat. Netto" => $data->hrgsatuannetto
                            ];
                        }
                        $blnRdp = true;
                    }
                    if ($blnRdp == true){
                        $arrModule[] = "ACCPIUTANGKACABRDP";
                        $arrKeterangan[] = "PENGAJUAN ACC PIUTANG";
                        $arrReportHeader[] = ["header"=>"Data retur penjualan","detail"=>"Data retur penjualan detail"];
                        $arrHeader[] = $arrRdp;
                        $arrDetail[] = $arrRdpD;
                        break;
                    }

                    // OVERDUE BGC
                    $kpBgc = $this->cekBGCKP($opj->tokoid);
                    $arrBgc = [];
                    $arrBgcD = [];
                    $blnBgc = false;
                    foreach ($kpBgc as $row){
                        $arrBgc[] = [
                            "No. Nota" => $row->notransaksi,
                            "Tgl. Transaksi" => $row->tgltransaksi,
                            "Tgl. JT" => $row->tgljatuhtempo,
                            "Kode Toko" => $row->kodetoko,
                            "Nama Toko" => $row->namatoko,
                            "Diajukan oleh" => strtoupper(auth()->user()->username)
                        ];
                        $bgc_columns = array(
                            0 => "konsol.kpdetail.tgltransaksi",
                            1 => "konsol.kpdetail.kredit"
                        );
                        $d_bgc = KPDetailKonsol::selectRaw(collect($bgc_columns)->implode(", "))
                            ->where("konsol.kpdetail.isaheaderid",$row->isarowid)
                            ->where("konsol.kpdetail.kodetransaksi", "BGC")
                            ->get();
                        foreach($d_bgc as $data){
                            $arrBgcD[] = [
                                "Tanggal. Bayar" => $data->tgltransaksi,
                                "Jumlah Bayar" => $data->kredit
                            ];
                        }
                        $blnBgc = true;
                    }

                    if ($blnBgc == true){
                        $arrModule[] = "ACCPIUTANGKACABOVDBGC";
                        $arrKeterangan[] = "PENGAJUAN ACC PIUTANG";
                        $arrReportHeader[] = ["header"=>"Data Kartu Piutang","detail"=>"Data pembayaran Kartu piutang"];
                        $arrHeader[] = $arrBgc;
                        $arrDetail[] = $arrBgcD;
                        break;
                    }
                }else if (strtolower($role->name == "ka.adm")){
                    $kpAdm = $this->cekOvdKP($opj->tokoid,1,29);
                    $arrAdm = [];
                    $arrAdmD = [];
                    $blnAdm = false;
                    foreach($kpAdm as $row){
                        $arrAdm[] = [
                            "No. Nota" => $row->notransaksi,
                            "Tgl. Transaksi" => $row->tgltransaksi,
                            "Tgl. JT" => $row->tgljatuhtempo,
                            "Kode Toko" => $row->kodetoko,
                            "Nama Toko" => $row->namatoko,
                            "Rp Nota" => $row->rpnota,
                            "Rp Bayar" => $row->rpbayar,
                            "Diajukan oleh" => strtoupper(auth()->user()->username)
                        ];
                        $adm_columns = array(
                            0 => "konsol.kpdetail.tgltransaksi",
                            1 => "konsol.kpdetail.kredit"
                        );
                        $d_adm = KPDetailKonsol::selectRaw(collect($adm_columns)->implode(", "))
                            ->where("konsol.kpdetail.isaheaderid",$row->isarowid)
                            ->whereNotIn("konsol.kpdetail.kodetransaksi", array("PJK","PJT","TAC"))
                            ->get();
                        foreach($d_adm as $data){
                            $arrAdmD[] = [
                                "Tanggal. Bayar" => $data->tgltransaksi,
                                "Jumlah Bayar" => $data->kredit
                            ];
                        }
                        $blnAdm = true;
                    }
                    if ($blnAdm == true){
                        $arrModule[] = "ACCPIUTANGKAADMOVDNOTA";
                        $arrKeterangan[] = "PENGAJUAN ACC PIUTANG";
                        $arrReportHeader[] = ["header"=>"Data Kartu Piutang","detail"=>"Data pembayaran Kartu piutang"];
                        $arrHeader[] = $arrAdm;
                        $arrDetail[] = $arrAdmD;
                        break;
                    }
                }
            }

            try{

                for($i=0; $i<count($arrModule); $i++){ //sekarang diubang dipriotaskan, jadi loopingnya tetap 1 data saja
                    $modul = $this->getModuleId($arrModule[$i]);
                    $keterangan = $arrKeterangan[$i];
                    $header = $arrReportHeader[$i];
                    $detail = ["header"=>$arrHeader[$i],"detail"=>$arrDetail[$i]];
        
                    $approval = new ApprovalManagement();
                    $approval->moduleid = $modul;
                    $approval->recordownerid = $opj->recordownerid;
                    $approval->keterangan = $keterangan;
                    $approval->datareportingheader = json_encode($header);
                    $approval->datareportingdetail = json_encode($detail);
                    $approval->closed = 0;
                    $approval->createdby = strtoupper($req->user()->username);
                    $approval->lastupdatedby = strtoupper($req->user()->username);
                    $approval->norequest = str_pad($norequest,10,'0',STR_PAD_LEFT);
                    $approval->save();
        
                    $approvaldetail = new ApprovalManagementDetail;
                    $approvaldetail->approvalmgmtid = $approval->id;
                    $approvaldetail->status         = "PENGAJUAN";
                    $approvaldetail->username       = strtoupper($req->user()->username);
                    $approvaldetail->keterangan     = 'PENGAJUAN ACC PIUTANG';
                    $approvaldetail->createdby      = strtoupper($req->user()->username);
                    $approvaldetail->lastupdatedby  = strtoupper($req->user()->username);
                    $approvaldetail->save();
                    
                    $orderapproval = OrderPenjualan::find($req->id);
                    $orderapproval->approvalmgmtidkaadm = null; //approvalmgmtidkaadm
                    $orderapproval->approvalmgmtidkacabang = null;
                    $orderapproval->approvalmgmtidpusat11 = null;
                    $orderapproval->save();

                    $order1 = OrderPenjualan::find($req->id);
                    if ($arrModule[$i] == "ACCPIUTANGKAADMOVDNOTA"){
                        $order1->approvalmgmtidkaadm = $approval->id;
                    }
                    if ($arrModule[$i] == "ACCPIUTANGKACABOVDNOTA" || $arrModule[$i] == 'ACCPIUTANGKACABOVDBGC' || $arrModule[$i] == 'ACCPIUTANGKACABGIT' || $arrModule[$i] == 'ACCPIUTANGKACABRDP'){
                        $order1->approvalmgmtidkacabang = $approval->id;
                    }
                    if ($arrModule[$i] == "ACCPIUTANG11OVDNOTA" || $arrModule[$i] == "ACCPIUTANG11BGCTOLAK"){
                        $order1->approvalmgmtidpusat11 = $approval->id;
                    }
                    $order1->save();
                    $norequest += 1;

                }

                if (count($arrModule) > 0){
                    $blnsuccess = true;
                    $counter = OrderPenjualan::find($req->id);
                    $counter->tglaccpiutang = Carbon::now()->toDateString();
                    $counter->noaccpiutang = 'T'.$this->getNextAccNo($req->session()->get('subcabang'),'T');
                    $counter->save();
                }
                
                
            }catch(Exception $ex){
                $message = "error";
                
            }
        }

        

        return response()->json(array("success" => $blnsuccess, "message" => $message));

    }

    public function getCatatanPKP(Request $req){
        $id = $req->id;
        $query = OrderPenjualan::select("pj.orderpenjualan.catatanpkp")->where("pj.orderpenjualan.id",$id)->first();
        return response()->json(array("catatanpkp" => $query->catatanpkp));
    }
    
    public function isiAccPiutangData(Request $req)
    {
        $opj                    = OrderPenjualan::find($req->id);
        $opj->rpsaldopiutang    = $req->acc['rpsaldopiutang'];
        $opj->rpgit             = $req->acc['rpgit'];
        $opj->rpsoaccproses     = $req->acc['rpsoaccprocess'];
        $opj->rpsaldooverdue    = $req->acc['rpsaldooverdue'];
        // $opj->tglaccpiutang     = $req->acc['tglaccpiutang'];
        $opj->tglaccpiutang     = Carbon::parse($req->acc['tglaccpiutang'])->format("Y-m-d");
        $opj->noaccpiutang      = $req->acc['noaccpiutang'];
        $opj->rpaccpiutang      = $req->acc['rpaccpiutang'];
        $opj->karyawanidpkp     = $req->acc['karyawanidpkp'];
        $opj->catatanpembayaran = $req->acc['catatanpembayaran'];
        $opj->save();
        
        return response()->json([
            'success' => true,
        ]);
    }
    
   
    public function updatePkp(Request $request){
        $params = $request->all();
        \DB::beginTransaction();
        try{
            $opj               = OrderPenjualan::find($params['id_orderpenjualan']);
            $opj->catatanpkp   = $params['catatanpkp'];
            $opj->save();
            \DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $opj
            ]);

        }catch(\Exception $e){
            \DB::rollback();
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }

    public function changePilihanAcc(Request $req)
    {
        $opj = OrderPenjualan::find($req->id);
        $nominal_bawah = 0;
        if($req->tipe == 1){
            $nomor   = 'F'.$this->getNextAccNo($req->session()->get('subcabang'),'F');
            $nominal = $this->cekPickingList($opj);
        }elseif($req->tipe == 2){
            $nomor   = 'S'.$this->getNextAccNo($req->session()->get('subcabang'),'S');
            $nominal = 0;
            if($opj->noaccpiutang){
                $nominal_bawah = $this->cekNominalNota($opj);
            }
        }else{
            $nomor   = 'T'.$this->getNextAccNo($req->session()->get('subcabang'),'T');
            $nominal = 0;
        }
        
        return response()->json([
            'nomor'         => $nomor,
            'nominal'       => $nominal,
            'nominal_bawah' => $nominal_bawah,
            'tipe'          => $req->tipe,
        ]);
    }

    public function cekNominalNota($jalan)
    {
        $nominal = 0;
        foreach ($jalan->nota as $nota) {
            if(!$nota->details->isEmpty()){
                foreach ($nota->details as $detail) {
                    $nominal += $detail->qtynota*$detail->hrgsatuannetto;
                }
            }
        }

        return $nominal;
    }

    public function cekSaldoPiutang($jalan)
    {
        // $nominal      = 0;
        // $nominalbayar = 0;
        // foreach ($jalan->kp as $kp) {
        //     $nominal += $kp->nominal;
        //     if(!$kp->details->isEmpty()){
        //         foreach ($kp->details as $kpd) {
        //             $nominalbayar += $kpd->nominalbayar;
        //         }
        //     }
        // }
        // $saldopiutang = $nominal-$nominalbayar;

        $tokoid = $jalan->tokoid;
        $saldopiutang = DB::select(DB::raw("SELECT COALESCE(SUM(nomnota)-SUM(nominalbayar),0) as total FROM ptg.kartupiutang pkp LEFT JOIN (SELECT kartupiutangid, SUM(nomtrans) as nominalbayar FROM ptg.kartupiutangdetail GROUP BY kartupiutangid) pkpd ON pkp.id = pkpd.kartupiutangid WHERE tokoid = $tokoid"));

        return ($saldopiutang) ? $saldopiutang[0]->total : 0;
    }

    public function cekRpGit($jalan)
    {
        // $nominal = 0;
        // $toko = $jalan->toko;
        // if(!$toko->is_notnotapenjualan()->isEmpty()){
        //     foreach ($toko->is_notnotapenjualan() as $nota) {
        //         $nominalnota = 0;
        //         if(!$nota->details->isEmpty()){
        //             foreach ($nota->details as $detail) {
        //                 $nominalnota += $detail->qtynota*$detail->hrgsatuannetto;
        //             } 
        //         }
        //         $nominal += $nominalnota;
        //     }
        //     return $nominal;
        // }else{
        //     return $nominal;
        // }

        $tokoid = $jalan->tokoid;
        $git    = DB::select(DB::raw("SELECT COALESCE(SUM(njd.qtynota*njd.hrgsatuannetto),0) as total FROM pj.notapenjualan nj LEFT JOIN pj.notapenjualandetail njd ON nj.id = njd.notapenjualanid WHERE tokoid = $tokoid AND tglnota IS NULL"));
        return ($git) ? $git[0]->total : 0;
    }

    public function cekPiLDalamProses($jalan)
    {
        // $nominal = 0;
        // $toko = $jalan->toko;
        // if(!$toko->is_opjpiutang()->isEmpty()){
        //     foreach ($toko->is_opjpiutang() as $opj) {
        //         if(Carbon::now()->diffInDays($opj->tglpickinglist, false) >= -30){
        //             $nominal += $opj->total;
        //         }
        //     }
        //     return $nominal;
        // }else{
        //     return $nominal;
        // }

        $tokoid = $jalan->tokoid;
        $pildalamproses_query = "
            SELECT COALESCE(SUM(total),0) AS total FROM (
                SELECT
                    oj.id,
                    SUM(pj.notapenjualandetail.qtynota*pj.notapenjualandetail.hrgsatuannetto) AS total
                FROM pj.orderpenjualan oj
                LEFT JOIN pj.notapenjualan ON oj.id = pj.notapenjualan.orderpenjualanid
                LEFT JOIN pj.notapenjualandetail ON pj.notapenjualan.id = pj.notapenjualandetail.notapenjualanid
                WHERE oj.tokoid = $tokoid
                AND oj.noaccpiutang IS NOT NULL
                AND oj.rpaccpiutang != 0
                AND (oj.tglpickinglist >= current_date - interval '1' day AND cast(oj.tglprintpickinglist as time) >= '16:00:00')
                AND oj.tglpickinglist <= current_date
                GROUP BY oj.id
                HAVING oj.id not IN (SELECT pj.notapenjualan.orderpenjualanid FROM pj.notapenjualan)
                OR SUM(pj.notapenjualandetail.qtynota*pj.notapenjualandetail.hrgsatuannetto) < oj.rpaccpiutang
            ) x";
        $pildalamproses = DB::select(DB::raw($pildalamproses_query));
        return ($pildalamproses) ? $pildalamproses[0]->total : 0;
    }

    public function cekPickingList($jalan)
    {
        // $nominal = 0;
        // if(!$jalan->details->isEmpty()){
        //     foreach ($jalan->details as $detail) {
        //         $nominal += $detail->qtysoacc*$detail->hrgsatuannetto;
        //     }
        //     return $nominal;
        // }else{
        //     return $nominal;
        // }

        $orderpenjualanid = $jalan->id;
        $pickinglist    = DB::select(DB::raw("SELECT SUM(qtysoacc*hrgsatuannetto) AS total FROM pj.orderpenjualandetail WHERE orderpenjualanid = $orderpenjualanid"));
        return ($pickinglist) ? $pickinglist[0]->total : 0;
    }

    public function cekSaldoOverdue($jalan)
    {
        // $nominal = 0;
        // if(!$jalan->nota->isEmpty()){
        //     foreach ($jalan->nota as $nota) {
        //         $tgljatuhtempo = Carbon::parse($nota->tglnota)->addDays($nota->tempokirim+$nota->temposalesman);
        //         if(Carbon::now()->diffInDays($tgljatuhtempo, false) < 0){
        //             $nominalnota = 0;
        //             if(!$nota->details->isEmpty()){
        //                 foreach ($nota->details as $detail) {
        //                     $nominalnota += $detail->qtynota*$detail->hrgsatuannetto;
        //                 } 
        //             }
        //             $nominal += $nominalnota;
        //         }
        //     }
        //     return $nominal;
        // }else{
        //     return $nominal;
        // }
        $orderpenjualanid = $jalan->id;
        $overdue_query  = "
            SELECT COALESCE(SUM(qtynota*hrgsatuannetto),0) AS total FROM pj.notapenjualan nj
            LEFT JOIN pj.notapenjualandetail njd ON nj.id = njd.notapenjualanid
            WHERE tglnota+(tempokirim+temposalesman) < current_date
            AND orderpenjualanid = $orderpenjualanid";
        $saldooverdue   = DB::select(DB::raw($overdue_query));
        return ($saldooverdue) ? $saldooverdue[0]->total : 0;
    }

    public function statusIsiAcc($jalan)
    {
        // ini_set('max_execution_time',300);
        if (count($jalan->kp) > 0) {
        // if(!$jalan->kp->isEmpty()){
            $is_saldopiutang = $this->statusSaldoPiutang($jalan);
            $is_jatuhtempo   = $this->statusJatuhTempo($jalan);

            if($is_saldopiutang && $is_jatuhtempo){
                $overdue = $this->cekOverdue150Nota($jalan);

                if(!$overdue){
                    $overdue = $this->cekOverdue120150Nota($jalan);
                }

                if(!$overdue){
                    $overdue = $this->cekOverdue60120Nota($jalan);
                }

                if(!$overdue){
                    $overdue = $this->cekOverdue060Nota($jalan);
                }

                return $overdue;
            }else{
                return 'isi';
            }
        }else{
            return 'isi';
        }
    }

    public function statusSaldoPiutang($jalan)
    {
        $is_saldopiutang = false;
        $nominal      = 0;
        $nominalbayar = 0;

        // KELAMAAN - GANTI PAKE QUERY RAW AJA
        // foreach ($jalan->kp as $kp) {
        //     echo $kp->id . "<br>";
        //     $nominal += $kp->nominal;
        //     if (count($kp->details) > 0) {
        //     // if(!$kp->details->isEmpty()){
        //         // foreach ($kp->details as $kpd) {
        //         //     $nominalbayar += $kpd->nominalbayar;
        //         // }
        //     } 
        // }
        $query  = "
            select
               sum(kp.nomnota) nominalnota,
               sum(kpiutdet.bayar) nominalbayar
            from ptg.kartupiutang kp
            join (
               select 
                  ptg.kartupiutangdetail.kartupiutangid,
                  sum(ptg.kartupiutangdetail.nomtrans) bayar
               from ptg.kartupiutangdetail
               group by ptg.kartupiutangdetail.kartupiutangid
            )kpiutdet on kpiutdet.kartupiutangid = kp.id
            where kp.tokoid = 1442";
        $saldo = DB::select(DB::raw($query));
        
        $nominal = $saldo[0]->nominalnota;
        $nominalbayar = $saldo[0]->nominalbayar;

        $saldopiutang = $nominal-$nominalbayar;
        if($saldopiutang > 0 ){
            $is_saldopiutang = true;
        }

        return $is_saldopiutang;
    }

    public function statusJatuhTempo($jalan)
    {
        $is_jatuhtempo = false;
        $tgljatuhtempo = '';
        foreach ($jalan->nota as $nota) {
            $tgljatuhtempo = Carbon::parse($nota->tglnota)->addDays($nota->tempokirim+$nota->temposalesman);
            if(Carbon::now()->diffInDays($tgljatuhtempo, false) < 0){
                $is_jatuhtempo = true;
                break;
            }
        }

        return $is_jatuhtempo;
    }

    public function cekOverdue150Nota($jalan)
    {
        $is_overdue    = false;
        $tgljatuhtempo = '';
        $overdue       = 0;
        $nota_overdue  = null;
        foreach ($jalan->nota as $nota) {
            $tgljatuhtempo = Carbon::parse($nota->tglnota)->addDays($nota->tempokirim+$nota->temposalesman);
            if(Carbon::now()->diffInDays($tgljatuhtempo, false) < -150){
                $is_overdue   = true;
                $nota_overdue = $nota;
                $overdue      = Carbon::now()->diffInDays($tgljatuhtempo);
                break;
            }
        }

        if($is_overdue){
            if($this->cekKomitmenCabNota($nota_overdue)){
                // approvalmgmtrequest Api untuk overdue sudah di ACC?
                if(true){
                    return false;
                }else{
                    return 'Nota nomor '.$nota_overdue->nonota.' tanggal '.$nota_overdue->tglnota.' Rp '.$this->cekSaldoPiutang($nota_overdue).', sudah overdue lebih besar dari 150 hari, umur overdue '.$overdue.' hari. Silahkan minta ACC Overdue ke Piutang 11 untuk proses ACC Piutang Picking List';
                }
            }else{
                return 'Nota nomor '.$nota_overdue->nonota.' tanggal '.$nota_overdue->tglnota.' Rp '.$this->cekSaldoPiutang($nota_overdue).', sudah overdue lebih besar dari 150 hari. Silahkan isi komitmen penyelesaian cabang dahulu di kartu piutang';
            }
        }

        return $is_overdue;
    }

    public function cekOverdue120150Nota($jalan)
    {
        $is_overdue    = false;
        $tgljatuhtempo = '';
        $overdue       = 0;
        $nota_overdue  = null;
        foreach ($jalan->nota as $nota) {
            $tgljatuhtempo = Carbon::parse($nota->tglnota)->addDays($nota->tempokirim+$nota->temposalesman);
            if(Carbon::now()->diffInDays($tgljatuhtempo, false) < -120 && Carbon::now()->diffInDays($tgljatuhtempo, false) >= -150){
                $is_overdue   = true;
                $nota_overdue = $nota;
                $overdue      = Carbon::now()->diffInDays($tgljatuhtempo);
                break;
            }
        }

        if($is_overdue){
            if(!$this->cekKomitmenKaCabNota($nota_overdue)){
                return 'Nota nomor '.$nota_overdue->nonota.' tanggal transaksi '.$nota_overdue->tglnota.', tanggal jatuh tempo '.Carbon::parse($tgljatuhtempo)->format('d-m-Y').', umur overdue '.$overdue.' hari. Silahkan minta Ka. Cab isi tanggal komitmen lunas.';
            }else{
                return false;
            }
        }
        
        return $is_overdue;
    }

    public function cekOverdue60120Nota($jalan)
    {
        $is_overdue    = false;
        $tgljatuhtempo = '';
        $overdue       = 0;
        $nota_overdue  = null;
        foreach ($jalan->nota as $nota) {
            $tgljatuhtempo = Carbon::parse($nota->tglnota)->addDays($nota->tempokirim+$nota->temposalesman);
            if(Carbon::now()->diffInDays($tgljatuhtempo, false) < -60 && Carbon::now()->diffInDays($tgljatuhtempo, false) >= -120){
                $is_overdue   = true;
                $nota_overdue = $nota;
                $overdue      = Carbon::now()->diffInDays($tgljatuhtempo);
                break;
            }
        }

        if($is_overdue){
            if(!$this->cekKomitmenKaAdmNota($nota_overdue)){
                return 'Nota nomor '.$nota_overdue->nonota.' tanggal transaksi '.$nota_overdue->tglnota.', tanggal jatuh tempo '.Carbon::parse($tgljatuhtempo)->format('d-m-Y').', umur overdue '.$overdue.' hari. Silahkan minta Ka.Adm isi tanggal komitmen lunas.';
            }else{
                return false;
            } 
        }
        
        return $is_overdue;
    }

    public function cekOverdue060Nota($jalan)
    {
        $is_overdue    = false;
        $tgljatuhtempo = '';
        $overdue       = 0;
        $nota_overdue  = null;
        foreach ($jalan->nota as $nota) {
            $tgljatuhtempo = Carbon::parse($nota->tglnota)->addDays($nota->tempokirim+$nota->temposalesman);
            if(Carbon::now()->diffInDays($tgljatuhtempo, false) < 0 && Carbon::now()->diffInDays($tgljatuhtempo, false) >= -60){
                $is_overdue   = true;
                $nota_overdue = $nota;
                $overdue      = Carbon::now()->diffInDays($tgljatuhtempo);
                break;
            }
        }

        if($is_overdue){
            if(!$this->cekKomitmenPkpNota($nota_overdue)){
                return 'Nota nomor '.$nota_overdue->nonota.' tanggal transaksi '.$nota_overdue->tglnota.', tanggal jatuh tempo '.Carbon::parse($tgljatuhtempo)->format('d-m-Y').', umur overdue '.$overdue.' hari. Silahkan minta PKP isi tanggal komitmen lunas.';
            }else{
                return 'isi';
            }
        }else{
            return 'isi';
        }
    }

    public function cekKomitmenCabNota($nota)
    {
        $is_cab = false;
        if(!$nota->kp->isEmpty()){
            foreach ($nota->kp as $kp) {
                if($kp->tglkomitmencabang){
                    $is_cab = true;
                    break;
                }
            }
        }

        return $is_cab;
    }

    public function cekKomitmenKaCabNota($nota)
    {
        $is_kacab = false;
        if(!$nota->kp->isEmpty()){
            foreach ($nota->kp as $kp) {
                if($kp->tglkomitmenkacab){
                    $is_kacab = true;
                    break;
                }
            }
        }
        
        return $is_kacab;
    }

    public function cekKomitmenKaAdmNota($nota)
    {
        $is_kaadm = false;
        if(!$nota->kp->isEmpty()){
            foreach ($nota->kp as $kp) {
                if($kp->tglkomitmenkaadm){
                    $is_kaadm = true;
                    break;
                }
            }
        }
        
        return $is_kaadm;
    }

    public function cekKomitmenPkpNota($nota)
    {
        $is_pkp = false;
        if(!$nota->kp->isEmpty()){
            foreach ($nota->kp as $kp) {
                if($kp->tglkomitmenpkp){
                    $is_pkp = true;
                    break;
                }
            }
        }
        
        return $is_pkp;
    }

    public function cekKewenangan(Request $req)
    {
        $lastUserId = auth()->user()->id;

        if(auth()->attempt(['username'=>$req->username,'password'=>$req->password]))
        {
            if(auth()->user()->can($req->permission))
            {
                auth()->loginUsingId($lastUserId);
                if($req->about == 'isiacc'){
                    $jalan = OrderPenjualan::find($req->opjid);
                    if($jalan->tglterimapilpiutang){
                        $isi_acc = $this->statusIsiAcc($jalan);
                    }else{
                        $isi_acc = 'Tidak bisa proses ACC Piutang. Picking List belum diterima. Isi tanggal terima Picking List dahulu.';
                    }
                    return response()->json([
                        'success' => true,
                        'id'      => $req->opjid,
                        'message' => $isi_acc,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => false,
        ]);
    }

    public function getNextAccNo($recordownerid, $tipe){
        $next_no = '';
        $max_order = OrderPenjualan::select('id','noaccpiutang')->whereRaw("left(noaccpiutang,1) = ?",[$tipe])->orderBy('lastupdatedon','desc')->take(1)->get()->first();
        if ($max_order == null) {
            $next_no = sprintf("%010d", 1);
        }elseif (strlen($max_order->noaccpiutang)<11) {
            $next_no = sprintf("%010d", 1);
        }elseif ($max_order->noaccpiutang == '9999999999') {
            $next_no = sprintf("%010d", 1);
        }else {
            $next_no = sprintf("%010d", ltrim($max_order->noaccpiutang,$tipe.'0')+1);
        }
        return $next_no;
    }


    public function rekapPembayaranToko(Request $req)
    {
        $result = $this->rekapPembayaranTokoQuery($req->id);
        return view('transaksi.accpiutang.rekappembayarantoko',$result);
    }

    public function rekapPembayaranTokoExcel(Request $req)
    {
        $result = $this->rekapPembayaranTokoQuery($req->id);

        // Xlsx Var
        $sheet  = str_slug("Laporan Piutang Toko");
        $file   = $sheet."-".uniqid().".xlsx";
        $folder = storage_path('excel/exports');
        $username = strtoupper(auth()->user()->username);
        $tanggal  = Carbon::now()->format('d/m/Y');

        $header_format = ['GENERAL'];
        $header_style  = [
            'font-style' =>'bold',
            'fill'   =>'#dbe5f1',
            'halign' =>'center',
            'border' =>'left,right,top,bottom',
        ];

        $data_style = [
            'border' =>'left,right,top,bottom',
        ];

        $col_width = [18,18,15,25,25,25,15,15,];

        // Init Xlsx
        $writer = new XLSXWriter();
        $writer->setAuthor($username);
        $writer->setTempDir($folder);

        // Write Sheet Header
        $writer->writeSheetHeader($sheet,$header_format,['suppress_row'=>true,'widths'=>$col_width]);
        $writer->markMergedCell($sheet,0,0,0,3);
        $writer->markMergedCell($sheet,1,0,1,3);
        $writer->writeSheetRow($sheet,["PIUTANG ".strtoupper($result['toko']->namatoko),"","","","WILID : ",$result['toko']->customwilayah],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,[$result['toko']->alamat,"","","",$result['toko']->kota.($result['toko']->kecamatan ? ", ".$result['toko']->kecamatan : '')],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["","","","","No. Telepon : ",$result['toko']->telepon],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["PLAFON : ",$result['toko']->plafon,"","","Penanggungjawab :",$result['toko']->penanggungjawab],['font-style'=>'bold'],['GENERAL','#,##0','GENERAL','GENERAL','GENERAL','GENERAL']);

        // Nota Berjalan
        $nb_format = ['GENERAL','GENERAL','GENERAL','#,##0','#,##0','#,##0','GENERAL','GENERAL',];
        $writer->writeSheetRow($sheet,[]);
        $writer->writeSheetRow($sheet,[]);
        $writer->markMergedCell($sheet,6,0,6,3);
        $writer->writeSheetRow($sheet,["I. Informasi Nota yang masih berjalan"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["Tanggal","No.Nota","Sales","Nilai Nota (Rp)","Dibayar (Rp)","Saldo (Rp)","Jatuh Tempo","Keterlambatan"],$header_style);
        if($result['kartupiutang']) {
            foreach($result['kartupiutang'] as $kp) {
                $value = [
                    $kp->tglproforma,
                    $kp->nonota,
                    $kp->kodesales,
                    $kp->nomnota,
                    $kp->tnomtrans,
                    $kp->nomnota-$kp->tnomtrans,
                    $kp->tgljt,
                    $kp->keterlambatan.' HR',
                ];
                $writer->writeSheetRow($sheet,$value,$data_style,$nb_format);
            }
        }

        // Versi Bulanan
        $vb_format = ['GENERAL','GENERAL','GENERAL','#,##0','#,##0','#,##0'];
        $writer->writeSheetRow($sheet,[]);
        $writer->writeSheetRow($sheet,[]);
        $writer->writeSheetRow($sheet,["Versi Bulanan"],['font-style'=>'bold']);
        if($result['kartupiutangbulan']) {
            foreach($result['kartupiutangbulan'] as $kpb) {
                $value = ["",$kpb->bulan,$kpb->tahun,$kpb->nomnota,$kpb->tnomtrans,$kpb->nomnota-$kpb->tnomtrans,];
                $writer->writeSheetRow($sheet,$value,$data_style,$vb_format);
            }
        }
        $cell_jml_vb = 11+count($result['kartupiutang'])+count($result['kartupiutangbulan']);
        $writer->markMergedCell($sheet,$cell_jml_vb,0,$cell_jml_vb,2);
        $writer->writeSheetRow($sheet,["JUMLAH","","",$result['total_nomnota'],$result['total_tnomtrans'],$result['total_sisa']],$data_style,$vb_format);

        // II. Riwayat Omset
        $ro_format = ['GENERAL','GENERAL','GENERAL','#,##0','#,##0','GENERAL',];
        $writer->writeSheetRow($sheet,[]);
        $writer->writeSheetRow($sheet,[]);
        $writer->writeSheetRow($sheet,["II. Riwayat Omset","Penjualan Tunai","Rp.",$result['riwayat_tunai']->nomnota,$result['riwayat_tunai']->lembarnota,"Lembar Nota"],['font-style'=>'bold'],$ro_format);
        $writer->writeSheetRow($sheet,["","Penjualan Kredit","Rp.",$result['riwayat_kredit']->nomnota,$result['riwayat_kredit']->lembarnota,"Lembar Nota"],['font-style'=>'bold'],$ro_format);
        $writer->writeSheetRow($sheet,["","Total","Rp.",$result['riwayat_total']['nomnota'],$result['riwayat_total']['lembarnota'],"x Transaksi"],['font-style'=>'bold'],$ro_format);

        // III. BG-BG MASIH BERJALAN
        $bgc_format = ['GENERAL','GENERAL','GENERAL','GENERAL','#,##0',];
        $writer->writeSheetRow($sheet,[]);
        $writer->writeSheetRow($sheet,[]);
        $writer->writeSheetRow($sheet,["III. BG-BG MASIH BERJALAN (semua sales)"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["No BGC","Tgl Terima","Jt Tempo","Hari Overdue","Nominal",],$header_style);
        if($result['bgc']) {
            foreach($result['bgc'] as $bgc) {
                $value = ["",$bgc->nobgc,$bgc->createdon,$bgc->tgljtbgc,$bgc->overdue,$bgc->nomtrans,];
                $writer->writeSheetRow($sheet,$value,$data_style,$bgc_format);
            }
        }

        // IV. Denda Tagihan
        $dt_format = ['GENERAL','GENERAL','GENERAL','#,##0','#,##0','#,##0',];
        $writer->writeSheetRow($sheet,[]);
        $writer->writeSheetRow($sheet,[]);
        $writer->writeSheetRow($sheet,["IV. Denda Tagihan"],['font-style'=>'bold']);
        $writer->writeSheetRow($sheet,["Tanggal","No. Nota","Salesman","Rp. Denda","Rp. Bayar","Rp. Sisa",],$header_style);
        if($result['dt']) {
            foreach($result['dt'] as $dt) {
                $value = ["",$dt->tgldenda,$dt->nonota,$dt->namakaryawan,$dt->rpdenda,$dt->rpbayar,$dt->rpsisa,];
                $writer->writeSheetRow($sheet,$value,$data_style,$dt_format);
            }
        }
        $cell_jml_dt = 14+$cell_jml_vb+count($result['bgc'])+count($result['dt']);
        $writer->markMergedCell($sheet,$cell_jml_dt,0,$cell_jml_dt,2);
        $writer->writeSheetRow($sheet,["JUMLAH","","",$result['total_rpdenda'],$result['total_rpbayar'],$result['total_rpsisa']],$data_style,$vb_format);

        // Footer
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,["Catatan Toko :",$result['toko']->catatan]);
        $writer->writeSheetRow($sheet,['']);
        $writer->markMergedCell($sheet,$cell_jml_dt+5,0,$cell_jml_dt+5,1);
        $writer->writeSheetRow($sheet,["Jangka Waktu Tertinggi :","",$result['tempotertinggi']]);
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,['']);
        $writer->writeSheetRow($sheet,[$username.",".(Carbon::now()->format('d/m/Y h:i:s'))],['font-style'=>'italic']);

        // return if write to file complete
        $writer->writeToFile($folder.'/'.$file);
        return asset('storage/excel/exports/'.$file);
    }

    private function rekapPembayaranTokoQuery($id)
    {
        $orderpenjualan = OrderPenjualan::find($id);
        $toko = $orderpenjualan->toko;
        // $toko = Toko::find($id);

        // Plafon 
        $plafon = Plafon::select('plafon')->where('tokoid',$toko->id)->orderBy('tglaktif','DESC')->limit(1)->first();
        $toko->plafon = ($plafon) ? $plafon->plafon : 0;

        $kartupiutang = KartuPiutang::selectRaw('tglproforma, nonota, kodesales, SUM(nomnota) AS nomnota, tgljt, DATE_PART(\'day\',tgljt::timestamp-now()::timestamp) AS keterlambatan, SUM(nomtrans) AS tnomtrans')
        ->leftJoin('ptg.kartupiutangdetail','ptg.kartupiutangdetail.kartupiutangid','=','ptg.kartupiutang.id')
        ->leftJoin('hr.karyawan','hr.karyawan.id','=','ptg.kartupiutang.karyawanidsalesman')
        ->where('tokoid',$toko->id)
        ->groupBy('tglproforma','nonota','kodesales','tgljt','keterlambatan')
        ->havingRaw('(SUM(nomnota)-SUM(nomtrans)) != 0')
        ->orderBy('tglproforma')
        ->get();

        $kartupiutangbulan = KartuPiutang::selectRaw('extract(month from tglproforma) AS bulan, extract(year from tglproforma) AS tahun, SUM(nomnota) AS nomnota, SUM(nomtrans) AS tnomtrans')
        ->leftJoin('ptg.kartupiutangdetail','ptg.kartupiutangdetail.kartupiutangid','=','ptg.kartupiutang.id')
        ->where('tokoid',$toko->id)
        ->groupBy('bulan','tahun')
        ->groupBy('tglproforma')
        ->havingRaw('(SUM(nomnota)-SUM(nomtrans)) != 0')
        ->orderBy('tahun')
        ->orderBy('bulan')
        ->get();

        $tempotertinggi = KartuPiutang::where('tokoid',$toko->id)->max('temponota');
        $riwayat_tunai  = KartuPiutang::selectRaw('COALESCE(SUM(nomnota),0) AS nomnota, COUNT(id) as lembarnota')->where('tokoid',$toko->id)->whereRaw("substr(tipetrans,1,1) = 'T'")->get()->first();
        $riwayat_kredit = KartuPiutang::selectRaw('COALESCE(SUM(nomnota),0) AS nomnota, COUNT(id) as lembarnota')->where('tokoid',$toko->id)->whereRaw("substr(tipetrans,1,1) = 'K'")->get()->first();
        $riwayat_total  = [
            'nomnota'    => $riwayat_tunai->nomnota+$riwayat_kredit->nomnota,
            'lembarnota' => $riwayat_tunai->lembarnota+$riwayat_kredit->lembarnota,
        ];

        $bgc = KartuPiutang::selectRaw('ksr.bgc.nobgc, ksr.bgc.createdon, ksr.bgc.tgljtbgc, ksr.bgc.nomtrans, DATE_PART(\'day\',ksr.bgc.tgljtbgc::timestamp-now()::timestamp) AS overdue')
        ->leftJoin('ptg.kartupiutangdetail','ptg.kartupiutangdetail.kartupiutangid','=','ptg.kartupiutang.id')
        ->leftJoin('ksr.bgc','ksr.bgc.id','=','ptg.kartupiutangdetail.bgcid')
        ->join('ksr.bgcsts','ksr.bgcsts.bgcid','=','ksr.bgc.id')
        ->where('tokoid',$toko->id)
        ->where(function($q) {
            $q->where('ksr.bgcsts.sts','!=','CAIR');
            $q->orWhere('ksr.bgcsts.sts','!=','TOLAK');
        })
        ->get();

        $dt = KartuPiutang::selectRaw('ptg.dendatagihan.tgldenda, ptg.kartupiutang.nonota, hr.karyawan.namakaryawan, ptg.dendatagihan.nom as rpdenda, sum(ptg.dendatagihandetail.nomtrans) as rpbayar')
        ->leftJoin('ptg.kartupiutangdetail','ptg.kartupiutangdetail.kartupiutangid','=','ptg.kartupiutang.id')
        ->leftJoin('hr.karyawan','hr.karyawan.id','=','ptg.kartupiutang.karyawanidsalesman')
        ->join('ptg.dendatagihan','ptg.dendatagihan.kartupiutangdetailid','=','ptg.kartupiutangdetail.id')
        ->join('ptg.dendatagihandetail','ptg.dendatagihandetail.dendatagihanid','=','ptg.dendatagihan.id')
        ->where('tokoid',$toko->id)
        ->groupBy('tgldenda','nonota','namakaryawan','rpdenda')
        ->get();

        // Bulan
        $bulan = [1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

        foreach($kartupiutang as $kp){
            $kp->tglproforma = Carbon::parse($kp->tglproforma)->format('d-m-Y');
            $kp->tgljt       = Carbon::parse($kp->tgljt)->format('d-m-Y');
        }

        $total_nomnota   = 0;
        $total_tnomtrans = 0;
        $total_sisa      = 0;
        foreach($kartupiutangbulan as $kpb){
            $kpb->bulan      = $bulan[$kpb->bulan];
            $total_nomnota   += $kp->nomnota;
            $total_tnomtrans += $kp->tnomtrans;
            $total_sisa      += ($kp->nomnota-$kp->tnomtrans);
        }

        foreach($bgc as $bg){
            $bg->createdon = Carbon::parse($bg->createdon)->format('d-m-Y');
            $bg->tgljtbgc  = Carbon::parse($bg->tgljtbgc)->format('d-m-Y');
        }

        $total_rpdenda = 0;
        $total_rpbayar = 0;
        $total_rpsisa  = 0;
        foreach($dt as $dx){
            $dx->tgldenda = Carbon::parse($dx->tgldenda)->format('d-m-Y');
            $dx->rpsisa   = $dx->rpdenda-$dx->rpbayar;
            $total_rpdenda += $kp->rpdenda;
            $total_rpbayar += $kp->rpbayar;
            $total_rpsisa  += ($kp->rpdenda-$kp->rpbayar);
        }

        return [
            'id'   => $id,
            'toko' => $toko,
            'tempotertinggi' => $tempotertinggi,
            'riwayat_kredit' => $riwayat_kredit,
            'riwayat_tunai'  => $riwayat_tunai,
            'riwayat_total'  => $riwayat_total,
            'kartupiutang'   => $kartupiutang,
            'kartupiutangbulan' => $kartupiutangbulan,
            'bgc' => $bgc,
            'dt'  => $dt,
            'total_nomnota'   => $total_nomnota,
            'total_tnomtrans' => $total_tnomtrans,
            'total_sisa'      => $total_sisa,
            'total_rpdenda'   => $total_rpdenda,
            'total_rpbayar'   => $total_rpbayar,
            'total_rpsisa'    => $total_rpsisa,
        ];
    }
}
