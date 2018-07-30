<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StockScrabDetail extends Model
{
    protected $table    = 'stk.gudangsementaradetail';
    protected $fillable = ['recordownerid','gudangsementaraid','stockid','qtyrq','qtyfinal','keterangan','parentgsid','isarowid','refid','createdby','lastupdatedby'];
    const UPDATED_AT    = 'lastupdatedon';
    const CREATED_AT    = 'createdon';

    public function scrab()
    {
        return $this->belongsTo('App\Models\StockScrab', 'gudangsementaraid');
    }

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
      return $this->hasOne('App\Models\Barang','id','stockid');
    }
}
