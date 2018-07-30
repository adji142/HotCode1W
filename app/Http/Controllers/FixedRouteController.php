<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\StockScrab;
use App\Models\StockScrabDetail;
use App\Models\SubCabang;
use App\Models\Karyawan;
use App\Models\Barang;
use App\Models\Mutasi;
use App\Models\MutasiDetail;
use App\Models\ApprovalManagement;
use App\Models\ApprovalManagementDetail;
use App\Models\ApprovalModule;
use App\Models\Toko;
use App\Models\Tokohakakses;
use App\Models\Kunjungan;
use App\Models\RealisasiKunjungan;
use EXCEL;
use PDF;
use DB;

class FixedRouteController extends Controller
{
    private $bulan = array('01' => 'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL','05'=>'MEI','06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS','09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER');
    protected $original_column = array(
      1 => "hr.karyawan.kodesales",
      2 => "hr.karyawan.namakaryawan"
    );
    public function index()
    {
     $bulanselected     = date('m');
     $realisasi         = RealisasiKunjungan::all();
     return view('transaksi.fixedroute.index',['bulan'=>$this->bulan,'bulanselected'=>$bulanselected,'realisasi'=>$realisasi]);
    }

    public function getDataHeader(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('fixed.index')) {
            return response()->json([
                'draw'            => $req->draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
            ]);
        }

        // jika lolos, tampilkan data
        $req->session()->put('bulan', $req->bulan);
        
        $filter_count = 0;
        $empty_filter = 0;

        $columns = array(
            0 => "hr.karyawan.kodesales",
            1 => "hr.karyawan.namakaryawan",
            2 => "hr.karyawan.id",
            3 => "hr.karyawan.nikhrd",
            4=>  "hr.karyawan.lastupdatedby",
            5=>  "hr.karyawan.lastupdatedon",
           
      );
      for ($i=1; $i < 3; $i++) {
        if(empty($req->custom_search[$i]['text'])){
          $empty_filter++;
        }
      }
      $fixed = Karyawan::select($columns);
      $fixed->where("hr.karyawan.kodesales",'!=',NULL)->where("hr.karyawan.tglkeluar",'=',NULL);
      $fixed->where("hr.karyawan.recordownerid",'=',$req->session()->get('subcabang'));
   
      $total_data = $fixed->count();
      if($empty_filter < 3){
        for ($i=1; $i < 3; $i++) {
          if ($req->custom_search[$i]['text']!='') {
            $index = $i;
            if($index== 2){
              if($req->custom_search[$i]['filter'] == '='){
                $fixed->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
              }else{
                $fixed->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
              }
            }else{
              $fixed->where($this->original_column[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
            }
            $filter_count++;
          }
        }
      }
      if($filter_count > 0){
        $filtered_data = $fixed->count();
        $fixed->skip($req->start)->take($req->length);
      }else{
        $filtered_data = $total_data;
        $fixed->skip($req->start)->take($req->length);
      }
      if ($req->tipe_edit) {
        $fixed->orderBy('hr.karyawan.lastupdatedon','desc');
      }else {
        if(array_key_exists($req->order[0]['column'], $this->original_column)){
          $fixed->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
        }
      }
      $data = array();
      foreach ($fixed->get() as $key => $sc) {
        $sc->lastupdatedon          = $sc->lastupdatedon;
        $data[$key]                 = $sc->toArray();
        $data[$key]['DT_RowId']     = 'gv1_'.$sc->id;
        $data[$key]['pilihtoko']    = 'pilihtoko';
        $data[$key]['luarrencana']  = 'luarrencana';
      }

      return response()->json([
        'draw'            => $req->draw,
        'recordsTotal'    => $total_data,
        'recordsFiltered' => $filtered_data,
        'data'            => $data,
        ]);
    }

    public function getDataDetail(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('fixed.index')) {
            return response()->json([
                'node' => $node,
            ]);
        }

        // jika lolos, tampilkan data
      $year       = date('Y');
      // $fixeddetail=Toko::select(["namatoko","customwilayah","kota","mstr.toko.id as tokooid","namakaryawan"])->join('mstr.tokohakakses', 'mstr.toko.id', '=', 'mstr.tokohakakses.tokoid');
      // $fixeddetail->join('hr.karyawan', 'hr.karyawan.id', '=', 'mstr.tokohakakses.karyawanidsalesman');
      // $fixeddetail->where("hr.karyawan.id",'=',$req->id);
      // $fixeddetail=Toko::select(["namatoko","customwilayah","kota","mstr.toko.id as tokooid","namakaryawan",'mstr.tokohakakses.tglaktif']);
      $fixeddetail=Toko::select(["namatoko","customwilayah","kota","mstr.toko.id as tokooid","namakaryawan"]);
      $fixeddetail->join('pj.kunjungansales', 'pj.kunjungansales.tokoid', '=', 'mstr.toko.id');
      $fixeddetail->join('hr.karyawan', 'hr.karyawan.id', '=', 'pj.kunjungansales.karyawanidsalesman');
      //$fixeddetail->leftJoin('mstr.tokohakakses', 'mstr.toko.id', '=', 'mstr.tokohakakses.tokoid');
      //$fixeddetail->where("mstr.tokohakakses.recordownerid",'=',$req->session()->get('subcabang'));
      $fixeddetail->where("hr.karyawan.id",'=',$req->id);
      $fixeddetail->whereMonth('pj.kunjungansales.tglkunjung', '=', $req->bulan);
      $fixeddetail->whereYear('pj.kunjungansales.tglkunjung', '=', $year);
      //$fixeddetail->orderBy("mstr.tokohakakses.tglaktif",'DESC');
      // $fixeddetail->groupBy("namatoko","customwilayah","kota","mstr.toko.id","namakaryawan","mstr.tokohakakses.tglaktif");
      $fixeddetail->groupBy("namatoko","customwilayah","kota","mstr.toko.id","namakaryawan");

      $data=$fixeddetail->get();
      //dd(count($data));
      $node = array();
      foreach ($data as $detail) {
        $temp = [
          0 => $detail->namatoko,
          1 => $detail->customwilayah,
          2 => $detail->kota,
          3 => $detail->tokooid,
          9 => $req->id,
        ];
        // $kunjungansales = Kunjungan::select('tglkunjung')->where('tokoid',$detail->tokooid)->whereMonth('pj.kunjungansales.tglkunjung', '=', $req->bulan)->whereYear('pj.kunjungansales.tglkunjung', '=', $year)->where('karyawanidsalesman',$req->id)->get()->toArray();
        $kunjungansales = Kunjungan::select('tglkunjung')->where('tokoid',$detail->tokooid)->whereMonth('pj.kunjungansales.tglkunjung', '=', $req->bulan)->whereYear('pj.kunjungansales.tglkunjung', '=', $year)->get()->toArray();
        for($i=0;$i<=4;$i++) {
          $temp[$i+4] = isset($kunjungansales[$i]['tglkunjung']) ? $kunjungansales[$i]['tglkunjung'] : '';
        }

        array_push($node, $temp);
      }
      //dd($node);
      return response()->json([
        'node' => $node,
      ]);
    }

    public function getDataDetailKunjungan(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('fixed.index')) {
            return response()->json([
                'node' => $node,
            ]);
        }

        // jika lolos, tampilkan data
      $year      = date('Y');
      // $fixeddetail=Toko::select(["pj.kunjungansales.createdon as tglinput","tglkunjung","namatoko","customwilayah","kota","keteranganrealisasi","keterangantambahan","omsetavg","saldopiutang","mstr.toko.id as tokooid","pj.kunjungansales.id as ksid"])->join('mstr.tokohakakses', 'mstr.toko.id', '=', 'mstr.tokohakakses.tokoid');
      // $fixeddetail->join('hr.karyawan', 'hr.karyawan.id', '=', 'mstr.tokohakakses.karyawanidsalesman');
      // $fixeddetail->join('pj.kunjungansales', 'pj.kunjungansales.tokoid', '=', 'mstr.tokohakakses.tokoid');
      // $fixeddetail->where("hr.karyawan.id",'=',$req->id);

      $fixeddetail=Toko::select(["pj.kunjungansales.createdon as tglinput","tglkunjung","namatoko","customwilayah","kota","keteranganrealisasi","keterangantambahan","omsetavg","saldopiutang","mstr.toko.id as tokooid","pj.kunjungansales.id as ksid","tagihandetailid"])->join('pj.kunjungansales', 'pj.kunjungansales.tokoid', '=', 'mstr.toko.id');
      $fixeddetail->join('hr.karyawan', 'hr.karyawan.id', '=', 'pj.kunjungansales.karyawanidsalesman');
      $fixeddetail->where("hr.karyawan.id",'=',$req->id);

      if($req->bulan) {
        $fixeddetail->whereMonth('pj.kunjungansales.tglkunjung', '=', $req->bulan);
      }
      $fixeddetail->whereYear('pj.kunjungansales.tglkunjung', '=', $year)->where('pj.kunjungansales.karyawanidsalesman',$req->id);
      // dd($fixeddetail->toSql());
      $data=$fixeddetail->get();

      $node = array();
      foreach ($data as $detail) {
        if ($detail->tagihandetailid != null) {
           $delete = 'Fixed Route sudah dibuat Register,tidak bisa dihapus Fixed Route.';
           $edit   = 'Fixed Route sudah dibuat Register,tidak bisa edit Fixed Route.';
        }else {
          if(Carbon::parse($detail->tglkunjung)->toDateString() <= Carbon::now()->toDateString()){
             $delete = 'Tidak bisa hapus record. Tanggal kunjungan sudah lewat tanggal.';
             $edit   = 'editdisable';
          }else {
            $delete  = 'delete';
            $edit    = 'edit';
          } 
       }
      
        $temp = [
          0 => date('d-m-Y',strtotime($detail->tglinput)),
          1 => date('d-m-Y',strtotime($detail->tglkunjung)),
          2 => $detail->namatoko,
          3 => $detail->customwilayah,
          4 => $detail->kota,
          5 => $detail->keteranganrealisasi,
          6 => $detail->keterangantambahan,
          7 => $detail->omsetavg,
          8 => $detail->saldopiutang,
          9 => $detail->tokooid,
          10=> $detail->ksid,
          11=> $delete,
          12=> $edit,
        ];
        array_push($node, $temp);
      }
      return response()->json([
        'node' => $node,
      ]);
    }
    
    public function getJumlahKunjungan(Request $req)
    {
      $year      = date('Y');
      // $fixeddetail =Toko::select(["pj.kunjungansales.createdon as tglinput","tglkunjung","namatoko","customwilayah","kota","keteranganrealisasi","keterangantambahan","omsetavg","saldopiutang","mstr.toko.id as tokooid","pj.kunjungansales.id as ksid","namakaryawan",'kodesales']):
      // $fixeddetail->join('mstr.tokohakakses', 'mstr.toko.id', '=', 'mstr.tokohakakses.tokoid');
      // $fixeddetail->join('hr.karyawan', 'hr.karyawan.id', '=', 'mstr.tokohakakses.karyawanidsalesman');
      // $fixeddetail->join('pj.kunjungansales', 'pj.kunjungansales.karyawanidsalesman', '=', 'hr.karyawan.id');
      // $fixeddetail->where("mstr.toko.id",'=',$req->id);
      // $fixeddetail->whereMonth('pj.kunjungansales.tglkunjung', '=', $req->bulan);
      // $fixeddetail->whereYear('pj.kunjungansales.tglkunjung', '=', $year);
      $fixeddetail =Toko::select(["pj.kunjungansales.createdon as tglinput","tglkunjung","namatoko","customwilayah","kota","keteranganrealisasi","keterangantambahan","omsetavg","saldopiutang","mstr.toko.id as tokooid","pj.kunjungansales.id as ksid","namakaryawan",'kodesales']);
      $fixeddetail->join('pj.kunjungansales', 'mstr.toko.id', '=', 'pj.kunjungansales.tokoid');
      $fixeddetail->join('hr.karyawan', 'hr.karyawan.id', '=', 'pj.kunjungansales.karyawanidsalesman');
      $fixeddetail->where("mstr.toko.id",'=',$req->id);
      $fixeddetail->whereMonth('pj.kunjungansales.tglkunjung', '=', $req->bulan);
      $fixeddetail->whereYear('pj.kunjungansales.tglkunjung', '=', $year);
      $data       = $fixeddetail->get();
      $jumlahdata = count($data);


      $karyawan    =Karyawan::select(["namakaryawan"]);
      $karyawan->join('pj.kunjungansales', 'pj.kunjungansales.karyawanidsalesman', '=', 'hr.karyawan.id');
      $karyawan->join('mstr.toko', 'mstr.toko.id', '=', 'pj.kunjungansales.tokoid');
      $karyawan->where("mstr.toko.id",'=',$req->id);
      $karyawan->whereMonth('pj.kunjungansales.tglkunjung', '=', $req->bulan);
      $karyawan->whereYear('pj.kunjungansales.tglkunjung', '=', $year);
      $karyawan->groupBy('hr.karyawan.namakaryawan');
      $datakaryawan=$karyawan->get();
      $jumlahdk    =count($datakaryawan);
      $k=array();
        for ($i=0 ; $i < $jumlahdk  ; $i++ ) { 
           if ($jumlahdk-1 == $i) {
           array_push($k, $datakaryawan[$i]->namakaryawan);
          }
          else{
          array_push($k,$datakaryawan[$i]->namakaryawan.',');
          }
        }
      if ($jumlahdata == '5') {
          return response()->json([
            'success' => true,
            'message' => 'Toko sudah dikunjungi 5 kali oleh sales '.implode("",$k).'',
          ]);  
      } 
      else{
        return response()->json([
              'success' => false,
              'message'     => 'Buka form FRinput Tanggal dengan mengambil parameter sebelumnya',
        ]);  
      }
    }

    public function formFr(Request $req, $id=null,$idkaryawan=null,$bulan=null)
    {
      $year      = date('Y');
      if($id){
        $toko        = Toko::find($req->id);
        $karyawan    = Karyawan::find($req->idkaryawan);
        $fixeddetail = Kunjungan::where('tokoid',$req->id);
        $fixeddetail->whereMonth('tglkunjung', '=', $req->bulan);
        $fixeddetail->whereYear('pj.kunjungansales.tglkunjung', '=', $year);
        $data          = $fixeddetail->get();
      }else{
        $toko     = $id;
        $karyawan = $idkaryawan;
      }
      $realisasi = RealisasiKunjungan::all();
      return view('transaksi.fixedroute.frform',compact('toko','karyawan','data','realisasi'));
    }
    
    public function frUpdatePerToko(Request $req, $id=null,$idkaryawan=null,$bulan=null)
    {
      $year      = date('Y');
      if($id){
        $toko          = Toko::find($req->id);
        $karyawan      = Karyawan::find($req->idkaryawan);
        $fixeddetail   = Kunjungan::where('tokoid',$req->id)->where('karyawanidsalesman',$req->idkaryawan);
        $fixeddetail->whereMonth('tglkunjung', '=', $req->bulan);
        $fixeddetail->whereYear('tglkunjung','=',$year);
        $data          = $fixeddetail->get();
        $bulan         = $bulan;
      }else{
        $toko          = $id;
        $karyawan      = $kodesales;
        $bulan         = $bulan;
      }
      $realisasi = RealisasiKunjungan::all();
      return view('transaksi.fixedroute.formupdatepertoko',compact('toko','karyawan','data','realisasi','bulan'));
    }
    
    public function cekEdit(Request $req){
      $idtoko     = $req->idtoko;
      $idkaryawan = $req->idkaryawan;
      $bulan      = $req->bulan;
      $year       = date('Y');
      $realisasi = RealisasiKunjungan::all();
      $fixeddetail   = Kunjungan::where('tokoid',$idtoko)->where('karyawanidsalesman',$idkaryawan);
      $fixeddetail->whereMonth('createdon', '=', $bulan);
      $fixeddetail->whereYear('tglkunjung','=',$year);
      $node = array();
      foreach ($fixeddetail->get() as $detail) {
        $temp = [
          0 => $detail->id,
          1 => $detail->tglkunjung,
          2 => $detail->keteranganrealisasi,
          3 => $detail->keterangantambahan,
        ];
        array_push($node, $temp);
      }
      // foreach ($fixeddetail->get() as $k => $val) {
      //   $v=$k+1;
      //   $data[$k]   = $val->toArray();
      //   $action      = "";
      //   if(Carbon::parse($val->tglkunjung)->toDateString() > Carbon::now()->toDateString()){
      //     $action    ="<div class='row'>
      //                 <div class='col-md-4'>
      //                   <div class='col-md-3 col-sm-3 col-xs-12'>
      //                     <div class='checkbox checkboxaction' style='text-align: right'>
      //                       <input type='checkbox' class='flat' value='".$val->id."'>
      //                     </div>
      //                       <input type='hidden' id='idkunjungan' name='idkunjungan[]' class='form-control' value='".$val->id."' tabindex='-1'>
      //                   </div>
      //                     <label class='control-label col-md-3 col-sm-3  col-xs-12'>Kunjungan ".$v."</label>
      //                 </div>
      //                 <div class='col-md-6'>
      //                  <div class='form-group'>
      //                   <label class='control-label col-md-3 col-sm-3 col-xs-12' for='tglkunjungan'>Tanggal Kunjungan : </label>
      //                   <div class='col-md-7 col-sm-7 col-xs-12'>
      //                     <input type='text' id='tglkunjung' name='tglkunjung[]' class='tgl form-control' placeholder='Tgl. Kunjung'  data-inputmask=''mask': 'd-m-y'' value='".$val->tglkunjung."'>
      //                   </div>
      //                 </div>
      //                  <div class='form-group'>
      //                   <label class='control-label col-md-3 col-sm-3 col-xs-12' for='realisasi'>Realisasi : </label>
      //                   <div class='col-md-7 col-sm-7 col-xs-12'>
                           
      //                   </div>
      //                 </div>
      //                 <div class='form-group'>
      //                   <label class='control-label col-md-3 col-sm-3 col-xs-12' for='kettambahan'>Keterangan Tambahan :</label>
      //                   <div class='col-md-7 col-sm-7 col-xs-12'>
      //                     <input type='text' id='kettambahan' name='kettambahan[]' class='form-control' value='".$val->keterangantambahan."' tabindex='-1' readonly='>
      //                   </div>
      //                 </div>
      //             </div>
      //               </div>";
      //   }else {
      //     $action    ="<div class='row'>
      //                 <div class='col-md-4'>
      //                   <div class='col-md-3 col-sm-3 col-xs-12'>
      //                     <div class='checkbox checkboxaction' style='text-align: right'>
      //                       <input type='checkbox' class='flat' value='".$val->id."'>
      //                     </div>
      //                       <input type='hidden' id='idkunjungan' name='idkunjungan[]' class='form-control' value='".$val->id."' tabindex='-1'>
      //                   </div>
      //                     <label class='control-label col-md-3 col-sm-3  col-xs-12'>Kunjungan ".$v."</label>
      //                 </div>
      //                 <div class='col-md-6'>
      //                  <div class='form-group'>
      //                   <label class='control-label col-md-3 col-sm-3 col-xs-12' for='tglkunjungan'>Tanggal Kunjungan : </label>
      //                   <div class='col-md-7 col-sm-7 col-xs-12'>
      //                     <input type='text' id='tglkunjung' name='tglkunjung[]' class='tgl form-control' placeholder='Tgl. Kunjung'  data-inputmask=''mask': 'd-m-y'' value='".$val->tglkunjung."' readonly=''>
      //                   </div>
      //                 </div>
      //                  <div class='form-group'>
      //                   <label class='control-label col-md-3 col-sm-3 col-xs-12' for='realisasi'>Realisasi : </label>
      //                   <div class='col-md-7 col-sm-7 col-xs-12'>
                           
      //                   </div>
      //                 </div>
      //                 <div class='form-group'>
      //                   <label class='control-label col-md-3 col-sm-3 col-xs-12' for='kettambahan'>Keterangan Tambahan :</label>
      //                   <div class='col-md-7 col-sm-7 col-xs-12'>
      //                     <input type='text' id='kettambahan' name='kettambahan[]' class='form-control' value='".$val->keterangantambahan."' tabindex='-1'>
      //                   </div>
      //                 </div>
      //             </div>
      //               </div>";
      //   }

      //   $data[$k]['action'] = $action;
      // }
      // return response()->json([
      //   'success'         => true,
      //   'data'            => $data,
      // ]);
      return response()->json([
        'success'         => true,
        'node'            => $node,
      ]);

    }
    
    public function CekTanggalKunjungan($tglkunjung,$idtoko){
      $cek = Kunjungan::where('tglkunjung',$tglkunjung)->where('tokoid',$idtoko)->first();
      //dd(count($cek));
      return $cek;
    }
    
    public function CekTanggalKunjunganToko(Request $req){
      $cek = Kunjungan::where('tglkunjung',$req->tglkunjung)->where('tokoid',$req->idtoko)->first();
      if ($cek == null) {
         return response()->json([
          'success' => true,
          'message' => 'Tidak ada kunjungan',
         ]);
      } else {
        return response()->json([
            'toko'      => $cek,
            'namatoko'  => $cek->toko->namatoko,
            'tglkunjung'=> $cek->tglkunjung,
            'sales'     => $cek->karyawan->namakaryawan,

        ]);
      }
    }
    
    public function tambahKunjungan(Request $req){
      foreach($req->tglkunjung as $key=> $data)
       {
         if ($data) {
           //dd($this->CekTanggalKunjungan($req->tglkunjung[$key],$req->idtoko));
           if(Carbon::parse($req->tglkunjung[$key])->toDateString() < Carbon::now()->toDateString()){
               return response()->json([
                'success' => false,
                'message' => 'Tanggal kunjungan harus lebih besar dari hari ini',
              ]);
           }
           // //jika ada tanggal kunjungan sama dengan toko yang dipilih
           else if(count($this->CekTanggalKunjungan($req->tglkunjung[$key],$req->idtoko)) > 0){
               $cek = $this->CekTanggalKunjungan($req->tglkunjung[$key],$req->idtoko);
               return response()->json([
                'success' => false,
                'message' => 'Toko '.$cek->toko->namatoko.' sudah dikunjungi pada tanggal '.date('d-m-Y',strtotime($req->tglkunjung[$key])).' oleh sales '.$cek->karyawan->namakaryawan.', tidak bisa buat fixedroute dengan tanggal kunjungan sama ditoko yang sama.',
              ]);
           }
           else{
              $kunjungan = new Kunjungan;
              $kunjungan->recordownerid      = $req->session()->get('subcabang');
              $kunjungan->tglkunjung         = date('Y-m-d',strtotime($req->tglkunjung[$key]));
              $kunjungan->karyawanidsalesman = $req->idkaryawan;
              $kunjungan->keterangantambahan = strtoupper($req->kettambahan[$key]);
              $kunjungan->keteranganrealisasi       = $req->realisasi[$key];
              $kunjungan->tokoid             = $req->idtoko;
              $kunjungan->rencana            = 'TRUE';
              $kunjungan->createdby          = strtoupper($req->user()->username);
              $kunjungan->lastupdateby       = strtoupper($req->user()->username);
              $kunjungan->save();
          }
         } 
       }       
       return response()->json([
        'success' => true,
        'message' => 'Data Tanggal Kunjungan Berhasil ditambahkan',
      ]);

    }

    public function formPilihToko(Request $req,$tglkunjung=null,$idkaryawan=null)
    {
      $tglkunjung = $req->tglkunjung;
      $idkaryawan = $req->idkaryawan;
      $karyawan   = Karyawan::select('namakaryawan')->find($req->idkaryawan);
      return view('transaksi.fixedroute.form',compact('tglkunjung','idkaryawan','karyawan'));
    }
    
    public function cekRecordKunjungan(Request $req){
        $bulan  = date('m');
        $year   = date('Y');
        $idtoko = $req->idtoko;

        foreach ($idtoko as $id) {
            $cekrecord = Kunjungan::where('tokoid',$id)->where('tglkunjung',$req->tglkunjung);
            $cekrecord->whereMonth('pj.kunjungansales.tglkunjung', '=', $bulan);
            $cekrecord->whereYear('pj.kunjungansales.tglkunjung', '=', $year);
            $data   = $cekrecord->get();
            $jumlah = (count($data));
            $k      = array();

            for ($i=0 ; $i < $jumlah  ; $i++ ) { 
                if ($jumlah-1 == $i) {
                array_push($k, $data[$i]->karyawan->namakaryawan);
                }
                else{
                array_push($k,$data[$i]->karyawan->namakaryawan.',');
                }
            }

            if ($jumlah > 5) {
                return response()->json([
                    'success' => true,
                    'message' => 'Toko sudah dikunjungi 5 kali oleh sales '.implode("",$k).'',
                ]);
            }
            else{
                $kunjungan = new Kunjungan;
                $kunjungan->recordownerid = $req->session()->get('subcabang');
                $kunjungan->tglkunjung    = date('Y-m-d',strtotime($req->tglkunjung));
                $kunjungan->karyawanidsalesman = $req->idkaryawan;
                $kunjungan->tokoid       = $id;
                $kunjungan->rencana      = 'TRUE';
                $kunjungan->createdby    = strtoupper($req->user()->username);
                $kunjungan->lastupdateby = strtoupper($req->user()->username);
                $kunjungan->save();
            }
        }
   }

    public function cekRecordKunjunganLR(Request $req){
      $bulan     = date('m');
      $year      = date('Y');
      $idtoko    = $req->idtoko;
      foreach ($idtoko as $id) {
          $cekrecord = Kunjungan::where('tokoid',$id)->where('tglkunjung',$req->tglkunjung);
          $cekrecord->whereMonth('pj.kunjungansales.tglkunjung', '=', $bulan);
          $cekrecord->whereYear('pj.kunjungansales.tglkunjung', '=', $year);
          $data = $cekrecord->get();
          $jumlah = (count($data));
          $k=array();
          for ($i=0 ; $i < $jumlah  ; $i++ ) { 
             if ($jumlah-1 == $i) {
             array_push($k, $data[$i]->karyawan->namakaryawan);
            }
            else{
            array_push($k,$data[$i]->karyawan->namakaryawan.',');
            }
          }

          if ($jumlah > 5) {
            return response()->json([
                  'success' => true,
                  'message' => 'Toko sudah dikunjungi 5 kali oleh sales '.implode("",$k).'',
            ]);
          }else{
              $kunjungan = new Kunjungan;
              $kunjungan->recordownerid      = $req->session()->get('subcabang');
              $kunjungan->tglkunjung         = date('Y-m-d',strtotime($req->tglkunjung));
              $kunjungan->karyawanidsalesman = $req->idkaryawan;
              $kunjungan->tokoid             = $id;
              $kunjungan->rencana            = 'FALSE';
              $kunjungan->createdby          = strtoupper($req->user()->username);
              $kunjungan->lastupdateby       = strtoupper($req->user()->username);
              $kunjungan->save();
          }
      }
      
   }
   
    public function formLuarRencana(Request $req,$tglkunjung=null,$idkaryawan=null)
    {
      $tglkunjung=$req->tglkunjung;
      $idkaryawan=$req->idkaryawan;
      $karyawan  = Karyawan::select('namakaryawan')->find($req->idkaryawan);
      return view('transaksi.fixedroute.forminputrencana',compact('tglkunjung','idkaryawan','karyawan'));
    }
    
    public function getCekTanggalKunjungan(Request $req)
    {
      $tglkunjung   = Carbon::parse($req->tglkunjung)->toDateString();
      // $fixeddetail  =Toko::select(["namatoko","customwilayah","kota","mstr.toko.id as tokooid","namakaryawan",'tglkunjung'])->join('mstr.tokohakakses', 'mstr.toko.id', '=', 'mstr.tokohakakses.tokoid');
      // $fixeddetail->join('hr.karyawan', 'hr.karyawan.id', '=', 'mstr.tokohakakses.karyawanidsalesman');
      // $fixeddetail->join('pj.kunjungansales','pj.kunjungansales.tokoid','=','mstr.toko.id');
      // $fixeddetail->where("hr.karyawan.id",'=',$req->idkaryawan);
      // $fixeddetail->where("pj.kunjungansales.tglkunjung",'=',$tglkunjung);

      $fixeddetail  =Toko::select(["namatoko","customwilayah","kota","mstr.toko.id as tokooid","namakaryawan",'tglkunjung']);
      $fixeddetail->join('pj.kunjungansales','pj.kunjungansales.tokoid','=','mstr.toko.id');
       $fixeddetail->join('hr.karyawan', 'hr.karyawan.id', '=', 'pj.kunjungansales.karyawanidsalesman');
      $fixeddetail->where("hr.karyawan.id",'=',$req->idkaryawan);
      $fixeddetail->where("pj.kunjungansales.tglkunjung",'=',$tglkunjung);
      $datafixeddetail=$fixeddetail->get();
      $node = array();
      foreach ($datafixeddetail as $detail) {
        $temp = [
          0 => $detail->namatoko,
          1 => date('d-m-Y',strtotime($detail->tglkunjung)),
          2 => $detail->namakaryawan,
        ];
        array_push($node, $temp);
      }
      return response()->json([
        'node' => $node,
      ]);
    }
    
    public function cekTokohakakses(Request $req){
      $fixeddetail=Toko::select(["namatoko","customwilayah","kota","mstr.toko.id as tokooid"])->join('mstr.tokohakakses', 'mstr.toko.id', '=', 'mstr.tokohakakses.tokoid');
      $fixeddetail->join('hr.karyawan', 'hr.karyawan.id', '=', 'mstr.tokohakakses.karyawanidsalesman');
      $fixeddetail->where("hr.karyawan.id",'=',$req->idkaryawan);
      $fixeddetail->where("mstr.toko.id",'=',$req->id);
      $data=$fixeddetail->get();
      $toko = Toko::find($req->id);
      if (count($data) > 0 ){
        return response()->json([
           'success'    => true,
           'toko'       => $toko,
           'idtoko'     => $req->id, 
           'tglkunjung' => $req->tglkunjung,
        ]);
      } else {
        return response()->json([
           'toko'       => $toko,
           'idtoko'     => $req->id, 
           'tglkunjung' => $req->tglkunjung,
           'message' => 'Toko '.$req->namatoko.' bukan toko milik sales '.$req->namakaryawan.', apakah anda yakin membuat fixed route tersebut?',
        ]);
      }
      
      
    }
   
    public function getDetail(Request $req)
    {
      $year      = date('Y');
      $detail    = Toko::find($req->id);
      $tambahan  = Kunjungan::select('omsetavg','saldopiutang')->where('createdon','=',DB::raw('(select max(createdon) from pj.kunjungansales where tokoid = '.$req->id.')'))->first();
      $node = array();
      $temp = [
          0 => $detail->namatoko,
          1 => $detail->alamat,
          2 => $detail->kecamatan,
          3 => $detail->kota,
          4 => $detail->customwilayah,
          5 => $detail->lastupdatedby,
          6 => $detail->lastupdatedon,
          27 =>$tambahan->omsetavg,
          28 =>$tambahan->saldopiutang,
      ];
      // $kunjungansales = Kunjungan::select('tglkunjung','createdon','keteranganrealisasi','keterangantambahan')
      //                             ->where('tokoid',$req->id)
      //                             ->whereMonth('tglkunjung', '=',$req->bulan)
      //                             ->whereYear('tglkunjung','=',$year)
      //                             ->where('karyawanidsalesman',$req->idkaryawan)
      //                             ->get()->toArray();
       $kunjungansales = Kunjungan::select('tglkunjung','createdon','keteranganrealisasi','keterangantambahan')
                                  ->where('tokoid',$req->id)
                                  ->whereMonth('tglkunjung', '=',$req->bulan)
                                  ->whereYear('tglkunjung','=',$year)
                                  ->get()->toArray();

      for($i=0;$i<=4;$i++) {
          $temp[$i+7] = isset($kunjungansales[$i]['createdon']) ? $kunjungansales[$i]['createdon'] : '';
          $temp[$i+12] = isset($kunjungansales[$i]['tglkunjung']) ? $kunjungansales[$i]['tglkunjung'] : '';
          $temp[$i+17] = isset($kunjungansales[$i]['keteranganrealisasi']) ? $kunjungansales[$i]['keteranganrealisasi'] : '';
          $temp[$i+22] = isset($kunjungansales[$i]['keterangantambahan']) ? $kunjungansales[$i]['keterangantambahan'] : '';
      }
      array_push($node, $temp);
      return response()->json([
        'node' => $node,
      ]);
    }
    
    public function getDetailKunjungan(Request $req)
    {
      $datadetail = Kunjungan::find($req->id);
      return response()->json([
        'tglinput'              => $datadetail->createdon,
        'tglkunjung'            => $datadetail->tglkunjung,
        'namatoko'              => $datadetail->toko->namatoko,
        'customwilayah'         => $datadetail->toko->customwilayah,
        'kota'                  => $datadetail->toko->kota,
        'keteranganrealisasi'          => $datadetail->keteranganrealisasi,
        'keterangantambahan'    => $datadetail->keterangantambahan,
        'omsetavg'              => $datadetail->omsetavg,
        'saldopiutang'          => $datadetail->saldopiutang,
        'alamat'                => $datadetail->toko->alamat,
        'kecamatan'             => $datadetail->toko->kecamatan,
        'namasales'             => $datadetail->karyawan->namakaryawan,
        'kodesales'             => $datadetail->karyawan->kodesales,
        'lastupdatedby'         => $datadetail->lastupdatedby,
        'lastupdatedon'         => $datadetail->lastupdatedtime,
      ]);
    }
    
    public function ubah(Request $req){
      $kunjungan = Kunjungan::find($req->data['idkunjungan']);
      if($req->data['tipe'] == 'edit'){
        if ($kunjungan->tglkunjung == $req->data['tglkunjung']) {
          return response()->json([
            'message'=> 'Tidak ada perubahan yang disimpan.',
          ]);
        }
        else{
            if ($kunjungan->tagihandetailid != null) {
              return response()->json([
                'message'=>'Fixed Route sudah dibuat Register,tidak bisa edit Fixed Route.',
              ]);
            }else {
              $kunjungan->tglkunjung              = $req->data['tglkunjung'];
              $kunjungan->keteranganrealisasi            = $req->data['realisasi'];
              $kunjungan->keterangantambahan      = strtoupper($req->data['kettambahan']);
              $kunjungan->lastupdateby            = strtoupper($req->user()->username);
              $kunjungan->save();
              return response()->json([
                'success'=>true,
              ]);
            }
        }
      }else
      {
         if ($kunjungan->keteranganrealisasi == $req->data['realisasi'] && $kunjungan->keterangantambahan == $req->data['kettambahan']) {
          return response()->json([
            'message'=> 'Tidak ada perubahan yang disimpan.',
          ]);
        }
        else{
            if ($kunjungan->tagihandetailid != null) {
              return response()->json([
                'message'=>'Fixed Route sudah dibuat Register,tidak bisa edit Fixed Route.',
              ]);
            }else {

              $kunjungan->tglkunjung              = $req->data['tglkunjung'];
              $kunjungan->keteranganrealisasi            = $req->data['realisasi'];
              $kunjungan->keterangantambahan      = strtoupper($req->data['kettambahan']);
              $kunjungan->lastupdateby            = strtoupper($req->user()->username);
              $kunjungan->save();
              return response()->json([
                'success'=>true,
              ]);
            }
        }
      }
    }
    
    public function ubahPerToko(Request $req){
      foreach($req->tglkunjung as $key=> $data)
       {
         if ($data) {
            $kunjungan = Kunjungan::find($req->idkunjungan[$key]);
              if ($kunjungan->tagihandetailid != null) {
                  return response()->json([
                    'message'=>'Fixed Route sudah dibuat Register,tidak bisa edit Fixed Route.',
                  ]);
              }
               else {
                $kunjungan->tglkunjung         = date('Y-m-d',strtotime($req->tglkunjung[$key]));
                $kunjungan->keterangantambahan = strtoupper($req->kettambahan[$key]);
                $kunjungan->keteranganrealisasi       = $req->realisasi[$key];
                $kunjungan->createdby          = strtoupper($req->user()->username);
                $kunjungan->lastupdateby       = strtoupper($req->user()->username);
                $kunjungan->save();
              }
         } 
       }       
      return response()->json([
        'success' => true,
        'message' => 'Data Berhasil disimpan',
      ]);

    }


    public function batalKunjungan(Request $req)
    { 
      $idkunjungan    = $req->idkunjungan;
      foreach ($idkunjungan as $id) {
          $cek = Kunjungan::find($id);
          if(Carbon::parse($cek->tglkunjung)->toDateString() < Carbon::now()->toDateString()){
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa dihapus, kunjungan sudah lewat tanggal',
            ]);
          }else {
            if ($cek->tagihandetailid != null) {
              return response()->json([
                'success' => false,
                'message'=>'Fixed Route sudah dibuat register,tidak bisa hapus Fixed Route.',
              ]);
            }else {
              $cek->tglkunjung              = null;
              $cek->lastupdateby            = strtoupper($req->user()->username);
              $cek->save();
              // dd($cek);
            }
          }    
      }
      return response()->json([
          'success'=>true,
      ]);
    }
    
    public function hapusKunjungan(Request $req)
    {
      $detail = Kunjungan::find($req->kunjunganid);
      $detail->delete();
      return response()->json(['success' => true]);   
    }
}
