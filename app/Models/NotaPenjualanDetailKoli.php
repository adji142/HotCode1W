<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NotaPenjualanDetailKoli extends Model
{
    protected $table = 'pj.notapenjualandetailkoli';
    protected $fillable = ['notapenjualandetailid','nokoli','keterangan','createdby','createdon','lastupdatedby','lastupdatedon','suratjalanid'];
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

    public function npjd()
    {
        return $this->belongsTo('App\Models\NotaPenjualanDetail','notapenjualandetailid');
    }
}
