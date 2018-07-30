<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalAccDetail extends Model
{
    //
    protected $table = 'acc.journaldetail';
	const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
    protected $guarded = ['id','createdon','lastupdatedon'];
	protected $hidden = ['lastupdatedon','createdon'];
}
