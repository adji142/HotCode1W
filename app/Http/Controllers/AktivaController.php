<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\AppSetting;

use App\Models\AktivaHistory;
use App\Models\AktivaMutasi;
use App\Models\AktivaPenyusutan;
use App\Models\AktivaGrupItem;
use App\Models\AktivaJenisItem;
use App\Models\AktivaGolongan;
use App\Models\Aktiva;
use App\Models\ClosingDate;
use App\Models\Numerator;

use App\Models\User;
use App\Models\SubCabang;

use App\Models\JournalAcc;
use App\Models\JournalAccDetail;
use App\Models\AktivaSaldo;

use DB;
use PDF;
use EXCEL;

class AktivaController extends Controller
{
    //
    #region function
    ///mengambil tgl closing di schema acc.dateclosing
    protected function dateClosing($ownerid)  {
        $data = ClosingDate::selectRaw('acc.closingdate.tglclosing')
                ->where('acc.closingdate.modul','F/A')
                ->where('acc.closingdate.recordownerid', $ownerid) 
                ->orderBy('tglclosing', 'desc')
                ->take(1)
                ->first()->tglclosing;
        return $data;
    }

    //increment acc.aktiva.noaktiva + 1 format "0000" contoh 0021
    protected function increment($ownerid){
        //$depan.str_pad($nomor, $lebar, '0', STR_PAD_LEFT);

        $query = Aktiva::selectRaw('acc.aktiva.noaktiva')
                ->where('acc.aktiva.recordownerid', $ownerid)
                ->max('acc.aktiva.noaktiva');
        $no = str_pad((int)$query + 1, 4, '0', STR_PAD_LEFT);
        return $no;
    }

    //get acc.aktivajenisitem
    public function getKelompok(){
        $data = AktivaJenisItem::selectRaw('id,keterangan,usiapenyusutan')
            ->where("status","1")
            ->orderBy("keterangan")
            ->get();
        return $data;
    }

    //get acc.aktivagolongan
    public function getGolongan($idjenisitem){
        $data = AktivaGolongan::selectRaw('id, golongan, keterangan')
                ->where('acc.aktivagolongan.jenisitemid', $idjenisitem)->get();
        return $data;
    }

    //get acc.aktivajenisitem.usiapenyusutan, digunakan saat menambahkan item aktiva baru
    public function getUsiaPenyusutan(Request $Request){
        $data = AktivaJenisItem::where('id',$Request->id)->get();
        $golongan = $this->getGolongan($Request->id);
        return response()->json([
            'data' => $data,
            'golongan' => $golongan
        ]);
    }

    //AKTIVA_PENYUSUTAN
    //get numerator untuk nobukti penyusutan
    protected function getNumeratorPenyusutan($ownerid) {
        $query = Numerator::selectRaw("depan, nomor, lebar")
                ->where("recordownerid", $ownerid)
                ->where("doc", "AKTIVA_PENYUSUTAN")
                ->first();
        $closing = Carbon::parse($this->dateClosing($ownerid))->addDay();
        $tahun = Carbon::parse($closing)->format("Y");
        $bulan = Carbon::parse($closing)->format("m");
        $depan = "SST".$tahun.$bulan;
        $format = str_pad((int)$query->nomor, (int)$query->lebar, '0', STR_PAD_LEFT);
        $kode = $depan.$format;

        return $kode;
    }

    protected function listOfMonth(){
        $result = array(
            1 => "Januari",
            2 => "Februari",
            3 => "Maret",
            4 => "April",
            5 => "Mei",
            6 => "Juni",
            7 => "Juli",
            8 => "Agustus",
            9 => "September",
            10 => "Oktober",
            11 => "November",
            12 => "Desember"
        );
        return $result;
    }
    
    //get numerator untuk nobukti penjualan aktiva
    protected function getNumerator($ownerid) {
        $query = Numerator::selectRaw("depan, nomor, lebar")
                ->where("recordownerid", $ownerid)
                ->where("doc", "AKTIVA_JUAL")
                ->first();
        $closing = Carbon::parse($this->dateClosing($ownerid))->addDay();
        $tahun = Carbon::parse($closing)->format("Y");
        $bulan = Carbon::parse($closing)->format("m");
        $depan = $tahun.$bulan;
        $format = str_pad((int)$query->nomor, (int)$query->lebar, '0', STR_PAD_LEFT);
        $kode = $depan.$format;

        return $kode;
    }

    protected $original_column = array(
        1 => 'acc.aktiva.noaktiva',
        2 => 'acc.aktiva.noregister',
        3 => 'acc.aktiva.namaaktiva',
        4 => 'acc.aktivajenisitem.keterangan',
        5 => 'acc.aktivagolongan.keterangan',
        6 => 'acc.aktiva.tglperolehan',
        7 => 'acc.aktiva.usiapenyusutan',
        8 => 'acc.aktiva.nomperolehan',
        9 => 'acc.aktiva.qtypenyusutan',
        10 => 'acc.aktiva.nombuku',
        11 => 'acc.aktiva.tglkeluar',
        12 => 'acc.aktiva.nomjual',
        13 => 'acc.aktiva.keterangankeluar',
        
    );

    // protected $original_column = array(
    //     1 => 'acc.aktiva.noaktiva',
    //     2 => 'acc.aktiva.namaaktiva',
    //     3 => 'acc.aktivajenisitem.keterangan',
    //     4 => 'acc.aktiva.tglperolehan',
    //     5 => 'acc.aktiva.nomperolehan',
    //     6 => 'acc.aktiva.usiapenyusutan',
    //     7 => 'acc.aktiva.qtypenyusutan',
    //     8 => 'acc.aktiva.nombuku',
    //     9 => 'acc.aktiva.tglkeluar',
    //     10 => 'acc.aktiva.nomjual',
    //     11 =>'acc.aktiva.status',
    //     12 => 'acc.aktiva.keterangankeluar',
    //     13 => 'acc.aktivagolongan.keterangan AS golongan'
    // );

    #endregion

    public function index(){
        return view('master.aktiva.index');
    }

    //header gridview aktiva, (acc.aktiva)
    public function getData(Request $Request){
        // if(!$Request->user()->can('aktiva.index')) {
        //     return response()->json([
        //         'draw'            => $Request->draw,
        //         'recordsTotal'    => 0,
        //         'recordsFiltered' => 0,
        //         'data'            => [],
        //     ]);
        // }

        // jika lolos, tampilkan data
    	//$Request->session()->put('tglmulai', $Request->tglmulai);
        //$Request->session()->put('tglselesai', $Request->tglselesai);

        $filter_count = 0;
        $empty_filter = 0;

        $columns = array(
            0 => 'acc.aktiva.id',
            1 => 'acc.aktiva.noaktiva',
            2 => 'acc.aktiva.noregister',
            3 => 'acc.aktiva.namaaktiva',
            4 => 'acc.aktivajenisitem.keterangan',
            5 => 'acc.aktivagolongan.keterangan AS golongan',
            6 => 'acc.aktiva.tglperolehan',
            7 => 'acc.aktiva.usiapenyusutan',
            8 => 'acc.aktiva.nomperolehan',
            9 => 'acc.aktiva.qtypenyusutan',
            10 => 'acc.aktiva.nombuku',
            11 => 'acc.aktiva.tglkeluar',
            12 => 'acc.aktiva.nomjual',
            13 => 'acc.aktiva.keterangankeluar',
            14 => 'acc.aktiva.status',
            
        );

        for ($i=1; $i < count($Request->custom_search); $i++) {
            if($Request->custom_search[$i]['text'] == ''){
                $empty_filter++;
            }
        }



        $query = Aktiva::selectRaw(collect($columns)->implode(", "))
            ->join("acc.aktivajenisitem","acc.aktiva.aktivajenisitemid", "acc.aktivajenisitem.id")
            ->join("acc.aktivagolongan", function($join){
                $join->on("acc.aktivajenisitem.id","=","acc.aktivagolongan.jenisitemid");
                $join->on(DB::raw("acc.aktiva.golongan::integer"),"=", DB::raw("acc.aktivagolongan.golongan::integer"));
            })
            //->join("acc.aktivagolongan","acc.aktivajenisitem.id","acc.aktivagolongan.jenisitemid")
            // ->where(function ($q) use($search){
            //     $q->where('acc.aktiva.noaktiva', 'ilike', '%'.$search.'%');
            //     $q->orWhere('acc.aktiva.namaaktiva', 'ilike', '%'.$search.'%');
            // })
            ->where('acc.aktiva.recordownerid', (int)$Request->session()->get('subcabang'));
        $search = $Request->search;
        if ($search != null || $search != ''){
            $query->where(function ($q) use($search){
                $q->where('acc.aktiva.noaktiva', 'ilike', '%'.$search.'%');
                $q->orWhere('acc.aktiva.namaaktiva', 'ilike', '%'.$search.'%');
            });
        }

        //dd($query->toSql());
        
        $total_data = $query->count();
        if($empty_filter < count($Request->custom_search) - 1){
            for ($i=1; $i < count($Request->custom_search); $i++) {
                if($Request->custom_search[$i]['text'] != ''){
                    $index = $i;
                    if($index > 1){
                        if($Request->custom_search[$i]['filter'] == '='){
                            $query->where($this->original_column[$index],'ilike','%'.$Request->custom_search[$i]['text'].'%');
                        }else{
                            $query->where($this->original_column[$index],'not ilike','%'.$Request->custom_search[$i]['text'].'%');
                        }
                    }else{
                        $query->where($this->original_column[$index],$Request->custom_search[$i]['filter'],$Request->custom_search[$i]['text']);
                    }
                    $filter_count++;
                }
            }
        }
        if($filter_count > 0){
            $filtered_data = $query->count();
        }else{
            $filtered_data = $total_data;
        }
        
        if($Request->tipe_edit){
            $query->orderBy('acc.aktiva.tglperolehan','desc');
            $query->orderBy('acc.aktiva.id', 'desc');
        }else{
            if(array_key_exists($Request->order[0]['column'], $this->original_column)){
                $query->orderByRaw($this->original_column[$Request->order[0]['column']].' '.$Request->order[0]['dir']);
            }
        }
        

        // if($Request->tipe_edit){
        //     $query->orderBy('acc.aktiva.tglperolehan','desc');
        // }else{
        //     if(array_key_exists($Request->order[0]['column'], $this->original_column)){
        //         $query->orderByRaw($this->original_column[$Request->order[0]['column']].' '.$Request->order[0]['dir']);
        //     }
        // }
        
        // if(array_key_exists($Request->order[0]['column'], $this->original_column)){
                
        //     }
        $query->orderByRaw($this->original_column[$Request->order[0]['column']].' '.$Request->order[0]['dir']);

        if($Request->start > $filtered_data){
            $query->skip(0)->take($Request->length);
        }else{
            $query->skip($Request->start)->take($Request->length);
        }

        $data = [];
        foreach ($query->get() as $k => $val) {
            $val->tglperolehan   = ($val->tglperolehan) ? Carbon::parse($val->tglperolehan)->format('d-m-Y') : '';
            $val->tglkeluar   = ($val->tglkeluar) ? Carbon::parse($val->tglkeluar)->format('d-m-Y') : '';
            $val->nomperolehan = number_format((float)$val->nomperolehan, 2, '.', ',');
            $val->nombuku = number_format((float)$val->nombuku, 2, '.', ',');
            $val->nomjual = number_format((float)$val->nomjual, 2, '.', ',');
            $data[$k] = $val->toArray();
        }

        return response()->json([
            'draw'              => $Request->draw,
            'recordsTotal'      => $total_data,
            'recordsFiltered'   => $filtered_data,
            'data'              => $data,
        ]);

    }

    //detail gridview aktiva, (acc.aktivapenyusutan)
    public function getDataDetail(Request $Request){
        $columns = array(
            0 => 'acc.aktivapenyusutan.id',
            1 => 'acc.aktivapenyusutan.tanggal',
            2 => 'acc.aktivapenyusutan.nobukti',
            3 => 'acc.aktivapenyusutan.nominal',
            4 => 'acc.aktivapenyusutan.uraian',
            5 => 'acc.aktivapenyusutan.penyusutanke'
        );

        $intID = $Request->id;
        
        $query = AktivaPenyusutan::selectRaw(collect($columns)->implode(', '))
            ->where('acc.aktivapenyusutan.aktivaid', $intID);

        $recordsTotal = $query->count();
        
        $data = [];
        foreach ($query->get() as $k => $val) {
            $val->tanggal   = ($val->tanggal) ? Carbon::parse($val->tanggal)->format('d-m-Y') : '';
            $val->nominal = number_format((float)$val->nominal, 2, '.', ',');
            $data[$k] = $val->toArray();
        }

        return json_encode($data);
    }

    //get aktiva berdasarkan id (untuk revisi edit data)
    public function getDataById(Request $Request){

        $ownerid = $Request->session()->get("subcabang");

        $id = $Request->id;

        $data = array();
        $golongan = array();
        $noaktiva =  "0";

        $kelompok = $this->getKelompok();

        if ($id > 0){
            $columns = array(
                0 => 'acc.aktiva.id',
                1 => 'acc.aktiva.namaaktiva',
                2 => 'acc.aktiva.aktivajenisitemid',
                3 => 'acc.aktiva.golongan',
                4 => 'acc.aktiva.tglperolehan',
                5 => 'acc.aktiva.nomperolehan',
                6 => 'acc.aktiva.nombuku',
                7 => 'acc.aktiva.usiapenyusutan',
                8 => 'acc.aktiva.nompenyusutan',
                9 => 'acc.aktiva.qtypenyusutan',
                10 => 'acc.aktiva.noregister'
            );
            $query = Aktiva::selectRaw(collect($columns)->implode(', '))
                ->where('acc.aktiva.id', $Request->id);

            foreach ($query->get() as $k => $val) {
                $val->tglperolehan   = ($val->tglperolehan) ? Carbon::parse($val->tglperolehan)->format('d-m-Y') : '';
                // $val->nominal = number_format($val->nominal,2,',','.');
                $data[$k] = $val->toArray();
            }
            
            $idgolongan = $data[0]['aktivajenisitemid'];
            $golongan = $this->getGolongan($idgolongan);
        }else{
            $noaktiva = $this->increment($ownerid);
        }

        return response()->json(
            array
            ('data' => $data, 
            'kelompok' => $kelompok, 
            'golongan' => $golongan,
            'noaktiva' => $noaktiva
        ));
    }

    //menghapus aktiva
    public function RunQueryDelete(Request $Request){
        $message = '';
        $blnresult = false;
        $ownerid = $Request->session()->get('subcabang');

        $closing = $this->dateClosing($ownerid);

        $queryAktiva = Aktiva::selectRaw('id, tglperolehan, qtypenyusutan')
                ->where('acc.aktiva.id', $Request->id)
                ->first();
        //dd(Carbon::parse($queryAktiva->tglperolehan));
        $blnContinue = true;
        if ($queryAktiva->qtypenyusutan > 1) {
            $blnContinue = false;
            $message = 'Aktiva tidak bisa dihapus karena sudah disusutkan.';
        }
        if (Carbon::parse($queryAktiva->tglperolehan)->format("Y-m-d") <= Carbon::parse($closing)->format("Y-m-d")){
            $blnContinue = false;
            $message = 'Aktiva tidak bisa dihapus karena sudah diclosing';
        }

        

        if ($blnContinue == true){
            DB::beginTransaction();
            try{
                $data = Aktiva::destroy($Request->id);
                $detail = AktivaPenyusutan::where('acc.aktivapenyusutan.aktivaid', $Request->id)->delete();
                $message = 'Aktiva berhasil dihapus.';
                $blnresult = true;
                DB::commit();
            }catch (Exception $ex){
                $message = $ex->getMessage();
                $blnresult = false;
                DB::rollback();
            }
        }
        return response()->json(array('success' => $blnresult, 'message' => $message));
    }

    //menambahkan dan mengubah aktiva
    public function RunQueryInsert(Request $Request){
        $blnresult = false;
        $message = '';

        $id = $Request->id;
        $ownerid = $Request->session()->get('subcabang');
        $intLastInsertId = 0;
        //$tglclosing = $this->dateClosing($ownerid);
        
        // $blncontinue = true;

        // if ($id == 0){
        //     if (Carbon::parse($Request->tglperolehan)->format('Y-m-d') <= Carbon::parse($tglclosing)->format('Y-m-d')){
        //         $message = 'Tanggal perolehan sudah diclosing.';
        //         $blncontinue = false;
        //     }

        // }
        //dd(Carbon::parse($Request->tglperolehan)->format('Y-m-d'));
        //if ($blncontinue == true){
            try{
                
                if ($id > 0){
                    $query = Aktiva::find($id);
                    $query->nompenyusutan = (float)$Request->nompenyusutan;
                }else{
                    $query = new Aktiva();
                    $query->noaktiva = $this->increment($ownerid);
                    $query->createdby = strtoupper(auth()->user()->username);
                    $query->status = 1;
                    $query->qtypenyusutan = 0;
                    $query->recordownerid = $ownerid;
                    $query->nompenyusutan = 0;
                }
                
                $query->namaaktiva = $Request->namaaktiva;
                $query->tglperolehan = Carbon::Parse($Request->tglperolehan)->format('Y-m-d');
                $query->aktivajenisitemid = $Request->aktivajenisitemid;
                $query->golongan = $Request->golongan;
                $query->nomperolehan = (float)$Request->nomperolehan;
                $query->nombuku = (float)$Request->nombuku;
                $query->usiapenyusutan = $Request->usiapenyusutan;
                $query->noregister = $Request->noregister;
                $query->lastupdatedby =  strtoupper(auth()->user()->username);
                
                $query->save();

                $intLastInsertId = $query->id;
                $message = 'Simpan data berhasil.';
                $blnresult = true;

            }catch(Exception $ex){
                $message = $ex->getMessage();
                $blnresult = false;
            }
        //}
        
        return response()->json(['success' => $blnresult, 'message' => $message, "lastid" => $intLastInsertId]);
    }

    protected function retrieveDepresiasi(Request $Request){
        $ownerid = $Request->session()->get('subcabang');
        $closing = Carbon::parse($this->dateClosing($ownerid))->format('Y-m-d');
        $periode = Carbon::parse($closing)->addDay()->format('Y-m-d');
        
        //dd(Carbon::parse($closing)->addDay()->addMonth()->subDay()->toDateString());

        $Tahun = date("Y", strtotime($closing));
        $Bulan = str_pad(date("m", strtotime($closing)),2,'0',STR_PAD_LEFT);
        $Format = "SST".$Tahun.$Bulan;
        //$queryPenyusutan = AktivaPenyusutan::selectRaw("SUBSTRING")

        $columns = array(
            0 => 'acc.aktiva.id',
            1 => 'acc.aktiva.noaktiva',
            2 => 'acc.aktiva.namaaktiva',
            3 => 'acc.aktivajenisitem.keterangan',
            4 => '(CASE WHEN acc.aktiva.qtypenyusutan ISNULL THEN 1 ELSE acc.aktiva.qtypenyusutan + 1 END) AS penyusutanke',
            5 => '(CASE WHEN acc.aktiva.usiapenyusutan = 0 THEN 0 ELSE (acc.aktiva.nomperolehan / acc.aktiva.usiapenyusutan) END) AS nompenyusutan'
        );

        $query = Aktiva::selectRaw(collect($columns)->implode(", "))
            ->join("acc.aktivajenisitem","acc.aktiva.aktivajenisitemid","acc.aktivajenisitem.id")
            ->whereNotExists(function ($subquery) use($closing) {
                $subquery->select(DB::raw('acc.aktivapenyusutan.id'))
                        ->from('acc.aktivapenyusutan')
                        ->whereRaw('acc.aktivapenyusutan.aktivaid = acc.aktiva.id')
                        ->whereRaw('acc.aktivapenyusutan.tanggal = ?',[Carbon::parse($closing)->addDay()->addMonth()->subDay()->toDateString()]);
                        
            })
            ->where("acc.aktiva.tglperolehan", "<", Carbon::parse($closing)->addDays(16)->toDateString())
            ->where("acc.aktiva.recordownerid", $ownerid)
            ->where("acc.aktiva.nombuku", ">", 2)
            ->where("acc.aktiva.status", 1)
            ->whereRaw("acc.aktiva.usiapenyusutan > acc.aktiva.qtypenyusutan")
            ->orderBy("acc.aktivajenisitem.keterangan", "asc")
            ->orderBy("acc.aktiva.noaktiva", "asc")
            ->orderBy("acc.aktiva.namaaktiva", "asc")
            ->get();
        //dd($query);
        // var_dump(Carbon::parse($closing)->addDay());
        // dd(Carbon::parse($closing)->addDay()->addMonth());
        return $query;
    }

    public function getDataDepresiasi(Request $Request){
        $query = $this->retrieveDepresiasi($Request);
        $data = [];
        foreach($query as $k => $val){
            $val->nompenyusutan = number_format((float)$val->nompenyusutan, 2, '.', ',');
            $data[$k] = $val->toArray();
        }

        $tglclosing = Carbon::parse($this->dateClosing($Request->session()->get("subcabang")));
        $arrbulan = $this->listOfMonth();
        $closing = Carbon::parse($tglclosing)->addDay();

        $tahun = Carbon::parse($closing)->format("Y");
        $bulan = $arrbulan[(int)Carbon::parse($closing)->format("m")];

        $periode = "Periode :   " . $bulan . ' ' . $tahun;

        //dd($bulan,$tahun,$periode);
        
        return response()->json(array("data" => json_encode($data), "periode" => strtoupper($periode)));

        //return json_encode($data);
    }

    public function RunQueryDepresiasi(Request $Request){

        $blnresult = false;
        $message = "";
        $ownerid = $Request->session()->get("subcabang");
        $closing = Carbon::parse($this->dateClosing($ownerid))->format('Y-m-d');

        $query = $this->retrieveDepresiasi($Request);

        if (count($query) < 1){
            $message = "Tidak ada aktiva untuk disusutkan.";
        }else{
            
            DB::beginTransaction();
            try{

                foreach ($query as $row){
                    $execute = new AktivaPenyusutan();
                    $execute->aktivaid = $row->id;
                    $execute->tanggal = Carbon::parse($closing)->addDay()->addMonth()->subDay()->toDateString();
                    $execute->nobukti = $this->getNumeratorPenyusutan($ownerid);
                    $execute->penyusutanke = $row->penyusutanke;
                    $execute->uraian = "Penyusutan ke-".str_pad($row->penyusutanke,4,'0',STR_PAD_LEFT);
                    $execute->nominal = $row->nompenyusutan;
                    $execute->createdby = strtoupper(auth()->user()->username);
                    $execute->lastupdatedby = strtoupper(auth()->user()->username);

                    $execute->save();

                    $queryNumerator = Numerator::where("doc", "AKTIVA_PENYUSUTAN")
                        ->where("recordownerid", $ownerid)->first();
                    $queryNumerator->nomor = $queryNumerator->nomor + 1;
                    $queryNumerator->lastupdatedby = strtoupper(auth()->user()->username);
                    $queryNumerator->save();

                    $execAktiva = Aktiva::find($row->id);
                    $execAktiva->qtypenyusutan = $row->penyusutanke;
                    $execAktiva->nombuku = ($execAktiva->nombuku - $row->nompenyusutan);
                    $execAktiva->nompenyusutan = ($execAktiva->nompenyusutan + $row->nompenyusutan);
                    $execAktiva->lastupdatedby = strtoupper(auth()->user()->username);
                    $execAktiva->save();

                }

                $blnresult = true;
                $message = "Aktiva berhasil disusutkan.";
                DB::commit();
            }catch (Exception $ex){
                $message = $ex->getMessage();
                $blnresult = false;
                DB::rollback();
            }
        }
        return response()->json(array('success' => $blnresult, 'message' => $message));

        /*
        $blnresult = false;
        $message = "";

        $ownerid = $Request->session()->get('subcabang');
        $closing = Carbon::parse($this->dateClosing($ownerid))->format('Y-m-d');

        $query = $this->retrieveDepresiasi($Request);

        if (count($query) < 1){
            $message = "Tidak ada aktiva untuk disusutkan.";
        }else{

            $Tahun = date("Y", strtotime($closing));
            $Bulan = str_pad(date("m", strtotime($closing)),2,'0',STR_PAD_LEFT);
            $Format = "SST".$Tahun.$Bulan;

            //$queryPenyusutan = AktivaPenyusutan::selectRaw("acc.aktivapenyusutan.nobukti")

            DB::beginTransaction();
            try
            {
                
                DB::commit();
            }catch (Exception $ex){
                $message = $ex->getMessage();
                $blnresult = false;
                DB::rollback();
            }
        }
        */

        

    }

    public function RunQuerySellingAssets(Request $Request){
        $blnresult = false;
        $message = '';

        $id = $Request->aktivaid;
        $ownerid = $Request->session()->get('subcabang');
        $numerator = $this->getNumerator($ownerid);

        $checkAktiva = Aktiva::selectRaw("acc.aktiva.id, acc.aktiva.status, acc.aktiva.qtypenyusutan")
            ->where("acc.aktiva.id", $Request->aktivaid)
            ->first();

        $tglclosing = Carbon::parse($this->dateClosing($ownerid))->addDay()->addMonth()->subDay()->format("Y-m-d");
        
        if (Carbon::parse($Request->tglkeluar)->format('Y-m-d') <= Carbon::parse($this->dateClosing($ownerid))->format('Y-m-d')){
            $message = 'Tanggal keluar sudah diclosing.';
        }else{
            $header = Aktiva::selectRaw('acc.aktiva.id, acc.aktiva.nomperolehan, acc.aktiva.nompenyusutan')
                ->where('acc.aktiva.id', $Request->aktivaid)
                ->first();
            $detail = AktivaPenyusutan::selectRaw('acc.aktivapenyusutan.id, acc.aktivapenyusutan.nominal')
                ->where('aktivaid', $Request->aktivaid)
                ->where('tanggal', Carbon::parse($tglclosing));
            
            $nomperolehan = $header->nomperolehan;
            $nompenyusutan = $header->nompenyusutan;
            
            $nominal = 0;
            $rowCount = $detail->count();
           
            if ($rowCount > 0){
                foreach ($detail->get() as $key => $val){
                    $nominal += $val->nominal;
                }
                $nompenyusutan -= $nominal;
            }
            
            DB::beginTransaction();
            try{
                //jika sudah memiliki penyusutan tetapi belum closing
                if ($rowCount > 0){
                    $queryAktiva = Aktiva::find($id);
                    $queryAktiva->qtypenyusutan = $queryAktiva->qtypenyusutan - $rowCount;
                    $queryAktiva->nombuku = $queryAktiva->nombuku + $nominal;
                    $queryAktiva->nompenyusutan = $queryAktiva->nompenyusutan - $nominal;
                    $queryAktiva->save();

                    $queryPenyusutan = AktivaPenyusutan::where("acc.aktivapenyusutan.aktivaid", $Request->aktivaid)
                        ->where("acc.aktivapenyusutan.tanggal", Carbon::parse($tglclosing))
                        ->delete();
                }

                $queryAktiva1 = Aktiva::find($id);
                $queryAktiva1->status = 0;
                $queryAktiva1->tglkeluar = Carbon::parse($Request->tglkeluar)->format('Y-m-d');
                $queryAktiva1->nomjual = $Request->nomjual;
                $queryAktiva1->keterangankeluar = $Request->keterangankeluar;
                $queryAktiva1->lastupdatedby =  strtoupper(auth()->user()->username);
                $queryAktiva1->save();

                $queryNumerator = Numerator::where("doc", "AKTIVA_JUAL")
                    ->where("recordownerid", $ownerid)->first();
                $queryNumerator->nomor = $queryNumerator->nomor + 1;
                $queryNumerator->lastupdatedby = strtoupper(auth()->user()->username);
                $queryNumerator->save();

                $queryMutasi = new AktivaMutasi();
                $queryMutasi->aktivaid = $id;
                $queryMutasi->nobukti = $numerator;
                $queryMutasi->tanggal = Carbon::parse($Request->tglkeluar)->format('Y-m-d');
                $queryMutasi->nominal = $nomperolehan * -1;
                $queryMutasi->createdby = strtoupper(auth()->user()->username);
                $queryMutasi->lastupdatedby = strtoupper(auth()->user()->username);
                $queryMutasi->save();

                $queryHistory = new AktivaHistory();
                $queryHistory->aktivaid = $id;
                $queryHistory->tanggal = Carbon::parse($Request->tglkeluar)->format('Y-m-d');
                $queryHistory->nominal = $nompenyusutan; //$Request->nomjual;
                $queryHistory->keterangan = "Penjualan Aktiva";
                $queryHistory->createdby = strtoupper(auth()->user()->username);
                $queryHistory->lastupdatedby = strtoupper(auth()->user()->username);
                $queryHistory->save();

                $blnresult = true;
                $message = "Proses jual aktiva berhasil.";
                DB::commit();
            }catch (Exception $ex){
                $blnresult = false;
                $message = $ex->getMessage();
                DB::rollback();
            }

        }
        return response()->json(array('success' => $blnresult, 'message' => $message));
    }
    
    public function laporanAktivaIndex(Request $Request)
    {
        $bulan = ['01' => 'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL','05'=>'MEI','06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS','09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER'];
        $tahun = [];
        for($i = date('Y'); $i>= date('Y')-5; $i--){
            $tahun[] = $i;
        }
        $id_gudang = $Request->session()->get('subcabang');
        $aktiva_golongan = Aktiva::select(array('golongan','aktivajenisitemid'))
                            ->where('recordownerid',$id_gudang)
                            ->groupBy('golongan','aktivajenisitemid')->get();
        $aktiva_golongan = $aktiva_golongan->toArray();;
        
        foreach ($aktiva_golongan as $a_key => $value) {
            $aktiva_golongan_instance = AktivaGolongan::where('golongan',$value['golongan'])
                                        ->where('jenisitemid',$value['aktivajenisitemid'])
                                        ->first();
            $aktiva_jenisitem_instance = AktivaJenisItem::where('id',$value['aktivajenisitemid'])
                                        ->first();
            
            if(!empty($aktiva_golongan_instance)){
                $aktiva_golongan[$a_key]['name'] = $aktiva_golongan_instance->keterangan;
                $aktiva_golongan[$a_key]['jenis'] = $aktiva_jenisitem_instance->keterangan;
            }else{
                unset($aktiva_golongan[$a_key]);
            }    
        }
       
    	return view('master.laporanaktiva.index',['bulan'=>$bulan,'tahun'=>$tahun, 'golongan'=>$aktiva_golongan]);
    }

    public function getLaporanPenyusutanData(Request $Request){
      
        $aktiva = [];
        $route = request()->route()->getName();
        $params = $Request->all();
        $id_gudang = $Request->session()->get('subcabang');

        $aktiva = Aktiva::where('recordownerid',$id_gudang)
                            ->with('AktivaJenisItem')
                            ->with('AktivaPenyusutan')
                            ->with('AktivaJenisGudang')
                            ->with('AktivaMutasi')
                            ->with('AktivaSaldo')
                            ->with('AktivaHistory')
                            // ->whereMonth('tglperolehan', '=', $params['bulan'])
                            // ->whereYear('tglperolehan', '=', $params['tahun'])
                            ->where(function ($query) use ($params){
                                if(!empty($params['gol_id'])){
                                    $query->where('golongan',$params['gol_id'])
                                          ->where('aktivajenisitemid',$params['jenisitem_id']);
                                }else {
                                    $query->orderBy('tglperolehan');
                                }   
                            });
                            //->where('id',1371);
                        
        $aktiva = json_encode($aktiva->get());
        
        $data_aktiva = $this->buildResponseLaporanAktiva(json_decode($aktiva,true),$params);
        
        if(!empty($data_aktiva)){
            if($route == "laporanaktiva.cetak"){

                $aktiva_golongan = Aktiva::select(array('golongan','aktivajenisitemid'))
                                    // ->where('recordownerid',$id_gudang)
                                    ->groupBy('golongan','aktivajenisitemid')
                                    ->orderBy('golongan', 'ASC')
                                    ->orderBy('aktivajenisitemid', 'ASC')
                                    ->get();
               
                foreach ($aktiva_golongan as $a_key => $value) {
                    $aktiva_golongan_instance = AktivaGolongan::where('golongan',$value['golongan'])
                                            ->where('jenisitemid',$value['aktivajenisitemid'])
                                            ->first();
                    $aktiva_jenisitem_instance = AktivaJenisItem::where('id',$value['aktivajenisitemid'])
                                            ->first();
                    if(!empty($aktiva_golongan_instance)){
                        $aktiva_golongan[$a_key]['name'] = $aktiva_golongan_instance->keterangan;
                        $aktiva_golongan[$a_key]['jenis'] = $aktiva_jenisitem_instance->keterangan;
                        $aktiva_golongan[$a_key]['jenisitem'] = $aktiva_jenisitem_instance->jenisitem;
                    }else{
                        unset($aktiva_golongan[$a_key]);
                    }    
                }
    
                $aktiva_golongan_array = $aktiva_golongan->toArray();
               
                usort($aktiva_golongan_array, function($a, $b) {
                    return $a['jenisitem'] <=> $b['jenisitem'];
                });
               
                Excel::create('Laporan_Aktiva_'.$params['tahun'].$params['bulan'], function($excel) use ($data_aktiva,$aktiva_golongan_array,$params){
            
                    $excel->setTitle('Laporan Aktiva '.$params['tahun'].$params['bulan']);
                    $excel->setCreator(strtoupper(auth()->user()->username))->setCompany('SAS');
                    $excel->setmanager(strtoupper(auth()->user()->username));
                    $excel->setsubject('Laporan Aktiva');
                    $excel->setlastModifiedBy(strtoupper(auth()->user()->username));
                    $excel->setDescription('Laporan Aktiva');
                    $excel->sheet('LaporanAktiva', function($sheet) use($data_aktiva,$aktiva_golongan_array,$params){
                    $last_row = 1;
                       
                        /////////////Column Number Format//////////
                        $sheet->setColumnFormat([
                            'E' => '#,##0.00',
                            'F' => '#,##0.00',
                            'G' => '#,##0.00', 
                            'H' => '#,##0.00',
                            'I' => '0.00%',
                            'J' => '#,##0.00',
                            'K' => '#,##0.00',
                            'L' => '#,##0.00',
                            'M' => '#,##0.00',
                            'N' => '#,##0.00',
                            'O' => '#,##0.00',
                            'P' => '#,##0.00',
                            'Q' => '#,##0.00',
                            'R' => '#,##0.00',
                            'S' => '#,##0.00',
                            'T' => '#,##0.00',
                            'U' => '#,##0.00',
                            'V' => '#,##0.00',
                            'W' => '#,##0.00',
                            'X' => '#,##0.00',
                            'Y' => '#,##0.00',
                            'Z' => '#,##0.00'
                        ]);
    
                        $init_array = $this->initColumnSheet(29);
                        end($init_array);  
                        $init_array_last_key = key($init_array); 
                        $sheet->setSize($init_array);
    
                        $sheet->row($last_row, array(
                            'Laporan Penyusutan Harta Tetap',
                        ));
                        $sheet->mergeCells("A1:B1");
                        $last_row = $last_row+2;
    
                        $sheet->row($last_row, array(
                            'Periode : ',
                            $params['tahun'].$params['bulan']
                        ));
                        $last_row = $last_row+1;
    
                        $sheet->row($last_row, array(
                            "Kode Gudang : ",
                            $data_aktiva[0]['aktiva_jenis_gudang']['kodesubcabang']
                        ));
                        $last_row = $last_row+3;
                       
                        foreach ($aktiva_golongan_array as $g_key => $golongan) {
                           
                            $filter_by_golongan = array_filter($data_aktiva, function($aktiva) use ($golongan,$params) {
                                if(
                                    ($aktiva['golongan'] == $golongan['golongan']) &&
                                    ($aktiva['aktivajenisitemid'] == $golongan['aktivajenisitemid'])
                                  )
                                {
                                    return true;
                                }
                            });
    
                            // usort($filter_by_golongan, function($a, $b) {
                            //     return Carbon::parse($a['tglperolehan']) <=> Carbon::parse($b['tglperolehan']);
                            // });
                           
                                //usort($filter_by_golongan, function($a, $b) {
                                //   if(Carbon::parse($a['tglperolehan']) == Carbon::parse($b['tglperolehan'])){
                                //       if($a['noaktiva'] == $b['noaktiva']){
                                //           return $a['namaaktiva'] - $b['namaaktiva'];
                                //       }else{
                                //           return $a['noaktiva'] - $b['noaktiva'];
                                //       }
                                //   }else{
                                //        return Carbon::parse($a['tglperolehan']) <=> Carbon::parse($b['tglperolehan']);
                                //   }
                                //});
                           
                                usort($filter_by_golongan, function($a, $b) {
                                   if($a['noaktiva'] == $b['noaktiva']){
                                       if(Carbon::parse($a['tglperolehan']) == Carbon::parse($b['tglperolehan'])){
                                           return strcmp($a["namaaktiva"], $b["namaaktiva"]);
                                       }else{
                                           return Carbon::parse($a['tglperolehan']) <=> Carbon::parse($b['tglperolehan']);
                                       }
                                   }else{
                                        return $a['noaktiva'] - $b['noaktiva'];
                                   }
                                });
                                
                                $sheet->row($last_row, array(
                                    '['.$golongan['jenisitem'].'] '.$golongan['jenis'],
                                ));
    
                                $cellJenis = 'A'.$last_row;
                                $cellJenisToMerge = 'B'.$last_row;
                                
                                $sheet->mergeCells($cellJenis.":".$cellJenisToMerge);
                                $sheet->cell('A'.$last_row, function($cell){
                                    $cell->setValignment('center');
                                    $cell->setAlignment('left');
                                });
    
                                $last_row = $last_row+1;
    
                                $sheet->row($last_row, array(
                                    $golongan['name'],
                                ));
    
                                $cellTitle = 'A'.$last_row;
                                $cellTitleToMerge = 'B'.$last_row;
                                
                                $sheet->mergeCells($cellTitle.":".$cellTitleToMerge);
                                $sheet->cell('A'.$last_row, function($cell){
                                    $cell->setValignment('center');
                                    $cell->setAlignment('left');
                                });
    
                                $last_row = $last_row+2;
                                
                               
                                $sheet->row($last_row, array(
                                    'Kode Aktiva', 'No. Register', 'Nama Item', 'Tanggal', 'Harga Perolehan', 'Mutasi','',
                                    'Harga Perolehan Akhir','Persen Susut','Nilai Buku Awal Tahun',
                                    '','','','','','','','','','','','',
                                    'Nilai Akumulasi','Nilai Akumulasi Total','ADJ/Koreksi','Nilai Buku','Keterangan','Keterangan'
                                ));
                                $sheet->row($last_row, function($row) {
                                    $row->setBackground('#b9cae5');
                                });
    
                                $cellAUpper = "A".$last_row;
                                $cellBUpper = "B".$last_row;
                                $cellCUpper = "C".$last_row;
                                $cellDUpper = "D".$last_row;
                                $cellEUpper = "E".$last_row;
                                $cellHUpper = "H".$last_row;
                                $cellIUpper = "I".$last_row;
                                $cellJUpper = "J".$last_row;
                                // $cellVUpper = "V".$last_row;
                                $cellWUpper = "W".$last_row;
                                $cellXUpper = "X".$last_row;
                                $cellYUpper = "Y".$last_row;
                                $cellZUpper = "Z".$last_row;
                                $cellAAUpper = "AA".$last_row;
                                $cellABUpper = "AB".$last_row;
                                $cellACUpper = "AC".$last_row;
    
                                $cellF = "F".$last_row;
                                $cellG = "G".$last_row;
                                $cellK = "K".$last_row;   
                                $cellV = "V".$last_row;   
    
                                $header_last_row = $last_row;
                                $last_row = $last_row+1;
    
                                $cellABot = "A".$last_row;
                                $cellBBot = "B".$last_row;
                                $cellCBot = "C".$last_row;
                                $cellDBot = "D".$last_row;
                                $cellEBot = "E".$last_row;
                                $cellHBot = "H".$last_row;
                                $cellIBot = "I".$last_row;
                                $cellJBot = "J".$last_row;
                                // $cellVBot = "V".$last_row;
                                $cellWBot = "W".$last_row;
                                $cellXBot = "X".$last_row;
                                $cellYBot = "Y".$last_row;
                                $cellZBot = "Z".$last_row;
                                $cellAABot = "AA".$last_row;
                                $cellABBot = "AB".$last_row;
                                $cellABBot = "AC".$last_row;
    
    
                                $sheet->row($last_row, array(
                                '','','','','','Masuk','Keluar','','','','Januari','Februari','Maret','April',
                                'Mei','Juni','Juli','Agustus','September','Oktober','November','Desember','','','','','Aktiva','Spec','Keluar'
                                ));
                                $sheet->row($last_row, function($row) {
                                    $row->setBackground('#b9cae5');
                                });
                                   
                                $sheet->mergeCells($cellAUpper.":".$cellABot);
                                $sheet->mergeCells($cellBUpper.":".$cellBBot);
                                $sheet->mergeCells($cellCUpper.":".$cellCBot);
                                $sheet->mergeCells($cellDUpper.":".$cellDBot);
                                $sheet->mergeCells($cellEUpper.":".$cellEBot);
                                $sheet->mergeCells($cellHUpper.":".$cellHBot);
                                $sheet->mergeCells($cellIUpper.":".$cellIBot);
                                $sheet->mergeCells($cellJUpper.":".$cellJBot);
                                $sheet->mergeCells($cellWUpper.":".$cellWBot);
                                $sheet->mergeCells($cellXUpper.":".$cellXBot);
                                $sheet->mergeCells($cellYUpper.":".$cellYBot);
                                $sheet->mergeCells($cellZUpper.":".$cellZBot);
                                $sheet->mergeCells($cellAAUpper.":".$cellACUpper);
                                // $sheet->mergeCells($cellVUpper.":".$cellVBot);
                                // $sheet->mergeCells($cellABUpper.":".$cellABBot);
                                $sheet->mergeCells($cellF.":".$cellG);
                                $sheet->mergeCells($cellK.":".$cellV);
    
                                $sheet->cell($cellAUpper.':AC'.$header_last_row, function($cell){
                                    $cell->setValignment('top');
                                    $cell->setAlignment('left');
                                });
    
                                $sheet->cell('A'.$last_row.':Z'.$last_row, function($cell){
                                    $cell->setValignment('top');
                                    $cell->setAlignment('right');
                                });
    
                                $sheet->cell('AA'.$last_row.':AC'.$last_row, function($cell){
                                    $cell->setValignment('top');
                                    $cell->setAlignment('center');
                                });
    
                                $sheet->cell($cellF, function($cell){
                                    $cell->setValignment('top');
                                    $cell->setAlignment('center');
                                });
    
                                $sheet->cell($cellK, function($cell){
                                    $cell->setValignment('top');
                                    $cell->setAlignment('center');
                                });
    
                                $sheet->cell($cellAAUpper, function($cell){
                                    $cell->setValignment('top');
                                    $cell->setAlignment('center');
                                });
    
                                $sheet->cell('B3', function($cell){
                                    $cell->setValignment('top');
                                    $cell->setAlignment('left');
                                });
    
                                $sheet->cell('A1', function($cell){
                                    $cell->setValignment('center');
                                    $cell->setAlignment('left');
                                    $cell->setFontWeight('bold');
                                    $cell->setFontSize(16);
                                });
                                
                            if(!empty($filter_by_golongan)){
                                $row_for_sum = $last_row+1;
                                foreach ($filter_by_golongan as $d_key => $data) {
                                    $last_row++;
                                    $sheet->row($last_row, array(
                                        // $data['aktiva_jenis_gudang']['kodesubcabang'], 
                                        $data['noaktiva'],
                                        $data['noregister'],
                                        $data['namaaktiva'],
                                        $data['tglperolehan'],
                                        $this->stringToNumber($data['nomperolehan'],'double'),
                                        $this->stringToNumber($data['aktiva_mutasi']['nominal_credit'],'double'),
                                        $this->stringToNumber($data['aktiva_mutasi']['nominal_debit'],'double'),
                                        $this->stringToNumber($data['aktiva_mutasi']['harga_perolehan_akhir'],'double'),
                                        (double)($data['persen_susut_excel'])/100,
                                        $this->stringToNumber($data['aktiva_saldo']['nilai_buku_awal_tahun'],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['data_per_periode'][0],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['data_per_periode'][1],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['data_per_periode'][2],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['data_per_periode'][3],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['data_per_periode'][4],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['data_per_periode'][5],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['data_per_periode'][6],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['data_per_periode'][7],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['data_per_periode'][8],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['data_per_periode'][9],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['data_per_periode'][10],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['data_per_periode'][11],'double'),
                                        $this->stringToNumber($data['aktiva_penyusutan']['nilai_akumulasi_penyusutan'],'double'), 
                                        $this->stringToNumber($data['aktiva_penyusutan']['nilai_akumulasi_penyusutan_total'],'double'),
                                        $this->stringToNumber($data['aktiva_history']['nilai_koreski'],'double'), 
                                        $this->stringToNumber($data['nilai_buku'],'double'), 
                                        $data['keterangan'], 
                                        $data['spec'], 
                                        $data['keterangankeluar'], 
                                    ));
                                    $sheet->cell('D'.$last_row.':Z'.$last_row, function($cell){
                                        $cell->setValignment('center');
                                        $cell->setAlignment('right');
                                    });
                                }
                            }else{
                                $row_for_sum = $last_row+1;
                                $last_row++;
                            }
    
                            $sheet->setBorder($cellAUpper.':AC'.$last_row, 'thin');
                            // $sheet->setCellValue('D'.($last_row+1),'=SUM(D'.$row_for_sum.':D'.$last_row.')');
                            $sheet->setCellValue('E'.($last_row+1),'=SUM(E'.$row_for_sum.':E'.$last_row.')');
                            $sheet->setCellValue('F'.($last_row+1),'=SUM(F'.$row_for_sum.':F'.$last_row.')');
                            $sheet->setCellValue('G'.($last_row+1),'=SUM(G'.$row_for_sum.':G'.$last_row.')');
                            $sheet->setCellValue('I'.($last_row+1),'=SUM(I'.$row_for_sum.':I'.$last_row.')');
                            $sheet->setCellValue('J'.($last_row+1),'=SUM(J'.$row_for_sum.':J'.$last_row.')');
                            $sheet->setCellValue('K'.($last_row+1),'=SUM(K'.$row_for_sum.':K'.$last_row.')');
                            $sheet->setCellValue('L'.($last_row+1),'=SUM(L'.$row_for_sum.':L'.$last_row.')');
                            $sheet->setCellValue('M'.($last_row+1),'=SUM(M'.$row_for_sum.':M'.$last_row.')');
                            $sheet->setCellValue('N'.($last_row+1),'=SUM(N'.$row_for_sum.':N'.$last_row.')');
                            $sheet->setCellValue('O'.($last_row+1),'=SUM(O'.$row_for_sum.':O'.$last_row.')');
                            $sheet->setCellValue('P'.($last_row+1),'=SUM(P'.$row_for_sum.':P'.$last_row.')');
                            $sheet->setCellValue('Q'.($last_row+1),'=SUM(Q'.$row_for_sum.':Q'.$last_row.')');
                            $sheet->setCellValue('R'.($last_row+1),'=SUM(R'.$row_for_sum.':R'.$last_row.')');
                            $sheet->setCellValue('S'.($last_row+1),'=SUM(S'.$row_for_sum.':S'.$last_row.')');
                            $sheet->setCellValue('T'.($last_row+1),'=SUM(T'.$row_for_sum.':T'.$last_row.')');
                            $sheet->setCellValue('U'.($last_row+1),'=SUM(U'.$row_for_sum.':U'.$last_row.')');
                            $sheet->setCellValue('V'.($last_row+1),'=SUM(V'.$row_for_sum.':V'.$last_row.')');
                            $sheet->setCellValue('W'.($last_row+1),'=SUM(W'.$row_for_sum.':W'.$last_row.')');
                            $sheet->setCellValue('X'.($last_row+1),'=SUM(X'.$row_for_sum.':X'.$last_row.')');
                            $sheet->setCellValue('Y'.($last_row+1),'=SUM(Y'.$row_for_sum.':Y'.$last_row.')');
                            $sheet->setCellValue('Z'.($last_row+1),'=SUM(Z'.$row_for_sum.':Z'.$last_row.')');
                            $last_row = $last_row + 3;
                          }
                    });
                })->store('xls', storage_path('excel/exports'));
                $nama_file_excel = 'Laporan_Aktiva_'.$params['tahun'].$params['bulan'];
                // return response()->download(storage_path("excel/exports/Laporan_Aktiva_".$params['tahun'].$params['bulan'].".xls"));
                return view('master.laporanaktiva.cetak',['id_gudang'=>$data_aktiva[0]["aktiva_jenis_gudang"]["kodesubcabang"],'bulan'=>$params['bulan'],'tahun'=>$params['tahun'],'golongan' => $aktiva_golongan_array,'file_excel'=>$nama_file_excel]);
            }else{
    
                usort($data_aktiva, function($a, $b) {
                    if(Carbon::parse($a['tglperolehan']) == Carbon::parse($b['tglperolehan'])){
                        if($a['noaktiva'] == $b['noaktiva']){
                            return strcmp($a["namaaktiva"], $b["namaaktiva"]);
                        }else{
                            return $a['noaktiva'] - $b['noaktiva'];
                        }
                    }else{
                         return Carbon::parse($a['tglperolehan']) <=> Carbon::parse($b['tglperolehan']);
                    }
                 });
    
                return response()->json([
                    'data' => $data_aktiva
                ]);
            }
        }else{
            // return response()->json([
            //     'status' => false,
            //     'message' => 'Data Kosong'
            // ]);
            if($Request->ajax()){
                return response()->json([
                    'data' => $data_aktiva
                ]);
            }
            return view("master.laporanaktiva.message");
        }
        
       
    }

    public function download(Request $request){
        $params= $request->all();

        if($params['type'] == 'excel'){
            $path = "excel/exports/".$params['file'].'.xls';
        }

        return response()->download(storage_path($path));
    }

    private function stringToNumber($string,$type){
        $result = '';
        if(!empty($string)){
            $result = str_replace(',','',$string);
            switch ($type) {
                case 'double':
                    $result = (double)$result;
                    break;
                default:
                    $result = (int)$result;
                    break;
            }
        }
        
        return $result;
    }

    private function buildResponseLaporanAktiva($data,$params){
        $this->params = $params;
        $response = $data;
       
        foreach ($data as $d_key => $value) {

            $response[$d_key]['aktiva_mutasi']['nominal_debit'] = 0.00;
            $response[$d_key]['aktiva_mutasi']['nominal_credit'] = 0.00;

            /////////////////////////Perhitungan Harga Perolehan//////////////////////////
            $perolehan_date = Carbon::parse($value["tglperolehan"]);
            $tanggal_keluar = Carbon::parse($value["tglkeluar"]);
            $response[$d_key]['nomperolehan'] = $value['nomperolehan'];

            if(!($perolehan_date->format('Y') < $params['tahun'])){
                if($perolehan_date->format('m') >= $params['bulan'] && $perolehan_date->format('m') >= $params['bulan']){
                    $response[$d_key]['nomperolehan'] = 0.00;
                }
            }

            $value['nomperolehan'] = $response[$d_key]['nomperolehan'];
            $response[$d_key]['nomperolehan'] = number_format($response[$d_key]['nomperolehan'], 2, '.', ',');;
            $response[$d_key]['tglperolehan'] = $perolehan_date->format('d-m-Y');

            /////////////////////////Perhitungan Mutasi//////////////////////////
            $nilai_tambah_mutasi = 0;
            $tgl_perolehan = Carbon::parse($value["tglperolehan"]);
           
            if($params['bulan'] == $tgl_perolehan->format('m') && $params['tahun'] == $tgl_perolehan->format('Y')){
                $nilai_tambah_mutasi = $data[$d_key]['nomperolehan'];
            }
           
            if(!empty($value['aktiva_mutasi'])){
           
                foreach ($value['aktiva_mutasi'] as $mutasi_key => $mutasi) {

                    $mutasi_date = Carbon::parse($mutasi["tanggal"]);

                    //if(($mutasi_date->format('m') == $params['bulan']) && ($mutasi_date->format('Y') == $params['tahun'])) 
                    //{
                        if($mutasi["nominal"] >= 0){
                            if(($mutasi_date->format('m') == $params['bulan']) && ($mutasi_date->format('Y') == $params['tahun'])) {
                                $response[$d_key]['aktiva_mutasi']['nominal_credit'] = $mutasi["nominal"] + $nilai_tambah_mutasi;
                            }
                            //$response[$d_key]['aktiva_mutasi']['nominal_debit'] = $mutasi["nominal"] * -1;
                            $response[$d_key]['aktiva_mutasi']['nominal_debit'] = 0;
                        }else{
                            if(($mutasi_date->format('m') == $params['bulan']) && ($mutasi_date->format('Y') == $params['tahun'])) {
                                $response[$d_key]['aktiva_mutasi']['nominal_debit'] = $mutasi["nominal"] * -1;
                            }
                            $response[$d_key]['aktiva_mutasi']['nominal_credit'] = 0 + $nilai_tambah_mutasi;
                            // $response[$d_key]['aktiva_mutasi']['nominal_debit'] = $mutasi["nominal"] * -1;
                        }
                    //}
                }
            }else{
                $response[$d_key]['aktiva_mutasi']['nominal_credit'] = 0 + $nilai_tambah_mutasi;
                $response[$d_key]['aktiva_mutasi']['nominal_debit'] = 0 * -1;
            }
            
            $response[$d_key]['aktiva_mutasi']['harga_perolehan_akhir'] = ((double)$value['nomperolehan'] - (double)$response[$d_key]['aktiva_mutasi']['nominal_debit'] + (double)$response[$d_key]['aktiva_mutasi']['nominal_credit']);

            /////masukan ke nilai buku sebelum diformat ke currency////
            $this->harga_perolehan_akhir = $response[$d_key]['aktiva_mutasi']['harga_perolehan_akhir'];

            $response[$d_key]['aktiva_mutasi']['nominal_credit']= number_format($response[$d_key]['aktiva_mutasi']['nominal_credit'], 2, '.', ',');
            $response[$d_key]['aktiva_mutasi']['nominal_debit'] = number_format($response[$d_key]['aktiva_mutasi']['nominal_debit'], 2, '.', ',');
            $response[$d_key]['aktiva_mutasi']['harga_perolehan_akhir'] = number_format($response[$d_key]['aktiva_mutasi']['harga_perolehan_akhir'], 2, '.', ',');;

            //////////////////////////Perhitungan % Susut///////////////////////

            $response[$d_key]['persen_susut'] = "0.0%";
            $response[$d_key]['persen_susut_excel'] = 0.00;

            if($value['usiapenyusutan'] != 0){

                if($value['usiapenyusutan'] < 12){
                    $response[$d_key]['persen_susut'] = 100;
                }else{
                    $response[$d_key]['persen_susut'] = ((12 / $value['usiapenyusutan'])*100);
                }

                $response[$d_key]['persen_susut'] = number_format($response[$d_key]['persen_susut'], 2, '.', '')."%";
                $response[$d_key]['persen_susut_excel'] = floatval($response[$d_key]['persen_susut']);
            }
            


            //////////////////////////Nilai Buku Awal Tahun////////////////////////
            $response[$d_key]['aktiva_saldo']['nilai_buku_awal_tahun'] = 0.00;

            if(!empty($value['aktiva_saldo'])){
                $matches_year = [];
                $split_periode = preg_match('/\d{4}/',$value['aktiva_saldo']['periode'],$matches_year);
                if($matches_year[0] == $params['tahun']){
                    $response[$d_key]['aktiva_saldo']['nilai_buku_awal_tahun'] = $value['aktiva_saldo']['nominal'];
                }
            }

            $nilai_buku_awal_tahun = $this->floordec($response[$d_key]['aktiva_saldo']['nilai_buku_awal_tahun']);
            $response[$d_key]['aktiva_saldo']['nilai_buku_awal_tahun'] = number_format($nilai_buku_awal_tahun, 2, '.', ',');

            //////////////////////Penyusutan Tahun Berjalan/////////////////////////
            $this->bulan = array_pad([],12,'') ;
            $this->bulan_tampilan = array_pad([],12,'') ;
            $this->bulan_minus = array_pad([],12,0) ;
           
            if(!empty($value['aktiva_penyusutan'])){
                $fiter_by_periode = array_filter($value['aktiva_penyusutan'], function($input) use ($params,$value) {
                    $penyusutan_date = Carbon::parse($input["tanggal"]);
                    $this->nominal =  $input["nominal"];

                    if(($penyusutan_date->format('m') <= $params['bulan']) && 
                        ($penyusutan_date->format('Y') == $params['tahun'])){
                        $this->bulan[($penyusutan_date->format('m')-1)] = $input["nominal"];
                        $this->bulan_tampilan[($penyusutan_date->format('m')-1)] = number_format($input["nominal"], 2, '.', ',');;
                    } 
                   
                    return true;
                });
            }
            
          
            $response[$d_key]['aktiva_penyusutan']['data_per_periode'] =  $this->bulan_tampilan; 


            //////////////////////////Nilai Akumulasi Penyusutan////////////////////
            $response[$d_key]['aktiva_penyusutan']['nilai_akumulasi_penyusutan'] = number_format(array_sum($this->bulan), 2, '.', ',');;
            
            //////////////////////////Nilai Akumulasi Penyusutan Total////////////////////
            $this->nominal_total = 0.00;
           
            if(!empty($value['usiapenyusutan'])){

                $tanggal_aktiva = Carbon::parse($value['tglperolehan']);
                $set_tanggal_laporan = Carbon::parse("01-{$params['bulan']}-{$params['tahun']}")->endOfMonth(); 
                $nilai_tambah = 0;
                $selisih_bulan = 0;
                $batas_awal = $tanggal_aktiva;
               
                $batas_akhir = $batas_awal->addMonth($value['qtypenyusutan']);
                
                if(Carbon::parse($value['tglperolehan'])->format('d') <= 15){
                    $batas_akhir->subMonth(1);
                }
               
                $tanggal_keluar = Carbon::parse($value['tglkeluar']);

                if($batas_akhir > $tanggal_keluar){
                    $batas_flag_tanggal = $tanggal_keluar;
                }else{
                    $batas_flag_tanggal = $batas_akhir;
                }
               
            
                // var_dump($batas_flag_tanggal->gt($set_tanggal_laporan));

                if($batas_flag_tanggal->gt($set_tanggal_laporan)){
                    $tanggal_aktiva = Carbon::parse($value['tglperolehan']);
                    $batas_awal = $tanggal_aktiva;

                    $iteration = $set_tanggal_laporan->format('Y') - $batas_awal->format('Y');
            
                    $tanggal_aktiva = Carbon::parse($value['tglperolehan']);
                    if($tanggal_aktiva->format('Y') <= $set_tanggal_laporan->format('Y')){
                        for($i = 0; $i <= $iteration; $i++){
                            if(!empty($value['tglkeluar'])){
                                $tgl_keluar = Carbon::parse($value['tglkeluar']);
                                if($i==0){
                                    if($batas_awal->format('d') > 15){
                                        $margin_bulan = 0;
                                    }else{
                                        $margin_bulan = 1;
                                    }

                                    if(($tgl_keluar->format('Y') == $batas_awal->format('Y'))){
                                        $selisih_bulan = ($tgl_keluar->format('m')-$batas_awal->format('m'))+$margin_bulan;
                                    }else{
                                        if($batas_awal->format('Y') == $set_tanggal_laporan->format('Y')){
                                            $selisih_bulan = $selisih_bulan + ($set_tanggal_laporan->format('m')-$batas_awal->format('m'))+ $margin_bulan;
                                        }else{
                                            $selisih_bulan = (12-$batas_awal->format('m'))+$margin_bulan;
                                        }
                                    }

                                }else if($batas_awal->format('Y') == $set_tanggal_laporan->format('Y')){
                                    $tgl_keluar = Carbon::parse($value['tglkeluar']);
                                    if($tgl_keluar->format('Y') == $batas_awal->format('Y')){
                                       
                                        if($tgl_keluar->format('m') < $set_tanggal_laporan->format('m')){
                                            $selisih_bulan = $selisih_bulan + $tgl_keluar->format('m');
                                        }elseif($tgl_keluar->format('m') > $set_tanggal_laporan->format('m')){
                                            $selisih_bulan = $selisih_bulan + $set_tanggal_laporan->format('m');
                                        }
                                    }else{
                                        $selisih_bulan = $selisih_bulan + $set_tanggal_laporan->format('m');
                                    }
                                    // break;
                                }else{
                                    if($tgl_keluar->format('Y') == $batas_awal->format('Y')){
                                        $selisih_bulan = $selisih_bulan + $tgl_keluar->format('m');
                                    }else{
                                        $selisih_bulan = $selisih_bulan + 12;
                                    }
                                }
                            }else{
                
                                if($i==0){
                                   
                                    if($batas_awal->format('d') > 15){
                                        $margin_bulan = 0;
                                    }else{
                                        $margin_bulan = 1;
                                    }

                                    if($batas_awal->format('Y') == $set_tanggal_laporan->format('Y')){
                                        $selisih_bulan = $selisih_bulan +  ($set_tanggal_laporan->format('m')-$batas_awal->format('m')) + $margin_bulan;
                                    }else{
                                        $selisih_bulan = (12-$batas_awal->format('m'))+$margin_bulan;
                                    }
                                   
                                }else if($batas_awal->format('Y') == $set_tanggal_laporan->format('Y')){
                                    $selisih_bulan = $selisih_bulan + $set_tanggal_laporan->format('m');
                                }else{
                                    $selisih_bulan = $selisih_bulan + 12;
                                }
                            }
                            // var_dump($selisih_bulan);
                            $batas_awal->addYear(1);
                        }
                    }else{
                        $nilai_tambah = array_sum($this->bulan);
                    }

                }else{
                   
                    $tanggal_aktiva = Carbon::parse($value['tglperolehan']);
                    $batas_awal = $tanggal_aktiva;
                  
                    $iteration = $batas_flag_tanggal->format('Y') - $batas_awal->format('Y');
                  
                    $tanggal_aktiva = Carbon::parse($value['tglperolehan']);
                  
                    for($i = 0; $i <= $iteration; $i++){
                        if($i==0){
                        
                            if($batas_awal->format('d') > 15){
                                $margin_bulan = 0;
                            }else{
                                $margin_bulan = 1;
                            }

                            if(!empty($value['tglkeluar'])){
                                $tgl_keluar = Carbon::parse($value['tglkeluar']);
                                if($tgl_keluar->format('Y') == $batas_awal->format('Y')){
                                    $selisih_bulan = ($tgl_keluar->format('m')-$batas_awal->format('m'))+$margin_bulan;
                                }else{
                                    $selisih_bulan = (12-$batas_awal->format('m'))+$margin_bulan;
                                }
                            }else{
                                if($batas_awal->format('Y') == $batas_flag_tanggal->format('Y')){
                                    $selisih_bulan = $selisih_bulan +  ($batas_flag_tanggal->format('m')-$batas_awal->format('m')) + $margin_bulan;
                                }else{
                                    $selisih_bulan = (12-$batas_awal->format('m'))+$margin_bulan;
                                }
                            }
                            

                        }else if($batas_awal->format('Y') == $batas_flag_tanggal->format('Y')){
                            if(!empty($value['tglkeluar'])){
                                $tgl_keluar = Carbon::parse($value['tglkeluar']);
                              
                                if($tgl_keluar->format('Y') == $batas_flag_tanggal->format('Y')){
                                    if($tgl_keluar->format('m') < $set_tanggal_laporan->format('m')){
                                        $selisih_bulan = $selisih_bulan + $tgl_keluar->format('m');
                                    }elseif($tgl_keluar->format('m') > $set_tanggal_laporan->format('m')){
                                        $selisih_bulan = $selisih_bulan + $set_tanggal_laporan->format('m');
                                    }
                                }else{
                                    $selisih_bulan = $selisih_bulan + $batas_flag_tanggal->format('m');
                                }
                    
                            }else{
                                $selisih_bulan = $selisih_bulan + $batas_flag_tanggal->format('m');
                            }
                            // break;
                        }else{
                            $selisih_bulan = $selisih_bulan + 12;
                        }
                        // var_dump('i '.$selisih_bulan);
                        $batas_awal->addYear(1);
                    }
                }
            
    
                $nilai_penyusutan =  $this->floordec(($selisih_bulan * ($data[$d_key]['nomperolehan']/$value['usiapenyusutan'])));
               
                $this->nominal_total =$nilai_penyusutan + $nilai_tambah;
            }       
            // dd($this->nominal_total);
            $response[$d_key]['aktiva_penyusutan']['nilai_akumulasi_penyusutan_total'] =  number_format($this->nominal_total, 2, '.', ',');
            // $response[$d_key]['aktiva_penyusutan']['nilai_akumulasi_penyusutan_total'] =  ($this->nominal_total);


            //////////////////////////Nilai ADJ/Koreksi//////////////////////////
            $this->nominal_history_total = 0.00;
            if(!empty($value['aktiva_history'])){
                $fiter_by_periode = array_filter($value['aktiva_history'], function($input) use ($params) {
                    $history_date = Carbon::parse($input["tanggal"]);
                    if(($history_date->format('m') <= $params['bulan']) && ($history_date->format('Y') == $params['tahun'])){
                        $this->nominal_history_total = $this->nominal_history_total + $input['nominal'];
                        return true;
                    } 
                });
            }
    
            $response[$d_key]['aktiva_history']['nilai_koreski'] =  number_format($this->nominal_history_total, 2, '.', ',');

            //////////////////////////Nilai Buku////////////////////////////
            $response[$d_key]['nilai_buku'] = 
            $this->harga_perolehan_akhir - ($this->nominal_total - $this->nominal_history_total);
            $response[$d_key]['nilai_buku'] = number_format($response[$d_key]['nilai_buku'], 2, '.', ',');
        }

        foreach ($response as $r_key => $item) {
            if(!empty($item['tglkeluar'])){
                $tanggal_keluar = Carbon::parse($item['tglkeluar']);

                if($tanggal_keluar->format('Y') < $this->params['tahun']){
                    continue;
                }else if($tanggal_keluar->format('Y') == $this->params['tahun']){
                    if($tanggal_keluar->format('m') < $this->params['bulan']){
                        unset($response[$r_key]);
                    }
                }
            }else{
                continue;
            }
        };

        foreach ($response as $r_key => $item) {
            if(!empty($item['tglperolehan'])){
                $tanggal_perolehan = Carbon::parse($item['tglperolehan']);

                if($tanggal_perolehan->format('Y') < $this->params['tahun']){
                    continue;
                }else if($tanggal_perolehan->format('Y') == $this->params['tahun']){
                    if($tanggal_perolehan->format('m') > $this->params['bulan']){
                        unset($response[$r_key]);
                    }
                }
            }else{
                continue;
            }
        };
       
        return $response;
    }

    private function initColumnSheet($int){
        $init_array = [];
        $alphas = range('A', 'Z');
        $width = 20;
        $j = 0;

        for ($i=1; $i <= $int; $i++) { 

            switch ($i) {
                case 3:
                    $width = 90;
                    $alphabet = $alphas[$i-1];
                    break;
                // case 27:
                //     $width = 60;
                //     $alphabet = $alphas[$i-1];
                //     break;
                
                case ($i > 26 && $i < 52):
                    $alphabet = $alphas[0].$alphas[$j];
                    $j++;

                    if(in_array($i,[27,28,29])){
                        $width = 60;
                    }

                    break;

                default:
                    $width = 20;
                    $alphabet = $alphas[$i-1];
                    break;
            }
                
            $init_array[$alphabet.'1'] = [
                'width'     => $width,
            ];
        }
        return $init_array;
    }

    private function floordec($zahl,$decimals=2){    
        return floor($zahl*pow(10,$decimals))/pow(10,$decimals);
    }

    public function GetLastClosing(Request $Request){
        $data = ClosingDate::selectRaw('acc.closingdate.tglclosing')
                ->where('acc.closingdate.modul','F/A')
                ->where('acc.closingdate.recordownerid', $Request->session()->get("subcabang"))
                ->orderBy('tglclosing', 'desc')
                ->take(1)
                ->first()->tglclosing;
        return response()->json(array('tglclosing' => $data));
    }

    public function closingFAIndex(Request $Request){
        //$tglclosing = $this->dateClosing($Request->session()->get("subcabang"));
        // $bulan = Carbon::parse($tglclosing)->format("m");
        // $tahun = Carbon::parse($tglclosing)->format("Y");
        $bulan = [1 => 'JANUARI',2=>'FEBRUARI',3=>'MARET',4=>'APRIL',5=>'MEI',6=>'JUNI',7=>'JULI',8=>'AGUSTUS',9=>'SEPTEMBER',10=>'OKTOBER',11=>'NOVEMBER',12=>'DESEMBER'];
        $tahun = [];
        for($i = date('Y'); $i>= date('Y')-5; $i--){
            $tahun[] = $i;
        }

        return view('master.aktiva.closingfa', array("bulan" => $bulan, "tahun" => $tahun));
    }

    public function closingFA(Request $Request)
    {
        $message = '';
        $blnsuccess = false;
        \DB::beginTransaction();
    try{

        
        $columns = array(
            0 => array(
               0 => "mstr.perusahaan.id",
               1 => "acc.closingdate.tglclosing"
            )
        );

        $listBulan = array(
            1 => 'JAN',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'APR',
            5 => 'MEI',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AGU',
            9 => 'SEP',
            10 => 'OKT',
            11 => 'NOV',
            12 => 'DES'
        );
        
        $initclosing = ClosingDate::selectRaw(collect($columns[0])->implode(", "))
            ->join("mstr.subcabang","acc.closingdate.recordownerid","mstr.subcabang.id")
            ->join("mstr.cabang","mstr.subcabang.cabangid","mstr.cabang.id")
            ->join("mstr.perusahaan","mstr.cabang.perusahaanid","mstr.perusahaan.id")
            ->where("acc.closingdate.recordownerid",$Request->session()->get("subcabang"))
            ->where("acc.closingdate.modul","F/A")
            ->orderBy("acc.closingdate.tglclosing","desc")
            ->first();

        if (!$initclosing){
            $message = "tidak dapat menemukan tanggal closing terakhir.";
            return response()->json(array("success" => $blnsuccess, "message" => $message));
        }

        

        $tglrequestawal = Carbon::parse($Request->tahun."-".$Request->bulan."-01");
        $tglrequestakhir = Carbon::parse($Request->tahun."-".$Request->bulan."-01")->addMonth()->addDays(-1);
        $tglperiodeawal = Carbon::parse($initclosing->tglclosing)->addDay();
        $tglperiodeakhir = Carbon::parse($initclosing->tglclosing)->addDay()->addMonth()->subDay();
        
        if ($tglrequestakhir < $tglperiodeawal){
            $message = "Periode dipilih sudah closing. Periode closing terakhir : ". Carbon::parse($initclosing->tglclosing)->format('d-m-Y') .". Hubungi Manager Anda!";
        }else if($tglrequestawal > $tglperiodeakhir){
            $message = "Periode dipilih melebihi periode closing. Periode closing terakhir : ". Carbon::parse($initclosing->tglclosing)->format('d-m-Y') .". Hubungi Manager Anda!";
        }else{
            $checkPenyusutan = $this->retrieveDepresiasi($Request);
            if (count($checkPenyusutan) > 0){
                return response()->json(array("success" => false, "message" => "Tidak bisa closing. Masih mempunyai aktiva yang belum disusutkan. Silahkan disusutkan dahulu!"));
            }
            $sqlPA = "
                SELECT
                    aj.keterangan, ano.noperdebet, ano.noperkredit, ano.noperjual, nom.nominal
                FROM acc.aktivajenisitem aj
                INNER JOIN acc.aktivajenisitemnoper ano ON ano.aktivajenisitemid = aj.id
                LEFT JOIN LATERAL
                (
                    SELECT
                        COALESCE(SUM(ap.nominal),0) AS nominal
                    FROM acc.aktiva a
                    INNER JOIN acc.aktivapenyusutan ap ON a.id = ap.aktivaid
                    WHERE a.aktivajenisitemid = aj.id AND ap.tanggal BETWEEN :tanggalmulai AND :tanggalselesai AND a.recordownerid = :recordownerid
                ) AS nom ON true
                WHERE ano.perusahaanid = :perusahaanid AND aj.status = '1' AND nom.nominal > 0
            ";
            
            $dataPA = DB::select($sqlPA, array(
                ":perusahaanid" => $initclosing->id,
                ":tanggalmulai" => Carbon::parse($tglperiodeawal)->toDateString(),
                ":tanggalselesai" => Carbon::parse($tglperiodeakhir)->toDateString(),
                ":recordownerid" => $Request->session()->get("subcabang")
            ));
            
            

            $PAAkumulasi = array();
            $PABeban = array();
            
            foreach($dataPA as $row){
                $PAAkumulasi[] = array(
                    "tanggal" => $tglperiodeakhir,
                    "src" => "PA",
                    "uraian" => "AKUM PENYUSUTAN ". $row->keterangan . " BLN " . $listBulan[$Request->bulan] . " ". $Request->tahun,
                    "debet" => "0",
                    "kredit" => $row->nominal,
                    "dk" => "K",
                    "noperkiraan" => $row->noperkredit
                );
                $PABeban[] = array(
                    "tanggal" => $tglperiodeakhir,
                    "src" => "PA",
                    "uraian" => "BY PENYUSUTAN ". $row->keterangan . " BLN " . $listBulan[$Request->bulan] . " ". $Request->tahun,
                    "debet" => $row->nominal,
                    "kredit" => "0",
                    "dk" => "D",
                    "noperkiraan" => $row->noperdebet
                );
            }
            
            if (count($PAAkumulasi) > 0){
                
                $VJACounter = Numerator::selectRaw("depan, nomor, lebar")
                    ->where("recordownerid",$Request->session()->get("subcabang"))
                    ->where("doc","VJA")
                    ->first();
                $VJANumber = (int)$VJACounter->nomor + 1;
                $VJAFormat = str_pad((int)$VJANumber, (int)$VJACounter->lebar, "0", STR_PAD_LEFT);
                $VJAPeriod = $Request->tahun.$Request->Bulan;
                $VJAResult = $VJAFormat.$VJACounter->depan.$VJAPeriod;
                
                $runPA = new JournalAcc();
                $runPA->recordownerid = $Request->session()->get("subcabang");
                $runPA->tanggal = $tglperiodeakhir;
                $runPA->noreff = $VJAResult;
                $runPA->uraian = "BY PENYUSUTAN AKTIVA BLN " . $listBulan[$Request->bulan] . " " . $Request->tahun;
                $runPA->src = "PA";
                $runPA->createdby = strtoupper(auth()->user()->username);
                $runPA->lastupdatedby = strtoupper(auth()->user()->username);
                $runPA->save();
                
                $VJAUpdate = Numerator::where("doc","VJA")
                    ->where("recordownerid", $Request->session()->get("subcabang"))
                    ->first();
                $VJAUpdate->nomor = (int)($VJACounter->nomor) + 1;
                $VJAUpdate->lastupdatedby = strtoupper(auth()->user()->username);
                $VJAUpdate->save();
                
                for ($i = 0; $i < count($PAAkumulasi); $i++){
                    $runPADetail = new JournalAccDetail();
                    $runPADetail->journalid = $runPA->id;
                    $runPADetail->noperkiraan = $PAAkumulasi[$i]["noperkiraan"];
                    $runPADetail->uraian = $PAAkumulasi[$i]["uraian"];
                    $runPADetail->debet = $PAAkumulasi[$i]["debet"];
                    $runPADetail->kredit = $PAAkumulasi[$i]["kredit"];
                    $runPADetail->dk = $PAAkumulasi[$i]["dk"];
                    $runPADetail->createdby = strtoupper(auth()->user()->username);
                    $runPADetail->lastupdatedby = strtoupper(auth()->user()->username);
                    $runPADetail->save();

                    $runPADetail1 = new JournalAccDetail();
                    $runPADetail1->journalid = $runPA->id;
                    $runPADetail1->noperkiraan = $PABeban[$i]["noperkiraan"];
                    $runPADetail1->uraian = $PABeban[$i]["uraian"];
                    $runPADetail1->debet = $PABeban[$i]["debet"];
                    $runPADetail1->kredit = $PABeban[$i]["kredit"];
                    $runPADetail1->dk = $PABeban[$i]["dk"];
                    $runPADetail1->createdby = strtoupper(auth()->user()->username);
                    $runPADetail1->lastupdatedby = strtoupper(auth()->user()->username);
                    $runPADetail1->save();
                }
            }
            
            $sqlPJA = "
                SELECT
                    a.noaktiva, a.namaaktiva, a.nomperolehan, a.nomjual, a.nompenyusutan, ano.noperkredit, ano.noperjual, ano.noperdebet
                FROM acc.aktiva a
                INNER JOIN acc.aktivajenisitemnoper ano ON a.aktivajenisitemid = ano.aktivajenisitemid
                WHERE ano.perusahaanid = :perusahaanid AND a.status = '0' AND a.tglkeluar BETWEEN :tanggalmulai AND :tanggalselesai AND recordownerid = :recordownerid
                ORDER BY noaktiva
            ";
            
            $dataPJA = DB::select($sqlPJA, array(
                ":perusahaanid" => $initclosing->id,
                ":tanggalmulai" => Carbon::parse($tglperiodeawal)->toDateString(),
                ":tanggalselesai" => Carbon::parse($tglperiodeakhir)->toDateString(),
                ":recordownerid" =>  $Request->session()->get("subcabang")
            ));
            
            
            $PJALabaRugi = array();
            $PJAAkumulasi = array();
            $PJAIden = array();
            $PJAAktiva = array();

            foreach($dataPJA as $row){
                $VJPJACounter = Numerator::selectRaw("depan, nomor, lebar")
                    ->where("recordownerid",$Request->session()->get("subcabang"))
                    ->where("doc","VJPJA")
                    ->first();
                $VJPJANumber = (int)$VJPJACounter->nomor + 1;
                $VJPJAFormat = str_pad((int)$VJPJANumber, (int)$VJPJACounter->lebar, "0", STR_PAD_LEFT);
                $VJPJAPeriod = $Request->tahun.$Request->bulan;
                $VJPJAResult = $VJPJAFormat.$VJPJACounter->depan.$VJPJAPeriod;

                

                $uraian = "ADJ PENJUALAN AKTIVA : ". strtoupper($row->namaaktiva);
                $nomperolehan = $row->nomperolehan;
                $nompenyusutan = $row->nompenyusutan;
                $nomjual = $row->nomjual;
                $nomlabarugi = $nomperolehan - $nomjual - $nompenyusutan;
                
                $runPJA = new JournalAcc();
                $runPJA->recordownerid = $Request->session()->get("subcabang");
                $runPJA->tanggal = Carbon::parse($tglperiodeakhir)->toDateString();
                $runPJA->noreff = $VJPJAResult;
                $runPJA->uraian = $uraian;
                $runPJA->src = "PJA";
                $runPJA->createdby = strtoupper(auth()->user()->username);
                $runPJA->lastupdatedby = strtoupper(auth()->user()->username);
                $runPJA->save();

                $VJPJAUpdate = Numerator::where("doc", "VJPJA")
                    ->where("recordownerid", $Request->session()->get("subcabang"))
                    ->first();
                $VJPJAUpdate->nomor = (int)($VJPJACounter->nomor) + 1;
                $VJPJAUpdate->lastupdatedby = strtoupper(auth()->user()->username);
                $VJPJAUpdate->save();

                if ($nomlabarugi < 0){
                    $runPJALabaRugi = new JournalAccDetail();
                    $runPJALabaRugi->journalid = $runPJA->id;
                    $runPJALabaRugi->noperkiraan =  "7140.10.110";
                    $runPJALabaRugi->uraian = $uraian;
                    $runPJALabaRugi->debet = 0;
                    $runPJALabaRugi->kredit =  ($nomlabarugi * -1);
                    $runPJALabaRugi->dk = "K";
                    $runPJALabaRugi->createdby = strtoupper(auth()->user()->username);
                    $runPJALabaRugi->lastupdatedby = strtoupper(auth()->user()->username);
                    $runPJALabaRugi->save();
                }else{
                    $runPJALabaRugi = new JournalAccDetail();
                    $runPJALabaRugi->journalid = $runPJA->id;
                    $runPJALabaRugi->noperkiraan =  "7140.10.110";
                    $runPJALabaRugi->uraian = $uraian;
                    $runPJALabaRugi->debet = $nomlabarugi;
                    $runPJALabaRugi->kredit =  0;
                    $runPJALabaRugi->dk = "D";
                    $runPJALabaRugi->createdby = strtoupper(auth()->user()->username);
                    $runPJALabaRugi->lastupdatedby = strtoupper(auth()->user()->username);
                    $runPJALabaRugi->save();
                }

                $runPJAIden = new JournalAccDetail();
                $runPJAIden->journalid = $runPJA->id;
                $runPJAIden->noperkiraan = "1501.10.900";
                $runPJAIden->uraian = $uraian;
                $runPJAIden->debet = $nomjual;
                $runPJAIden->kredit = 0;
                $runPJAIden->dk = "D";
                $runPJAIden->createdby = strtoupper(auth()->user()->username);
                $runPJAIden->lastupdatedby = strtoupper(auth()->user()->username);
                $runPJAIden->save();

                $runPJAAktiva = new JournalAccDetail();
                $runPJAAktiva->journalid = $runPJA->id;
                $runPJAAktiva->noperkiraan = $row->noperjual;
                $runPJAAktiva->uraian = $uraian;
                $runPJAAktiva->debet = 0;
                $runPJAAktiva->kredit = $nomperolehan;
                $runPJAAktiva->dk = "D";
                $runPJAAktiva->createdby = strtoupper(auth()->user()->username);
                $runPJAAktiva->lastupdatedby = strtoupper(auth()->user()->username);
                $runPJAAktiva->save();

                $runPJAAkumulasi = new JournalAccDetail();
                $runPJAAkumulasi->journalid = $runPJA->id;
                $runPJAAkumulasi->noperkiraan = $row->noperkredit;
                $runPJAAkumulasi->uraian = $uraian;
                $runPJAAkumulasi->debet = $nompenyusutan;
                $runPJAAkumulasi->kredit = 0;
                $runPJAAkumulasi->dk = "D";
                $runPJAAkumulasi->createdby = strtoupper(auth()->user()->username);
                $runPJAAkumulasi->lastupdatedby = strtoupper(auth()->user()->username);
                $runPJAAkumulasi->save();
            }

            $runCD = new ClosingDate();
            $runCD->recordownerid = $Request->session()->get("subcabang");
            $runCD->modul = "F/A";
            $runCD->tglclosing = $tglperiodeakhir;
            $runCD->lastupdatedby = strtoupper(auth()->user()->username);
            $runCD->lastupdatedtime = Carbon::now("Asia/Jakarta");
            $runCD->save();

            if ($Request->bulan == 12){
                $runAktiva = Aktiva::selectRaw("acc.aktiva.id, acc.aktiva.nombuku")
                    ->where("acc.aktiva.recordownerid",$Request->session()->get("subcabang"))
                    //->where("acc.aktiva.status", "1")
                    ->get();
                for ($i = 0; $i < count($runAktiva); $i++){
                    $runSaldo = new AktivaSaldo();
                    $runSaldo->aktivaid = $runAktiva[$i]["id"];
                    $runSaldo->nominal = $runAktiva[$i]["nombuku"];
                    $runSaldo->periode = ((int)$Request->tahun + 1) . "01";
                    $runSaldo->createdby = strtoupper(auth()->user()->username);
                    $runSaldo->lastupdatedby = strtoupper(auth()->user()->username);
                    $runSaldo->save();
                }
            }
            
            $blnsuccess = true;
            $message = "sukses";
            \DB::commit();
        }
    }catch (\Exception $ex){
        $blnsuccess = false;
        $message = $ex->getMessage();
        \DB::rollBack();
        
    }
        return response()->json(array("success" => $blnsuccess, "message" => $message));
        
    }


}