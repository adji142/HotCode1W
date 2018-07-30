<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Models\User;
// use App\Models\NotaPembelian;
// use App\Models\NotaPembelianDetail;
// use App\Models\ReturPembelianDetail;
use App\Models\Numerator;
use Carbon\Carbon;
use DB;


use App\Models\SubCabang;
use App\Models\Cabang;
use App\Models\Toko;
use App\Models\KategoriPenjualan;
// use App\Models\StatusToko;
// use App\Models\HistoryBMK0;
// use App\Models\HistoryBMK1;
// use App\Models\HistoryBMK2;
// use App\Models\AppSetting;
// use App\Models\Salesman;

class MstrController extends Controller
{
    public function index()
    {
	    return 'lookup';
    }

    public function getToko($query){
      $toko = Toko::select('mstr.toko.id','namatoko','alamat','kodetoko','customwilayah','kota')
        ->where('namatoko', 'ilike', '%'.$query.'%')
        ->get();
      return json_encode($toko);
    }

    public function getSubCabang(){
      $data = SubCabang::select('mstr.subcabang.id','kodesubcabang', 'cabangid','namasubcabang','initsubcabang','aktif')
      // ->where('namatoko', 'ilike', '%'.$query.'%')
          ->orderBy('kodesubcabang', 'asc')
          ->get();
      
      return response()->json([
          'data'      => $data,
      ]);
    }

    public function getKategoriPenjualan(){
        $data = KategoriPenjualan::select('mstr.kategoripenjualan.id','kategori')
            ->orderBy('kategori', 'asc')
            ->get();
        
        return response()->json([
            'data'            => $data,
        ]);
      // return json_encode($katPenj);
    }


}

