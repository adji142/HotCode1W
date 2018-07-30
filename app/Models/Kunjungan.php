<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Kunjungan extends Model
{
    protected $table = 'pj.kunjungansales';
    protected $fillable = ['recordownerid','tglkunjung','karyawanidsalesman','tokoid','keteranganrealisasi','rencana','omsetavg','saldopiutang','keterangantambahan','isarowid','createdon','createdby','lastupdatedby'];
    const UPDATED_AT = 'lastupdatedtime';
    const CREATED_AT = 'createdon'; 

    public function toko()
    {
      return $this->belongsTo('App\Models\Toko','tokoid');
    }
    public function karyawan()
    {
      return $this->belongsTo('App\Models\Karyawan','karyawanidsalesman');
    }
    public function getLastupdatedtimeAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }
    public function setCreatedonAttribute($date)
    {
        $this->attributes['createdon'] = Carbon::parse($date)->toDateTimeString();
    }
    public function getCreatedonAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }
    // public function setTglkunjungAttribute($date)
    // {   
    //     if($date == null){
    //        return $date;
    //     }else{
    //        $this->attributes['tglkunjung'] = Carbon::parse($date)->toDateTimeString();
    //     } 
    // }
    public function getTglkunjungAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }
}
