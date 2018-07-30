<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KPKonsol extends Model
{
    protected $table    = 'konsol.kp';
    const UPDATED_AT    = 'lastupdatedon';
    const CREATED_AT    = 'createdon';

    public $timestamps = false;
    public $tag = null;
}
