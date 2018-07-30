<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tokodraft extends Model
{
	protected $primaryKey 	= 'rowid';
	public $incrementing 	= false;
    protected $table    	= 'mstr.tokodraft';
    public $timestamps 		= false;
}
