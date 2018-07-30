<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReturPembelian extends Model
{
    protected $table = 'pb.returpembelian';
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

    public function getLastupdatedonAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }

    // public function setTglprbAttribute($date)
    // {
    //     $this->attributes['tglprb'] = Carbon::parse($date)->toDateString();
    // }

    public function getTglprbAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    // public function setTglkirimAttribute($date)
    // {
    //     $this->attributes['tglkirim'] = Carbon::parse($date)->toDateString();
    // }

    public function getTglkirimAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    // public function setTglnrj11Attribute($date)
    // {
    //     $this->attributes['tglnrj11'] = Carbon::parse($date)->toDateString();
    // }

    public function getTglnrj11Attribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    /* RELATIONS */
    public function details()
    {
        return $this->hasMany('App\Models\ReturPembelianDetail','returpembelianid');
    }

    public function subcabang()
    {
        return $this->belongsTo('App\Models\SubCabang','recordownerid');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier','supplierid');
    }

    public function expedisi()
    {
        return $this->belongsTo('App\Models\Expedisi','expedisiid');
    }

    public function pemeriksa00()
    {
        return $this->belongsTo('App\Models\Karyawan','staffidpemeriksa00');
    }
}
