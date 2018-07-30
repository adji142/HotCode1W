<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivaMutasi extends Model
{
    //
    protected $table = 'acc.aktivamutasi';
    protected $hidden = ['lastupdatedon','createdon'];
    protected $guarded = ['id','createdon','lastupdatedon'];
	const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
   

    public function Aktiva(){
    	return $this->belongsTo('App\Models\Aktiva','aktivaid');
    }
}
