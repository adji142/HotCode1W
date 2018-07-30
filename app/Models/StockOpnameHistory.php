<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StockOpnameHistory extends Model
{
    protected $table    = 'stk.stockopnamehistory';
    protected $fillable = ['recordownerid','stockid','tglstop','qtyawal','qtybaik','qtyrusak','qtygs','qtyaggit','qtystop','karyawanidpenghitung','karyawanidpemeriksa','tglclosing','keteranganrencana','keteranganhasilhitung','print','syncflag','createdby','lastupdatedby','tglrencana'];
    const UPDATED_AT    = 'lastupdatedon';
    const CREATED_AT    = 'createdon';

	public function getTglrencanaAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function getTglstopAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function getTglclosingAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function getLastupdatedonAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function barang()
    {
        return $this->belongsTo('App\Models\Barang','stockid');
    }

    public function penghitung()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanidpenghitung');
    }

    public function pemeriksa()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanidpemeriksa');
    }
}
