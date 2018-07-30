<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Toko;
use App\Models\Tagihan;
use App\Models\TagihanDetail;
use App\Models\KartuPiutang;
use App\Models\KartuPiutangDetail;
use App\Models\SubCabang;
use App\Models\Salesman;
use App\Models\Karyawan;
use Carbon\Carbon;
use DB;

class TagihanController extends Controller
{
  private function numPad($val, $len) {
    $val = strval($val);
    $len = intval($len);
    if (strlen($val) < $len) {
      $tmp1 = '';
      for ($i = strlen($val); $i < $len; $i++) $tmp1 .= '0';
      $val = $tmp1 . $val;
    }
    return v;
  }

  public function apiSynch(Request $req) {
    $data = null;
    if (isset($req->data)) $data = $req->data;

    $res = [
      'Result' => false,
      'Msg' => 'Tidak ada data'
    ];
    $done = function ($msg = "", $ok = null) use($res) {
      $res["Result"] = ($ok == null ? (($msg == "") ? true : false) : $ok);
      if ($msg != "") $res["Msg"] = $msg;
      else if(isset($res["Msg"])) unset($res["Msg"]);
      return response()->json($res);
    };

    if ($data != null) {
      try {
        if (gettype($data) != "array") $data = json_decode(strval($data), true);

      } catch (\Exception $ex) { return $done("Error parsing data"); }

      // checking fields
      $allow = $this->CheckFields($data, ["Tagihan", "KartuPiutang", "Toko", "KodeGudang"]);
      if (count($allow) > 0) return $done("Missing data: " . join($allow, ", "));

      $gudang = SubCabang::where(DB::raw("trim(kodesubcabang)"), trim(strval($data["KodeGudang"])))->first();
      if (count($gudang) <= 0) return $done("SubCabang not found: " . strval($data["KodeGudang"]));

      $push = [
        "Tagihan" => [],
        "TagihanDetail" => [],
        "KartuPiutang" => [],
        "KartuPiutangDetail" => [],
        "Toko" => [],
        "Collector" => [],
        "Sales" => []
      ];

      // -- check

      // 0: Tagihan & TagihanDetail
      $allow = [
        ["HRowID", "HTglReg", "HNoReg", "HTglKembali", "HPeriode2", "HCollectorID", "HWilayah", "HNPrint", "HLastUpdatedBy", "HLastUpdatedTime", "HSyncFlag", "Details"],
        ["RowID", "KPID", "RpTagih", "Keterangan", "LastUpdatedBy", "LastUpdatedTime"]
      ];
      foreach ($data["Tagihan"] as $k => $d) {
        $na = $this->CheckFields($d, $allow[0]);
        if (count($na) <= 0) {
          foreach ($d["Details"] as $d2) {
            $na = $this->CheckFields($d2, $allow[1]);
            if (count($na) > 0) return $done("Missing data on Tagihan Detail: " . join($na, ", "));
          }

        } else return $done("Missing data on Tagihan: " . join($na, ", "));
      }

      // 1: KartuPiutang & KartuPiutangDetail
      $allow = [
        ["HRowID", "HTagihanDetailID", "HTokoID", "HKodeToko", "HKodeSales", "HTglTransaksi", "HNoTransaksi", "HTglJatuhTempo", "HJangkaWaktu", "HHariKirim", "HHariSales", "HUraian", "HTransactionType", "HTglKomitmuenKaAdm", "HKeteranganKomitmenKaAdm", "HTglKomitmenPKP", "HKeteranganKomitmenPKP", "HLastUpdatedBy", "HLastUpdatedTime", "Details"],
        ["RowID", "TglTransaksi", "KodeTransaksi", "Debet", "Uraian", "LastUpdatedBy", "LastUpdatedTime"]
      ];
      foreach ($data["KartuPiutang"] as $k => $d) {
        $na = $this->CheckFields($d, $allow[0]);
        if (count($na) <= 0) {
          foreach ($d["Details"] as $d2) {
            $na = $this->CheckFields($d2, $allow[1]);
            if (count($na) > 0) return $done("Missing data on KartuPiutang Detail: " . join($na, ", "));
          }

        } else return $done("Missing data on KartuPiutang: " . join($na, ", "));
      }

      // 2: Toko
      $allow = ["RowID", "TokoID", "TokoIDLama", "KodeToko", "NamaToko", "Alamat", "Propinsi", "Kota", "WillID", "Telp", "Fax", "PenanggungJawab", "Tgl1st", "Catatan", "nama_pemilik", "jenis_kelamin", "tempat_lhr", "TglLahir", "email", "no_rekening", "nama_bank", "np_npwp", "TipeBisnis", "HP", "LastUpdatedBy", "LastUpdatedTime"];
      foreach ($data["Toko"] as $k => $d) {
        $na = $this->CheckFields($d, $allow);
        if (count($na) > 0) return $done("Missing data on Toko: " . join($na, ", "));
      }

      // 3: Collector / Karyawan
      $allow = ["Nama", "TglMasuk", "TglKeluar", "Kode", "LastUpdatedBy", "LastUpdatedTime"];
      foreach ($data["Collector"] as $k => $d) {
        $na = $this->CheckFields($d, $allow);
        if (count($na) > 0) return $done("Missing data on Collector: " . join($na, ", "));
      }

      // 4: Sales / Karyawan
      $allow = ["NamaSales", "TglMasuk", "TglKeluar", "SalesID", "LastUpdatedBy", "LastUpdatedTime"];
      foreach ($data["Sales"] as $k => $d) {
        $na = $this->CheckFields($d, $allow);
        if (count($na) > 0) return $done("Missing data on Sales: " . join($na, ", "));
      }

      // -- preset

      $npreset = "Prepare";
      try {
        $cols = [
          "isarowid" => "RowID",
          "tokoidwarisan" => "TokoID",
          "tokoidwarisanlama" => "TokoIDLama",
          "kodetoko" => "KodeToko",
          "namatoko" => "NamaToko",
          "alamat" => "Alamat",
          "propinsi" => "Propinsi",
          "kota" => "Kota",
          "customwilayah" => "WillID",
          "telp" => "Telp",
          "fax" => "Fax",
          "penanggungjawab" => "PenanggungJawab",
          "tgldob" => "Tgl1st",
          "catatan" => "Catatan",
          "pemilik" => "nama_pemilik",
          "gender" => "jenis_kelamin",
          "tempatlahir" => "tempat_lhr",
          "tgllahir" => "TglLahir",
          "email" => "email",
          "norekening" => "no_rekening",
          "namabank" => "nama_bank",
          "nonpwp" => "np_npwp",
          "tipebisnis" => "TipeBisnis",
          "hp" => "HP",
          "createdby" => "!WISER",
          "createdon" => "!" . date("Y-m-d H:i:s"),
          "lastupdatedby" => "LastUpdatedBy",
          "lastupdatedon" => "LastUpdatedTime"
        ];
        foreach ($data["Toko"] as $k => $v) {
          $npreset = "Toko";
          if (!Toko::where([["kodetoko", "=", $v["KodeToko"]], ["tokoidwarisan", "=", $v["TokoID"]]])->exists()) {
            $cur = new Toko();
            foreach ($cols as $ck => $cv) {
              $cvv = @$v[$cv];
              if (gettype($cv) == "string") {
                if (@$cv[0] == "!" && @$cv[1] != "!") $cvv = substr($cv, 1);
                else if (@$cv[0] == "!" && @$cv[1] == "!") $cvv = $v[substr($cv, 1)];
              } else $cvv = $cv;
              switch ($ck) {
                case "gender":
                  $cvv = strtoupper(substr($cvv, 0, 1));
                  break;
              }
              $cur->$ck = $cvv;
            }
            $cur->save();
          }
        }
        
        $cols = [
          "namakaryawan" => "Nama",
          "tglmasuk" => "TglMasuk",
          "tglkeluar" => "TglKeluar",
          "kodecollector" => "Kode",
          "createdby" => "!WISER",
          "createdon" => "!" . date("Y-m-d H:i:s"),
          "lastupdatedby" => "LastUpdatedBy",
          "lastupdatedon" => "LastUpdatedTime"
        ];
        foreach ($data["Collector"] as $k => $v) {
          $npreset = "Collector";
          if (!Karyawan::where([["kodecollector", "=", $k], ["recordownerid", "=", $gudang->id]])->exists()) {
            $cur = new Karyawan();
            $lid = Karyawan::orderBy("id", "desc")->first();
            $cur->id = ((count($lid) > 0 ? $lid->id : 0) + 1);
            $cur->recordownerid = $gudang->id;
            foreach ($cols as $ck => $cv) {
              $cvv = @$v[$cv];
              if (gettype($cv) == "string") {
                if (@$cv[0] == "!" && @$cv[1] != "!") $cvv = substr($cv, 1);
                else if (@$cv[0] == "!" && @$cv[1] == "!") $cvv = $v[substr($cv, 1)];
              } else $cvv = $cv;
              $cur->$ck = $cvv;
            }
            $cur->save();
          }
        }

        $cols = [
          "namakaryawan" => "NamaSales",
          "tglmasuk" => "TglMasuk",
          "tglkeluar" => "TglKeluar",
          "kodesales" => "SalesID",
          "createdby" => "!WISER",
          "createdon" => "!" . date("Y-m-d H:i:s"),
          "lastupdatedby" => "LastUpdatedBy",
          "lastupdatedon" => "LastUpdatedTime"
        ];
        foreach ($data["Sales"] as $k => $v) {
          $npreset = "Sales";
          if (!Karyawan::where([["kodesales", "=", $k], ["recordownerid", "=", $gudang->id]])->exists()) {
            $cur = new Karyawan();
            $lid = Karyawan::orderBy("id", "desc")->first();
            $cur->id = ((count($lid) > 0 ? $lid->id : 0) + 1);
            $cur->recordownerid = $gudang->id;
            foreach ($cols as $ck => $cv) {
              $cvv = @$v[$cv];
              if (gettype($cv) == "string") {
                if (@$cv[0] == "!" && @$cv[1] != "!") $cvv = substr($cv, 1);
                else if (@$cv[0] == "!" && @$cv[1] == "!") $cvv = $v[substr($cv, 1)];
              } else $cvv = $cv;
              $cur->$ck = $cvv;
            }
            $cur->save();
          }
        }

      } catch (\Exception $ex) {
        return $done("Error while preset " . $npreset . ", " . $ex->getMessage());
      }

      // -- prepare

      $cols = [
        [
          "isarowid" => "HRowID",
          "tglreg" => "HTglReg",
          "noreg" => "HNoReg",
          "tglkembali" => "HTglKembali",
          "karyawanid" => "HCollectorID",
          "wilayah" => "HWilayah",
          "tgljtnota" => "HPeriode2",
          "print" => "HNPrint",
          "createdby" => "!WISER",
          "createdon" => "!" . date("Y-m-d H:i:s"),
          "lastupdatedby" => "HLastUpdatedBy",
          "lastupdatedon" => "HLastUpdatedTime",
          //"synch" => "HSyncFlag"
        ],
        [
          "isarowid" => "RowID",
          "rptagih" => "RpTagih",
          "keterangan" => "Keterangan",
          "createdby" => "!WISER",
          "createdon" => "!" . date("Y-m-d H:i:s"),
          "lastupdatedby" => "LastUpdatedBy",
          "lastupdatedon" => "LastUpdatedTime"
        ]
      ];

      $delst = [];
      foreach ($data["Tagihan"] as $k => $v) {
        //if (Tagihan::where("isarowid", $k)->exists()) {
        if (true) {
          $dlsts = [];
          $cur = Tagihan::where([
            ["isarowid", "=", $k],
            ["noreg", "=", $v['HNoReg']],
            ["recordownerid", "=", $gudang->id]
          ])->first();

          if (count($cur) <= 0) {
            $cur = new Tagihan();
            $cur->tag = ['itnew' => true];

          } else {
            $dlsts = TagihanDetail::where("tagihanid", $cur->id)->selectRaw("id, isarowid")->get()->toArray();
            $cur->tag = ['itnew' => false];
          }

          $cur->statusaktif = true;
          $cur->recordownerid = $gudang->id;
          $cur->approvalkasir = (@$v["kasir"] ? true : false);
          foreach ($cols[0] as $ck => $cv) {
            $cvv = @$v[$cv];
            if (gettype($cv) == "string") {
              if (@$cv[0] == "!" && @$cv[1] != "!") $cvv = substr($cv, 1);
              else if (@$cv[0] == "!" && @$cv[1] == "!") $cvv = $v[substr($cv, 1)];
            } else $cvv = $cv;

            switch ($ck) {
              case "karyawanid":
                $cvv2 = Karyawan::where([["kodesales", "=", $cvv], ["recordownerid", "=", $gudang->id]])->first();
                if (count($cvv2) <= 0) {
                  $cvv2 = Karyawan::where([["kodecollector", "=", $cvv], ["recordownerid", "=", $gudang->id]])->first();
                  if (count($cvv2) <= 0) {
                    if (isset($data["Collector"][$cvv]) || isset($data["Sales"][$cvv])) {
                      if ($cur->tag !== null) $cur->tag['karyawanid'] = $cvv;
                      else $cur->tag = ['karyawanid' => $cvv];
                      $cvv = 0;
                    }
                    else return $done("Cannot found Sales/Karyawan for Tagihan: " . $k);
                  }
                }

                if (count($cvv2) > 0) $cvv = $cvv2->id;
                break;
            }
            $cur->$ck = $cvv;
          }
          $push["Tagihan"][$k] = $cur;

          foreach ($v["Details"] as $v2) {
            //if (TagihanDetail::where("isarowid", $v2["RowID"])->exists()) {
            if (true) {
              $cur = TagihanDetail::where("isarowid", "=", $v2["RowID"])->first();
              foreach ($dlsts as $k3 => $v3) if ($v3['isarowid'] == $v2['RowID']) unset($dlsts[$k3]);

              if (count($cur) <= 0) {
                $cur = new TagihanDetail();
                $cur->tag = ['itnew' => true];
              } else $cur->tag = ['itnew' => false];

              $cur->tag['tagihan'] = $k;
              $cur->tag['kartupiutang'] = $v2["KPID"];

              foreach ($cols[1] as $ck => $cv) {
                $cvv = @$v2[$cv];
                if (gettype($cv) == "string") {
                  if (@$cv[0] == "!" && @$cv[1] != "!") $cvv = substr($cv, 1);
                  else if (@$cv[0] == "!" && @$cv[1] == "!") $cvv = $v2[substr($cv, 1)];
                } else $cvv = $cv;
                switch ($ck) {
                  case "kartupiutangid":
                    $cvv = 0;
                    break;
                }
                $cur->$ck = $cvv;
              }
              $push["TagihanDetail"][$v2["RowID"]] = $cur;
            }
          }
          if (count($dlsts) > 0) foreach ($dlsts as $v3) $delst[] = $v3['id'];
        }
      }

      $cols = [
        [
          "isarowid" => "HRowID",
          "tokoid" => "HKodeToko",
          "karyawanidsalesman" => "HKodeSales",
          "tglproforma" => "HTglTransaksi",
          "tglterima" => "HTglLink",
          "nonota" => "HNoTransaksi",
          "tgljt" => "HTglJatuhTempo",
          "temponota" => "HJangkaWaktu",
          "tempokirim" => "HHariKirim",
          "temposalesman" => "HHariSales",
          "uraian" => "HUraian",
          "tipetrans" => "HTransactionType",
          "tglklsudahjt" => "HTglKomitmuenKaAdm",
          "ketklsudahjt" => "HKeteranganKomitmenKaAdm",
          "tglklakanjt" => "HTglKomitmenPKP",
          "ketklakanjt" => "HKeteranganKomitmenPKP",
          "createdby" => "!WISER",
          "createdon" => "!" . date("Y-m-d H:i:s"),
          "lastupdatedby" => "HLastUpdatedBy",
          "lastupdatedon" => "HLastUpdatedTime"
        ],
        [
          "isarowid" => "RowID",
          "tgltrans" => "TglTransaksi",
          "kodetrans" => "KodeTransaksi",
          "nomtrans" => "Debet",
          "uraian" => "Uraian",
          "createdby" => "!WISER",
          "createdon" => "!" . date("Y-m-d H:i:s"),
          "lastupdatedby" => "LastUpdatedBy",
          "lastupdatedon" => "LastUpdatedTime"
        ]
      ];
      foreach ($data["KartuPiutang"] as $k => $v) {
        //if (KartuPiutang::where("isarowid", $k)->exists()) {
        if (true) {
          $cur = KartuPiutang::where([
            ["isarowid", "=", $k],
            ["nonota", "=", $v["HNoTransaksi"]],
            ["recordownerid", "=", $gudang->id]
          ])->first();

          if (count($cur) <= 0) {
            $cur = new KartuPiutang();
            $cur->tag = ['itnew' => true];
          } else $cur->tag = ['itnew' => false];

          $cur->recordownerid = $gudang->id;
          foreach ($cols[0] as $ck => $cv) {
            $cvv = @$v[$cv];
            if (gettype($cv) == "string") {
              if (@$cv[0] == "!" && @$cv[1] != "!") $cvv = substr($cv, 1);
              else if (@$cv[0] == "!" && @$cv[1] == "!") $cvv = $v[substr($cv, 1)];
            } else $cvv = $cv;
            switch ($ck) {
              case "tokoid":
                $cvv2 = Toko::where([["kodetoko", "=", $cvv], ["tokoidwarisan", "=", $v["HTokoID"]]])->first();
                if (count($cvv2) <= 0) {
                  if (!isset($data["Toko"][$cvv])) return $done("Cannot found Toko for KartuPiutang: " . $cvv . " | " . $k);
                  else {
                    if ($cur->tag != null) $cur->tag['tokoid'] = $cvv;
                    else $cur->tag = ['tokoid' => $cvv, 'tidwarisan' => $v["HTokoID"]];
                    $cvv = 0;
                  }

                } else $cvv = $cvv2->id;
                break;

              case "karyawanidsalesman":
                $cvv2 = Karyawan::where([["kodesales", "=", $cvv], ["recordownerid", "=", $gudang->id]])->first();

                if (count($cvv2) <= 0) {
                  if (isset($data["Sales"][$cvv])) {
                    if ($cur->tag != null) $cur->tag['karyawanidsalesman'] = $cvv;
                    else $cur->tag = ['karyawanidsalesman' => $cvv];
                    $cvv = 0;
                  }
                  else return $done("Cannot found Sales for KartuPiutang: " . $k);
                }

                if (count($cvv2) > 0) $cvv = $cvv2->id;
                break;
            }
            $cur->$ck = $cvv;
          }
          $push["KartuPiutang"][$k] = $cur;

          foreach ($v["Details"] as $v2) {
            //if (KartuPiutangDetail::where("isarowid", $v2["RowID"])->exists()) {
            if (true) {
              $cur = KartuPiutangDetail::where("isarowid", "=", $v2["RowID"])->first();

              if (count($cur) <= 0) {
                $cur = new KartuPiutangDetail();
                $cur->tag = ['itnew' => true];
              } else $cur->tag = ['itnew' => false];

              $cur->tag['kartupiutang'] = $k;
              $cur->tag['tagihandetail'] = $v["HTagihanDetailID"];

              foreach ($cols[1] as $ck => $cv) {
                $cvv = @$v2[$cv];
                if (gettype($cv) == "string") {
                  if (@$cv[0] == "!" && @$cv[1] != "!") $cvv = substr($cv, 1);
                  else if (@$cv[0] == "!" && @$cv[1] == "!") $cvv = $v2[substr($cv, 1)];
                } else $cvv = $cv;
                switch ($ck) {
                  case "tagihandetailid":
                    $cvv = 0;
                    break;
                }
                $cur->$ck = $cvv;
              }
              $push["KartuPiutangDetail"][$v2["RowID"]] = $cur;
            }
          }
        }
      }

      // -- insert & rollback

      $fins = [
        "Tagihan" => [],
        "TagihanDetail" => [],
        "KartuPiutang" => [],
        "KartuPiutangDetail" => [],
        "Toko" => [],
        "Collector" => [],
        "Sales" => []
      ];
      $rollback = function ($msg = "") use(&$fins, &$res, &$done) {
        try {
          if (gettype($fins) != "array") return;
          foreach ($fins as $f) foreach ($f as $ff) {
            if (isset($ff->tag) && $ff->tag !== null && isset($ff->tag['itnew']) && !$ff->tag['itnew']) continue;
            $ff->delete();
          }
          return $done("Operation error" . (strlen($msg) > 0 ? " " . $msg : ""));

        } catch (\Exception $ex) {
          return $done("Fatal error: " + $msg + "\n" + $ex->getMessage());
        }
      };

      if (count($push["Tagihan"]) <= 0) {
        $res["Count"] = 0;
        return $done("Tidak ada data / Data telah ada", true);
      }

      foreach ($push["Toko"] as $k) {
        try {
          $k->save();
          $fins["Toko"][$k->kodetoko] = $k;

        } catch (\Exception $ex) {
          return $rollback("inserting Toko\n" . $ex->getMessage());
        }
      }

      foreach ($push["Collector"] as $k) {
        try {
          $k->id = (Karyawan::orderBy("id", "desc")->first()->id) + 1;
          $k->save();
          $fins["Collector"][$k->kodecollector] = $k;

        } catch (\Exception $ex) {
          return $rollback("inserting Collector\n" . $ex->getMessage());
        }
      }

      foreach ($push["Sales"] as $k) {
        try {
          $k->id = (Salesman::orderBy("id", "desc")->first()->id) + 1;
          $k->save();
          $fins["Sales"][$k->kodesales] = $k;

        } catch (\Exception $ex) {
          return $rollback("inserting Sales\n" . $ex->getMessage());
        }
      }

      foreach ($push["Tagihan"] as $k) {
        try {
          // > karyawanid
          if ($k->karyawanid == 0) {
            if ($k->tag !== null) {
              if (isset($fins["Collector"][$k->tag['karyawanid']])) $k->karyawanid = $fins["Collector"][$k->tag['karyawanid']]->id;
              else if (isset($fins["Sales"][$k->tag['karyawanid']])) $k->karyawanid = $fins["Sales"][$k->tag['karyawanid']]->id;
            }
            if ($k->karyawanid == 0) return $rollback("Cannot found Tagihan.Collector: " . $k->isarowid);
          }
          $k->save();
          $fins["Tagihan"][$k->isarowid] = $k;

        } catch (\Exception $ex) {
          return $rollback("inserting Tagihan\n" . $ex->getMessage());
        }
      }

      foreach ($push["KartuPiutang"] as $k) {
        try {
          // > tokoid
          if ($k->tokoid == 0) {
            if (
              isset($k->tag['tokoid']) &&
              isset($k->tag['tidwarisan']) &&
              isset($fins["Toko"][$k->tag['tokoid']])
            ) $k->tokoid = $fins["Toko"][$k->tag['tokoid']]->id;
            else return $rollback("Cannot found KartuPiutang.Toko: " . $k->isarowid);
          }
          // > karyawanidsalesman
          if ($k->karyawanidsalesman == 0) {
            if (isset($k->tag['karyawanidsalesman']) && isset($fins["Sales"][$k->tag['karyawanidsalesman']])) $k->karyawanidsalesman = $fins["Sales"][$k->tag['karyawanidsalesman']]->id;
            else return $rollback("Cannot found KartuPiutang.Sales: " . $k->isarowid);
          }
          $k->save();
          $fins["KartuPiutang"][$k->isarowid] = $k;

        } catch (\Exception $ex) {
          return $rollback("inserting KartuPiutang\n" . $ex->getMessage());
        }
      }

      foreach ($push["KartuPiutangDetail"] as $k) {
        try {
          // > kartupiutangid
          if (!isset($fins["KartuPiutang"][$k->tag['kartupiutang']])) {
            $cv = KartuPiutang::where([
              ["isarowid", "=", $k->tag['kartupiutang']],
              ["recordownerid", "=", $gudang->id]
            ])->first();
            if (count($cv) <= 0) return $rollback("Cannot found KartuPiutangDetail.KartuPiutang: " . $k->tag['kartupiutang']);
            else $k->kartupiutangid = $cv->id;

          } else $k->kartupiutangid = $fins["KartuPiutang"][$k->tag['kartupiutang']]->id;

          $k->save();
          $fins["KartuPiutangDetail"][$k->isarowid] = $k;

        } catch (\Exception $ex) {
          return $rollback("inserting KartuPiutangDetail\n" . $ex->getMessage());
        }
      }

      foreach ($push["TagihanDetail"] as $k) {
        try {
          // > tagihanid
          if (!isset($fins["Tagihan"][$k->tag['tagihan']])) {
            $cv = Tagihan::where([
              ["isarowid", "=", $k->tag['tagihan']],
              ["recordownerid", "=", $gudang->id]
            ])->first();
            if (count($cv) <= 0) return $rollback("Cannot found TagihanDetail.Tagihan: " . $k->tag['tagihan']);
            else $k->tagihanid = $cv->id;

          } else $k->tagihanid = $fins["Tagihan"][$k->tag['tagihan']]->id;

          // > kartupiutangid
          if (!isset($fins["KartuPiutang"][$k->tag['kartupiutang']])) {
            $cv = KartuPiutang::where([
              ["isarowid", "=", $k->tag['kartupiutang']],
              ["recordownerid", "=", $gudang->id]
            ])->first();

            if (count($cv) <= 0) return $rollback("Cannot found TagihanDetail.KartuPiutang: " . $k->tag['kartupiutang']);
            else $k->kartupiutangid = $cv->id;

          } else $k->kartupiutangid = $fins["KartuPiutang"][$k->tag['kartupiutang']]->id;

          $k->save();
          $fins["TagihanDetail"][$k->isarowid] = $k;

        } catch (\Exception $ex) {
          return $rollback("inserting TagihanDetail\n" . $ex->getMessage());
        }
      }

      // -- finaly, linkin and clean up

      foreach ($fins["KartuPiutangDetail"] as $k) {
        try {
          // > tagihandetailid
          if (!isset($fins["TagihanDetail"][$k->tag['tagihandetail']])) {
            $cv = TagihanDetail::where("isarowid", $k->tag['tagihandetail']);
            $cv = $cv->first();
            if (count($cv) <= 0) return $rollback("Cannot found KartuPiutangDetail.TagihanDetail: " . $k->tag['tagihandetail']);
            else $k->tagihandetailid = $cv->id;

          } else $k->tagihandetailid = $fins["TagihanDetail"][$k->tag['tagihandetail']]->id;

          $k->save();
          $fins["KartuPiutangDetail"][$k->isarowid] = $k;

        } catch (\Exception $ex) {
          return $rollback("updating KartuPiutangDetail\n" . $ex->getMessage());
        }
      }

      if (count($delst) > 0) {
        try {
          TagihanDetail::whereIn('id', $delst)->delete(); 

        } catch (\Exception $ex) {
          return $rollback("cleanup TagihanDetail\n" . $ex->getMessage());
        }
      }

      unset($res["Msg"]);
      $res["Result"] = true;
      $res["List"] = []; foreach ($fins["Tagihan"] as $k) $res["List"][] = $k->isarowid;
      $res["Count"] = count($res["List"]);
      return response()->json($res);
    }
    return response()->json($res);
  }
}
