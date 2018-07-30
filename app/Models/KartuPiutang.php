<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KartuPiutang extends Model
{
	protected $table    = 'ptg.kartupiutang';
	//protected $fillable = ['recordownerid','notaid','tokoid','notransaksi','tgltransaksi','tgllink','tgljatuhtempo','nominal','status','createdon','lastupdatedon','createdby','lastupdatedby'];
	const UPDATED_AT    = 'lastupdatedon';
	const CREATED_AT    = 'createdon';

    // free data object
    public $tag = null;

    public function details()
    {
    	return $this->hasMany('App\Models\KartuPiutangDetail','kartupiutangid');
    }
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
