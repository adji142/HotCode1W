<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use PDF;

use App\Models\AppSetting;
use App\Models\KartuPiutangDetail;
use App\Models\KategoriRetur;
use App\Models\NotaPenjualanDetail;
use App\Models\Numerator;
use App\Models\ReturPenjualan;
use App\Models\ReturPenjualanDetail;
use App\Models\SubCabang;
use App\Models\Toko;

class ReturPenjualanController extends Controller
{
    public function index(Request $req)
    {
        $ppn = AppSetting::where('recordownerid',$req->session()->get('subcabang'))->where('keyid','ppn')->first();
        return view('transaksi.returpenjualan.index',['ppn'=>($ppn) ? $ppn->value : 0]);
    }

    public function cekKewenangan(Request $req)
    {
        $lastUserId = auth()->user()->id;
        if(auth()->attempt(['username'=>$req->userKewenangan,'password'=>$req->passKewenangan]))
        {
            if(auth()->user()->can($req->permission))
            {
                auth()->loginUsingId($lastUserId);
                if(substr($req->permission,15) == 'cetaknrj'){
                    return response()->json([
                        'success' => true,
                        'url'     => route('notapenjualan.cetaknrj',$req->returid),
                    ]);

                }elseif (substr($req->permission,15) == 'cetaksppb') {
                    return response()->json([
                        'success' => true,
                        'url'     => route('notapenjualan.cetaksppb',$req->returid),
                    ]);

                }elseif (substr($req->permission,15) == 'hapusretur') {
                    $this->hapusRetur($req->returid);
                    return response()->json([ 'success' => true,]);

                }elseif (substr($req->permission,15) == 'hapusdetail') {
                    $this->hapusDetail($req->returid);
                    return response()->json([ 'success' => true,]);

                }else {
                    return response()->json([ 'success' => true,]);
                }
            }
        }

        return response()->json(['success' => false]);
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('returpenjualan.index')) {
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
                1 => "pj.returpenjualan.tglmpr",
                2 => "pj.returpenjualan.nompr",
                3 => "pj.returpenjualan.tglnotaretur",
                4 => "pj.returpenjualan.nonotaretur",
                5 => "mstr.toko.namatoko",
            ];

            $columns_alias = [
                "pj.returpenjualan.id",
                "pj.returpenjualan.tglmpr",
                "pj.returpenjualan.nompr",
                "pj.returpenjualan.tglnotaretur",
                "pj.returpenjualan.tglsppb",
                "pj.returpenjualan.tglnotaretur",
                "pj.returpenjualan.nonotaretur",
                "pj.returpenjualan.tokoid",
                "mstr.toko.namatoko as namatoko",
                "pj.returpenjualan.lastupdatedby",
                "pj.returpenjualan.lastupdatedon",
            ];

            foreach($req->custom_search as $search){
                if(empty($search['text'])){
                    $empty_filter++;
                }
            }

            // Models
            $modelObj = ReturPenjualan::leftJoin('mstr.toko', 'mstr.toko.id', '=', 'pj.returpenjualan.tokoid');
            $modelObj->leftJoin('mstr.subcabang', 'pj.returpenjualan.recordownerid', '=', 'mstr.subcabang.id');
            $modelObj->where("pj.returpenjualan.recordownerid",$req->session()->get('subcabang'));
            // $modelObj->whereRaw("DATE(pj.returpenjualan.tglmpr) BETWEEN '".$tglmulai."' AND '".$tglselesai."'");
            $modelObj->whereRaw("
                (
                    (DATE(pj.returpenjualan.tglmpr) BETWEEN '".$tglmulai."' AND '".$tglselesai."')
                    OR
                    (DATE(pj.returpenjualan.tglnotaretur) BETWEEN '".$tglmulai."' AND '".$tglselesai."')
                )
            ");
            // $modelObj->where(function($query) use ($req) {
            //     $query->whereBetween("pj.returpenjualan.tglmpr", [Carbon::parse($req->tglmulai),Carbon::parse($req->tglselesai)]);
            //     $query->orWhereBetween("pj.returpenjualan.tglnotaretur", [Carbon::parse($req->tglmulai),Carbon::parse($req->tglselesai)]);
            // });
            $total_data = $modelObj->count();

            if($empty_filter){
                foreach($req->custom_search as $i=>$search){
                    if($search['text'] != ''){
                        if($i == 2 || $i == 4 || $i == 5){
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

            // dd($req->order);
            if($req->tipe_edit){
                $modelObj->orderBy('pj.returpenjualan.lastupdatedon','desc');
            }else{
                if(array_key_exists($req->order[0]['column'], $columns)){
                    // $modelObj->orderByRaw($columns[$req->order[0]['column']].' '.$req->order[0]['dir']);
                    $modelObj->orderByRaw($columns[$req->order[0]['column']].' desc');
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
                $val->tglsppb       = $val->tglsppb;
                $val->tglkirim      = $val->tglkirim;
                $val->tglnotaretur  = $val->tglnotaretur;
                $val->lastupdatedon = $val->lastupdatedon;
                $data[$k] = $val->toArray();
                $data[$k]['DT_RowId'] = 'gv1_'.$val->id;


                // Tambah rpjd
                if($val->details()->whereNotNull('koreksirjparentid')->exists()){
                    $data[$k]['tambahrpjd'] = 'disabled';
                }elseif($val->details()->whereNotNull('newchildid')->exists()){
                    $data[$k]['tambahrpjd'] = 'disabled';
                }elseif(Carbon::parse($val->tglsppb)->toDateString() != Carbon::now()->toDateString()){
                    $data[$k]['tambahrpjd'] = 'Tidak bisa tambah record. Tanggal server tidak sama dengan tanggal SPPB. Hubungi Manager Anda.';
                }elseif($val->tglnotaretur != null){
                    $data[$k]['tambahrpjd'] = 'Tidak bisa tambah record. Tanggal NRJ sudah terisi. Hubungi Manager Anda.';
                }else{
                    $data[$k]['tambahrpjd'] = 'tambah';
                }

                // Hapus rpj
                if($val->details()->whereNotNull('koreksirjparentid')->exists()){
                    $data[$k]['hapusretur'] = 'disabled';
                }elseif($val->details()->whereNotNull('newchildid')->exists()){
                    $data[$k]['hapusretur'] = 'disabled';
                }elseif($val->details()->exists() != null){
                    $data[$k]['hapusretur'] = 'Tidak bisa hapus record. Sudah ada record di Retur Penjualan Detail. Hapus detail terlebih dahulu. Hubungi manager anda.';
                }else{
                    $data[$k]['hapusretur'] = 'auth';
                }

                // Cetak NRJ
                if($val->tglnotaretur != null){
                    $data[$k]['cetaknrj'] = 'cetak';
                }elseif($val->printnrj >= 3){
                    $data[$k]['cetaknrj'] = 'auth';
                }else{
                    $data[$k]['cetaknrj'] = 'disabled';
                }

                // Cetak SPPB
                if($val->tglsppb != null){
                    $data[$k]['cetaksppb'] = 'cetak';
                }elseif($val->printsppb >= 3){
                    $data[$k]['cetaksppb'] = 'auth';
                }else{
                    $data[$k]['cetaksppb'] = 'disabled';
                }

                // Update SPPB
                if($val->details()->whereNotNull('koreksirjparentid')->exists()){
                    $data[$k]['updatesppb'] = 'disabled';
                }elseif($val->details()->whereNotNull('newchildid')->exists()){
                    $data[$k]['updatesppb'] = 'disabled';
                }elseif($val->tglnotaretur != null){
                    $data[$k]['updatesppb'] = 'Tidak bisa update record. Nota retur Jual sudah dibuat. Hubungi manager anda.';
                }elseif($val->tglsppb != null){
                    $data[$k]['updatesppb'] = 'auth';
                }else{
                    $data[$k]['updatesppb'] = 'update';
                }

                // Update NRJ
                if($val->details()->whereNotNull('koreksirjparentid')->exists()){
                    $data[$k]['updatenrj'] = 'disabled';
                }elseif($val->details()->whereNotNull('newchildid')->exists()){
                    $data[$k]['updatenrj'] = 'disabled';
                }elseif($val->kartupiutangdetailid != null){
                    $data[$k]['updatenrj'] = 'Tidak bisa update Nota Retur Jual. Data sudah dilink ke kartu piutang detail. Di nomor transaksi xxx (kartupiutang) tanggal dd-mm-yyyy (kartupiutangdetail)';
                }elseif($val->tglnotaretur == null){
                    $data[$k]['updatenrj'] = 'update';
                }elseif(Carbon::parse($val->tglnotaretur)->toDateString() != Carbon::now()->toDateString()){
                    $data[$k]['updatenrj'] = 'Tidak bisa update record. Tanggal Nota Retur Jual tidak sama dengan tanggal server. Hubungi manager anda.';
                }else{
                    $data[$k]['updatenrj'] = 'auth';
                }

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
        if(!$req->user()->can('returpenjualan.index')) {
            return response()->json(['node'=>[]]);
        }

        // jika lolos, tampilkan data
        $node = $this->getDataDetailNode($req->id);
        return response()->json(['node'=>$node]);
    }

    private function getDataDetailNode($id)
    {
        $retur = ReturPenjualan::find($id);

        if(Carbon::parse($retur->tglsppb)->toDateString() != Carbon::now()->toDateString()){
            $default_hapusretur = 'Tidak bisa hapus record. Tanggal SPPB tidak sama dengan tanggal server. Hubungi manager anda.';
        }elseif($retur->tglnotaretur != null){
            $default_hapusretur = 'Tidak bisa hapus record. Tanggal Nota Retur Jual sudah terisi. Hubungi Manager Anda.';
        }else{
            $default_hapusretur = 'auth';
        }

        // Koreksi
        // if($retur->kartupiutangdetailid == null || $retur->kartupiutangdetailid == '' || $retur->kartupiutangdetailid == -1){
        // if($retur->kartupiutangdetailid > 0){
        if($retur->kartupiutangdetail){
            $default_koreksi = 'Tidak bisa buat Nota Koreksi Retur Jual. Nota Retur jual belum di link ke Piutang. Hubungi manager anda.';
        }else{
            $default_koreksi = 'koreksi';
        }

        $retur_details = $retur->details;

        $node = array();
        foreach ($retur_details as $detail) {
            $koreksi    = $default_koreksi;
            $hapusretur = $default_hapusretur;
            // if($detail->qtynrj < 0){
            //     $koreksi    = 'disabled';
            //     $hapusretur = 'disabled';
            // }elseif($detail->newchildid){
            if($detail->newchildid){
                $transaksi  = ReturPenjualanDetail::find($detail->newchildid);
                // $koreksi    = 'Tidak bisa buat Nota koreksi Retur Jual. Item barang ini sudah dikoreksi menjadi Nota Retur jual tanggal '.$transaksi->returpenjualan->tglnotaretur.' nomor '.$transaksi->returpenjualan->nonotaretur.'. Hubungi Manager anda.';
                $hapusretur = 'Tidak bisa menghapus Nota koreksi Retur Jual. Item barang ini sudah dikoreksi menjadi Nota Retur jual tanggal '.$transaksi->returpenjualan->tglnotaretur.' nomor '.$transaksi->returpenjualan->nonotaretur.'. Hubungi Manager anda.';
            // }elseif($detail->qtynrj < 0){
            }elseif($detail->koreksirjparentid){
                $transaksi  = ReturPenjualanDetail::find($detail->koreksirjparentid);
                if($detail->qtynrj < 0){
                    $koreksi    = 'Tidak bisa buat Nota koreksi Retur Jual. Item barang ini adalah transaksi koreksi pembatalan atas transaksi Nota Retur jual tanggal '.$transaksi->returpenjualan->tglnotaretur.' nomor '.$transaksi->returpenjualan->nonotaretur.'. Hubungi Manager anda.';
                }
                $hapusretur = 'Tidak bisa menghapus Nota koreksi Retur Jual. Item barang ini adalah transaksi koreksi pembatalan atas transaksi Nota Retur jual tanggal '.$transaksi->returpenjualan->tglnotaretur.' nomor '.$transaksi->returpenjualan->nonotaretur.'. Hubungi Manager anda.';
            }

            $barang = $detail->barang;
            $temp['DT_RowId']   = 'gv2_'.$detail->id;
            $temp['namabarang'] = $barang->namabarang;
            $temp['satuan']     = $barang->satuan;
            $temp['qtympr']     = ($detail->qtympr) ? $detail->qtympr : 0;
            $temp['qtynrj']     = ($detail->qtynrj) ? $detail->qtynrj : 0;
            $temp['qtysppb']    = ($detail->qtysppb) ? $detail->qtysppb : 0;
            $temp['hrgnrj']     = $detail->hrgsatuanbrutto;
            $temp['hrgttlnrj']  = $detail->hrgsatuanbrutto;
            $temp['id']         = $detail->id;
            $temp['koreksi']    = $koreksi;
            $temp['hapusretur'] = $hapusretur;
            $temp['koreksirjparentid'] = $detail->koreksirjparentid;
            $temp['newchildid']    = $detail->newchildid;
            if(isset($detail->kategoriretur) and isset($detail->kategoriretur->nama)){
                $temp['kategorirj']    = $detail->kategoriretur->nama;    
            }else{
                $temp['kategorirj']    = NULL;
            }
            
            $temp['kategoriidrpj'] = $detail->kategoriidrpj;

            $hrgdisc1 = (1-($detail->disc1/100)) * $detail->hrgsatuanbrutto;
            $hrgdisc2 = (1-($detail->disc2/100)) * $hrgdisc1;
            $hrgppn   = (1+($detail->ppn/100)) * $hrgdisc2;
            $temp['hrgdisc1']        = $hrgdisc1;
            $temp['hrgdisc2']        = $hrgdisc2;
            $temp['hrgsatnetto']     = $hrgppn;
            $temp['hrgsatnettompr']  = $hrgppn*$detail->qtympr;
            $temp['hrgsatnettosppb'] = $hrgppn*$detail->qtysppb;
            $temp['hrgsatnettonrj']  = $hrgppn*$detail->qtynrj;

            array_push($node, $temp);
        }

        return $node;
    }

    public function getHeader(Request $req)
    {
        $retur = ReturPenjualan::find($req->id);
        $retur->lastupdatedon = $retur->lastupdatedon;
        $retur->createdon = $retur->createdon;

        $data  = $retur->toArray();
        $data['recordowner']        = $retur->subcabang->namasubcabang;
        $data['omsetsubcabangkode']    = ($retur->omsetsubcabang) ? $retur->omsetsubcabang->kodesubcabang : '';
        $data['pengirimsubcabangkode'] = ($retur->pengirimsubcabang) ? $retur->pengirimsubcabang->kodesubcabang : '';
        $data['namatoko']           = $retur->toko->namatoko;
        $data['alamattoko']         = $retur->toko->alamat;
        $data['kotatoko']           = $retur->toko->kota;
        $data['kecamatantoko']      = $retur->toko->kecamatan;
        $data['wilidtoko']          = $retur->toko->customwilayah;
        $data['idtoko']             = $retur->toko->id;
        $data['namaexpedisi']       = ($retur->expedisi) ? $retur->expedisi->namaexpedisi : '-';
        $data['namastaffstok']      = ($retur->staffstok) ? $retur->staffstok->namakaryawan : '-';
        // $data['nonotaretur']        = '?';
        $data['namastaffexpedisi']  = ($retur->staffexpedisi) ? $retur->staffexpedisi->namakaryawan : '-';
        $data['namastaffpenjualan'] = ($retur->staffpenjualan) ? $retur->staffpenjualan->namakaryawan : '-';
        
        return response()->json($data);
    }

    public function getDetail(Request $req)
    {
        $detail = ReturPenjualanDetail::find($req->id);
        $detail->lastupdatedon = $detail->lastupdatedon;
        $detail->createdon = $detail->createdon;
        $data   = $detail->toArray();

        $data['barang']        = $detail->barang->namabarang;
        $data['kodebarang']    = $detail->barang->kodebarang;
        $data['satuan']        = $detail->barang->satuan;
        $data['kategorirj']    = $detail->kategoriretur->nama;

        $data['qtympr']  = ($detail->qtympr) ? $detail->qtympr : 0;
        $data['qtynrj']  = ($detail->qtynrj) ? $detail->qtynrj : 0;
        $data['qtysppb'] = ($detail->qtysppb) ? $detail->qtysppb : 0;

        $hrgdisc1 = (1-($detail->disc1/100)) * $detail->hrgsatuanbrutto;
        $hrgdisc2 = (1-($detail->disc2/100)) * $hrgdisc1;
        $hrgppn   = (1+($detail->ppn/100)) * $hrgdisc2;
        $data['hrgdisc1']        = $hrgdisc1;
        $data['hrgdisc2']        = $hrgdisc2;
        $data['hrgsatnetto']     = $hrgppn;
        $data['hrgsatnettompr']  = $hrgppn*$detail->qtympr;
        $data['hrgsatnettosppb'] = $hrgppn*$detail->qtysppb;
        $data['hrgsatnettonrj']  = $hrgppn*$detail->qtynrj;

        return response()->json($data);
    }

    public function cetaksppb(Request $req, $id)
    {
        $config = [
            'mode'                 => '',
            'format'               => [162.56,215.9],
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 36,
            'margin_bottom'        => 12,
            'margin_header'        => 5,
            'margin_footer'        => 5,
            'orientation'          => 'L',
            'title'                => 'Surat Permintaan Pengambilan Barang',
            'author'               => '',
            'watermark'            => '',
            'show_watermark'       => true,
            'show_watermark_image' => true,
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
        ];

        $retur = ReturPenjualan::find($id);
        if(!$retur->tglprintsppb){
            $retur->tglprintsppb = Carbon::now();
        }
        $retur->printsppb++;
        $retur->save();

        $pdf   = PDF::loadView('transaksi.returpenjualan.sppbpdf',['user'=>$req->user(),'retur'=>$retur],[],$config);

        return $pdf->stream('SPPB-'.$id.'.pdf');
    }

    public function cetaknrj(Request $req, $id)
    {
        $config = [
            'mode'                 => '',
            'format'               => [162.56,215.9],
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 36,
            'margin_bottom'        => 12,
            'margin_header'        => 5,
            'margin_footer'        => 5,
            'orientation'          => 'L',
            'title'                => 'Nota Retur Jual',
            'author'               => '',
            'watermark'            => '',
            'show_watermark'       => true,
            'show_watermark_image' => true,
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
        ];

        $retur = ReturPenjualan::find($id);
        if(!$retur->tglprintnrj){
            $retur->tglprintnrj = Carbon::now();
        }
        $retur->printnrj++;
        $retur->save();

        $pdf   = PDF::loadView('transaksi.returpenjualan.nrjpdf',['user'=>$req->user(),'retur'=>$retur],[],$config);

        return $pdf->stream('NRJ-'.$id.'.pdf');
    }

    private function nextnoprb()
    {
        $max_no  = ReturPenjualan::selectRaw("MAX((SUBSTRING(nompr FROM '[0-9]+'))::int) as max")->first()->max;
        $next_no = str_pad($max_no+1, 7, '0', STR_PAD_LEFT);

        return $next_no;
    }

    public function tambah(Request $req)
    {
        if ($req->session()->exists('subcabang')) {
            $subcabanguser = SubCabang::find($req->session()->get('subcabang'))->kodesubcabang;
            $subcabang     = $req->session()->get('subcabang');

            $ppn = AppSetting::where('recordownerid',$req->session()->get('subcabang'))->where('keyid','ppn')->first();
            return view('transaksi.returpenjualan.form', ['subcabang'=>$subcabang,'subcabanguser'=>$subcabanguser,'ppn'=>($ppn) ? $ppn->value : 0]);
        }else{
            return redirect()->route('returpenjualan.index');
        }
    }

    public function simpan(Request $req)
    {
        if($req->session()->exists('subcabang')) {
            // Create New Retur
            $retur = new ReturPenjualan;
            $retur->recordownerid             = $req->session()->get('subcabang');
            $retur->pengirimsubcabangid       = $req->session()->get('subcabang');
            $retur->omsetsubcabangid          = $req->c2id;
            $retur->nompr                     = strtoupper($req->nompr);
            $retur->tglmpr                    = Carbon::parse($req->tglmpr)->format("Y-m-d");
            // $retur->tglsppb                = Carbon::parse($req->tglsppb)->format("Y-m-d");
            // $retur->tglnotaretur           = $req->tglnotaretur;
            $retur->tokoid                    = $req->tokoid;
            // $retur->tglterimagudang        = $req->tglterimagudang;
            $retur->keterangan                = strtoupper($req->keterangan);
            // $retur->tglprintnrj            = $req->tglprintnrj;
            // $retur->printnrj               = $req->printnrj;
            // $retur->kartupiutangdetailid   = $req->kartupiutangdetailid;
            // $retur->isarowid               = $req->isarowid;
            $retur->createdby                 = strtoupper($req->user()->username);
            $retur->lastupdatedby             = strtoupper($req->user()->username);
            // $retur->tglsppb                = $req->tglsppb;
            $retur->karyawanidpenjualan       = $req->karyawanidpenjualan;
            // $retur->karyawanidstock        = $req->karyawanidstock;
            // $retur->printsppb              = $req->printsppb;
            // $retur->tglprintsppb           = $req->tglprintsppb;
            $retur->Expedisiid                = $req->expedisiid;
            $retur->karyawanidpengambilbarang = $req->karyawanidexpedisi;
            $retur->save();

            return response()->json(['success'=>true, 'returid'=>$retur->id]);
        }else{
            return response()->json(['success'=>false, 'message'=>'Retur Penjualan gagal ditambahkan. Sub Cabang kosong.']);
        }
    }

    public function simpanDetail(Request $req)
    {
        // Create New Retur
        $retur = ReturPenjualan::find($req->returid);
        $node  = array();

        if($retur && Carbon::parse($retur->tglsppb)->toDateString() == Carbon::now()->toDateString() && $retur->tglnotaretur == null) {
            $detail = new ReturPenjualanDetail();
            $detail->returpenjualanid     = $retur->id;
            $detail->tiperetur            = ($req->historis == 1) ? 'M' : 'T';
            if($req->historis == 1) {
                 $int = $req->npjdid;
                if(empty($int) or strcmp(preg_replace("/[^0-9,.]/", "", $int), $req->npjdid) != 0 )
                {
                    return response()->json([
                        'success' => false,
                        'msg' => "Nota penjualan belum di pilih!"
                    ]);

                }else{
                    $detail->notapenjualandetailid = intval((int)$req->npjdid);
                }

                // if (!is_int($req->npjdid)) {
                //     return response()->json([
                //         'success' => false,
                //         'msg' => "Nota penjualan belum di pilih!"
                //     ]);

                // } else $detail->notapenjualandetailid = intval($req->npjdid);
            }

            $detail->stockid              = $req->barangid;
            $detail->qtympr               = $req->qtympr;
            // $detail->qtysppb              = $req->qtysppb;
            // $detail->qtynrj               = $req->qtynrj;
            $detail->hrgsatuanbrutto      = is_numeric($req->hrgsatuanbrutto) ? floatval($req->hrgsatuanbrutto) : 0;
            if($req->input('catatan') != '') {
                $detail->keterangan           = strtoupper($req->catatan);    
            }else{
                $detail->keterangan           = NULL;
            }
            
            if($req->input('kategoriidrpj') != ''){
                $detail->kategoriidrpj        = $req->kategoriidrpj;    
            }else{
                $detail->kategoriidrpj        = NULL;
            }
            
            // $detail->koreksirjparentid = $req->koreksirjparentid;
            // $detail->newchildid        = $req->newchildid;
            // $detail->isarowid          = $req->isarowid;
            $detail->disc1                = $req->disc1 ? $req->disc1 : 0;
            $detail->disc2                = $req->disc2 ? $req->disc2 : 0;
            $detail->ppn                  = $req->ppn ? $req->ppn : 0 ;
            $detail->hrgsatuannetto       = $req->hrgsatuannetto;
            $detail->lastupdatedby        = strtoupper($req->user()->username);
            $detail->createdby            = strtoupper($req->user()->username);
            // dd($detail);
            $detail->save();

            $node = $this->getDataDetailNode($req->returid);
        }

        return response()->json([
            'success' => true,
            'node'    => $node,
        ]);
    }

    public function updatesppb(Request $req)
    {
        $retur = ReturPenjualan::find($req->updateid);

        // Update SPPB Retur
        $retur->tglsppb                   = Carbon::now();
        $retur->lastupdatedby             = strtoupper($req->user()->username);
        $retur->Expedisiid                = $req->expedisiid;
        $retur->karyawanidpengambilbarang = $req->karyawanidexpedisi;
        $retur->karyawanidpenjualan       = $req->karyawanidpenjualan;
        $retur->save();

        // Update Detail SPPB Retur
        foreach($req->updateiddetail as $key=>$val) {
            $detail = ReturPenjualanDetail::find($val);
            $detail->qtysppb       = $req->updateqtysppb[$key];
            // $detail->qtynrj     = $req->updateqtynrj[$key];
            $detail->kategoriidrpj = $req->updatekategoriidrpj[$key];
            $detail->lastupdatedby = strtoupper($req->user()->username);
            $detail->save();
        }

        return response()->json(['success' => true]);
    }

    public function updatenrj(Request $req)
    {
        // Numerator
        // $numerator = Numerator::find(Numerator::where('doc','NOMOR_NOTA_RJ')->value('id'));
        $numerator = Numerator::where('doc','NOMOR_NOTA_RJ')->first();
        $depan     = $numerator->depan;
        $lebar     = $numerator->lebar-strlen($numerator->depan);
        $nomor     = sprintf("%0".$lebar."d", $numerator->nomor);
        $numerator->nomor++;
        $numerator->save();

        // Update NRJ Retur
        $retur = ReturPenjualan::find($req->updateid);
        $retur->nonotaretur     = $depan.$nomor;
        $retur->tglnotaretur    = Carbon::now();
        $retur->lastupdatedby   = strtoupper($req->user()->username);
        $retur->karyawanidstock = $req->karyawanidstock;
        $retur->save();

        // Update Detail NRJ Retur
        foreach($req->updateiddetail as $key=>$val) {
            $detail = ReturPenjualanDetail::find($val);
            // $detail->qtysppb    = $req->updateqtysppb[$key];
            $detail->qtynrj        = $req->updateqtynrj[$key];
            $detail->kategoriidrpj = $req->updatekategoriidrpj[$key];
            $detail->lastupdatedby = strtoupper($req->user()->username);
            $detail->save();
        }

        return response()->json(['success' => true]);
    }

    public function hapusretur($returid)
    {
        $retur = ReturPenjualan::find($returid);
        $retur->delete();
        return true;
    }

    public function hapusdetail($returid)
    {
        $detail = ReturPenjualanDetail::find($returid);
        $detail->delete();
        return true;
    }

    public function simpanDetailKoreksi(Request $req)
    {
        // Numerator
        // $numerator = Numerator::find(Numerator::where('doc','KOREKSI_RETUR_PENJUALAN')->value('id'));
        $numerator = Numerator::where('doc','KOREKSI_RETUR_PENJUALAN')->first();
        $depan     = $numerator->depan;
        $lebar     = $numerator->lebar-strlen($numerator->depan);
        $nomor     = sprintf("%0".$lebar."d", $numerator->nomor);
        $numerator->nomor++;
        $numerator->save();

        // Data Lama
        $detaillama = ReturPenjualanDetail::find($req->koreksiidrpjd);
        $returlama  = $detaillama->returpenjualan;
        $kartupiutangid = $returlama->kartupiutangdetail->kartupiutangid;

        // Create New Retur
        $tgltransaksi = Carbon::now();
        $retur = $returlama->replicate();
        $retur->nonotaretur     = $depan.$nomor;
        $retur->tglnotaretur    = $tgltransaksi;
        $retur->karyawanidstock = $req->karyawanidstock;
        $retur->createdby       = strtoupper($req->user()->username);
        $retur->lastupdatedby   = strtoupper($req->user()->username);
        $retur->printnrj        = 0;
        $retur->printsppb       = 0;
        $retur->save();

        // New ID
        $id = $retur->id;

        // Kartu Piutang Detail Minus
        $kpdminus = new KartuPiutangDetail;
        $kpdminus->kartupiutangid = $kartupiutangid;
        $kpdminus->tgltrans   = Carbon::parse($tgltransaksi);
        $kpdminus->kodetrans  = $retur->nonotaretur;
        $kpdminus->nomtrans   = (($detaillama->qtynrj)*-1)*$detaillama->hrgsatuannetto;
        $kpdminus->uraian         = 'Koreksi Retur Penjualan';
        $kpdminus->createdby      = strtoupper($req->user()->username);
        $kpdminus->lastupdatedby  = strtoupper($req->user()->username);
        // $kpdminus->tgljtgiro = '';
        // $kpdminus->giroid    = '';
        $kpdminus->save();

        // Insert Detail Retur Minus
        $detailminus = $detaillama->replicate();
        $detailminus->returpenjualanid     = $id;
        $detailminus->koreksirjparentid    = $detaillama->id;
        $detailminus->kartupiutangdetailid = $kpdminus->id;
        $detailminus->tiperetur            = 'T'; // Auto non-historis
        $detailminus->qtynrj               = ($detaillama->qtynrj)*-1;
        $detailminus->keterangan           = strtoupper($req->catatan);
        $detailminus->createdby            = strtoupper($req->user()->username);
        $detailminus->lastupdatedby        = strtoupper($req->user()->username);
        $detailminus->save();

        // Kartu Piutang Detail
        $kpd = new KartuPiutangDetail;
        $kpd->kartupiutangid = $kartupiutangid;
        $kpd->tgltrans   = Carbon::parse($tgltransaksi);
        $kpd->kodetrans  = $retur->nonotaretur;
        $kpd->nomtrans   = $req->qtynrj*$detaillama->hrgsatuannetto;
        $kpd->uraian         = 'Koreksi Retur Penjualan';
        $kpd->createdby      = strtoupper($req->user()->username);
        $kpd->lastupdatedby  = strtoupper($req->user()->username);
        // $kpd->tgljtgiro = '';
        // $kpd->giroid    = '';
        $kpd->save();

        // Insert Detail Retur Seharusnya
        $detail = $detaillama->replicate();
        $detail->returpenjualanid     = $id;
        $detail->koreksirjparentid    = $detaillama->id;
        $detail->kartupiutangdetailid = $kpd->id;
        $detail->tiperetur            = 'T'; // Auto non-historis
        $detail->qtynrj               = $req->qtynrj;
        $detail->keterangan           = strtoupper($req->catatan);
        $detail->createdby            = strtoupper($req->user()->username);
        $detail->lastupdatedby        = strtoupper($req->user()->username);
        $detail->save();

        // Update NewchildID
        $detaillama->newchildid = $detail->id;
        $detaillama->save();

        return response()->json(['success'=>true]);
    }
}
