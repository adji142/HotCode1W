<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivaSaldo extends Model
{
    //
    protected $table = 'acc.aktivasaldo';
    protected $hidden = ['lastupdatedon','createdon'];
	const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
    protected $guarded = ['id','createdon','lastupdatedon'];
}
