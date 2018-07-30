<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tokodraft;
use App\Models\Toko;
use App\Models\Tokohakakses;
use App\Models\Tokodrafthistory;
use App\Models\TokoUpdateHistory;
use App\Models\Kecamatan;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\SubCabang;
use DB;

class TokodraftController extends Controller
{

    public function index()
    {
        return view('master.tokodraft.index');
    }

    public function getData(Request $req)
    {
        // gunakan permission dari indexnya aja
        if(!$req->user()->can('tokodraft.index')) {
            return response()->json([
                'draw'            => $req->draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
            ]);
        }

        //$req->session()->put('tipestatus', $req->tipestatus);
        $filter_count = 0;
        $empty_filter = 0;

        // Models
        $columns = [
            //1 => "case when (mstr.tokodraft.statusacc = 'APPROVED') then 'A' else '-' end as statusacc",
            1 => 'mstr.tokodraft.statusacc ',
            2 => 'mstr.tokodraft.tokodraftID ',
            3 => 'mstr.tokodraft.namatoko ',
            4 => 'mstr.tokodraft.toko00 ',
            5 => 'mstr.tokodraft.alamat ',
            6 => 'mstr.tokodraft.kota ',
            7 => 'mstr.tokodraft.telp ',
            8 => 'mstr.tokodraft.penanggungjawab ',
            9 => 'mstr.tokodraft.kodetoko ',
            10 => 'mstr.tokodraft.daerah ',
            11 => 'mstr.tokodraft.piutangb ',
            12 => 'mstr.tokodraft.piutangj ',
            13 => 'mstr.tokodraft.plafon ',
            14 => 'mstr.tokodraft.classid ',
            15 => 'mstr.tokodraft.tojual ',
            16 => 'mstr.tokodraft.toretpot ',
            17 => 'mstr.tokodraft.jangkawaktukredit ',
            18 => 'mstr.tokodraft.tgldob ',
            19 => 'mstr.tokodraft.thnberdiri ',
            20 => 'mstr.tokodraft.statusruko ',
            21 => 'mstr.tokodraft.fax ',
            22 => 'mstr.tokodraft.bangunan ',
            23 => 'mstr.tokodraft.habis_kontrak ',
            24 => 'mstr.tokodraft.pemilik ',
            25 => 'mstr.tokodraft.no_ktp ',
            26 => 'mstr.tokodraft.jmlcabang ',
            27 => 'mstr.tokodraft.gender ',
            28 => 'mstr.tokodraft.tempatlahir ',
            29 => 'mstr.tokodraft.email ',
            30 => 'mstr.tokodraft.norekening ',
            31 => 'mstr.tokodraft.namabank ',
            32 => 'mstr.tokodraft.no_member ',
            33 => 'mstr.tokodraft.hobi ',
            34 => 'mstr.tokodraft.nonpwp ',
            35 => 'mstr.tokodraft.jmlsales ',
            36 => 'mstr.tokodraft.catatan ',
            37 => 'mstr.tokodraft.harikirim ',
            38 => 'mstr.tokodraft.kodepos ',
            39 => 'mstr.tokodraft.grade ',
            40 => 'mstr.tokodraft.plafon1st ',
            41 => 'mstr.tokodraft.bentrok ',
            42 => 'mstr.tokodraft.statusaktif ',
            43 => 'mstr.tokodraft.harisales ',
            44 => 'mstr.tokodraft.alamatrumah ',
            45 => 'mstr.tokodraft.pengelola ',
            46 => 'mstr.tokodraft.tgllahir ',
            47 => 'mstr.tokodraft.hp ',
            48 => 'mstr.tokodraft.kinerja ',
            49 => 'mstr.tokodraft.tipebisnis ',
            50 => 'mstr.tokodraft.i_spart ',
            51 => 'mstr.tokodraft.lastupdatedby ',
            52 => 'mstr.tokodraft.lastupdatedon ',
            53 => 'mstr.tokodraft.jenis_produk ',
            54 => 'mstr.tokodraft.kecamatan ',
            55 => 'mstr.tokodraft.namakabkota ',
            56 => 'mstr.tokodraft.rowid ',
          ];

        $modelObj = Tokodraft::selectRaw(collect($columns)->implode(', '));
        /*if($req->tipestatus) {
            $modelObj->where("mstr.tokodraft.statusacc",$req->tipestatus);
        }*/
        $total_data = $modelObj->count();

        if($empty_filter < 56){
            for ($i=1; $i < 56; $i++) {
              if ($req->custom_search[$i]['text']!='') {
                //$index = $i;
                /*if($index == 2 ){
                    $modelObj->where($columns[$index],$req->custom_search[$i]['filter'],$req->custom_search[$i]['text']);
                }else{*/
                    if($req->custom_search[$i]['filter'] == '='){
                        $modelObj->where(DB::raw($columns[$i] . '::VARCHAR'),'ilike','%'.$req->custom_search[$i]['text'].'%');
                    }else{
                        $modelObj->where(DB::raw($columns[$i] . '::VARCHAR'),'not ilike','%'.$req->custom_search[$i]['text'].'%');
                    }
                  
                //}
                $filter_count++;
              }
            }
        }

        if($filter_count > 0){
            $filtered_data = $modelObj->count();
        }else{
            $filtered_data = $total_data;
        }

        $modelObj->orderBy('mstr.tokodraft.namatoko','asc');

        if($req->start > $filtered_data){
            $modelObj->skip(0)->take($req->length);
        }else{
            $modelObj->skip($req->start)->take($req->length);
        }

        // Data
        $data = [];
        foreach ($modelObj->get($columns) as $k => $val) {
            $data[$k] = $val->toArray();

            $action = '';
            if($val->statusacc == 'APPROVED'){
                $action .= "<div class='btn btn-xs btn-success  no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='Toko APPROVED'><i class='fa fa-check'></i></div>";
            } else{
                if($req->user()->can('tokodraft.tdhistory')){
                  $action .= "<a onclick='tampilHistory(this)' class='btn btn-xs btn-success no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='History Toko Draft'><i class='fa fa-history'></i></a>";
                }else{
                  $action .= "<div class='btn btn-xs btn-success no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='History Toko Draft' onclick=\"this.blur(); swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;\"><i class='fa fa-history'></i></div>";
                }

                if($req->user()->can('tokodraft.hapus')){
                  $action .= "<div class='btn btn-xs btn-danger no-margin-action skeyF3' data-toggle='tooltip' data-placement='bottom' title='Hapus Toko Draft' onclick='hapus(this)'><i class='fa fa-trash'></i></div>";
                }else{
                  $action .= "<div class='btn btn-xs btn-danger no-margin-action skeyF3' data-toggle='tooltip' data-placement='bottom' title='Hapus Toko Draft' onclick=\"this.blur(); swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;\"><i class='fa fa-trash'></i></div>";
                }

                if($req->user()->can('tokodraft.tddetail')){
                  $action .= "<a data-toggle='tooltip' onclick='tampilDetail(this)' class='btn btn-xs btn-info no-margin-action skeyF2' data-placement='bottom' title='Detail Toko Draft'><i class='fa fa-eye'></i></a>";
                }else{
                  $action .= "<div class='btn btn-xs btn-info no-margin-action skeyF2' data-toggle='tooltip' data-placement='bottom' title='Detail Toko Draft' onclick=\"this.blur(); swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;\"><i class='fa fa-eye'></i></div>";
                }

                if($req->user()->can('tokodraft.approve')){
                  $action .= "<div class='btn-group'><button data-toggle='dropdown' class='btn btn-xs btn-primary dropdown-toggle btn-sm' type='button' aria-expanded='false'>A <span class='caret'></span>
                      </button><ul role='menu' class='dropdown-menu'>
                        <li><a data-toggle='tooltip' onclick='tambahApprove(this)' data-placement='bottom' class='open-modal'>APPROVED</a>
                        </li>
                        <li><a data-toggle='tooltip' onclick='tambahReject(this)' data-placement='bottom' class='open-modal'>REJECTED</a>
                        </li>
                      </ul></div>";
                }else{
                  $action .= "<div class='btn btn-xs btn-danger no-margin-action skeyF3' data-toggle='tooltip' data-placement='bottom' title='Hapus Toko Draft' onclick=\"this.blur(); swal('Ups!', 'Anda tidak memiliki HAK AKSES! Hubungi Manager Anda.','error'); return false;\"><i class='fa fa-trash'></i></div>";
                }
            }
            
            $data[$k]['action']   = $action;
            $data[$k]['DT_RowId'] = 'gv1_'.$val->id;
        }

        return response()->json([
            'draw'            => $req->draw,
            'recordsTotal'    => $total_data,
            'recordsFiltered' => $filtered_data,
            'data'            => $data,
        ]);
    }

    public function form($kodetoko = null)
    {
        $tokodraft  = Toko::where('kodetoko',$kodetoko)->first();
        $prov       = Provinsi::orderBy('namaprovinsi','asc')->pluck('namaprovinsi','id');
        $toko00     = SubCabang::orderBy('kodesubcabang','asc')->pluck('kodesubcabang');

        $query      = "SELECT sc.kodesubcabang FROM mstr.subcabang sc JOIN mstr.tokohakakses tha
                    ON sc.id = tha.recordownerid JOIN mstr.toko t
                    ON t.id = tha.tokoid WHERE t.kodetoko = '$kodetoko' ";
        $result     = DB::select($query);

        if($result){
            $kodecabang = $result[0]->kodesubcabang;
        } else{
            $kodecabang = NULL;
        }

        return view('master.tokodraft.form',['tokodraft' => $tokodraft, 'prov' => $prov, 'toko00' => $toko00, 'kodecabang' => $kodecabang]);
    }

    function uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    function buat_tokodraftID($kunci, $jumlah_karakter = 0)
    {
        $query = 'SELECT MIN(cast(t1.tokoidwarisan as int) + 1) AS nextid
                FROM mstr.toko t1
                   LEFT JOIN mstr.toko t2
                       ON cast(t1.tokoidwarisan as int) + 1 = cast(t2.tokoidwarisan as int)
                   LEFT JOIN mstr.tokodraft t3
                       ON cast(t1.tokoidwarisan as int) + 1 = cast(t3.tokodraftid as int)
                WHERE t2.tokoidwarisan IS NULL AND t3.tokodraftid IS NULL';
        $result = DB::select($query);

        $nomor_baru = $result[0]->nextid;
        $nomor_baru_plus_nol = str_pad($nomor_baru, $jumlah_karakter, "0", STR_PAD_LEFT);
        $kode = $kunci . $nomor_baru_plus_nol;

        return $kode;
    }

    public function simpan(Request $request){ 
        
        $rowid                   = $this->uuid();
        $tokoid                  = $this->buat_tokodraftID('', 7);

        $today                   = substr(date("YmdHisu"), 0, -4);
        $user                    = substr(auth()->user()->username,0,3);
        $kodetoko                = $today . $user;

        if($id = $request['kodetoko']){
             
            ///////////////////////////////////////////////////////////////////////////
            //Insert Toko Update History//
            ///////////////////////////////////////////////////////////////////////////
            $tk = Toko::where(DB::raw("trim(kodetoko)"), trim($id))->first();
            $tkh = new TokoUpdateHistory();
            $attr = $tk->getAttributes();
            foreach ($attr as $tr => $td) {
                switch ($tr) {
                    case 'id': case 'tosupdate':
                        // do nothing
                        break;
                        
                    case 'kodetoko':
                        $tkh->$tr = trim($td);
                        break;

                    default: $tkh->$tr = $td; break;
                }
            }
            $tkh->tglobsolete = date("Y-m-d H:i:s");
            $tkh->obsoleteby  = auth()->user()->username;
            $tkh->save();
            
            //End

            ///////////////////////////////////////////////////////////////////////////
            //Update Toko//
            ///////////////////////////////////////////////////////////////////////////
            $update = Toko::where('kodetoko',$id)->first();
            $update->namatoko                   = strtoupper($request['nmtoko']);
            $update->alamat                     = strtoupper($request['alamat']);
            $update->kota                       = $request['namakab'];
            $update->telp                       = $request['telp'];
            $update->propinsi                   = $request['namaprov'];
            $update->penanggungjawab            = strtoupper($request['penanggungjawab']);
            
            if($request['plafon'] == ""){
                $update->plafon                 = 0;
            } else{
                $update->plafon                 = str_replace(".", "", $request['plafon']);
            }

            $update->catatan                    = strtoupper($request['catatan']);
            $update->daerah                     = strtoupper($request['daerah']);
            $update->lastupdatedby              = auth()->user()->username;
            
            if($request['piutangB'] == ""){
                $update->piutangb               = 0;
            } else{
                $update->piutangb               = str_replace(".", "", $request['piutangB']);
            }

            if($request['piutangJ'] == ""){
                $update->piutangj               = 0;
            } else{
                $update->piutangj               = str_replace(".", "", $request['piutangJ']);
            }

            if($request['toJual'] == ""){
                $update->tojual                 = 0;
            } else{
                $update->tojual                 = str_replace(".", "", $request['toJual']);
            }

            if($request['toRetPot'] == ""){
                $update->toretpot               = 0;
            } else{
                $update->toretpot               = str_replace(".", "", $request['toRetPot']);
            }

            if($request['jangkawaktukredit'] == ""){
                $update->jangkawaktukredit      = 30;
            } else{
                $update->jangkawaktukredit      = $request['jangkawaktukredit'];
            }
            
            if ($request['tgl1st'] == "") {
                $update->tgldob                 = NULL;
            } else{
                $update->tgldob                 = date('Y-m-d', strtotime($request['tgl1st']));
            }

            $update->exist                      = $request['exist'];
            $update->classid                    = $request['classID'];
            
            if($request['harikirim'] == ""){
                $update->harikirim              = 0;
            } else{
                $update->harikirim              = $request['harikirim'];
            }

            $update->kodepos                    = $request['kodepos'];
            $update->grade                      = strtoupper($request['grade']);
            
            if($request['plafon1st'] == ""){
                $update->plafon1st              = 0;
            } else{
                $update->plafon1st              = str_replace(".", "", $request['plafon1st']);
            }

            //$update->StatusAktif                = $request['statusaktif'];
            
            if($request['harisales'] == ""){
                $update->harisales              = 0;
            } else{
                $update->harisales              = $request['harisales'];
            }

            $update->alamatrumah                = strtoupper($request['alamatrumah']);
            $update->pengelola                  = strtoupper($request['pengelola']);
            
            if ($request['tanggallahir'] == "") {
                $update->tgllahir               = NULL;
            } else{
                $update->tgllahir               = date('Y-m-d', strtotime($request['tanggallahir']));
            }

            $update->hp                         = $request['hp'];
            $update->thnberdiri                 = $request['thnberdiri'];
            $update->statusruko                 = $request['statusruko'];
            
            if($request['jmlcabang'] == ""){
                $update->jmlcabang              = 0;
            } else{
                $update->jmlcabang              = $request['jmlcabang'];
            }

            if($request['jmlsales'] == ""){
                $update->jmlsales               = 0;
            } else{
                $update->jmlsales               = $request['jmlsales'];
            }

            $update->kinerja                    = strtoupper($request['kinerja']);
            $update->tipebisnis                 = strtoupper($request['bidangusaha']);
            $update->i_spart                    = $request['I_spart'];
            $update->lastupdatedon              = date('Y-m-d H:i:s');
            $update->fax                        = $request['fax'];
            $update->bangunan                   = $request['bangunan'];
            
            if ($request['habis_kontrak'] == "") {
                $update->habis_kontrak          = NULL;
            } else{
                $update->habis_kontrak          = date('Y-m-d', strtotime($request['habis_kontrak']));
            }

            $update->jenis_produk               = $request['jenis_produk'];
            $update->pemilik                    = strtoupper($request['pemilik']);
            $update->gender                     = $request['gender'];
            $update->tempatlahir                = strtoupper($request['tempatlahir']);
            $update->email                      = strtoupper($request['email']);
            $update->norekening                 = $request['norekening'];
            $update->namabank                   = strtoupper($request['namabank']);
            $update->no_member                  = $request['no_member'];
            $update->hobi                       = strtoupper($request['hobi']);
            $update->nonpwp                     = $request['nonpwp'];
            $update->no_ktp                     = $request['no_ktp'];
            $update->kecamatan                  = $request['namakec'];
            $update->namakabkota                = strtoupper($request['namakab']);
            $update->customwilayah              = strtoupper($request['wilid']);

            //Upload Foto KTP
            if($request->hasFile('file_gambar')){
            $path = $request->file('file_gambar');
            $val = file_get_contents($path);
            $base64 = 'data:image/png;base64,' . base64_encode($val);
            $update->imagektp = $base64;
            }

            $update->update();

            //End

            ///////////////////////////////////////////////////////////////////////////
            //Update Toko Hak Akses//
            ///////////////////////////////////////////////////////////////////////////

            $isarowid = Toko::where('kodetoko',$id)->first();
            $cektoko00 = Tokohakakses::where('tokoid',$isarowid->id)->first();
            $toko00 = $request['toko00'];
            $roi = SubCabang::where('kodesubcabang',$toko00)->first();

            if($request['toko00']){
                if($cektoko00){
                    DB::table('mstr.tokohakakses')->where('tokoid',$isarowid->id)->update([
                        'recordownerid' => $roi->id,
                        'tglaktif'      => date('Y-m-d', strtotime($request['tgl1st'])),
                        'lastupdatedby' => auth()->user()->username,
                        'lastupdatedon' => date('Y-m-d H:i:s')
                    ]);
                    
                } else{
                    DB::table('mstr.tokohakakses')->insert([
                        'tokoid'        => $isarowid->id,
                        'recordownerid' => $roi->id,
                        'tglaktif'      => date('Y-m-d', strtotime($request['tgl1st'])),
                        'createdby'     => auth()->user()->username,
                        'createdon'     => date('Y-m-d H:i:s'),
                        'lastupdatedby' => auth()->user()->username,
                        'lastupdatedon' => date('Y-m-d H:i:s')
                    ]);
                    
                }
                  
            } else{
                $del = Tokohakakses::where('tokoid',$isarowid->id)->delete();
            }
            //End 

            $desc = "Toko telah sukses diubah.";
            //End

            return redirect()->route('toko.index')->with('message', ['status' => 'success','desc' => $desc]);

        } else {
             
            ///////////////////////////////////////////////////////////////////////////
            //Insert Toko Draft//
            ///////////////////////////////////////////////////////////////////////////

            $data = new Tokodraft();
            $data->rowid                        = $rowid;
            $data->tokodraftid                  = $tokoid;
            $data->namatoko                     = strtoupper($request['nmtoko']);
            $data->alamat                       = strtoupper($request['alamat']);
            $data->kota                         = $request['namakab'];
            $data->telp                         = $request['telp'];
            $data->propinsi                     = $request['namaprov'];
            $data->penanggungjawab              = strtoupper($request['penanggungjawab']);

            if($request['plafon'] == ""){
                $data->plafon                   = 0;
            } else{
                $data->plafon                   = str_replace(".", "", $request['plafon']);
            }
            
            $data->catatan                      = strtoupper($request['catatan']);
            $data->daerah                       = strtoupper($request['daerah']);
            $data->createdby                    = auth()->user()->username;
            $data->createdon                    = date('Y-m-d H:i:s');
            $data->lastupdatedby                = auth()->user()->username;
            $data->lastupdatedon                = date('Y-m-d H:i:s');
            $data->kodetoko                     = $kodetoko;

            if($request['piutangB'] == ""){
                $data->piutangb                 = 0;
            } else{
                $data->piutangb                 = str_replace(".", "", $request['piutangB']);
            }

            if($request['piutangJ'] == ""){
                $data->piutangj                 = 0;
            } else{
                $data->piutangj                 = str_replace(".", "", $request['piutangJ']);
            }

            if($request['toJual'] == ""){
                $data->tojual                   = 0;
            } else{
                $data->tojual                   = str_replace(".", "", $request['toJual']);
            }

            if($request['toRetPot'] == ""){
                $data->toretpot                 = 0;
            } else{
                $data->toretpot                 = str_replace(".", "", $request['toRetPot']);
            }

            if($request['jangkawaktukredit'] == ""){
                $data->jangkawaktukredit        = 30;
            } else{
                $data->jangkawaktukredit        = $request['jangkawaktukredit'];
            }

            if ($request['tgl1st'] == "") {
                $data->tgldob                   = NULL;
            } else{
                $data->tgldob                   = date('Y-m-d', strtotime($request['tgl1st']));
            }

            if($request['exist'] == ""){
                $data->exist                    = 0;
            } else{
                $data->exist                    = $request['exist'];
            }
            
            $data->classid                      = $request['classID'];
            
            if($request['harikirim'] == ""){
                $data->harikirim                = 0;
            } else{
                $data->harikirim                = $request['harikirim'];
            }

            $data->kodepos                      = $request['kodepos'];
            $data->grade                        = strtoupper($request['grade']);
            
            if($request['plafon1st'] == ""){
                $data->plafon1st                = 0;
            } else{
                $data->plafon1st                = str_replace(".", "", $request['plafon1st']);
            }

            $data->statusaktif                  = 1;
            
            if($request['harisales'] == ""){
                $data->harisales                = 0;
            } else{
                $data->harisales                = $request['harisales'];
            }

            $data->alamatrumah                  = strtoupper($request['alamatrumah']);
            $data->pengelola                    = strtoupper($request['pengelola']);

            if ($request['tanggallahir'] == "") {
                $data->tgllahir                 = NULL;
            } else{
                $data->tgllahir                 = date('Y-m-d', strtotime($request['tanggallahir']));
            }

            $data->hp                           = $request['hp'];
            $data->thnberdiri                   = $request['thnberdiri'];
            $data->status                       = 0;

            if($request['statusruko'] == ""){
                $data->statusruko               = 0;
            } else{
                $data->statusruko               = $request['statusruko'];
            }
            
            if($request['jmlcabang'] == ""){
                $data->jmlcabang                = 0;
            } else{
                $data->jmlcabang                = $request['jmlcabang'];
            }

            if($request['jmlsales'] == ""){
                $data->jmlsales                 = 0;
            } else{
                $data->jmlsales                 = $request['jmlsales'];
            }

            $data->kinerja                      = strtoupper($request['kinerja']);
            $data->tipebisnis                   = strtoupper($request['bidangusaha']);
            $data->i_spart                      = $request['I_spart'];
            $data->fax                          = $request['fax'];
            $data->bangunan                     = $request['bangunan'];

            if ($request['habis_kontrak'] == "") {
                $data->habis_kontrak            = NULL;
            } else{
                $data->habis_kontrak            = date('Y-m-d', strtotime($request['habis_kontrak']));
            }

            $data->jenis_produk                 = $request['jenis_produk'];
            $data->pemilik                      = strtoupper($request['pemilik']);
            $data->gender                       = $request['gender'];
            $data->tempatlahir                  = strtoupper($request['tempatlahir']);
            $data->email                        = strtoupper($request['email']);
            $data->norekening                   = $request['norekening'];
            $data->namabank                     = strtoupper($request['namabank']);
            $data->no_member                    = $request['no_member'];
            $data->hobi                         = strtoupper($request['hobi']);
            $data->nonpwp                       = $request['nonpwp'];
            $data->no_ktp                       = $request['no_ktp'];
            $data->kecamatan                    = $request['namakec'];
            $data->namakabkota                  = strtoupper($request['namakab']);
            $data->customwilayah                = strtoupper($request['wilid']);
            $data->statusacc                    = 'APPROVED';

             //Upload Foto KTP
            if($request->hasfile('file_gambar')){
                $path = $request->file('file_gambar');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $val = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($val);
                $data->imagektp = $base64;
            } else{
                $data->imagektp = null;
            }

            if($request['toko00'] == ""){
                $data->toko00                   = null;
            } else {
                $data->toko00                   = $request['toko00'];
            }

            $data->save();

            ///////////////////////////////////////////////////////////////////////////
            //Insert Toko//
            ///////////////////////////////////////////////////////////////////////////

            $data = new Toko();
            $data->isarowid                     = $rowid;
            $data->tokoidwarisan                = $tokoid;
            $data->tokoidwarisanlama            = $tokoid;
            $data->namatoko                     = strtoupper($request['nmtoko']);
            $data->alamat                       = strtoupper($request['alamat']);
            $data->kota                         = $request['namakab'];
            $data->telp                         = $request['telp'];
            $data->propinsi                     = $request['namaprov'];
            $data->penanggungjawab              = strtoupper($request['penanggungjawab']);

            if($request['plafon'] == ""){
                $data->plafon                   = 0;
            } else{
                $data->plafon                   = str_replace(".", "", $request['plafon']);
            }
            
            $data->catatan                      = strtoupper($request['catatan']);
            $data->daerah                       = strtoupper($request['daerah']);
            $data->createdby                    = auth()->user()->username;
            $data->createdon                    = date('Y-m-d H:i:s');
            $data->lastupdatedby                = auth()->user()->username;
            $data->lastupdatedon                = date('Y-m-d H:i:s');
            $data->kodetoko                     = $kodetoko;

            if($request['piutangB'] == ""){
                $data->piutangb                 = 0;
            } else{
                $data->piutangb                 = str_replace(".", "", $request['piutangB']);
            }

            if($request['piutangJ'] == ""){
                $data->piutangj                 = 0;
            } else{
                $data->piutangj                 = str_replace(".", "", $request['piutangJ']);
            }

            if($request['toJual'] == ""){
                $data->tojual                   = 0;
            } else{
                $data->tojual                   = str_replace(".", "", $request['toJual']);
            }

            if($request['toRetPot'] == ""){
                $data->toretpot                 = 0;
            } else{
                $data->toretpot                 = str_replace(".", "", $request['toRetPot']);
            }

            if($request['jangkawaktukredit'] == ""){
                $data->jangkawaktukredit        = 30;
            } else{
                $data->jangkawaktukredit        = $request['jangkawaktukredit'];
            }

            if ($request['tgl1st'] == "") {
                $data->tgldob                   = NULL;
            } else{
                $data->tgldob                   = date('Y-m-d', strtotime($request['tgl1st']));
            }

            if($request['exist'] == ""){
                $data->exist                    = 0;
            } else{
                $data->exist                    = $request['exist'];
            }
            
            $data->classid                      = $request['classID'];
            
            if($request['harikirim'] == ""){
                $data->harikirim                = 0;
            } else{
                $data->harikirim                = $request['harikirim'];
            }

            $data->kodepos                      = $request['kodepos'];
            $data->grade                        = strtoupper($request['grade']);
            
            if($request['plafon1st'] == ""){
                $data->plafon1st                = 0;
            } else{
                $data->plafon1st                = str_replace(".", "", $request['plafon1st']);
            }

            $data->statusaktif                  = 1;
            
            if($request['harisales'] == ""){
                $data->harisales                = 0;
            } else{
                $data->harisales                = $request['harisales'];
            }

            $data->alamatrumah                  = strtoupper($request['alamatrumah']);
            $data->pengelola                    = strtoupper($request['pengelola']);

            if ($request['tanggallahir'] == "") {
                $data->tgllahir                 = NULL;
            } else{
                $data->tgllahir                 = date('Y-m-d', strtotime($request['tanggallahir']));
            }

            $data->hp                           = $request['hp'];
            $data->thnberdiri                   = $request['thnberdiri'];
            $data->status                       = 0;

            if($request['statusruko'] == ""){
                $data->statusruko               = 0;
            } else{
                $data->statusruko               = $request['statusruko'];
            }
            
            if($request['jmlcabang'] == ""){
                $data->jmlcabang                = 0;
            } else{
                $data->jmlcabang                = $request['jmlcabang'];
            }

            if($request['jmlsales'] == ""){
                $data->jmlsales                 = 0;
            } else{
                $data->jmlsales                 = $request['jmlsales'];
            }

            $data->kinerja                      = strtoupper($request['kinerja']);
            $data->tipebisnis                   = strtoupper($request['bidangusaha']);
            $data->i_spart                      = $request['I_spart'];
            $data->fax                          = $request['fax'];
            $data->bangunan                     = $request['bangunan'];

            if ($request['habis_kontrak'] == "") {
                $data->habis_kontrak            = NULL;
            } else{
                $data->habis_kontrak            = date('Y-m-d', strtotime($request['habis_kontrak']));
            }

            $data->jenis_produk                 = $request['jenis_produk'];
            $data->pemilik                      = strtoupper($request['pemilik']);
            $data->gender                       = $request['gender'];
            $data->tempatlahir                  = strtoupper($request['tempatlahir']);
            $data->email                        = strtoupper($request['email']);
            $data->norekening                   = $request['norekening'];
            $data->namabank                     = strtoupper($request['namabank']);
            $data->no_member                    = $request['no_member'];
            $data->hobi                         = strtoupper($request['hobi']);
            $data->nonpwp                       = $request['nonpwp'];
            $data->no_ktp                       = $request['no_ktp'];
            $data->kecamatan                    = $request['namakec'];
            $data->namakabkota                  = strtoupper($request['namakab']);
            $data->customwilayah                = strtoupper($request['wilid']);

             //Upload Foto KTP
            if($request->hasfile('file_gambar')){
                $path = $request->file('file_gambar');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $val = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($val);
                $data->imagektp = $base64;
            } else{
                $data->imagektp = null;
            }

            $data->save();

            ///////////////////////////////////////////////////////////////////////////
            //Insert Toko hak Akses//
            ///////////////////////////////////////////////////////////////////////////

            if($request['toko00']){
                $isarowid = Toko::where('isarowid',$rowid)->first();
                $toko00 = $request['toko00'];
                
                $roi = SubCabang::where('kodesubcabang',$toko00)->first();

                DB::table('mstr.tokohakakses')->insert([
                    'tokoid'        => $isarowid->id,
                    'recordownerid' => $roi->id,
                    'tglaktif'      => date('Y-m-d', strtotime($request['tgl1st'])),
                    'createdby'     => auth()->user()->username,
                    'createdon'     => date('Y-m-d H:i:s'),
                    'lastupdatedby' => auth()->user()->username,
                    'lastupdatedon' => date('Y-m-d H:i:s')
                ]);
            }
            //End

            $desc = "Tokodraft baru telah sukses ditambah.";
            //End

            return redirect()->route('tokodraft.index')->with('message', ['status' => 'success','desc' => $desc]);
        }
    }

    public function approve(Request $request){
        $id = $request['id'];
        $update                     = Tokodraft::where('rowid', $id)->first();  
        $update->userACC            = $request['useracc'];
        $update->alasanACC          = strtoupper($request['alasan']);
        $update->statusACC          = $request['statusacc'];
        $update->LastUpdatedTime    = date('Y-m-d H:i:s');
        $update->update();

        if($request['statusacc'] == "APPROVED"){
            //Insert to Toko
            DB::table('mstr.toko')->insert([
                'tokoidwarisan'     => $update->tokodraftID,
                'tokoidwarisanlama' => $update->tokodraftIDlama,
                'kodetoko'          => $update->KodeToko,
                'namatoko'          => $update->NamaToko,
                'alamat'            => $update->Alamat,
                'propinsi'          => $update->Propinsi,
                'kota'              => $update->Kota,
                'kecamatan'         => $update->NamaKecamatan,
                'customwilayah'     => $update->wilID,
                'telp'              => $update->Telp,
                'fax'               => $update->Fax,
                'penanggungjawab'   => $update->PenanggungJawab,
                'tgldob'            => $update->Tgl1st,
                'catatan'           => $update->Catatan,
                'pemilik'           => $update->nama_pemilik,
                'tempatlahir'       => $update->tempat_lhr,
                'tgllahir'          => $update->TglLahir,
                'email'             => $update->email,
                'norekening'        => $update->no_rekening,
                'namabank'          => $update->nama_bank,
                'nonpwp'            => $update->no_npwp,
                'tipebisnis'        => $update->bidangusaha,
                'isarowid'          => $update->rowid,
                'createdby'         => $update->createdby,
                'createdon'         => $update->createdon,
                'lastupdatedby'     => $update->LastUpdatedBy,
                'lastupdatedon'     => date('Y-m-d H:i:s'),
                'hp'                => $update->HP,
                'no_ktp'            => $update->no_ktp,
                'piutangb'          => $update->PiutangB,
                'piutangj'          => $update->PiutangJ,
                'plafon'            => $update->Plafon,
                'tojual'            => $update->ToJual,
                'toretpot'          => $update->ToRetPot,
                'jangkawaktukredit' => $update->JangkaWaktuKredit,
                'cabang2'           => $update->Cabang2,
                'exist'             => $update->Exist,
                'classid'           => $update->ClassID,
                'harikirim'         => $update->HariKirim,
                'kodepos'           => $update->KodePos,
                'grade'             => $update->Grade,
                'plafon1st'         => $update->Plafon1st,
                'flag'              => $update->Flag,
                'bentrok'           => $update->Bentrok,
                'statusaktif'       => $update->StatusAktif,
                'harisales'         => $update->HariSales,
                'daerah'            => $update->Daerah,
                'alamatrumah'       => $update->AlamatRumah,
                'pengelola'         => $update->Pengelola,
                'status'            => $update->Status,
                'thnberdiri'        => $update->ThnBerdiri,
                'statusruko'        => $update->StatusRuko,
                'jmlcabang'         => $update->JmlCabang,
                'jmlsales'          => $update->JmlSales,
                'kinerja'           => $update->Kinerja,
                'refsales'          => $update->RefSales,
                'refcollector'      => $update->RefCollector,
                'refsupervisor'     => $update->RefSupervisor,
                'plafonsurvey'      => $update->plafonsurvey,
                'no_toko'           => $update->no_toko,
                'exp_norm'          => $update->Exp_Norm,
                'cabang'            => $update->cabang,
                'cabang1'           => $update->cabang1,
                'i_spart'           => $update->I_spart,
                'bangunan'          => $update->Bangunan,
                'habis_kontrak'     => $update->Habis_kontrak,
                'jenis_produk'      => $update->Jenis_produk,
                'no_member'         => $update->no_member,
                'hobi'              => $update->hobi,
                'kodetokodeleted'   => $update->KodeTokoDeleted,
                'namakabkota'       => $update->NamaKabKota,
                'bentukbadanusaha'  => $update->bentukbadanusaha,
                'luasgudang'        => $update->luasgudang,
                'armadamobil'       => $update->armadamobil,
                'armadamotor'       => $update->armadamotor,
                'wilayahpemasaran'  => $update->wilayahpemasaran,
                'norekbgch'         => $update->norekbgch,
                'imagektp'          => $update->imagektp,
                'imagetoko'         => $update->imagetoko,
                'networklocation'   => $update->networklocation,
                'gpslocation'       => $update->gpslocation,
                'verifiednik'       => $update->verifiednik,
                'tosupdate'         => $update->tosupdate
            ]);
            //End

            //Insert to TokoHakAkses
            $isarowid = Toko::where('isarowid',$id)->first();
            $toko00 = explode(',', $update->toko00);
            $count = count($toko00);
            for ($i=0;$i<$count;$i++) {
                $roi = SubCabang::where('kodesubcabang',$toko00[$i])->first();
                DB::table('mstr.tokohakakses')->insert([
                    'tokoid'        => $isarowid->id,
                    'recordownerid' => $roi->id,
                    'tglaktif'      => $update->Tgl1st,
                    'createdby'     => $update->createdby,
                    'createdon'     => date('Y-m-d H:i:s'),
                    'lastupdatedby' => $update->LastUpdatedBy,
                    'lastupdatedon' => date('Y-m-d H:i:s')
                ]);
            }
            //End

            $desc = "APPROVED telah ditambahkan";

        } else{

            DB::select("INSERT INTO mstr.tokodraftrejected SELECT * FROM mstr.tokodraft WHERE rowid='$id' ");
            DB::select("DELETE FROM mstr.tokodraft WHERE rowid='$id' ");

            $desc = "REJECTED telah ditambahkan";
        }

        return redirect()->route('tokodraft.index')->with('message', ['status' => 'success', 'desc' => $desc]);        
    }

    public function hapus(Request $req){
        if($req->kodetoko) {
            $tokodraft = Tokodraft::where('kodetoko',$req->kodetoko)->delete();
            return json_encode(true);
        }else{
            return json_encode(false);
        }
    }

    public function getkota($id) {
        $kota = Kabupaten::where("provinsiid",$id)->pluck("namakota","id");
        return json_encode($kota);
    }

    public function getkecamatan($id) {
        $kec = Kecamatan::where("kabkotaid",$id)->pluck("namakecamatan","id");
        return json_encode($kec);
    }

    public function cekktp($ktp){
        $ktp = Tokodraft::where('no_ktp',$ktp)->pluck('no_ktp');
        return json_encode($ktp);
    }

    public function viewfotoktp($id){
        $tokodraft = Tokodraft::where('tokodraftid', $id)->first();  
        return view('master.tokodraft.viewktp',['tokodraft' => $tokodraft]);
    }

    public function rejected(){
        $tokodraft = DB::table('mstr.tokodraftrejected')->orderBy('tokodraftID','asc')->get();
        return view('master.tokodraft.tokodraftrejected', ['tokodraft' => $tokodraft]);
    }

    public function batalrejected($id){
        DB::select("INSERT INTO mstr.tokodraft SELECT * FROM mstr.tokodraftrejected WHERE rowid='$id' ");

        $update                     = Tokodraft::where('rowid', $id)->first();  
        $update->alasanacc          = NULL;
        $update->statusacc          = NULL;
        $update->update();

        DB::select("DELETE FROM mstr.tokodraftrejected WHERE rowid='$id' ");

        $desc = "REJECTED telah dibatalkan";

         return redirect()->route('tokodraft.rejected')->with('message', ['status' => 'success', 'desc' => $desc]);    
    }

    public function tdhistory(Request $req){
      // gunakan permission dari indexnya aja
        if(!$req->user()->can('tokodraft.index')) {
            return response()->json([
                'draw'            => $req->draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
            ]);
        }

        // jika lolos, tampilkan data
        $filter_count = 0;
        $empty_filter = 0;
        $columns = [
            1 => 'mstr.tokodrafthistory.tglobsolete ',
            2 => 'mstr.tokodrafthistory."tokodraftID" ',
            3 => 'mstr.tokodrafthistory."NamaToko" ',
            4 => 'mstr.tokodrafthistory."Toko00" ',
            5 => 'mstr.tokodrafthistory."Alamat" ',
            6 => 'mstr.tokodrafthistory."Kota" ',
            7 => 'mstr.tokodrafthistory."Telp" ',
            8 => 'mstr.tokodrafthistory."PenanggungJawab" ',
            9 => 'mstr.tokodrafthistory."KodeToko" ',
            10 => 'mstr.tokodrafthistory."Daerah" ',
            11 => 'mstr.tokodrafthistory."PiutangB" ',
            12 => 'mstr.tokodrafthistory."PiutangJ" ',
            13 => 'mstr.tokodrafthistory."Plafon" ',
            14 => 'mstr.tokodrafthistory."ClassID" ',
            15 => 'mstr.tokodrafthistory."ToJual" ',
            16 => 'mstr.tokodrafthistory."ToRetPot" ',
            17 => 'mstr.tokodrafthistory."JangkaWaktuKredit" ',
            18 => 'mstr.tokodrafthistory."Tgl1st" ',
            19 => 'mstr.tokodrafthistory."ThnBerdiri" ',
            20 => 'mstr.tokodrafthistory."StatusRuko" ',
            21 => 'mstr.tokodrafthistory."Fax" ',
            22 => 'mstr.tokodrafthistory."Bangunan" ',
            23 => 'mstr.tokodrafthistory."Habis_kontrak" ',
            24 => 'mstr.tokodrafthistory.nama_pemilik ',
            25 => 'mstr.tokodrafthistory.no_ktp ',
            26 => 'mstr.tokodrafthistory."JmlCabang" ',
            27 => 'mstr.tokodrafthistory.jenis_kelamin ',
            28 => 'mstr.tokodrafthistory.tempat_lhr ',
            29 => 'mstr.tokodrafthistory.email ',
            30 => 'mstr.tokodrafthistory.no_rekening ',
            31 => 'mstr.tokodrafthistory.nama_bank ',
            32 => 'mstr.tokodrafthistory.no_member ',
            33 => 'mstr.tokodrafthistory.hobi ',
            34 => 'mstr.tokodrafthistory.no_npwp ',
            35 => 'mstr.tokodrafthistory."JmlSales" ',
            36 => 'mstr.tokodrafthistory."Catatan" ',
            37 => 'mstr.tokodrafthistory."HariKirim" ',
            38 => 'mstr.tokodrafthistory."KodePos" ',
            39 => 'mstr.tokodrafthistory."Grade" ',
            40 => 'mstr.tokodrafthistory."Plafon1st" ',
            41 => 'mstr.tokodrafthistory."Bentrok" ',
            42 => 'mstr.tokodrafthistory."StatusAktif" ',
            43 => 'mstr.tokodrafthistory."HariSales" ',
            44 => 'mstr.tokodrafthistory."AlamatRumah" ',
            45 => 'mstr.tokodrafthistory."Pengelola" ',
            46 => 'mstr.tokodrafthistory."TglLahir" ',
            47 => 'mstr.tokodrafthistory."HP" ',
            48 => 'mstr.tokodrafthistory."Kinerja" ',
            49 => 'mstr.tokodrafthistory."BidangUsaha" ',
            50 => 'mstr.tokodrafthistory."I_spart" ',
            51 => 'mstr.tokodrafthistory."LastUpdatedBy" ',
            52 => 'mstr.tokodrafthistory."LastUpdatedTime" ',
            53 => 'mstr.tokodrafthistory."Jenis_produk" ',
            54 => 'mstr.tokodrafthistory."NamaKecamatan" ',
            55 => 'mstr.tokodrafthistory."NamaKabKota" ',
        ];

        // Models
        $modelObj = Tokodrafthistory::selectRaw(collect($columns)->implode(', '));
        $modelObj->where("mstr.tokodrafthistory.rowid",$req->rowid);
        $total_data = $modelObj->count();
        //$modelObj->orderBy('secure.users.username');
        if($filter_count > 0){
            $filtered_data = $stop->count();
        }else{
            $filtered_data = $total_data;
        }

        if($req->start > $filtered_data){
            $modelObj->skip(0)->take($req->length);
        }else{
            $modelObj->skip($req->start)->take($req->length);
        }

        // Data
        $data = [];
        foreach ($modelObj->get($columns) as $k => $val) {
            $data[$k] = $val->toArray();
            $data[$k]['DT_RowId'] = 'gv1_'.$val->id;
        }

        return response()->json([
            'draw'            => $req->draw,
            'recordsTotal'    => $total_data,
            'recordsFiltered' => $filtered_data,
            'data'            => $data,
        ]);
    }

    public function tddetail(Request $req){
      $data = Tokodraft::where('kodetoko',$req->kodetoko)->first();

      return response()->json([
        "tokodraftid"   => $data->tokodraftid,
        "kodetoko"   => $data->kodetoko,
        "namatoko"      => $data->namatoko,
        "alamat"   => $data->alamat,
        "propinsi"   => $data->propinsi,
        "kota"   => $data->kota,
        "kecamatan"   => $data->kecamatan,
        "telp"   => $data->telp,
        "fax"   => $data->fax,
        "penanggungjawab"   => $data->penanggungjawab,
        "tgldob"   => $data->tgldob,
        "catatan"   => $data->catatan,
        "pemilik"   => $data->pemilik,
        "gender"   => $data->gender,
        "tempatlahir"   => $data->tempatlahir,
        "tgllahir"   => $data->tgllahir,
        "email"   => $data->email,
        "norekening"   => $data->norekening,
        "namabank"   => $data->namabank,
        "nonpwp"   => $data->nonpwp,
        "tipebisnis"   => $data->bidangUsaha,
        "lastupdatedby"   => $data->lastupdatedby,
        "lastupdatedon"   => $data->lastupdatedon,
        "hp"   => $data->hp,
        "no_ktp"   => $data->no_ktp,
        "piutangb"   => $data->piutangb,
        "piutangj"   => $data->piutangj,
        "plafon"   => $data->plafon,
        "tojual"        => $data->tojual,
        "toretpot"   => $data->toretpot,
        "jangkawaktukredit"   => $data->jangkawaktukredit,
        "cabang2"   => $data->cabang2,
        "exist"   => $data->exist,
        "classid"   => $data->classid,
        "harikirim"   => $data->harikirim,
        "kodepos"   => $data->kodepos,
        "grade"   => $data->grade,
        "plafon1st"   => $data->plafon1st,
        "flag"   => $data->flag,
        "bentrok"   => $data->bentrok,
        "statusaktif"   => $data->statusaktif,
        "harisales"   => $data->harisales,
        "daerah"   => $data->daerah,
        "alamatrumah"   => $data->alamatrumah,
        "pengelola"   => $data->pengelola,
        "status"   => $data->status,
        "thnberdiri"   => $data->thnberdiri,
        "statusruko"   => $data->statusruko,
        "jmlcabang"   => $data->jmlcabang,
        "jmlsales"   => $data->jmlsales,
        "kinerja"   => $data->kinerja,
        "no_toko"   => $data->no_toko,
        "exp_norm"   => $data->exp_norm,
        "i_spart"   => $data->i_spart,
        "bangunan"   => $data->bangunan,
        "habis_kontrak"   => $data->habis_kontrak,
        "jenis_produk"   => $data->jenis_produk,
        "no_member"   => $data->no_member,
        "hobi"   => $data->hobi,
        "namakabkota"   => $data->namakabkota,
        "bentukbadanusaha"   => $data->bentukbadanusaha,
        "luasgudang"   => $data->luasgudang,
        "armadamobil"   => $data->armadamobil,
        "armadamotor"   => $data->armadamotor,
        "wilayahpemasaran"   => $data->wilayahpemasaran,
        "norekbgch"        => $data->norekbgch,
      ]);
    }
}
