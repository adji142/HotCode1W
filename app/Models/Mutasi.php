<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Mutasi extends Model
{
    protected $table = 'stk.mutasi';
    protected $fillable = [
      'recordownerid','tglmutasi','nomutasi','keterangan','tipemutasi','isarowid','createdby','createdon','lastupdatedby','lastupdatedon'
    ];
    protected $dates = ['tglmutasi', 'lastupdatedon', 'createdon'];
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';


    public function getTglmutasiAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }

    public function details()
    {
    	return $this->hasMany('App\Models\MutasiDetail','mutasiid');
    }

}
