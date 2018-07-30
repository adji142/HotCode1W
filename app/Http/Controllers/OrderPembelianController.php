<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalManagement;
use App\Models\ApprovalManagementDetail;
use App\Models\ApprovalModule;
use App\Models\AppSetting;
use App\Models\Barang;
use App\Models\BudgetPersediaan;
use App\Models\NotaPenjualan;
use App\Models\NotaPenjualanDetail;
use App\Models\OrderPembelian;
use App\Models\OrderPembelianDetail;
use App\Models\OrderPenjualan;
use App\Models\OrderPenjualanDetail;
// use App\Models\RekapStockDaily;
use App\Models\StagingOrderPembelian;
use App\Models\StagingOrderPembelianDetail;
use App\Models\SubCabang;
use App\Models\Supplier;
// use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DB;
use PDF;
use XLSXWriter;

class OrderPembelianController extends Controller
{
    protected $original_column = array(
      1 => "pb.orderpembelian.tglorder",
      2 => "pb.orderpembelian.noorder",
      3 => "mstr.supplier.nama",
      4 => "detail.status",
    );

    public function index(Request $request)
    {
      $ppn = AppSetting::where('recordownerid',$request->session()->get('subcabang'))->where('keyid','ppn')->first();
      $ppn = ($ppn) ? $ppn->value : 0;
      return view('transaksi.orderpembelian.index', compact('ppn'));
    }

    public function getData(Request $req)
    {
      // gunakan permission dari indexnya aja
      if(!$req->user()->can('orderpembelian.index')) {
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
      $tglmulai   = Carbon::parse($req->tglmulai)->toDateString();
      $tglselesai = Carbon::parse($req->tglselesai)->toDateString();

      $filter_count = 0;
      $empty_filter = 0;
      $columns = array(
        0 => "pb.orderpembelian.tglorder",
        1 => "pb.orderpembelian.noorder",
        2 => "mstr.supplier.nama as suppliernama",
        3 => "pb.orderpembelian.id",
        4 => "pb.orderpembelian.approvalmgmtid",
        5 => "detail.status",
        6 => "detail.keterangan as keteranganapproval",
        7 => "detail.createdon as tglapproval",
        8 => "pb.orderpembelian.tempo",
        9 => "pb.orderpembelian.keterangan",
        10 => "pb.orderpembelian.supplierid",
        11 => "pb.orderpembelian.recordownerid",
        12 => "pb.orderpembelian.lastupdatedby",
        13 => "pb.orderpembelian.lastupdatedon",
        14 => "pb.orderpembelian.sync11",
      );

      foreach($req->custom_search as $search){
        if(empty($search['text'])){
          $empty_filter++;
        }
      }

      $pembelian = OrderPembelian::select($columns)->join('mstr.supplier', 'pb.orderpembelian.supplierid', '=', 'mstr.supplier.id');
      $pembelian->leftJoin('secure.approvalmgmtdetail as detail', function ($leftJoin) {
        $leftJoin->on('detail.approvalmgmtid', '=', 'pb.orderpembelian.approvalmgmtid');
        $leftJoin->on('detail.createdon', '=',DB::raw('(select max(createdon) from secure.approvalmgmtdetail where approvalmgmtid = detail.approvalmgmtid)'));
      });
      $pembelian->where("pb.orderpembelian.recordownerid",$req->session()->get('subcabang'));
      // $pembelian->where("pb.orderpembelian.tglorder",'>=',Carbon::parse($req->tglmulai))->where("pb.orderpembelian.tglorder",'<=',Carbon::parse($req->tglselesai));
      $pembelian->whereRaw("DATE(pb.orderpembelian.tglorder) BETWEEN '".$tglmulai."' AND '".$tglselesai."'");
      $total_data = $pembelian->count();

      if($empty_filter){
        foreach($req->custom_search as $i=>$search){
          if($search['text'] != ''){
            if($i == 2 || $i == 3 || $i == 4){
              if($search['filter'] == '='){
                $pembelian->where($this->original_column[$i],'ilike','%'.$search['text'].'%');
              }else{
                $pembelian->where($this->original_column[$i],'not ilike','%'.$search['text'].'%');
              }
            }else{
              $pembelian->where($this->original_column[$i],$search['filter'],$search['text']);
            }
            $filter_count++;
          }
        }
      }

      if($filter_count > 0){
        $filtered_data = $pembelian->count();
        $pembelian->skip($req->start)->take($req->length);
      }else{
        $filtered_data = $total_data;
        $pembelian->skip($req->start)->take($req->length);
      }
      if ($req->issync || $req->tipe_edit) {
        $pembelian->orderBy('pb.orderpembelian.lastupdatedon','desc');
      }else {
        if(array_key_exists($req->order[0]['column'], $this->original_column)){
          $pembelian->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
        }
        $pembelian->orderBy('pb.orderpembelian.noorder','asc');
      }

      // Data
      $data = [];
      foreach ($pembelian->get() as $k => $val) {
        $val->lastupdatedon = $val->lastupdatedon;
        $val->tglapproval   = ($val->tglapproval) ? Carbon::parse($val->tglapproval)->format('d-m-Y H:i:s') : '';
        $data[$k] = $val->toArray();
        $data[$k]['DT_RowId'] = 'gv1_'.$val->id;

        if(Carbon::parse($val->tglorder)->toDateString() == Carbon::now()->toDateString()){
          if ($val->approvalmgmtid != null) {
            $msg_edit = 'Tidak bisa edit data. Ka. Cabang sudah proses persetujuan order. Hubungi Manager Anda.';
            $msg_add  = 'Tidak bisa tambah data. Ka. Cabang sudah proses persetujuan order. Hubungi Manager Anda.';
          }else {
            $msg_edit = 'edit';
            $msg_add  = 'add';
          }
        }else {
          $msg_edit = 'Tidak bisa edit data. Tanggal server tidak sama dengan Tanggal Order. Hubungi Manager Anda.';
          $msg_add  = 'Tidak bisa tambah data. Tanggal server tidak sama dengan Tanggal Order. Hubungi Manager Anda.';
        }

        $jumlahdetails = count(OrderPembelian::find($val->id)->details);
        if ($jumlahdetails > 0) {
          $msg_delete = 'Tidak bisa hapus record. Sudah ada record di Order Pembelian Detail. Hapus detail terlebih dahulu.';
        }else {
          $msg_delete = 'auth';
        }

        if($val->approvalmgmtid){
          $msg_approval = 'reapproval';
        }else{
          $msg_approval = 'approval';
        }

        // Action Pindah Ke Backend
        $action = "";
        if($val->sync11 != null) {
          $action = "<div class='checkbox checkboxaction'><input type='checkbox' class='flat' value='".$val->id."' checked disabled></div>";
        }else {
          if($val->status == 'APPROVED') {
            $action .= "<div class='checkbox checkboxaction'><input type='checkbox' class='flat' value='".$val->id."'></div>";
          }else{
            $action .= "<div class='checkbox checkboxaction'><input type='checkbox' class='flat' value='".$val->id."'disabled></div>";
          }
          $action .= "<div class='btn btn-success btn-xs no-margin-action skeyF1' data-toggle='tooltip' data-placement='bottom' title='Tambah Detail - F1' onclick='tambahDetail(this)' data-message='".$msg_add."'><i class='fa fa-plus'></i></div>";
          $action .= "<div class='btn btn-warning btn-xs no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='Edit - F2' onclick='update(this)' data-message='".$msg_edit."' data-tipe='header'><i class='fa fa-pencil'></i></div>";
          $action .= "<div class='btn btn-danger btn-xs no-margin-action skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus - Del' onclick='deleteOrder(this)' data-message='".$msg_delete."' data-tipe='header'><i class='fa fa-trash'></i></div>";
          $action .= "<div class='btn-group no-margin-action'><button type='button' class='btn btn-primary dropdown-toggle btn-xs no-margin-action' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>A <span class='caret'></span></button>";
          $action .= "<ul class='dropdown-menu'>";
          if($val->status == 'APPROVED' || $val->status == 'PENGAJUAN') {
            $action .= "<li><a href='javascript:void(0)'>APR Order PB</a></li>";
          }else{
            $action .= "<li><a href='#' onclick='approval(this)' data-message='".$msg_approval."'>APR Order PB</a></li>";
          }
          $action .= "<li><a class='skeyF3' onclick='cetakoh(this,\"pdf\")'>Cetak PDF OH - F3</a></li>";
          $action .= "<li><a href='#' class='skeyF4' onclick='cetakoh(this,\"excel\")'>Cetak EXCEL OH - F4</a></li>";
          $action .= "</ul></div>";
        }

        $data[$k]['action'] = $action;

      }

      return response()->json([
        'draw'            => $req->draw,
        'recordsTotal'    => $total_data,
        'recordsFiltered' => $filtered_data,
        'data'            => $data,
      ]);
    }

    public function ceksbp(Request $req)
    {
      $kel = ['B','E','Lain'];
      $bp  = BudgetPersediaan::where('recordownerid',$req->session()->get('subcabang'))->orderBy('tglaktif','desc')->limit(1)->first()->toArray();

      $op = OrderPembelianDetail::selectRaw("
        (CASE WHEN (left(mstr.stock.kodebarang,2) = 'FB' OR left(mstr.stock.kodebarang,2) = 'FE') THEN substring(mstr.stock.kodebarang,2,1) ELSE 'Lain' END) as kode,
        SUM(pb.orderpembeliandetail.qtyorder*pb.orderpembeliandetail.hrgsatuannetto) as total
      ")
      ->join('mstr.stock','pb.orderpembeliandetail.stockid','=','mstr.stock.id')
      ->where('orderpembelianid',$req->id)
      ->groupBy('kode')->get()->toArray();

      $opdp = OrderPembelianDetail::selectRaw("
        (CASE WHEN (left(mstr.stock.kodebarang,2) = 'FB' OR left(mstr.stock.kodebarang,2) = 'FE') THEN substring(mstr.stock.kodebarang,2,1) ELSE 'Lain' END) as kode,
        SUM(pb.orderpembeliandetail.qtyorder*pb.orderpembeliandetail.hrgsatuannetto) as total
      ")
      ->join('pb.orderpembelian','pb.orderpembeliandetail.orderpembelianid','=','pb.orderpembelian.id')
      ->leftJoin('pb.notapembelian','pb.orderpembelian.noorder','=','pb.notapembelian.noorder')
      ->join('mstr.stock','pb.orderpembeliandetail.stockid','=','mstr.stock.id')
      ->where("pb.orderpembelian.recordownerid",$req->session()->get('subcabang'))
      ->whereRaw("pb.notapembelian.id IS NULL")
      ->whereRaw("pb.orderpembelian.tglorder < NOW() - INTERVAL '14 days'")
      ->groupBy('kode')
      ->get()->toArray();

      $persediaan = Barang::selectRaw("(CASE WHEN (left(mstr.stock.kodebarang,2) = 'FB' OR left(mstr.stock.kodebarang,2) = 'FE') THEN substring(mstr.stock.kodebarang,2,1) ELSE 'Lain' END) as kode, SUM(rpstokakhir) as total")
      ->join(DB::raw("(SELECT DISTINCT ON (stockid) stockid, rpstokakhir, tanggal
        FROM stk.rekapstockdaily WHERE recordownerid = ".$req->session()->get('subcabang')."
        AND stk.rekapstockdaily.tanggal >= NOW() - INTERVAL '6 months'
        ORDER  BY stockid, tanggal DESC) as rekapstok"),'rekapstok.stockid','=','mstr.stock.id')
      ->where('mstr.stock.statusaktif','=','1')
      ->groupBy('kode')
      ->get()->toArray();

      $sbp = 0;
      $pesan = "";
      foreach($kel as $k) {
        $key_op         = array_search($k, array_column($op, 'kode'));
        // $key_persediaan = array_search($k, array_column($persediaan, 'kode'));
        $key_opdp       = array_search($k, array_column($opdp, 'kode'));
        if($key_op !== false){
          $bp_nominal         = $bp['budget'.strtolower($k)];
          // $persediaan_nominal = ($key_persediaan ? $persediaan[$key_persediaan]['total'] : 0);
          $persediaan_nominal = 0;
          $opdp_nominal       = ($key_opdp !== false ? $opdp[$key_opdp]['total'] : 0);
          $op_nominal         = ($key_op !== false ? $op[$key_op]['total'] : 0);

          $sbp = $bp_nominal - $persediaan_nominal - $op_nominal - $opdp_nominal;

          $pesan = "";
          $pesan .= "Tidak bisa ACC order pembelian,<br/>Sisa Buget Barang ".$k." Tidak Cukup <br/>";
          $pesan .= "Buget Kelompok Barang ".$k." : <b>".number_format($bp_nominal,2,',','.')."</b><br/>";
          $pesan .= "Persediaan Kelompok Barang ".$k." : <b>".number_format($persediaan_nominal,2,',','.')."</b><br/>";
          $pesan .= "Order Pembelian Kelompok Barang ".$k." dalam Proses : <b>".number_format($opdp_nominal,2,',','.')."</b><br/>";
          $pesan .= "Nilai Order Kelompok Barang ".$k." : <b>".number_format($op_nominal,2,',','.')."</b><br/>";
          $pesan .= "Sisa Buget Persediaan Kelompok Barang ".$k." : <b>".number_format($sbp,2,',','.')."</b><br/>";
        }

        if($sbp < 0) break;
      }

      return response()->json(['sbp'=>$sbp,'pesan'=>$pesan]);
    }

    public function getDataDetail(Request $req)
    {
      // gunakan permission dari indexnya aja
      if(!$req->user()->can('orderpembelian.index')) {
        return response()->json(['node' => []]);
      }

      // jika lolos, tampilkan data
      $order = OrderPembelian::find($req->id);
      if(Carbon::parse($order->tglorder)->toDateString() == Carbon::now()->toDateString()){
        if ($order->approvalmgmtid != null) {
          $edit = 'Tidak bisa edit data. Ka. Cabang sudah proses persetujuan order. Hubungi Manager anda.';
          $delete = 'Tidak bisa hapus data. Ka. Cabang sudah proses persetujuan order. Hubungi Manager anda.';
        }else {
          $edit = 'edit';
          $delete = 'auth';
        }
      }else {
        $edit = 'Tidak bisa edit data. Tanggal server tidak sama dengan Tanggal Order. Hubungi Manager Anda.';
        $delete = 'Tidak bisa delete data. Tanggal server tidak sama dengan Tanggal Order. Hubungi Manager Anda.';
      }

      $order_details = $order->details;
      $node = array();
      foreach ($order_details as $detail) {
        $barang = $detail->barang;

        $action = '';
        if ($order->sync11 == null) {
          $action .= '<div class="btn btn-warning btn-xs skeyF2" data-toggle="tooltip" data-placement="bottom" title="Edit - F2" onclick="update(this)" data-message="'.$edit.'" data-tipe="detail"><i class="fa fa-pencil"></i></div>';
          $action .= '<div class="btn btn-danger btn-xs skeyDel" data-toggle="tooltip" data-placement="bottom" title="Hapus - Del" onclick="deleteOrder(this)" data-message="'.$delete.'" data-tipe="detail"><i class="fa fa-trash"></i></div>';
        }

        $temp = [
          'DT_RowId'   => 'gv2_'.$detail->id,
          'id'         => $detail->id,
          'barang'     => $barang->namabarang,
          'satuan'     => $barang->satuan,
          'qtyorder'   => $detail->qtyorder+$detail->qtytambahan,
          'hrgsatuannetto' => number_format($detail->hrgsatuannetto,2,',','.'),
          'hrgtotalnetto'  => number_format($detail->qtyorder*$detail->hrgsatuannetto,0,',','.'),
          'action'         => $action,
        ];
        array_push($node, $temp);
      }
      return response()->json([
        'node' => $node,
      ]);
    }

    public function getDetail(Request $req)
    {
      $detail = OrderPembelianDetail::find($req->id);

      $hrgdisc1 = (1-($detail->disc1/100)) * $detail->hrgsatuanbrutto;
      $hrgdisc2 = (1-($detail->disc2/100)) * $hrgdisc1;

      return response()->json([
        'orderpembelianid' => $detail->orderpembelianid,
        'orderdetailid'    => $detail->id,
        'barangid'         => $detail->stockid,
        'kodebarang'       => $detail->barang->kodebarang,
        'namabarang'       => $detail->barang->namabarang,
        'satuan'           => $detail->barang->satuan,
        'qtyorder'         => $detail->qtyorder,
        'qtypenjualanbo'   => $detail->qtypenjualanbo,
        'qtyrataratajual'  => $detail->qtyrataratajual,
        'qtystokakhir'     => $detail->qtystokakhir,
        'hrgsatuanbrutto'  => $detail->hrgsatuanbrutto,
        'disc1'            => $detail->disc1,
        'hrgdisc1'         => $hrgdisc1,
        'disc2'            => $detail->disc2,
        'hrgdisc2'         => $hrgdisc2,
        'ppn'              => $detail->ppn,
        'hrgsatuannetto'   => $detail->hrgsatuannetto,
        'hrgtotalnetto'    => $detail->qtyorder * $detail->hrgsatuannetto,
        'keteranganbarang' => $detail->keterangan,
        'lastupdatedby'    => $detail->lastupdatedby,
        'lastupdatedon'    => $detail->lastupdatedon,
      ]);
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

    public function tambah(Request $request)
    {
      if ($request->session()->get('subcabang')) {
        $subcabanguser   = SubCabang::find($request->session()->get('subcabang'))->kodesubcabang;
        $supplierdefault = Supplier::where([['nama','ilike','KP-SOLO%'],['recordownerid',$request->session()->get('subcabang')]])->first();
        $ppn = AppSetting::where('recordownerid',$request->session()->get('subcabang'))->where('keyid','ppn')->first();
        $ppn = ($ppn) ? $ppn->value : 0;
        return view('transaksi.orderpembelian.form', compact('subcabanguser', 'supplierdefault', 'ppn'));
      }else {
        return redirect()->route('orderpembelian.index');
      }
    }

    public function simpan(Request $request)
    {
      $subcabang = $request->subcabang;
      if (empty($subcabang)) $subcabang = session("subcabang");
      $data = [
        'recordownerid' => $request->session()->get('subcabang'),
        'tglorder'      => date('Y-m-d'),
        'noorder'       => $this->getNextOrderNo($request->session()->get('subcabang')),
        'supplierid'    => $request->supplierid,
        'tempo'         => $request->tempo,
        'keterangan'    => strtoupper($request->keterangan),
        'createdby'     => strtoupper($request->user()->username),
        'lastupdatedby' => strtoupper($request->user()->username)
      ];
      $order = OrderPembelian::create($data);
      return response()->json(['success'=>true, 'orderid'=>$order->id, 'noorder'=>$order->noorder, 'tglorder'=>date('d-m-Y',strtotime($order->tglorder))]);
    }

    public function simpanDetail(Request $request)
    {
      $opbd = new OrderPembelianDetail;
      $opbd->orderpembelianid = $request->orderpembelianid;
      $opbd->stockid          = $request->stockid;
      $opbd->qtyorder         = ($request->qtyorder) ? $request->qtyorder : 0;
      $opbd->qtytambahan      = ($request->qtytambahan) ? $request->qtytambahan : 0;
      $opbd->qtypenjualanbo   = ($request->qtypenjualanbo) ? $request->qtypenjualanbo : 0;
      $opbd->qtyrataratajual  = ($request->qtyrataratajual) ? $request->qtyrataratajual : 0;
      $opbd->qtystokakhir     = ($request->qtystokakhir) ? $request->qtystokakhir : 0;
      $opbd->hrgsatuanbrutto  = ($request->hrgsatuanbrutto) ? $request->hrgsatuanbrutto : 0;
      $opbd->disc1            = ($request->disc1) ? $request->disc1 : 0;
      $opbd->disc2            = ($request->disc2) ? $request->disc2 : 0;
      $opbd->ppn              = ($request->ppn) ? $request->ppn :0;
      $opbd->hrgsatuannetto   = $request->hrgsatuannetto;
      $opbd->keterangan       = strtoupper($request->keterangandetail);
      $opbd->createdby        = strtoupper($request->user()->username);
      $opbd->lastupdatedby    = strtoupper($request->user()->username);
      $opbd->save();
      return response()->json(['success'=>true]);
    }

    public function getHitungBO($stockid,Request $req)
    {
      //$tglmulai     = date("d-m-Y", strtotime("-7 day"));
      //$tglakhir     = date("d-m-Y",strtotime("-1 day"));
      //dd($stockid);
      $tglmulai ='01-12-2016';
      $tglakhir ='12-12-2016';
      $tglstok  ='01-07-2016';
      $columns = array(
        0 => "mstr.stock.id as stockid",
        1 => "mstr.stock.namabarang",
        2 => "mstr.stock.satuan",
        3 => "(pb.orderpembeliandetail.qtystokakhir) as qtyorder",
        4 => "stk.standarstock.rataratajual",
        5 => "(stk.standarstock.rataratajual * mstr.stock.stockmin) as qtystockmin",
        6 => "pb.orderpembeliandetail.qtystokakhir",
      );
      $pembelian = OrderPembelianDetail::selectRaw(collect($columns)->implode(', '))
                                        ->join('mstr.stock', 'pb.orderpembeliandetail.stockid', '=','mstr.stock.id')
                                        ->join('stk.standarstock', 'stk.standarstock.stockid', '=','mstr.stock.id')
                                        ->where('stk.standarstock.tglaktif','=',Carbon::parse($tglstok))
                                        ->where('stk.standarstock.stockid','=',$stockid);
      $pembelian->skip($req->start)->take($req->length);
      $data = array();
      foreach ($pembelian->get() as $key => $d) {
        $qtybo = $d->getPenjualanBo($tglmulai,$tglakhir);
        if($d->qtystockmin-$d->qtyorder+$qtybo > 0 && $d->stockid=$stockid){
          $data = [
              'stockid'       => $d->stockid,
              'namabarang'    => $d->namabarang,
              'qtyorder'      => $d->qtystockmin-$d->qtyorder+$qtybo,
              'rataratajual'  => $d->rataratajual,
              'qtystockmin'   => $d->qtystockmin,
              'qtystokakhir'  => $d->qtystokakhir,
              'qtybo'         => $qtybo,
          ];
          // array_push($data, $temp_detail);
        }
        return response()->json($data);   
       }
    }

    public function ubah(Request $req){
      $order = OrderPembelian::find($req->data['orderid']);
      $order->supplierid    = $req->data['supplier'];
      $order->tempo         = $req->data['tempo'];
      $order->keterangan    = strtoupper($req->data['keterangan']);
      $order->lastupdatedby = strtoupper($req->user()->username);
      $order->save();

      return response()->json([
        'id'         => $order->id,
        'tglorder'   => $order->tglorder,
        'noorder'    => $order->noorder,
        'tempo'      => $order->tempo,
        'keterangan' => $order->keterangan,
        'supplier'   => $order->supplier->nama,
      ]);
    }

    public function ubahDetail(Request $req){
      $detail = OrderPembelianDetail::find($req->orderdetailid);
      $detail->stockid         = $req->stockid;
      $detail->qtyorder        = $req->qtyorder;
      $detail->hrgsatuanbrutto = $req->hrgsatuanbrutto;
      $detail->disc1           = $req->disc1;
      $detail->disc2           = $req->disc2;
      $detail->ppn             = $req->ppn;
      $detail->hrgsatuannetto  = $req->hrgsatuannetto;
      $detail->keterangan      = strtoupper($req->keterangandetail);
      $detail->lastupdatedby   = strtoupper($req->user()->username);
      $detail->save();

      return response()->json(['success'=>true]);
    }

    public function kewenangan(Request $req)
    {
      $lastUserId = auth()->user()->id;
      if(auth()->attempt(['username'=>$req->username,'password'=>$req->password]))
      {
        if(auth()->user()->can($req->permission))
        {
          auth()->loginUsingId($lastUserId);
          if ($req->tipe == 'header') {
            $order = OrderPembelian::find($req->orderid);
            $order->delete();
          }else {
            $detail = OrderPembelianDetail::find($req->orderid);
            $detail->delete();
          }
          return response()->json(['success' => true]);
        }
      }
      return response()->json(['success' => false]);
    }

    public function getRatarataJual($stockid,$tglstok)
    {
      $tglstok = date("d-m-Y");
      $columns = array(
        0 => "mstr.stock.id as stockid",
        1 => "stk.standarstock.rataratajual as rataratajual",
        2 => "(stk.standarstock.rataratajual * mstr.stock.stockmin) as qtystockmin"
      );
      $ratarata = Barang::selectRaw(collect($columns)->implode(', '))
                                        ->join('stk.standarstock', 'stk.standarstock.stockid', '=','mstr.stock.id')
                                        ->where('stk.standarstock.stockid','=',$stockid)
                                        ->where('stk.standarstock.tglaktif','=',Carbon::parse($tglstok));
      $data = ($ratarata->first()) ? $ratarata->first()->rataratajual : 0;
      return json_decode($data, true);                                
    }

    public function getPenjualanBo($stockid,$tglmulai,$tglakhir)
    {
      $tglmulai = Carbon::now()->subDays(7);
      $tglakhir = Carbon::now()->subDays(1);
      // $tglmulai = date("d-m-Y", strtotime("-7 day"));
      // $tglakhir = date("d-m-Y",strtotime("-1 day"));
      // $tglmulai ='01-12-2016';
      // $tglakhir ='12-12-2016';
      $columns = array(
        0 => "pj.orderpenjualandetail.stockid as id",
        1 => "SUM(pj.orderpenjualandetail.qtysoacc) - SUM(pj.notapenjualandetail.qtynota) as total"
      );
      $penjualanbo  = OrderPenjualan::selectRaw(collect($columns)->implode(', '))
                                ->leftJoin('pj.orderpenjualandetail', 'pj.orderpenjualandetail.orderpenjualanid', '=', 'pj.orderpenjualan.id')
                                ->leftJoin('pj.notapenjualan', 'pj.notapenjualan.orderpenjualanid', '=', 'pj.orderpenjualan.id')
                                ->leftJoin('pj.notapenjualandetail', 'pj.notapenjualandetail.notapenjualanid', '=', 'pj.notapenjualan.id')
                                ->where('pj.orderpenjualandetail.stockid','=',$stockid)
                                ->where("pj.orderpenjualan.tglpickinglist",'>=',$tglmulai)->where("pj.orderpenjualan.tglpickinglist",'<=',$tglakhir)
                                ->groupBy('pj.orderpenjualandetail.stockid')
                                ->havingRaw('SUM(pj.orderpenjualandetail.qtysoacc) - SUM(pj.notapenjualandetail.qtynota) > 0');                
      //$data = $penjualanbo->first();
      $data = ($penjualanbo->first()) ? $penjualanbo->first()->total : 0;
      return json_decode($data, true);
    }

    public function getDataDetailStokTipis(Request $req)
    {
      $tglmulai     = date("d-m-Y", strtotime("-7 day"));
      $tglakhir     = date("d-m-Y",strtotime("-1 day"));
      // $tglmulai ='01-12-2016';
      // $tglakhir ='12-12-2016';
      $tglstok  ='01-07-2016';
      $columns = array(
        0 => "mstr.stock.id as stockid",
        1 => "mstr.stock.namabarang",
        2 => "mstr.stock.satuan",
        3 => "(pb.orderpembeliandetail.qtystokakhir) as qtyorder",
        4 => "sst.rataratajual",
        5 => "(sst.rataratajual * mstr.stock.stockmin) as qtystockmin",
        6 => "pb.orderpembeliandetail.qtystokakhir",
      );
      $pembelian = OrderPembelianDetail::selectRaw(collect($columns)->implode(', '));
      $pembelian->join('mstr.stock', 'pb.orderpembeliandetail.stockid', '=','mstr.stock.id');

      $pembelian->join(DB::raw("(SELECT distinct(stockid) as stockid, rataratajual, tglaktif FROM stk.standarstock ORDER BY tglaktif DESC) as sst"), 'sst.stockid', '=','pb.orderpembeliandetail.stockid');
      $pembelian->where('mstr.stock.statusaktif','=',TRUE);
      // ->where('sst.tglaktif','=',Carbon::now());
      // ->where('sst.tglaktif','=',Carbon::parse($tglstok));
                                        
      $pembelian->skip($req->start)->take($req->length);
      $data = array();
      foreach ($pembelian->get() as $key => $d) {
        $qtybo = $d->getPenjualanBo($tglmulai,$tglakhir);
        if($d->qtystockmin-$d->qtyorder+$qtybo > 0 ){
          $temp_detail = [
              'stockid'       => $d->stockid,
              'satuan'        => $d->satuan,
              'namabarang'    => $d->namabarang,
              'qtyorder'      => $d->qtystockmin-$d->qtyorder+$qtybo,
              'rataratajual'  => $d->rataratajual,
              'qtystockmin'   => $d->qtystockmin,
              'qtystokakhir'  => $d->qtystokakhir,
              'qtybo'         => $qtybo,
          ];
          array_push($data, $temp_detail);
        }
      }
      
      $total_data = count($data);

      return response()->json([
        'recordsTotal'       => $total_data,
        'data'               => $data,
      ]);
    }

    public function stokTipis(Request $request){
      $supplierdefault = Supplier::where([['nama','ilike','KP-SOLO%'],['recordownerid',$request->session()->get('subcabang')]])->first();
      return view('transaksi.orderpembelian.formstoktipis',compact('supplierdefault'));
    }

    public function cekStokTipis(Request $request)
    {
      $idstocks     = $request->datadetails;
      $totaldetails = count($request->datadetails);
      $tglmulai     = date("d-m-Y", strtotime("-14 day"));
      $tglselesai   = date("d-m-Y");
      foreach ($idstocks as $key => $val) {
        $data[$key] = json_decode($val);
      }
      for ($i=0; $i < $totaldetails; $i++) {
        $columns = array(
          0 => "mstr.stock.id as stockid",
          1 => "mstr.stock.namabarang as namabarang",
          2 => "pb.orderpembelian.tglorder as tglorder",
          3 => "pb.orderpembeliandetail.qtyorder as qtyorder",
          4 => "pb.orderpembelian.noorder as noorder",
        );
        $pembelian = OrderPembelianDetail::select($columns)->join('pb.orderpembelian', 'pb.orderpembelian.id', '=', 'pb.orderpembeliandetail.orderpembelianid')->join('mstr.stock', 'pb.orderpembeliandetail.stockid', '=', 'mstr.stock.id');
        $pembelian->where("pb.orderpembelian.recordownerid",$request->session()->get('subcabang'))->where("pb.orderpembelian.tglorder",'>=',Carbon::parse($tglmulai))->where("pb.orderpembelian.tglorder",'<=',Carbon::parse($tglselesai));
        $pembelian->where("mstr.stock.id",'=',$data[$i]->stockid);
        $pembelian->groupBy("mstr.stock.id","mstr.stock.namabarang","pb.orderpembelian.tglorder","pb.orderpembeliandetail.qtyorder","pb.orderpembelian.noorder");
        $pembelian->orderBy("pb.orderpembelian.noorder","desc")->limit(1);
        $data     =$pembelian->get()->toArray();
        //dd($data[$i]->stockid);
        $jumlah   =count($data);
        if($jumlah > 0)
        {
          return response()->json([
            'success'    => true,
            'namabarang' =>$data[$i]['namabarang'],
            'tglorder'   =>date('d-m-Y',strtotime($data[$i]['tglorder'])),
            'qtyorder'   =>$data[$i]['qtyorder'],
            'noorder'    =>$data[$i]['noorder']
            ]);
        }
        else{
          return response()->json([
            'nocheck'    => true
          ]);
        } 
      }
    }

    public function simpanStokTipis(Request $request){
      $idstock     = $request->datadetail;
      $maxdetail   = 50;
      $totaldetail = count($request->datadetail);
      $headercount = ceil($totaldetail/$maxdetail);
      $datastatus  = '';
      
      $data    = [];
      foreach ($idstock as $key => $val) {
        $data[$key] = json_decode($val);
      }

      $result = '';
      $tglorder = date('Y-m-d');
      for ($x=0; $x < $headercount; $x++) {
        $dataheader = [
          'recordownerid' => $request->session()->get('subcabang'),
          'tglorder'      => $tglorder,
          'noorder'       => $this->getNextOrderNo($request->session()->get('subcabang')),
          'supplierid'    => $request->supplierid,
          'keterangan'    => strtoupper($request->keterangan),
          'createdby'     => strtoupper($request->user()->username),
          'lastupdatedby' => strtoupper($request->user()->username)
        ];
        $order = OrderPembelian::create($dataheader);
        $result = (($result) ? ', ':'').'Order Nomor '.$order->noorder.' tanggal '.date('d-m-Y',strtotime($tglorder));

        $start = $x * $maxdetail;
        $end   = (($start + $maxdetail) < $totaldetail)? ($start + $maxdetail) : $totaldetail;

        for ($i=$start; $i < $end; $i++) {
            $detail = [
              'orderpembelianid' => $order->id,
              'stockid'          => $data[$i]->stockid,
              'qtyorder'         => $data[$i]->qtyorder,
              'qtypenjualanbo'   => $data[$i]->qtybo,
              'qtyrataratajual'  => $data[$i]->rataratajual,
              'qtystokakhir'     => $data[$i]->qtystokakhir,
              'hrgsatuanbrutto'  => 0,
              'disc1'            => 0,
              'disc2'            => 0,
              'ppn'              => 10,
              'hrgsatuannetto'   => 0,
              'createdby'        => strtoupper($request->user()->username),
              'lastupdatedby'    => strtoupper($request->user()->username),
            ];
            $orderdetail = OrderPembelianDetail::create($detail);
            $penjualan   = OrderPenjualanDetail::where('stockid',$orderdetail->stockid)->update(['orderpembeliandetailid' => 1]);
        }
      }

      return response()->json([
        'result' => $result,
      ]);
    }

    public function bo(Request $request){
      $supplierdefault = Supplier::where([['nama','ilike','KP-SOLO%'],['recordownerid',$request->session()->get('subcabang')]])->first();
      return view('transaksi.orderpembelian.formbo',compact('supplierdefault'));
    }

    public function getDataDetailBo(Request $req)
    {
      $tglmulai     = date("d-m-Y", strtotime("-7 day"));
      $tglakhir     = date("d-m-Y",strtotime("-1 day"));
      // $tglmulai ='01-12-2016';
      // $tglakhir ='12-12-2016';
      $tglstok  ='01-07-2016';
      $columns = array(
        0 => "mstr.stock.id as stockid",
        1 => "mstr.stock.namabarang",
        2 => "mstr.stock.satuan",
        3 => "qtyso as qtyorder",
        4 => "sst.rataratajual",
        5 => "(sst.rataratajual * mstr.stock.stockmin) as qtystockmin",
        6 => "pj.orderpenjualandetail.qtysoacc as qtystokakhir",
      );
      $penjualan = OrderPenjualanDetail::selectRaw(collect($columns)->implode(', '));
      $penjualan->join('pj.orderpenjualan', 'pj.orderpenjualandetail.orderpenjualanid', '=', 'pj.orderpenjualan.id');
      $penjualan->join('mstr.stock', 'pj.orderpenjualandetail.stockid', '=','mstr.stock.id');
      $penjualan->join(DB::raw("(SELECT distinct(stockid) as stockid, rataratajual, tglaktif FROM stk.standarstock ORDER BY tglaktif DESC) as sst"), 'sst.stockid', '=','pj.orderpenjualandetail.stockid');
      $penjualan->where('mstr.stock.statusaktif','=',TRUE);
      $penjualan->where("pj.orderpenjualan.tglpickinglist",'>=',$tglmulai)->where("pj.orderpenjualan.tglpickinglist",'<=',$tglakhir);
      // ->where('sst.tglaktif','=',Carbon::now());
      // ->where('sst.tglaktif','=',Carbon::parse($tglstok));
                                        
      $penjualan->skip($req->start)->take($req->length);
      $data = array();
      foreach ($penjualan->get() as $key => $d) {
        $qtybo = $this->getPenjualanBo($d->stockid,$tglmulai,$tglakhir);
        if($d->qtystockmin-$d->qtyorder+$qtybo > 0 ){
          $temp_detail = [
              'stockid'       => $d->stockid,
              'satuan'        => $d->satuan,
              'namabarang'    => $d->namabarang,
              'qtyorder'      => $d->qtystockmin-$d->qtyorder+$qtybo,
              'rataratajual'  => $d->rataratajual,
              'qtystockmin'   => $d->qtystockmin,
              'qtystokakhir'  => $d->qtystokakhir,
              'qtybo'         => $qtybo,
          ];
          array_push($data, $temp_detail);
        }
      }
      
      $total_data = count($data);

      return response()->json([
        'recordsTotal'       => $total_data,
        'data'               => $data,
      ]);
    }

    public function simpanBo(Request $request){
      $idstock     = $request->datadetail;
      $maxdetail   = 50;
      $totaldetail = count($request->datadetail);
      $headercount = ceil($totaldetail/$maxdetail);
      $datastatus  = '';

      $data    = [];
      foreach ($idstock as $key => $val) {
        $data[$key] = json_decode($val);
      }

      for ($x=0; $x < $headercount; $x++) {
        $dataheader = [
        'recordownerid' => $request->session()->get('subcabang'),
        'tglorder'      => date('Y-m-d'),
        'noorder'       => $this->getNextOrderNo($request->session()->get('subcabang')),
        'supplierid'    => $request->supplierid,
        'keterangan'    => strtoupper($request->keterangan),
        'createdby'     => strtoupper($request->user()->username),
        'lastupdatedby' => strtoupper($request->user()->username)
        ];
        $order = OrderPembelian::create($dataheader);

        $start = $x * $maxdetail;
        $end   = (($start + $maxdetail) < $totaldetail)? ($start + $maxdetail) : $totaldetail;

        for ($i=$start; $i < $end; $i++) {
            $detail = [
            'orderpembelianid' => $order->id,
            'stockid'          => $data[$i]->stockid,
            'qtyorder'         => $data[$i]->qtyorder,
            'qtypenjualanbo'   => $data[$i]->qtybo,
            'qtyrataratajual'  => $data[$i]->rataratajual,
            'qtystokakhir'     => $data[$i]->qtystokakhir,
            'hrgsatuanbrutto'  => 0,
            'disc1'            => 0,
            'disc2'            => 0,
            'ppn'              => 10,
            'hrgsatuannetto'   => 0,
            'createdby'        => strtoupper($request->user()->username),
            'lastupdatedby'    => strtoupper($request->user()->username),
            ];
            $orderdetail= OrderPembelianDetail::create($detail);
            $penjualan = OrderPenjualanDetail::where('stockid',$orderdetail->stockid)->update(['orderpembeliandetailid' => 1]);
        }
        return response()->json([
          'success' => true,
          'message' => 'Order Pembelian dari BO sudah terbentuk di No order '.$order->noorder.'',
        ]);
      }
    }

    public function simpanApproval(Request $request){
      $modulename = 'ORDERPEMBELIAN01';
      $module = ApprovalModule::where('modulename',$modulename)->first();
      $order  = OrderPembelian::find($request->id);
      $cabang = "";

      if($order){
        // Siapin Data
        $tgltransaksi = Carbon::createFromFormat("d-m-Y",$order->tglorder);
        $cabang = SubCabang::find($order->recordownerid)->kodesubcabang;

        // Data
        $orderdata[] = [
          'Tgl. Order'    => $order->tglorder,
          'No. Order'     => $order->noorder,
          'Nama Supplier' => $order->supplier->nama,
          'Di Ajukan Oleh'=> auth()->user()->name,
        ];

        $orderdetaildata = [];
        foreach ($order->details as $detail) {
          $orderdetaildata[] = [
            'Kode Barang'     => $detail->barang->kodebarang,
            'Nama Barang'     => $detail->barang->namabarang,
            'Satuan'          => $detail->barang->satuan,
            'Qty. Order'      => $detail->qtyorder,
            'Hrg. Sat. Netto' => number_format($detail->hrgsatuannetto,2,',','.'),
            'Hrg. Total'      => number_format($detail->qtyorder*$detail->hrgsatuannetto,0,',','.'),
          ];
        }

        $header = ["header"=>"Data Order Pembelian","detail"=>"Daftar Barang Order Pembelian"];
        $detail = ["header"=>$orderdata,"detail"=>$orderdetaildata];

        // Tembak Gaaannnn!!!
        $approval = new ApprovalManagement;
        $approval->recordownerid       = $request->session()->get('subcabang');
        $approval->moduleid            = $module->id;
        // $approval->tgltransaksi        = $tgltransaksi;
        $approval->keterangan          = 'PENGAJUAN ACC ORDER PEMBELIAN';
        $approval->datareportingheader = json_encode($header);
        $approval->datareportingdetail = json_encode($detail);
        $approval->closed              = 0;
        $approval->createdby           = strtoupper($request->user()->username);
        $approval->lastupdatedby       = strtoupper($request->user()->username);
        $approval->save();

        $approvaldetail = new ApprovalManagementDetail;
        $approvaldetail->approvalmgmtid = $approval->id;
        $approvaldetail->status         = "PENGAJUAN";
        $approvaldetail->username       = strtoupper($request->user()->username);
        $approvaldetail->keterangan     = 'PENGAJUAN ACC ORDER PEMBELIAN';
        $approvaldetail->createdby      = strtoupper($request->user()->username);
        $approvaldetail->lastupdatedby  = strtoupper($request->user()->username);
        $approvaldetail->save();

        // Simpan Approval id ke order
        $order  = OrderPembelian::find($request->id);
        // $order->timestamps = false;
        $order->approvalmgmtid = $approval->id;
        $order->save();

        // Bikin Daftar Email
        $email_list = ApprovalModule::join('secure.approvalgroup','secure.approvalgroup.approvalmoduleid','=','secure.approvalmgmtmodule.id');
        $email_list->join('secure.roleuser','secure.roleuser.role_id','=','secure.approvalgroup.rolesid');
        $email_list->join('secure.users','secure.roleuser.user_id','=','secure.users.id');
        $email_list->where('modulename',$modulename);
        $email_to = $email_list->get(['email','name','username']);

        // Isi Email
        $email_title = $module->emailsubject;
        $email_body  = $module->emailbody;

        $email_title   = str_replace("#KODESUBCABANG", $cabang, $email_title);
        $email_url = url("/") . "/approvalmgmt/email?id=" . encrypt($approval->id) . "&modulename=ORDERPEMBELIAN01&username=";

        // Isi Detil Data
        $html = '<br/><br/>';
        $html .= 'Pengajuan order pembelian dari cabang '.$cabang.' dengan detail sebagai berikut : <br>';
        foreach($header as $k=>$h) {
          // Get Field
          if(count($detail[$k])) {
            $field = array_keys($detail[$k][0]);
          }else{
            $field = [];
          }
          $html .= '<h4>'.$h.'</h4>';
          $html .= '<div class="table-responsive">';
          $html .= '    <table id="tabel'.$k.'" class="table table-bordered table-striped" width="100%" cellspacing="0" border="1">';
          $html .= '        <thead>';
          $html .= '            <tr>';
          if($field) {
            foreach ($field as $f) {
              $html .= '<th>'.$f.'</th>';
            }
          }else{
              $html .= '<th>Data Kosong</th>';
          }
          $html .= '            </tr>';
          $html .= '        </thead>';
          $html .= '        <tbody>';
          if($field) {
            foreach($detail[$k] as $d) {
            $html .= '            <tr>';
              foreach ($field as $f) {
                $html .= '<td>'.$d[$f].'</td>';
              }
            $html .= '            </tr>';
            };
          }else{
            $html .= '<td>Data Kosong</td>';
          }
          $html .= '        </tbody>';
          $html .= '    </table>';
          $html .= '</div>';
        }
        $html .= '<br><a class="btn btn-success" target="_blank" href="#URLACC">ACC</a> | <a class="btn btn-danger" target="_blank" href="#URLTOLAK">TOLAK</a>';
        $html .= '<br><br>Atas perhatiannya kami ucapkan terima kasih.';
        $html .= '<br><i>Email ini merupakan email otomatis yang dikirim oleh program WISER</i>';
        $html .= '<br><i>Mohon untuk tidak mereply email ini, karena account email ini tidak dimonitor</i>';
        $html .= '<br><i>Jika ingin mereply email ini, mohon untuk ditujukan kepada Tim IT palur yaitu okytss1101@gmail.com atau didik.swn@gmail.com</i>';
        $html .= '<br><br>['.Carbon::now()->toDateTimeString().'] -';
        $email_body    = str_replace("#DETILDATA", $html, $email_body);

        //emailpjl, emailptg, emailacc, emailkasir, emailstok
        //ambil dari appsetting
        // $email_cc = ["laksanayulisuroto@gmail.com","jokopurwo97@mail.ugm.ac.id"];
        $email_cc_array = AppSetting::select("value")
          ->where("keyid", "like", "email%")
          ->get()->toArray();

        $email_cc = [];

        foreach ($email_cc_array as $cc) {
          array_push($email_cc, $cc["value"]);
        }

        // Kirim Email
        foreach($email_to as $email) {
          if($email->email) {
            // Sesuaikan Nama User
            $email_receipt = $email->email;
            $email_body    = str_replace("#NAMAUSER", $email->name, $email_body);
            $email_body    = str_replace("#URLACC", $email_url . $email->username . "&tipe=acc", $email_body);
            $email_body    = str_replace("#URLTOLAK", $email_url . $email->username . "&tipe=tolak", $email_body);

            Mail::send('approvalmgmt.email',['email_title'=>$email_title,'email_body'=>$email_body], function ($message) use ($email_title, $email_receipt, $email_cc)
            {
              $message->from('tss.palur.sas@gmail.com', 'Team IT SAS');
              $message->to($email_receipt);
              $message->cc($email_cc);
              $message->subject($email_title);
            });
          }
        }

        // Pulang
        return response()->json(['success' => true]);
      }else{
        return response()->json(['success' => false]);
      }
    }

    public function sync11(Request $request){
      $idpembelians = $request->data;
      foreach ($idpembelians as $id) {
        $order = OrderPembelian::find($id);
        $header = [
          'id'                   => $id,
          'recordownerid'        => $order->recordownerid,
          'recordownersubcabang' => $order->subcabang->kodesubcabang,
          'tglorder'             => Carbon::createFromFormat('d-m-Y',$order->tglorder),
          'noorder'              => $order->noorder,
          'supplierid'           => $order->supplierid,
          'tempo'                => $order->tempo,
          'keterangan'           => $order->keterangan,
          'approvalmgmtid'       => $order->approvalmgmtid,
          'isarowid'             => $order->isarowid,
          'createdby'            => $order->createdby,
          'createdon'            => Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($order->createdon)->toDateTimeString()),
          'lastupdatedby'        => $order->lastupdatedby,
          'lastupdatedon'        => Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($order->lastupdatedon)->toDateTimeString()),
        ];
        StagingOrderPembelian::create($header);

        foreach ($order->details as $value) {
          $detail = [
            'id'               => $value->id,
            'orderpembelianid' => $value->orderpembelianid,
            'kodebarang'       => $value->barang->kodebarang,
            'qtyorder'         => $value->qtyorder,
            'qtypenjualanbo'   => $value->qtypenjualanbo,
            'qtyrataratajual'  => $value->qtyrataratajual,
            'qtystokakhir'     => $value->qtystokakhir,
            'hrgsatuanbrutto'  => $value->hrgsatuanbrutto,
            'disc1'            => $value->disc1,
            'disc2'            => $value->disc2,
            'ppn'              => $value->ppn,
            'hrgsatuannetto'   => $value->hrgsatuannetto,
            'keterangan'       => $value->keterangan,
            'isarowid'         => $value->isarowid,
            'createdby'        => $value->createdby,
            'createdon'        => Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($value->createdon)->toDateTimeString()),
            'lastupdatedby'    => $value->lastupdatedby,
            'lastupdatedon'    => Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($value->lastupdatedon)->toDateTimeString()),
          ];
          StagingOrderPembelianDetail::create($detail);
        }
        $order->sync11 = date('Y-m-d H:i:s');
        $order->save();
      }
      return response()->json(['success' => true]);
    }

    public function cetakOhPdf(Request $req)
    {
      $config = [
        'mode'              => '',
        // 'format'            => [210,330],
        'format'            => [139.7,215.9],
        'default_font_size' => '8',
        'default_font'      => 'sans-serif',
        'margin_left'       => 8,
        'margin_right'      => 8,
        'margin_top'        => 32,
        'margin_bottom'     => 15,
        'margin_header'     => 5,
        'margin_footer'     => 4,
        'orientation'       => 'L',
        'title'             => 'CETAK ORDER HARIAN',
        'author'            => '',
        'display_mode'      => 'fullpage',
      ];

      $order   = OrderPembelian::find($req->id);
      // if(!$order->tglprintpickinglist){
      //   $order->tglprintpickinglist = Carbon::now();
      // }
      // $order->print = ($order->print)+1;
      // $order->save();

      // Detail
      $details = OrderPembelianDetail::where('orderpembelianid',$req->id)->get()->load(['barang' => function($query) {
        $query->orderBy('kodebarang', 'asc');
      }]);

      foreach ($details as $row) {
        $standarstock = $row->barang->standarstock()->where('recordownerid',$req->session()->get('subcabang'))->orderBy('tglaktif','desc')->first();
        $row->rataratajual = ($standarstock) ? $standarstock->rataratajual : 0;
      }

      $pdf   = PDF::loadView('transaksi.orderpembelian.cetakohpdf',['user'=>$req->user(),'order'=>$order,'details'=>$details],[],$config);
      return $pdf->stream('CetakOrderHarian-'.$req->id.'.pdf');
    }

    public function cetakOhExcel(Request $req)
    {
      $order   = OrderPembelian::find($req->id);
      // if(!$order->tglprintpickinglist){
      //   $order->tglprintpickinglist = Carbon::now();
      // }
      // $order->print = ($order->print)+1;
      // $order->save();

      // Detail
      $details = OrderPembelianDetail::where('orderpembelianid',$req->id)->get()->load(['barang' => function($query) {
        $query->orderBy('kodebarang', 'asc');
      }]);

      foreach ($details as $row) {
        $standarstock = $row->barang->standarstock()->where('recordownerid',$req->session()->get('subcabang'))->orderBy('tglaktif','desc')->first();
        $row->rataratajual = ($standarstock) ? $standarstock->rataratajual : 0;
      }

      // Xlsx Var
      $sheet    = str_slug("Laporan Order Harian");
      $file     = $sheet."-".uniqid().".xlsx";
      $folder   = storage_path('excel/exports');
      $noorder  = $order->noorder;
      $username = strtoupper(auth()->user()->username);
      $tanggal  = Carbon::now()->format('d/m/Y');

      $header_format = ['GENERAL'];
      $header_style  = [
        'font-style' =>'bold',
        'fill'       =>'#dbe5f1',
        'halign'     =>'center',
        'border'     =>'left,right,top,bottom',
      ];
      $data_style = [
        'border' =>'left,right,top,bottom',
      ];

      $header_data = [
        "No",
        "Nama Stok",
        "Sat",
        "Kode Barang",
        "BO",
        "Keb Stok",
        "Total",
        "Stok Akhir",
        "Rata-rata Jual",
        "Qty. Di+",
      ];

      $data_format = [
          'GENERAL','GENERAL','GENERAL','GENERAL','#,##0','#,##0','#,##0','#,##0','#,##0','#,##0',
      ];

      $col_width = [
          5,90,8,20,8,10,10,10,15,10
      ];

      // Init Xlsx
      $writer = new XLSXWriter();
      $writer->setAuthor($username);
      $writer->setTempDir($folder);

      // Write Sheet Header
      $writer->writeSheetHeader($sheet,$header_format,['suppress_row'=>true,'widths'=>$col_width]);
      $writer->writeSheetRow($sheet,["ORDER HARIAN"],['font-style'=>'bold']);
      $writer->writeSheetRow($sheet,["Tanggal : $tanggal"],['font-style'=>'bold']);
      $writer->writeSheetRow($sheet,["No Request : $noorder"],['font-style'=>'bold']);
      $writer->writeSheetRow($sheet,['']);

      // Write Table Header
      $writer->writeSheetRow($sheet,$header_data,$header_style);

      // Write Table Data
      $i = 1;
      foreach ($details as $row) {
          $value = [
            $i++,
            $row->barang->namabarang,
            $row->barang->satuan,
            $row->barang->kodebarang,
            $row->qtypenjualanbo,
            $row->qtyorder,
            $row->qtypenjualanbo+$row->qtyorder,
            $row->qtystokakhir,
            $row->rataratajual,
            "",
          ];

          $writer->writeSheetRow($sheet,$value,$data_style,$data_format);
      }

      // Footer
      $writer->writeSheetRow($sheet,['']);
      $writer->writeSheetRow($sheet,['']);
      $writer->writeSheetRow($sheet,[$username.",".(Carbon::now()->format('d/m/Y h:i:s'))],['font-style'=>'italic']);

      // return if write to file complete
      $writer->writeToFile($folder.'/'.$file);
      return asset('storage/excel/exports/'.$file);
    }

    public function cekBarang(Request $req)
    {
      $opjd = OrderPembelianDetail::select('id')->where('orderpembelianid',$req->id)->where('stockid',$req->stockid)->first();
      if($opjd){
        if($req->orderiddetail == $opjd->id){
          return response()->json(false);
        }else{
          return response()->json(true);
        }
      }else{
        return response()->json(false);
      }
    }
}
