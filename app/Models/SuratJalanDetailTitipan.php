<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SuratJalanDetailTitipan extends Model
{
    protected $table    = 'pj.suratjalandetailtitipan';
    protected $fillable = ['nokoli','keterangan','createdby','lastupdatedby','suratjalanid'];
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
