<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PengirimanDetail extends Model
{
    protected $table    = 'pj.pengirimandetail';
    protected $fillable = ['pengirimanid','suratjalanid','keteranganpending','createdby','lastupdatedby'];
    const UPDATED_AT    = 'lastupdatedon';
    const CREATED_AT    = 'createdon';

    public function getLastupdatedonAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }

    public function sj()
    {
    	return $this->belongsTo('App\Models\SuratJalan','suratjalanid');
    }
}
