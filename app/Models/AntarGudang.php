<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Karyawan;
use Carbon\Carbon;

class AntarGudang extends Model
{
    protected $table    = 'stk.antargudang';
    protected $fillable = ['recordownerid','norqag','tglnotaag','subcabangidpengirim','subcabangidpenerima','catatan','syncflag','coupleid','createdby','lastupdatedby'];
    const UPDATED_AT    = 'lastupdatedon';
    const CREATED_AT    = 'createdon';

    public function getTglnotaagAttribute($value)
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

    public function getLastupdatedonAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
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

    public function toArray($raw = true)
    {
        $this->attributes['createdon'] = $this->createdon;
        return $raw ? $this->getArrayableAttributes() : parent::toArray();
    }

    public function checkdetails()
    {
        return $this->hasOne('App\Models\AntarGudangDetail','antargudangid')->select('id');
    }

    public function darisubcabang()
    {
        return $this->belongsTo('App\Models\SubCabang','subcabangidpengirim');
    }

    public function kesubcabang()
    {
        return $this->belongsTo('App\Models\SubCabang','subcabangidpenerima');
    }

    public function getKaryawanpengirimAttribute()
    {
        if(Karyawan::find($this->karyawanidpengirim)){
            return Karyawan::find($this->karyawanidpengirim);
        }
    }

    public function getKaryawanpenerimaAttribute()
    {
        if(Karyawan::find($this->karyawanidpenerima)){
            return Karyawan::find($this->karyawanidpenerima)->namakaryawan;
        }
    }

    public function getChecker1pengirimAttribute()
    {
        if(Karyawan::find($this->karyawanidchecker1pengirim)){
            return Karyawan::find($this->karyawanidchecker1pengirim)->namakaryawan;
        }
    }

    public function getChecker2pengirimAttribute()
    {
        if(Karyawan::find($this->karyawanidchecker2pengirim)){
            return Karyawan::find($this->karyawanidchecker2pengirim)->namakaryawan;
        }
    }

    public function getChecker1penerimaAttribute()
    {
        if(Karyawan::find($this->karyawanidchecker1penerima)){
            return Karyawan::find($this->karyawanidchecker1penerima)->namakaryawan;
        }
    }

    public function getChecker2penerimaAttribute()
    {
        if(Karyawan::find($this->karyawanidchecker2penerima)){
            return Karyawan::find($this->karyawanidchecker2penerima)->namakaryawan;
        }
    }

    #region --Created by : Halim  --Date : 24 Jan 2018    --Description : expedisi untuk Antar Gudang V2
    public function details()
    {
        return $this->hasMany('App\Models\AntarGudangDetail','antargudangid');
    }

    #endregion

    public function getSyncflagmutateAttribute()
    {
        if($this->syncflag == 0){
            return 'BELUM SYNCH';
        }else if($this->syncflag == 1){
            return 'SUDAH SYNCH KE PENGIRIM';
        }else if($this->syncflag == 2){
            return 'SUDAH SYNCH KEMBALI KE PENERIMA';
        }else{
            return 'AG SUDAH SELESAI';
        }
    }
}
