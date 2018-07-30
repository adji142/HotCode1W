<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AktivaGolongan extends Model
{
    //
	protected $table = 'acc.aktivagolongan';
	const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
	
	public function AktivaJenisItem(){
		return $this->belongsTo('App\Models\AktivaGolongan','jenisitemid');
	} //one to many inverse
}
