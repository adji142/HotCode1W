<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'hr.karyawan';
    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';

 	// free data object
 	public $tag = null;
}
