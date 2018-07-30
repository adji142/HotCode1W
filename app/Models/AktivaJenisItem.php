<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AktivaJenisItem extends Model
{
    //
	protected $table = 'acc.aktivajenisitem';
	const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
	protected $hidden = ['lastupdatedon','createdon'];
	public function AktivaGrupItem(){
		return $this->belongsTo('App\Models\AktivaJenisItem','groupid');
	} //one to many inverse
	
	public function AktivaGolongan(){
		return $this->hasMany('App\Models\AktivaGolongan','jenisitemid');
	} //one to many
	
	public function Aktiva(){
		return $this->hasMany('App\Models\Aktiva','aktivajenisitemid');
	} //one to many
}
