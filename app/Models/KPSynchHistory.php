<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KPSynchHistory extends Model
{
    protected $table    = 'konsol.kpsynchhistory';
    
    public $timestamps = false;
    public $tag = null;
}
