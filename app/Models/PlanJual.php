<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanJual extends Model
{
    //
	protected $table = "pj.planjualsales";
	const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
    protected $guarded = ['id','createdon','lastupdatedon'];


    
}
