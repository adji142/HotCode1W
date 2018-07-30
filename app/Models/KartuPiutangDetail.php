<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KartuPiutangDetail extends Model
{
    protected $table = 'ptg.kartupiutangdetail';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
	//protected $fillable = ['kartupiutangid','tgltransaksi','kodetransaksi','nominalbayar','tgljtgiro','uraian','giroid','isarowid','createdon','lastupdatedon','createdby','lastupdatedby'];

    // free data object
    public $tag = null;

    public function setTgltransaksiAttribute($date) 
    {
        if($date){
            $this->attributes['tgltransaksi'] = Carbon::parse($date)->toDateString(); 
        }else{
            $this->attributes['tgltransaksi'] = NULL;
        }
    }

    public function getTgltransaksiAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('d-m-Y');
        }else{
            return $value;
        }
    }
}
