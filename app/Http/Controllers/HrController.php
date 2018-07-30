<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Models\User;
// use App\Models\NotaPembelian;
// use App\Models\NotaPembelianDetail;
// use App\Models\ReturPembelianDetail;
use App\Models\Numerator;
use App\Models\SubCabang;
use Carbon\Carbon;
use DB;

// use App\Models\OrderPenjualan;
// use App\Models\OrderPenjualanDetail;
// use App\Models\NotaPenjualan;
// use App\Models\SubCabang;
// use App\Models\Expedisi;
// use App\Models\Toko;
// use App\Models\StatusToko;
// use App\Models\HistoryBMK0;
// use App\Models\HistoryBMK1;
// use App\Models\HistoryBMK2;
// use App\Models\AppSetting;
use App\Models\Salesman;


class HrController extends Controller
{
    public function index()
    {
	    return 'lookup';
    }

    public function getSalesman($recowner, $query){
      $sales = Salesman::select('id','kodesales','namakaryawan')
        ->where('namakaryawan', 'ilike', '%'.$query.'%')
        ->where('recordownerid', '=', $recowner)
        ->where('kodesales', '<>', null)
        ->orderBy('kodesales', 'asc')
        ->get();
      return json_encode($sales);
    }


}
