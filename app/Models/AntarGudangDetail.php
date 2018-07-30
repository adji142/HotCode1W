<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AntarGudangDetail extends Model
{
    protected $table    = 'stk.antargudangdetail';
    protected $fillable = ['antargudangid','stockid','qtyrq','qtynotaag','qtyterima','catatan','createdby','lastupdatedby'];
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

    public function getCreatedonAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return $value;
        }
    }

    public function barang()
    {
    	return $this->belongsTo('App\Models\Barang','stockid');
    }

    public function ag()
    {
    	return $this->belongsTo('App\Models\AntarGudang','antargudangid');
    }
}
