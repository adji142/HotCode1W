<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TosFormToko extends Model
{
    protected $table = 'mstr.tosformtoko';

    public $timestamps = false;

    const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'createdon';
    
    // free data object
    public $tag = null;
}
