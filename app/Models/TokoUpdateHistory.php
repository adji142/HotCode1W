<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TokoUpdateHistory extends Model
{
    protected $table = 'mstr.tokoupdatehistory';
    public $timestamps = false;

    // free data object
    public $tag = null;

}