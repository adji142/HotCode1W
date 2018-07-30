<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use PDF;
use Mail;
use App\Models\Karyawan;
use App\Models\SubCabang;
use App\Models\AntarGudang;
use App\Models\AntarGudangDetail;
use App\Models\Numerator;
class AntarGudangV2Controller extends Controller
{
    //
    // =============================================
    // Author		: M Nur Halim
    // Create date	: 22 Jan 2018
    // Description	: Fitur Antar Gudang Versi 2
    // =============================================

    // =============================================
    // Modifier 1	: 
    // date			: 
    // Description	: 
    // =============================================
    
    protected function setNumerator($ownerid){
        $query = Numerator::selectRaw("depan, nomor, lebar")
                ->where("recordownerid", $ownerid)
                ->where("doc", "NOMOR_ANTAR_GUDANG")
                ->first();
        $format = str_pad((int)$query->nomor, (int)$query->lebar, '0', STR_PAD_LEFT);
        $kode = $query->depan.$format;
        return $kode;
    }

    protected $original_column = array(
        1 => "stk.antargudang.tglrqag",
        2 => "stk.antargudang.tglnotaag",
        3 => "stk.antargudang.tglkirim",
        4 => "stk.antargudang.tglterima",
        5 => "stk.antargudang.norqag",
        6 => "darisubcabang.kodesubcabang as darisubcabangnama",
        7 => "kesubcabang.kodesubcabang as kesubcabangnama",
    );

    public function index(Request $req){
        $checkers = Karyawan::where('recordownerid',$req->session()->get('subcabang'))->where('kodechecker','!=',null)->get();
        return view('transaksi.antargudangv2.index',compact('checkers'));
    }

    public function getData(Request $req){
        // gunakan permission dari indexnya aja
        // if(!$req->user()->can('antargudang.index')) {
        //     return response()->json([
        //         'draw'            => $req->draw,
        //         'recordsTotal'    => 0,
        //         'recordsFiltered' => 0,
        //         'data'            => [],
        //     ]);
        // }

        // jika lolos, tampilkan data
    	$req->session()->put('tglmulai', $req->tglmulai);
        $req->session()->put('tglselesai', $req->tglselesai);

        $filter_count = 0;
        $empty_filter = 0;

        $columns = array(
            0 => "stk.antargudang.tglrqag",
            1 => "stk.antargudang.tglnotaag",
            2 => "stk.antargudang.tglkirim",
            3 => "stk.antargudang.tglterima",
            4 => "stk.antargudang.norqag",
            5 => "darisubcabang.kodesubcabang as darisubcabangnama",
            6 => "kesubcabang.kodesubcabang as kesubcabangnama",
            7 => "stk.antargudang.id",
            8 => "stk.antargudang.printnotaag",
            9 => "stk.antargudang.printpilag"
        );

        for ($i=1; $i < 7; $i++) {
            if($req->custom_search[$i]['text'] == ''){
                $empty_filter++;
            }
        }
        
        $ags = AntarGudang::selectRaw(collect($columns)->implode(', '));
        $ags->leftJoin('mstr.subcabang as darisubcabang','stk.antargudang.subcabangidpengirim','=','darisubcabang.id');
        $ags->leftJoin('mstr.subcabang as kesubcabang','stk.antargudang.subcabangidpenerima','=','kesubcabang.id');
        $ags->where("stk.antargudang.recordownerid",$req->session()->get('subcabang'))->where("stk.antargudang.tglrqag",'>=',Carbon::parse($req->tglmulai))->where("stk.antargudang.tglrqag",'<=',Carbon::parse($req->tglselesai)->endOfDay());
        $total_data = $ags->count();
        if($empty_filter < 6){
            for ($i=1; $i < 7; $i++) {
                if($req->custom_search[$i]['text'] != ''){
                    $index = $i;
                    if($index > 1){
                        if($req->custom_search[$i]['filter'] == '='){
                            $ags->where($this->original_column[$index],'ilike','%'.$req->custom_search[$i]['text'].'%');
                        }else{
                            $ags->where($this->original_column[$index],'not ilike','%'.$req->custom_search[$i]['text'].'%');
                        }
                    }else{
                    	$ags->where($this->original_column[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                    }
                    $filter_count++;
                }
            }
        }
        if($filter_count > 0){
            $filtered_data = $ags->count();
        }else{
            $filtered_data = $total_data;
        }
        if($req->tipe_edit){
            $ags->orderBy('stk.antargudang.lastupdatedon','desc');
        }else{
            if(array_key_exists($req->order[0]['column'], $this->original_column)){
                $ags->orderByRaw($this->original_column[$req->order[0]['column']].' '.$req->order[0]['dir']);
            }
        }
        if($req->start > $filtered_data){
            $ags->skip(0)->take($req->length);
        }else{
            $ags->skip($req->start)->take($req->length);
        }

        $data = array();
        foreach ($ags->get() as $key => $ag) {
            $delete = 'auth';
            $update = 'auth';
            $insert = 'auth';
            $pengiriman = 'auth';

            $ag->tglrqag = ($ag->tglrqag) ? Carbon::parse($ag->tglrqag)->format("d-m-Y") : "";
            $ag->tglnotaag = ($ag->tglnotaag) ? Carbon::parse($ag->tglnotaag)->format("d-m-Y") : "";
            $ag->tglterima = ($ag->tglterima) ? Carbon::parse($ag->tglterima)->format("d-m-Y") : "";
            $data[$key] = $ag->toArray();

            if ($ag->checkdetails != null){
                $delete = 'Tidak bisa hapus record. Sudah ada record di Antar Gudang Detail. Hubungi Manager Anda!';
            }

            if ($ag->tglnotaag != null){
                if (Carbon::parse($ag->tglnotaag)->toDateString() != Carbon::now()->toDateString()){
                    $update = "Tidak bisa update record. Tgl. Nota AG sudah terisi dan nilainya lebih kecil dari tanggal server. Hubungi Manager Anda!";
                }
                if ($ag->tglkirim != null){
                    $pengiriman = "Tidak bisa tambah record pengiriman. Tanggal Nota AG harus terisi dan tanggal kirim harus kosong. Hubungi Manager Anda!";
                }
                $insert = "Tidak bisa tambah record detail. Tanggal Nota AG sudah terisi. Hubungi Manager Anda!";
            }else{
                $pengiriman = "Tidak bisa tambah record pengiriman. Tanggal Nota AG belum terisi. Hubungi Manager Anda!";
                //$update = "Tidak bisa update record. Tgl. Nota AG belum terisi. Hubungi Manager Anda!";
            }
            
            $data[$key]['insert'] = $insert;
            $data[$key]['update'] = $update;
            $data[$key]['delete'] = $delete;
            $data[$key]['pengiriman'] = $pengiriman;

        }

        return response()->json([
            'draw'              => $req->draw,
            'recordsTotal'      => $total_data,
            'recordsFiltered'   => $filtered_data,
            'data'              => $data,
        ]);
    }

    public function getDataDetail(Request $req){
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('antargudang.index')) {
            return response()->json([
                'data' => [],
            ]);
        }

        $columns = array(
            0 => "stk.antargudangdetail.id",
            1 => "mstr.stock.namabarang",
            2 => "mstr.stock.satuan",
            3 => "stk.antargudangdetail.qtyrqag",
            4 => "stk.antargudangdetail.qtynotaag",
            5 => "stk.antargudang.tglnotaag",
            6 => "stk.antargudang.printpilag"
        );

        $query = AntarGudangDetail::selectRaw(collect($columns)->implode(", "))
            ->leftJoin("stk.antargudang", "stk.antargudang.id", "stk.antargudangdetail.antargudangid")
            ->leftJoin("mstr.stock","mstr.stock.id",'=','stk.antargudangdetail.stockid')
            ->where("stk.antargudangdetail.antargudangid",$req->id);
        
        $data = array();
        foreach ($query->get() as $key => $val) {
            $val->namabarang = str_replace("\'","'", $val->namabarang);
            $data[$key] = $val->toArray();
            if ($val->tglnotaag != null){
                $data[$key]["delete"] =  "Tidak bisa hapus record detail. Tanggal Nota AG sudah terisi. Hubungi Manager Anda!";
            }else{
                $data[$key]["delete"] =  "auth";
            }
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    public function getHeader(Request $req){
        $columns = array(
            0 => "stk.antargudang.id",
            1 => "to_char(stk.antargudang.tglrqag::date, 'DD-MM-YYYY') as tglrqag",
            2 => "to_char(stk.antargudang.tglnotaag::date, 'DD-MM-YYYY') as tglnotaag",
            3 => "to_char(stk.antargudang.tglkirim::date, 'DD-MM-YYYY') as tglkirim",
            4 => "to_char(stk.antargudang.tglterima::date, 'DD-MM-YYYY') as tglterima",
            5 => "stk.antargudang.norqag",
            6 => "darisubcabang.kodesubcabang as darisubcabangnama",
            7 => "kesubcabang.kodesubcabang as kesubcabangnama",
            8 => "pengirim1.namakaryawan as checkerpengirim1",
            9 => "pengirim2.namakaryawan as checkerpengirim2",
            10 => "penerima1.namakaryawan as checkerpenerima1",
            11 => "penerima2.namakaryawan as checkerpenerima2",
            12 => "stk.antargudang.catatan",
            13 => "date_trunc('second', stk.antargudang.tglprintnotaag) as tglprintnotaag",
            14 => "date_trunc('second', stk.antargudang.tglprintpilag) as tglprintpilag",
            15 => "karyawanpengirim.namakaryawan as stockpengirim",
            16 => "karyawanpenerima.namakaryawan as stockpenerima",
            17 => "stk.antargudang.printnotaag",
            18 => "stk.antargudang.printpilag",
            19 => "date_trunc('second', stk.antargudang.lastupdatedon) as lastupdatedon",
            20 => "stk.antargudang.lastupdatedby",
            21 => "stk.antargudang.karyawanidpengirim",
            22 => "stk.antargudang.karyawanidpenerima",
            23 => "stk.antargudang.karyawanidchecker1pengirim",
            24 => "stk.antargudang.karyawanidchecker2pengirim",
            25 => "stk.antargudang.karyawanidchecker1penerima",
            26 => "stk.antargudang.karyawanidchecker2penerima",
            27 => "stk.antargudang.subcabangidpengirim",
            28 => "stk.antargudang.subcabangidpenerima"
        );

        $query = AntarGudang::selectRaw(collect($columns)->implode(", "))
            ->leftJoin('mstr.subcabang as darisubcabang','stk.antargudang.subcabangidpengirim','=','darisubcabang.id')
            ->leftJoin('mstr.subcabang as kesubcabang','stk.antargudang.subcabangidpenerima','=','kesubcabang.id')
            ->leftJoin('hr.karyawan as pengirim1','stk.antargudang.karyawanidchecker1pengirim','=','pengirim1.id')
            ->leftJoin('hr.karyawan as pengirim2','stk.antargudang.karyawanidchecker2pengirim','=','pengirim2.id')
            ->leftJoin('hr.karyawan as penerima1','stk.antargudang.karyawanidchecker1penerima','=','penerima1.id')
            ->leftJoin('hr.karyawan as penerima2','stk.antargudang.karyawanidchecker2penerima','=','penerima2.id')
            ->leftJoin('hr.karyawan as karyawanpengirim','stk.antargudang.karyawanidpengirim','=','karyawanpengirim.id')
            ->leftJoin('hr.karyawan as karyawanpenerima','stk.antargudang.karyawanidpenerima','=','karyawanpenerima.id')
            ->where("stk.antargudang.id", $req->id)
            ->first();
        
        $subcabangid = SubCabang::find($req->session()->get("subcabang"))->kodesubcabang;

        if ($subcabangid == $query->darisubcabangnama){
            $query["notaupdatetipe"] = "pengirim";
            $query["notaupdatetglnotaag"] = Carbon::now()->format("d-m-Y");
            $query["notaupdatetglterima"] = $query->tglterima;
        }else{
            $query["notaupdatetipe"] = "penerima";
            $query["notaupdatetglnotaag"] = $query->tglnotaag;
            $query["notaupdatetglterima"] = Carbon::now()->format("d-m-Y");
        }

        return json_encode($query);
    }

    public function getDetail(Request $req){
        $columns = array(
            0 => "stk.antargudangdetail.id",
            1 => "mstr.stock.namabarang",
            2 => "stk.antargudangdetail.qtyrqag",
            3 => "stk.antargudangdetail.qtynotaag",
            4 => "stk.antargudangdetail.catatan",
            5 => "date_trunc('second', stk.antargudangdetail.lastupdatedon) as lastupdatedon",
            6 => "stk.antargudangdetail.lastupdatedby",
            7 => "stk.antargudangdetail.nokoli"
        );
        $query = AntarGudangDetail::selectRaw(collect($columns)->implode(", "))
            ->leftJoin("mstr.stock","mstr.stock.id","stk.antargudangdetail.stockid")
            ->where("stk.antargudangdetail.id", $req->id)
            ->first();
        return json_encode($query);
    }

    public function tambah(Request $req)
    {
        if ($req->session()->exists("subcabang")){
            $subcabanguser = SubCabang::find($req->session()->get('subcabang'))->kodesubcabang;
            $subcabangid = SubCabang::find($req->session()->get("subcabang"))->id;
            return view('transaksi.antargudangv2.form', ['numerator'=>$this->setNumerator($req->session()->get('subcabang')), 'subcabanguser'=>$subcabanguser, 'subcabangid' => $subcabangid]);
        }else{
            return redirec()->route("antargudangv2.index");
        }
    }

    public function insertData(Request $req){
        $blnsuccess = false;
        $subcabangidpengirim = $req->session()->get('subcabang');

        $query = new AntarGudang();
        $query->subcabangidpengirim = $subcabangidpengirim;
        $query->subcabangidpenerima = $req->gudangid;
        $query->catatan = $req->catatan;
        $query->createdby = strtoupper(auth()->user()->username);
        $query->lastupdatedby = strtoupper(auth()->user()->username);
        $query->tglrqag = Carbon::now();
        $query->norqag = $this->setNumerator($req->session()->get('subcabang'));
        $query->recordownerid = $req->session()->get('subcabang');
        $query->save();

        $queryNumerator = Numerator::where("doc", "NOMOR_ANTAR_GUDANG")
            ->where("recordownerid", $req->session()->get('subcabang'))->first();
        $queryNumerator->nomor = $queryNumerator->nomor + 1;
        $queryNumerator->lastupdatedby = strtoupper(auth()->user()->username);
        $queryNumerator->save();

        $antargudangid = $query->id;

        $blnsuccess = true;

        return response()->json(array('success' => $blnsuccess, 'antargudangid' => $antargudangid));
    }

    public function checkStockExists(Request $req){
        $query = AntarGudangDetail::where("stk.antargudangdetail.antargudangid", $req->antargudangid)
            ->where("stk.antargudangdetail.stockid", $req->stockid)
            ->where("stk.antargudangdetail.antargudangid", $req->antargudangid)
            ->get();
        $blnsuccess = false;
        if (count($query) > 0){ $blnsuccess = true; }
        return response()->json(array('success' => $blnsuccess));
    }

    public function updatePengiriman(Request $req){
        $query = AntarGudang::find($req->pengirimanid);
        $query->karyawanidsopir = $req->sopirid;
        $query->karyawanidhelper = $req->kernetid;
        $query->tglkirim = Carbon::now()->toDateString();
        $query->lastupdatedby = strtoupper(auth()->user()->username);
        $query->save();
        return response()->json(array('success' => true));
    }

    public function updateNota(Request $req){
        $query = AntarGudang::find($req->notaupdateid);
        // $query->norqag = $req->norqag;
        // $query->tglrqag = $req->tglrqag;
        // $query->subcabangidpengirim = $req->subcabangidpengirim;
        // $query->subcabangidpenerima = $req->subcabangidpenerima;
        if (strtolower($req->notaupdatetipe) == "pengirim")
        {
            $query->tglnotaag = Carbon::now()->toDateString();
            $query->karyawanidpengirim = $req->pengirimid;
            $query->karyawanidchecker1pengirim = $req->checker1id;
            $query->karyawanidchecker2pengirim = $req->checker2id;
        }else{
            $query->tglterima = Carbon::now()->toDateString();
            $query->karyawanidpenerima = $req->penerimaid;
            $query->karyawanidchecker1penerima = $req->pemeriksaid;
            $query->karyawanidchecker2penerima = $req->pemeriksa00id;
        }
        $query->save();
        return response()->json(array("success" => true));
    }

    public function insertDetail(Request $req){
        $blnsuccess = false;
        $detail = AntarGudangDetail::selectRaw("stk.antargudangdetail.id")
            ->where("stk.antargudangdetail.stockid", $req->barangid)
            ->where("stk.antargudangdetail.antargudangid", $req->antargudangid);
        if ($detail->count() > 0){
            $id = $detail->first()->id;


            $query = AntarGudangDetail::find($id);
            $query->qtyrqag = $query->qtyrqag + $req->qtyrqag;
            if ($req->catatandetail != ''){$query->catatan = $req->catatandetail;}
            $query->lastupdatedby = strtoupper(auth()->user()->username);
            $query->save();
            $blnsuccess = true;
        }else{
            $query = new AntarGudangDetail();
            $query->antargudangid = $req->antargudangid;
            $query->stockid = $req->barangid;
            $query->qtyrqag = $req->qtyrqag;
            $query->catatan = $req->catatandetail;
            $query->createdby = strtoupper(auth()->user()->username);
            $query->lastupdatedby = strtoupper(auth()->user()->username);
            $query->save();
            $blnsuccess = true;
        }
        return response()->json(array('success'=> $blnsuccess));
    }

    public function Kewenangan(Request $req){
        $blnsuccess = false;
        $message = "";

        $lastUserId = auth()->user()->id;
        if(auth()->attempt(['username'=>$req->username,'password'=>$req->password]))
        {
            auth()->loginUsingId($lastUserId);
            if ($req->tipe == 'header'){
                if(auth()->user()->can($req->permission) && $req->permission == 'antargudangv2.hapus')
                {
                    $header = AntarGudang::find($req->aghapusid);
                    $header->delete();
                    $message = "Record Antar Gudang berhasil dihapus.";
                    $blnsuccess = true;
                }
            }else if ($req->tipe == 'detail'){
                if(auth()->user()->can($req->permission) && $req->permission == 'antargudangv2.detail.hapus')
                {
                    $detail = AntarGudangDetail::find($req->aghapusid);
                    $detail->delete();
                    $message = "Record Antar Gudang Detail berhasil dihapus.";
                    $blnsuccess = true;
                }
            }else if ($req->tipe == 'cetakpil'){
                if(auth()->user()->can($req->permission) && $req->permission == 'antargudangv2.cetakpil')
                {
                    $blnsuccess = true;
                    $message = "Print PiL AG berhasil diperbaharui.";
                }
            }else if ($req->tipe == 'cetaknota'){
                if(auth()->user()->can($req->permission) && $req->permission == 'antargudangv2.cetaknota')
                {
                    $blnsuccess = true;
                    $message = "Print Nota AG berhasil diperbaharui.";
                }
            }else if ($req->tipe == 'tambah'){
                if(auth()->user()->can($req->permission) && $req->permission == 'antargudangv2.detail.tambah')
                {
                    $blnsuccess = true;
                    $message = 'auth';
                }
            }

        }
        return response()->json(array("success" => $blnsuccess, "message" => $message));
    }

    public function cetakPil(Request $req)
    {
        $columns = array(
            0 => "stk.antargudang.id",
            1 => "to_char(stk.antargudang.tglrqag::date, 'DD-MM-YYYY') as tglrqag",
            2 => "to_char(stk.antargudang.tglnotaag::date, 'DD-MM-YYYY') as tglnotaag",
            3 => "stk.antargudang.norqag",
            4 => "darisubcabang.kodesubcabang as darisubcabangnama",
            5 => "kesubcabang.kodesubcabang as kesubcabangnama",
            6 => "pengirim1.namakaryawan as checkerpengirim1",
            7 => "pengirim2.namakaryawan as checkerpengirim2",
            8 => "penerima1.namakaryawan as checkerpenerima1",
            9 => "penerima2.namakaryawan as checkerpenerima2",
            10 => "karyawanpengirim.namakaryawan as stockpengirim",
            11 => "karyawanpenerima.namakaryawan as stockpenerima",
            12 => "sopir.namakaryawan as namasopir",
            13 => "helper.namakaryawan as namahelper"
        );

        $query = AntarGudang::selectRaw(collect($columns)->implode(", "))
            ->leftJoin('mstr.subcabang as darisubcabang','stk.antargudang.subcabangidpengirim','=','darisubcabang.id')
            ->leftJoin('mstr.subcabang as kesubcabang','stk.antargudang.subcabangidpenerima','=','kesubcabang.id')
            ->leftJoin('hr.karyawan as pengirim1','stk.antargudang.karyawanidchecker1pengirim','=','pengirim1.id')
            ->leftJoin('hr.karyawan as pengirim2','stk.antargudang.karyawanidchecker2pengirim','=','pengirim2.id')
            ->leftJoin('hr.karyawan as penerima1','stk.antargudang.karyawanidchecker1penerima','=','penerima1.id')
            ->leftJoin('hr.karyawan as penerima2','stk.antargudang.karyawanidchecker2penerima','=','penerima2.id')
            ->leftJoin('hr.karyawan as karyawanpengirim','stk.antargudang.karyawanidpengirim','=','karyawanpengirim.id')
            ->leftJoin('hr.karyawan as karyawanpenerima','stk.antargudang.karyawanidpenerima','=','karyawanpenerima.id')
            ->leftJoin('hr.karyawan as sopir','stk.antargudang.karyawanidsopir','=','sopir.id')
            ->leftJoin('hr.karyawan as helper','stk.antargudang.karyawanidhelper','=','helper.id')
            ->where("stk.antargudang.id", $req->id)
            ->first();

        $format = [215.9,162.56];
        $title = "PIL AG";
        $view = 'transaksi.antargudangv2.cetakpil';
        $config = [
            'mode'                 => '',
            'format'               => $format,
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 40,
            'margin_bottom'        => 8,
            'margin_header'        => 0,
            'margin_footer'        => 5,
            'orientation'          => 'P',
            'title'                => $title,
            'author'               => '',
            'watermark'            => '',
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
            'show_watermark_image' => true,
            'show_watermark'       => true,
        ];

        $pdf = PDF::loadView($view,[
            'ag' => $query,
        ],[],$config);

        $ag  = AntarGudang::find($req->id);
        $ag->tglprintpilag = Carbon::now()->toDateString();
        if ($ag->printpilag){
            $ag->printpilag = $ag->printpilag + 1;
        }else{
            $ag->printpilag = 1;
        }
        $ag->save();

        return $pdf->stream('AG_PIL-'.$ag->norqag.'.pdf');
    }

    public function cetakNota(Request $req)
    {
        $columns = array(
            0 => "stk.antargudang.id",
            1 => "to_char(stk.antargudang.tglrqag::date, 'DD-MM-YYYY') as tglrqag",
            2 => "to_char(stk.antargudang.tglnotaag::date, 'DD-MM-YYYY') as tglnotaag",
            3 => "stk.antargudang.norqag",
            4 => "darisubcabang.kodesubcabang as darisubcabangnama",
            5 => "kesubcabang.kodesubcabang as kesubcabangnama",
            6 => "pengirim1.namakaryawan as checkerpengirim1",
            7 => "pengirim2.namakaryawan as checkerpengirim2",
            8 => "penerima1.namakaryawan as checkerpenerima1",
            9 => "penerima2.namakaryawan as checkerpenerima2",
            10 => "karyawanpengirim.namakaryawan as stockpengirim",
            11 => "karyawanpenerima.namakaryawan as stockpenerima",
            12 => "sopir.namakaryawan as namasopir",
            13 => "helper.namakaryawan as namahelper"
        );

        $query = AntarGudang::selectRaw(collect($columns)->implode(", "))
            ->leftJoin('mstr.subcabang as darisubcabang','stk.antargudang.subcabangidpengirim','=','darisubcabang.id')
            ->leftJoin('mstr.subcabang as kesubcabang','stk.antargudang.subcabangidpenerima','=','kesubcabang.id')
            ->leftJoin('hr.karyawan as pengirim1','stk.antargudang.karyawanidchecker1pengirim','=','pengirim1.id')
            ->leftJoin('hr.karyawan as pengirim2','stk.antargudang.karyawanidchecker2pengirim','=','pengirim2.id')
            ->leftJoin('hr.karyawan as penerima1','stk.antargudang.karyawanidchecker1penerima','=','penerima1.id')
            ->leftJoin('hr.karyawan as penerima2','stk.antargudang.karyawanidchecker2penerima','=','penerima2.id')
            ->leftJoin('hr.karyawan as karyawanpengirim','stk.antargudang.karyawanidpengirim','=','karyawanpengirim.id')
            ->leftJoin('hr.karyawan as karyawanpenerima','stk.antargudang.karyawanidpenerima','=','karyawanpenerima.id')
            ->leftJoin('hr.karyawan as sopir','stk.antargudang.karyawanidsopir','=','sopir.id')
            ->leftJoin('hr.karyawan as helper','stk.antargudang.karyawanidhelper','=','helper.id')
            ->where("stk.antargudang.id", $req->id)
            ->first();

		$format = [215.9,162.56];
        $title = "NOTA AG";
        $view = 'transaksi.antargudangv2.cetaknota';
        $config = [
            'mode'                 => '',
            'format'               => $format,
            'default_font_size'    => '9',
            'default_font'         => 'sans-serif',
            'margin_left'          => 8,
            'margin_right'         => 8,
            'margin_top'           => 40,
            'margin_bottom'        => 8,
            'margin_header'        => 0,
            'margin_footer'        => 5,
            'orientation'          => 'P',
            'title'                => $title,
            'author'               => '',
            'watermark'            => '',
            'watermark_font'       => 'sans-serif',
            'display_mode'         => 'fullpage',
            'watermark_text_alpha' => 0.2,
            'show_watermark_image' => true,
            'show_watermark'       => true,
        ];

        
        $pdf = PDF::loadView($view,[
            'ag' => $query,
        ],[],$config);

        $ag  = AntarGudang::find($req->id);
        $ag->tglprintnotaag = Carbon::now()->toDateString();
        if ($ag->printnotaag){
            $ag->printnotaag = $ag->printnotaag + 1;
        }else{
            $ag->printnotaag = 1;
        }
        $ag->save();

        return $pdf->stream('AG_NOTA-'.$ag->norqag.'.pdf');
    }
    
}
