<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivaHistory extends Model
{
    //
    protected $table = 'acc.aktivahistory';
    protected $hidden = ['lastupdatedon','createdon'];
    protected $guarded = ['id','createdon','lastupdatedon'];
	const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
}
