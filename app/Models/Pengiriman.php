<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pengiriman extends Model
{
    protected $table    = 'pj.pengiriman';
    protected $fillable = ['recordownerid','tglkirim','nokirim','karyawanidsopir','karyawanidkernet','armadakirimid','kmberangkat','kmkembali','catatan','print','tglprint','tglkembali','createdby','lastupdatedby'];
    const UPDATED_AT    = 'lastupdatedon';
    const CREATED_AT    = 'createdon';

    public function getTglkirimAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function getTglkembaliAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function getTglprintAttribute($value)
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
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }

    public function sopir()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanidsopir');
    }

    public function kernet()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanidkernet');
    }

    public function armadakirim()
    {
        return $this->belongsTo('App\Models\ArmadaKirim','armadakirimid');
    }

    public function details()
    {
        return $this->hasMany('App\Models\PengirimanDetail','pengirimanid');
    }
}
