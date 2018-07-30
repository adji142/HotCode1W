<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCabang;
use App\Models\LoginLog;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if(!$req->session()->has('tglmulai'))
        {
           $req->session()->put('tglmulai', Carbon::now()->format('d-m-Y'));
        }

        if(!$req->session()->has('tglselesai'))
        {
            $req->session()->put('tglselesai', Carbon::now()->format('d-m-Y'));
        }

        return view('home');
    }

    public function getData(Request $req)
    {
        $columns = array(
            0 => 'mstr.subcabang.kodesubcabang',
            1 => 'mstr.cabang.nama as cabang',
            2 => 'mstr.subcabang.namasubcabang',
            3 => 'mstr.subcabang.initsubcabang',
            4 => 'mstr.subcabang.aktif as aktif',
            5 => 'mstr.subcabang.id',
        );

        $subcabang = SubCabang::join('mstr.cabang', 'mstr.subcabang.cabangid', '=', 'mstr.cabang.id');
        $ors = [];
        foreach (auth()->user()->akses as $key => $value) {
            if($key == 0){
                if (!in_array($value->id, $ors)) $ors[] = $value->id;
                //$subcabang->where('mstr.subcabang.id',$value->id);
            }else{
                if (!in_array($value->id, $ors)) $ors[] = $value->id;
                //$subcabang->orWhere('mstr.subcabang.id',$value->id);
            }
        }

        $subcabang
        ->where("mstr.subcabang.aktif", true)
        ->whereIn("mstr.subcabang.id", $ors);

        $total_data = $subcabang->count();
        $filtered_data = $total_data;
        $data = $subcabang->get($columns);
        return response()->json([
            'draw'              => $req->draw,
            'recordsTotal'      => $total_data,
            'recordsFiltered'   => $filtered_data,
            'data'              => $data,
        ]);
    }

    public function setSubcabang(Request $req)
    {
        $req->session()->put('subcabang', $req->id);
        $columns = array(
            0 => 'mstr.subcabang.kodesubcabang',
            1 => 'mstr.cabang.kodecabang as cabang',
            2 => 'mstr.subcabang.namasubcabang',
            3 => 'mstr.subcabang.initsubcabang',
            4 => 'mstr.subcabang.aktif',
            5 => 'mstr.subcabang.id',
            6 => 'mstr.subcabang.cabangid',
        );
        $subcabang = SubCabang::join('mstr.cabang', 'mstr.subcabang.cabangid', '=', 'mstr.cabang.id')
                    -> where('subcabang.id','=',$req->id);
        $data = $subcabang->get($columns)->toArray();
        $req->session()->put('cabang', $data[0]["cabang"]);
        
        // SET LOGIN LOG
        $log    = LoginLog::find($req->user()->id);
        $ceklog = count($log);

        //cek apakah sudah ada didaftar login ? jika belum tambah data login dengan status login true
        if($ceklog == 0){
            $simpan = new LoginLog;
            $simpan->recordownerid = $req->session()->get('subcabang');
            $simpan->userid        = $req->user()->id;
            $simpan->username      = strtoupper($req->user()->username);
            $simpan->ipaddress     = $req->ip();
            $simpan->browserinfo   = $req->header('User-Agent');
            $simpan->statuslogin   = TRUE;
            $simpan->save();
        }else{
            $log->recordownerid    = $req->session()->get('subcabang');
            $log->ipaddress        = $req->ip();
            $log->browserinfo      = $req->header('User-Agent');
            $log->statuslogin      = TRUE;
            $log->save(); 
        }
        return response()->json([
            'success'   => true,
            'subcabang' => $data[0]['kodesubcabang']

        ]);
    }

}
