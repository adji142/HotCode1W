<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use PDF;

use App\Models\Barang;
use App\Models\Karyawan;
use App\Models\KategoriRetur;
use App\Models\NotaPembelianDetail;
use App\Models\Numerator;
use App\Models\ReturPembelian;
use App\Models\ReturPembelianMurni;
use App\Models\ReturPembelianDetail;
use App\Models\KoreksiReturPembelian;
use App\Models\StagingReturPembelian;
use App\Models\StagingReturPembelianDetail;
use App\Models\SubCabang;

class ReturPembelianController extends Controller
{
    public function index()
    {
        return view('transaksi.returpembelian.index');
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('returpembelian.index')) {
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
        
        if ($req->session()->exists('subcabang')) {
            $filter_count = 0;
            $empty_filter = 0;
            $columns = [
                1 => "pb.returpembelian.tglprb",
                2 => "pb.returpembelian.noprb",
                3 => "mstr.supplier.nama",
                4 => "pb.returpembelian.tglkirim",
                5 => "pb.returpembelian.tglnrj11",
            ];

            $columns_alias = [
                "pb.returpembelian.tglprb",
                "pb.returpembelian.noprb",
                "mstr.supplier.nama as namasupplier",
                "pb.returpembelian.tglkirim",
                "pb.returpembelian.tglnrj11",
                "pb.returpembelian.id",
                "hr.karyawan.namakaryawan as namastaff",
                "mstr.subcabang.namasubcabang as namasubcabang",
                "pb.returpembelian.qtykoli",
                "pb.returpembelian.nokoli",
                "pb.returpembelian.keterangan",
                "pb.returpembelian.nonrj11",
                "pb.returpembelian.staffpemeriksa11",
                "pb.returpembelian.ohpsdepo",
                "pb.returpembelian.lastupdatedby",
                "pb.returpembelian.lastupdatedon",
                "pb.returpembelian.sync11",
            ];

            foreach($req->custom_search as $search){
                if(empty($search['text'])){
                    $empty_filter++;
                }
            }

            // Models
            $modelObj = ReturPembelian::leftJoin('mstr.supplier', 'pb.returpembelian.supplierid', '=', 'mstr.supplier.id');
            $modelObj->leftJoin('hr.karyawan', 'pb.returpembelian.staffidpemeriksa00', '=', 'hr.karyawan.id');
            $modelObj->leftJoin('mstr.subcabang', 'pb.returpembelian.recordownerid', '=', 'mstr.subcabang.id');
            $modelObj->where("pb.returpembelian.recordownerid",$req->session()->get('subcabang'));
            $modelObj->whereRaw("DATE(pb.returpembelian.tglprb) BETWEEN '".$tglmulai."' AND '".$tglselesai."'");
            $total_data = $modelObj->count();

            if($empty_filter){
                foreach($req->custom_search as $i=>$search){
                    if($search['text'] != ''){
                        if($i == 2 || $i == 3){
                            if($search['filter'] == '='){
                                $modelObj->where($columns[$i],'ilike','%'.$search['text'].'%');
                            }else{
                                $modelObj->where($columns[$i],'not ilike','%'.$search['text'].'%');
                            }
                        }else{
                            $modelObj->where($columns[$i],$search['filter'],$search['text']);
                        }
                        $filter_count++;
                    }
                }
            }

            if($filter_count > 0){
                $filtered_data = $modelObj->count();
            }else{
                $filtered_data = $total_data;
            }

            if($req->tipe_edit){
                $modelObj->orderBy('pb.returpembelian.lastupdatedon','desc');
            }else{
                if(array_key_exists($req->order[0]['column'], $columns)){
                    $modelObj->orderByRaw($columns[$req->order[0]['column']].' '.$req->order[0]['dir']);
                }
            }

            if($req->start > $filtered_data){
                $modelObj->skip(0)->take($req->length);
            }else{
                $modelObj->skip($req->start)->take($req->length);
            }

            // Data
            $data = [];
            foreach ($modelObj->get($columns_alias) as $k => $val) {
                $val->tglprb        = $val->tglprb;
                $val->tglkirim      = $val->tglkirim;
                $val->tglnrj11      = $val->tglnrj11;
                $val->lastupdatedon = $val->lastupdatedon;
                $data[$k] = $val->toArray();
                $data[$k]['DT_RowId'] = 'gv1_'.$val->id;

                // Tambah RPBD
                if(Carbon::parse($val->tglprb)->toDateString() != Carbon::now()->toDateString()){
                    $data[$k]['tambahrpbd'] = 'Tidak bisa tambah record. Tanggal server tidak sama dengan Tanggal Pengajuan Retur Beli. Hubungi Manager Anda.';
                }elseif($val->tglnrj11 != null){
                    $data[$k]['tambahrpbd'] = 'Tidak bisa tambah record. Tanggal Nota Retur Jual dari 11 sudah terisi. Hubungi Manager Anda.';
                }else{
                    $data[$k]['tambahrpbd'] = 'tambah';
                }

                // Hapus RPB
                if(Carbon::parse($val->tglprb)->toDateString() != Carbon::now()->toDateString()){
                    $data[$k]['hapusretur'] = 'Tidak bisa hapus record. Tanggal server tidak sama dengan Tanggal Pengajuan retur Beli. Hubungi Manager Anda.';
                }elseif($val->details()->exists() != null){
                    $data[$k]['hapusretur'] = 'Tidak bisa hapus record. Sudah ada record di Retur Pembelian Detail. Hapus detail terlebih dahulu. Hubungi manager anda.';
                }else{
                    $data[$k]['hapusretur'] = 'auth';
                }

                // Update Kirim
                if($val->tglnrj11 != null){
                    $data[$k]['updatekirim'] = 'Tanggal Nota Retur Jual 11 sudah terisi. Tidak bisa update tanggal kirim. Hubungi Manager Anda.';
                }else{
                    $data[$k]['updatekirim'] = 'update';
                }

                $data[$k]['edit'] = 'edit';
                foreach ($val->details as $detail) {
                    if($detail->newchildid){
                        $data[$k]['edit'] = 'unedit';
                    }
                }

                $action = '';
                if ($val->sync11 != null) {
                    $action .= "<div class='checkbox checkboxaction'><input type='checkbox' class='flat disabled' value='".$val->id."' checked disabled></div>";
                }else {
                    if ($val->tglkirim != null) {
                    $action .= "<div class='checkbox checkboxaction'><input type='checkbox' class='flat disabled' value='".$val->id."'></div>";
                    }else{
                    $action .= "<div class='checkbox checkboxaction'><input type='checkbox' class='flat disabled' value='".$val->id."' readonly onclick=\"var ini = this; swal('Ups!', 'Tanggal Kirim Masih Kosong!.', 'error'); setTimeout(function(){ $(ini).iCheck('uncheck');}, 1); return false;\"></div>";
                }
                    if($data[$k]['edit'] != 'unedit'){
                    $action .= "<div class='btn btn-xs btn-success no-margin-action skeyF1' data-toggle='tooltip' data-placement='bottom' title='Tambah RPBD - F1' onclick='tambahrpbd(this)'><i class='fa fa-plus'></i></div>";
                    }
                    $action .= "<div class='btn btn-xs btn-warning no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='Update Kirim - F2' onclick='updatekirim(this)'><i class='fa fa-pencil'></i></div>";
                    if($data[$k]['edit'] != 'unedit'){
                    $action .= "<div class='btn btn-xs btn-danger no-margin-action skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus RPB - Del' onclick='hapusretur(this)' data-tipe='header'><i class='fa fa-trash'></i></div>";
                    }
                    if(!$req->user()->can('returpembelian.cetakmpr')) {
                    $action .= "<a href='".route('returpembelian.cetakmpr',$val->id)."' target='_blank' class='btn btn-xs btn-primary no-margin-action skeyF3' data-toggle='tooltip' data-placement='bottom' title='Cetak MPR - F3'><i class='fa fa-print'></i></a>";
                    }else{
                    $action .= "<a href='#' class='btn btn-xs btn-primary no-margin-action skeyF3' data-toggle='tooltip' data-placement='bottom' title='Cetak MPR - F3' onclick='this.blur();swal(\"Ups!\", \"Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.\",\"error\");return false;'><i class='fa fa-print'></i></a>";
                    }
                }

                $data[$k]['action']   = $action;
            }

            return response()->json([
                'draw'              => $req->draw,
                'recordsTotal'      => $total_data,
                'recordsFiltered'   => $filtered_data,
                'data'              => $data,
            ]);
        }else{
            return response()->json([
                'draw'              => $req->draw,
                'recordsTotal'      => 0,
                'recordsFiltered'   => 0,
                'data'              => [],
            ]);
        }
    }

    public function getDataDetail(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('returpembelian.index')) {
            return response()->json(['node'=> []]);
        }

        // jika lolos, tampilkan data
        $retur   = ReturPembelian::find($req->id);
        // Hapus RPB
        if(Carbon::parse($retur->tglprb)->toDateString() != Carbon::now()->toDateString()){
            $hapusretur = 'Tidak bisa hapus record. Tanggal server tidak sama dengan Tanggal Pengajuan retur Beli. Hubungi Manager Anda.';
        }elseif($retur->tglnrj11 != null){
            $hapusretur = 'Tidak bisa hapus record. Tanggal Nota retur jual dari 11 sudah terisi. Hubungi Manager Anda.';
        }else{
            $hapusretur = 'auth';
        }

        // Koreksi
        if($retur->tglnrj11 == null){
            $koreksi = 'Tidak bisa koreksi Pengajuan retur beli. Tanggal Nota retur jual masih kosong. Hubungi Manager Anda.';
        }else{
            $koreksi = 'koreksi';
        }

        $retur_details = $retur->details;

        $node = array();
        foreach ($retur_details as $detail) {
            if($detail->newchildid){
                $hapusretur = '-';
                $koreksi    = '-';
            }
            if($detail->koreksipbparentid){
                $hapusretur = '-';
            }

            $action = '';
            if(!$retur->sync11) {
                if($detail->koreksirpbparentid) {
                    if($detail->qtyprb > 0 && $koreksi != '-'){
                        $action .= "<div class='btn btn-xs btn-info skeyF6' data-toggle='tooltip' data-placement='bottom' title='Koreksi - F6' onclick='koreksi(this)'><i class='fa fa-edit'></i></div>";
                    }
                }else if($detail->newchildid) {
                    if($detail->qtyprb > 0 && $koreksi != '-'){
                        $action .= "<div class='btn btn-xs btn-info skeyF6' data-toggle='tooltip' data-placement='bottom' title='Koreksi - F6' onclick='koreksi(this)'><i class='fa fa-edit'></i></div>";
                    }
                }else{
                    if($koreksi != '-'){
                        $action .= "<div class='btn btn-xs btn-info skeyF6' data-toggle='tooltip' data-placement='bottom' title='Koreksi - F6' onclick='koreksi(this)'><i class='fa fa-edit'></i></div>";
                    }
                    if($hapusretur != '-'){
                        $action .= "<div class='btn btn-xs btn-danger skeyDel' data-toggle='tooltip' data-placement='bottom' title='Hapus RPB - Del' onclick='hapusretur(this)' data-tipe='detail'><i class='fa fa-trash'></i></div>";
                    }
                }
            }else if($koreksi != '-'){
                $action .= "<div class='btn btn-xs btn-info skeyF6' data-toggle='tooltip' data-placement='bottom' title='Koreksi - F6' onclick='koreksi(this)'><i class='fa fa-edit'></i></div>";
            }

            $barang = $detail->barang;
            $temp   = [
                'DT_RowId'   => 'gv2_'.$detail->id,
                'action'     => $action,
                'namabarang' => $barang->namabarang,
                'satuan'     => $barang->satuan,
                'qtyprb'     => $detail->qtyprb,
                'id'         => $detail->id,
                'koreksi'    => $koreksi,
                'hapusretur' => $hapusretur,
                'newchildid' => $detail->newchildid,
                'sync11'     => $retur->sync11,
                'koreksirpbparentid' => $detail->koreksirpbparentid,
            ];
            array_push($node, $temp);
        }

        return response()->json([
            'node' => $node,   
        ]);
    }

    public function getDetail(Request $req)
    {
        $detail = ReturPembelianDetail::find($req->id);
        $retur  = $detail->returpembelian;

        $hrgdisc1 = (1-($detail->disc1/100)) * $detail->hargabruttonrj * $detail->qtyprb;
        $hrgdisc2 = (1-($detail->disc2/100)) * $hrgdisc1;

        return response()->json([
            'recordowner'    => $retur->subcabang->namasubcabang,
            'recordownerid'  => $retur->recordownerid,
            'tglprb'         => $retur->tglprb,
            'noprb'          => $retur->noprb,
            'supplier'       => $retur->supplier->nama,
            'pemeriksa00'    => ($retur->pemeriksa00()->exists()) ?$retur->pemeriksa00->nama : null,
            'tglkirim'       => $retur->tglkirim,
            'qtykoli'        => $retur->qtykoli,
            'nokoli'         => $retur->nokoli,
            'historis'       => $detail->historispembelian,
            'barang'         => $detail->barang->namabarang,
            'satuan'         => $detail->barang->satuan,
            'qtyprb'         => $detail->qtyprb,
            'dtnokoli'       => $detail->dtnokoli,
            'kategoriprb'    => $detail->kategoriretur->nama,
            'keteranganprb'  => $detail->keteranganprb,
            'idrpbd'         => $detail->id,
            'qtyprb'         => $detail->qtyprb,
            'qtynrj'         => $detail->qtynrj,
            'qtyrq11'        => $detail->qtyrq11,
            'hargabruttonrj' => $detail->hargabruttonrj,
            'disc1'          => $detail->disc1,
            'hrgdisc1'       => $hrgdisc1,
            'disc2'          => $detail->disc2,
            'hrgdisc2'       => $hrgdisc2,
            'ppn'            => $detail->ppn,
            'lastupdatedby'  => $detail->lastupdatedby,
            'lastupdatedon'  => $detail->lastupdatedon,
        ]);
    }

    public function tambah(Request $req)
    {
        if ($req->session()->exists('subcabang')) {
            $subcabanguser = SubCabang::find($req->session()->get('subcabang'))->kodesubcabang;
            return view('transaksi.returpembelian.form', ['next_no'=>$this->nextnoprb(), 'subcabanguser'=>$subcabanguser]);
        }else{
            return redirect()->route('returpembelian.index');
        }
    }

    public function simpan(Request $req)
    {
        if($req->session()->exists('subcabang')) {
            // Create New Retur
            $retur = new ReturPembelian;
            $retur->recordownerid      = $req->session()->get('subcabang');
            $retur->tglprb             = Carbon::parse($req->tglprb)->format("Y-m-d");
            $retur->noprb              = $this->nextnoprb();
            $retur->supplierid         = $req->supplierid;
            $retur->staffidpemeriksa00 = $req->staffidpemeriksa00;
            $retur->createdby          = strtoupper($req->user()->username);
            $retur->lastupdatedby      = strtoupper($req->user()->username);
            $retur->nprint             = 0;
            $retur->save();

            return response()->json(['success'=>true, 'returid'=>$retur->id, 'noprb'=>$retur->noprb]);
        }else{
            return response()->json(['success'=>false, 'message'=>'Retur Pembelian gagal ditambahkan. Sub Cabang kosong.']);
        }
    }
    // Api update retur pembelian
    public function apiReturPembelian(Request $req)
    {
         $tglprb    = $req->tglprb;
         $noprb     = $req->noprb;
         $tglkirim  = $req->tglkirim;
         $nonrj11   = $req->nonrj11;
         $tglnrj11  = $req->tglnrj11;
         $kodebarang= $req->kodebarang;
         $qtynrj11  = $req->qtynrj11;

        // cari returpembelianheader dg tglprb , noprb , tglkirim yg di kirim parameter
        $cekretur = ReturPembelian::whereDate('tglprb',$tglprb)->where('noprb',$noprb)->whereDate('tglkirim',$tglkirim)->first();
        if ($cekretur) {
            //update kolom nonrjll dam tglnrj11
            $retur = ReturPembelian::find($cekretur->id);
            $retur->nonrj11  = $nonrj11;
            $retur->tglnrj11 = $tglnrj11;
            $retur->save();
            $messageheader = 'Retur Pembelian berhasil diubah';

            //cari returpembeliandetail dg kodebarang yg ada di parameter dan headernya jg yg ada di parameter, 
            $cekreturdetail = ReturPembelianDetail::select('pb.returpembeliandetail.id')->join('mstr.stock','pb.returpembeliandetail.stockid','=','mstr.stock.id')->where('mstr.stock.kodebarang',$kodebarang)->where('pb.returpembeliandetail.returpembelianid',$cekretur->id)->first();

            //update kolom qtynrj dari parameter.qtynrj11
            if ($cekreturdetail) {
                $returdetail = ReturPembelianDetail::find($cekreturdetail->id);
                $returdetail->qtynrj = $qtynrj11;
                $returdetail->save();
                $messagedetail ='Retur Pembelian Detail berhasil diubah';
            }else{
                $messagedetail ='Tidak ada Retur Pembelian Detail';
            }
            return response()->json([
                    'success'       =>true, 
                    'returid'       =>$retur->id, 
                    'messageheader' =>$messageheader,
                    'messagedetail' =>$messagedetail
                ]);
        } else {
            return response()->json([
                    'success'=>false, 
                    'message'=>'Tidak ditemukan data retur pembelian yang Anda masukan.'
            ]);
        }
    }
    // Api insert retur pembelian
    public function apiInsertReturPembelian(Request $req)
    {
        $kodesubcabangid   = $req->kodesubcabangid;
        $tglprb            = $req->tglprb;
        $noprb             = $req->noprb;
        $details           = $req->details;
        $katprbid  = KategoriRetur::where('nama','PERMINTAAN "11"')->first();
        // ambil value kodesubcabangid 
        $subcabang = SubCabang::where('kodesubcabang',$kodesubcabangid)->first();
        if($subcabang){
            // insert header berdasarkan parameter { kodesubcabangid , tglrb , norb }
            $cekheader = ReturPembelian::where('recordownerid',$subcabang->id)->whereDate('tglprb',$tglprb)->where('noprb',$noprb)->first();
            $katprbid  = KategoriRetur::where('kode','Q')->first();
                if($cekheader){
                    return response()->json([
                        'success'=>false, 
                        'message'=>'Data sudah ada.'
                    ]);
                }else{
                    $retur = new ReturPembelian();
                    $retur->recordownerid = $subcabang->id;
                    $retur->tglprb        = $tglprb;
                    $retur->noprb         = $noprb;
                    
                    // sudah ditanyakan
                    $retur->supplierid    = 1;
                    $retur->nprint        = 0;
                    $retur->createdby     = strtoupper('stok1101');
                    $retur->lastupdatedby = strtoupper('stok1101');

                    $retur->save();
                    // insert detail
                    foreach ($details as $key => $value) {
                        $barang    = Barang::where('kodebarang',json_decode($value)->kodebarang)->first();
                        if ($barang) {
                            $detail = new ReturPembelianDetail();
                            $detail->returpembelianid      = $retur->id;
                            //sudah ditanyakan
                            $detail->historispembelian     = 0;
                            $detail->disc2                 = 0;
                            if ($katprbid) {
                                $detail->kategoriprbid     = $katprbid->id;
                            }
                            $detail->createdby             = strtoupper('stok1101');
                            $detail->lastupdatedby         = strtoupper('stok1101');

                            $detail->stockid               = $barang->id;
                            $detail->qtyrq11               = json_decode($value)->qtyrq11;
                            $detail->save();
                        }
                    }
                    return response()->json([
                        'success'=>true, 
                        'message'=>'Data Berhasil ditambahkan.'
                    ]);
                }
            // { kodesubcabangid , tglrb , norb , kodebarang , qtyrq11  } insert detail -> cari id headernya dg 3 parameter kodesubcabangid , tglrb, norb 
            

        }else{
            return response()->json([
                    'success'=>false, 
                    'message'=>'Kode SubCabang tidak ada.'
            ]);
        }
    }
    public function simpanDetail(Request $req)
    {
        // Create New Retur
        $retur = ReturPembelian::find($req->returid);
        $node  = array();

        if($retur && Carbon::parse($retur->tglprb)->toDateString() == Carbon::now()->toDateString() && $retur->tglnrj11 == null) {
            $detail = new ReturPembelianDetail();
            $detail->returpembelianid  = $retur->id;
            $detail->historispembelian = ($req->historis == 1) ? true : false;
            if($req->historis == 1) {
                $detail->notapembeliandetailid = $req->npbdid;
            }
            $detail->stockid       = $req->barangid;
            $detail->qtyprb        = $req->qtyprb;
            $detail->kategoriprbid = $req->kategoriprbid;
            $detail->nokoli        = $req->dtnokoli;
            $detail->keteranganprb = $req->keteranganprb;
            $detail->disc1         = 0;
            $detail->disc2         = 0;
            $detail->ppn           = 0;
            $detail->lastupdatedby = strtoupper($req->user()->username);
            $detail->createdby     = strtoupper($req->user()->username);
            $detail->save();

            $retur = ReturPembelian::find($req->returid);

            // Hapus RPB
            if(Carbon::parse($retur->tglprb)->toDateString() != Carbon::now()->toDateString()){
                $hapusretur = 'Tidak bisa hapus record. Tanggal server tidak sama dengan Tanggal Pengajuan retur Beli. Hubungi Manager Anda.';
            }elseif($retur->tglnrj11 != null){
                $hapusretur = 'Tidak bisa hapus record. Tanggal Nota retur jual dari 11 sudah terisi. Hubungi Manager Anda.';
            }else{
                $hapusretur = 'auth';
            }

            // Koreksi
            if($retur->tglnrj11 == null){
                $koreksi = 'Tidak bisa koreksi Pengajuan retur beli. Tanggal Nota retur jual masih kosong. Hubungi Manager Anda.';
            }else{
                $koreksi = 'koreksi';
            }

            $retur_details = $retur->details;

            $node = array();
            foreach ($retur_details as $detail) {
                if($detail->newchildid){
                    $hapusretur = '-';
                    $koreksi    = '-';
                }
                if($detail->koreksipbparentid){
                    $hapusretur = '-';
                }

                $barang = $detail->barang;
                $temp   = [
                    0 => $barang->namabarang,
                    1 => $barang->satuan,
                    2 => $detail->qtyprb,
                    3 => $detail->id,
                    4 => $koreksi,
                    5 => $hapusretur,
                    6 => $detail->koreksirpbparentid,
                    7 => $detail->newchildid,
                    8 => $retur->sync11,
                ];
                array_push($node, $temp);
            }
        }

        return response()->json([
            'success' => true,
            'node'    => $node,
        ]);
    }

    public function updatetglkirim(Request $req)
    {
        $retur = ReturPembelian::find($req->returid);
        $retur->tglkirim      = $req->tglkirim;
        $retur->qtykoli       = $req->qtykoli;
        $retur->nokoli        = $req->nokoli;
        $retur->lastupdatedby = strtoupper($req->user()->username);
        $retur->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function simpanDetailKoreksi(Request $req)
    {
        // Numerator
        $numerator = Numerator::find(Numerator::where('doc','KOREKSI_RETUR_PEMBELIAN')->value('id'));
        $depan     = $numerator->depan;
        $lebar     = $numerator->lebar-strlen($numerator->depan);
        $nomor     = sprintf("%0".$lebar."d", $numerator->nomor);
        $numerator->nomor++;
        $numerator->save();

        // Data Lama
        $detaillama = ReturPembelianDetail::find($req->koreksiidrpbd);
        $returlama  = $detaillama->returpembelian;

        // Create New Retur
        $retur = new ReturPembelian;
        $retur->recordownerid      = $returlama->recordownerid;
        $retur->tglprb             = $req->koreksitglprb;
        $retur->tglkirim           = $req->koreksitglkirim;
        $retur->noprb              = $depan.$nomor;
        // $retur->noprb              = $req->koreksinoprb;
        $retur->supplierid         = $returlama->supplierid;
        $retur->staffidpemeriksa00 = $req->koreksipemeriksa00id;
        $retur->keterangan         = $returlama->keterangan;
        $retur->createdby          = strtoupper($req->user()->username);
        $retur->lastupdatedby      = strtoupper($req->user()->username);
        $retur->nprint             = 0;
        $retur->save();

        // New ID
        $id = $retur->id;

        // Insert Detail Retur Minus
        $detailminus = new ReturPembelianDetail;
        $detailminus->returpembelianid      = $id;
        $detailminus->koreksirpbparentid    = $detaillama->id;
        $detailminus->historispembelian     = false; // Auto non-historis
        $detailminus->notapembeliandetailid = $detaillama->notapembeliandetailid;
        $detailminus->stockid               = $detaillama->stockid;
        $detailminus->qtyprb                = ($detaillama->qtyprb)*-1;
        $detailminus->kategoriprbid         = $detaillama->kategoriprbid;
        $detailminus->nokoli                = $detaillama->nokoli;
        $detailminus->keteranganprb         = $detaillama->keteranganprb;
        $detailminus->createdby             = strtoupper($req->user()->username);
        $detailminus->lastupdatedby         = strtoupper($req->user()->username);
        $detailminus->save();

        // Insert Detail Retur Seharusnya
        $detail = new ReturPembelianDetail;
        $detail->returpembelianid      = $id;
        $detail->koreksirpbparentid    = $detaillama->id;
        $detail->historispembelian     = false; // Auto non-historis
        $detail->notapembeliandetailid = $detaillama->notapembeliandetailid;
        $detail->stockid               = $detaillama->stockid;
        $detail->qtyprb                = $req->koreksiqtyprb;
        $detail->kategoriprbid         = $detaillama->kategoriprbid;
        $detail->nokoli                = $detaillama->nokoli;
        $detail->keteranganprb         = $req->koreksiketeranganprb;
        $detail->createdby             = strtoupper($req->user()->username);
        $detail->lastupdatedby         = strtoupper($req->user()->username);
        $detail->save();

        // Update NewchildID
        $detaillama->newchildid = $detail->id;
        $detaillama->save();

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
                    $retur = ReturPembelian::find($req->returid);
                    $retur->delete();
                }else {
                    $detail = ReturPembelianDetail::find($req->returid);
                    $detail->delete();
                }
                return response()->json(['success' => true]);
            }
        }
        return response()->json(['success' => false]);
    }

    public function cetakmpr(Request $req, $id)
    {
        $config = [
            'mode'                 => '',
            'format'               => [162.56,215.9],
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 30,
            'margin_bottom'        => 12,
            'margin_header'        => 5,
            'margin_footer'        => 5,
            'orientation'          => 'L',
            'title'                => 'MEMO PENGAJUAN RETUR BELI',
            'author'               => '',
            'watermark'            => '',
            'show_watermark'       => true,
            'show_watermark_image' => true,
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
        ];

        $retur = ReturPembelian::find($id);
        $pdf   = PDF::loadView('transaksi.returpembelian.pdf',['user'=>$req->user(),'retur'=>$retur],[],$config);

        return $pdf->stream('MPR-'.$id.'.pdf');
    }

    private function nextnoprb()
    {
        $max_no  = ReturPembelian::selectRaw("MAX((SUBSTRING(noprb FROM '[0-9]+'))::int) as max")->whereRaw('LENGTH(noprb) = 10')->first()->max;
        $next_no = str_pad($max_no+1, 10, '0', STR_PAD_LEFT);

        return $next_no;
    }

    public function sync11(Request $request){
        $idpembelians = $request->data;
        foreach ($idpembelians as $id) {
            $retur  = ReturPembelian::find($id);
            $stagingRetur = new StagingReturPembelian;
            $stagingRetur->id                 = $retur->id;
            $stagingRetur->recordownerid      = SubCabang::where("id", $retur->recordownerid)->first()->kodesubcabang; //$retur->recordownerid;
            $stagingRetur->tglprb             = Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($retur->tglprb)->toDateTimeString());
            $stagingRetur->noprb              = $retur->noprb;
            if($retur->supplier){
                $stagingRetur->kodesupplier   = $retur->supplier->kode;
            }
            $stagingRetur->tglkirim           = Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($retur->tglkirim)->toDateTimeString());
            $stagingRetur->staffidpemeriksa00 = $retur->staffidpemeriksa00;
            if($retur->expedisi){
                $stagingRetur->kodeexpedisi   = $retur->expedisi->kodeexpedisi;
            }
            $stagingRetur->qtykoli            = $retur->qtykoli;
            $stagingRetur->nokoli             = $retur->nokoli;
            // $stagingRetur->tglnrj11           = $retur->tglnrj11;
            // $stagingRetur->nonrj11            = $retur->nonrj11;
            // $stagingRetur->staffpemeriksa11   = $retur->staffpemeriksa11;
            $stagingRetur->keterangan         = $retur->keterangan;
            $stagingRetur->nprint             = $retur->nprint;
            $stagingRetur->ohpsdepo           = $retur->ohpsdepo;
            $stagingRetur->isarowid           = $retur->isarowid;
            $stagingRetur->createdby          = $retur->createdby;
            $stagingRetur->createdon          = Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($retur->createdon)->toDateTimeString());
            $stagingRetur->lastupdatedby      = $retur->lastupdatedby;
            $stagingRetur->lastupdatedon      = Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($retur->lastupdatedon)->toDateTimeString());
            $stagingRetur->save();

            foreach ($retur->details as $value) {
                $stagingDetail = new StagingReturPembelianDetail;
                $stagingDetail->id                    = $value->id;
                $stagingDetail->returpembelianid      = $value->returpembelianid;
                $stagingDetail->historispembelian     = $value->historispembelian;
                $stagingDetail->notapembeliandetailid = $value->notapembeliandetailid;
                $stagingDetail->kodebarang            = $value->barang->kodebarang;
                $stagingDetail->qtyprb                = $value->qtyprb;
                $stagingDetail->qtynrj                = $value->qtynrj;
                // $stagingDetail->qtyrq11               = $value->qtyrq11;
                $stagingDetail->hargabruttonrj        = $value->hargabruttonrj;
                $stagingDetail->disc1                 = $value->disc1;
                $stagingDetail->disc2                 = $value->disc2;
                $stagingDetail->ppn                   = $value->ppn;
                $stagingDetail->harganettonrj         = $value->harganettonrj;
                $stagingDetail->kodekategori          = $value->kategoriretur->kode;
                $stagingDetail->nokoli                = $value->nokoli;
                $stagingDetail->keteranganprb         = $value->keteranganprb;
                $stagingDetail->koreksirpbparentid    = $value->koreksirpbparentid;
                $stagingDetail->newchildid            = $value->newchildid;
                $stagingDetail->isarowid              = $value->isarowid;
                $stagingDetail->createdby             = $value->createdby;
                $stagingDetail->createdon             = Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($value->createdon)->toDateTimeString());
                $stagingDetail->lastupdatedby         = $value->lastupdatedby;
                $stagingDetail->lastupdatedon         = Carbon::createFromFormat('Y-m-d H:i:s',Carbon::parse($value->lastupdatedon)->toDateTimeString());
                $stagingDetail->save();
            }

            // Update Tgl
            $retur->timestamps = false;
            $retur->sync11    = date('Y-m-d H:i:s');
            $retur->save();
        }
        return response()->json(['success' => true]);
    }

}
