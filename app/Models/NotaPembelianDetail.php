<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NotaPembelianDetail extends Model
{
    protected $table = 'pb.notapembeliandetail';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';

    protected function getNextstatementidAttribute()
    {
        $next_id = \DB::select("select nextval('pb.notapembeliandetail_id_seq')");
        return intval($next_id[0]->nextval);
    }

    public function barang()
    {
        return $this->hasOne('App\Models\Barang','id','stockid');
    }

    public function returdetail()
    {
    	return $this->hasMany('App\Models\ReturPembelianDetail','notapembeliandetailid');
    }

    public function nota()
    {
    	return $this->belongsTo('App\Models\NotaPembelian','notapembelianid');
    }

    public function setTglnotaAttribute($date) 
    {
        $this->attributes['tglnota'] = Carbon::parse($date)->toDateString();
    }

    public function setTglterimaAttribute($date) 
    {
        $this->attributes['tglterima'] = Carbon::parse($date)->toDateString();
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
}
