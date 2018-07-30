<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StockScrab extends Model
{
    protected $table    = 'stk.gudangsementara';
    const UPDATED_AT    = 'lastupdatedon';
    const CREATED_AT    = 'createdon';
    protected $fillable = ['recordownerid','tgldocument','tgltransaksi','notransaksi','keterangan','approvalmgmtid00','approvalmgmtid11','isarowid','createdby','lastupdatedby','karyawanidstock','karyawanidpemeriksa','karyawanid11acc','tgllink','keterangantolak','tglajuan','tglajuanstok11','createdon','lastupdatedon'];

    public function details()
    {
      return $this->hasMany('App\Models\StockScrabDetail','gudangsementaraid');
    }

	  public function setCreatedonAttribute($date)
    {
        $this->attributes['createdon'] = Carbon::parse($date)->toDateTimeString();
    }
    public function setLastupdatedonAttribute($date)
    {
        $this->attributes['lastupdatedon'] = Carbon::parse($date)->toDateTimeString();
    }
   
     public function getTglajuanstok11Attribute($value)
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
     public function getCreatedonAttribute($value)
    {
      if($value != null){
          return Carbon::parse($value)->format('d-m-Y');
      }else{
          return $value;
      }
    }

    public function getTgltransaksiAttribute($value)
    {
      if($value != null){
          return Carbon::parse($value)->format('d-m-Y');
      }else{
          return $value;
      }
    }
    public function getTgllinkAttribute($value)
    {
      if($value != null){
          return Carbon::parse($value)->format('d-m-Y');
      }else{
          return $value;
      }
    }
    public function getTglajuanAttribute($value)
    {
      if($value != null){
          return Carbon::parse($value)->format('d-m-Y');
      }else{
          return $value;
      }
    }
    public function stafstock()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanidstock');
    }
    public function pemeriksa()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanidpemeriksa');
    }
    public function pemeriksaacc()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanid11acc');
    }
}
