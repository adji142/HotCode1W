<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NotaPembelian extends Model
{
    protected $table = 'pb.notapembelian';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';

    protected function getNextstatementidAttribute()
    {
        $next_id = \DB::select("select nextval('pb.notapembelian_id_seq')");
        return intval($next_id[0]->nextval);
    }


    public function details()
    {
        return $this->hasMany('App\Models\NotaPembelianDetail','notapembelianid');
    }

    public function supplier()
    {
    	return $this->belongsTo('App\Models\Supplier','supplierid');
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\Karyawan','staffidpemeriksa00');
    }

    public function pusat()
    {
    	return $this->belongsTo('App\Models\User','staffidpemeriksa11');
    }

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

    public function getTglnotaAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function getTglterimaAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }
}
