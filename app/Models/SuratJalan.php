<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\NotaPenjualan;

class SuratJalan extends Model
{
    protected $table    = 'pj.suratjalan';
    protected $fillable = ['recordownerid','nosj','tglsj','tokoid','tglproforma','keterangansj','titipanketerangan','titipandari','titipanuntuk','titipanalamat','titipannotelepon','tipetransaksi','createdby','lastupdatedby'];
    const UPDATED_AT    = 'lastupdatedon';
    const CREATED_AT    = 'createdon';

    public function getTglsjAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
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

    public function getTokoidStatusAttribute()
    {
        if($this->tokoid == null){
            return false;
        }else{
            return true;
        }
    }

    public function npjdk()
    {
        return $this->hasMany('App\Models\NotaPenjualanDetailKoli','suratjalanid');
    }

    public function toko()
    {
        return $this->belongsTo('App\Models\Toko','tokoid');
    }

    public function details()
    {
        return $this->hasMany('App\Models\SuratJalanDetail','suratjalanid');
    }

    public function titipan()
    {
        return $this->hasMany('App\Models\SuratJalanDetailTitipan','suratjalanid');
    }

    public function pengirimandetail()
    {
        return $this->hasMany('App\Models\PengirimanDetail','suratjalanid');
    }

    public function totalkoli()
    {
        $node_koli = array();
        foreach ($this->titipan as $titipan) {
            $temp_koli = $titipan->nokoli;
            array_push($node_koli, $temp_koli);
        }

        foreach ($this->details as $sjd) {
            $npj = $sjd->nota;
            if(!$npj->details->isEmpty()){
                foreach ($npj->details as $detail) {
                    if(!$detail->koli->isEmpty()){
                        foreach ($detail->koli as $koli) {
                            $temp_koli = $koli->nokoli;
                            array_push($node_koli, $temp_koli);
                        }
                    }
                } 
            }
        }


        return collect($node_koli)->unique()->count();
    }

    public function getNpjNoSjId($tipetransaksi)
    {
        $npj = NotaPenjualan::select(['pj.notapenjualan.id','pj.notapenjualan.tipetransaksi','pj.notapenjualandetailkoli.nokoli'])
                              ->leftJoin('pj.notapenjualandetail','pj.notapenjualan.id','=','pj.notapenjualandetail.notapenjualanid')
                              ->leftJoin('pj.notapenjualandetailkoli','pj.notapenjualandetail.id','=','pj.notapenjualandetailkoli.notapenjualandetailid')
                              ->where('pj.notapenjualan.tokoid',$this->tokoid)
                              ->where('pj.notapenjualan.tglproforma',Carbon::parse($this->tglproforma))
                              ->where('pj.notapenjualan.tglprintproforma','!=',null)
                              ->where('pj.notapenjualandetailkoli.nokoli','!=',null)
                              ->where('pj.notapenjualandetailkoli.suratjalanid',null)
                              ->whereRaw("left(pj.notapenjualan.tipetransaksi,1) = '".$tipetransaksi."'")
                              ->distinct()
                              ->get();                    
        return $npj;
    }

    public function getNpjdkNoSjId($tipetransaksi)
    {
        $npjdk = NotaPenjualan::select(['pj.notapenjualandetailkoli.id','pj.notapenjualandetailkoli.nokoli'])
                              ->leftJoin('pj.notapenjualandetail','pj.notapenjualan.id','=','pj.notapenjualandetail.notapenjualanid')
                              ->leftJoin('pj.notapenjualandetailkoli','pj.notapenjualandetail.id','=','pj.notapenjualandetailkoli.notapenjualandetailid')
                              ->where('pj.notapenjualan.tokoid',$this->tokoid)
                              ->where('pj.notapenjualan.tglproforma',Carbon::parse($this->tglproforma))
                              ->where('pj.notapenjualandetailkoli.suratjalanid',null)
                              ->whereRaw("left(pj.notapenjualan.tipetransaksi,1) = '".$tipetransaksi."'")
                              ->get();                    
        return $npjdk;
    }
}
