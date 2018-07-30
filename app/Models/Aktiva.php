<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Aktiva extends Model
{
    //
	protected $table = 'acc.aktiva';
	const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
    protected $guarded = ['id','createdon','lastupdatedon'];
	protected $hidden = ['lastupdatedon','createdon'];

	public function AktivaJenisItem(){
		return $this->belongsTo('App\Models\AktivaJenisItem','aktivajenisitemid');
	} // one to many inverse

	public function AktivaJenisGudang(){
		return $this->belongsTo('App\Models\SubCabang','recordownerid');
	} 

	public function AktivaPenyusutan(){
		return $this->hasMany('App\Models\AktivaPenyusutan','aktivaid');
	}

	public function AktivaMutasi(){
		return $this->hasMany('App\Models\AktivaMutasi','aktivaid');
	}

	public function AktivaSaldo(){
		return $this->hasOne('App\Models\AktivaSaldo','aktivaid');
	}

	public function AktivaHistory(){
		return $this->hasMany('App\Models\AktivaHistory','aktivaid');
	}
	
}
