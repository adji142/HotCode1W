<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\LoginLog;
use Carbon\Carbon;

use DB;
use App\Models\OrderPenjualanSynch;
use App\Models\OrderPenjualanDetail;
use App\Models\NotaPenjualanMurni;
use App\Models\NotaPenjualanDetail;
use App\Models\KoreksiPenjualanDetail;
use App\Models\ReturPenjualanMurni;
use App\Models\ReturPenjualanDetail;
use App\Models\KoreksiReturPenjualan;
use App\Models\Pengiriman;
use App\Models\PengirimanDetail;
use App\Models\OrderPembelian;
use App\Models\OrderPembelianDetail;
use App\Models\NotaPembelianMurni;
use App\Models\NotaPembelianDetail;
use App\Models\KoreksiNotaPembelian;
use App\Models\ReturPembelianMurni;
use App\Models\ReturPembelianDetail;
use App\Models\KoreksiReturPembelian;


class SynchController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    protected $redirectTo = '/synch/token';

    public function username()
    {
        return 'username';
    }
    
    public function token(Request $request)
    {
        return csrf_token();
    }

    public function synchLogin(Request $request)
    {
        $this->login($request);
    }

    public function nomorkoli($nokoli)
    {
        $nomor = explode(",", $nokoli);
        $nomor = array_values(array_sort($nomor, function($value){ return $value; }));

        $awal = null;
        $koli = "";
        $separator = ",";
        
        foreach ($nomor as $urut) {
            if($awal == null)
            {
                $koli .= $urut;
            }
            else
            {
                if(((int)$awal + 1) != (int)$urut || $urut == max($nomor))
                {
                    $koli .= $separator . $urut . ",";
                    $separator = ",";
                }
                else
                {
                    $separator = "-";
                }
            }
            $awal = $urut;
        }

        return $koli;
    }

    public function synchDOHeader(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      // dd($tglmulai);

      $order = OrderPenjualanSynch::select([
            // 0 => "pj.orderpenjualansynch.",
            0 => "pj.orderpenjualansynch.id",
            1 => "pj.orderpenjualansynch.recordownerid",
            2 => "pj.orderpenjualansynch.omsetsubcabangid",
            3 => "pj.orderpenjualansynch.pengirimsubcabangid",
            4 => "pj.orderpenjualansynch.noso",
            5 => "pj.orderpenjualansynch.tglso",
            6 => "pj.orderpenjualansynch.nopickinglist",
            7 => "pj.orderpenjualansynch.tglpickinglist",
            8 => "pj.orderpenjualansynch.noaccpiutang",
            9 => "pj.orderpenjualansynch.karyawanidpkp",
            11 => "pj.orderpenjualansynch.tglaccpiutang",
            12 => "pj.orderpenjualansynch.rpaccpiutang",
            13 => "pj.orderpenjualansynch.rpsaldopiutang",
            14 => "pj.orderpenjualansynch.rpsaldooverdue",
            15 => "pj.orderpenjualansynch.rpsoaccproses",
            16 => "pj.orderpenjualansynch.rpplafon",
            17 => "pj.orderpenjualansynch.rpsisaplafon",
            18 => "pj.orderpenjualansynch.rpgit",
            19 => "pj.orderpenjualansynch.tokoid",
            21 => "pj.orderpenjualansynch.approvalmgmtidoverdue",
            22 => "pj.orderpenjualansynch.statustoko",
            23 => "pj.orderpenjualansynch.temponota",
            24 => "pj.orderpenjualansynch.tempokirim",
            25 => "pj.orderpenjualansynch.temposalesman",
            26 => "pj.orderpenjualansynch.tipetransaksi",
            27 => "pj.orderpenjualansynch.karyawanidsalesman",
            28 => "pj.orderpenjualansynch.catatanpenjualan",
            29 => "pj.orderpenjualansynch.catatanpembayaran",
            31 => "pj.orderpenjualansynch.catatanpengiriman",
            32 => "pj.orderpenjualansynch.print",
            33 => "pj.orderpenjualansynch.tglprintpickinglist",
            34 => "pj.orderpenjualansynch.tglterimapilpiutang",
            35 => "pj.orderpenjualansynch.statusajuanhrg11",
            36 => "pj.orderpenjualansynch.expedisiid",
            37 => "pj.orderpenjualansynch.bomurni",
            38 => "pj.orderpenjualansynch.tglpjkegudang",
            39 => "pj.orderpenjualansynch.alasanterlambatpjkegudang",
            40 => "pj.orderpenjualansynch.isarowid",
            41 => "pj.orderpenjualansynch.createdby",
            42 => "pj.orderpenjualansynch.lastupdatedby",
            43 => "pj.orderpenjualansynch.namatoko",
            44 => "pj.orderpenjualansynch.kodetoko",
            45 => "pj.orderpenjualansynch.alamat",
            46 => "pj.orderpenjualansynch.kota",
            47 => "pj.orderpenjualansynch.omsetcabang",
            48 => "pj.orderpenjualansynch.kirimcabang",
            49 => "pj.orderpenjualansynch.kodesales",
            50 => "pj.orderpenjualansynch.namakaryawan",
            51 => "pj.orderpenjualansynch.status",
            52 => "pj.orderpenjualansynch.namaexpedisi",
          ])
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pj.orderpenjualansynch.createdon"), ">=", $tglmulai)
                ->where(DB::raw("pj.orderpenjualansynch.createdon"), "<=", $tglselesai);
          })
          ->where("pj.orderpenjualansynch.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($order as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchDODetail(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      $details = OrderPenjualanDetail::select([
            // 0 => "pj.orderpenjualandetail.*",
            0 => "pj.orderpenjualandetail.id",
            1 => "pj.orderpenjualandetail.orderpenjualanid",
            2 => "pj.orderpenjualandetail.stockid",
            3 => "pj.orderpenjualandetail.qtyso",
            4 => "pj.orderpenjualandetail.qtysoacc",
            5 => "pj.orderpenjualandetail.hrgbmk",
            6 => "pj.orderpenjualandetail.hrgsatuanbrutto",
            7 => "pj.orderpenjualandetail.disc1",
            8 => "pj.orderpenjualandetail.disc2",
            9 => "pj.orderpenjualandetail.ppn",
            11 => "pj.orderpenjualandetail.hrgsatuannetto",
            12 => "pj.orderpenjualandetail.approvalmgmtidacc11",
            13 => "pj.orderpenjualandetail.noacc11",
            14 => "pj.orderpenjualandetail.catatan",
            15 => "pj.orderpenjualandetail.qtystockgudang",
            16 => "pj.orderpenjualandetail.komisikhusus11",
            17 => "pj.orderpenjualandetail.orderpembeliandetailid",
            18 => "pj.orderpenjualandetail.isarowid",
            19 => "pj.orderpenjualandetail.createdby",
            20 => "pj.orderpenjualandetail.lastupdatedby",
            21 => "mstr.stock.kodebarang",
            22 => "mstr.stock.namabarang",
            23 => "pj.orderpenjualan.isarowid as orderpenjualanrowid",
          ])
          ->join("pj.orderpenjualan", "pj.orderpenjualan.id", "=", "orderpenjualanid")
          ->join("mstr.stock", "mstr.stock.id", "=", "pj.orderpenjualandetail.stockid")
          ->join("mstr.subcabang as subcbowner", "pj.orderpenjualan.recordownerid", "=", "subcbowner.id")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pj.orderpenjualandetail.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pj.orderpenjualandetail.createdon::date"), "<=", $tglselesai);
          })
          ->orWhere(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pj.orderpenjualan.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pj.orderpenjualan.createdon::date"), "<=", $tglselesai);
          })
          ->where("subcbowner.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($details as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchPJHeader(Request $req)
    {
        $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
        $cabang = $req->cabang;

        $nota = NotaPenjualanMurni::select([
                0 => "pj.notapenjualanmurni.id",
                1 => "pj.notapenjualanmurni.recordownerid",
                2 => "pj.notapenjualanmurni.orderpenjualanid",
                3 => "pj.notapenjualanmurni.omsetsubcabangid",
                4 => "pj.notapenjualanmurni.pengirimsubcabangid",
                5 => "pj.notapenjualanmurni.nonota",
                6 => "pj.notapenjualanmurni.tglnota",
                7 => "pj.notapenjualanmurni.tglproforma",
                8 => "pj.notapenjualanmurni.tokoid",
                9 => "pj.notapenjualanmurni.karyawanidsalesman",
                10 => "pj.notapenjualanmurni.tglcheck",
                11 => "pj.notapenjualanmurni.tipetransaksi",
                12 => "pj.notapenjualanmurni.temponota",
                13 => "pj.notapenjualanmurni.tempokirim",
                14 => "pj.notapenjualanmurni.temposalesman",
                15 => "pj.notapenjualanmurni.karyawanidchecker1",
                16 => "pj.notapenjualanmurni.karyawanidchecker2",
                17 => "pj.notapenjualanmurni.printproforma",
                18 => "pj.notapenjualanmurni.printnota",
                19 => "pj.notapenjualanmurni.tglprintnota",
                20 => DB::raw("pj.notapenjualanmurni.tglprintproforma::date"),
                21 => "pj.notapenjualanmurni.totalnominal",
                22 => "pj.notapenjualanmurni.isarowid",
                23 => "pj.notapenjualanmurni.createdby",
                24 => "pj.notapenjualanmurni.lastupdatedby",
                25 => "mstr.toko.kodetoko",
                26 => "mstr.toko.namatoko",
                27 => "mstr.toko.alamat",
                28 => "mstr.toko.kota",
                29 => "cbomset.kodecabang as omsetcabang",
                30 => "cbkirim.kodecabang as kirimcabang",
                31 => "hr.karyawan.kodesales",
                32 => "pj.orderpenjualan.tipetransaksi",
                33 => "chk1.namakaryawan as checker1",
                34 => "chk2.namakaryawan as checker2",
                35 => "pj.orderpenjualan.isarowid as orderpenjualanrowid",
            ])
            ->join("pj.orderpenjualan", "pj.notapenjualanmurni.orderpenjualanid", "=", "pj.orderpenjualan.id")
            ->join("mstr.toko", "pj.notapenjualanmurni.tokoid", "=", "mstr.toko.id")
            ->join("hr.karyawan", "pj.notapenjualanmurni.karyawanidsalesman", "=", "hr.karyawan.id")
            ->leftjoin("hr.karyawan as chk1", "pj.notapenjualanmurni.karyawanidchecker1", "=", "chk1.id")
            ->leftjoin("hr.karyawan as chk2", "pj.notapenjualanmurni.karyawanidchecker2", "=", "chk2.id")
            ->join("mstr.subcabang as subcbomset", "pj.notapenjualanmurni.omsetsubcabangid", "=", "subcbomset.id")
            ->join("mstr.cabang as cbomset", "subcbomset.cabangid", "=", "cbomset.id")
            ->join("mstr.subcabang as subcbkirim", "pj.notapenjualanmurni.omsetsubcabangid", "=", "subcbkirim.id")
            ->join("mstr.cabang as cbkirim", "subcbkirim.cabangid", "=", "cbkirim.id")
            ->join("mstr.subcabang as subcbowner", "pj.notapenjualanmurni.recordownerid", "=", "subcbowner.id")
            ->where(function($query) use ($tglmulai, $tglselesai){
                $query->where(DB::raw("pj.orderpenjualan.createdon::date"), ">=", $tglmulai)
                    ->where(DB::raw("pj.orderpenjualan.createdon::date"), "<=", $tglselesai);
            })
            ->where("subcbowner.kodesubcabang", $cabang)
            ->get();

        $data = [];
        foreach ($nota as $key => $value) {
            $data[$key] = $value->toArray();
        }

        return json_encode($data);
    }

    public function synchPJDetail(Request $req)
    {
        $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
        $cabang = $req->cabang;

        $details = NotaPenjualanDetail::select([
                0 => "pj.notapenjualandetail.id",
                1 => "pj.notapenjualandetail.notapenjualanid",
                2 => "pj.notapenjualandetail.stockid",
                3 => "pj.notapenjualandetail.qtynota",
                4 => "pj.notapenjualandetail.hrgsatuanbrutto",
                5 => "pj.notapenjualandetail.disc1",
                6 => "pj.notapenjualandetail.disc2",
                7 => "pj.notapenjualandetail.totaldiscount",
                8 => "pj.notapenjualandetail.ppn",
                9 => "pj.notapenjualandetail.hrgsatuannetto",
                10 => "pj.notapenjualandetail.stockgudang",
                11 => "pj.notapenjualandetail.orderpenjualandetailid",
                12 => "pj.notapenjualandetail.koreksipjparentid",
                13 => "pj.notapenjualandetail.newchildid",
                14 => "pj.notapenjualandetail.isarowid",
                15 => "pj.notapenjualandetail.createdby",
                17 => "pj.notapenjualandetail.lastupdatedby",
                19 => "pj.notapenjualandetail.kartupiutangdetailid",
                20 => "pj.notapenjualandetail.pot",
                21 => "mstr.stock.kodebarang",
                22 => "mstr.stock.namabarang",
                23 => "pj.notapenjualandetailnokoli.nokoli",
                24 => "pj.notapenjualandetailnokoli.keterangan as keterangankoli",
                25 => "pj.notapenjualanmurni.isarowid as notapenjualanrowid",
                26 => "pj.orderpenjualandetail.isarowid as orderpenjualandetailrowid",
            ])
            ->join("pj.notapenjualanmurni", "pj.notapenjualandetail.notapenjualanid", "=", "pj.notapenjualanmurni.id")
            ->join("pj.orderpenjualandetail", "pj.orderpenjualandetail.id", "=", "pj.notapenjualandetail.orderpenjualandetailid")
            ->join("mstr.stock", "pj.notapenjualandetail.stockid", "=", "mstr.stock.id")
            ->join("mstr.subcabang as subcbowner", "pj.notapenjualanmurni.recordownerid", "=", "subcbowner.id")
            ->leftjoin("pj.notapenjualandetailnokoli", "pj.notapenjualandetailnokoli.id", "=", "pj.notapenjualandetail.id")
            ->where(function($query) use ($tglmulai, $tglselesai){
                $query->where(DB::raw("pj.notapenjualanmurni.createdon::date"), ">=", $tglmulai)
                    ->where(DB::raw("pj.notapenjualanmurni.createdon::date"), "<=", $tglselesai);
            })
            ->orWhere(function($query) use ($tglmulai, $tglselesai){
                $query->where(DB::raw("pj.notapenjualandetail.createdon::date"), ">=", $tglmulai)
                    ->where(DB::raw("pj.notapenjualandetail.createdon::date"), "<=", $tglselesai);
            })
            ->where("subcbowner.kodesubcabang", $cabang)
            ->get();

        $data = [];
        foreach ($details as $key => $value) {
            $data[$key] = $value->toArray();
            $data[$key]["nokoli"] = self::nomorkoli($data[$key]["nokoli"]);
        }

        return json_encode($data);
    }

    public function synchPJKoreksi(Request $req)
    {
        $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
        $cabang = $req->cabang;

        $details = KoreksiPenjualanDetail::select([
                0 => "pj.koreksipenjualandetail.id",
                1 => "pj.koreksipenjualandetail.notapenjualanid",
                2 => "pj.koreksipenjualandetail.stockid",
                3 => "pj.koreksipenjualandetail.qtynota",
                4 => "pj.koreksipenjualandetail.hrgsatuanbrutto",
                5 => "pj.koreksipenjualandetail.disc1",
                6 => "pj.koreksipenjualandetail.disc2",
                7 => "pj.koreksipenjualandetail.totaldiscount",
                8 => "pj.koreksipenjualandetail.ppn",
                9 => "pj.koreksipenjualandetail.hrgsatuannetto",
                10 => "pj.koreksipenjualandetail.stockgudang",
                11 => "pj.koreksipenjualandetail.orderpenjualandetailid",
                12 => "pj.koreksipenjualandetail.koreksipjparentid",
                13 => "pj.koreksipenjualandetail.newchildid",
                14 => "pj.koreksipenjualandetail.isarowid",
                15 => "pj.koreksipenjualandetail.createdby",
                17 => "pj.koreksipenjualandetail.lastupdatedby",
                19 => "pj.koreksipenjualandetail.kartupiutangdetailid",
                20 => "pj.koreksipenjualandetail.pot",
                21 => "pj.koreksipenjualandetail.notapenjualandetailid",
                22 => "pj.koreksipenjualandetail.hrgkoreksi",
                23 => "pj.koreksipenjualandetail.qtykoreksi",
                24 => "pj.notapenjualan.tglproforma",
                25 => "pj.notapenjualan.nonota",
                26 => "mstr.toko.kodetoko",
                27 => "mstr.stock.kodebarang",
                28 => "mstr.stock.namabarang",
                29 => "pj.koreksipenjualandetail.koreksike",
                30 => "pj.notapenjualandetail.isarowid as notapenjualandetailrowid",
            ])
            ->join("pj.notapenjualan", "pj.koreksipenjualandetail.notapenjualanid", "=", "pj.notapenjualan.id")
            ->join("pj.notapenjualandetail", "pj.koreksipenjualandetail.notapenjualandetailid", "=", "pj.notapenjualandetail.id")
            ->join("mstr.stock", "pj.koreksipenjualandetail.stockid", "=", "mstr.stock.id")
            ->join("mstr.subcabang as subcbowner", "pj.notapenjualan.recordownerid", "=", "subcbowner.id")
            ->join("mstr.toko", "pj.notapenjualan.tokoid", "=", "mstr.toko.id")
            ->where(function($query) use ($tglmulai, $tglselesai){
                $query->where(DB::raw("pj.notapenjualan.createdon::date"), ">=", $tglmulai)
                    ->where(DB::raw("pj.notapenjualan.createdon::date"), "<=", $tglselesai);
            })
            ->orWhere(function($query) use ($tglmulai, $tglselesai){
                $query->where(DB::raw("pj.koreksipenjualandetail.createdon::date"), ">=", $tglmulai)
                    ->where(DB::raw("pj.koreksipenjualandetail.createdon::date"), "<=", $tglselesai);
            })
            ->where("subcbowner.kodesubcabang", $cabang)
            ->get();

        $data = [];
        foreach ($details as $key => $value) {
            $data[$key] = $value->toArray();
        }

        return json_encode($data);
    }

    public function synchRJHeader(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      $order = ReturPenjualanMurni::select([
            // 0 => "pj.orderpenjualansynch.",
            0 => "pj.returpenjualanmurni.id",
            1 => "pj.returpenjualanmurni.recordownerid",
            2 => "pj.returpenjualanmurni.omsetsubcabangid",
            3 => "pj.returpenjualanmurni.pengirimsubcabangid",
            4 => "pj.returpenjualanmurni.nompr",
            5 => "pj.returpenjualanmurni.tglmpr",
            6 => "pj.returpenjualanmurni.tglsppb",
            7 => "pj.returpenjualanmurni.nonotaretur",
            8 => "pj.returpenjualanmurni.tglnotaretur",
            9 => "pj.returpenjualanmurni.tokoid",
            10 => "pj.returpenjualanmurni.tglterimagudang",
            11 => "pj.returpenjualanmurni.keterangan",
            12 => "pj.returpenjualanmurni.tglprintsppb",
            13 => "pj.returpenjualanmurni.printsppb",
            14 => "pj.returpenjualanmurni.tglprintnrj",
            15 => "pj.returpenjualanmurni.printnrj",
            16 => "pj.returpenjualanmurni.kartupiutangdetailid",
            17 => "pj.returpenjualanmurni.karyawanidstock",
            18 => "pj.returpenjualanmurni.karyawanidpenjualan",
            19 => "pj.returpenjualanmurni.karyawanidpengambilbarang",
            20 => "pj.returpenjualanmurni.isarowid",
            21 => "pj.returpenjualanmurni.createdby",
            22 => "pj.returpenjualanmurni.lastupdatedby",
            23 => "pj.returpenjualanmurni.cabang1",
            24 => "pj.returpenjualanmurni.cabang2",
            25 => "pj.returpenjualanmurni.namatoko",
            26 => "pj.returpenjualanmurni.kodetoko",
            27 => "pj.returpenjualanmurni.alamat",
            28 => "pj.returpenjualanmurni.stock",
            29 => "pj.returpenjualanmurni.penjualan",
            30 => "pj.returpenjualanmurni.pengambilbarang",
          ])
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pj.returpenjualanmurni.createdon"), ">=", $tglmulai)
                ->where(DB::raw("pj.returpenjualanmurni.createdon"), "<=", $tglselesai);
          })
          ->where("pj.returpenjualanmurni.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($order as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchRJDetail(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      $details = ReturPenjualanDetail::select([
            0 => "pj.returpenjualandetail.id",
            1 => "pj.returpenjualandetail.returpenjualanid",
            2 => "pj.returpenjualandetail.notapenjualandetailid",
            3 => "pj.returpenjualandetail.kartupiutangdetailid",
            4 => "pj.returpenjualandetail.stockid",
            5 => "pj.returpenjualandetail.qtympr",
            6 => "pj.returpenjualandetail.qtysppb",
            7 => "pj.returpenjualandetail.qtynrj",
            8 => "pj.returpenjualandetail.hrgsatuanbrutto",
            9 => "pj.returpenjualandetail.disc1",
            10 => "pj.returpenjualandetail.disc2",
            11 => "pj.returpenjualandetail.ppn",
            12 => "pj.returpenjualandetail.hrgsatuannetto",
            13 => "pj.returpenjualandetail.keterangan",
            14 => "pj.returpenjualandetail.kategoriidrpj",
            15 => "pj.returpenjualandetail.tiperetur",
            16 => "pj.returpenjualandetail.koreksirjparentid",
            17 => "pj.returpenjualandetail.newchildid",
            18 => "pj.returpenjualandetail.isarowid",
            19 => "pj.returpenjualandetail.createdby",
            20 => "pj.returpenjualandetail.lastupdatedby",
            21 => "pj.returpenjualandetail.karyawanidsalesman",
            22 => "mstr.stock.kodebarang",
            23 => "mstr.stock.namabarang",
            24 => "mstr.kategoriretur.kode as kategori",
            25 => "pj.returpenjualanmurni.kodesubcabang",
            26 => "pj.notapenjualandetail.isarowid as notapenjualandetailrowid",
          ])
          ->join("pj.returpenjualanmurni", "pj.returpenjualanmurni.id", "=", "returpenjualanid")
          ->leftjoin("pj.notapenjualandetail", "pj.notapenjualandetail.id", "=", "notapenjualandetailid")
          ->leftjoin("mstr.stock", "mstr.stock.id", "=", "pj.returpenjualandetail.stockid")
          ->leftjoin("mstr.kategoriretur", "pj.returpenjualandetail.kategoriidrpj", "=", "mstr.kategoriretur.id")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pj.returpenjualandetail.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pj.returpenjualandetail.createdon::date"), "<=", $tglselesai);
          })
          ->orWhere(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pj.returpenjualanmurni.createdon"), ">=", $tglmulai)
                ->where(DB::raw("pj.returpenjualanmurni.createdon"), "<=", $tglselesai);
          })
          ->where("pj.returpenjualanmurni.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($details as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchRJKoreksi(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      $details = KoreksiReturPenjualan::select([
            0 => "pj.koreksireturpenjualan.id",
            1 => "pj.koreksireturpenjualan.returpenjualanid",
            2 => "pj.koreksireturpenjualan.notapenjualandetailid",
            3 => "pj.koreksireturpenjualan.kartupiutangdetailid",
            4 => "pj.koreksireturpenjualan.tglnotaretur",
            5 => "pj.koreksireturpenjualan.nonotaretur",
            6 => "pj.koreksireturpenjualan.kodebarang",
            7 => "pj.koreksireturpenjualan.namabarang",
            8 => "pj.koreksireturpenjualan.qtympr",
            9 => "pj.koreksireturpenjualan.qtysppb",
            10 => "pj.koreksireturpenjualan.qtynrj",
            11 => "pj.koreksireturpenjualan.hrgsatuanbrutto",
            12 => "pj.koreksireturpenjualan.disc1",
            13 => "pj.koreksireturpenjualan.disc2",
            14 => "pj.koreksireturpenjualan.ppn",
            15 => "pj.koreksireturpenjualan.hrgsatuannetto",
            16 => "pj.koreksireturpenjualan.qtykoreksi",
            17 => "pj.koreksireturpenjualan.hrgkoreksi",
            18 => "pj.koreksireturpenjualan.keterangan",
            19 => "pj.koreksireturpenjualan.kategoriidrpj",
            20 => "pj.koreksireturpenjualan.koreksirjparentid",
            21 => "pj.koreksireturpenjualan.newchildid",
            22 => "pj.koreksireturpenjualan.isarowid",
            23 => "pj.koreksireturpenjualan.createdby",
            24 => "pj.koreksireturpenjualan.createdon",
            25 => "pj.koreksireturpenjualan.lastupdatedby",
            26 => "pj.koreksireturpenjualan.lastupdatedon",
            27 => "pj.koreksireturpenjualan.karyawanidsalesman",
            28 => "mstr.toko.kodetoko",
            29 => "mstr.toko.namatoko",
            30 => "pj.returpenjualandetail.id as returpenjualandetailid",
          ])
          ->leftjoin("pj.returpenjualan", "pj.returpenjualan.id", "=", "returpenjualanid")
          ->leftjoin("pj.returpenjualandetail", "pj.returpenjualandetail.newchildid", "=", "pj.koreksireturpenjualan.id")
          ->leftjoin("mstr.toko", "pj.returpenjualan.tokoid", "=", "mstr.toko.id")
          ->leftjoin("mstr.subcabang", "pj.returpenjualan.recordownerid", "=", "mstr.subcabang.id")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pj.koreksireturpenjualan.createdon"), ">=", $tglmulai)
                ->where(DB::raw("pj.koreksireturpenjualan.createdon"), "<=", $tglselesai);
          })
          ->orWhere(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pj.returpenjualan.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pj.returpenjualan.createdon::date"), "<=", $tglselesai);
          })
          ->where("mstr.subcabang.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($details as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchPEHeader(Request $req)
    {
        $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
        $cabang = $req->cabang;

        $details = Pengiriman::select([
            0 => "pj.pengiriman.id",
            1 => "pj.pengiriman.recordownerid",
            2 => DB::raw("to_char(pj.pengiriman.tglkirim, 'YYYY-MM-DD') as tglkrm"),
            3 => DB::raw("to_char(pj.pengiriman.tglkembali, 'YYYY-MM-DD') as tglkmb"),
            4 => "pj.pengiriman.nokirim",
            5 => "pj.pengiriman.karyawanidsopir",
            6 => "pj.pengiriman.karyawanidkernet",
            7 => "pj.pengiriman.armadakirimid",
            8 => "pj.pengiriman.kmberangkat",
            9 => "pj.pengiriman.kmkembali",
            10 => "pj.pengiriman.catatan",
            11 => "pj.pengiriman.print",
            12 => DB::raw("to_char(pj.pengiriman.tglprint, 'YYYY-MM-DD') as tglprnt"),
            13 => "pj.pengiriman.createdby",
            14 => "pj.pengiriman.lastupdatedby",
            15 => "pj.pengiriman.isarowid",
            16 => "mstr.armadakirim.nomorpolisi",
            17 => "sopir.namakaryawan as sopir",
            18 => "kernet.namakaryawan as kernet",
          ])
          ->leftjoin("mstr.armadakirim", "mstr.armadakirim.id", "=", "pj.pengiriman.armadakirimid")
          ->leftjoin("hr.karyawan as sopir", "sopir.id", "=", "pj.pengiriman.karyawanidsopir")
          ->leftjoin("hr.karyawan as kernet", "kernet.id", "=", "pj.pengiriman.karyawanidkernet")
          ->leftjoin("mstr.subcabang as subcbowner", "pj.pengiriman.recordownerid", "=", "subcbowner.id")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pj.pengiriman.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pj.pengiriman.createdon::date"), "<=", $tglselesai);
          })
          ->where("subcbowner.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($details as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchPEDetail(Request $req)
    {
        $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
        $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
        $cabang = $req->cabang;

        $details = PengirimanDetail::select([
            0 => "pj.pengirimandetail.id",
            1 => "pj.pengirimandetail.pengirimanid",
            2 => "pj.pengirimandetail.suratjalanid",
            3 => "pj.pengirimandetail.keteranganpending",
            4 => "pj.pengirimandetail.createdby",
            5 => "pj.pengirimandetail.lastupdatedby",
            6 => "pj.pengirimandetail.isarowid",
            7 => "pj.suratjalan.isarowid as suratjalanrowid",
            8 => "pj.pengiriman.isarowid as headerrowid",
          ])
          ->leftjoin("pj.pengiriman", "pj.pengiriman.id", "=", "pj.pengirimandetail.pengirimanid")
          ->leftjoin("pj.suratjalan", "pj.suratjalan.id", "=", "pj.pengirimandetail.suratjalanid")
          ->leftjoin("mstr.subcabang as subcbowner", "pj.pengiriman.recordownerid", "=", "subcbowner.id")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pj.pengiriman.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pj.pengiriman.createdon::date"), "<=", $tglselesai);
          })
          ->orWhere(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pj.pengirimandetail.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pj.pengirimandetail.createdon::date"), "<=", $tglselesai);
          })
          ->where("subcbowner.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($details as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchOHHeader(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      // dd($tglmulai);

      $order = OrderPembelian::select([
            // 0 => "pj.orderpenjualansynch.",
            0 => "pb.orderpembelian.id",
            1 => "pb.orderpembelian.recordownerid",
            2 => DB::raw("to_char(pb.orderpembelian.tglorder, 'yyyy-MM-dd') as tglord"),
            3 => "pb.orderpembelian.noorder",
            4 => "pb.orderpembelian.supplierid",
            5 => "pb.orderpembelian.tempo",
            6 => "pb.orderpembelian.keterangan",
            7 => "pb.orderpembelian.approvalmgmtid",
            8 => "pb.orderpembelian.isarowid",
            9 => "pb.orderpembelian.createdby",
            10 => "pb.orderpembelian.lastupdatedby",
            11 => DB::raw("pb.orderpembelian.sync11::date as synch11"),
            12 => "mstr.cabang.kodecabang",
            13 => "mstr.supplier.nama as supplier",
          ])
          ->leftjoin("mstr.subcabang", "mstr.subcabang.id", "=", "pb.orderpembelian.recordownerid")
          ->leftjoin("mstr.cabang", "mstr.cabang.id", "=", "mstr.subcabang.cabangid")
          ->leftjoin("mstr.supplier", "mstr.supplier.id", "=", "pb.orderpembelian.supplierid")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pb.orderpembelian.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pb.orderpembelian.createdon::date"), "<=", $tglselesai);
          })
          ->where("mstr.subcabang.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($order as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchOHDetail(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      // dd($tglmulai);

      $order = OrderPembelianDetail::select([
            // 0 => "pj.orderpenjualansynch.",
            0 => "pb.orderpembeliandetail.id",
            1 => "pb.orderpembeliandetail.orderpembelianid",
            2 => "pb.orderpembeliandetail.stockid",
            3 => "pb.orderpembeliandetail.qtyorder",
            4 => "pb.orderpembeliandetail.qtypenjualanbo",
            5 => "pb.orderpembeliandetail.qtyrataratajual",
            6 => "pb.orderpembeliandetail.qtystokakhir",
            7 => "pb.orderpembeliandetail.hrgsatuanbrutto",
            8 => "pb.orderpembeliandetail.disc1",
            9 => "pb.orderpembeliandetail.disc2",
            10 => "pb.orderpembeliandetail.ppn",
            11 => "pb.orderpembeliandetail.hrgsatuannetto",
            12 => "pb.orderpembeliandetail.keterangan",
            13 => "pb.orderpembeliandetail.isarowid",
            14 => "pb.orderpembeliandetail.createdby",
            15 => "pb.orderpembeliandetail.lastupdatedby",
            16 => "pb.orderpembelian.isarowid as headerrowid",
            17 => "mstr.stock.kodebarang",
            18 => "mstr.stock.namabarang",
            19 => "mstr.subcabang.kodesubcabang",
          ])
          ->leftjoin("pb.orderpembelian", "pb.orderpembelian.id", "=", "pb.orderpembeliandetail.orderpembelianid")
          ->leftjoin("mstr.subcabang", "mstr.subcabang.id", "=", "pb.orderpembelian.recordownerid")
          ->leftjoin("mstr.stock", "mstr.stock.id", "=", "pb.orderpembeliandetail.stockid")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pb.orderpembelian.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pb.orderpembelian.createdon::date"), "<=", $tglselesai);
          })
          ->orWhere(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pb.orderpembeliandetail.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pb.orderpembeliandetail.createdon::date"), "<=", $tglselesai);
          })
          ->where("mstr.subcabang.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($order as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchPBHeader(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      // dd($tglmulai);

      $order = NotaPembelianMurni::select([
            // 0 => "pj.orderpenjualansynch.",
            0 => "pb.notapembelianmurni.id",
            1 => "pb.notapembelianmurni.recordownerid",
            2 => "pb.notapembelianmurni.noorder",
            3 => DB::raw("pb.notapembelianmurni.tglnota::date"),
            4 => "pb.notapembelianmurni.nonota",
            5 => "pb.notapembelianmurni.supplierid",
            6 => DB::raw("pb.notapembelianmurni.tglterima::date"),
            7 => "pb.notapembelianmurni.expedisiid",
            8 => "pb.notapembelianmurni.staffidpemeriksa00",
            9 => "pb.notapembelianmurni.keterangan",
            10 => "pb.notapembelianmurni.staffidpemeriksa11",
            11 => "pb.notapembelianmurni.isarowid",
            12 => "pb.notapembelianmurni.createdby",
            13 => "pb.notapembelianmurni.lastupdatedby",
            14 => "mstr.cabang.kodecabang",
            15 => "mstr.supplier.nama as supplier",
            16 => "mstr.expedisi.namaexpedisi",
            17 => "p00.namakaryawan as staff00",
            18 => "p11.namakaryawan as staff11",
          ])
          ->leftjoin("mstr.subcabang", "mstr.subcabang.id", "=", "pb.notapembelianmurni.recordownerid")
          ->leftjoin("mstr.cabang", "mstr.cabang.id", "=", "mstr.subcabang.cabangid")
          ->leftjoin("mstr.supplier", "mstr.supplier.id", "=", "pb.notapembelianmurni.supplierid")
          ->leftjoin("mstr.expedisi", "mstr.expedisi.id", "=", "pb.notapembelianmurni.expedisiid")
          ->leftjoin("hr.karyawan as p00", "p00.id", "=", "pb.notapembelianmurni.staffidpemeriksa00")
          ->leftjoin("hr.karyawan as p11", "p11.id", "=", "pb.notapembelianmurni.staffidpemeriksa11")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pb.notapembelianmurni.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pb.notapembelianmurni.createdon::date"), "<=", $tglselesai);
          })
          ->where("mstr.subcabang.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($order as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchPBDetail(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      // dd($tglmulai);

      $order = NotaPembelianDetail::select([
            // 0 => "pj.orderpenjualansynch.",
            0 => "pb.notapembeliandetail.id",
            1 => "pb.notapembeliandetail.notapembelianid",
            2 => "pb.notapembeliandetail.stockid",
            3 => "pb.notapembeliandetail.qtynota",
            4 => "pb.notapembeliandetail.qtyterima",
            5 => "pb.notapembeliandetail.hrgsatuanbrutto",
            6 => "pb.notapembeliandetail.disc1",
            7 => "pb.notapembeliandetail.disc2",
            8 => "pb.notapembeliandetail.ppn",
            9 => "pb.notapembeliandetail.hrgsatuannetto",
            10 => "pb.notapembeliandetail.keterangan",
            11 => "pb.notapembeliandetail.koreksipbparentid",
            12 => "pb.notapembeliandetail.newchildid",
            13 => "pb.notapembeliandetail.isarowid",
            14 => "pb.notapembeliandetail.createdby",
            15 => "pb.notapembeliandetail.lastupdatedby",
            16 => "pb.notapembelianmurni.isarowid as headerrowid",
            17 => "mstr.stock.kodebarang",
            18 => "mstr.stock.namabarang",
            19 => "mstr.subcabang.kodesubcabang",
          ])
          ->join("pb.notapembelianmurni", "pb.notapembelianmurni.id", "=", "pb.notapembeliandetail.notapembelianid")
          ->leftjoin("mstr.subcabang", "mstr.subcabang.id", "=", "pb.notapembelianmurni.recordownerid")
          ->leftjoin("mstr.stock", "mstr.stock.id", "=", "pb.notapembeliandetail.stockid")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pb.notapembelianmurni.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pb.notapembelianmurni.createdon::date"), "<=", $tglselesai);
          })
          ->orWhere(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pb.notapembeliandetail.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pb.notapembeliandetail.createdon::date"), "<=", $tglselesai);
          })
          ->where("mstr.subcabang.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($order as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchPBKoreksi(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      // dd($tglmulai);

      $order = KoreksiNotaPembelian::select([
            // 0 => "pj.orderpenjualansynch.",
            0 => "pb.koreksinotapembelian.id",
            1 => "pb.koreksinotapembelian.recordownerid",
            2 => DB::raw("pb.koreksinotapembelian.tglnota::date as tglnote"),
            3 => "pb.koreksinotapembelian.nonota",
            4 => "pb.koreksinotapembelian.notapmbeliandetailid",
            5 => "pb.koreksinotapembelian.notapembeliandetailrowid",
            6 => "pb.koreksinotapembelian.stockid",
            7 => "pb.koreksinotapembelian.qtynota",
            8 => "pb.koreksinotapembelian.hrgsatuannetto",
            9 => "pb.koreksinotapembelian.keterangan",
            10 => "pb.koreksinotapembelian.supplierid",
            11 => "pb.koreksinotapembelian.hrgkoreksi",
            12 => "pb.koreksinotapembelian.qtykoreksi",
            13 => "pb.koreksinotapembelian.createdby",
            14 => "pb.koreksinotapembelian.lastupdatedby",
            15 => "mstr.stock.kodebarang",
            16 => "mstr.stock.namabarang",
            17 => "mstr.supplier.nama as supplier",
            18 => "pb.notapembeliandetail.isarowid",
          ])
          ->join("pb.notapembeliandetail", "pb.koreksinotapembelian.id", "=", "pb.notapembeliandetail.id")
          ->leftjoin("mstr.subcabang", "mstr.subcabang.id", "=", "pb.koreksinotapembelian.recordownerid")
          ->leftjoin("mstr.stock", "mstr.stock.id", "=", "pb.koreksinotapembelian.stockid")
          ->leftjoin("mstr.supplier", "mstr.supplier.id", "=", "pb.koreksinotapembelian.supplierid")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pb.koreksinotapembelian.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pb.koreksinotapembelian.createdon::date"), "<=", $tglselesai);
          })
          ->where("mstr.subcabang.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($order as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchRBHeader(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      // dd($tglmulai);

      $order = ReturPembelianMurni::select([
            // 0 => "pj.orderpenjualansynch.",
            0 => "pb.returpembelianmurni.id",
            1 => "pb.returpembelianmurni.recordownerid",
            2 => DB::raw("pb.returpembelianmurni.tglprb::date"),
            3 => "pb.returpembelianmurni.noprb",
            4 => "pb.returpembelianmurni.supplierid",
            5 => DB::raw("pb.returpembelianmurni.tglkirim::date"),
            6 => "pb.returpembelianmurni.staffidpemeriksa00",
            7 => "pb.returpembelianmurni.expedisiid",
            8 => "pb.returpembelianmurni.qtykoli",
            9 => "pb.returpembelianmurni.nokoli",
            10 => "pb.returpembelianmurni.tglnrj11",
            11 => "pb.returpembelianmurni.nonrj11",
            12 => "pb.returpembelianmurni.staffpemeriksa11",
            13 => "pb.returpembelianmurni.keterangan",
            14 => "pb.returpembelianmurni.nprint",
            15 => "pb.returpembelianmurni.ohpsdepo",
            16 => "pb.returpembelianmurni.isarowid",
            17 => "pb.returpembelianmurni.createdby",
            18 => "pb.returpembelianmurni.lastupdatedby",
            19 => DB::raw("pb.returpembelianmurni.sync11::date"),
            20 => "mstr.cabang.kodecabang",
            21 => "mstr.supplier.nama as supplier",
            22 => "mstr.expedisi.namaexpedisi",
            23 => "p00.namakaryawan as staff00",
            // 24 => "p11.namakaryawan as staff11",
          ])
          ->leftjoin("mstr.subcabang", "mstr.subcabang.id", "=", "pb.returpembelianmurni.recordownerid")
          ->leftjoin("mstr.cabang", "mstr.cabang.id", "=", "mstr.subcabang.cabangid")
          ->leftjoin("mstr.supplier", "mstr.supplier.id", "=", "pb.returpembelianmurni.supplierid")
          ->leftjoin("mstr.expedisi", "mstr.expedisi.id", "=", "pb.returpembelianmurni.expedisiid")
          ->leftjoin("hr.karyawan as p00", "p00.id", "=", "pb.returpembelianmurni.staffidpemeriksa00")
          // ->leftjoin("hr.karyawan as p11", "p11.id", "=", "pb.returpembelianmurni.staffpemeriksa11")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pb.returpembelianmurni.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pb.returpembelianmurni.createdon::date"), "<=", $tglselesai);
          })
          ->where("mstr.subcabang.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($order as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchRBDetail(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      // dd($tglmulai);

      $order = ReturPembelianDetail::select([
            // 0 => "pj.orderpenjualansynch.",
            0 => "pb.returpembeliandetail.id",
            1 => "pb.returpembeliandetail.returpembelianid",
            2 => "pb.returpembeliandetail.historispembelian",
            3 => "pb.returpembeliandetail.notapembeliandetailid",
            4 => "pb.returpembeliandetail.stockid",
            5 => "pb.returpembeliandetail.qtyprb",
            6 => "pb.returpembeliandetail.qtynrj",
            7 => "pb.returpembeliandetail.qtyrq11",
            8 => "pb.returpembeliandetail.hargabruttonrj",
            9 => "pb.returpembeliandetail.disc1",
            10 => "pb.returpembeliandetail.disc2",
            11 => "pb.returpembeliandetail.ppn",
            12 => "pb.returpembeliandetail.harganettonrj",
            13 => "pb.returpembeliandetail.kategoriprbid",
            14 => "pb.returpembeliandetail.nokoli",
            15 => "pb.returpembeliandetail.keteranganprb",
            16 => "pb.returpembeliandetail.koreksirpbparentid",
            17 => "pb.returpembeliandetail.newchildid",
            18 => "pb.returpembeliandetail.isarowid",
            19 => "pb.returpembeliandetail.createdby",
            20 => "pb.returpembeliandetail.lastupdatedby",
            21 => "pb.returpembelianmurni.isarowid as headerrowid",
            22 => "mstr.stock.kodebarang",
            23 => "mstr.stock.namabarang",
            24 => "pb.notapembeliandetail.isarowid as notapembeliandetailrowid",
            25 => "mstr.subcabang.kodesubcabang",
            26 => "pb.returpembelianmurni.tglkirim",
          ])
          ->join("pb.returpembelianmurni", "pb.returpembelianmurni.id", "=", "pb.returpembeliandetail.returpembelianid")
          ->leftjoin("pb.notapembeliandetail", "pb.notapembeliandetail.id", "=", "pb.returpembeliandetail.notapembeliandetailid")
          ->leftjoin("mstr.subcabang", "mstr.subcabang.id", "=", "pb.returpembelianmurni.recordownerid")
          ->leftjoin("mstr.stock", "mstr.stock.id", "=", "pb.notapembeliandetail.stockid")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pb.returpembelianmurni.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pb.returpembelianmurni.createdon::date"), "<=", $tglselesai);
          })
          ->orWhere(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pb.returpembeliandetail.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pb.returpembeliandetail.createdon::date"), "<=", $tglselesai);
          })
          ->where("mstr.subcabang.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($order as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }

    public function synchRBKoreksi(Request $req)
    {
      $tglmulai = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();
      $cabang = $req->cabang;

      // dd($tglmulai);

      $order = KoreksiReturPembelian::select([
            // 0 => "pj.orderpenjualansynch.",
            0 => "pb.koreksireturpembelian.id",
            1 => "pb.koreksireturpembelian.recordownerid",
            2 => DB::raw("pb.koreksireturpembelian.tglprb::date as tglrpb"),
            3 => "pb.koreksireturpembelian.noprb",
            4 => "pb.koreksireturpembelian.returpembeliandetailid",
            5 => "pb.koreksireturpembelian.returpembeliandetailrowid",
            6 => "pb.koreksireturpembelian.stockid",
            7 => "pb.koreksireturpembelian.qtyprb",
            8 => "pb.koreksireturpembelian.harganettonrj",
            9 => "pb.koreksireturpembelian.keteranganprb",
            10 => "pb.koreksireturpembelian.supplierid",
            11 => "pb.koreksireturpembelian.hrgkoreksi",
            12 => "pb.koreksireturpembelian.qtykoreksi",
            13 => "pb.koreksireturpembelian.createdby",
            14 => "pb.koreksireturpembelian.lastupdatedby",
            15 => "mstr.stock.kodebarang",
            16 => "mstr.stock.namabarang",
            17 => "mstr.supplier.nama as supplier",
            18 => "pb.koreksireturpembelian.isarowid",
          ])
          ->leftjoin("mstr.subcabang", "mstr.subcabang.id", "=", "pb.koreksireturpembelian.recordownerid")
          ->leftjoin("mstr.stock", "mstr.stock.id", "=", "pb.koreksireturpembelian.stockid")
          ->leftjoin("mstr.supplier", "mstr.supplier.id", "=", "pb.koreksireturpembelian.supplierid")
          ->where(function($query) use ($tglmulai, $tglselesai){
              $query->where(DB::raw("pb.koreksireturpembelian.createdon::date"), ">=", $tglmulai)
                ->where(DB::raw("pb.koreksireturpembelian.createdon::date"), "<=", $tglselesai);
          })
          ->where("mstr.subcabang.kodesubcabang", $cabang)
          ->get();

      $data = [];
      foreach ($order as $key => $value) {
        $data[$key] = $value->toArray();
      }

      return json_encode($data);
    }
}
