<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReturPenjualan extends Model
{
    protected $table = 'pj.returpenjualan';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';

    /* MUTATOR */
    public function setCreatedonAttribute($date) 
    {
        $this->attributes['createdon'] = Carbon::parse($date)->toDateTimeString();
    }

    public function setLastupdatedonAttribute($date)
    {
        $this->attributes['lastupdatedon'] = Carbon::parse($date)->toDateTimeString();
    }

    public function getCreatedonAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }

    public function getLastupdatedonAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }

    public function getTglmprAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function getTglkirimAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function getTglnotareturAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function getTglsppbAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function getTglprintsppbAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }

    public function getTglprintnrjAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }

    /* RELATIONS */
    public function details()
    {
        return $this->hasMany('App\Models\ReturPenjualanDetail','returpenjualanid');
    }

    public function subcabang()
    {
        return $this->belongsTo('App\Models\SubCabang','recordownerid');
    }

    public function kartupiutangdetail()
    {
        return $this->belongsTo('App\Models\KartuPiutangDetail','kartupiutangdetailid');
    }

    public function omsetsubcabang()
    {
        return $this->belongsTo('App\Models\SubCabang','omsetsubcabangid');
    }

    public function pengirimsubcabang()
    {
        return $this->belongsTo('App\Models\SubCabang','pengirimsubcabangid');
    }

    public function toko()
    {
        return $this->belongsTo('App\Models\Toko','tokoid');
    }

    public function expedisi()
    {
        return $this->belongsTo('App\Models\Expedisi','Expedisiid');
    }

    public function staffpenjualan()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanidpenjualan');
    }

    public function staffstock()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanidstock');
    }

    public function staffexpedisi()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanidexpedisi');
    }

}
