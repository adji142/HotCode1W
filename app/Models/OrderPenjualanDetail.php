<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OrderPenjualanDetail extends Model
{
  protected $table = 'pj.orderpenjualandetail';
  const UPDATED_AT = 'lastupdatedon';
  const CREATED_AT = 'createdon';
  protected $fillable = [
    'orderpenjualanid','stockid','qtyso','qtysoacc','hrgbmk','hrgsatuanbrutto','disc1','disc2','ppn','hrgsatuannetto','approvalmgmtidacc11',
    'noacc11','catatan','qtystockgudang','komisikhusus11','orderpembeliandetailid','isarowid','createdby','createdon','lastupdatedby','lastupdatedon'
  ];

  public function barang()
  {
    return $this->hasOne('App\Models\Barang','id','stockid');
  }

  public function order()
  {
    return $this->belongsTo('App\Models\OrderPenjualan', 'orderpenjualanid');
  }

  public function notadetail()
  {
    return $this->hasMany('App\Models\NotaPenjualanDetail','orderpenjualandetailid');
  }

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
}
