<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KPDetailKonsol extends Model
{
    protected $table    = 'konsol.kpdetail';
    const UPDATED_AT    = 'lastupdatedon';
    const CREATED_AT    = 'createdon';

    public $timestamps = false;
    public $tag = null;
}
