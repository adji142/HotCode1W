<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NotaPenjualan extends Model
{
    protected $table = 'pj.notapenjualan';
    protected $fillable = ['recordownerid','orderpenjualanid','omsetsubcabangid','pengirimsubcabangid','tokoid','karyawanidsalesman','tipetransaksi','temponota','tempokirim','temposalesman','nonota','tglproforma','printproforma','printnota','createdby','lastupdatedby'];
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';

    protected $status = [
        0 => 'BELUM CETAK',
        1 => 'SUDAH 1 KALI DICETAK',
        2 => 'SUDAH 2 KALI DICETAK',
        3 => 'SUDAH CETAK 3 KALI',
    ];

    public function setCreatedonAttribute($date) 
    {
        $this->attributes['createdon'] = Carbon::parse($date)->toDateTimeString();
    }

    public function setLastupdatedonAttribute($date)
    {
        $this->attributes['lastupdatedon'] = Carbon::parse($date)->toDateTimeString();
    }

    public function setTglnotaAttribute($date) 
    {
        if($date){
            $this->attributes['tglnota'] = Carbon::parse($date)->toDateString(); 
        }else{
            $this->attributes['tglnota'] = NULL;
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

    public function getTglnotaAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function getTglcheckAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }

    public function getTglproformaAttribute($value)
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

    public function getPrintproformastatusAttribute($value)
    {
        return $this->status[$this->attributes['printproforma']];
    }

    public function getPrintnotastatusAttribute($value)
    {
        return $this->status[$this->attributes['printnota']];
    }

	public function orderpenjualan()
    {
    	return $this->belongsTo('App\Models\OrderPenjualan','orderpenjualanid');
    }

	public function details()
    {
        return $this->hasMany('App\Models\NotaPenjualanDetail','notapenjualanid');
    }

    public function c1()
    {
        return $this->belongsTo('App\Models\SubCabang','omsetsubcabangid');
    }

    public function c2()
    {
        return $this->belongsTo('App\Models\SubCabang','pengirimsubcabangid');
    }

    public function checker1()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanidchecker1');
    }

    public function checker2()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanidchecker2');
    }

    public function toko()
    {
        return $this->belongsTo('App\Models\Toko','tokoid');
    }

    public function salesman()
    {
        return $this->belongsTo('App\Models\Karyawan','karyawanidsalesman');
    }

    public function kp()
    {
        return $this->hasMany('App\Models\KartuPiutang','notaid');
    }

    public function kpd()
    {
        return $this->hasMany('App\Models\KartuPiutangDetail','notaid');
    }

    public function suratjalandetail()
    {
        return $this->hasMany('App\Models\SuratJalanDetail','notapenjualanid');
    }

}
