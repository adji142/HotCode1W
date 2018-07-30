<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OrderPenjualan extends Model
{
  protected $table = 'pj.orderpenjualan';
  const UPDATED_AT = 'lastupdatedon';
  const CREATED_AT = 'createdon';
  protected $fillable = [
    'recordownerid','omsetsubcabangid','pengirimsubcabangid','noso','tglso','nopickinglist','tglpickinglist','noaccpiutang','karyawanidpkp',
    'tglaccpiutang','rpaccpiutang','rpsaldopiutang','rpsaldooverdue','rpsoaccproses','rpgit','tokoid','approvalmgmtidoverdue','statustoko',
    'temponota','tempokirim','temposalesman','tipetransaksi','karyawanidsalesman','catatanpenjualan','catatanpembayaran','catatanpengiriman',
    'print','tglprintpickinglist','tglterimapilpiutang','statusajuanhrg11','isarowid uuid','createdby','createdon','lastupdatedby','lastupdatedon','expedisiid'
  ];

  public function details()
  {
    return $this->hasMany('App\Models\OrderPenjualanDetail','orderpenjualanid');
  }

  public function toko()
  {
    return $this->belongsTo('App\Models\Toko','tokoid');
  }

  public function salesman()
  {
    return $this->belongsTo('App\Models\Karyawan','karyawanidsalesman');
  }

  public function karyawanpkp()
  {
    return $this->belongsTo('App\Models\Karyawan','karyawanidpkp');
  }

  public function c1()
  {
    return $this->belongsTo('App\Models\SubCabang','omsetsubcabangid');
  }

  public function c2()
  {
    return $this->belongsTo('App\Models\SubCabang','pengirimsubcabangid');
  }

  public function expedisi()
  {
    return $this->belongsTo('App\Models\Expedisi','expedisiid');
  }

  public function nota()
  {
    return $this->hasMany('App\Models\NotaPenjualan','orderpenjualanid');
  }

  public function kp()
  {
    return $this->hasMany('App\Models\KartuPiutang','tokoid','tokoid');
  }

  public function plafon()
  {
    return $this->hasMany('App\Models\Plafon','tokoid','tokoid');
  }

  public function statstoko()
  {
    return $this->hasMany('App\Models\StatusToko','tokoid','tokoid');
  }

  public function hargahppa()
  {
    return $this->hasMany('App\Models\HPPA','stockid','stockid');
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

  public function getTglsoAttribute($value)
  {
    if($value != null){
        return Carbon::parse($value)->format('d-m-Y');
    }else{
        return $value;
    }
  }

  public function getTglpickinglistAttribute($value)
  {
    if($value != null){
        return Carbon::parse($value)->format('d-m-Y');
    }else{
        return $value;
    }
  }

  public function getTglaccpiutangAttribute($value)
  {
    if($value != null){
        return Carbon::parse($value)->format('d-m-Y');
    }else{
        return $value;
    }
  }

  public function getTglprintpickinglistAttribute($value)
  {
    if($value != null){
        return Carbon::parse($value)->format('d-m-Y');
    }else{
        return $value;
    }
  }

  public function getTglterimapilpiutangAttribute($value)
  {
    if($value != null){
        return Carbon::parse($value)->format('d-m-Y');
    }else{
        return $value;
    }
  }

  public function getLaststatustokoAttribute()
  {
    return $this->statstoko->sortByDesc('tglaktif')->first();
  }

  public function getHppaperstockAttribute()
  {
    return $this->hargahppa->where('tglaktif','<',Carbon::parse($this->tglpickinglist))->sortByDesc('tglaktif')->first();
  }

  public function getHargabmkstockAttribute()
  {
    $statusbmk = strtolower($this->statustoko);
    $harga = HistoryBMK0::select('hrgb0 as hrgb','hrgm0 as hrgm','hrgk0 as hrgk')->where([['stockid', $this->stockid],['tglpasif',null],['tglaktif','<',Carbon::parse($this->tglpickinglist)]])->orderBy('tglaktif','desc')->first();
    if ($harga == null) {
      if (substr($statusbmk,1) == 1) {
        $harga = HistoryBMK1::select('hrgb1 as hrgb','hrgm1 as hrgm','hrgk1 as hrgk')->where('stockid', $this->stockid)->where('tglaktif','<',Carbon::parse($this->tglpickinglist))->orderBy('tglaktif','desc')->first();
      }else {
        $harga = HistoryBMK2::select('hrgb2 as hrgb','hrgm2 as hrgm','hrgk2 as hrgk')->where('stockid', $this->stockid)->where('tglaktif','<',Carbon::parse($this->tglpickinglist))->orderBy('tglaktif','desc')->first();
      }
    }

    if($harga != null)
      if(substr($statusbmk,0,1) == 'b'){
        $finalharga = $harga->hrgb;
      }elseif(substr($statusbmk,0,1) == 'm'){
        $finalharga = $harga->hrgm;
      }else{
        $finalharga = $harga->hrgk;
      }
    else
      // mungkin di ganti 
      $finalharga = 1;
    return $finalharga;
  }

  public function checkdetails()
  {
    return $this->hasOne('App\Models\OrderPenjualanDetail','orderpenjualanid')->select('id');
  }

  public function checknota()
  {
    return $this->hasOne('App\Models\NotaPenjualan','orderpenjualanid')->select('id');
  }
}
