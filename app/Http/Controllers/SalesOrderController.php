<?php

namespace App\Http\Controllers;

use App\Models\Interfaces\APIMasterController;
use Illuminate\Http\Request;
use App\Models\AppSetting;
use App\Models\Expedisi;
use App\Models\HistoryBMK0;
use App\Models\HistoryBMK1;
use App\Models\HistoryBMK2;
use App\Models\HPPA;
use App\Models\Karyawan;
use App\Models\NotaPenjualan;
use App\Models\OrderPembelian;
use App\Models\OrderPembelianDetail;
use App\Models\OrderPenjualan;
use App\Models\OrderPenjualanDetail;
use App\Models\OrderPenjualanSynch;
use App\Models\StagingOrderPenjualan;
use App\Models\StagingOrderPenjualanDetail;
use App\Models\StatusToko;
use App\Models\SubCabang;
use App\Models\Supplier;
use App\Models\Cabang;
use App\Models\Toko;
use App\Models\Tokohakakses;
use Carbon\Carbon;
use DB;
use PDF;
use EXCEL;

// need info about interfaces APIMasterController ?
// contact 'gearintellix' how to use it
class SalesOrderController extends APIMasterController //Controller
{

  // __construct ver APIMasterController
  // don't overrides __construct
  protected function __startup() {
    $this->apis = [
      'synch' => [
        'mode' => ['get', 'post'],
        'params' => [],
        'output' => [
          'defaultJSON' => [
            "Result" => false,
            "Mystic" => [],
            "Data" => [],
            "Count" => 0
          ],
          'errorJSON' => [
            "Result" => false,
            "Msg" => "!%message%",
            //"Trace" => "!%trace%"
          ]
        ],
        'fn' => function (&$out, $req, $args, $config) {
          if (array_key_exists("subd", $args['dslug'])) {
            $cabang   = trim(@$args['dslug']['subd']);

            $allow = [];
            if (isset($args['params']['mark'])) $allow = ["ids"];
            else $allow = ["from", "to"];

            $allow = $this->checkFields($args['params'], $allow);
            if (count($allow) > 0) throw new \Exception("Data not valid: " . join($this->listFields($allow), ", "));

            $cbng = SubCabang::where("kodesubcabang", $cabang)->first();
            if (count($cbng) <= 0) throw new \Exception("Sub cabang not found");

            if (!isset($args['params']['ids'])) {
              $fromdate = new \DateTime(trim($args['params']['from']));
              $todate   = new \DateTime(trim($args['params']['to']));

              $opj = OrderPenjualan::where([
                ["tglso", ">=", $fromdate->format("Y-m-d")],
                ["tglso", "<=", $todate->format("Y-m-d")],
                ["recordownerid", "=", $cbng->id],
                ["noso", "=", "SALESMAN"]
              ])
              ->whereRaw("salesclosedso is not null")
              ->whereRaw("isasynch is null")
              ->get();

              if (count($opj) > 0) {
                foreach ($opj as $o) {
                  $cur = $o->getAttributes();

                  $tmpx = $o->toko;
                  $cur['namatoko'] = (count($tmpx) > 0 ? $tmpx->namatoko : '');
                  $cur['kodetoko'] = (count($tmpx) > 0 ? $tmpx->kodetoko : '');
                  $cur['tokoid'] = (count($tmpx) > 0 ? $tmpx->tokoidwarisan : '');
                  $cur['alamat'] = (count($tmpx) > 0 ? $tmpx->alamat : '');
                  $cur['kota'] = (count($tmpx) > 0 ? $tmpx->kota : '');

                  $tmpx = $o->salesman;
                  $cur['kodesales'] = (count($tmpx) > 0 ? $tmpx->kodesales : '');
                  $cur['namasales'] = (count($tmpx) > 0 ? $tmpx->namakaryawan : '');

                  $tmpx = SubCabang::where("id", $o->omsetsubcabangid)->first();
                  $cur['omsetcabang'] = (count($tmpx) > 0 ? $tmpx->kodesubcabang : '');

                  $tmpx = SubCabang::where("id", $o->pengirimsubcabangid)->first();
                  $cur['kirimcabang'] = (count($tmpx) > 0 ? $tmpx->kodesubcabang : '');

                  $tmpx = Karyawan::where("id", $o->karyawanidpkp)->first();
                  $cur['namakaryawan'] = (count($tmpx) > 0 ? $tmpx->namakaryawan : '');

                  $tmpx = Expedisi::where("id", $o->expedisiid)->first();
                  $cur['namaexpedisi'] = (count($tmpx) > 0 ? $tmpx->namaexpedisi : '');
                  $cur['kodeexpedisi'] = (count($tmpx) > 0 ? $tmpx->kodeexpedisi : '');

                  $cur['details'] = [];
                  $cur['total'] = 0;
                  $cur['count'] = 0;

                  foreach ($o->details as $d) {
                    $cur['details'][$d->id] = $d->getAttributes();

                    $tmpx = $d->barang;
                    $cur['details'][$d->id]['kodebarang'] = (count($tmpx) > 0 ? $tmpx->kodebarang : '');

                    $cur['total'] += (floatval($d->hrgsatuannetto) * intval($d->qtyso));
                    $cur['count'] += intval($d->qtyso);
                  }
                  $out["Data"][$o->id] = $cur;
                  $out['Count'] += 1;
                }
              }
              $out['Result'] = true;

            } else {
              $ids = $args['params']['ids'];
              if (gettype($ids) != "array") {
                if (gettype($ids) == "string") $ids = json_decode($ids, true);
                else throw new \Exception("Request not valid");
              }

              foreach ($ids as $id) {
                $dat = OrderPenjualan::where([
                  ["id", "=", $id],
                  ["noso", "=", "SALESMAN"],
                  ["recordownerid", "=", $cbng->id]
                ])
                ->whereRaw("isasynch is null")
                ->whereRaw("salesclosedso is not null")
                ->first();

                if (count($dat) > 0) {
                  $dat->isasynch = date("Y-m-d");
                  try {
                    $dat->save();
                    $out['Count'] += 1;
                    $out['Data'][$id] = true;

                  } catch (\Exception $ex) {
                    $out['Data'][$id] = false;
                    $out['Mystic'][$id] = $ex->getMessage();
                  }

                } else $out['Data'][$id] = false;
              }
              $out['Result'] = true;
            }

          } else throw new \Exception("Cabang not yet set");
        }
      ]
    ];
  }

    protected $original_column = array(
      1 => "pj.orderpenjualan.tglso",
      2 => "pj.orderpenjualan.noso",
      3 => "pj.orderpenjualan.tglpickinglist",
      4 => "pj.orderpenjualan.nopickinglist",
      5 => "pj.orderpenjualan.noaccpiutang",
      6 => "mstr.toko.namatoko",
      7 => "hr.karyawan.namakaryawan",
      8 => "pj.orderpenjualan.rpaccpiutang",
      9 => "pj.orderpenjualan.statusajuanhrg11",
    );

    public function index(Request $req)
    {
      $ppn = AppSetting::where('recordownerid',$req->session()->get('subcabang'))->where('keyid','ppn')->first();
      $ppn = ($ppn) ? $ppn->value : 0;
      return view('transaksi.salesorder.index', compact('ppn'));
    }

    public function getData(Request $req)
    {
      // gunakan permission dari indexnya aja
      if(!$req->user()->can('salesorder.index')) {
        return response()->json([
          'draw'            => $req->draw,
          'recordsTotal'    => 0,
          'recordsFiltered' => 0,
          'data'            => [],
        ]);
      }

      // jika lolos, tampilkan data
      $cekajuan = AppSetting::where('keyid','ACCHargaMandiri')->where('recordownerid',$req->session()->get('subcabang'))->first();
      $filter_count = 0;
    	$empty_filter = 0;
    	$columns = array(
        0 => "pj.orderpenjualan.tglpickinglist",
        1 => "pj.orderpenjualan.nopickinglist",
        2 => "pj.orderpenjualan.tglso",
        3 => "pj.orderpenjualan.noso",
		    4 => "pj.orderpenjualan.noaccpiutang",
		    5 => "mstr.toko.namatoko as tokonama",
		    6 => "hr.karyawan.namakaryawan as salesmannama",
		    7 => "pj.orderpenjualan.statusajuanhrg11",
        8 => "pj.orderpenjualan.rpaccpiutang",
        9 => "pj.orderpenjualan.id",
        10 => "pj.orderpenjualan.tipetransaksi",
        11 => "pj.orderpenjualan.statustoko",
        12 => "pj.orderpenjualan.tokoid",
        13 => "pj.orderpenjualan.salesclosedso",
        14 => "pj.orderpenjualan.isasynch"
      );
      for ($i=1; $i < 10; $i++) {
        if(empty($req->custom_search[$i]['text'])){
          $empty_filter++;
        }
      }
      $penjualan = OrderPenjualan::selectRaw(collect($columns)->implode(', '));
      $penjualan->leftJoin('mstr.toko', 'pj.orderpenjualan.tokoid', '=', 'mstr.toko.id');
      $penjualan->leftJoin('hr.karyawan', 'pj.orderpenjualan.karyawanidsalesman', '=', 'hr.karyawan.id');
      $penjualan->where("pj.orderpenjualan.recordownerid",$req->session()->get('subcabang'));

      // filter tanggal
      if (isset($req->tglmulai) && isset($req->tglselesai)) {
        $_frm = new \DateTime($req->tglmulai);
        $_to = new \DateTime($req->tglselesai);
        $penjualan->where([
          [DB::raw("pj.orderpenjualan.tglpickinglist::date"), ">=", $_frm->format("Y-m-d")],
          [DB::raw("pj.orderpenjualan.tglpickinglist::date"), "<=", $_to->format("Y-m-d")]
        ]);
      }      
      
      // filter toko dan barang
      if ($req->xsearch) {
        $penjualan->where("mstr.toko.namatoko",'ilike','%'.$req->xsearch.'%');
      }

      // filter salesman
      if ($req->user()->hasRole("SALESMAN")) {
        $sals = Karyawan::where('kodesales', $req->user()->username)->first();
        if (count($sals) > 0) {
          $penjualan->where("pj.orderpenjualan.karyawanidsalesman", $sals->id);
        } else $penjualan->take(0);
      }

      $total_data = $penjualan->count();
      if($empty_filter < 9){
        for ($i=1; $i < 10; $i++) {
          if ($req->custom_search[$i]['text']!='') {
            $index = $i;
            if($index == 2 || $index == 3 || $index == 4 || $index == 6 || $index == 7 || $index == 9 ){
              if($req->custom_search[$i]['filter'] == '='){
                $penjualan->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
              }else{
                $penjualan->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
              }
            }else{
              $penjualan->where($this->original_column[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
            }
            $filter_count++;
          }
        }
      }

      if($filter_count > 0){
        $filtered_data = $penjualan->count();
      }else{
        $filtered_data = $total_data;
      }

      $penjualan->skip($req->start)->take($req->length);

      if ($req->tipe_edit) {
        $penjualan->orderBy('pj.orderpenjualan.lastupdatedon','desc');
      }else {
        if(array_key_exists($req->order[0]['column'], $this->original_column)){
          $penjualan->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
        }
      }

      $data = array();
      foreach ($penjualan->get() as $key => $jual) {
        $jual->lastupdatedon = $jual->lastupdatedon;
        $data[$key]          = $jual->toArray();
        $data[$key]['rpaccpiutang'] = number_format($jual->rpaccpiutang,0,',','.');
        if ($jual->checknota != null) {
          $data[$key]['edit'] = 'Tidak bisa edit data. Picking List sudah diproses menjadi Nota jual. Hubungi Manager Anda.';
          $data[$key]['add'] = 'Tidak bisa tambah data. Picking List sudah diproses menjadi Nota jual. Hubungi Manager Anda.';
        }else {
          if(Carbon::parse($jual->tglpickinglist)->toDateString() != Carbon::now()->toDateString()){
            $data[$key]['edit'] = 'Tidak bisa edit data. Tanggal server tidak sama dengan Tanggal Picking List. Hubungi Manager Anda.';
            $data[$key]['add'] = 'Tidak bisa tambah data. Tanggal server tidak sama dengan Tanggal Picking List. Hubungi Manager Anda.';
          }else {
            if ($data[$key]['statusajuanhrg11'] == null) {
              if ($jual->isasynch != null) {
                $data[$key]['edit'] = 'Tidak bisa edit data. Order sudah di tarik';
                $data[$key]['add'] = 'Tidak bisa tambah data. Order sudah di tarik';

              } else if ($jual->salesclosedso != null) {
                $data[$key]['edit'] = 'Tidak bisa edit data. Order sudah closed';
                $data[$key]['add'] = 'Tidak bisa tambah data. Order sudah closed';

              } else {
                $data[$key]['edit'] = 'edit';
                $data[$key]['add'] = 'add';
              }

            }else {
              $data[$key]['edit'] = 'Tidak bisa edit data. Picking List sedang dalam proses pengajuan harga ke 11. Hubungi Manager Anda.';
              $data[$key]['add'] = 'Tidak bisa tambah data. Picking List sedang dalam proses pengajuan harga ke 11. Hubungi Manager Anda.';
            }
          }
        }

        if ($jual->salesclosedso != null) {
          $data[$key]['closingso'] = 'Order sudah di closed, tidak dapat di closing lagi';
        } else $data[$key]['closingso'] = 'closing';

        $data[$key]['color'] = "default";
        if ($jual->salesclosedso != null) $data[$key]['color'] = "closed";
        if ($jual->salesclosedso != null && $jual->isasynch != null) $data[$key]['color'] = "pulled";

        if($cekajuan->value == 1 || $cekajuan->value == 'true') {
            $data[$key]['ajuan'] = 'Cabang anda memiliki kewenangan ACC Harga Mandiri. Tidak perlu minta ACC ke 11.';
        }else{
          if ($jual->statusajuanhrg11 != null) {
            $data[$key]['ajuan'] = 'Tidak bisa Ajuan Harga lagi. SO Sudah diajukan ke 11.';
          } else {
            $counthrga = 0;
            $so = '';
            foreach ($jual->details as $opjd) {
              if (($opjd->hrgsatuannetto > $opjd->hrgbmk)) {
              $counthrga += 1;
                if ($so == '') {
                  $so = $jual->noso;
                }
              }
            }
            if ($counthrga > 0) {
              // $data[$key]['ajuan11'] = 'SO no. '.$so.' berhasil diajukan ke 11. Tunggu jawaban dari 11.';
               $data[$key]['ajuan'] = 'ajuan';
            }else {
              $data[$key]['ajuan'] = 'Tidak perlu proses ajuan harga ke 11. Tidak ada detail SO yang harganya kurang dari harga BMK.';
            }
          }
        }

        if ($jual->checkdetails != null) {
          $data[$key]['delete'] = 'Tidak bisa hapus data. Sudah ada data di Order Penjualan Detail. Hapus detail terlebih dahulu.';
        }else {
          $data[$key]['delete'] = 'auth';
        }

        if ($jual->checknota != null) {
          $data[$key]['batalpil'] = 'Tidak bisa batal record. Picking List sudah diproses menjadi Nota jual. Hubungi Manager Anda.';
        }else {
          if ($data[$key]['statusajuanhrg11'] != null) {
            $data[$key]['batalpil'] = 'Tidak bisa batal record. Picking List sedang dalam proses pengajuan harga ke 11. Hubungi Manager Anda.';
          }else {
            if ($jual->checkdetails != null) {
              $data[$key]['batalpil'] = 'batalpil';
            }else {
              $data[$key]['batalpil'] = 'Tidak bisa batal record. Tidak ada record di Picking List detail yang bisa dibatalkan. Hubungi Manager Anda.';
            }
          }
        }

        if ($jual->checkdetails == null) {
          $data[$key]['cetakpil'] = 'Tidak bisa cetak dokumen. Tidak ada record detail yang harus dicetak';
        }else {
          $countqty = 0;
          $counthrg = 0;
          $sku = '';
          foreach ($jual->details as $opjd) {
            $opjd->lastupdatedon = $opjd->lastupdatedon;
            if ($opjd->qtysoacc == 0) {
              $countqty += 1;
            }
            // if (($opjd->hrgsatuannetto > $opjd->hrgbmk) && ($opjd->noacc11==null)) {
            if (($opjd->hrgsatuannetto < $opjd->hrgbmk) && ($opjd->noacc11==null)) {
              $counthrg += 1;
              if ($sku == '') {
                $sku = $opjd->barang->kodebarang;
              }
            }
          }

          if ($countqty == count($jual->details)) {
            $data[$key]['cetakpil'] = 'Tidak bisa cetak dokumen. Semua record detail tidak ada kuantiti ACCnya.';
          }else {
            if ($counthrg > 0) {
              $data[$key]['cetakpil'] = 'Tidak bisa cetak Picking List. SKU '.$sku.' butuh ACC Harga 11 dahulu.';
            }else {
              $data[$key]['cetakpil'] = 'cetakpil';
              $data[$key]['cetakpil'] = 'cetakpil';
            }
          }
        }

        // if (count($jual->checknota) && !($jual->checknota->tglproforma)) {
        //   $data[$key]['updatenoso'] = 'updatenoso';
        // }else {
        //   $data[$key]['updatenoso'] = 'Tidak bisa Update No. SO, Tgl Proforma telah terisi!';
        // }

        $data[$key]['updatenoso'] = 'updatenoso';
        if ($jual->nota) {
          foreach ($jual->nota as $nota_val) {
            if($nota_val->tglproforma != null) {
              $data[$key]['updatenoso'] = 'Tidak bisa Update No. SO, Tgl Proforma telah terisi!';
              break;
            }
          }
        }
      }

      return response()->json([
        'draw' 				       => $req->draw,
        'recordsTotal' 		   => $total_data,
    		'recordsFiltered' 	 => $filtered_data,
    		'data' 				       => $data,
      ]);
    }

    public function getDataDetail(Request $req)
    {
      // gunakan permission dari indexnya aja
      if(!$req->user()->can('salesorder.index')) {
        return response()->json(['node' => []]);
      }

      // jika lolos, tampilkan data
      $penjualan = OrderPenjualan::find($req->id);
      if ($penjualan->checknota != null) {
        $delete = 'Tidak bisa hapus data. Picking List sudah diproses menjadi Nota jual. Hubungi Manager Anda.';
      }else {
        if(Carbon::parse($penjualan->tglpickinglist)->toDateString() != Carbon::now()->toDateString()){
          $delete = 'Tidak bisa hapus data. Tanggal server tidak sama dengan Tanggal Picking List. Hubungi Manager Anda.';
        }else {
          if ($penjualan->statusajuanhrg11 != null) {
            $delete = 'Tidak bisa hapus data. Picking List sedang dalam proses pengajuan harga ke 11. Hubungi Manager Anda.';
          }else {
            $delete = 'auth';
          }
        }
      }

      $penjualan_details = $penjualan->details;
      $node = array();
      foreach ($penjualan_details as $detail) {
        if ($penjualan->checknota != null) {
          $batalpil = 'Tidak bisa batal record. Picking List sudah diproses menjadi Nota jual. Hubungi Manager Anda.';
        }else {
          if ($penjualan->statusajuanhrg11 != null) {
            $batalpil = 'Tidak bisa batal record. Picking List sedang dalam proses pengajuan harga ke 11. Hubungi Manager Anda.';
          }else {
            if ($detail->qtysoacc == 0) {
              $batalpil = 'Tidak bisa batal record. QTY. SO ACC sudah 0. Tidak ada record yang perlu dibatalkan.';
            }else {
              $batalpil = 'batalpil';
            }
          }
        }

        $ditolak = false;
        if($penjualan->statusajuanhrg11 == "SUDAH ACC" && strpos(strtolower($detail->keterangan), 'batal') === false && $detail->qtysoacc == 0) {
          $ditolak = true;
        }

        $barang = $detail->barang;
        $hrgdisc1 = (1-($detail->disc1/100)) * $detail->hrgsatuanbrutto;
        $hrgdisc2 = (1-($detail->disc2/100)) * $hrgdisc1;
        $hrgnetto = (1+($detail->ppn/100)) * $hrgdisc2;

        $temp = [
          0 => $barang->namabarang,
          1 => $barang->satuan,
          2 => $detail->qtysoacc,
          3 => number_format($hrgnetto,2,',','.'),
          4 => number_format($detail->qtysoacc * $hrgnetto,0,',','.'),
          5 => $detail->id,
          6 => $delete,
          7 => $batalpil,
          8 => $ditolak,
        ];
        array_push($node, $temp);
      }
      return response()->json([
        'node' => $node,
      ]);
    }

    public function getHeaderDetail(Request $req)
    {
      $data = OrderPenjualan::find($req->id);

      return response()->json([
        "c1"                    => $data->c1==null?null:$data->c1->kodesubcabang,
        "c2"                    => $data->c2==null?null:$data->c2->kodesubcabang,
        "c2id"                  => $data->pengirimsubcabangid,
        "noso"                  => $data->noso,
        "tglso"                 => $data->tglso,
        "nopickinglist"         => $data->nopickinglist,
        "tglpickinglist"        => $data->tglpickinglist,
        "tokoid"                => $data->tokoid,
        "tokonama"              => $data->toko->namatoko,
        "tokoalamat"            => $data->toko->alamat,
        "tokokota"              => $data->toko->kota,
        "kecamatan"             => $data->toko->kecamatan,
        "wilid"                 => $data->toko->customwilayah,
        "idtoko"                => $data->toko->id,
        "statustoko"            => $data->statustoko,
        "tokoidwarisan"         => $data->toko->tokoidwarisan,
        "salesmannama"          => $data->salesman==null? null:$data->salesman->namakaryawan,
        "expedisinama"          => $data->expedisi==null?null:$data->expedisi->namaexpedisi,
        "expedisiid"            => $data->expedisiid,
        "tipetransaksi"         => $data->tipetransaksi,
        "temponota"             => $data->temponota,
        "tempokirim"            => $data->tempokirim,
        "temposalesman"         => $data->temposalesman,
        "noaccpiutang"          => $data->noaccpiutang,
        "namapkp"               => $data->karyawanpkp==null?null:$data->karyawanpkp->namakaryawan,
        "tglaccpiutang"         => $data->tglaccpiutang,
        "rpaccpiutang"          => number_format($data->rpaccpiutang,0,'','.'),
        "rpsaldopiutang"        => number_format($data->rpsaldopiutang,0,'','.'),
        "rpsaldooverdue"        => number_format($data->rpsaldooverdue,0,'','.'),
        "rpsoaccproses"         => number_format($data->rpsoaccproses,0,'','.'),
        "rpgit"                 => number_format($data->rpgit,0,'','.'),
        "catatanpenjualan"      => $data->catatanpenjualan,
        "catatanpembayaran"     => $data->catatanpembayaran,
        "catatanpengiriman"     => $data->catatanpengiriman,
        "print"                 => $data->print,
        "tglprintpickinglist"   => $data->tglprintpickinglist,
        "statusapprovaloverdue" => '?',
        "tglterimapilpiutang"   => $data->tglterimapilpiutang,
        "statusajuanhrg11"      => $data->statusajuanhrg11,
        "lastupdatedby"         => $data->lastupdatedby,
        "lastupdatedon"         => $data->lastupdatedon,
      ]);
    }

    public function getDetail(Request $req)
    {
      $detail = OrderPenjualanDetail::find($req->id);
      $hrgdisc1 = (1-($detail->disc1/100)) * $detail->hrgsatuanbrutto;
      $hrgdisc2 = (1-($detail->disc2/100)) * $hrgdisc1;
      $hrgnetto = (1+($detail->ppn/100)) * $hrgdisc2;

      return response()->json([
        'orderdetailid'   => $detail->id,
        'namabarang'      => $detail->barang->namabarang,
        'satuan'          => $detail->barang->satuan,
        'qtyso'           => $detail->qtyso,
        'qtysoacc'        => $detail->qtysoacc,
        'hrgsatuanbrutto' => number_format($detail->hrgsatuanbrutto,2,',','.'),
        'disc1'           => $detail->disc1,
        'hrgdisc1'        => number_format($hrgdisc1,2,',','.'),
        'disc2'           => $detail->disc2,
        'hrgdisc2'        => number_format($hrgdisc2,2,',','.'),
        'ppn'             => $detail->ppn,
        'hrgsatuannetto'  => number_format($hrgnetto,2,',','.'),
        'hrgtotalnetto'   => number_format($detail->qtysoacc * $hrgnetto,0,',','.'),
        'hrgbmk'          => number_format($detail->hrgbmk,2,',','.'),
        'noacc11'         => $detail->noacc11,
        'catatan'         => $detail->catatan,
        'qtystockgudang'  => $detail->qtystockgudang,
        'komisikhusus11'  => $detail->komisikhusus11,
        'linkpembelian'   => $detail->orderpembeliandetailid==null? 'Tidak':'Ya',
        'lastupdatedby'   => $detail->lastupdatedby,
        'lastupdatedon'   => $detail->lastupdatedon,
      ]);
    }

    public function getNextPickingListNo($recordownerid){
      $next_no = '';
      $max_pj = OrderPenjualan::where('id', OrderPenjualan::where('recordownerid', $recordownerid)->max('id'))->first();
      if ($max_pj==null) {
        $next_no = '0000001';
      }elseif (strlen($max_pj->nopickinglist)<7) {
        $next_no = '0000001';
      }elseif ($max_pj->nopickinglist == '9999999') {
        $next_no = '0000001';
      }else {
        $next_no = substr('0000000', 0, 7-strlen($max_pj->nopickinglist+1)).($max_pj->nopickinglist+1);
      }
      return $next_no;
    }

    public function tambah(Request $request)
    {
      if ($request->session()->get('subcabang')) {
        $recownerid    = $request->session()->get('subcabang');
        $subcabanguser = SubCabang::find($recownerid);
        $expedisi      = Expedisi::where('kodeexpedisi','SAS')->where('recordownerid',$recownerid)->first();
        $ppn = AppSetting::where('recordownerid',$request->session()->get('subcabang'))->where('keyid','ppn')->first();
        $ppn = ($ppn) ? $ppn->value : 0;
        return view('transaksi.salesorder.form', compact('subcabanguser','expedisi','ppn'));
      }else {
        return redirect()->route('orderpenjualan.index');
      }
    }

    public function closingSO(Request $req) {
      if (isset($req->id)) {
        $dat = OrderPenjualan::where([
          ['id', '=', $req->id],
          ['noso', '=', 'SALESMAN']
        ])
        ->whereRaw("salesclosedso is null")
        ->whereRaw("isasynch is null")
        ->first();

        if (count($dat) > 0) {
          if (Auth()->User()->karyawanid != $dat->karyawanidsalesman) {
            return response()->json([
              'result' => false,
              'msg' => "Anda tidak dapat closing so untuk order ini"
            ]);
          }

          try {
            $dat->salesclosedso = date("Y-m-d H:i:s");
            $dat->save();

            return response()->json(['result' => true]);

          } catch (\Exception $ex) {
            return response()->json(['result' => false, 'msg' => $ex->getMessage()]);
          }

        } else return response()->json(['result' => false, 'msg' => "Tidak ada data atau bukan data sales order"]);
      } else return response()->json(['result' => false, 'msg' => "No ID"]);
    }

    public function simpan(Request $request){
      $subcabang = $request->session()->get('subcabang');

      $order = new OrderPenjualan;
      $order->recordownerid       = $subcabang;
      $order->omsetsubcabangid    = $subcabang;
      $order->pengirimsubcabangid = $request->c2;
      $order->noso                = "SALESMAN";
      $order->tglso               = date('Y-m-d');
      $order->nopickinglist       = $this->getNextPickingListNo($subcabang);
      $order->tglpickinglist      = date('Y-m-d');
      $order->tokoid              = $request->tokoid;
      $order->expedisiid          = $request->expedisiid;
      $order->statustoko          = $request->statustoko;
      $order->temponota           = $request->temponota;
      $order->tempokirim          = $request->tempokirim;
      $order->temposalesman       = $request->temposalesman;
      $order->tipetransaksi       = $request->tipetransaksi;
      $order->karyawanidsalesman  = $request->user()->karyawanid;
      $order->catatanpenjualan    = strtoupper($request->catatanpenjualan);
      $order->catatanpembayaran   = strtoupper($request->catatanpembayaran);
      $order->catatanpengiriman   = strtoupper($request->catatanpengiriman);
      $order->createdby           = strtoupper($request->user()->username);
      $order->lastupdatedby       = strtoupper($request->user()->username);
      $order->save();

      return response()->json(['success'=>true, 'orderid'=>$order->id, 'nopickinglist'=>$order->nopickinglist, 'tglpickinglist'=>date('d-m-Y',strtotime($order->tglpickinglist))]);
    }
    
    public function simpanDetail(Request $request)
    {
      $detail = new OrderPenjualanDetail;
      $detail->orderpenjualanid = $request->orderpenjualanid;
      $detail->stockid          = $request->stockid;
      $detail->qtyso            = $request->qtyso;
      $detail->qtysoacc         = $request->qtysoacc;
      $detail->hrgbmk           = $request->hrgbmk;
      $detail->hrgsatuanbrutto  = $request->hrgsatuanbrutto;
      $detail->disc1            = $request->disc1;
      $detail->disc2            = $request->disc2;
      $detail->ppn              = $request->ppn;
      $detail->hrgsatuannetto   = $request->hrgsatuannetto;
      $detail->catatan          = strtoupper($request->catatandetail);
      $detail->qtystockgudang   = $request->qtystockgudang;
      $detail->createdby        = strtoupper($request->user()->username);
      $detail->lastupdatedby    = strtoupper($request->user()->username);

      //==== ditambahkan halim ======//

      if ($request->catatandetail == 'ACC OTOMATIS' && $request->qtyso == 0){
        $detail->noacc11 = "ACCOTMS";
      }

      //==== END ====================//

      $detail->save();
      
      // Cek OPJD belum jadi nota
      $tokoid = OrderPenjualan::select('tokoid')->find($request->orderpenjualanid)->tokoid;
      $cek_opjd = OrderPenjualan::select('pj.orderpenjualan.nopickinglist','pj.orderpenjualan.tglpickinglist','pj.orderpenjualandetail.id','pj.notapenjualandetail.qtynota')
            ->join('pj.orderpenjualandetail','pj.orderpenjualandetail.orderpenjualanid','=','pj.orderpenjualan.id')
            ->leftJoin('pj.notapenjualandetail','pj.notapenjualandetail.orderpenjualandetailid','=','pj.orderpenjualandetail.id')
            ->where('pj.orderpenjualan.tokoid',$tokoid)
            ->where('pj.orderpenjualandetail.stockid',$request->stockid)
            ->whereRaw('pj.orderpenjualan.tglpickinglist > (current_date - interval \'30 day\')')
            ->whereRaw('pj.orderpenjualan.tglpickinglist < current_date')
            ->first();

      if($cek_opjd) {
        $update_qty = OrderPenjualanDetail::find($cek_opjd->id);
        $update_qty->qtysoacc = ($cek_opjd->qtynota) ? $cek_opjd->qtynota : 0;
        $update_qty->save();

        // Format Tanggal
        $tglpickinglist = Carbon::parse($cek_opjd->tglpickinglist)->format('d-m-Y');
        
        return response()->json(['success'=>true,'cek_opjd'=>"PiL no ".$cek_opjd->nopickinglist." Tanggal ".$tglpickinglist.", qtybo dipasifkan."]);
      }else{
        return response()->json(['success'=>true,'cek_opjd'=>null]);
      }
    }

    public function ubah(Request $req)
    {
      $order = OrderPenjualan::find($req->data['orderid']);
      $order->pengirimsubcabangid = $req->data['c2'];
      $order->noso                = strtoupper($req->data['noso']);
      $order->tglso               = $req->data['tglso'];
      $order->expedisiid          = $req->data['expedisiid'];
      $order->tempokirim          = $req->data['tempokirim'];
      $order->catatanpenjualan    = strtoupper($req->data['catatanpenjualan']);
      $order->catatanpembayaran   = strtoupper($req->data['catatanpembayaran']);
      $order->catatanpengiriman   = strtoupper($req->data['catatanpengiriman']);
      $order->lastupdatedby       = strtoupper($req->user()->username);
      $order->save();

      return response()->json(['success'=> true]);
    }

    public function hapusData(Request $req)
    {
      if($req->permission == 'salesorder.hapus')
      {
        if ($req->tipe == 'header') {
          $order = OrderPenjualan::find($req->orderid);
          $order->delete();
          return response()->json(['success' => true]);
        }else {
          $detail = OrderPenjualanDetail::find($req->orderid);
          $detail->delete();
          return response()->json(['success' => true]);
        }
      }
      return response()->json(['success' => false]);
    }

    public function kewenangan(Request $req)
    {
      $lastUserId = auth()->user()->id;
      if(auth()->attempt(['username'=>$req->username,'password'=>$req->password]))
      {
        if(auth()->user()->can($req->permission) && $req->permission == 'salesorder.hapus')
        {
          auth()->loginUsingId($lastUserId);
          if ($req->tipe == 'header') {
            $order = OrderPenjualan::find($req->orderid);
            $order->delete();
          }else {
            $detail = OrderPenjualanDetail::find($req->orderid);
            $detail->delete();
          }
          return response()->json(['success' => true]);
        }
      }
      return response()->json(['success' => false]);
    }

    public function batalPiL(Request $req)
    {
      $order = OrderPenjualan::find($req->id);
      $opjd  = OrderPenjualanDetail::where('orderpenjualanid', $order->id)
               ->update(array("qtysoacc" => 0,'catatan'=>'BATAL PICKING LIST'));
      $order->catatanpenjualan = 'BATAL PICKING LIST';
      $order->save();
      return response()->json(['success'=> true]);
    }

    public function batalPiLDetail(Request $req)
    {
      $detail = OrderPenjualanDetail::find($req->id);
      $detail ->qtysoacc = 0;
      $detail ->catatan  = 'BATAL PICKING LIST';
      $detail ->save();
      return response()->json(['success'=> true]);
    }

    public function cetakPicking(Request $req,$id)
    {
        // Posisi kertas di printer : F5 Landscape / A4 Potrait
        $config = [
            'mode'                  => '',
            'format'                => [215.9,139.7,],
            // 'format'                => 'F5',
            'default_font_size'     => 8.5,
            'default_font'          => 'sans-serif',
            'margin_left'           => 10,
            'margin_right'          => 10,
            'margin_top'            => 38,
            'margin_bottom'         => 20,
            'margin_header'         => 0,
            'margin_footer'         => 4,
            'orientation'           => 'P',
            'title'                 => 'CETAK PICKING LIST',
            'author'                => '',
            'display_mode'          => 'fullpage',
        ];

        $cetak   = OrderPenjualan::find($req->id);
        if(!$cetak->tglprintpickinglist){
          $cetak->tglprintpickinglist = Carbon::now();
        }
        $cetak->print = ($cetak->print)+1;
        $cetak->save();

        // Detail
        $details = OrderPenjualanDetail::where('orderpenjualanid',$req->id)->get()->load(['barang' => function($query) {
          $query->orderBy('area1', 'asc');
        }]);

        $pdf   = PDF::loadView('transaksi.salesorder.picking_2',['user'=>$req->user(),'cetak'=>$cetak,'details'=>$details],[],$config);
        return $pdf->stream('CetakPickingList-'.$req->id.'.pdf');
    }

    public function cetakPicking_test(Request $req,$id)
    {
        // Posisi kertas di printer : F5 Landscape / A4 Potrait
        // $config = [
        //     'mode'                  => '',
        //     'format'                => [139.7,215.9],
        //     'default_font_size'     => 8.5,
        //     'default_font'          => 'sans-serif',
        //     'margin_left'           => 10,
        //     'margin_right'          => 10,
        //     'margin_top'            => 38,
        //     'margin_bottom'         => 20,
        //     'margin_header'         => 0,
        //     'margin_footer'         => 4,
        //     'orientation'           => 'L',
        //     'title'                 => 'CETAK PICKING LIST',
        //     'author'                => '',
        //     'display_mode'          => 'fullpage',
        // ];

        // Posisi kertas di printer : F5 Portrait
        // $config = [
        //     'mode'                  => '',
        //     'format'                => [215.9,139.7],
        //     'default_font_size'     => 8.5,
        //     'default_font'          => 'sans-serif',
        //     'margin_left'           => 10,
        //     'margin_right'          => 10,
        //     'margin_top'            => 38,
        //     'margin_bottom'         => 20,
        //     'margin_header'         => 0,
        //     'margin_footer'         => 4,
        //     'orientation'           => 'P',
        //     'title'                 => 'CETAK PICKING LIST',
        //     'author'                => '',
        //     'display_mode'          => 'fullpage',
        // ];

        $config = [
            'mode'                  => '',
            // 'format'                => [215.9,139.7],
            // 'format'                => [165,210],
            // 'format'                => [330, 215], //ukuran F4
            // 'format'                => [165, 215], //ukuran F5
            // 'format'                => [297,210], //ukuran A4
            // 'format'                => [148.5,210], //ukuran A5
            'default_font_size'     => 8.5,
            'default_font'          => 'sans-serif',
            // 'margin_left'           => 10,
            // 'margin_right'          => 10,
            // 'margin_top'            => 38,
            // 'margin_bottom'         => 20,
            // 'margin_header'         => 0,
            // 'margin_footer'         => 4,
            // 'orientation'           => 'L',
            'title'                 => 'CETAK PICKING LIST',
            'author'                => '',
            'display_mode'          => 'fullwidth',
        ];

        $config = [
            'mode'                  => '',
            'default_font_size'     => 8.5,
            'default_font'          => 'sans-serif',
            'margin_left'           => 0,
            'margin_right'          => 0,
            'margin_top'            => 0,
            'margin_bottom'         => 0,
            'margin_header'         => 0,
            'margin_footer'         => 0,
            'title'                 => 'CETAK PICKING LIST',
            'author'                => '',
            'display_mode'          => 'default',
        ];

        $cetak   = OrderPenjualan::find($req->id);
        if(!$cetak->tglprintpickinglist){
          $cetak->tglprintpickinglist = Carbon::now();
        }
        $cetak->print = ($cetak->print)+1;
        $cetak->save();

        // Detail
        $details = OrderPenjualanDetail::where('orderpenjualanid',$req->id)->get()->load(['barang' => function($query) {
          $query->orderBy('area1', 'asc');
        }]);

        $pdf   = PDF::loadView('transaksi.salesorder.picking',['user'=>$req->user(),'cetak'=>$cetak,'details'=>$details],[],$config);
        return $pdf->stream('CetakPickingList-'.$req->id.'.pdf');
    }

    public function riwayatJual(Request $req,$toko)
    {
      $blnawal    = date("Y-m-d", strtotime("-6 months"));
      $blnserver  = date("Y-m-d");
      $totalharga = 0;

      $rjual = Toko::find($req->tokoid);
      $columns = array(
        0 => "pj.notapenjualan.tokoid as toko",
        1 => "pj.notapenjualan.tglnota as tglnota",
        2 => "pj.notapenjualan.id",
        3 => "pj.notapenjualandetail.notapenjualanid",
        4 => "pj.notapenjualandetail.qtynota",
        5 => "pj.notapenjualandetail.hrgsatuannetto",
        6 => "pj.notapenjualandetail.disc1",
        7 => "pj.notapenjualandetail.disc2",
        8 => "pj.notapenjualandetail.hrgsatuanbrutto",
        9 => "pj.notapenjualandetail.ppn",
        10 => "pj.notapenjualandetail.stockid",
        11 => "mstr.stock.namabarang",
      );
      $penjualan = NotaPenjualan::selectRaw(collect($columns)->implode(', '));
      $penjualan ->join('pj.notapenjualandetail', 'pj.notapenjualan.id', '=', 'pj.notapenjualandetail.notapenjualanid');
      $penjualan ->join('mstr.stock', 'mstr.stock.id', '=', 'pj.notapenjualandetail.stockid');
      $penjualan ->where('pj.notapenjualan.tokoid',$req->tokoid);
      $penjualan ->where("tglnota",'>=',$blnawal)->where("tglnota",'<=',$blnserver);
      $penjualan ->orderBy('namabarang','asc');
      $penjualan ->orderBy('tglnota','desc');

      $data = $penjualan->get();
      foreach ($data as $detail) {
            $totalharga += $detail->qtynota * $detail->hrgsatuannetto;
      }
      $user = $req->user();
      Excel::create('Riwayat Jual', function($excel) use ($rjual,$data,$totalharga,$user) {
        $excel->setTitle('Riwayat Jual');
        $excel->setCreator($user->name)->setCompany('SAS');
        $excel->setkeywords('OK OCE');
        $excel->setmanager($user->name);
        $excel->setsubject('Data Riwayat Jual Toko');
        $excel->setlastModifiedBy($user->name);
        $excel->setcategory('SAS OK OCE');
        $excel->setDescription('Data Riwayat Jual 6 Bulan terakhir');
        $excel->sheet('RiwayatJual', function($sheet) use ($rjual,$data,$totalharga,$user) {
            $sheet->loadView('transaksi.salesorder.riwayat',array('rjual' =>$rjual,'notajual'=>$data,'totalharga'=>$totalharga,'user'=>$user));
        });
      })->download('xlsx');   
    }

    public function getNextOrderNo($recordownerid){
      $next_no = '';
      $max_order = OrderPembelian::where('id', OrderPembelian::where('recordownerid', $recordownerid)->max('id'))->first();
      if ($max_order==null) {
        $next_no = '0000000001';
      }elseif (strlen($max_order->noorder)<10) {
        $next_no = '0000000001';
      }elseif ($max_order->noorder == '9999999999') {
        $next_no = '0000000001';
      }else {
        $next_no = substr('0000000000', 0, 10-strlen($max_order->noorder+1)).($max_order->noorder+1);
      }
      return $next_no;
    }

    public function copydooh(Request $req)
    {
        $orderpenjualan = OrderPenjualan::find($req->id);
        $supplierdefault = Supplier::where([['nama','ilike','KP-SOLO%'],['recordownerid',$req->session()->get('subcabang')]])->first();
        $data = array();
        $data_temp = array();
        $tgl = Carbon::now()->format('Ymd');
        foreach ($orderpenjualan->details as $detail) {
                    $temp_detail = [
                        'namabarang'     => $detail->barang->namabarang,
                        'satuan'         => $detail->barang->satuan,
                        'qtysoacc'       => $detail->qtysoacc,
                        'hrgsatuannetto' => $detail->hrgsatuannetto,
                        'hrgtotal'       => $detail->qtysoacc * $detail->hrgsatuannetto
                    ];
                    array_push($data, $temp_detail);
                
            
        }
        $dataOrder = [
            'id'             => $orderpenjualan->id,
            'tglpickinglist' => $orderpenjualan->tglpickinglist,
            'nopickinglist'  => $orderpenjualan->nopickinglist,
            'toko'           => $orderpenjualan->toko->namatoko,
            'alamat'         => $orderpenjualan->toko->alamat,
            'kota'           => $orderpenjualan->toko->kota,
            'kecamatan'      => $orderpenjualan->toko->kecamatan,
            'wilid'          => $orderpenjualan->toko->customwilayah,
            'idtoko'         => $orderpenjualan->toko->id,
        ];
        return response()->json([
            'success' => true,
            'order'   => $dataOrder,
            'barang'  => $data,
            'supplierdefault' => $supplierdefault,
        ]);
        
    }

    public function insertDooh(Request $req)
    {
      $orderpenjualan = OrderPenjualan::find($req->id);
      $ppn = AppSetting::where('recordownerid',$req->session()->get('subcabang'))->where('keyid','ppn')->first();
      $ppn = ($ppn) ? $ppn->value : 0;
      $data = [
        'recordownerid' => $req->session()->get('subcabang'),
        'tglorder'      => date('Y-m-d'),
        'noorder'       => $this->getNextOrderNo($req->session()->get('subcabang')),
        'supplierid'    => $req->supplierid,
        'tempo'         => $req->tempo,
        'keterangan'    => strtoupper($req->keterangan),
        'createdby'     => strtoupper($req->user()->username),
        'lastupdatedby' => strtoupper($req->user()->username)
      ];
     $order = OrderPembelian::create($data);
     foreach ($orderpenjualan->details as $detail) {
       $detailpembelian = OrderPembelianDetail::create([
              'orderpembelianid' => $order->id,
              'qtypenjualanbo'   => 0,
              'qtyrataratajual'  => 0,
              'stockid'          => $detail->stockid,
              'qtyorder'         => $detail->qtysoacc,
              'hrgsatuanbrutto'  => 0,
              'disc1'            => 0,
              'disc2'            => 0,
              'ppn'              => $ppn,
              'isarowid'         => NULL,
              // 'hrgsatuannetto'   => $detail->hrgsatuannetto,
              'hrgsatuannetto'   => 0,
              'createdby'        => strtoupper(auth()->user()->username),
              'lastupdatedby'    => strtoupper(auth()->user()->username)
          ]);
        $detail->orderpembeliandetailid = 1;
        $detail->save();
      }
      return response()->json([
          'success' => true,
          'message' => 'No. Order '.$order->noorder.' sudah terbentuk',
      ]);
    }

    public function copydodo(Request $req)
    {
        $orderpenjualan = OrderPenjualan::find($req->id);
        $recownerid     = $req->session()->get('subcabang');
        $subcabanguser  = SubCabang::find($recownerid);
        $data           = array();
        $data_temp      = array();
        $tgl            = Carbon::now()->format('Ymd');

        foreach ($orderpenjualan->details as $detail) {
          $temp_detail = [
              'namabarang'     => $detail->barang->namabarang,
              'satuan'         => $detail->barang->satuan,
              'qtysoacc'       => $detail->qtysoacc,
              'hrgsatuannetto' => $detail->hrgsatuannetto,
              'hrgtotal'       => $detail->qtysoacc * $detail->hrgsatuannetto
          ];
          array_push($data, $temp_detail);
        }

        $dataOrder = [
            'id'                  => $orderpenjualan->id,
            'noso'                => $orderpenjualan->noso,
            'tglpickinglist'      => $orderpenjualan->tglpickinglist,
            'nopickinglist'       => $orderpenjualan->nopickinglist,
            'toko'                => $orderpenjualan->toko->namatoko,
            'tokoid'              => $orderpenjualan->tokoid,
            'alamat'              => $orderpenjualan->toko->alamat,
            'kota'                => $orderpenjualan->toko->kota,
            'kecamatan'           => $orderpenjualan->toko->kecamatan,
            'wilid'               => $orderpenjualan->toko->customwilayah,
            'idtoko'              => $orderpenjualan->toko->id,
            'tipetransaksi'       => $orderpenjualan->tipetransaksi,
            'idexpedisi'          => ($orderpenjualan->expedisi == null) ? '' : $orderpenjualan->expedisi->id,
            'namaexpedisi'        => ($orderpenjualan->expedisi == null) ? '' : $orderpenjualan->expedisi->namaexpedisi,
            'salesman'            => ($orderpenjualan->karyawanidsalesman == null) ? '' : $orderpenjualan->salesman->namakaryawan,
            'salesmanid'          => ($orderpenjualan->karyawanidsalesman == null) ? '' : $orderpenjualan->salesman->id,
            'statustoko'          => $orderpenjualan->statustoko,
            'temponota'           => $orderpenjualan->temponota,
            'tempokirim'          => $orderpenjualan->tempokirim,
            'temposalesman'       => $orderpenjualan->temposalesman,
            'catatanpenjualan'    => $orderpenjualan->catatanpenjualan,
            'catatanpembayaran'   => $orderpenjualan->catatanpembayaran,
            'catatanpengiriman'   => $orderpenjualan->catatanpengiriman,
        ];
        return response()->json([
            'success' => true,
            'order'   => $dataOrder,
            'barang'  => $data,
            'subcabanguser'  => $subcabanguser,
        ]);
        
    }

    public function insertDodo(Request $req)
    {
      $orderpenjualan = OrderPenjualan::find($req->id);
      $orderpenjualannew = OrderPenjualan::create([
        'recordownerid'       => $orderpenjualan->recordownerid,
        'omsetsubcabangid'    => $orderpenjualan->omsetsubcabangid,
        'pengirimsubcabangid' => $orderpenjualan->pengirimsubcabangid,
        'noso'                => $orderpenjualan->noso,
        'tglso'               => date('Y-m-d'),
        'nopickinglist'       => $this->getNextPickingListNo($req->session()->get('subcabang')),
        'tglpickinglist'      => date('Y-m-d'),
        'tokoid'              => $orderpenjualan->tokoid,
        'expedisiid'          => $orderpenjualan->expedisiid,
        'statustoko'          => $orderpenjualan->statustoko,
        'temponota'           => $orderpenjualan->temponota,
        'tempokirim'          => $orderpenjualan->tempokirim,
        'temposalesman'       => $orderpenjualan->temposalesman,
        'tipetransaksi'       => $orderpenjualan->tipetransaksi,
        'karyawanidsalesman'  => $orderpenjualan->karyawanidsalesman,
        'catatanpenjualan'    => strtoupper($orderpenjualan->catatanpenjualan),
        'catatanpembayaran'   => strtoupper($orderpenjualan->catatanpembayaran),
        'catatanpengiriman'   => strtoupper($orderpenjualan->catatanpengiriman),
        'createdby'           => strtoupper($req->user()->username),
        'lastupdatedby'       => strtoupper($req->user()->username),
      ]);

      foreach ($orderpenjualan->details as $detail) {
        $detailpenjualan = OrderPenjualanDetail::create([
          'orderpenjualanid' => $orderpenjualannew->id,
          'stockid'          => $detail->stockid,
          'qtyso'            => $detail->qtyso,
          'qtysoacc'         => $detail->qtyso,
          'hrgbmk'           => 0,
          'hrgsatuanbrutto'  => $detail->hrgsatuanbrutto,
          'disc1'            => $detail->disc1,
          'disc2'            => $detail->disc2,
          'ppn'              => $detail->ppn,
          'hrgsatuannetto'   => $detail->hrgsatuannetto,
          'approvalmgmtidacc11' => NULL,
          'noacc11'             => NULL,
          'qtystockgudang'   => 0,
          'komisikhusus11'   => NULL,
          'isarowid'         => NULL,
          'createdby'        => strtoupper(auth()->user()->username),
          'lastupdatedby'    => strtoupper(auth()->user()->username),
          'orderpembeliandetailid' => NULL,
        ]);
      }
      return response()->json([
          'success' => true,
          'message' => 'No Picking List '.$orderpenjualannew->nopickinglist.' sudah terbentuk',
      ]);
    }

    public function updateNoSo(Request $req)
    {
      $orderpenjualan = OrderPenjualan::find($req->id);
      $orderpenjualan->timestamps = false;
      $orderpenjualan->noso = $req->noso;
      $orderpenjualan->save();

      return response()->json(['success' => true,]);
    }

    public function getAjuanUpdate(Request $req)
    {
      $idpenjualans = $req->id;
      foreach ($idpenjualans as $id) {
        $order = OrderPenjualan::select([
            0 => "pj.orderpenjualan.*",
            1 => "mstr.toko.namatoko",
            2 => "mstr.toko.kodetoko",
            3 => "mstr.toko.alamat",
            4 => "mstr.toko.kota",
            5 => "cbomset.kodecabang as omsetcabang",
            6 => "cbkirim.kodecabang as kirimcabang",
            7 => "hr.karyawan.kodesales",
          ])
          ->join("mstr.toko", "pj.orderpenjualan.tokoid", "=", "mstr.toko.id")
          ->join("hr.karyawan", "pj.orderpenjualan.karyawanidsalesman", "=", "hr.karyawan.id")
          ->join("mstr.subcabang as subcbomset", "pj.orderpenjualan.omsetsubcabangid", "=", "subcbomset.id")
          ->join("mstr.cabang as cbomset", "subcbomset.cabangid", "=", "cbomset.id")
          ->join("mstr.subcabang as subcbkirim", "pj.orderpenjualan.omsetsubcabangid", "=", "subcbkirim.id")
          ->join("mstr.cabang as cbkirim", "subcbkirim.cabangid", "=", "cbkirim.id")
          ->where("pj.orderpenjualan.id", $id)
          ->first();

        // // Staging
        // // Header
        $stagingorder = null;

        $cek = StagingOrderPenjualan::find($order->id);
        if(count($cek) == 0)
        {
          $stagingorder = New StagingOrderPenjualan();
          $stagingorder->id         = $order->id;
          $stagingorder->CreatedBy  = auth()->user()->name;
          $stagingorder->CreatedOn  = Carbon::now();
        }
        else
        {
          $stagingorder = $cek;
        }
        // $stagingorder->id                        = $order->id;
        // $stagingorder->recordownerid             = $order->recordownerid;
        // $stagingorder->omsetsubcabangid          = $order->omsetsubcabangid;
        // $stagingorder->pengirimsubcabangid       = $order->pengirimsubcabangid;
        // $stagingorder->noso                      = $order->noso;
        // $stagingorder->tglso                     = Carbon::parse($order->tglso)->format('Y-m-d');
        // $stagingorder->nopickinglist             = $order->nopickinglist;
        // $stagingorder->tglpickinglist            = Carbon::parse($order->tglpickinglist)->format('Y-m-d');
        // $stagingorder->noaccpiutang              = $order->noaccpiutang;
        // $stagingorder->karyawanidpkp             = $order->karyawanidpkp;
        // $stagingorder->tglaccpiutang             = $order->tglaccpiutang;
        // $stagingorder->rpaccpiutang              = $order->rpaccpiutang;
        // $stagingorder->rpsaldopiutang            = $order->rpsaldopiutang;
        // $stagingorder->rpsaldooverdue            = $order->rpsaldooverdue;
        // $stagingorder->rpsoaccproses             = $order->rpsoaccproses;
        // $stagingorder->rpplafon                  = $order->rpplafon;
        // $stagingorder->rpsisaplafon              = $order->rpsisaplafon;
        // $stagingorder->rpgit                     = $order->rpgit;
        // $stagingorder->tokoid                    = $order->tokoid;
        // $stagingorder->approvalmgmtidoverdue     = $order->approvalmgmtidoverdue;
        // $stagingorder->statustoko                = $order->statustoko;
        // $stagingorder->temponota                 = $order->temponota;
        // $stagingorder->tempokirim                = $order->tempokirim;
        // $stagingorder->temposalesman             = $order->temposalesman;
        // $stagingorder->tipetransaksi             = $order->tipetransaksi;
        // $stagingorder->karyawanidsalesman        = $order->karyawanidsalesman;
        // $stagingorder->catatanpenjualan          = $order->catatanpenjualan;
        // $stagingorder->catatanpembayaran         = $order->catatanpembayaran;
        // $stagingorder->catatanpengiriman         = $order->catatanpengiriman;
        // $stagingorder->print                     = $order->print;
        // $stagingorder->tglprintpickinglist       = $order->tglprintpickinglist;
        // $stagingorder->tglterimapilpiutang       = $order->tglterimapilpiutang;
        // $stagingorder->statusajuanhrg11          = $order->statusajuanhrg11;
        // $stagingorder->expedisiid                = $order->expedisiid;
        // $stagingorder->bomurni                   = $order->bomurni;
        // $stagingorder->tglpjkegudang             = $order->tglpjkegudang;
        // $stagingorder->alasanterlambatpjkegudang = $order->alasanterlambatpjkegudang;
        // $stagingorder->isarowid                  = $order->isarowid;
        // $stagingorder->createdby                 = $order->createdby;
        // $stagingorder->createdon                 = Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($order->createdon)->toDateTimeString());
        // $stagingorder->lastupdatedby             = $order->lastupdatedby;
        // $stagingorder->lastupdatedon             = Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($order->lastupdatedon)->toDateTimeString());
        $stagingorder->RecordOwnerID             = $order->recordownerid;
        $stagingorder->OmsetSubCabangID          = $order->omsetcabang;
        $stagingorder->PengirimSubCabangID       = $order->kirimcabang;
        $stagingorder->TglSO                     = Carbon::parse($order->tglso)->format('Y-m-d');
        $stagingorder->NoSO                      = $order->noso;
        $stagingorder->TokoID                    = $order->tokoid;
        $stagingorder->KodeTokoLama              = $order->kodetoko;
        $stagingorder->NamaTokoCabang            = $order->namatoko;
        $stagingorder->AlamatTokoCabang          = $order->alamat;
        $stagingorder->KotaCabang                = $order->kota;
        $stagingorder->StatusTokoCabang          = $order->statustoko;
        $stagingorder->NoPiL                     = $order->nopickinglist;
        $stagingorder->TglPiL                    = Carbon::parse($order->tglpickinglist)->format('Y-m-d');
        $stagingorder->KodeSales                 = $order->kodesales;
        $stagingorder->TglUpload                 = Carbon::now();
        $stagingorder->LastUpdatedBy             = auth()->user()->name;
        $stagingorder->LastUpdatedTime           = Carbon::now();
        $stagingorder->TglUpload00               = Carbon::now();
        $stagingorder->TglAjuan                  = Carbon::now();
        $stagingorder->TransactionType           = $order->tipetransaksi;
        $stagingorder->save();

        $details = OrderPenjualanDetail::select([
            0 => "pj.orderpenjualandetail.*",
            1 => "mstr.stock.kodebarang",
            2 => "mstr.stock.namabarang",
          ])
          ->join("mstr.stock", "mstr.stock.id", "=", "pj.orderpenjualandetail.stockid")
          ->where("pj.orderpenjualandetail.orderpenjualanid", $order->id)
          ->get();

        // Detail
        foreach($details as $detail) {
          $hppa = HPPA::select('nominalhppa')->where('stockid',$detail->stockid)->orderBy('tglaktif','desc')->limit(1)->first();
          $stagingorderdetail = null;

          $cekdetail = StagingOrderPenjualanDetail::find($detail->id);
          if(count($cekdetail) == 0)
          {
            $stagingorderdetail = new StagingOrderPenjualanDetail();
            $stagingorderdetail->id        = $detail->id;
            $stagingorderdetail->CreatedBy = auth()->user()->name;
            $stagingorderdetail->CreatedOn = Carbon::now();
          }
          else
          {
            $stagingorderdetail = $cekdetail;
          }
          // $stagingorderdetail->ID                     = $detail->id;
          // $stagingorderdetail->orderpenjualanid       = $detail->orderpenjualanid;
          // $stagingorderdetail->stockid                = $detail->stockid;
          // $stagingorderdetail->qtyso                  = $detail->qtyso;
          // $stagingorderdetail->qtysoacc               = $detail->qtysoacc;
          // $stagingorderdetail->hrgbmk                 = $detail->hrgbmk;
          // $stagingorderdetail->hrgsatuanbrutto        = $detail->hrgsatuanbrutto;
          // $stagingorderdetail->disc1                  = $detail->disc1;
          // $stagingorderdetail->disc2                  = $detail->disc2;
          // // $stagingorderdetail->pot                    = $detail->pot;
          // $stagingorderdetail->ppn                    = $detail->ppn;
          // $stagingorderdetail->hrgsatuannetto         = $detail->hrgsatuannetto;
          // $stagingorderdetail->approvalmgmtidacc11    = $detail->approvalmgmtidacc11;
          // $stagingorderdetail->noacc11                = $detail->noacc11;
          // $stagingorderdetail->catatan                = $detail->catatan;
          // $stagingorderdetail->qtystockgudang         = $detail->qtystockgudang;
          // $stagingorderdetail->komisikhusus11         = $detail->komisikhusus11;
          // $stagingorderdetail->orderpembeliandetailid = $detail->orderpembeliandetailid;
          // $stagingorderdetail->isarowid               = $detail->isarowid;
          // $stagingorderdetail->createdby              = $detail->createdby;
          // $stagingorderdetail->createdon              = Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($detail->createdon)->toDateTimeString());
          // $stagingorderdetail->lastupdatedby          = $detail->lastupdatedby;
          // $stagingorderdetail->lastupdatedon          = Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($detail->lastupdatedon)->toDateTimeString());
          $stagingorderdetail->StgOrderPenjualanID    = $detail->orderpenjualanid;
          $stagingorderdetail->KodeBarang             = $detail->kodebarang;
          $stagingorderdetail->NamaBarang             = $detail->namabarang;
          $stagingorderdetail->HrgNetto               = $detail->hrgsatuanbrutto;
          $stagingorderdetail->HrgBMK                 = $detail->hrgbmk;
          $stagingorderdetail->HrgPokok               = ($hppa) ? $hppa->nominalhppa : 0;
          $stagingorderdetail->Diskon1                = $detail->disc1;
          $stagingorderdetail->Diskon2                = $detail->disc2;
          $stagingorderdetail->Potongan               = $detail->pot;
          $stagingorderdetail->QtySO                  = $detail->qtysoacc;
          $stagingorderdetail->Catatan                = $detail->catatan;
          $stagingorderdetail->NoAcc                  = $detail->noacc11;        
          $stagingorderdetail->KomisiKhusus           = $detail->komisikhusus11;
          $stagingorderdetail->LastUpdatedBy          = auth()->user()->name;
          $stagingorderdetail->LastUpdatedTime        = Carbon::now();
          $stagingorderdetail->save();
        }

        // Update Status
        $order->statusajuanhrg11 = 'PROSES ACC';
        $order->save();
      }

      return response()->json(['success'=> true]);
    }

    public function getAcc11(Request $req)
    {
      echo "A" . $req->data;
      exit;

      $curuser = auth()->user()->name;
      $result = array(
        "result" => false
      );
      $data = array();
      $trans = false;

      try {
        $data = json_decode($req->data, true);

        // check minimal data
        $allow = array("RowID", "ID", "Details");
        foreach($data as $k => $v) {
          $i = array_search($k, $allow);
          if($i !== false) {
            unset($allow[$i]);
          }
        }
        if(count($allow) > 0) {
          throw new \Exception("Data request not completed,\nMissing data: " . join($allow, ", "));
        }

        /*
         * json format
         * {
         *    header.data, ...
         *    [details]: [{detail.data}, ...]
         * }
         */
        $dat = OrderPenjualan::find($data["ID"]);
        if(count($dat) <= 0) {
          throw new \Exception("Record header not found,\nID: " . $data["RowID"]);
        
        } else {
          $dat->statusajuanhrg11 = "SUDAH ACC";
          $dat->lastupdatedby    = $curuser;

          $details = array();
          foreach($data["Details"] as $ddata) {
            // check minimal data
            $allow = array("RowID", "ID", "HrgAcc", "QtyAcc", "NoAcc", "Catatan", "LastUpdatedBy");
            foreach($ddata as $k => $v) {
              $i = array_search($k, $allow);
              if($i !== false) {
                unset($allow[$i]);
              }
            }
            if(count($allow) > 0) {
              throw new \Exception("Data detail request not completed\nMissing data: " . join($allow, ", "));
            }

            // must exists data
            $dat2 = OrderPenjualanDetail::find($ddata["ID"]);
            if(count($dat2) <= 0) {
              throw new \Exception("Record detail not found,\nID: " . $ddata["RowID"]);

            } else {
              $hrgacc = (double)$ddata["HrgAcc"];
              $disc1  = (double)$dat2->disc1;
              $disc2  = (double)$dat2->disc2;
              $ppn    = (double)$dat2->ppn;
              $pot    = (double)$dat2->pot;
              if($pot == null) { $pot = 0; }
              $hrgnetto = $hrgacc - ($disc1 / 100 * $hrgacc) - ($disc2 / 100 * $hrgacc) + ($ppn / 100 * $hrgacc) - $pot;

              $dat2->hrgsatuanbrutto  = (int)$ddata["HrgAcc"];
              $dat2->hrgsatuannetto   = $hrgnetto;
              $dat2->qtysoacc         = $ddata["QtyAcc"];
              $dat2->noacc11          = $ddata["NoAcc"];
              if($ddata["Catatan"] != "" && $dat2->catatan != "BATAL PICKING LIST")
              {
                $dat2->catatan        = $ddata["Catatan"];
              }
              $dat2->lastupdatedby    = $ddata["LastUpdatedBy"];

              $details[$ddata["RowID"]] = $dat2;
            }

            // sensitive query
            $trans = true;
            DB::beginTransaction();

            if($dat->save()) {
              foreach($details as $k => $v) {
                if(!$v->save()) {
                  throw new \Exception("Cannot update detail\nID: " . $k);
                  break;
                }
              }

            } else {
              throw new \Exception("Cannot update header\nID: " .$data["RowID"]);
            }

            DB::commit();
            $result["result"] = true;
          }
        }

      } catch(\Exception $ex) {
        if($trans) { // rollback when fail
          DB::rollback();
        }

        $result["msg"] = $ex->getMessage();
        $result["line"] = $ex->getLine();
        $result["result"] = false;
      }
        
      return response()->json($result);
    }


    public function synchHeader(Request $req)
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

    public function synchDetail(Request $req)
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

    public function cekBarang(Request $req)
    {
      $opjd = OrderPenjualanDetail::where('orderpenjualanid',$req->id)->where('stockid',$req->stockid)->exists();
      return response()->json($opjd);
    }

    public function cekMarkup(Request $req)
    {
      $opj  = OrderPenjualan::find($req->id);

      foreach($opj->details as $detail) {
        // Cek harga markup
        $barang = $detail->barang;
        $tabel  = substr($opj->statustoko,1);
        $bmk    = strtolower(substr($opj->statustoko,0,1));

        if($tabel == 1){
          $harga = HistoryBMK1::select('hrg'.$bmk.'1 as harga')->where('stockid', $detail->stockid)->orderBy('tglaktif','desc')->first();
        }else {
          $harga = HistoryBMK2::select('hrg'.$bmk.'2 as harga')->where('stockid', $detail->stockid)->orderBy('tglaktif','desc')->first();
        }

        $mupbarang = null;
        // if($barang->jenis_tipe_brg == 'FB') {
        //     $mupbarang = AppSetting::select('value')->where('recordownerid',$req->session()->get('subcabang'))->where('keyid','MUPbarangB')->first();
        // }elseif($barang->jenis_tipe_brg == 'FE') {
        //     $mupbarang = AppSetting::select('value')->where('recordownerid',$req->session()->get('subcabang'))->where('keyid','MUPbarangE')->first();
        // }
        if($barang->jenis_tipe_brg == 'FE') {
            $mupbarang = AppSetting::select('value')->where('recordownerid',$req->session()->get('subcabang'))->where('keyid','MUPbarangE')->first();
        }else{
            $mupbarang = AppSetting::select('value')->where('recordownerid',$req->session()->get('subcabang'))->where('keyid','MUPbarangB')->first();
        }

        if($mupbarang) {
            $harga->harga = $harga->harga+($harga->harga*$mupbarang->value/100);
        }

        if($detail->hrgsatuannetto < $harga->harga){
          return response()->json(false);
        }
      }

      return response()->json(true);
    }

}
