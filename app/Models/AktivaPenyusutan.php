<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivaPenyusutan extends Model
{
    //
    protected $table = 'acc.aktivapenyusutan';
    protected $hidden = ['lastupdatedon','createdon'];
	const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
    protected $guarded = ['id','createdon','lastupdatedon'];

    public function Aktiva(){
    	return $this->belongsTo('App\Models\Aktiva','aktivaid');
    }
}
