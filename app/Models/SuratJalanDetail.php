<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SuratJalanDetail extends Model
{
    protected $table = 'pj.suratjalandetail';
    protected $fillable = ['notapenjualanid','keterangan','suratjalanid','createdby','lastupdatedby'];
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';

    public function getLastupdatedonAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }

    public function nota()
    {
        return $this->belongsTo('App\Models\NotaPenjualan','notapenjualanid'); 
    }

    public function titipan()
    {
        return $this->hasMany('App\Models\SuratJalanDetailTitipan','suratjalanid','suratjalanid');
    }

    public function pd()
    {
        return $this->hasMany('App\Models\PengirimanDetail','suratjalanid','suratjalanid');
    }
}
