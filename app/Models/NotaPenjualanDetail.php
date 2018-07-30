<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NotaPenjualanDetail extends Model
{
    protected $table = 'pj.notapenjualandetail';
    protected $fillable = ['notapenjualanid','stockid','hrgsatuanbrutto','disc1','disc2','ppn','orderpenjualandetailid','qtynota','totaldiscount','hrgsatuannetto','stockgudang','createdby','lastupdatedby'];
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';

    public function barang()
    {
        return $this->hasOne('App\Models\Barang','id','stockid');
    }

    public function orderpenjualandetail()
    {
        return $this->hasOne('App\Models\OrderPenjualanDetail','id','orderpenjualandetailid');
    }

    public function nota()
    {
        return $this->belongsTo('App\Models\NotaPenjualan','notapenjualanid');
    }

    public function koli()
    {
        return $this->hasMany('App\Models\NotaPenjualanDetailKoli','notapenjualandetailid');
    }

    public function getLastupdatedonAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }
}
