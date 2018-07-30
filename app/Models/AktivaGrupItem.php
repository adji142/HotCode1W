<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AktivaGrupItem extends Model
{
    //
	protected $table = 'acc.aktivagrupitem';
	const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
	
	public function AktivaJenisItem(){
		return $this->hasMany('App\Models\AktivaJenisItem','groupid');
	} // one to many
	
	
}
